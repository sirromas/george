<?php

require_once './classes/Pages.php';
$p = new Pages();
$id = $_POST['id'];
$p->del_suite($id);
