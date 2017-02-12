<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Completion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/mailer/Mailer.php';

/**
 * Description of Groups
 *
 * @author moyo
 */
class Groups extends Utils {

    function __construct() {
        parent::__construct();
    }

    function is_user_deleted($userid) {
        $query = "select * from uk_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $deleted = $row['deleted'];
        }
        return $deleted;
    }

    function get_groups_page() {
        $list = "";
        $groups = array();
        $query = "select * from uk_practice order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_user_deleted($row['userid']);
                if ($status == 0) {
                    $c = new stdClass();
                    foreach ($row as $key => $value) {
                        $c->$key = $value;
                    }
                    $groups[] = $c;
                }
            } // end while
        } // end if $num > 0
        $list.=$this->create_gropus_page($groups);
        return $list;
    }

    function get_gp_admin($groupid) {
        $query = "select * from uk_groups_members where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $group_users[] = $row['userid'];
        }

        foreach ($group_users as $userid) {
            $query = "select * from uk_role_assignments "
                    . "where userid=$userid and contextid=1";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($row['roleid'] == 10) {
                    return $userid;
                } // end if $row['roleid']==10
            } // end while
        } // end foreach
    }

    function get_group_cohort($courseid, $groupid) {
        $query = "select * from uk_courses2cohorts "
                . "where courseid=$courseid "
                . "and groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cohortid = $row['cohortid'];
        }
        return $cohortid;
    }

    function create_gropus_page($groups) {
        $list = "";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span9'><button id='add_gp'>Add Practice</button></span>";
        $list.="</div>";
        if (count($groups) > 0) {

            $list.="<table id='all_groups' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Clinical Group</th>";
            $list.="<th>Practice Name</th>";
            $list.="<th>Practice Admin User</th>";
            $list.="<th>Actions</th>";
            $list.="</tr>";
            $list.="</thead>";
            $list.="<tbody>";
            foreach ($groups as $g) {
                $cohortname = $this->get_cohort_name($g->cohortid);
                $adminuser = $this->get_user_data_by_id($g->userid); // object
                $list.="<tr>";
                $list.="<td>$cohortname</td>";
                $list.="<td>$g->name</td>";
                $list.="<td><span class='span4'>$adminuser->firstname $adminuser->lastname</span> <span class='span2'><a href='mailto:$adminuser->email'>$adminuser->email</a></span></td>";
                $list.="<td><i id='group_info_userid_$g->userid' style='cursor:pointer;' class='fa fa-user-circle-o' aria-hidden='true' title='User data'></i>"
                        . "<i  id='group_practiceid_$g->id' style='cursor:pointer;padding-left:15px;' class='fa fa-podcast' aria-hidden='true'></i>"
                        . "<i  id='group_delete_userid_$g->userid' style='cursor:pointer;padding-left:15px;' class='fa fa-trash' title='Delete' aria-hidden='true'></i></td>";
                $list.="</tr>";
            } // end foreach
            $list.="</tbody>";
            $list.="</table>";
        } // end if count($cohorts)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'>There are no any practice defined</span>";
            $list.="</div>";
        } // end else

        return $list;
    }

    function get_cohorts_list() {
        $list = "";

        $list.="<select id='cohort' style='width:220px;'>";
        $list.="<option value='0' selected>Please select</value>";
        $query = "select * from uk_cohort order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        }
        $list.="</select>";
        return $list;
    }

    function get_practice_types() {
        $list = "";
        $list = "<select id='gptypes' style='width:220px'>";
        $list.="<option value='0' selected>Please select </option>";
        $query = "select * from uk_course_categories order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        }
        $list.="</select>";

        return $list;
    }

    function get_practice_courses($catid = null) {
        $list = "";
        $list.="<select multiple id='gpcourses' style='width:220px;height:95px;'>";
        $list.="<option value=0 selected>Please select</option>";
        if ($catid == null) {
            $query = "select * from uk_course order by fullname";
        } // end if $catid==null
        else {
            $query = "select * from uk_course where category=$catid order by fullname";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $list.="<option value='" . $row['id'] . "'>" . $row['fullname'] . "</option>";
                }
            }
        }

        $list.="</select>";

        return $list;
    }

    function create_cohort($name) {
        $query = "insert into uk_cohort "
                . "(contextid,"
                . "name,"
                . "descriptionformat,"
                . "timecreated,"
                . "timemodified) "
                . "values(1,"
                . "'$name',"
                . "1,"
                . "'" . time() . "',"
                . "'" . time() . "')";
        $this->db->query($query);
        $lastid = $this->get_table_last_id('uk_cohort');
        return $lastid;
    }

    function create_group($courseid, $groupname) {
        $query = "select * from uk_groups "
                . "where courseid=$courseid "
                . "and name='$groupname'";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $idnumber = substr(time(), -4);
            $query = "insert into uk_groups "
                    . "(courseid,"
                    . "idnumber,"
                    . "name,"
                    . "timecreated,"
                    . "timemodified) "
                    . "values($courseid,"
                    . "'$idnumber',"
                    . "'$groupname',"
                    . "'" . time() . "',"
                    . "'" . time() . "')";
            //echo "Query: " . $query . "<br>";
            $this->db->query($query);
            $lastid = $this->get_table_last_id('uk_groups');
        } // end if
        else {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $lastid = $row['id'];
            } // end else
        }
        return $lastid;
    }

    function get_cohort_name($cohortid) {
        $query = "select * from uk_cohort where id=$cohortid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
        }
        return $name;
    }

    function add_user_to_cohort($cohortid, $userid) {
        $query = "insert into uk_cohort_members "
                . "(cohortid,"
                . "userid,"
                . "timeadded) "
                . "values($cohortid,$userid,'" . time() . "')";
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

    function attach_course_to_group($courseid, $groupid) {
        $query = "insert into uk_courses2groups (groupid,courseid) "
                . "values($groupid,$courseid)";
        $this->db->query($query);
    }

    function attach_course_to_cohort($cohortid, $courseid, $groupid) {
        $query = "select * from uk_courses2cohorts "
                . "where cohortid=$cohortid "
                . "and courseid=$courseid "
                . "and groupid=$groupid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $query = "insert into uk_courses2cohorts "
                    . "(cohortid,groupid,courseid) "
                    . "values($cohortid, $groupid, $courseid)";
            $this->db->query($query);
        }
    }

    function assign_gp_admin_role($userid) {
        $query = "insert into uk_role_assignments "
                . "(roleid,"
                . "contextid,"
                . "userid,"
                . "timemodified,"
                . "modifierid) "
                . "values(10,1,$userid,'" . time() . "',2)";
        $this->db->query($query);
    }

    function create_practice($cohortid, $userid, $gpname) {
        $query = "insert into uk_practice "
                . "(cohortid,"
                . "userid,"
                . "name) "
                . "values($cohortid,"
                . "$userid,'$gpname')";
        $this->db->query($query);
        $lastid = $this->get_table_last_id('uk_practice');
        return $lastid;
    }

    function add_user_to_practice($practiceid, $userid) {
        $query = "insert into uk_practice_members "
                . "(practiceid,userid) "
                . "values ($practiceid,$userid)";
        $this->db->query($query);
    }

    function add_new_practice($p) {

        /*
         * 
          stdClass Object
          (
          [new_ccg] => West Birmingham
          [cohortid] => 0
          [gpname] => Greenlane practice
          [firstname] => John
          [lastname] => Connair
          [email] => john@alex.com
          [pwd] => abba1abba2
          [gpcourses] => Array
          (
          [0] => 163
          [1] => 164
          [2] => 165
          [3] => 166
          [4] => 167
          [5] => 168
          [6] => 169
          )
          )
         * 
         */


        // 1. Create GP Admin account
        // 2. Create system cohort
        // 3. Add user to this cohort
        // 4. Iterate over courses list and create corresponded course groups
        // 5. Add user to above groups 

        $user = new stdClass();
        $user->firstname = $p->firstname;
        $user->lastname = $p->lastname;
        $user->email = $p->email;
        $user->pwd = $p->pwd;

        $status = $this->create_user($user);
        if ($status) {
            $dbuser = $this->get_user_data_by_email($p->email); // object
            $userid = $dbuser->id;
            $this->assign_gp_admin_role($userid);
            $m = new Mailer();
            $m->send_account_confirmation_message($user);
            if (trim($p->new_ccg) == '') {
                // Cohort already exists
                $this->add_user_to_cohort($p->cohortid, $userid);
                $cohort_name = $this->get_cohort_name($p->cohortid);
                $practicename = $cohort_name . " - " . $p->gpname;
                $practiceid = $this->create_practice($p->cohortid, $userid, $practicename);
                $this->add_user_to_practice($practiceid, $userid);
                foreach ($p->gpcourses as $courseid) {
                    $coursename = $this->get_course_name($courseid);
                    $group_name = $cohort_name . " - " . $p->gpname . " - " . $coursename;
                    $groupid = $this->create_group($courseid, $group_name);
                    $this->add_user_to_group($groupid, $userid);
                    $this->attach_course_to_group($courseid, $groupid);
                    $this->attach_course_to_cohort($p->cohortid, $courseid, $groupid);
                } // end foreach
            } // end if $p->gpname==''
            else {
                // Cohort need to be created
                $cohortid = $this->create_cohort($p->new_ccg);
                $this->add_user_to_cohort($cohortid, $userid);
                $practicename = $p->new_ccg . " - " . $p->gpname;
                $practiceid = $this->create_practice($cohortid, $userid, $practicename);
                $this->add_user_to_practice($practiceid, $userid);
                foreach ($p->gpcourses as $courseid) {
                    $coursename = $this->get_course_name($courseid);
                    $group_name = $p->new_ccg . " - " . $p->gpname . " - " . $coursename;
                    $groupid = $this->create_group($courseid, $group_name);
                    $this->add_user_to_group($groupid, $userid);
                    $this->attach_course_to_group($courseid, $groupid);
                    $this->attach_course_to_cohort($cohortid, $courseid, $groupid);
                } // end foreach
            } // end else
        }
    }

    function remove_practice_cohort_data($cohortid, $users) {
        foreach ($users as $userid) {
            $query = "delete from uk_cohort_members "
                    . "where cohortid=$cohortid and userid=$userid";
            $this->db->query($query);
        }
    }

    function remove_practice_groups_data($groups) {
        foreach ($groups as $groupid) {
            $query = "delete from uk_groups_members where groupid=$groupid";
            $this->db->query($query);

            $query = "delete from uk_groups where id=$groupid";
            $this->db->query($query);
        }
    }

    function remove_role_assignments($users) {
        foreach ($users as $userid) {
            $query = "delete from uk_role_assignments where userid=$userid";
            $this->db->query($query);

            $query = "delete from uk_user_enrolments where userid=$userid";
            $this->db->query($query);
        }
    }

    function remove_practice_course_links($groups) {
        foreach ($groups as $groupid) {
            $query = "delete from uk_courses2cohorts where groupid=$groupid";
            $this->db->query($query);
        }
    }

    function remove_practice_users($users) {
        foreach ($users as $userid) {
            $query = "delete from uk_practice_members where userid=$userid";
            $this->db->query($query);

            $query = "update uk_user set deleted=1 where id=$userid";
            $this->db->query($query);
        }
    }

    function delete_pracrtice($practiceid) {
        $query = "delete from uk_practice where id=$practiceid";
        $this->db->query($query);
    }

    function del_practice($userid) {
        // $userid - is practice manager user
        // 1. Get practice cohort, groups and users
        // 2. Remove users from cohort
        // 3. Delete cohort
        // 4. Remove all users from practice groups
        // 5. Delete all groups
        // 6. Delete user accounts

        $groups = array();
        $users = array();

        $query = "select * from uk_practice where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cohortid = $row['cohortid'];
            $practiceid = $row['id'];
        }

        $query = "select * from uk_practice_members where practiceid=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['userid'];
        }

        $query = "select * from uk_groups_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groups[] = $row['groupid'];
        }


        echo "<pre>";
        print_r($users);
        echo "</pre><br>-------------------------<br>";

        echo "<pre>";
        print_r($groups);
        echo "</pre>";


        $this->remove_practice_course_links($groups);
        $this->remove_role_assignments($users);
        $this->remove_practice_groups_data($groups);
        $this->remove_practice_cohort_data($cohortid, $users);
        $this->remove_practice_users($users);
        $this->delete_pracrtice($practiceid);
    }

    function get_add_gp_dialog() {

        $list = "";
        $cohorts = $this->get_cohorts_list();
        $categories = $this->get_practice_types();
        $courses = $this->get_practice_courses();
        $list.="<div id='myModal' class='modal fade' style='overflow: visible;width:1000px;margin-left:0px;left:15%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width:1000px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Green Practice</h4>
                </div>
                <div class='modal-body' style='max-height:875px;'>
                
                <!-- Start of external div -->
                
                <div class='container-fluid' style=''>
                
                <span class='span3' style='border: 0px solid'>
                
                <div class='container-fluid'>
                <span class='span2'>Clinical group*</span>
                <span class='span6'>$cohorts</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'><input type='checkbox' id='create_new_ccg'>Create new group</span>
                <span class='span6'><input type='text' id='new_ccg' disabled></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Practice name*</span>
                <span class='span6'><input type='text' id='gpname'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Manager firstname*</span>
                <span class='span6'><input type='text' id='gpfirstname'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Manager lastname*</span>
                <span class='span6'><input type='text' id='gplastname'></span>
                </div>
                
                </span>
                
                <span class='span3' style='border: 0px solid'>
                
                <div class='container-fluid'>
                <span class='span2'>Practice type*</span>
                <span class='span6'>$categories</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Practice courses*</span>
                <span class='span6' id='courses_container'>$courses</span>
                </div>
                
                <div class='container-fluid' style='padding-top:3px;'>
                <span class='span2'>Manager email*</span>
                <span class='span6'><input type='text' id='gpemail'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Manager password*</span>
                <span class='span6'><input type='password' id='gppwd'></span>
                </div>

                </span>
                
                </div>
                <!-- End of external div -->

                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='gp_err'></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;display:none;' id='gp_loader'><img src='http://'" . $_SERVER['SERVER_NAME'] . "/assests/img/loader.gif'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='add_gp_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_practice_admin_modal_dialog($userid) {
        $list = "";
        $query = "select * from uk_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $email = $row['email'];
        }

        $list.="<div id='myModal' class='modal fade' style='width:675px;margin-left:0px;left:25%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Practice Manager</h4>
                </div>
                <div class='modal-body' style='max-height:875px;width:675px;'>
                <input type='hidden' id='gpadmin_userid' value='$userid'>
                <div class='container-fluid'>
                <span class='span1'>Firstname*</span>
                <span class='span2'><input type='text' id='gpadmin_firstname' value='$firstname'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Lastname*</span>
                <span class='span2'><input type='text' id='gpadmin_lastname' value='$lastname'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Email*</span>
                <span class='span2'><input type='text' id='gpadmin_email' value='$email'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Password</span>
                <span class='span2'><input type='password' id='gpadmin_pwd' value=''></span>
                </div>
            
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span6' style='color:red;' id='gpadmin_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='gpadmin_update_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_practice_course_id($groupid) {
        $query = "select * from uk_courses2cohorts where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    function get_practice_courses_block($groups) {

        //echo "get_practice_courses_block groups ...<br>";
        //print_r($groups);

        $list = "";
        $list.="<div class='row-fluid' id='existing_practice_courses_container'>";
        $list.="<ul>";
        foreach ($groups as $groupid) {
            //echo "Grpoup ID: ".$groupid."<br>";
            if ($groupid > 0) {
                $courseid = $this->get_practice_course_id($groupid);
                if ($courseid > 0) {
                    $coursename = $this->get_course_name($courseid);
                    $list.="<div class='row-fluid'>";
                    $list.="<span class='span6'>$coursename</span><span class='span1'><i id='practice_course_$groupid' style='cursor:pointer;' class='fa fa-trash' title='Delete' aria-hidden='true'></i></span>";
                    $list.="</div>";
                } // end if
            } // end if $groupid > 0
        }
        $list.="</ul>";
        $list.="</div>";
        return $list;
    }

    function remove_course_from_practice($userid, $groupid) {
        // 1. Remove users from the group
        // 2  Remove group
        // 3  Remove course links
        // 4  Remove course from duration table

        $query = "select * from uk_groups where id=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }

        $query = "delete from uk_groups_members where groupid=$groupid";
        $this->db->query($query);

        $query = "delete from uk_groups where id=$groupid";
        $this->db->query($query);

        $query = "delete from uk_courses2cohorts where groupid=$groupid";
        $this->db->query($query);

        $query = "select * from uk_practice_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $practiceid = $row['practiceid'];
        }

        $query = "delete from uk_practice_course_duration "
                . "where practiceid=$practiceid "
                . "and courseid=$courseid";
        $this->db->query($query);

        $query = "select * from uk_groups_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groups[] = $row['groupid'];
        }

        return $this->get_practice_courses_block($groups);
    }

    function get_pracrice_course_dialog($practiceid) {
        $list = "";
        $groups = array();
        $courses = array();
        $query = "select * from uk_practice where id=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $userid = $row['userid'];
        }

        $query = "select * from uk_groups_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groups[] = $row['groupid'];
        }

        foreach ($groups as $groupid) {
            $query = "select * from uk_courses2cohorts where groupid=$groupid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['courseid'];
            }
        }

        $practice_courses = $this->get_practice_courses_block($groups);
        $pcategories = $this->get_practice_types();
        $pcourses = $this->get_practice_courses();

        // ********* Rendering part *********** /

        $list.="<div id='myModal' class='modal fade' style='width:700px;margin-left:0px;left:25%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Practice Courses</h4>
                </div>
                <div class='modal-body' style='max-height:575px;width:660px;'>
                <input type='hidden' id='practiceid' value='$practiceid'>
                <input type='hidden' id='gpadmin_userid' value='$userid'>    
                <div class='container-fluid' style='font-weight:bold;'>
                <span class='span3'>Existing courses</span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6'>$practice_courses</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span6'><hr></span>
                </div>
                
                <div class='container-fluid' style='font-weight:bold;'>
                <span class='span3'>Add new courses</span>
                </div>
                
                <ul>
                <div class='container-fluid' style=''>
                <span class='span2'>Practice type</span>
                <span class='span3'>$pcategories</span>
                </div>
                    
                <div class='container-fluid' style=''>
                <span class='span2'>Practice courses</span>
                <span class='span3' id='courses_container'>$pcourses</span>
                </div>
                </ul>
                
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='practice_add_course_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_course_to_practice($practice) {
        $practiceid = $practice->practiceid;
        $courses = $practice->courses;
        $userid = $practice->userid;
        $comp = new Completion();
        $query = "select * from uk_practice where id=$practiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $practicename = $row['name'];
            $cohortid = $row['cohortid'];
        }
        $cohort_name = $this->get_cohort_name($cohortid);

        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $scorm = $comp->has_scorm_section($courseid);
                if ($scorm > 0) {
                    $coursename = $this->get_course_name($courseid);
                    // practice name already includes CCG name
                    $groupname = $practicename . " - " . $coursename;
                    $groupid = $this->create_group($courseid, $groupname);
                    $this->add_user_to_group($groupid, $userid);
                    $this->attach_course_to_cohort($cohortid, $courseid, $groupid);
                } // end if $scorm > 0
            } // end foreach
        } // end if count($courses)>0
    }

    function update_gpadmin($user) {
        if ($user->pwd != '') {
            $hashed_pwd = hash_internal_user_password($user->pwd);
            $query = "update uk_user "
                    . "set firstname='$user->firstname', "
                    . "lastname='$user->lastname', "
                    . "email='$user->email', password='$hashed_pwd' "
                    . "where id=$user->userid";
        } // end if
        else {
            $query = "update uk_user "
                    . "set firstname='$user->firstname', "
                    . "lastname='$user->lastname', "
                    . "email='$user->email' "
                    . "where id=$user->userid";
        } // end else
        $this->db->query($query);
    }

}
