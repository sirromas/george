<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Reports
 *
 * @author moyo
 */
class Reports extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_reports_page($userid) {
        $list = "";
        if ($userid == 2) {
            $list.=$this->get_admin_report_page();
        } // end if $userid==2
        else {
            $list.=$this->gpadmin_report_page();
        } // end else
        return $list;
    }

    function get_admin_report_page() {
        $list = "";

        $list.="<br/><div class='row-fluid'>";
        $list.="<span class='span2'><input type='text' id='r_cohort' style='width:125px' placeholder='All Cohorts'></span>";
        $list.="<span class='span2'><input type='text' id='r_practice' style='width:125px' placeholder='All Practices'></span>";
        $list.="<span class='span2'><input type='text' id='r_courses' style='width:125px' placeholder='All Courses'></span>";
        $list.="<span class='span2'><input type='text' id='r_users' style='width:125px;padding-right:5px;' placeholder='All Users'></span>";
        $list.="<span class='span1'><input type='text' id='date1' style='width:75px;'></span>";
        $list.="<span class='span1'><input type='text' id='date2' style='width:75px;'></span>";
        $list.="<span class='span1' style='padding-left:15px;'><button id='report_search'>Search</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='display:none;'>";
        $list.="<span class='span11'><img src='http://mycodebusters.com/assets/img/loader.gif'></span>";
        $list.="</div><br>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span11'><hr/></span>";
        $list.="</div><br>";

        $list.="<div class='row-fluid' id='report_data'></div>";

        return $list;
    }

    function gpadmin_report_page() {
        $list = "";

        return $list;
    }

}
