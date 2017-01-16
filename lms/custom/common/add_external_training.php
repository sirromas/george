<?php

require_once './classes/Courses.php';
$c = new Courses();
$course = $_POST['course'];
$c->add_external_course(json_decode($course));

