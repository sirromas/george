<?php

require_once './classes/Pages.php';
$p = new Pages();
$list = $p->get_page_list();
echo $list;
