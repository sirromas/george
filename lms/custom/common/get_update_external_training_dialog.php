<?php

require_once './classes/Courses.php';
$c = new Courses();
$courseid = $_POST['courseid'];
$list = $c->get_update_external_training_dialog($courseid);
echo $list;
