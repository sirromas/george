<?php

require_once './classes/Groups.php';
$g = new Groups();
$user = $_POST['user'];
$g->update_gpadmin(json_decode($user));