<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

class Courses extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_courses_page($userid) {
        $list = "";
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
        $query = "select * from uk_course_categories where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
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

    function get_admin_page($userid) {
        $list = "";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/course/management.php'><button style='width:147px;'>Manage courses</button></a></span>";
        $list.="<span class='span2'><button style='width:147px;'>Add Group</button></span>";
        $list.="<span class='span2'><button style='width:147px;'>Add GP Practice</button></span>";
        $list.="</div><br>";
        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span3'>Course name/provider</span>";
            $list.="<span class='span1'>Length</span>";
            $list.="<span class='span1'>Frequency</span>";
            $list.="<span class='span2'>Status</span>";
            $list.="<span class='span1'>Plicies</span>";
            $list.="<span class='span1'>Certificates</span>";
            $list.="<span class='span1'>Date passed</span>";
            $list.="<span class='span1'>Due date</span>";
            $list.="</div>";

            foreach ($courses as $courseid) {
                $course_data = $this->get_course_detailes($courseid);
                $list = "<div class='container-fluid'>";
                $list.="<span class='span3'></span>";
                $list.="<span class='span3'></span>";
                $list.="</div>";
            } // end foreach
        } // end if count($courses)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6' style='padding-left:7px;'>You are not enrolled into any course</span>";
            $list.="</div>";
        }


        return $list;
    }

    function get_student_page($userid) {
        $list = "";

        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span3'>Course name/provider</span>";
            $list.="<span class='span1'>Length</span>";
            $list.="<span class='span1'>Frequency</span>";
            $list.="<span class='span2'>Status</span>";
            $list.="<span class='span1'>Plicies</span>";
            $list.="<span class='span1'>Certificates</span>";
            $list.="<span class='span1'>Date passed</span>";
            $list.="<span class='span1'>Due date</span>";
            $list.="</div>";

            foreach ($courses as $courseid) {
                $course_data = $this->get_course_detailes($courseid);
                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'></span>";
                $list.="<span class='span3'></span>";
                $list.="</div>";
            } // end foreach
        } // end if count($courses)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6' style='padding-left:7px;'>You are not enrolled into any course</span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_ccg_page($userid) {
        $list = "";

        return $list;
    }

    function get_gp_page($userid) {
        $list = "";

        return $list;
    }

    function get_personal_external_training_courses($userid) {
        $list = "";
        $list.= "<div class='container-fluid' style='font-weight:bold;'>";
        $list.="<span class='span12'>My External Training</span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='font-weight:bold;'>";
        $list.="<span class='span2'>Course Name</span>";
        $list.="<span class='span2'>Provider</span>";
        $list.="<span class='span1'>Date</span>";
        $list.="<span class='span1'>Duration</span>";
        $list.="<span class='span2'>Status</span>";
        $list.="<span class='span2'>User</span>";
        $list.="<span class='span2'>Notes</span>";
        $list.="</div>";
        $query = "select * from uk_external_training where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $username = $this->get_username_by_id($row['userid']);
                $status = $this->get_status_text($row['status']);
                $list.="<div class='container-fluid' style=''>";
                $list.="<span class='span2'>" . $row['name'] . "</span>";
                $list.="<span class='span2'>" . $row['provider'] . "</span>";
                $list.="<span class='span1'>" . date('m/d/Y', $row['cdate']) . "</span>";
                $list.="<span class='span1' style='text-align:center;'>" . $row['duration'] . "</span>";
                $list.="<span class='span2'>" . $status . "</span>";
                $list.="<span class='span2'>" . $username . "</span>";
                $list.="<span class='span2'>" . $row['notes'] . "</span>";
                $list.="</div>";
            } // end while
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span9'>There are no external courses you enrolled</span>";
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
                    $groupingid = $this->get_user_grouping($userid);
                    $list2 = $this->get_gp_external_training($groupingid);
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

    function get_external_coure_status_box() {
        $list = "";
        $list.="<select id='ext_course_status'>";
        $list.="<option value='0' selected>Please select</option>";
        $list.="<option value='1'>Not started yet</option>";
        $list.="<option value='2'>In progress</option>";
        $list.="<option value='3'>Complete</option>";
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
        $list.="<div id='myModal' class='modal fade' style='width:875px;margin-left:0px;left:15%;'>
        <div class='modal-dialog' >
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add External Training</h4>
                </div>
                <div class='modal-body' style='min-height:120px;'>
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

    function get_gp_external_training($groupingid) {
        $list = "";

        return $list;
    }

    function get_all_external_training_courses() {
        $not_started = 0;
        $progress = 0;
        $complete = 0;
        $list = "";
        $query = "select * from uk_external_training order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $list.= "<div class='container-fluid'>";
            $list.="<span class='span12'><hr/></span>";
            $list.="</div>";
            $list.= "<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span12'>All External Training</span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>Course Name</span>";
            $list.="<span class='span2'>Provider</span>";
            $list.="<span class='span1'>Date</span>";
            $list.="<span class='span1'>Duration</span>";
            $list.="<span class='span2'>Status</span>";
            $list.="<span class='span2'>User</span>";
            $list.="<span class='span2'>Notes</span>";
            $list.="</div>";
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

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>" . $row['name'] . "</span>";
                $list.="<span class='span2'>" . $row['provider'] . "</span>";
                $list.="<span class='span1'>" . date('m/d/Y', $row['cdate']) . "</span>";
                $list.="<span class='span1' style='text-align:center;'>" . $row['duration'] . "</span>";
                $list.="<span class='span2'>" . $status . "</span>";
                $list.="<span class='span2'>" . $username . "</span>";
                $list.="<span class='span2'>" . $row['notes'] . "</span>";
                $list.="</div>";
            } // end while

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

        return $list;
    }

}
