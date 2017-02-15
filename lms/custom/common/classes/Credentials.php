<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Credentials
 *
 * @author moyo
 */
class Credentials extends Utils {

    function __construct() {
        parent::__construct();
    }

    function check_credentials($user) {

        echo "<pre>";
        print_r($user);
        echo "</pre>";

        $hashed_pwd = hash_internal_user_password($user->p);
        $hashed_pwd = md5($user->p);
        $query = "select * from uk_user "
                . "where username='$user->u' "
                . "and password='$hashed_pwd'";
        echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        return $num;
    }

}
