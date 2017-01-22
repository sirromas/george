<?php

require_once './classes/Users.php';
$u = new Users();
$courseid = $_POST['courseid'];
$userid = $_POST['userid'];
$list = $u->remove_user_from_course($courseid, $userid);
echo $list;
