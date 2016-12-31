<?php

require_once './classes/Pages.php';
$p = new Pages();
$id = $_POST['id'];
$list = $p->get_edit_suite_dialog($id);
echo $list;
