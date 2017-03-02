<?php

require_once './classes/Groups.php';
$g = new Groups();
$list = $g->get_add_pgroup_dialog();
echo $list;
