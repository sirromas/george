<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Completion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/classes/Certificate.php';

class Courses extends Utils {

    public $scorm_module_id = 18;
    public $path;
    public $data_path;

    function __construct() {
        parent::__construct();
        $this->data_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/data';
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates';
        $this->create_courses_json_data();
    }

    function get_courses_page($userid) {
        $list = "";
        $this->create_duration_items();
        $this->create_user_training_report();
        if ($userid == 2) {
            $roleid = 0;
        } // end if
        else {
            $roleid = $this->get_user_role($userid);
        } // end else
        $list.=$this->get_course_page_by_role($userid, $roleid);
        return $list;
    }

    function get_course_page_by_role($userid, $roleid) {
        $list = "";
        switch ($roleid) {
            case 0:
                $list.=$this->get_admin_page($userid);
                break;
            case 5:
                $list.=$this->get_student_page($userid);
                break;
            case 9:
                $list.=$this->get_ccg_page($userid);
                break;
            case 10:
                $list.=$this->get_gp_page($userid);
                break;
        }
        return $list;
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

    function get_course_detailes($id) {
        $query = "select * from uk_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $c = new stdClass();
            foreach ($row as $key => $value) {
                $c->$key = $value;
            }
            $c->catname = $this->get_course_category_name($row['category']);
        }
        return $c;
    }

