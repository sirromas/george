<?php

require_once './classes/Groups.php';
$g = new Groups();
$id = $_POST['id'];
$g->update_practice_group_course_status($id);
