<?php

require_once './classes/Users.php';
$u = new Users();
$userid = $_POST['userid'];
$list = $u->get_add_user_dialog($userid);
echo $list;
