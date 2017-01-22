<?php

require_once './classes/Groups.php';
$g = new Groups();
$practiceid = $_POST['practiceid'];
$list = $g->get_pracrice_course_dialog($practiceid);
echo $list;
