<?php

require_once './classes/Users.php';
$u = new Users();
$course = $_POST['course'];
$u->update_all_user_courses(json_decode($course));
