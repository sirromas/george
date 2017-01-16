<?php

require_once './classes/Courses.php';
$c = new Courses();
$list = $c->get_add_external_training_dialog($c->user->id);
echo $list;
