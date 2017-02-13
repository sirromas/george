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

    function get_reports_tab() {
        $userid = $this->user->id;
        $list = $this->get_reports_page($userid);
        return $list;
    }

    function get_report_initial_data($userid) {

        $users = array();
        $courses = array();
        $scourses = array();
        //echo "User id: " . $userid . "<br>";

        if ($userid == 2) {
            $courses = $this->get_all_courses();
            $scourses = $this->get_scorm_courses($courses);
            $users = $this->get_all_users();
            $stat = $this->get_courses_stat($users);
            $left = $stat->progress;
            $complete = $stat->compelete;
            $overdue = $stat->overdue;
            $left_pers = round($left / count($scourses));
            $complete_pers = round($complete / count($scourses));
            $overdue_pers = round($overdue / count($scourses));
        } // end if
        else {
            $groups = $this->get_practice_groups($userid);

            /*
              echo "Groups: <pre>";
              print_r($groups);
              echo "</pre><br>------------------------<br>";
             */

            $courses = $this->get_practice_courses_by_groups($groups);
            /*
              echo "Courses: <pre>";
              print_r($courses);
              echo "</pre><br>------------------------<br>";
             */

            $scourses = $this->get_scorm_courses($courses);
            $users = $this->get_practice_users($userid);
            /*
              echo "Users: <pre>";
              print_r($users);
              echo "</pre><br>------------------------<br>";
             */

            $stat = $this->get_courses_stat($users);
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

    function get_category_name($categoryid) {
        $query = "select * from uk_course_categories where id=$categoryid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
        }
        return $name;
    }

    function get_gp_courses_box($courses) {
        $list = "";
        $list.="<select id='r_courses_g' style='width:width:125px'>";
        $list.="<option value='All Courses' selected>All Courses</option>";
        foreach ($courses as $courseid) {
            $categoryid = $this->get_course_categoryid($courseid);
            $categoryname = $this->get_category_name($categoryid);
            $coursename = $this->get_course_name($courseid);
            $name = $categoryname . "-" . $coursename;
            $list.="<option value='$name'>$name</option>";
        } // end foreach
        $list.="</select>";
        return $list;
    }

    function get_gp_users_box($users) {
        $list = "";
        $list.="<select id='r_users_g' style='width:width:125px'>";
        $list.="<option value='All Users' selected>All Users</option>";
        foreach ($users as $userid) {
            $userdata = $this->get_user_data_by_id($userid);
            $name = $userdata->firstname . " " . $userdata->lastname;
            $list.="<option value='$name'>$name</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_report_dashboard($data, $toolbar = true) {
        $list = "";

        $left = $data->left;
        $complete = $data->complete;
        $overdue = $data->overdue;

        $left_side = $this->get_left_summary_block($data);

        if ($toolbar) {
            if ($data->userid == 2) {
                $list.="<br/><div class='row-fluid'>";
                $list.="<span class='span2'><input type='text' id='r_cohort' style='width:125px' placeholder='All Clinical Groups'></span>";
                $list.="<span class='span2'><input type='text' id='r_practice' style='width:125px' placeholder='All Practices'></span>";
                $list.="<span class='span2'><input type='text' id='r_courses' style='width:125px' placeholder='All Courses'></span>";
                $list.="<span class='span2'><input type='text' id='r_users' style='width:125px;padding-right:5px;' placeholder='All Users'></span>";
                $list.="<span class='span1'><input type='text' id='date1' style='width:75px;' placeholder='From'></span>";
                $list.="<span class='span1'><input type='text' id='date2' style='width:75px;' placeholder='To'></span>";
                $list.="<span class='span1' style='padding-left:15px;'><button id='report_search_ad'>Search</button></span>";
                //$list.="<span class='span1'><i style='cursor:pointer;' title='Refresh' class='fa fa-refresh' aria-hidden='true' id='refresh_report'></i></span>";
                $list.="</div>";
            } // end if $data->userid==2
            else {
                $coursesbox = $this->get_gp_courses_box($data->scourses);
                $usersbox = $this->get_gp_users_box($data->users);
                $list.="<br/><div class='row-fluid' style='text-align:center;'>";
                $list.="<span class='span4'>$coursesbox</span>";
                $list.="<span class='span2'>$usersbox</span>";
                $list.="<span class='span1'><input type='text' id='date1' style='width:75px;' placeholder='From'></span>";
                $list.="<span class='span1'><input type='text' id='date2' style='width:75px;' placeholder='To'></span>";
                $list.="<span class='span1' style='padding-left:15px;'><button id='report_search_gp'>Search</button></span>";
                $list.="</div>";
            } // end else 

            $list.="<div class='row-fluid'>";
            $list.="<span class='span12' id='search_err' style='color:red;'></span>";
            $list.="</div>";

            $list.="<div class='row-fluid' style='display:none;text-align:center;' id='report_ajax_loader'>";
            $list.="<span class='span12'><img src='http://mycodebusters.com/assets/img/loader.gif'></span>";
            $list.="</div><br>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span11'><hr/></span>";
            $list.="</div><br>";
        } // end if toolbar

        $list.="<div id='report_data'>";

        $list.="<div class='row-fluid'>";

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
                            text: 'Total'
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
                                format: '{point.y:.1f}'
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

        if (count($data->users) > 0) {
            $list.="<div>";
            $list.="<span class='span12'>";

            $list.="<table id='user_detailes_container' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>User</th>";
            $list.="<th>Clinical Group</th>";
            $list.="<th>Practice</th>";
            $list.="<th><div class='row-fluid'><span class='span6' style='min-height:0px;'>Course</span><span style='min-height:0px;padding-left:13px;' class='span4'>Enroll</span><span style='min-height:0px;padding-left:10px;' class='span2'>Score</span></div></th>";
            $list.="<th>Report</th>";
            $list.="</tr>";
            $list.="</thead>";

            $list.="<tbody>";
            foreach ($data->users as $userid) {
                $userdata = $this->get_user_data_by_id($userid);
                $cohort = $this->get_user_cohort_name($this->get_user_cohort($userid));
                $courses = $this->get_user_courses_block($userid);
                $practice = $this->get_practice_name_by_userid($userid);
                $progress = $this->user_has_non_zero_progress($userid);
                $path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/common/certificates/$userid/report.pdf";
                if (file_exists($path) && $courses != '' && $progress > 0) {
                    $url = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/certificates/$userid/report.pdf";
                    $link = "<form action='$url' method='get' target='_blank' id='report_form_$userid'><a href='#' onClick='return false;' id='get_user_training_report_$userid'>Download</a></form>";
                } // end if file_exists($path)
                else {
                    $link = "N/A";
                } // end else
                $list.="<tr>";
                $list.="<td>$userdata->firstname $userdata->lastname</td>";
                $list.="<td>$cohort</td>";
                $list.="<td>$practice</td>";
                $list.="<td>$courses</td>";
                $list.="<td>$link</td>";
                $list.="</tr>";
            } // end foreach
            $list.="</tbody>";

            $list.="</table>";

            $list.="</span>";
            $list.="</div>";
        } // end if count($data->users)>0
        $list.="</div>";

        return $list;
    }

    function user_has_non_zero_progress($userid) {
        $totalscore = 0;
        $comp = new Completion();
        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $scoid = $comp->get_scorm_scoid($courseid);
                if ($scoid > 0) {
                    $score = $comp->get_student_course_score($scoid, $userid, $courseid, true);
                    if ($score === null) {
                        $score = 0;
                    }
                    $totalscore = $totalscore + $score;
                } // end if $scoid>0        
            } // end foreach
        } // end if count($courses)>0
        return $totalscore;
    }

    function get_passed_course_link($courseid, $coursename, $userid) {
        $list = "";
        $action = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/certificates/$userid/$courseid/certificate.pdf";
        $list.="<form action='$action' method='get' target='_blank' id='data_user_certificates_report_form_$courseid" . "_" . "$userid'>";
        $list.="<a href='#' onClick='return false;' title='Click to get user certificate' id='data_user_report_certificates_$courseid/$userid'>$coursename</a>";
        $list.="</form>";
        return $list;
    }

    function get_user_courses_block($userid) {
        $list = "";
        $comp = new Completion();
        $c = new Courses();
        $courses = array_unique($this->get_user_courses($userid));

        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $scoid = $comp->get_scorm_scoid($courseid);
                $coursename = $this->get_course_name($courseid);
                $date = $c->get_course_enrollment_date($courseid, $userid);
                if ($scoid > 0) {
                    $passgrade = $comp->get_scorm_passing_grade($scoid);
                    $score = $comp->get_student_course_score($scoid, $userid, $courseid, true);
                    if ($score === null) {
                        $score = 0;
                    }
                    if ($score >= $passgrade) {
                        $link = $this->get_passed_course_link($courseid, $coursename, $userid);
                    } // end if $score>=$passgrade
                    else {
                        $link = $coursename;
                    }
                } // end if $scoid>0        
                else {
                    $score = 'N/A';
                } // end else
                $list.="<div class='row-fluid'>";
                $list.="<span class='span6'> $link</span>";
                $list.="<span class='span4'>" . date('m-d-y', $date) . "</span>";
                $list.="<span class='span2'>" . round($score) . "%</span>";
                $list.="</div>";
            } // end foreach
        } // end if count($courses)>0

        return $list;
    }

    function get_user_cohort_name($cohortid) {
        if ($cohortid > 0) {
            $query = "select * from uk_cohort where id=$cohortid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $name = $row['name'];
            }
        } // end if$cohortid>0
        else {
            $name = 'Admin Cohort';
        }
        return $name;
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

        $left = $data->left;
        $complete = $data->complete;
        $overdue = $data->overdue;

        $list.="<div id='report_stat' style='margin-left:0px;'>";

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

        $list.="<br><div class='row-fluid' style=''>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        $list.="<br><div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span6'>Courses summary</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span6'>Total users</span>";
        $list.="<span class='span6'>" . count($data->users) . "</span>";
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

    function search_report_data($s) {
        $list = "";

        /*
          echo "<pre>";
          print_r($s);
          echo "</pre>";
         */

        $cohort = $s->cohort;
        $practice = $s->practice;
        $course = $s->course;
        $user = $s->user;
        $date1 = $s->date1;
        $date2 = $s->date2;
        $current_user = $this->user->id;

        if ($s->src == 'ad') {

            $courses = $this->get_all_courses();
            $scourses = $this->get_scorm_courses($courses);
            $ausers = $this->get_all_users();

            if ($cohort == '' && $practice == '' && $course == '' && $user == '' && trim($date1) == '' && trim($date2) == '') {
                $data = $this->get_report_initial_data($current_user);
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort != '' && $practice == '' && $course == '' && $user == '' && trim($date1) == '' && trim($date2) == '') {
                $cohortid = $this->get_cohortid_by_name($cohort);
                $users = array_unique($this->filter_by_cohort($ausers, $cohortid));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort != '' && $practice == '' && $course == '' && $user == '' && trim($date1) != '' && trim($date2) == '') {
                $cohortid = $this->get_cohortid_by_name($cohort);
                $date2 = date('m/d/y', time());
                $tmp1 = $this->filter_by_cohort($ausers, $cohortid);
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort != '' && $practice == '' && $course == '' && $user == '' && trim($date1) != '' && trim($date2) != '') {

                $cohortid = $this->get_cohortid_by_name($cohort);
                $tmp1 = $this->filter_by_cohort($ausers, $cohortid);
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            // ****************************************************************/

            if ($cohort != '' && $practice != '' && $course == '' && $user == '' && $date1 == '' && $date2 == '') {
                $cohortid = $this->get_cohortid_by_name($cohort);
                $temp1 = $this->filter_by_cohort($ausers, $cohortid);
                $practiceid = $this->get_practiceid_by_name($practice);
                $users = array_unique($this->filter_by_practice($temp1, $practiceid));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort != '' && $practice != '' && $course == '' && $user == '' && $date1 != '' && $date2 == '') {
                $cohortid = $this->get_cohortid_by_name($cohort);
                $temp1 = $this->filter_by_cohort($ausers, $cohortid);
                $practiceid = $this->get_practiceid_by_name($practice);
                $tmp2 = $this->filter_by_practice($temp1, $practiceid);
                $date2 = date('m/d/Y', time());
                $users = array_unique($this->filter_by_dates($tmp2, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort != '' && $practice != '' && $course == '' && $user == '' && $date1 != '' && $date2 != '') {
                $cohortid = $this->get_cohortid_by_name($cohort);
                $temp1 = $this->filter_by_cohort($ausers, $cohortid);
                $practiceid = $this->get_practiceid_by_name($practice);
                $tmp2 = $this->filter_by_practice($temp1, $practiceid);
                $users = array_unique($this->filter_by_dates($tmp2, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort == '' && $practice != '' && $course == '' && $user == '' && $date1 == '' && $date2 == '') {
                $practiceid = $this->get_practiceid_by_name($practice);
                $users = $this->filter_by_practice($ausers, $practiceid);

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort == '' && $practice != '' && $course == '' && $user == '' && $date1 != '' && $date2 == '') {
                $practiceid = $this->get_practiceid_by_name($practice);
                $tmp1 = $this->filter_by_practice($ausers, $practiceid);
                $date2 = date('m/d/Y', time());
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($cohort == '' && $practice != '' && $course == '' && $user == '' && $date1 != '' && $date2 != '') {
                $practiceid = $this->get_practiceid_by_name($practice);
                $tmp1 = $this->filter_by_practice($ausers, $practiceid);
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            // ****************************************************************/

            if ($course != '' && $user == '' && $date1 == '' && $date2 == '') {
                $courseid = $this->get_curseid_by_name($course);
                $users = array_unique($this->filter_by_course($ausers, $courseid));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($course != '' && $user == '' && $date1 != '' && $date2 == '') {
                $courseid = $this->get_curseid_by_name($course);
                $tmp1 = $this->filter_by_course($ausers, $courseid);
                $date2 = date('m/d/Y', time());
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($course != '' && $user == '' && $date1 != '' && $date2 != '') {
                $courseid = $this->get_curseid_by_name($course);
                $tmp1 = $this->filter_by_course($ausers, $courseid);
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            // ****************************************************************/

            if ($user != '') {

                $userid = $this->get_userid_by_name($user);
                $users[] = $userid;

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }
        } // end if $s->src=='ad'
        else {
            // ******** It is GP Admin case *************** //
            $groups = $this->get_practice_groups($current_user);
            $courses = $this->get_practice_courses_by_groups($groups);
            $scourses = $this->get_scorm_courses($courses);
            $ausers = $this->get_practice_users($current_user);

            if ($course == 'All Courses' && ($user == 'All Users' || $user == '' ) && $date1 == '' && $date2 == '') {
                $data = $this->get_report_initial_data($current_user);
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($course != 'All Courses' && $course != '' && ($user == 'All Users' || $user == '' ) && $date1 == '' && $date2 == '') {
                $courseid = $this->get_curseid_by_name($course);
                $users = array_unique($this->filter_by_course($ausers, $courseid));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($course != 'All Courses' && $course != '' && ($user == 'All Users' || $user == '' ) && $date1 != '' && $date2 == '') {
                $courseid = $this->get_curseid_by_name($course);
                $tmp1 = $this->filter_by_course($ausers, $courseid);
                $date2 = date('m/d/Y', time());
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($course != 'All Courses' && $course != '' && ($user == 'All Users' || $user == '' ) && $date1 != '' && $date2 != '') {
                $courseid = $this->get_curseid_by_name($course);
                $tmp1 = $this->filter_by_course($ausers, $courseid);
                $users = array_unique($this->filter_by_dates($tmp1, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($user != 'All Users' && $user != '') {
                $userid = $this->get_userid_by_name($user);
                $users[] = $userid;

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($course == 'All Courses' && ($user == 'All Users' || $user == '' ) && $date1 != '' && $date2 == '') {
                $date2 = date('m/d/Y', time());
                $users = array_unique($this->filter_by_dates($ausers, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }

            if ($course == 'All Courses' && ($user == 'All Users' || $user == '') && $date1 != '' && $date2 != '') {

                $users = array_unique($this->filter_by_dates($ausers, $date1, $date2));

                $stat = $this->get_courses_stat($users);
                $left = $stat->progress;
                $complete = $stat->compelete;
                $overdue = $stat->overdue;
                $left_pers = round($left / count($scourses));
                $complete_pers = round($complete / count($scourses));
                $overdue_pers = round($overdue / count($scourses));

                $data = new stdClass();
                $data->userid = $current_user;
                $data->courses = $courses;
                $data->scourses = $scourses;
                $data->users = $users;
                $data->left = $left;
                $data->complete = $complete;
                $data->overdue = $overdue;
                $data->left_pers = $left_pers;
                $data->complete_pers = $complete_pers;
                $data->overdue_pers = $overdue_pers;
                $list.=$this->get_report_dashboard($data, false);
                return $list;
            }
        } // end else 
    }

    function get_cohortid_by_name($name) {
        $query = "select * from uk_cohort where name='$name'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
            } // end while
        } // end if $num > 0
        else {
            $id = 0;
        } // end else
        return $id;
    }

    function get_practiceid_by_name($name) {
        $query = "select * from uk_practice where name='$name'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
            } // end while
        } // end if $num > 0
        else {
            $id = 0;
        } // end else
        return $id;
    }

    function get_categoryid_by_name($name) {
        $query = "select * from uk_course_categories where name='$name'";
        $result = $this->db->query($query);
        $num = $this->db->numrows($query);
        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
            } // end while
        } // end if $num > 0
        else {
            $id = 0;
        } // end else
        return $id;
    }

    function get_curseid_by_name($name) {
        $names_arr = explode('-', $name);
        $categoryname = $names_arr[0];
        $categoryid = $this->get_categoryid_by_name($categoryname);
        $coursename = $names_arr[1];
        $query = "select * from uk_course where category=$categoryid "
                . "and fullname='$coursename' and visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
            } // end while
        } // end if $num > 0
        else {
            $id = 0;
        } // end else
        return $id;
    }

    function get_userid_by_name($name) {
        $names_arr = explode(' ', $name);
        $total = count($names_arr);
        if ($total == 2) {
            $firstname = $names_arr[0];
            $lastname = $names_arr[1];
        }
        if ($total == 3) {
            $firstname = $names_arr[0] . " " . $names_arr[1];
            $lastname = $names_arr[2];
        }
        if ($firstname != '' && $lastname != '') {
            $query = "select * from uk_user "
                    . "where deleted=0 "
                    . "and firstname like '%$firstname%' "
                    . "and lastname like'%$lastname%'";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['id'];
                } // end while
            } // end if $num > 0
            else {
                $id = 0;
            } // end else
        } // end if $firstname!='' && $lastname!=''
        else {
            $id = 0;
        } // end else
        return $id;
    }

    function filter_by_cohort($src, $cohortid) {
        $users = array();
        if (count($src) > 0) {
            foreach ($src as $userid) {
                $query = "select * from uk_cohort_members "
                        . "where cohortid=$cohortid "
                        . "and userid=$userid";
                $num = $this->db->numrows($query);
                if ($num > 0) {
                    $users[] = $userid;
                } // end if $num > 0
            } // end foreach
        } // end if count($src)>0
        return $users;
    }

    function filter_by_practice($src, $practiceid) {
        $users = array();
        if (count($src) > 0) {
            foreach ($src as $userid) {
                $query = "select * from uk_practice_members "
                        . "where practiceid=$practiceid "
                        . "and userid=$userid";
                $num = $this->db->numrows($query);
                if ($num > 0) {
                    $users[] = $userid;
                } // end if $num > 
            } // end foreach
        } // end if count($src)>0
        return $users;
    }

    function filter_by_course($src, $courseid) {
        $users = array();
        if (count($src) > 0) {
            foreach ($src as $userid) {
                if ($courseid > 0) {
                    $contextid = $this->get_course_context($courseid);
                    $query = "select * from uk_role_assignments "
                            . "where roleid=5 "
                            . "and contextid=$contextid "
                            . "and userid=$userid";
                    //echo "Query: " . $query . "<br>";
                    $num = $this->db->numrows($query);
                    if ($num > 0) {
                        $users[] = $userid;
                    } // end if $num > 
                } // end if $courseid>0
            } // end foreach
        } // end if count($src)>0
        return $users;
    }

    function filter_by_dates($src, $date1, $date2) {
        $users = array();
        $date1u = strtotime($date1);
        $date2u = strtotime($date2) + 86400;
        $c = new Courses();
        if (count($src) > 0) {
            foreach ($src as $userid) {
                $courses = $this->get_user_courses($userid);
                if (count($courses) > 0) {
                    foreach ($courses as $courseid) {
                        $enrollid = $c->get_course_enroll_id($courseid);
                        $query = "select * from uk_user_enrolments "
                                . "where enrolid=$enrollid "
                                . "and userid=$userid "
                                . "and timestart between $date1u and $date2u";
                        //echo "Query: " . $query . "<br>";
                        $num = $this->db->numrows($query);
                        if ($num > 0) {
                            $users[] = $userid;
                        } // end if $num > 
                    } // end foreach
                } // end if count($courses)>0
            } // end foreach
        } // end if count($src)>0
        return $users;
    }

    function filter_by_user($src, $userident) {
        $users = array();
        if (count($src) > 0) {
            foreach ($src as $userid) {
                if ($userid == $userident) {
                    $users[] = $userid;
                } // end if 
            } // end foreach
        } // end if count($src) > 0) 
        return $users;
    }

}
?>

