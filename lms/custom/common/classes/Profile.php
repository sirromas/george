<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

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
        $list.="<div class='row-fluid' style='font-weight:bold;margin-left:15px;'>";
        $list.="<span class='span3'>My Profile</span><span style='padding-left:40px;color:red;' class='span7' id='profile_err'></span>";
        $list.="</div>";

        $list.="<table class='table table-striped table-bordered' cellspacing='0' width='50%'>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>First Name*</td>";
        $list.="<td style='padding:15px;'><input type='text' id='firstname' value='$user->firstname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Last Name*</td>";
        $list.="<td style='padding:15px;'><input type='text' id='lastname' value='$user->lastname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Email*</td>";
        $list.="<td style='padding:15px;'><input type='text' id='email' value='$user->email'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Password</td>";
        $list.="<td style='padding:15px;'><input type='password' id='pwd' value=''></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>&nbsp;</td>";
        $list.="<td style='padding:15px;'><button id='update_profile'>Update</button></td>";
        $list.="</tr>";

        $list.="</table>";

        

        return $list;
    }

    function update_profile($pr) {
        //$2y$10$y0aoGlvfH8Mr5DfUx39zAei8CjaKbJHzIWN/vIx/7fTiohxsjcbpm 
        //hash_internal_user_password
        $hashed_pwd = hash_internal_user_password($pr->pwd);
        if ($pr->pwd == '') {
            $query = "update uk_user set "
                    . "firstname='$pr->firstname', "
                    . "lastname='$pr->lastname', email='$pr->email' "
                    . "where id=$pr->id";
        } // end if
        else {
            $query = "update uk_user set "
                    . "firstname='$pr->firstname', "
                    . "lastname='$pr->lastname', email='$pr->email', "
                    . "password='$hashed_pwd' "
                    . "where id=$pr->id";
        }
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
        $list = "Profile has been updated";
        return $list;
    }

}
