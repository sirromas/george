<?php

require_once './classes/Users.php';
$u = new Users();
$userid = $_POST['userid'];
$u->delete_user($userid);
