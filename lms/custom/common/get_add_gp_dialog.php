<?php

require_once './classes/Groups.php';
$g=new Groups();
$list=$g->get_add_gp_dialog();
echo $list;
