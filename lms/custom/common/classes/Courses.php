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

    function get_ccg_page($userid) {
        $list = "";

        return $list;
    }

    function get_gp_page($userid) {
        $list = "";

        return $list;
    }

}
