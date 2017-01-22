<?php

require_once './classes/Users.php';
$u = new Users();
$userid = $_POST['userid'];
$list = $u->get_users_page($userid);
echo $list;

