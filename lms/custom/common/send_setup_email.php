<?php

require_once './classes/Groups.php';
$g = new Groups();
$userid = $_POST['userid'];
$g->send_setup_email($userid);
