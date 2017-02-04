<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/classes/Certificate.php';

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

    function is_certificate_exists($courseid, $userid) {
        $query = "select * from uk_course_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function create_user_certificates($c) {
        $userid = $c->userid;
        $courses = $c->courses;

        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $already_exists = $this->is_certificate_exists($courseid, $userid);
                if ($already_exists == 0) {
                    
                } // end if $already_exists
            } // end foreach
        } // end if count($courses)>0
    }

    function get_user_last_attempt($scoid, $userid) {
        $query = "SELECT * FROM `uk_scorm_scoes_track` "
                . "WHERE userid=$userid and scoid=$scoid "
                . "ORDER BY attempt DESC "
                . "limit 0 , 1";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $attempt = $row['attempt'];
            }
        } // end if $num > 0
        else {
            $attempt = 0;
        } // end else
        return $attempt;
    }

    function get_scorm_element_value($scoid, $userid, $attempt, $element) {
        $query = "select * from uk_scorm_scoes_track "
                . "where scoid=$scoid "
                . "and userid=$userid "
                . "and attempt=$attempt "
                . "and element='$element'";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $value = $row['value'];
        }
        return $value;
    }

    function get_user_course_stat($courseid, $userid) {
        $list = "";
        $scoid = $this->get_scorm_scoid($courseid);
        if ($scoid > 0) {
            $lastattempt = $this->get_user_last_attempt($scoid, $userid);
            if ($lastattempt > 0) {
                $score = $this->get_student_course_score($scoid, $userid, $courseid, TRUE);
                if ($score === NULL) {
                    $score = 0;
                }
                $status = $this->get_scorm_element_value($scoid, $userid, $lastattempt, 'cmi.core.lesson_status');
                $access = $this->get_scorm_element_value($scoid, $userid, $lastattempt, 'x.start.time');
                $totaltime = $this->get_scorm_element_value($scoid, $userid, $lastattempt, 'cmi.core.total_time');

                $list.="<table>";

                $list.="<thead>";
                $list.="<tr>";
                $list.="<th style='padding:15px;'>Status</th>";
                $list.="<th style='padding:15px;'>Last Access</th>";
                $list.="<th style='padding:15px;'>Attempt</th>";
                $list.="<th style='padding:15px;'>Score</th>";
                $list.="<th style='padding:15px;'>Total Time</th>";
                $list.="</tr>";
                $list.="</thead>";

                $list.="<tbody>";
                $list.="<tr>";
                $list.="<td style='padding:15px;'>$status</td>";
                $list.="<td style='padding:15px;'>" . date('m-d-Y h:i:s', $access) . "</td>";
                $list.="<td style='padding:15px;' align='center'>$lastattempt</td>";
                $list.="<td style='padding:15px;' align='center'>$score %</td>";
                $list.="<td style='padding:15px;'>$totaltime</td>";
                $list.="</tr>";
                $list.="</tbody>";

                $list.="</table>";
            } // end if $lastattempt>0
            else {
                $list.="N/A";
            } // end else
        } // end if $scoid>0
        else {
            $list.="N/A";
        } // end else

        return $list;
    }

    function get_user_training_report_link() {
        $list = "";
        $userid = $this->user->id;
        $action = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/certificates/$userid/report.pdf";
        $list.="<form action='$action' method='get' target='_blank' id='training_report_form'>";
        $list.="<a href='#' onClick='return false;' id='training_report' style='color:black;'>Download your training report</a>";
        $list.="</form>";
        return $list;
    }

    function get_user_certificates_link() {
        $userid = $this->user->id;
        $list = "";
        $query = "select * from uk_course_certificates where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $action = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/certificates/$userid/certificates.pdf";
            $list.="<form action='$action' method='get' target='_blank' id='user_certificates_form'>";
            $list.="<a href='#' onClick='return false;' id='user_certificates' style='color:black;'>Download your training certificates</a>";
            $list.="</form>";
        } // end if $num > 0
        else {
            $list.="<a href='#' onClick='return false;' id='user_certificates' disabled style='color:black;'>Download your training certificates</a>";
        } // end else
        return $list;
    }

    function get_course_detailes_certificate_link($courseid) {
        $userid = $this->user->id;
        $query = "select * from uk_course_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $action = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/certificates/$userid/$courseid/certificate.pdf";
            $list.="<form action='$action' method='get' target='_blank' id='user_certificates_form'>";
            $list.="<a href='#' onClick='return false;' id='user_certificates' style='color:black;'>Download</a>";
            $list.="</form>";
        } // end if $num > 0
        else {
            $list.="N/A";
        } // end else
        return $list;
    }

    function get_user_passed_courses($courses, $userid) {
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $scoid = $this->get_scorm_scoid($courseid);
                if ($scoid > 0) {
                    $passgrade = $this->get_scorm_passing_grade($scoid);
                    $score = $this->get_student_course_score($scoid, $userid, $courseid); // object
                    if ($score->value >= $passgrade) {
                        $pcourses[] = $courseid;
                    } // end if $score->value < $passgrade
                } // end if $scoid>0
            } // end foreach
        } // end if count($courses)>0
        return $pcourses;
    }

}
