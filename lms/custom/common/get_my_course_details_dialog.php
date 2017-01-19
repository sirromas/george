<?php

require_once './classes/Courses.php';
$c = new Courses();
$course = $_POST['course'];
$list = $c->get_my_course_details(json_decode($course));
echo $list;
