<?php

require_once './classes/Courses.php';
$c = new Courses();
$year = $_POST['year'];
$userid = $_POST['userid'];
$list = $c->get_course_by_year($userid, $year);
echo $list;


