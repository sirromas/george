<?php

require_once './classes/Groups.php';
$g = new Groups();
$userid = $_POST['userid'];
$g->del_practice($userid);
