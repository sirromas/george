<?php

require_once './classes/Courses.php';
$c = new Courses();
$courseid = $_POST['courseid'];
$list = $c->get_upload_policy_dialog($courseid);
echo $list;
