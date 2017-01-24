<?php

require_once './classes/Courses.php';
$c = new Courses();
$course = $_POST['course'];
$c->update_practice_course_duration(json_decode($course));

