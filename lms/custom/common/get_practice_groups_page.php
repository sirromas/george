<?php

require_once './classes/Groups.php';
$g = new Groups();
$practiceid = $_POST['practiceid'];
$list = $g->get_gp_groups_page($practiceid);
echo $list;
