<?php

require_once './classes/Suites.php';
$l = new Suites();
$id = $_POST['id'];
$l->del_suite($id);
