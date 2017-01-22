<?php

require_once './classes/Users.php';
$u = new Users();
$current_user = $_POST['current_user'];
$userid = $_POST['userid'];
$list = $u->get_edit_user_dialog($current_user, $userid);
echo $list;
