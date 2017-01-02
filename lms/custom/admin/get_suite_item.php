<?php

require_once './classes/Suites.php';
$l = new Suites();
$page = $_POST['id'];
$list = $l->get_suite_item($page);
echo $list;
