<?php

require_once './classes/Users.php';
$u = new Users();
$user = $_POST['user'];
$list = $u->update_user_courses(json_decode($user));

