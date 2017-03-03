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

    function is_practice_group_exists($practiceid, $name) {
        $query = "select * from uk_practice_groups "
                . "where practiceid=$practiceid "
                . "and name='$name'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function create_practice_initial_groups($practiceid) {
        $name = 'All Training';
        $status = $this->is_practice_group_exists($practiceid, $name);
        if ($status == 0) {
            $query = "insert into uk_practice_groups (practiceid,name) "
                    . "values ($practiceid,$name)";
            $this->db->query($query);
        } // end if $status==0
    }

    function get_group_course_number($name) {
        
    }

    function get_practice_group_name($groupid) {
        $query = "select * from uk_practice_groups where id=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
        }
        return $name;
    }

    function get_practice_group_courses($practiceid, $groupid) {
        $query = "select * from uk_practice_groups2courses "
                . "where practiceid=$practiceid "
                . "and groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = $row['courseid'];
        }
        return $courses;
    }

    function get_practice_group_course_link_status($practiceid, $groupid, $courseid) {
        $query = "select * from uk_practice_groups2courses "
                . "where practiceid=$practiceid and groupid=$groupid "
                . "and courseid=$courseid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        //echo "Num: ".$num."<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $row['linked'];
            }
        } // end if $num>0
        else {
            $status = 0;
        }
        return $status;
    }

    function get_group2course_record_id($practiceid, $groupid, $courseid) {
        $query = "select * from uk_practice_groups2courses "
                . "where practiceid=$practiceid and groupid=$groupid "
                . "and courseid=$courseid";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function update_practice_group_course_status($id) {
        $query = "select * from uk_practice_groups2courses where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $link = $row['linked'];
        }
        $status = ($link == 1) ? 0 : 1;
        $query = "update uk_practice_groups2courses set linked='$status' where id=$id";
        $this->db->query($query);
    }

    function get_practice_group_courses_block($practiceid, $groupid) {
        $list = "";
        //$courses = $this->get_practice_group_courses($practiceid, $groupid);
        $courses = $this->get_all_courses();
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $coursename = $this->get_course_name($courseid);
                $status = $this->get_practice_group_course_link_status($practiceid, $groupid, $courseid);
                $id = $this->get_practice_group_course_table_id($groupid, $courseid);
                $checkbox = ($status == 1) ? "<input type='checkbox' id='gp_courses_$id' checked>" : "<input type='checkbox' id='gp_courses_$id'>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span4'>$coursename</span>";
                $list.="<span class='span3' style='text-align:center;'>$checkbox</span>";
                $list.="</div>";
            } // end foreach
        } // end if count($courses)>0
        return $list;
    }

    function get_gp_groups_page($userid) {
        $list = "";
        $practiceid = $this->get_user_practiceid($userid);
        $query = "select * from uk_practice_groups where practiceid=$practiceid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $groups[] = $row['id'];
            } // end while
        } // end if $num > 0

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'><button id='add_group'>Add Group</button></span>";
        $list.="</div>";

        $list.="<table id='all_groups' class='table table-striped table-bordered' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Group Name</th>";
        $list.="<th>Course Name</th>";
        $list.="<th style='text-align:center;'>Assigned to group?</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";
        if (count($groups) > 0) {
            $system_courses = $this->get_all_courses();
            foreach ($groups as $groupid) {
                foreach ($system_courses as $courseid) {
                    $groupname = $this->get_practice_group_name($groupid);
                    $coursename = $this->get_course_name($courseid);
                    $id = $this->get_group2course_record_id($practiceid, $groupid, $courseid);
                    $status = $this->get_practice_group_course_link_status($practiceid, $groupid, $courseid);
                    $checkbox = ($status == 1) ? "<input type='checkbox' id='gp_courses_$id' checked>" : "<input type='checkbox' id='gp_courses_$id'>";
                    $list.="<tr>";
                    $list.="<td>$groupname</td>";
                    $list.="<td>$coursename</td>";
                    $list.="<td style='text-align:center;'>$checkbox</td>";
                    $list.="</tr>";
                } // end foreach
            } // end foreach
        } // end if count($groupd)>0

        $list.="</tbody>";
        $list.="</table>";

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
        $list.="<span class='span9'><button id='add_gp'>Add Account</button></span>";
        $list.="</div>";
        if (count($groups) > 0) {

            $list.="<table id='all_groups' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Clinical Group</th>";
            $list.="<th>Practice Name</th>";
            $list.="<th>Practice Admin User</th>";
            $list.="<th>Admin User Email</th>";
            $list.="<th>Actions</th>";
            $list.="</tr>";
            $list.="</thead>";
            $list.="<tbody>";
            foreach ($groups as $g) {
                $cohortname = $this->get_cohort_name($g->cohortid);
                $adminuser = $this->get_user_data_by_id($g->userid); // object
                $pr_names = explode('-', $g->name);
                $prname = $pr_names[1];
                if ($prname == '') {
                    $prname = 'Admin Practice';
                }
                $list.="<tr>";
                $list.="<td>$cohortname</td>";
                $list.="<td>$prname</td>";
                $list.="<td>$adminuser->firstname $adminuser->lastname</td>";
                $list.="<td><a href='mailto:$adminuser->email'>$adminuser->email</a></td>";
                if ($g->userid != 2) {
                    $list.="<td><i id='group_info_userid_$g->userid' style='cursor:pointer;' class='fa fa-user-circle-o' aria-hidden='true' title='User data'></i>"
                            //. "<i  id='group_practiceid_$g->id' style='cursor:pointer;padding-left:15px;' class='fa fa-podcast' aria-hidden='true'></i>"
                            . "<i  id='group_delete_userid_$g->userid' style='cursor:pointer;padding-left:15px;' class='fa fa-trash' title='Delete' aria-hidden='true'></i>"
                            . "<i  id='group_setup_userid_$g->userid' style='cursor:pointer;padding-left:15px;' class='fa fa-share' title='Send Setup Email' aria-hidden='true'></i></td>";
                } // end if
                else {
                    $list.="<td><i id='group_info_userid_$g->userid' style='cursor:pointer;' class='fa fa-user-circle-o' aria-hidden='true' title='User data'></i>"
                            //. "<i  id='group_practiceid_$g->id' style='cursor:pointer;padding-left:15px;' class='fa fa-podcast' aria-hidden='true'></i>"
                            //. "<i  id='group_delete_userid_$g->userid' style='cursor:pointer;padding-left:15px;' class='fa fa-trash' title='Delete' aria-hidden='true'></i>"
                            . "</td>";
                }
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

    function create_practice_group($practiceid, $name, $all = 0) {
        if ($all == 1) {
            $query = "insert into uk_practice_groups "
                    . "(practiceid,p_all,name) "
                    . "values ($practiceid,1,'$name')";
        } // end uf $all==1
        else {
            $query = "insert into uk_practice_groups "
                    . "(practiceid,p_all,name) "
                    . "values ($practiceid,0,'$name')";
        }
        $this->db->query($query);
    }

    function get_category_courses($groupname) {
        $query = "select * from uk_preset_groups  where name='$groupname' ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['id'];
        }

        $query = "select * from uk_preset_group_courses where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = $row['courseid'];
        }
        return $courses;
    }

    function attach_group_courses($practiceid, $groupname, $all = 0, $new = FALSE) {
        $system_courses = $this->get_all_courses();
        $query = "select * from uk_practice_groups "
                . "where practiceid=$practiceid "
                . "and name='$groupname'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['id'];
        }

        if ($new == false) {
            if ($all == 0) {
                // Get group preset courses from template
                $group_courses = $this->get_category_courses($groupname);
                foreach ($system_courses as $courseid) {
                    if (in_array($courseid, $group_courses)) {
                        $status = 1;
                    } // end if
                    else {
                        $status = 0;
                    } // end else
                    $this->add_course_to_practice_group($practiceid, $groupid, $courseid, $status);
                } // end foreach
            } // end if $all == 0
            else {
                // Attach all system courses
                foreach ($system_courses as $courseid) {
                    $status = 1;
                    $this->add_course_to_practice_group($practiceid, $groupid, $courseid, $status);
                } // end foreach
            } // end else
        } // end if $new==false
        else {
            foreach ($system_courses as $courseid) {
                $status = 0;
                $this->add_course_to_practice_group($practiceid, $groupid, $courseid, $status);
            } // end foreach
        } // end else
    }

    function add_course_to_practice_group($practiceid, $groupid, $courseid, $status) {
        $query = "insert into uk_practice_groups2courses "
                . "(practiceid,"
                . "groupid, linked, "
                . "courseid) "
                . "values ($practiceid,"
                . "$groupid, $status, "
                . "$courseid)";
        $this->db->query($query);
    }

    function get_course_categories() {
        $query = "select * from uk_preset_groups ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['id'];
        }
        return $categories;
    }

    function get_category_name($categoryid) {
        $query = "select * from uk_preset_groups where id=$categoryid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
        }
        return $name;
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
        $user->pname = $p->gpname;

        /*
          echo "<pre>";
          print_r($user);
          echo "</pre>";
          die();
         */

        $this->create_user($user);
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
        } // end if $p->gpname==''
        else {
            // Cohort need to be created
            $cohortid = $this->create_cohort($p->new_ccg);
            $this->add_user_to_cohort($cohortid, $userid);
            $practicename = $p->new_ccg . " - " . $p->gpname;
            $practiceid = $this->create_practice($cohortid, $userid, $practicename);
            $this->add_user_to_practice($practiceid, $userid);
        } // end else
        // Create all training practice group
        $groupname = 'All Training';
        $this->create_practice_group($practiceid, $groupname, 1);
        $this->attach_group_courses($practiceid, $groupname, 1);

        // Create other practice groups
        $categories = $this->get_course_categories();
        if (count($categories) > 0) {
            foreach ($categories as $categoryid) {
                $categoryname = $this->get_category_name($categoryid);
                $this->create_practice_group($practiceid, $categoryname, 0);
                $this->attach_group_courses($practiceid, $categoryname, 0);
            } // end foreach
        } // end if count($categories)>0
    }

    function add_new_practice_group($g) {
        $this->create_practice_group($g->practiceid, $g->name, 0);
        $this->attach_group_courses($g->practiceid, $g->name, 0, TRUE);
    }

    function create_user_password($length = 25) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function send_setup_email($userid) {
        $practicename = $this->get_practice_name_by_userid($userid);
        $pwd = $this->create_user_password(8);
        $hashed_pwd = hash_internal_user_password($pwd);
        $query = "update uk_user set password='$hashed_pwd' where id=$userid";
        $this->db->query($query);
        $user = $this->get_user_data_by_id($userid);
        $user->pname = $practicename;
        $user->pwd = $pwd;
        $m = new Mailer();
        $m->send_account_confirmation_message($user);
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
        //$categories = $this->get_practice_types();
        //$courses = $this->get_practice_courses();
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
                  
                </span>
                
                <span class='span3' style='border: 0px solid'>
                
                <div class='container-fluid' style='padding-top:3px;'>
                <span class='span2'>Manager email*</span>
                <span class='span6'><input type='text' id='gpemail'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Manager password*</span>
                <span class='span6'><input type='password' id='gppwd'></span>
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

    function get_practice_id($userid) {
        $query = "select * from uk_practice_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $practiceid = $row['practiceid'];
        }
        return $practiceid;
    }

    function get_add_pgroup_dialog() {

        $userid = $this->user->id;
        $practiceid = $this->get_practice_id($userid);
        $list.="<div id='myModal' class='modal fade' style='width:675px;margin-left:0px;left:25%;'>
        <div class='modal-dialog' >
            <div class='modal-content' style='width'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Group</h4>
                </div>
                <div class='modal-body' style='max-height:875px;width:675px;'>
                
                <input type='hidden' id='gpadmin_userid' value='$userid'>
                <input type='hidden' id='gpadmin_practiceid' value='$practiceid'>
                
                <div class='container-fluid' style=''>
                <span class='span1'>Name *</span>
                <span class='span2'><input type='text' id='gname'></span>
                </div>
            
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span2' style='color:red;' id='gpadmin_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span class='span1'>&nbsp;</span>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='gpadmin_add_group_button'>OK</button></span>
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

    function update_user_course_duration($c) {
        $query = "select * from uk_practice_user_course_duration "
                . "where practiceid=$c->practiceid "
                . "and userid=$c->userid and courseid=$c->courseid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $query = "insert into uk_practice_user_course_duration "
                    . "(practiceid,"
                    . "courseid,"
                    . "userid,"
                    . "duration) "
                    . "values ($c->practiceid,"
                    . "$c->courseid,"
                    . "$c->userid,"
                    . "$c->duration)";
        } // end if 
        else {
            $query = "update uk_practice_user_course_duration "
                    . "set duration=$c->duration "
                    . "where practiceid=$c->practiceid "
                    . "and userid=$c->userid "
                    . "and courseid=$c->courseid";
        } // end else
        $this->db->query($query);
    }

    function get_practice_user_duration_box($practiceid, $userid, $courseid) {
        $list = "";

        $list.= "<select id='select_u_c_d_$courseid'>";

        $query = "select * from uk_practice_user_course_duration "
                . "where practiceid=$practiceid "
                . "and userid=$userid and courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $list.="<option value='0' selected>Change to</option>";
            for ($i = 1; $i <= 12; $i++) {
                $list.="<option value='$i'>$i</option>";
            } // end for
        } // end if $num == 0
        else {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $duration = $row['duration'];
            }
            for ($i = 1; $i <= 12; $i++) {
                if ($i == $duration) {
                    $list.="<option value='$i' selected>$i</option>";
                } // end if $i==$duration
                else {
                    $list.="<option value='$i'>$i</option>";
                } // end else
            } // end for
        } // end else

        $list.= "</select>";

        return $list;
    }

    function get_user_course_detailes($userid) {
        $list = "";

        //uk_practice_user_course_duration
        $system_courses = $this->get_all_courses();
        $user_courses = $this->get_user_courses($userid);
        $practiceid = $this->get_user_practiceid($userid);
        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'><button id='get_user_page'>Back</button></span>";
        $list.="<input type='hidden' id='gp_courses_userid' value='$userid'>";
        $list.="<input type='hidden' id='gp_practiceid' value='$practiceid'>";
        $list.="</div>";

        $list.="<table id='user_courses' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Course Name</th>";
        $list.="<th style='text-align:center;'><span style='padding-left:35px'><input type='checkbox' id='user_check_all_courses'>All</span></th>";
        $list.="<th style='text-align:center;'>Repeat Duration (Group Override)</th>";
        $list.="</tr>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";

        foreach ($system_courses as $courseid) {
            $coursename = $this->get_course_name($courseid);
            $status = in_array($courseid, $user_courses);
            $checkbox = ($status == true) ? "<input type='checkbox' id='u_p_$courseid' checked>" : "<input type='checkbox' id='u_p_$courseid'>";
            $duration_box = $this->get_practice_user_duration_box($practiceid, $userid, $courseid);
            $list.="<tr>";
            $list.="<td>$coursename</td>";
            $list.="<td style='text-align:center;'>$checkbox</td>";
            $list.="<td style='text-align:center;'>$duration_box</td>";
            $list.="</tr>";
        } // end foreach

        $list.="</tbody>";

        $list.="</table>";

        return $list;
    }

}
