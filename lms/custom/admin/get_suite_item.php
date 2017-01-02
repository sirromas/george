<?php

require_once './classes/Pages.php';
$p = new Pages();
$page = $_POST['id'];
$list = $p->get_suite_item($page);
echo $list;
