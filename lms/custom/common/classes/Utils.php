<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/class.pdo.database.php';

/**
 * Description of Utils
 *
 * @author moyo
 */
class Utils {

    public $db;
    public $user;
    public $course;
    public $session;
    public $signup_url;

    function __construct() {
        global $USER, $COURSE, $SESSION;
        $db = new pdo_db();
        $this->db = $db;
        $this->user = $USER;
        $this->course = $COURSE;
        $this->session = $SESSION;
        $this->signup_url = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/login/mysignup.php';
    }

    function get_current_user_id() {
        return $this->user->id;
    }

    function get_current_course_id() {
        return $this->course->id;
    }

    function get_context($userid) {
        $context = context_user::instance($userid);
        return $context;
    }

    function get_user_role($userid) {
        $context = $this->get_context($userid);
        if ($userid != 2) {
            $query = "select * from uk_role_assignments "
                    . "where userid=$userid and roleid<>5";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $roleid = $row['roleid'];
                }
            } // end if
            else {
                $roleid = 5;
            }
        } // end if $userid != 2
        else {
            $roleid = 0; // admin user
        } // end else
        return $roleid;
    }

    function get_user_data_by_email($email) {
        $query = "select * from uk_user where email='$email'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
        }
        return $user;
    }

    function get_user_data_by_id($id) {
        $query = "select * from uk_user where id='$id'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
        }
        return $user;
    }

    function get_table_last_id($table) {
        $query = "select * from $table order by id desc limit 0, 1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_course_name($courseid) {
        $query = "select * from uk_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function random_string($length) {
        $pool = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }

    function create_user($user) {
        $encoded_user = base64_encode(json_encode($user));
        $data = array('user' => $encoded_user);

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );

        $context = stream_context_create($options);
        $response = file_get_contents($this->signup_url, false, $context);

        if ($response !== false) {
            return true;
        }  // end if $response !== false        
        else {
            return false;
        }
    }

    function create_random_user() {
        $user = new stdClass();
        $user->firstname = $this->random_string(5);
        $user->lastname = $this->random_string(5);
        $user->email = $this->random_string(5) . "@gmail.com";
        $user->pwd = $this->random_string(8);

        echo "<pre>";
        print_r($user);
        echo "</pre>";
        echo "<br>---------------------------------------------------<br>";


        $encoded_user = base64_encode(json_encode($user));
        $data = array('user' => $encoded_user);

        // 1. Signup user into moodle    
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );

        $context = stream_context_create($options);
        //$response = @file_get_contents($this->signup_url, false, $context);
        $response = file_get_contents($this->signup_url, false, $context);
        print_r($response);

        if ($response !== false) {
            return true;
        }  // end if $response !== false        
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>Signup error happened </span>";
            $list.="</div>";
            echo $list;
            die();
        }
    }

    function get_user_groups($userid) {
        
    }

    function get_user_cohort($userid) {
        $query = "select * from uk_cohort_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['cohortid'];
        }
        return $id;
    }

    function get_user_grouping($userid) {
        $query = "select * from ";
    }

    function get_role_name($userid, $roleid) {
        if ($userid != '2') {
            $query = "select * from uk_role where id=$roleid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $rolename = $row['shortname'];
            }
        } // end if $userid != '2'
        else {
            $rolename = 'Administrator';
        } // end else
        return ucfirst($rolename);
    }

    function get_instance_id($contextid) {
        $query = "select * from uk_context where id=$contextid "
                . "and contextlevel=50";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    function get_user_courses($userid, $year = NULL) {
        $courses = array();

        if ($year == null) {
            $year = date('Y', time());
        }

        $query = "select * from uk_role_assignments "
                . "where userid=$userid and roleid=5 "
                . "and FROM_UNIXTIME(timemodified, '%Y')='$year'";
        //echo "Query: ".$query."<br>";
        //die();
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $this->get_instance_id($row['contextid']);
            } // end while
        } // end if $num > 0

        return $courses;
    }

    function is_email_exists($email) {
        $query = "select * from uk_user where username='$email'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_username_by_id($id) {
        $query = "select * from uk_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $names = $row['firstname'] . "&nbsp;" . $row['lastname'];
        }
        return $names;
    }

}
