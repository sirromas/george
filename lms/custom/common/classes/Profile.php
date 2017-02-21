<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/mailer/Mailer.php';

class Profile extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_user_profile($userid) {
        $list = "";

        $query = "select * from uk_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
        }

        $list.="<input type='hidden' id='id' value='$userid'>";
        //$list.="<div class='row-fluid' style='font-weight:bold;margin-left:15px;'>";
        //$list.="<span class='span3'>My Profile</span><span style='padding-left:40px;color:red;' class='span7' width='220px' height='30px' id='profile_err'></span>";
        //$list.="</div>";

        $list.="<table class='table table-striped table-bordered' cellspacing='0' width='50%'>";

        $list.="<tr style='font-weight:bold;'>";
        $list.="<td style='padding:15px;'>My Profile</td>";
        $list.="<td style='padding:15px;'><span style='color:red;' class='span7' width='220px' height='30px' id='profile_err'></td>";
        $list.="</tr>";
        
        $list.="<tr>";
        $list.="<td style='padding:15px;'>First Name *</td>";
        $list.="<td style='padding:15px;'><input type='text' id='firstname' value='$user->firstname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Last Name *</td>";
        $list.="<td style='padding:15px;'><input type='text' id='lastname' value='$user->lastname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Email *</td>";
        $list.="<td style='padding:15px;'><input type='text' id='email' value='$user->email'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Password *</td>";
        $list.="<td style='padding:15px;'><input type='password' id='pwd' value=''></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'><span>* Required fields</span></td>";
        $list.="<td style='padding:15px;'><button id='update_profile'>Update</button> &nbsp;&nbsp; <span id='profile_ajax' style='display:none;'><img src='http://mycodebusters.com/assets/img/loader2.gif' width='32px' height='32px'></span></td>";
        $list.="</tr>";

        $list.="</table>";

        return $list;
    }

    function update_profile($pr) {
        $hashed_pwd = hash_internal_user_password($pr->pwd);
        if ($pr->pwd == '') {
            $query = "update uk_user set "
                    . "firstname='$pr->firstname', "
                    . "lastname='$pr->lastname', email='$pr->email', username='$pr->email' "
                    . "where id=$pr->id";
        } // end if
        else {
            $query = "update uk_user set "
                    . "firstname='$pr->firstname', "
                    . "lastname='$pr->lastname', email='$pr->email', username='$pr->email', "
                    . "password='$hashed_pwd' "
                    . "where id=$pr->id";
        }
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
        $pname = $this->get_practice_name_by_userid($pr->id);
        $pr->pname = $pname;
        $m = new Mailer();
        $m->send_update_credentials_letter($pr);
        $list = "Profile has been updated";
        return $list;
    }

}
