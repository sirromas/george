<?php

require_once './classes/Courses.php';
$c = new Courses();
$list = $c->get_user_training_report();
echo $list;
