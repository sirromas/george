<?php

require_once './classes/Groups.php';
$g = new Groups();
$groupid = $_POST['groupid'];
$userid = $_POST['userid'];
$list = $g->remove_course_from_practice($userid, $groupid);
echo $list;
