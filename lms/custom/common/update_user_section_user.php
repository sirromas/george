<?php

require_once './classes/Users.php';
$u = new Users();
$user = $_POST['user'];
$u->update_user(json_decode($user));
