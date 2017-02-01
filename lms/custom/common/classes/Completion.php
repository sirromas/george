<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Completion
 *
 * @author moyo
 */
class Completion extends Utils {

    public $scorm_module_id = 18;

    function __construct() {
        parent::__construct();
    }

    function has_scorm_activity($courseid, $userid) {
        $scoid = $this->get_scorm_scoid($courseid);
        if ($scoid > 0) {
            $query = "select * from uk_scorm_scoes_track "
                    . "where scoid=$scoid and userid=$userid";
            $num = $this->db->numrows($query);
        } // end if $scoid>0
        else {
            $num = 0;
        } // end else
        return $num;
    }

    function get_scorm_scoid($courseid) {
        if ($courseid > 0) {
            $query = "select * from uk_scorm where course=$courseid";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['id'];
                }
                $query = "select * from uk_scorm_scoes "
                        . "where launch='index_lms.html' and scorm=$id";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $scoid = $row['id'];
                }
            } // end if $num>0
            else {
                $scoid = 0;
            } // end else
        } // end if $courseid>0
        else {
            $scoid = 0;
        } // end else 
        return $scoid;
    }

    function get_scorm_passing_grade($scoid) {
        $query = "select * from uk_scorm_scoes_data "
                . "where scoid=$scoid and name='masteryscore'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $passgrade = $row['value'];
        }
        return $passgrade;
    }

    function has_scorm_section($courseid) {
        $query = "select * from uk_course_modules "
                . "where course=$courseid "
                . "and module=$this->scorm_module_id";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_all_scorm_based_courses() {
        $courses = array();
        $query = "SELECT * FROM `uk_course_modules` "
                . "WHERE module=$this->scorm_module_id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['course'];
            }
        } // end if $num>0
        return $courses;
    }

    function get_student_course_score($scoid, $userid, $courseid, $progress = FALSE) {
        if ($courseid > 0) {
            $query = "SELECT * FROM `uk_scorm_scoes_track` "
                    . "WHERE element='cmi.core.score.raw' "
                    . "and scoid=$scoid and userid=$userid "
                    . "order by timemodified desc limit 0,1";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if ($progress == FALSE) {
                        $score = new stdClass();
                        foreach ($row as $key => $value) {
                            $score->$key = $value;
                        } // end foreach
                    } // end if $progress==FALSE
                    else {
                        $score = $row['cmi.core.score.raw'];
                    } // end else
                } // end while
            } // end if $num > 0
            else {
                $tracks = $this->has_scorm_activity($courseid, $userid);
                $value = ($tracks > 0) ? 5 : 0;
                if ($progress == FALSE) {
                    $score = new stdClass();
                    $score->value = $value;
                } // end if $progress == FALSE
                else {
                    $score = $value;
                }
            } // end else
        } // end if $courseid > 0
        return $score;
    }

    function get_student_progress_courses($courses, $userid, $date = null) {
        $total = 0;
        $dscourses = array();
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $scoid = $this->get_scorm_scoid($courseid);
                if ($scoid > 0) {
                    $passgrade = $this->get_scorm_passing_grade($scoid);
                    $score = $this->get_student_course_score($scoid, $userid, $courseid); // object
                    if ($score->value < $passgrade) {
                        $total++;
                        $dscourses[] = $courseid;
                    } // end if $score->value < $passgrade
                } // end if $scoid>0
            } // end foreach 
        } // end if count($courses)>0
        $courseslist = implode(',', $dscourses);
        $c = new stdClass();
        $c->courses = $courseslist;
        $c->total = $total;
        return $c;
    }

    function get_student_passed_courses($courses, $userid, $date = null) {
        $dscourses = array();
        $total = 0;
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $scoid = $this->get_scorm_scoid($courseid);
                if ($scoid > 0) {
                    $passgrade = $this->get_scorm_passing_grade($scoid);
                    $score = $this->get_student_course_score($scoid, $userid, $courseid); // object
                    if ($score->value >= $passgrade) {
                        $total++;
                        $dscourses[] = $courseid;
                    } // end if $score->value < $passgrade
                } // end if $scoid>0
            } // end foreach 
        } // end if count($courses)>0
        $courseslist = implode(',', $dscourses);
        $c = new stdClass();
        $c->courses = $courseslist;
        $c->total = $total;
        return $c;
    }

    function get_student_overdue_courses($userid, $date = null) {
        $total = 0;
        $dscourses = array();
        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $scoid = $this->get_scorm_scoid($courseid);
                if ($scoid > 0) {
                    $passgrade = $this->get_scorm_passing_grade($scoid);
                    $score = $this->get_student_course_score($scoid, $userid);
                    if ($score->vaue < $passgrade) {

                        // Get course duration
                        $practiceid = $this->get_student_practice($userid);
                        if ($practiceid > 0) {
                            $query = "select * from uk_practice_course_duration "
                                    . "where courseid=$courseid";
                        } // end if $practiceid>0
                        else {
                            $query = "select * from uk_course_duration "
                                    . "where courseid=$courseid";
                        } // end else
                        $result = $this->db->query($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $duration = $row['duration']; // months
                        }

                        // Get user course enrollment date
                        $query = "select * from uk_enrol where "
                                . "courseid=$courseid "
                                . "and enrol='manual'";
                        $result = $this->db->query($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $enrollid = $row['id'];
                        }
                        $query = "select * from uk_user_enrolments "
                                . "where enrolid=$enrollid "
                                . "and userid=$userid";
                        $result = $this->db->query($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $start = $row['timestart']; // unix timestamp
                        }

                        // Calculate expiration date
                        $end = $start + ($duration * 2592000);
                        $now = time();
                        if ($now > $end) {
                            $total++;
                            $dscourses[] = $courseid;
                        } // end if $now>$end
                    } // end if $score->vaue<$passgrade
                } // end if $scoeid>0
            }  // end foreach
        } // end if count($courses)>0
        $courseslist = implode(',', $dscourses);
        $c = new stdClass();
        $c->courses = $courseslist;
        $c->total = $total;
        return $c;
    }

}