    function get_courses_progress() {
        $progress = array();
        $userid = $this->user->id;
        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $stat = $this->get_course_progress($courseid, $userid);
                $pr = new stdClass();
                $pr->courseid = $courseid;
                $pr->stat = json_encode($stat);
                $progress[] = $pr;
            } // end foreach
        } // end if count($courses)>0
        //return json_encode($courses);
        return json_encode($progress);
    }

    function get_course_progress($courseid, $userid) {
        $comp = new Completion();
        $scoid = $comp->get_scorm_scoid($courseid);
        if ($scoid > 0) {
            $passgrade = $comp->get_scorm_passing_grade($scoid);
            $stat = $comp->get_student_course_score($scoid, $userid, $courseid, TRUE);
            $progress = ($stat >= $passgrade) ? 100 : $stat;
        } // end if $scoid
        else {
            $progress = 0;
        }
        return $progress;
    }

    function get_course_context($courseid) {
        $query = "select * from uk_context "
                . "where contextlevel=50 "
                . "and instanceid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_user_enrollment_date($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $query = "select * from uk_role_assignments "
                . "where roleid=5 and "
                . "contextid=$contextid and "
                . "userid=$userid ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['timemodified'];
        }
        return $date;
    }

    function get_course_duration($courseid, $userid) {
        if ($userid == 2) {
            $query = "select * from uk_course_duration where courseid=$courseid";
        } // end if $userid==2
        else {
            $practiceid = $this->get_student_practice($userid);
            $query = "select * from uk_practice_course_duration "
                    . "where practiceid=$practiceid "
                    . "and courseid=$courseid";
        }
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $duration = $row['duration'];
        }
        return $duration;
    }

    function get_course_policy($courseid) {
        $list = "";
        $query = "select * from uk_course_policies where courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/policies/$courseid/" . $row['path'] . "' target='_blank'>Download</a>";
            } // end while
        } // end if $num > 0
        else {
            $list.="N/A";
        } // end else

        return $list;
    }

    function get_user_course_certificate($courseid, $userid) {
        $list = "";
        $query = "select * from uk_course_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<a href='http://" . $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/common/certificates/$courseid/certificate.pdf' target='_blank'>Download</a>";
            } // end while
        } // end if $num > 0
        else {
            $list.="N/A";
        } // end else

        return $list;
    }

    function get_user_course_scorm_progress($courseid, $userid) {
        
    }

    function get_user_course_quiz_progress($courseid, $userid) {
        
    }

    function get_user_passed_course_date($courseid, $userid) {
        $list = "";
        $scorm_status = $this->get_user_course_scorm_progress($courseid, $userid);
        $quiz_status = $this->get_user_course_quiz_progress($courseid, $userid);

        if ($scorm_status == 100 && $quiz_status >= 80) {
            $list.=""; // here should be quiz passed date
        } // end if $scorm_status == 100 && $quiz_status >= 80
        else {
            $list.="N/A";
        }
        return $list;
    }

    function get_my_course_details($c) {

        /*
         * 
          echo "<pre>";
          print_r($c);
          echo "</pre>";
          die();
         * 
         */
        $comp = new Completion();
        $enr_date = $this->get_user_enrollment_date($c->courseid, $c->userid);
        $duration = $this->get_course_duration($c->courseid, $c->userid); // months
        $due_date = $enr_date + (86400 * 30 * $duration);
        $enr_h_date = date('m-d-Y', $enr_date);
        $due_h_date = date('m-d-Y', $due_date);

        $course = $this->get_course_detailes($c->courseid);
        $status = $this->get_course_progress($c->courseid, $c->userid);
        $policy = $this->get_course_policy($c->courseid);
        $certificate = $comp->get_course_detailes_certificate_link($c->courseid);


        $list.="<div id='myModal' class='modal fade' style='overflow: visible;margin-left:0px;left:29%;min-height:375px'>
        <div class='modal-dialog' style='background-color:none;margin-left:0px;'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Course Details</h4>
                </div>

                <div class='modal-body' style=''>
                
                <input type='hidden' id='courseid' value='$c->courseid'>
                <input type='hidden' id='userid' value='$c->userid'>
                    
                <table style='padding-left:15px;' border='0' align='center'>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px;'>Name</td><td style='padding-left:15px;padding-right:15px'>$course->fullname</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Length</td><td style='padding-left:15px;padding-right:15px'>60 minutes</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Provider</td><td style='padding-left:15px;padding-right:15px'>Practice Index</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Duration</td><td style='padding-left:15px;padding-right:15px'>$duration (month)</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Progress</td><td style='padding-left:15px;padding-right:15px'>$status %</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Policy</td><td style='padding-left:15px;padding-right:15px'>$policy</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Certificate</td><td style='padding-left:15px;padding-right:15px'>$certificate</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Notes</td><td style='padding-left:15px;padding-right:15px'>$course->summary</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Enroll date</td><td style='padding-left:15px;padding-right:15px'>$enr_h_date</td>
                </tr>
                
                <tr>
                <td style='padding-left:15px;padding-right:15px'>Due date</td><td style='padding-left:15px;padding-right:15px'>$due_h_date</td>
                </tr>
                
                <tr>
                <td style='padding:15px;' colspan='2' align='center'><button type='button'  data-dismiss='modal' id='cancel'>OK</button></td>
                </tr>

                </table>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function is_duration_item_exists($courseid) {
        $query = "select * from uk_course_duration where courseid=$courseid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function create_duration_items() {
        $query = "select * from uk_course";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $status = $this->is_duration_item_exists($row['id']);
            if ($status == 0) {
                $query = "insert into uk_course_duration "
                        . "(courseid) values (" . $row['id'] . ")";
                $this->db->query($query);
            } // end if
        } // end while 
    }

    function get_year_selection_box($year = null) {
        if ($year == null) {
            $year = date('Y', time());
        }

        $list = "";
        $list.="<select id='my_courses_year_selection_box' style=''>";
        for ($i = 2014; $i <= 2035; $i++) {
            if ($i == $year) {
                $list.="<option value='$i' selected>$i</option>";
            } // end if
            else {
                $list.="<option value='$i'>$i</option>";
            } // end else
        }
        $list.="";
        $list.="";
        $list.="";
        $list.="</select>";

        return $list;
    }

    function get_course_by_year($userid, $year) {
        $list = "";
        $courses = $this->get_user_courses($userid, $year);
        $list.=$this->get_my_courses_block($courses, $userid, false);
        return $list;
    }

    function get_course_scorm_link($courseid) {
        $link = "";
        $comp = new Completion();
        $userid = $this->user->id;
        $query = "select * from uk_course_modules "
                . "where course=$courseid "
                . "and module=$this->scorm_module_id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cmid = $row['id']; // it is SCORM module id
        }

        if ($cmid > 0) {
            $query = "select * from uk_scorm where course=$courseid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $scormid = $row['id'];
                $launchid = $row['launch']; // it is launcher id
            }

            $query = "select * from uk_scorm_scoes "
                    . "where scorm=$scormid "
                    . "and organization<>''";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $organization = $row['organization'];
            }

            $scormstatus = $comp->has_scorm_activity($courseid, $userid);
            $caption = ($scormstatus == 0) ? 'Start' : 'Continue';

            $link.="<form id='scormviewform' method='post' action='http://" . $_SERVER['SERVER_NAME'] . "/lms/mod/scorm/player.php'>
                <input type='hidden' name='mode' value='normal'>
                <input type='hidden' name='newattempt' value='on'>
                <input type='hidden' name='scoid' value='$launchid'>
                <input type='hidden' name='cm' value='$cmid'>
                <input type='hidden' name='currentorg' value='$organization'>
                <input type='submit' value='$caption'>
                </form>";
        } // end if $cmid > 0
        //$link = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/mod/scorm/view.php?id=$id' target='_blank'>Continue >></a>";
        return $link;
    }

    function get_my_courses_block($courses, $userid, $toolbar = true) {
        $list = "";

        $year_box = $this->get_year_selection_box();
        $list.="<input type='hidden' id='userid' value='$userid'>";
        if ($toolbar) {
            if ($userid != 2) {
                $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
                $list.="<span class='span2'>My Courses</span>";
                $list.="<span class='span2'>$year_box</span>";
                $list.="</div>";
            } // end if
            else {
                $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
                $list.="<span class='span2'>My Courses</span>";
                $list.="<span class='span2'>$year_box</span>";
                $list.="<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/course/management.php' target='_blank'><button style='width:147px;'>Manage courses</button></a></span>";
                $list.="</div>";
            }
        }

        if (count($courses) > 0) {

            $list.="<div id='my_courses_container' style=''>";
            $list.="<table id='my_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Course name</th>";
            $list.="<th>Duration</th>";
            $list.="<th>Enrollment date</th>";
            $list.="<th>Due date</th>";
            $list.="<th>My Progress</th>";
            $list.="<th>Actions</th>";
            $list.="</tr>";
            $list.="</thead>";

            $list.="<tbody>";

            foreach ($courses as $courseid) {
                //echo "Current courseid: " . $courseid . "<br>";
                $course_data = $this->get_course_detailes($courseid);
                $progress = $this->get_course_progress($courseid, $userid);
                $enroll_date = date('m-d-Y', $this->get_user_enrollment_date($courseid, $userid));
                $enr_date = $this->get_user_enrollment_date($courseid, $userid);
                $duration = $this->get_course_duration($courseid, $userid); // months
                $due_date = $enr_date + (86400 * 30 * $duration);
                $due_h_date = date('m-d-Y', $due_date);
                $link = $this->get_course_scorm_link($courseid);

                /*
                  echo "<pre>";
                  print_r($course_data);
                  echo "</pre><br>";
                  echo "Enroll date: " . $enroll_date . "<br>";
                  echo "Expiration date: " . $due_h_date . "<br>";
                  echo "Link: " . $link . "<br>";
                 */

                $list.="<tr>";
                $list.="<td><a href='#' onClick='return false;' title='Click to get details' id='my_course_title_$courseid' data-userid='$userid'>$course_data->fullname</a></td>";
                $list.="<td>60 minutes</td>";
                $list.="<td>$enroll_date</td>";
                $list.="<td>$due_h_date</td>";
                $list.="<td><div id='pr_$courseid' title='Progress: $progress%'></div></td>";
                $list.="<td>$link</td>";
                $list.="</tr>";
            } // end foreach

            $list.="</tbody>";
            $list.="</table>";
            $list.="</div>";
        } // end if count($courses)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span3'>You are not enrolled into any course</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_all_system_courses_block() {
        $list = "";

        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
        $list.="<span class='span2'>All system courses</span>";
        $list.="</div>";

        $list.="<table id='all_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course Category</th>";
        $list.="<th>Course Name</th>";
        $list.="<th>Actions</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";

        $query = "select * from uk_course where id<>1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $catname = $this->get_course_category_name($row['category']);
                $list.="<tr>";
                $list.="<td>$catname</td>";
                $list.="<td>" . $row['fullname'] . "</td>";
                $list.="<td><span class='col1'><a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/course/view.php?id=" . $row['id'] . "' target='_blank' target='_blank'>View</a></span></td>";
                $list.="</tr>";
            } // end while
        } // end if $num > 0

        $list.="</tbody>";

        $list.="</table>";

        //$list.="</div></div>"; // end of panel

        return $list;
    }

    function get_admin_page($userid) {
        $list = "";

        $courses = $this->get_user_courses($userid);
        $mycourses = $this->get_my_courses_block($courses, $userid);
        $allcourses = $this->get_all_system_courses_block();
        $list.=$mycourses;
        $list.=$allcourses;

        return $list;
    }

    function get_student_page($userid) {
        $list = "";
        $courses = $this->get_user_courses($userid);
        $list.=$this->get_my_courses_block($courses, $userid);
        return $list;
    }

    function get_practice_courses_block($userid) {
        $list = "";
        $courses = array();
        $groups = $this->get_practice_groups($userid);
        foreach ($groups as $groupid) {
            $query = "select * from uk_groups where id=$groupid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['courseid'];
            }
        }
        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
        $list.="<span class='span2'>Practice courses</span>";
        $list.="</div>";

        $list.="<table id='all_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course Category</th>";
        $list.="<th>Course Name</th>";
        //$list.="<th>Actions</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";
        foreach ($courses as $courseid) {
            if ($courseid > 0) {
                $catname = $this->get_course_category_name($this->get_course_categoryid($courseid));
                $name = $this->get_course_name($courseid);
                $list.="<tr>";
                $list.="<td>$catname</td>";
                $list.="<td>" . $name . "</td>";
                //$list.="<td><span class='col1'><a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/course/view.php?id=" . $courseid . "' target='_blank' target='_blank'>View</a></span></td>";
                $list.="</tr>";
            } // end if 
        } // end foreach
        $list.="</tbody>";

        $list.="</table>";

        return $list;
    }

    function get_gp_page($userid) {
        $list = "";
        $courses = $this->get_user_courses($userid);
        $mycourses = $this->get_my_courses_block($courses, $userid);
        $practicecourses = $this->get_practice_courses_block($userid);
        $list.=$mycourses;
        $list.=$practicecourses;
        return $list;
    }

    function get_personal_external_training_courses($userid) {
        $list = "";

        $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
        $list.="<span class='span3'>My External Training</span>";
        $list.="<input type='hidden' id='external_training_userid' value='$userid'>";
        $list.="</div>";

        $query = "select * from uk_external_training where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $list.="<table id='my_external_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Course Name</th>";
            $list.="<th>Provider</th>";
            $list.="<th>Date</th>";
            $list.="<th>Duration</th>";
            $list.="<th>Status</th>";
            $list.="<th>User</th>";
            $list.="<th>Notes</th>";
            $list.="<th>Actions</th>";
            $list.="</tr>";
            $list.="</thead>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $username = $this->get_username_by_id($row['userid']);
                $status = $this->get_status_text($row['status']);
                $list.="<tbody>";
                $list.="<tr>";
                $list.="<td>" . $row['name'] . "</td>";
                $list.="<td>" . $row['provider'] . "</td>";
                $list.="<td>" . date('m/d/Y', $row['cdate']) . "</td>";
                $list.="<td>" . $row['duration'] . "&nbsp;&nbsp;(month)</td>";
                $list.="<td>" . $status . "</tdd>";
                $list.="<td>" . $username . "</td>";
                $list.="<td>" . $row['notes'] . "</td>";
                $list.="<td><a style='cursor:pointer;' onClick='return false;' id='update_external_course_$id'>Update Status</a></td>";
                $list.="</tr>";
                $list.="</tbody>";
            } // end while
            $list.="</table>";

            $list.= "<div class='container-fluid'>";
            $list.="<input type='hidden' id='external_userid' value='$userid'>";
            $list.="</div>";
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span9'><button id='add_ext_training'>Add Course</button></span>";
            $list.="</div>";
        } // end if $num > 0
        else {
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span9'>There are no external courses you enrolled</span>";
            $list.="<input type='hidden' id='external_userid' value='$userid'>";
            $list.="</div>";
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span9'><button id='add_ext_training'>Add Course</button></span>";
            $list.="</div>";
        } // end else
        //$list.="</div></div>";

        if ($userid == 2) {
            // It is admin - get all external courses
            $list2 = $this->get_all_external_training_courses();
        } // end if $userid==2
        else {

            $roleid = $this->get_user_role($userid);

            switch ($roleid) {
                case 5:
                    $list2 = "";
                    break;
                case 9:
                    $cohortid = $this->get_user_cohort($userid);
                    $list2 = $this->get_ccg_external_training($cohortid);
                    break;
                case 10:
                    $list2 = $this->get_gp_external_training($userid);
                    break;
            } // end of switch
        } // end else

        $list.=$list2;
        return $list;
    }

    function get_course_duration_retake_box($id = null) {
        $list = "";
        $list.="<select id='ext_course_retake_duration'>";
        if ($id == null) {
            $list.="<option value='0' selected>Please select</option>";
            for ($i = 1; $i <= 36; $i++) {
                $list.="<option value='$i'>$i</option>";
            } // end for
        } // end if $id == null
        else {
            for ($i = 1; $i <= 36; $i++) {
                if ($i == $id) {
                    $list.="<option value='$i' selected>$i</option>";
                } // end if $i==$id
                else {
                    $list.="<option value='$i'>$i</option>";
                } // end else 
            } // end for
        }
        $list.="</select>";

        return $list;
    }

    function update_external_course_status($course) {
        $query = "update uk_external_training "
                . "set status='$course->duration' "
                . "where id=$course->courseid";
        $this->db->query($query);
    }

    function get_external_status_label($id) {
        switch ($id) {
            case 1:
                $label = "Not started yet";
                break;
            case 2:
                $label = "In progress";
                break;
            case 3:
                $label = "Complete";
                break;
        }
        return $label;
    }

    function get_external_coure_status_box($id = null) {
        $list = "";

        $list.="<select id='ext_course_status'>";
        if ($id == null) {
            $list.="<option value='0' selected>Please select</option>";
            $list.="<option value='1'>Not started yet</option>";
            $list.="<option value='2'>In progress</option>";
            $list.="<option value='3'>Complete</option>";
        } // end if 
        else {
            for ($i = 1; $i <= 3; $i++) {
                $status = ($i == $id) ? 'selected' : '';
                $label = $this->get_external_status_label($i);
                $list.="<option value='$i' $status>$label</option>";
            }
        }
        $list.="</select>";

        return $list;
    }

    function get_status_text($id) {
        switch ($id) {
            case "1":
                $status = 'Not started yet';
                break;
            case "2":
                $status = 'In progress';
                break;
            case "3":
                $status = 'Complete';
                break;
        }
        return $status;
    }

    function get_add_external_training_dialog($userid) {
        $list = "";
        $duration_box = $this->get_course_duration_retake_box();
        $status_box = $this->get_external_coure_status_box();
        $list.="<div id='myModal' class='modal fade' style='transparent;overflow: visible;width:875px;margin-left:0px;left:15%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width:875px;height:500px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add External Training</h4>
                </div>
                <div class='modal-body' style=''>
                <input type='hidden' id='userid' value='$userid'>
                <div class='container-fluid'>
                <span class='span1'>Name*</span>
                <span class='span6'><input type='text' id='name' style='width:670px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Provider*</span>
                <span class='span6'><input type='text' id='provider' style='width:670px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Date*</span>
                <span class='span6'><input type='text' id='ext_course_date' style=''></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Duration*</span>
                <span class='span6'>$duration_box</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Status*</span>
                <span class='span6'>$status_box</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Notes</span>
                <span class='span6'><textarea id='ext_notes' style='width:670px;' rows='5'></textarea></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='ext_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='add_external_course_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_update_external_training_dialog($courseid) {

        $query = "select * from uk_external_training where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['status'];
        }
        $status_box = $this->get_external_coure_status_box($status);

        $list.="<div id='myModal' class='modal fade' style='transparent;overflow: visible;width:675px;margin-left:0px;left:23%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style=';'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Update External Course Status</h4>
                    <input type='hidden' id='external_training_courseid_to_update' value='$courseid'>
                </div>
                <div class='modal-body'>
                <div class='container-fluid' style='text-align:center;'>
                <span class='span1'>Status</span>
                <span class='span3'>$status_box</span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='update_external_course_status'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_add_course_category_dialiog() {
        $list = "";

        $list.="<div id='myModal' class='modal fade' style='border:none;background-color:transparent;overflow: visible;width:875px;margin-left:0px;left:5%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width:875px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Course Category</h4>
                </div>
                <div class='modal-body' style=''>
                <div class='container-fluid'>
                <span class='span1'>Name*</span>
                <span class='span6'><input type='text' id='catname' style='width:670px;'></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='cat_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='add_course_category_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_upload_policy_dialog($courseid) {
        $list = "";

        $list.="<div id='myModal' class='modal fade' style='width:825px;margin-left:0px;left:18%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width:825px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Upload policy to course</h4>
                </div>
                <input type='hidden' id='policy_course' value='$courseid'>
                <div class='modal-body' style=''>
                
                <div class='container-fluid'>
                <span class='span1'>Title*</span>
                <span class='span6'><input type='text' id='policy_title' style='width:600px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Summary</span>
                <span class='span6'><input type='textarea' id='policy_summary' style='width:570px;height:45px;'></textarea></span>
                </div>
                
                <div class='container-fluid' style='padding-top:15px;'>
                <span class='span1'>File*</span>
                <span class='span6'>
                <label class='btn btn-default btn-file'>
                    Browse <input type='file' id='policy_file' style='display: none;'>
                </label>
                </span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='policy_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='add_policy_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_course_category($name) {

        $query = "insert into uk_course_categories "
                . "(name, "
                . "timemodified, "
                . "depth, descriptionformat,"
                . "path) values('$name', '" . time() . "', '1', '1', '1')";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);

        $query = "select * from uk_course_categories order by id desc limit 0,1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $lastid = $row['id'];
        }
        $path = "/$lastid";

        $query = "update uk_course_categories set path='$path' where id=$lastid";
        $this->db->query($query);
    }

    function add_external_course($course) {
        $cdate = strtotime($course->date);
        $query = "insert into uk_external_training "
                . "(name,"
                . "provider,"
                . "cdate,"
                . "duration, "
                . "status,"
                . "notes,"
                . "userid) "
                . "values('$course->name',"
                . "'$course->provider',"
                . "'$cdate',"
                . "'$course->retake_duration', "
                . "'$course->status',"
                . "'$course->notes',"
                . "'$course->userid')";
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

    function get_ccg_external_training($cohortid) {
        $list = "";

        return $list;
    }

    function get_gp_external_training($userid) {
        $list = "";
        $not_started = 0;
        $progress = 0;
        $complete = 0;
        $courses = array();

        $practice_users_arr = $this->get_practice_users($userid);
        $practice_users_list = implode(',', $practice_users_arr);

        //echo $practice_users_list ."<br>";

        $query = "select * from uk_external_training "
                . "where userid in ($practice_users_list) ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course = new stdClass();
                foreach ($row as $key => $value) {
                    $course->$key = $value;
                }
                $courses[] = $course;
            }
        } // end if $num > 0

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        $list.= "<div class='container-fluid' style='font-weight:bold;'>";
        $list.="<span class='span12'>Practice External Training</span>";
        $list.="</div>";

        if (count($courses) > 0) {

            $list.="<table id='all_external_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Course Name</th>";
            $list.="<th>Provider</th>";
            $list.="<th>Date</th>";
            $list.="<th>Duration</th>";
            $list.="<th>Status</th>";
            $list.="<th>User</th>";
            $list.="<th>Notes</th>";
            $list.="</tr>";
            $list.="</thead>";
            $list.="<tbody>";

            foreach ($courses as $courseObj) {
                $username = $this->get_username_by_id($courseObj->userid);
                $status = $this->get_status_text($courseObj->status);

                switch ($courseObj->status) {
                    case "1":
                        $not_started++;
                        break;
                    case "2":
                        $progress++;
                        break;
                    case "3":
                        $complete++;
                        break;
                }

                $list.="<tr>";
                $list.="<td>" . $courseObj->name . "</td>";
                $list.="<td>" . $courseObj->provider . "</td>";
                $list.="<td>" . date('m/d/Y', $courseObj->cdate) . "</td>";
                $list.="<td>" . $courseObj->duration . "&nbsp;&nbsp;(month)</td>";
                $list.="<td>" . $status . "</td>";
                $list.="<td>" . $username . "</td>";
                $list.="<td>" . $courseObj->notes . "</td>";
                $list.="</tr>";
            } // end foreach
            $list.="</tbody>";
            $list.="</table>";

            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>Total Not Started</span>";
            $list.="<span class='span1'>$not_started</span>";
            $list.="<span class='span2'>Total In Progress</span>";
            $list.="<span class='span1'>$progress</span>";
            $list.="<span class='span2'>Total Complete</span>";
            $list.="<span class='span1'>$complete</span>";
            $list.="</div>";
        } // end if count($courses)>0
        else {
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span12'>There are no external courses inside practice</span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_all_external_training_courses() {
        $not_started = 0;
        $progress = 0;
        $complete = 0;
        $list = "";

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        $query = "select * from uk_external_training order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {

            $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
            $list.="<span class='span3'>All External Training</span>";
            $list.="</div>";

            $list.="<table id='all_external_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Course Name</th>";
            $list.="<th>Provider</th>";
            $list.="<th>Date</th>";
            $list.="<th>Duration</th>";
            $list.="<th>Status</th>";
            $list.="<th>User</th>";
            $list.="<th>Notes</th>";
            $list.="</tr>";
            $list.="</thead>";
            $list.="<tbody>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $username = $this->get_username_by_id($row['userid']);
                $status = $this->get_status_text($row['status']);

                switch ($row['status']) {
                    case "1":
                        $not_started++;
                        break;
                    case "2":
                        $progress++;
                        break;
                    case "3":
                        $complete++;
                        break;
                }

                $list.="<tr>";
                $list.="<td>" . $row['name'] . "</td>";
                $list.="<td>" . $row['provider'] . "</td>";
                $list.="<td>" . date('m/d/Y', $row['cdate']) . "</td>";
                $list.="<td>" . $row['duration'] . "&nbsp;&nbsp;(month)</td>";
                $list.="<td>" . $status . "</td>";
                $list.="<td>" . $username . "</td>";
                $list.="<td>" . $row['notes'] . "</td>";
                $list.="</tr>";
            } // end while
            $list.="</tbody>";
            $list.="</table>";

            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>Total Not Started</span>";
            $list.="<span class='span1'>$not_started</span>";
            $list.="<span class='span2'>Total In Progress</span>";
            $list.="<span class='span1'>$progress</span>";
            $list.="<span class='span2'>Total Complete</span>";
            $list.="<span class='span1'>$complete</span>";
            $list.="</div>";
        } // end if $num > 0
        else {
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span12'><hr/></span>";
            $list.="</div>";
            $list.= "<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span12'>All External Training</span>";
            $list.="</div>";
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span12'>There are no external courses in the system</span>";
            $list.="</div>";
        } // end else
        //$list.="</div></div>";

        return $list;
    }

    function get_repeat_training_page($userid) {
        $list = "";
        if ($userid == 2) {
            $list.=$this->get_admin_repeat_training_page($userid);
        } // end if $userid==2
        else {
            $roleid = $this->get_user_role($userid);
            if ($roleid == 5) {
                $list.=$this->get_student_repeat_training_page($userid);
            }

            if ($roleid == 10) {
                $list.=$this->get_gpadmin_repeat_training_page($userid);
            }
        } // end else

        return $list;
    }

    function get_student_repeat_training_page($userid) {
        $list = "";
        $courses = $this->get_user_courses($userid);
        $practiceid = $this->get_student_practice($userid);

        $list.= "<div class='container-fluid' style='font-weight:bold;'>";
        $list.="<span class='span12'>Repeated courses</span>";
        $list.="</div>";

        if (count($courses) > 0) {

            $list.="<table id='repeat_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Course Category</th>";
            $list.="<th>Course Name</th>";
            $list.="<th>Repeat Duration</th>";
            $list.="</tr>";
            $list.="</thead>";

            $list.="<tbody>";

            foreach ($courses as $courseid) {
                $catname = $this->get_course_category_name($this->get_course_categoryid($courseid));
                $coursename = $this->get_course_name($courseid);
                $duration = $this->get_student_course_duration_info($practiceid, $courseid);
                $list.="<tr>";
                $list.="<td>$catname</td>";
                $list.="<td>" . $coursename . "</td>";
                $list.="<td align='center'><span class='col3' style='text-align:center;padding-left:0px;'>$duration &nbsp;&nbsp;(month)</span></td>";
                $list.="</tr>";
            }

            $list.="</tbody>";

            $list.="</table>";
        } // end if count($courses)>0
        else {
            $list.="<tr>";
            $list.="<td colspan='5'>You are not enrolled into any course</td>";
            $list.="</tr>";
        } // end else 

        return $list;
    }

    function get_student_course_duration_info($practiceid, $courseid) {
        $query = "select * from uk_practice_course_duration "
                . "where practiceid=$practiceid "
                . "and courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $duration = $row['duration'];
        }
        return $duration;
    }

    function get_course_repeat_box($courseid) {
        $list = "";
        $list.="<select id='course_duration_$courseid'>";
        $query = "select * from uk_course_duration where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $db_duration = $row['duration'];
        }
        for ($i = 1; $i <= 36; $i++) {
            if ($i == $db_duration) {
                $list.="<option value='$i' selected>$i</option>";
            } // end if $i==$db_duration
            else {
                $list.="<option value='$i'>$i</option>";
            } // end else
        }
        $list.="</select>";
        return $list;
    }

    function get_practice_course_repeat_box($practiceid, $courseid) {
        $list = "";
        $list.="<select id='practice2_$courseid'>";
        $query = "select * from uk_practice_course_duration "
                . "where courseid=$courseid "
                . "and practiceid=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $db_duration = $row['duration'];
        }

        for ($i = 1; $i <= 36; $i++) {
            if ($i == $db_duration) {
                $list.="<option value='$i' selected>$i</option>";
            } // end if $i==$db_duration
            else {
                $list.="<option value='$i'>$i</option>";
            } // end else
        }
        $list.="</select>";
        return $list;
    }

    function update_course_duration($courseid, $duration) {
        $query = "update uk_course_duration set duration=$duration "
                . "where courseid=$courseid";
        $this->db->query($query);
    }

    function is_course_duration_exists($courseid) {
        $query = "select * from uk_course_duration where courseid=$courseid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function create_course_duration_data($courseid) {
        $status = $this->is_course_duration_exists($courseid);
        if ($status == 0) {
            $query = "insert into uk_course_duration "
                    . "(courseid, duration) "
                    . "values ($courseid, 12)";
            $this->db->query($query);
        } // end if $status==0 
    }

    function get_admin_repeat_training_page() {
        $list = "";

        $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
        $list.="<span class='span2'>Repeated courses</span>";
        $list.="</div>";

        $list.="<table id='repeat_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course Category</th>";
        $list.="<th>Course Name</th>";
        $list.="<th>Repeat Duration</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";

        $query = "select * from uk_course where id<>1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $this->create_course_duration_data($row['id']);
                $catname = $this->get_course_category_name($row['category']);
                $duration = $this->get_course_repeat_box($row['id']);
                $list.="<tr>";
                $list.="<td>$catname</td>";
                $list.="<td>" . $row['fullname'] . "</td>";
                $list.="<td align='center'><span class='col3' style='text-align:center;padding-left:0px;'>$duration &nbsp;&nbsp;(month)</span></td>";
                $list.="</tr>";
            } // end while
        } // end if $num > 0

        $list.="</tbody>";

        $list.="</table>";

        return $list;
    }

    function is_practice_course_duration_exists($practiceid, $courseid) {
        $query = "select * from uk_practice_course_duration "
                . "where courseid=$courseid and practiceid=$practiceid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function create_practice_initial_courses_duration($practiceid, $courses) {
        foreach ($courses as $courseid) {
            $status = $this->is_practice_course_duration_exists($practiceid, $courseid);
            if ($status == 0) {
                $query = "insert into uk_practice_course_duration "
                        . "(practiceid,"
                        . "courseid,"
                        . "duration) "
                        . "values($practiceid,"
                        . "$courseid,12)";
                $this->db->query($query);
            } // end if
        } // end foreach
    }

    function is_practice_course($courseid) {
        $userid = $this->user->id;
        $groups = $this->get_practice_groups($userid);
        $courses = $this->get_practice_courses_by_groups($groups); // array
        return in_array($courseid, $courses);
    }

    function get_gpadmin_repeat_training_page($userid) {
        $list = "";
        $courses = array();
        $groups = $this->get_practice_groups($userid);
        $practice = $this->get_practice_by_admin_userid($userid);
        $practiceid = $practice->id;

        foreach ($groups as $groupid) {
            $query = "select * from uk_groups where id=$groupid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['courseid'];
            }
        }
        $this->create_practice_initial_courses_duration($practiceid, $courses);

        $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
        $list.="<span class='span2'>Repeated courses</span>";
        $list.="<input type='hidden' id='repeat_practice_id' value='$practiceid'>";
        $list.="</div>";

        $list.="<table id='repeat_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course Category</th>";
        $list.="<th>Course Name</th>";
        $list.="<th>Repeat Duration</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";

        $query = "select * from uk_practice_course_duration where practiceid=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['courseid'] > 0) {
                if ($this->is_practice_course($row['courseid'])) {
                    $catname = $this->get_course_category_name($this->get_course_categoryid($row['courseid']));
                    $coursename = $this->get_course_name($row['courseid']);
                    $duration = $this->get_practice_course_repeat_box($practiceid, $row['courseid']);
                    $list.="<tr>";
                    $list.="<td>$catname</td>";
                    $list.="<td>" . $coursename . "</td>";
                    $list.="<td align='center'><span class='col3' style='text-align:center;padding-left:0px;'>$duration &nbsp;&nbsp;(month)</span></td>";
                    $list.="</tr>";
                } // end if is_practice_course($courseid)
            } // end if $row['courseid']>0
        }

        $list.="</tbody>";

        $list.="</table>";

        return $list;
    }

    function update_practice_course_duration($course) {
        $query = "update uk_practice_course_duration "
                . "set duration=$course->duration "
                . "where courseid=$course->courseid "
                . "and practiceid=$course->practiceid";
        $this->db->query($query);
    }

    function get_policy_page($userid) {
        $list = "";
        if ($userid == 2) {
            $list.=$this->get_admin_policy_page($userid);
        } // end if $userid==2
        else {
            $list.=$this->get_gpadmin_policy_page($userid);
        } // end else 
        return $list;
    }

    function get_policy_box($courseid, $upload = true) {
        $list = "";
        $list.="<div class='row-fluid'>";
        $query = "select * from uk_course_policies where courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $link = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/policies/$courseid/" . $row['path'] . "' target='_blank'>" . $row['title'] . "</a>";
                $list.="<span class='span6'>$link</span>";
                if ($upload) {
                    $list.="<span class='span1'><i id='p_upload_$courseid' style='cursor:pointer;' title='Uplload' class='fa fa-upload' aria-hidden='true'></i></span>";
                }
            }
        } // end if $num > 0
        else {
            $list.="<span class='span3'>N/A</span>";
            if ($upload) {
                $list.="<span class='span1'><i id='p_upload_$courseid' style='cursor:pointer;' title='Uplload' class='fa fa-upload' aria-hidden='true'></i></span>";
            }
        } // end else 
        $list.="</div>";

        return $list;
    }

    function get_admin_policy_page($userid) {
        $list = "";

        $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
        $list.="<span class='span2'>Courses policy</span>";
        $list.="</div>";

        $list.="<table id='courses_policy' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course Category</th>";
        $list.="<th>Course Name</th>";
        $list.="<th>Course Policy</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";

        $query = "select * from uk_course where id<>1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $catname = $this->get_course_category_name($row['category']);
                $policy_box = $this->get_policy_box($row['id']);
                $list.="<tr>";
                $list.="<td>$catname</td>";
                $list.="<td>" . $row['fullname'] . "</td>";
                $list.="<td align='center'><span class='col6' style='text-align:center;padding-left:0px;'>$policy_box</span></td>";
                $list.="</tr>";
            } // end while
        } // end if $num > 0

        $list.="</tbody>";

        $list.="</table>";

        return $list;
    }

    function is_policy_exists($courseid) {
        $query = "select * from uk_course_policies where courseid=$courseid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function add_new_policy($files, $post) {
        $file = $files[0];
        $name = $file['name'];
        $tmp_name = $file['tmp_name'];
        $error = $file['error'];
        $size = $file['size'];
        $courseid = $post['courseid'];
        $title = $post['title'];
        $summary = $post['summary'];
        if ($tmp_name != '' && $error == 0 && $size > 0) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/common/policies/$courseid";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $destination = $dir . "/" . $name;
            $status = move_uploaded_file($tmp_name, $destination);
            if ($status) {
                $exists = $this->is_policy_exists($courseid);
                if ($exists == 0) {
                    $query = "insert into uk_course_policies "
                            . "(courseid,"
                            . "title,"
                            . "summary,"
                            . "path) "
                            . "values($courseid,"
                            . "'$title', "
                            . "'$summary',"
                            . "'$name')";
                    $this->db->query($query);
                } // end if $exists == 0
                else {
                    $query = "update uk_course_policies "
                            . "set title='$title', "
                            . "summary='$summary', "
                            . "path='$name' "
                            . "where courseid=$courseid";
                    $this->db->query($query);
                } // end else
            } // end if $status
        } // end if $tmp_name!='' && $error==0 && $size>0
    }

    function get_gpadmin_policy_page($userid) {
        $list = "";
        $practice = $this->get_practice_by_admin_userid($userid);
        $practiceid = $practice->id;

        $query = "select * from uk_practice_course_duration "
                . "where practiceid=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = $row['courseid'];
        }
        $list.="<div class='row-fluid' style='margin-left:15px;font-weight:bold;'>";
        $list.="<span class='span2'>Courses policy</span>";
        $list.="</div>";

        $list.="<table id='courses_policy' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course Category</th>";
        $list.="<th>Course Name</th>";
        $list.="<th>Course Policy</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";

        foreach ($courses as $courseid) {
            if ($courseid > 0) {
                $catname = $this->get_course_category_name($this->get_course_categoryid($courseid));
                $coursename = $this->get_course_name($courseid);
                $policy_box = $this->get_policy_box($courseid, false);
                $list.="<tr>";
                $list.="<td>$catname</td>";
                $list.="<td>" . $coursename . "</td>";
                $list.="<td align='center'><span class='col6' style='text-align:center;padding-left:0px;'>$policy_box</span></td>";
                $list.="</tr>";
            } // end if $courseid>0
        } // end foreach

        $list.="</tbody>";

        $list.="</table>";

        return $list;
    }

    function get_course_enroll_id($courseid) {
        $query = "select * from uk_enrol "
                . "where enrol='manual' "
                . "and courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_course_enrollment_date($courseid, $userid) {
        $enrollid = $this->get_course_enroll_id($courseid);
        $query = "select * from uk_user_enrolments "
                . "where enrolid=$enrollid "
                . "and $userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $start = $row['timestart'];
        }
        return $start;
    }

    function get_course_expiration_date($courseid, $userid) {
        $start = $this->get_course_enrollment_date($courseid, $userid);
        if ($userid != 2) {
            $practiceid = $this->get_student_practice($userid);
            if ($practiceid > 0) {
                $query = "select * from uk_practice_course_duration "
                        . "where courseid=$courseid";
            } // end if $practiceid>0
            else {
                $query = "select * from uk_course_duration "
                        . "where courseid=$courseid";
            } // end else
        } // end if $userid!=2
        else {
            $query = "select * from uk_course_duration "
                    . "where courseid=$courseid";
        } // end else 

        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $duration = $row['duration']; // months
        }
        $end = $start + ($duration * 2592000);
        return $end;
    }

    function get_course_stat_dialog($c) {
        $list = "";
        $userid = $c->userid;
        $courses = explode(',', $c->courses);

        $list.="<div id='myModal' class='modal fade' style='width:825px;margin-left:0px;left:18%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width:825px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Courses</h4>
                </div>
                
                <div class='modal-body' style='height:875px;'>";

        $list.="<table id='courses_list' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course name</th>";
        $list.="<th>Progress</th>";
        $list.="<th>Enrollment date</th>";
        $list.="<th>Due date</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";
        foreach ($courses as $courseid) {
            $coursename = $this->get_course_name($courseid);
            $progress = $this->get_course_progress($courseid, $userid);
            $starth = date('m-d-Y', $this->get_course_enrollment_date($courseid, $userid));
            $expirh = date('m-d-Y', $this->get_course_expiration_date($courseid, $userid));
            $list.="<tr>";
            $list.="<td>$coursename</td>";
            $list.="<td>$progress %</td>";
            $list.="<td>$starth</td>";
            $list.="<td>$expirh</td>";
            $list.="</tr>";
        } // end foeacj
        $list.="</tbody>";
        $list.="</table>";
        $list.="<div class='container-fluid' style='text-align:center;padding-top:10px;'>
                    <span align='center' class='span7' style='padding-left:250x;'><button type='button'  data-dismiss='modal' id='cancel'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function create_courses_json_data() {
        $query = "select * from uk_cohort order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $name[] = mb_convert_encoding($row['name'], 'UTF-8');
            } // end while
            file_put_contents($this->data_path . "/cohorts.json", json_encode($name));
        } // end if $num > 0

        $query = "select * from uk_practice order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $pr[] = mb_convert_encoding($row['name'], 'UTF-8');
            } // end while
            file_put_contents($this->data_path . "/practices.json", json_encode($pr));
        } // end if $num > 0 


        $query = "select * from uk_course where category>0 order by fullname";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $categoryname = $this->get_course_category_name($row['category']);
                $coursename = $categoryname . "-" . $row['fullname'];
                $courses[] = mb_convert_encoding($coursename, 'UTF-8');
            } // end while
            file_put_contents($this->data_path . "/courses.json", json_encode($courses));
        } // end if$num > 0

        $query = "select * from uk_user where deleted=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_practice = $this->has_practice($row['id']);
                if ($has_practice > 0) {
                    $name = $row['firstname'] . " " . $row['lastname'];
                    $users[] = mb_convert_encoding($name, 'UTF-8');
                } // end if $has_practice >0 
            } // end while
            file_put_contents($this->data_path . "/users.json", json_encode($users));
        } // end if $num > 0
    }

    function has_practice($userid) {
        $query = "select * from uk_practice_members where userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_user_certificates() {
        $userid = $this->user->id;
        $list = "";

        return $list;
    }

    function create_user_training_report() {
        $list = "";
        $comp = new Completion();
        $cert = new Certificate();
        $userid = $this->user->id;
        $courses = $this->get_user_courses($userid);

        $list.="<html>";
        $list.="<head>";
        $list.="<link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>";
        $list.="</head>";
        $list.="<body>";

        $list.="<br><br>";
        $list.="<div style='text-align:center;'><h2>Training report</h2></div>";

        $list.="<br><p align='center'>";

        $list.="<table id='stat' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr style='font-weight:bold;'>";
        $list.="<th style='padding:15px;align:left;'>Course Name</th>";
        $list.="<th style='padding:15px;align:left;'>Progress</th>";
        $list.="<th style='padding:15px;align:left;'>Enroll Date</th>";
        $list.="<th style='padding:15px;align:left;'>Due Date</th>";
        $list.="<th style='padding:15px;align:left;'>Stats</th>";
        $list.="</tr>";
        $list.="</thead>";

        if (count($courses) > 0) {
            $list.="<tbody>";
            foreach ($courses as $courseid) {
                $coursename = $this->get_course_name($courseid);
                $progress = $this->get_course_progress($courseid, $userid);
                $starth = date('m-d-Y', $this->get_user_enrollment_date($courseid, $userid));
                $endh = date('m-d-Y', $this->get_course_expiration_date($courseid, $userid));
                $stat = $comp->get_user_course_stat($courseid, $userid);
                $list.="<tr>";
                $list.="<td style='padding:15px;'>$coursename</td>";
                $list.="<td style='padding:15px;' style='text-align:center;'>$progress %</td>";
                $list.="<td style='padding:15px;'>$starth</td>";
                $list.="<td style='padding:15px;'>$endh</td>";
                $list.="<td style='padding:15px;'>$stat</td>";
                $list.="</tr>";
            } // end foreach
            $list.="</tbody>";
        } // end if count($courses)>0

        $list.="</table>";
        $list.="</p>";
        $list.="</body>";
        $list.= "</html>";

        $path = $this->path . "/$userid";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $htmlpath = $path . "/report.html";
        file_put_contents($htmlpath, $list);
        $cert->create_report_pdf_file($list);
        $link = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/certificates/$userid/report.pdf";
        return $link;
    }

}
