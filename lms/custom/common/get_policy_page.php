<?php

require_once './classes/Courses.php';
$c = new Courses();
$userid = $_POST['userid'];
$list = $c->get_policy_page($userid);
echo $list;
