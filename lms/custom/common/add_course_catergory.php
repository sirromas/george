<?php

require_once './classes/Courses.php';
$c = new Courses();
$name = $_POST['name'];
$c->add_course_category($name);
