<?php

require_once './classes/Groups.php';
$g = new Groups();
$userid = $_POST['userid'];
$list = $g->get_user_course_detailes($userid);
echo $list;
