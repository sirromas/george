<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/class.pdo.database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Completion.php';

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

    function get_user_practiceid($userid) {
        $query = "select * from uk_practice_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $practiceid = $row['practiceid'];
        }
        return $practiceid;
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

    function get_user_rolename($roleid) {
        $query = "select * from uk_role where id=$roleid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['shortname'];
        }
        return $name;
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

    function get_course_context($courseid) {
        $query = "select * from uk_context "
                . "where contextlevel=50 "
                . "and instanceid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contextid = $row['id'];
        }
        return $contextid;
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

    function get_practice_by_admin_userid($userid) {
        $query = "select * from uk_practice where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $practice = new stdClass();
            foreach ($row as $key => $value) {
                $practice->$key = $value;
            }
        }// end hwile
        return $practice;
    }

    function get_practice_groups($userid) {
        $groups = array();
        $query = "select * from uk_groups_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groups[] = $row['groupid'];
        }
        return $groups;
    }

    function get_practice_courses_by_groups($groups) {
        $courses = array();
        foreach ($groups as $groupid) {
            $query = "select * from uk_groups where id=$groupid";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['courseid'] > 0) {
                        if (!in_array($row['courseid'], $courses)) {
                            $courses[] = $row['courseid'];
                        } // end if !in_array($row['courseid'], $courses)
                    } // end if $row['courseid'] > 0
                } // end while
            } // end if $num > 0
        } // end foreach
        return $courses;
    }

    function get_course_categoryid($courseid) {
        $query = "select * from uk_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categoryid = $row['category'];
        }
        return $categoryid;
    }

    function get_course_category_name($id) {
        if ($id > 0) {
            $query = "select * from uk_course_categories where id=$id";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $name = $row['name'];
            }
        } // end if $id > 0
        else {
            $name = 'N/A';
        }
        return $name;
    }

    function is_practice_member($practiceid, $userid) {
        $query = "select * from uk_practice_members "
                . "where practiceid=$practiceid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_practice_users($admin_userid) {
        $users = array();
        $practiceid = $this->get_student_practice($admin_userid);
        $query = "select * from uk_practice_members "
                . "where practiceid=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['userid'];
        }
        return $users;
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

    function get_student_practice($userid) {
        $query = "select * from uk_practice_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $practiceid = $row['practiceid'];
        }
        return $practiceid;
    }

    function get_user_course_last_access($scoid, $userid) {
        if ($scoid > 0) {
            $query = "SELECT * FROM `uk_scorm_scoes_track` "
                    . "WHERE element='x.start.time' "
                    . "and userid=$userid "
                    . "and scoid=$scoid "
                    . "order by value desc "
                    . "limit 0,1";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $lastacccess = $row['value'];
            }
        } // end if $scoid>0
        else {
            $lastacccess = 0;
        } // end else
        return $lastacccess;
    }

    function get_courseid_by_enrolid($enrolid) {
        $query = "select * from uk_enrol where id=$enrolid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    function get_user_courses($userid, $year = NULL) {
        $courses = array();
        $comp = new Completion();

        if ($year == null) {
            $year = date('Y', time());
        }

        $query = "select * from uk_user_enrolments where userid=$userid "
                . "and FROM_UNIXTIME(timestart, '%Y')='$year'";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        //echo "Num: ".$num."<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courseid = $this->get_courseid_by_enrolid($row['enrolid']);
                //echo "Course id: ".$courseid."<br>";
                $scoid = $comp->get_scorm_scoid($courseid);
                $lastccess = $this->get_user_course_last_access($scoid, $userid);
                if ($lastccess > 0) {
                    $courses[$lastccess] = $courseid;
                }
                if (!in_array($courseid, $courses)) {
                    $courses[] = $courseid;
                }
            } // end while
        } // end if $num > 0

        /*
          $query = "select * from uk_role_assignments "
          . "where userid=$userid and roleid=5 "
          . "and FROM_UNIXTIME(timemodified, '%Y')='$year'";
          $num = $this->db->numrows($query);
          if ($num > 0) {
          $result = $this->db->query($query);
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $courseid = $this->get_instance_id($row['contextid']);
          if (!in_array($courseid, $courses)) {
          $scoid = $comp->get_scorm_scoid($courseid);
          $laccess = $this->get_user_course_last_access($scoid, $userid);
          $courses[$laccess] = $courseid;
          } // end if
          } // end while
          } // end if $num > 0
          krsort($courses);
         */

        krsort($courses);
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

    function get_practice_name_by_userid($userid) {
        if ($userid != 2) {
            $query = "select * from uk_practice_members where userid=$userid";
            $result = $this->db->query($query);
            $num = $this->db->numrows($query);
            if ($num > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $practiceid = $row['practiceid'];
                } // end hwile
            } // end if $num > 0
            else {
                $practiceid = 0;
            }
            if ($practiceid > 0) {
                $query = "select * from uk_practice where id=$practiceid";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $ccgname = $row['name'];
                    $names_arr = explode('-', $ccgname);
                    $name = $names_arr[1];
                }
            } // end if $practiceid>0
            else {
                $name = 'N/A';
            }
        } // end if $userid!=2
        else {
            $name = 'Admin Practice';
        } // end else 

        return $name;
    }

    function get_all_users() {
        $users = array();
        $query = "select * from uk_user where deleted=0";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dusers[] = $row['id'];
        }
        if (count($dusers) > 0) {
            foreach ($dusers as $userid) {
                $practicename = $this->get_practice_name_by_userid($userid);
                if ($practicename != 'N/A') {
                    $users[] = $userid;
                } // end if
            } // end foreach
        } // end if count($dusers)>0
        return $users;
    }

    function get_all_courses() {
        $courses = array();
        $query = "select * from uk_course "
                . "where category=20";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['id'];
            } // while
        } // end if $num > 0
        return $courses;
    }

}
