<?php

require_once './classes/Courses.php';
$c = new Courses();
$list = $c->get_personal_external_training_courses($c->user->id);
echo $list;
