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
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>First Name*</span>";
        $list.="<span class='span5'><input type='text' id='firstname' value='$user->firstname'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Last Name*</span>";
        $list.="<span class='span5'><input type='text' id='lastname' value='$user->lastname'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Email*</span>";
        $list.="<span class='span5'><input type='text' id='email' value='$user->email'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Password</span>";
        $list.="<span class='span5'><input type='password' id='pwd' value=''></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span7'style='color:red;' id='profile_err'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>&nbsp;</span>";
        $list.="<span class='span1'><button id='update_profile'>Update</button></span>";
        $list.="</div>";

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
