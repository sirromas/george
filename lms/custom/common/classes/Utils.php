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

    function __construct() {
        global $USER, $COURSE, $SESSION;
        $db = new pdo_db();
        $this->db = $db;
        $this->user = $USER;
        $this->course = $COURSE;
        $this->session = $SESSION;
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

    function get_user_groups($userid) {
        
    }

    function get_user_cohort($userid) {
        
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
        return $rolename;
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

    function get_user_courses($userid, $roleid) {
        $courses = array();
        if ($userid == 2) {
            // Admin user
            $query = "select * from uk_course";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['id'];
            }
        } // end if $userid == 2
        else {
            $query = "select * from uk_role_assignments "
                    . "where userid=$userid and roleid=5";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $courses[] = $this->get_instance_id($row['contextid']);
                } // end while
            } // end if $num > 0
        } // end else
        return $courses;
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
