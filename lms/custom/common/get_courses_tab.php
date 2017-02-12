<?php

require_once './classes/Courses.php';
$c = new Courses();
$list = $c->get_courses_tab();
echo $list;
