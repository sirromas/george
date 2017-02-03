<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Users.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Courses.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Completion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/classes/Certificate.php';

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
        $data = $this->get_report_initial_data($userid);
        $list.=$this->get_report_dashboard($data);
        return $list;
    }

    function get_report_initial_data($userid) {

        $users = array();
        $courses = array();
        $scourses = array();

        //echo "User id: " . $userid . "<br>";

        if ($userid == 2) {

            $courses = $this->get_all_courses();
            //echo "Courses: " . json_encode($courses);
            //echo "<br>-------------------------<br>";


            $scourses = $this->get_scorm_courses($courses);
            //echo "SCORM courses: " . json_encode($scourses);
            //echo "<br>-------------------------<br>";

            $users = $this->get_all_users();
            //echo "Users: " . json_encode($users);
            //echo "<br>-------------------------<br>";

            $stat = $this->get_courses_stat($users);
            //echo "Stat: " . json_encode($stat);
            //echo "<br>-------------------------<br>";


            $left = $stat->progress;
            $complete = $stat->compelete;
            $overdue = $stat->overdue;

            $left_pers = round($left / count($scourses));
            $complete_pers = round($complete / count($scourses));
            $overdue_pers = round($overdue / count($scourses));
        } // end if
        else {
            $groups = $this->get_practice_groups($userid);
            $courses = $this->get_practice_courses_by_groups($groups);
            //echo "Courses: " . json_encode($courses);
            //echo "<br>-------------------------<br>";

            $scourses = $this->get_scorm_courses($courses);
            //echo "SCORM courses: " . json_encode($scourses);
            //echo "<br>-------------------------<br>";

            $users = $this->get_practice_users($userid);
            //echo "Users: " . json_encode($users);
            //echo "<br>-------------------------<br>";

            $stat = $this->get_courses_stat($users);
            //echo "Stat: " . json_encode($stat);
            //echo "<br>-------------------------<br>";

            $left = $stat->progress;
            $complete = $stat->compelete;
            $overdue = $stat->overdue;

            $left_pers = round($left / count($scourses));
            $complete_pers = round($complete / count($scourses));
            $overdue_pers = round($overdue / count($scourses));
        } // end else

        $data = new stdClass();
        $data->userid = $userid;
        $data->courses = $courses;
        $data->scourses = $scourses;
        $data->users = $users;
        $data->left = $left;
        $data->complete = $complete;
        $data->overdue = $overdue;
        $data->left_pers = $left_pers;
        $data->complete_pers = $complete_pers;
        $data->overdue_pers = $overdue_pers;

        return $data;
    }

    function get_report_dashboard($data) {
        $list = "";

        $left = $data->left_pers;
        $complete = $data->complete_pers;
        $overdue = $data->overdue_pers;

        /*
          $left = 8;
          $complete = 14;
          $overdue = 3;
         */

        $left_side = $this->get_left_summary_block($data);

        if ($data->userid == 2) {
            $list.="<br/><div class='row-fluid'>";
            $list.="<span class='span2'><input type='text' id='r_cohort' style='width:125px' placeholder='All Cohorts'></span>";
            $list.="<span class='span2'><input type='text' id='r_practice' style='width:125px' placeholder='All Practices'></span>";
            $list.="<span class='span2'><input type='text' id='r_courses' style='width:125px' placeholder='All Courses'></span>";
            $list.="<span class='span2'><input type='text' id='r_users' style='width:125px;padding-right:5px;' placeholder='All Users'></span>";
            $list.="<span class='span1'><input type='text' id='date1' style='width:75px;' placeholder='From'></span>";
            $list.="<span class='span1'><input type='text' id='date2' style='width:75px;' placeholder='To'></span>";
            $list.="<span class='span1' style='padding-left:15px;'><button id='report_search'>Search</button></span>";
            $list.="</div>";
        } // end if $data->userid==2
        else {
            $list.="<br/><div class='row-fluid' style='text-align:center;'>";
            $list.="<span class='span2'>&nbsp;</span>";
            $list.="<span class='span2'><input type='text' id='r_courses' style='width:125px' placeholder='All Courses'></span>";
            $list.="<span class='span2'><input type='text' id='r_users' style='width:125px;padding-right:5px;' placeholder='All Users'></span>";
            $list.="<span class='span1'><input type='text' id='date1' style='width:75px;' placeholder='From'></span>";
            $list.="<span class='span1'><input type='text' id='date2' style='width:75px;' placeholder='To'></span>";
            $list.="<span class='span1' style='padding-left:15px;'><button id='report_search'>Search</button></span>";
            $list.="</div>";
        } // end else 

        $list.="<div class='row-fluid' style='display:none;'>";
        $list.="<span class='span11'><img src='http://mycodebusters.com/assets/img/loader.gif'></span>";
        $list.="</div><br>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span11'><hr/></span>";
        $list.="</div><br>";

        $list.="<div class='row-fluid' id='report_summary_data'>";

        $list.="<span class='span3' style='border: 0px solid black;padding:0px;'>$left_side</span>";
        $list.="<span class='span9' style='padding:0px;'><div id='stat_container' style='padding:0px;text-align:left;border: 1px solid black;'></div></span>";

        $list.="</div>";

        $list.="<script type='text/javascript'>";

        $list.="Highcharts.setOptions({
                colors: ['#058DC7', '#50B432', '#ED561B']});";

        $list.="$(function () {
                // Create the chart
                Highcharts.chart('stat_container', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Courses Summary'
                    },

                    xAxis: {
                        type: 'category'
                    },
                    yAxis: {
                        title: {
                            text: 'Total percent'
                        }

                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.1f}%'
                            }
                        }
                    },

                    series: [{
                        name: 'Courses',
                        colorByPoint: true,
                        data: [{
                            name: 'Completed',
                            y: $complete,

                        }, {
                            name: 'Left',
                            y: $left,

                        }, {
                            name: 'Overdue',
                            y: $overdue,

                        }]
                    }]

                });
            });
            ";

        $list.="</script>";

        $list.="<div class='row-fluid' id='report_detailes_data'>";

        $list.="</div>";

        return $list;
    }

    function get_all_users() {
        $users = array();
        $u = new Users();
        $query = "select * from uk_user where deleted=0";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dusers[] = $row['id'];
        }
        if (count($dusers) > 0) {
            foreach ($dusers as $userid) {
                $practicename = $u->get_practice_name_by_userid($userid);
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
                . "where category>0 and visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['id'];
            } // while
        } // end if $num > 0
        return $courses;
    }

    function get_scorm_courses($courses) {
        $scourses = array();
        $comp = new Completion();
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $has_scorm_section = $comp->has_scorm_section($courseid);
                if ($has_scorm_section > 0) {
                    $scourses[] = $courseid;
                } // end if $has_scorm_section>0
            } // end forach
        } // end if count($courses)>0
        return $scourses;
    }

    function get_user_left_courses($courses, $userid) {
        $comp = new Completion();
        $c = $comp->get_student_progress_courses($courses, $userid);
        return $c->total;
    }

    function get_user_complete_courses($courses, $userid) {
        $comp = new Completion();
        $c = $comp->get_student_passed_courses($courses, $userid);
        return $c->total;
    }

    function get_user_overdue_courses($userid) {
        $comp = new Completion();
        $c = $comp->get_student_overdue_courses($userid);
        return $c->total;
    }

    function get_courses_stat($users) {
        $progress = 0;
        $complete = 0;
        $overdue = 0;
        if (count($users) > 0) {
            foreach ($users as $userid) {
                $courses = $this->get_user_courses($userid);
                $left = $this->get_user_left_courses($courses, $userid);
                $done = $this->get_user_complete_courses($courses, $userid);
                $over = $this->get_user_overdue_courses($userid);
                $progress = $progress + $left;
                $complete = $complete + $done;
                $overdue = $overdue + $over;
            } // end foreach
        } // end if count($users)>0
        $stat = new stdClass();
        $stat->progress = $progress;
        $stat->compelete = $complete;
        $stat->overdue = $overdue;
        return $stat;
    }

    function get_left_summary_block($data) {
        $list = "";

        /*
          echo "<pre>";
          print_r($data);
          echo "</pre>";
          die();
         */

        $left = $data->left;
        $complete = $data->complete;
        $overdue = $data->overdue;

        $list.="<div id='report_stat' style='margin-left:25px;'>";

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span6'>System stats: </span>";
        $list.="</div>";

        if ($data->userid == 2) {
            $list.="<div class='row-fluid' style=''>";
            $list.="<span class='span6'>Total courses</span>";
            $list.="<span class='span6'>" . count($data->courses) . "</span>";
            $list.="</div>";

            $list.="<div class='row-fluid' style=''>";
            $list.="<span class='span6'>Scorm courses</span>";
            $list.="<span class='span6'>" . count($data->scourses) . "</span>";
            $list.="</div>";
        } // end if $data->userid==2
        else {
            $list.="<div class='row-fluid' style=''>";
            $list.="<span class='span6'>Total courses</span>";
            $list.="<span class='span6'>" . count($data->scourses) . "</span>";
            $list.="</div>";
        } // end else 

        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span6'>Total users</span>";
        $list.="<span class='span6'>" . count($data->users) . "</span>";
        $list.="</div>";

        $list.="<br><div class='row-fluid' style=''>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        $list.="<br><div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span6'>User courses</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span6'>Total left</span>";
        $list.="<span class='span6'>$left</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span6'>Total completed</span>";
        $list.="<span class='span6'>$complete</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span6'>Total overdue</span>";
        $list.="<span class='span6'>$overdue</span>";
        $list.="</div>";

        $list.="</div>";


        return $list;
    }

}
?>

