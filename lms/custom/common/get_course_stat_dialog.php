<?php

require_once './classes/Courses.php';
$c = new Courses();
$course = $_POST['course'];
$list = $c->get_course_stat_dialog(json_decode($course));
echo $list;
