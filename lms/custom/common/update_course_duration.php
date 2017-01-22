<?php

require_once './classes/Courses.php';
$c = new Courses();
$courseid = $_POST['courseid'];
$duration = $_POST['duration'];
$c->update_course_duration($courseid, $duration);
