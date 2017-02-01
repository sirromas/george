<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Completion.php';

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

    function get_left_part_of_dashboard($userid, $roleid, $courses, $c_completed, $c_left, $c_overdue) {
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

        $list.="<br><div class='container-fluid'>";
        $list.="<span class='span12' id='container' style='min-width: 310px; max-width: 800px; height: 220px; margin: 0 auto'></span>";
        $list.="</div>";

        $list.="<script type='text/javascript'>";

        $list.="Highcharts.setOptions({
                colors: ['#058DC7', '#50B432', '#ED561B']});";

        $list.="$(function () {
                Highcharts.chart('container', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: ['']
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Courses Summary'
                        }
                    },
                    legend: {
                        reversed: true
                    },
                    plotOptions: {
                        series: {
                            stacking: 'normal'
                        }
                    },
                    series: [{
                            name: 'Courses completed',
                            data: [$c_completed]
                        }, {
                            name: 'Courses left',
                            data: [$c_left]
                        }, {
                            name: 'Courses overdue',
                            data: [$c_overdue]
                        }]
                        });
                    });";
        $list.="</script>";


        return $list;
    }

    function get_right_part_of_dashboard($userid, $roleid, $courses, $c_completed, $c_left, $c_overdue) {
        $list = "";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:18px;font-weight:bold;'>Your training summary</span>";
        $list.="</div><br>";

        $list.="<div class='container-fluid'>";
        $link = ($c_completed == 0) ? "<span style='color:#058DC7;'>$c_completed</span>" : "<a href='#' style='color:#058DC7;' onClick='return false;' id='completed_stat' data-userid='$userid'>$c_completed</a>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'>Courses completed this year: $link</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $link = ($c_left == 0) ? "<span style='color:#50B432;'>$c_left</span>" : "<a href='#' style='color:#50B432;' onClick='return false;' id='progress_stat' data-userid='$userid'>$c_left</a>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'>Courses left to do this year: $link</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $link = ($c_overdue == 0) ? "<span style='color:#ED561B;'>$c_overdue</span>" : "<a href='#' style='color:#ED561B;' onClick='return false;' id='overdue_stat' data-userid='$userid'>$c_overdue</a>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'>Courses overdue: $link</span>";
        $list.="</div><br>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'>(Click the numbers to see list of the courses)</span>";
        $list.="</div><br>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'><a href='#' onClick='return false;' style='color:black;'>Download your training report</a></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='font-size:14px;font-weight:bold;'><a href='#' onClick='return false;' style='color:black;'>Download your training certificates</a></span>";
        $list.="</div>";

        $list.="<br><div class='container-fluid'>";
        $list.="<span class='span12' id='container2' style='min-width: 310px; max-width: 800px; height: 220px; margin: 0 auto'></span>";
        $list.="</div>";

        $list.="<script type='text/javascript'>";

        $list.="Highcharts.setOptions({
                colors: ['#058DC7', '#50B432', '#ED561B']});";

        $list.="$(function () {
                Highcharts.chart('container2', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: ['']
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Courses Summary'
                        }
                    },
                    legend: {
                        reversed: true
                    },
                    plotOptions: {
                        series: {
                            stacking: 'normal'
                        }
                    },
                    series: [{
                            name: 'Courses completed',
                            data: [$c_completed]
                        }, {
                            name: 'Courses left',
                            data: [$c_left]
                        }, {
                            name: 'Courses overdue',
                            data: [$c_overdue]
                        }]
                        });
                    });";
        $list.="</script>";



        return $list;
    }

    function get_dashboard_by_role($userid, $roleid) {
        $list = "";
        $comp = new Completion();
        $courses = $this->get_user_courses($userid);
        
        $c_completed = $comp->get_student_passed_courses($courses, $userid);
        $c_left = $comp->get_student_progress_courses($courses, $userid);
        $c_overdue = $comp->get_student_overdue_courses($userid);

        //$c_completed = 12;
        //$c_left = 3;
        //$c_overdue = 1;

        $left = $this->get_left_part_of_dashboard($userid, $roleid, $courses, $c_completed, $c_left, $c_overdue);
        $right = $this->get_right_part_of_dashboard($userid, $roleid, $courses, $c_completed, $c_left, $c_overdue);

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>$left</span>";
        $list.="<span class='span6' style='border-left: 1px solid black;'>$right</span>";
        $list.="</div>";

        return $list;
    }

}
