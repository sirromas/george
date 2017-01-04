<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Completion
 *
 * @author moyo
 */
class Completion extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_user_scorm_status($courseid, $userid) {
        
    }

    function get_user_quiz_status($courseid, $userid) {
        
    }

    function get_completed_courses($userid, $date = null) {
        // Temp workaround:
        return rand(1, 10);
    }

    function get_not_completed_courses($userid, $date = null) {
        // Temp workaround:
        return rand(1, 10);
    }

    function get_overdue_courses($userid, $date = null) {
        // Temp workaround:
        return rand(1, 10);
    }

}
