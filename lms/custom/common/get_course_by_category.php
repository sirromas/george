<?php

require_once './classes/Groups.php';
$g = new Groups();
$catid = $_POST['catid'];
$list = $g->get_practice_courses($catid);
echo $list;
