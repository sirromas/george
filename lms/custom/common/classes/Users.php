<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Groups.php';

/**
 * Description of Users
 *
 * @author moyo
 */
class Users extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_users_page($userid) {
        $list = "";
        $userid = $this->user->id;
        if ($userid == 2) {
            $list.=$this->get_admin_users_page($userid);
        } // end if $userid==2
        else {
            $list.=$this->get_gp_users_page($userid);
        } // end else
        return $list;
    }

    function get_practice_name_by_userid($userid) {
        $query = "select * from uk_practice_members where userid=$userid";
        $result = $this->db->query($query);
        $num = $this->db->numrows($query);
        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $practiceid = $row['practiceid'];
            } // end hwile
        } // end if $num > 0
        else {
            $practiceid = 0;
        }
        if ($practiceid > 0) {
            $query = "select * from uk_practice where id=$practiceid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $name = $row['name'];
            }
        } // end if $practiceid>0
        else {
            $name = 'N/A';
        }

        return $name;
    }

    function get_admin_users_page($current_userid) {
        $list = "";
        $current_user = $this->user->id;
        $users = array();
        $query = "select * from uk_user where deleted=0";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['id'];
        }

        $list.="<div class='row-fluid'>";
        $list.="<input type='hidden' id='current_user' value='$current_userid'>";
        $list.="<span class='span3'><button id='users_add_user'>Add User</button></span>";
        $list.="</div>";

        if (count($users) > 0) {
            $list.="<div id='users_container'>";
            $list.="<table id='data-users' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Practice name</th>";
            $list.="<th>Role</th>";
            $list.="<th>Firstname</th>";
            $list.="<th>Lastname</th>";
            $list.="<th>Email</th>";
            $list.="<th>Actions</th>";
            $list.="</tr>";
            $list.="</thead>";

            $list.="<tbody>";

            foreach ($users as $userid) {
                $user = $this->get_user_data_by_id($userid);
                $practicename = $this->get_practice_name_by_userid($userid);
                if ($userid != 2) {
                    $rolename = $this->get_user_rolename($this->get_user_role($userid));
                } // end if $userid!=2
                else {
                    $rolename = 'Super Admin';
                } // end else 
                if ($practicename != 'N/A') {
                    $list.="<tr>";
                    $list.="<td>$practicename</td>";
                    $list.="<td>$rolename</td>";
                    $list.="<td>$user->firstname</td>";
                    $list.="<td>$user->lastname</td>";
                    $list.="<td>$user->email</td>";
                    $list.="<td><i id='users_info_userid_$userid' style='cursor:pointer;' class='fa fa-user-circle-o' aria-hidden='true' title='User data'></i>"
                            . "<i id='users_courses_$userid' style='cursor:pointer;padding-left:15px;' class='fa fa-podcast' aria-hidden='true'></i>";
                    if ($current_user != $userid) {
                        $list.="<i id='users_delete_userid_$userid' style='cursor:pointer;padding-left:15px;' class='fa fa-trash' title='Delete' aria-hidden='true'></i></td>";
                    }
                    $list.="</tr>";
                } // end if $practicename != 'N/A'
            } // end foreach
            $list.="</table>";
            $list.="</div>";
        } // end ifcount($users)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'>There are no users in the systen</span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_gp_users_page($adminuserid) {
        $list = "";
        $current_user = $this->user->id;
        $users = array();
        $g = new Groups();

        $query = "select * from uk_practice where userid=$adminuserid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $practiceid = $row['id'];
            $practicename = $row['name'];
        }
        //echo "Practice id: " . $practiceid . "<br>";
        //echo "Practice name: " . $practicename . "<br>";
        $query = "select * from uk_practice_members where practiceid=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['userid'];
        }

        $list.="<div class='row-fluid'>";
        $list.="<input type='hidden' id='current_user' value='$adminuserid'>";
        $list.="<span class='span3'><button id='users_add_user'>Add User</button></span>";
        $list.="</div>";

        if (count($users) > 0) {
            $list.="<div id='users_container'>";
            $list.="<table id='data-users' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Practice name</th>";
            $list.="<th>Role</th>";
            $list.="<th>Firstname</th>";
            $list.="<th>Lastname</th>";
            $list.="<th>Email</th>";
            $list.="<th>Actions</th>";
            $list.="</tr>";
            $list.="</thead>";

            $list.="<tbody>";
            foreach ($users as $userid) {
                if ($userid != 2) {
                    $user = $this->get_user_data_by_id($userid);
                    $rolename = $this->get_user_rolename($this->get_user_role($userid));
                    $list.="<tr>";
                    $list.="<td>$practicename</td>";
                    $list.="<td>$rolename</td>";
                    $list.="<td>$user->firstname</td>";
                    $list.="<td>$user->lastname</td>";
                    $list.="<td>$user->email</td>";
                    $list.="<td><i id='users_info_userid_$userid' style='cursor:pointer;' class='fa fa-user-circle-o' aria-hidden='true' title='User data'></i>"
                            . "<i id='users_courses_$userid' style='cursor:pointer;padding-left:15px;' class='fa fa-podcast' aria-hidden='true'></i>";
                    if ($current_user != $userid) {
                        $list.="<i id='users_delete_userid_$userid' style='cursor:pointer;padding-left:15px;' class='fa fa-trash' title='Delete' aria-hidden='true'></i></td>";
                    } // end if 
                    $list.="</tr>";
                } // end if $userid != 2
            } // end foreach
            $list.="</tbody>";

            $list.="</table>";
            $list.="</div>";
        } // end if count($users)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'>There are no users inside practice</span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_edit_user_dialog($current_user, $userid) {
        $list = "";
        $list.=$this->get_admin_edit_user_dialog($current_user, $userid);
        return $list;
    }

    function get_courseid_by_context($contextid) {
        $query = "select * from uk_context where id=$contextid "
                . "and contextlevel=50";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    function get_users_course_block($userid, $toolbar = true) {
        $list = "";
        if ($toolbar) {
            $list.="<div class='row-fluid' id='existing_user_courses_container'>";
        }
        $list.="<ul>";

        $query = "select * from uk_role_assignments "
                . "where roleid=5 and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courseid = $this->get_courseid_by_context($row['contextid']);
                $coursename = $this->get_course_name($courseid);
                $list.="<div class='row-fluid'>";
                $list.="<span class='span6'>$coursename</span><span class='span1'><i id='user_course_$courseid' style='cursor:pointer;' class='fa fa-trash' title='Delete' aria-hidden='true'></i></span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>N/A</span>";
            $list.="</div>";
        } // end else
        $list.="</ul>";
        if ($toolbar) {
            $list.="</div>";
        }
        return $list;
    }

    function get_admin_edit_user_dialog($current_user, $userid) {
        $list = "";
        $user = $this->get_user_data_by_id($userid);

        $list.="<div id='myModal' class='modal fade' style='width:800px;margin-left:0px;left:18%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style=''>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit User</h4>
                </div>
                <div class='modal-body' style=''>
                <input type='hidden' id='user_section_userid' value='$userid'>
                    
                <div class='container-fluid'>
                <span class='span1'>Firstname*</span>
                <span class='span6'><input type='text' id='user_firstname' value='$user->firstname'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Lastname*</span>
                <span class='span6'><input type='text' id='user_lastname' value='$user->lastname'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Email*</span>
                <span class='span6'><input type='text' id='user_email' value='$user->email'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Password</span>
                <span class='span6'><input type='password' id='user_pwd' value=''></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='user_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='update_user_section_user'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_user_practice_courses($userid) {
        $list = "";
        $courses = array();

        if ($userid != 2) {
            $query = "select * from uk_practice_members where userid=$userid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $practiceid = $row['practiceid'];
            }

            $query = "select * from uk_practice where id=$practiceid";
            //echo "Query: " . $query . "<br>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $pracice_admin_userid = $row['userid'];
            }

            $query = "select * from uk_groups_members where userid=$pracice_admin_userid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $groups[] = $row['groupid'];
            }

            foreach ($groups as $groupid) {
                $query = "select * from uk_groups where id=$groupid";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $courses[] = $row['courseid'];
                }
            }

            $list.="<select multiple id='gpcourses' style='width:220px;height:95px;'>";
            $list.="<option value=0 selected>Please select</option>";
            foreach ($courses as $courseid) {
                $coursename = $this->get_course_name($courseid);
                $list.="<option value='$courseid'>$coursename</option>";
            }
            $list.="</select>";
        } // end if $userid!=2
        else {
            $list.="<select multiple id='gpcourses' style='width:220px;height:95px;'>";
            $list.="<option value=0 selected>Please select</option>";

            $list.="</select>";
        } // end else 

        return $list;
    }

    function get_admin_users_course_dialog($userid) {
        $list = "";
        $ext_courses = $this->get_users_course_block($userid);
        $g = new Groups();
        $pcategories = $g->get_practice_types();
        $pcourses = $this->get_user_practice_courses($userid);

        $list.="<div id='myModal' class='modal fade' style='width:800px;margin-left:0px;left:18%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width:800px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit User Courses</h4>
                </div>
                <div class='modal-body' style='min-height:520px;'>
                <input type='hidden' id='user_section_userid' value='$userid'>
                
                <div class='container-fluid' >
                <span class='span2' style='font-weight:bold;'>Enrolled courses:</span>
                <span class='span6'>$ext_courses</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span7'><hr></span>
                </div>
                
                <div class='container-fluid' >
                <span class='span2' style='font-weight:bold;'>Enroll into course</span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span2'>Categories</span>
                <span class='span3' >$pcategories</span>
                </div>
                </ul>
                    
                <div class='container-fluid' style=''>
                <span class='span2'>Courses</span>
                <span class='span3' id='courses_container'>$pcourses</span>
                </div>
                </ul>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='user_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='update_user_courses'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_user_courses_dialog($current_user, $userid) {
        $list = "";
        if ($userid == 2) {
            $list.=$this->get_admin_users_course_dialog($userid);
        } // end if $userid==2
        else {
            $ext_courses = $this->get_users_course_block($userid);
            //$g = new Groups();
            //$pcategories = $g->get_practice_types();
            $pcourses = $this->get_user_practice_courses($userid);

            $list.="<div id='myModal' class='modal fade' style='width:800px;margin-left:0px;left:18%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width:800px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit User Courses</h4>
                </div>
                <div class='modal-body' style='min-height:520px;'>
                <input type='hidden' id='user_section_userid' value='$userid'>
                
                <div class='container-fluid' >
                <span class='span2' style='font-weight:bold;'>Enrolled courses:</span>
                <span class='span6'>$ext_courses</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span7'><hr></span>
                </div>
                
                <div class='container-fluid' >
                <span class='span2' style='font-weight:bold;'>Enroll into course</span>
                </div>
                    
                <div class='container-fluid' style=''>
                <span class='span2'>Practice courses</span>
                <span class='span3' id='courses_container'>$pcourses</span>
                </div>
                </ul>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='user_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='update_user_courses'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";
        } // end else

        return $list;
    }

    function get_course_groupid($courseid) {
        $query = "select * from uk_groups where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['id'];
        }
        return $groupid;
    }

    function create_course_enrollment_method($courseid) {
        $method = 'manual';
        $period = '31536000';
        $date = time();
        $query = "insert into uk_enrol "
                . "(enrol, "
                . "courseid, "
                . "enrolperiod, "
                . "timecreated, "
                . "timemodified) "
                . "values ('$method',"
                . "$courseid,"
                . "'$period',"
                . "'$date',"
                . "$date)";
        $this->db->query($query);
    }

    function get_entoll_id($courseid) {
        $method = 'manual';
        $query = "select * from uk_enrol "
                . "where enrol='$method' "
                . "and courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $this->create_course_enrollment_method($courseid);
        } // end if $num==0
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $enrollid = $row['id'];
        }
        return $enrollid;
    }

    function enroll_user($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $roleid = 5; // student
        $enrolid = $this->get_entoll_id($courseid);
        // Check before make enroll, maybe already enrolled?
        $query = "select * from uk_role_assignments "
                . "where contextid=$contextid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            // 1. Insert into uk_user_enrolments table
            $query = "insert into uk_user_enrolments
             (enrolid,
              userid,
              timestart,
              modifierid,
              timecreated,
              timemodified)
               values ('" . $enrolid . "',
                       '" . $userid . "',
                        '" . time() . "',   
                        '2',
                         '" . time() . "',
                         '" . time() . "')";
            //echo "Query: ".$query."<br/>";
            $this->db->query($query);

            // 2. Insert into uk_role_assignments table
            $query = "insert into uk_role_assignments
                  (roleid,
                   contextid,
                   userid,
                   timemodified,
                   modifierid)                   
                   values ('" . $roleid . "',
                           '" . $contextid . "',
                           '" . $userid . "',
                           '" . time() . "',
                            '2'         )";
            // echo "Query: ".$query."<br/>";
            $this->db->query($query);
        } // end if $num==0
    }

    function unenroll_user($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $enrolid = $this->get_entoll_id($courseid);

        $query = "delete from uk_role_assignments "
                . "where contextid=$contextid "
                . "and userid=$userid";
        $this->db->query($query);

        $query = "delete from uk_user_enrolments "
                . "where enrolid=$enrolid "
                . "and userid=$userid";
        $this->db->query($query);
    }

    function add_user_to_group($groupid, $userid) {
        $query = "select * from uk_groups_members "
                . "where groupid=$groupid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $query = "insert into uk_groups_members "
                    . "(groupid,"
                    . "userid,"
                    . "timeadded) "
                    . "values($groupid,$userid,'" . time() . "')";
            $this->db->query($query);
        }
    }

    function remove_user_from_group($groupid, $userid) {
        // GP admin should be never removed from any GP course group
        $status = $this->is_user_gpadmin($userid);
        if ($status == 0) {
            $query = "delete from uk_groups_members "
                    . "where groupid=$groupid "
                    . "and userid=$userid";
            $this->db->query($query);
        }
    }

    function is_user_gpadmin($userid) {
        $query = "select * from uk_practice where userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function remove_user_from_course($courseid, $userid) {
        $list = "";
        $this->unenroll_user($courseid, $userid);
        $groupid = $this->get_course_groupid($courseid);
        if ($groupid > 0) {
            $this->remove_user_from_group($groupid, $userid);
        }
        $list.=$this->get_users_course_block($userid, false);
        return $list;
    }

    function update_user($user) {
        $g = new Groups();
        $g->update_gpadmin($user);
    }

    function update_user_courses($user) {
        $query = "select * from uk_cohort_members "
                . "where userid=$user->userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $cohortid = $row['cohortid'];
            }
        } // end if $num > 0
        else {
            $cohortid = 0;
        }

        if ($cohortid > 0) {
            $query = "select * from uk_cohort where id=$cohortid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $cohort_name = $row['name'];
            }
        } // end if $cohortid>0
        else {
            $cohort_name = "Practice Index";
        }

        $query = "select * from uk_practice_members where userid=$user->userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $practiceid = $row['practiceid'];
            }
        } // end if $num > 0
        else {
            $practiceid = 0;
        }

        if ($practiceid > 0) {
            $query = "select * from uk_practice where id=$practiceid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $praticename = $row['name'];
            }
        } // end if $practiceid>0
        else {
            $praticename = 'Practice Index';
        }

        $courses = $user->courses;
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                if ($courseid > 0) {
                    $this->enroll_user($courseid, $user->userid);
                    if ($user->userid != 2) {
                        $groupid = $this->get_course_groupid($courseid);
                        $this->add_user_to_group($groupid, $user->userid);
                    } // end if $user->userid != 2
                } // end if $courseid>0
            } // end foreach
        } // end if count($courses)>0
    }

    function get_add_user_dialog($userid) {
        $list = "";
        $list.=$this->get_admin_add_user_dialog($userid);
        return $list;
    }

    function get_practices_list($userid) {
        $list = "";
        if ($userid == 2) {
            $list.="<select id='practices_list' style='width:220px;'>";
            $list.="<option value='0' selected>Please select</option>";
            $query = "select * from uk_practice order by name";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $list.="<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                } // end while
            } // end if $num > 0
            $list.="</select>";
        } // end if $userid==2
        else {
            $practice = $this->get_practice_by_admin_userid($userid);
            $list.="<input type='hidden' id='practices_list' value='$practice->id'>";
            $list.=$practice->name;
        }
        return $list;
    }

    function get_admin_add_user_dialog($userid) {
        $list = "";
        $practices = $this->get_practices_list($userid);
        $list.="<div id='myModal' class='modal fade' style='width:800px;margin-left:0px;left:18%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style=''>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add User</h4>
                </div>
                <div class='modal-body' style=''>
                <input type='hidden' id='user_section_userid' value='$userid'>
                    
                <div class='container-fluid'>
                <span class='span1'>Firstname*</span>
                <span class='span6'><input type='text' id='user_firstname' value=''></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Lastname*</span>
                <span class='span6'><input type='text' id='user_lastname' value=''></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Email*</span>
                <span class='span6'><input type='text' id='user_email' value=''></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Password*</span>
                <span class='span6'><input type='password' id='user_pwd' value=''></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Practice*</span>
                <span class='span6'>$practices</span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='user_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='add_new_user'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_user_to_practice($practiceid, $userid) {
        $query = "insert into uk_practice_members "
                . "(practiceid,userid) "
                . "values ($practiceid,$userid)";
        $this->db->query($query);
    }

    function add_user_to_cohort($practiceid, $userid) {
        $query = "select * from uk_practice where id=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cohortid = $row['cohortid'];
        }

        $query = "insert into uk_cohort_members "
                . "(cohortid,"
                . "userid,"
                . "timeadded) "
                . "values ($cohortid,$userid,'" . time() . "')";
        $this->db->query($query);
    }

    function add_new_user($user) {

        $status = $this->create_user($user);
        if ($status) {
            $dbuser = $this->get_user_data_by_email($user->email); // object
            $userid = $dbuser->id;
            $this->add_user_to_practice($user->practiceid, $userid);
            $this->add_user_to_cohort($user->practiceid, $userid);
        }
    }

    function delete_user($userid) {

        // 1. Remove from groups
        // 2. Unenroll user
        // 3. Remove from cohorts
        // 4. Remove from practice
        // 5. Delete user

        $query = "delete from uk_groups_members where userid=$userid";
        $this->db->query($query);

        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $this->unenroll_user($courseid, $userid);
            }
        }

        $query = "delete from uk_cohort_members where userid=$userid";
        $this->db->query($query);

        $query = "delete from uk_practice_members where userid=$userid";
        $this->db->query($query);

        $query = "update uk_user set deleted=1 where id=$userid";
        $this->db->query($query);
    }

}
