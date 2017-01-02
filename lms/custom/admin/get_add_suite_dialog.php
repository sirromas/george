<?php

require_once './classes/Suites.php';
$l = new Suites();
$list = $l->get_add_suite_dialog();
echo $list;
