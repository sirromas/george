<?php

require_once './classes/Suites.php';
$l = new Suites();
$id = $_POST['id'];
$list = $l->get_edit_suite_dialog($id);
echo $list;
