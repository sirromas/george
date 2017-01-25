<?php

require_once './classes/Courses.php';
$c = new Courses();
$course = $_POST['course'];
$c->update_external_course_status(json_decode($course));
