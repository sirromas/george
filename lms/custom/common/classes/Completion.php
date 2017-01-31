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

    function get_student_course_score($scoid, $userid) {
        $query = "SELECT * FROM `uk_scorm_scoes_track` "
                . "WHERE element='cmi.core.score.raw' "
                . "and scoid=$scoid and userid=$userid "
                . "order by timemodified desc limit 0,1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $score = new stdClass();
            foreach ($row as $key => $value) {
                $score->$key = $value;
            } // end foreach
        } // end while
        return $score;
    }

    function get_student_progress_courses($userid, $date = null) {
        $total = 0;
        $query = "select MAX(id), userid,scoid, element,value, attempt, "
                . "timemodified from uk_scorm_scoes_track "
                . "where userid=$userid and element='cmi.core.score.raw' ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($row['scoid'] != null) {
                    $passgrade = $this->get_scorm_passing_grade($row['scoid']);
                    $score = $this->get_student_course_score($row['scoid'], $userid); // object
                    if ($score->value < $passgrade) {
                        $total++;
                    } // end if $score->value < $passgrade
                } // end if $row['scoid']!=null
            } // end while
        } // end if $num>0 
        return $total;
    }

    function get_student_passed_courses($userid, $date = null) {
        $total = 0;
        $query = "select MAX(id), userid,scoid, element,value, attempt, "
                . "timemodified from uk_scorm_scoes_track "
                . "where userid=$userid and element='cmi.core.score.raw' ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($row['scoid'] != null) {
                    $passgrade = $this->get_scorm_passing_grade($row['scoid']);
                    $score = $this->get_student_course_score($row['scoid'], $userid); //object
                    if ($score->value >= $passgrade) {
                        $total++;
                    } // end if
                } // end if ($row['scoid'] != null) {
            } // end while
        } // end if $num>0 
        return $total;
    }

    function get_student_overdue_courses($userid, $date = null) {
        $total = 0;
        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                //echo "Current course id: " . $courseid . "<br>";
                $scoid = $this->get_scorm_scoid($courseid);
                //echo "Current scoid: " . $scoid . "<br>";
                if ($scoid > 0) {
                    $passgrade = $this->get_scorm_passing_grade($scoid);
                    $score = $this->get_student_course_score($scoid, $userid);
                    if ($score->vaue < $passgrade) {
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
                        //echo "Course duration month(s): " . $duration . "<br>";
                        $query = "select * from uk_enrol where "
                                . "courseid=$courseid "
                                . "and enrol='manual'";
                        $result = $this->db->query($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $enrollid = $row['id'];
                        }
                        //echo "Enroll id: " . $enrollid . "<br>";
                        $query = "select * from uk_user_enrolments "
                                . "where enrolid=$enrollid "
                                . "and userid=$userid";
                        //echo "Query: " . $query . "<br>";
                        $result = $this->db->query($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $start = $row['timestart']; // unix timestamp
                        }
                        //echo "<br>Enrollment date (unix): " . $start . "<br>";
                        //echo "Enrollment date (human): " . date('m-d-Y', $start);
                        $end = $start + ($duration * 2592000);
                        //echo "<br>Expiration date (unix): " . $end . "<br>";
                        //echo "Expiration date (human): " . date('m-d-Y', $end) . "<br>";

                        $now = time();
                        //echo "<br>Current moment (unixtime): " . $now . "<br>";
                        //echo "<br>Current moment (human): " . date('m-d-Y', $now);

                        if ($now > $end) {
                            $total++;
                        } // end if $now>$end
                    } // end if $score->vaue<$passgrade
                } // end if $scoeid>0
            }  // end foreach
        } // end if count($courses)>0
        return $total;
    }

}
