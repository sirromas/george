<?php

require_once './classes/Pages.php';
$p = new Pages();
$id = 1;
$list = $p->get_elearning_suites_page($id);
echo $list;
