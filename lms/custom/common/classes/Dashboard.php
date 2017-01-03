<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Dashboard
 *
 * @author moyo
 */
class Dashboard extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_user_dashboard($userid) {
        $list = "";
        $roleid = $this->get_user_role($userid);
        $list.=$this->get_dashboard_by_role($userid, $roleid);
        return $list;
    }

    function get_left_part_of_dashboard($userid, $roleid, $courses) {
        $list = "";
        $rolename = $this->get_role_name($userid, $roleid);
        $username = $this->get_username_by_id($userid);

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' style='font-size:24px;font-weight:bold;'>$username</span>";
        $list.="<span class='span6' style='font-size:24px;color:green;'>($rolename)</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'>As administrator you can create and "
                . "edit users, groups, assign courses, access learning reports "
                . "and certificates for all your staff including yourself.</span>";
        $list.="</div>";

        $list.="<br><div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:18px;font-weight:bold;'>Your practice training summary</span>";
        $list.="</div>";
        $list.="";


        return $list;
    }

    function get_right_part_of_dashboard($userid, $roleid, $courses) {
        $list = "";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:18px;font-weight:bold;'>Your training summary</span>";
        $list.="</div><br>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'>Courses completed this year: <a href='#'>0</a></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'>Courses left to do this year: <a href='#'>0</a></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'>Courses overdue: <a href='#' style='color:red;'>0</a></span>";
        $list.="</div><br>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'><a href='#' style='color:black;'>(Click the numbers to see list of the courses)</a></span>";
        $list.="</div><br>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'><a href='#' style='color:black;'>Download your training report</a></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'><a href='#' style='color:black;'>Download your training certificates</a></span>";
        $list.="</div>";


        return $list;
    }

    function get_dashboard_by_role($userid, $roleid) {
        $list = "";

        $courses = $this->get_user_courses($userid, $roleid);
        $left = $this->get_left_part_of_dashboard($userid, $roleid, $courses);
        $right = $this->get_right_part_of_dashboard($userid, $roleid, $courses);

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>$left</span>";
        $list.="<span class='span6' style='border-left: 1px solid black;'>$right</span>";
        $list.="</div>";

        return $list;
    }

}
