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
        return $roleid;
    }

    function get_user_groups($userid) {
        
    }

    function get_user_cohort($userid) {
        
    }

}
