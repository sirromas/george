<?php

require_once './classes/Groups.php';
$g = new Groups();
$course = $_POST['course'];
$g->update_user_course_duration(json_decode($course));
