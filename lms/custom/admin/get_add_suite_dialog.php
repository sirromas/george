<?php

require_once './classes/Pages.php';
$p = new Pages();
$list = $p->get_add_suite_dialog();
echo $list;
