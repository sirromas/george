<?php

require_once './classes/Groups.php';
$g = new Groups();
$list = $g->get_groups_page();
echo $list;
