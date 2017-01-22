<?php

require_once './classes/Groups.php';
$g = new Groups();
$userid = $_POST['userid'];
$list = $g->get_practice_admin_modal_dialog($userid);
echo $list;
