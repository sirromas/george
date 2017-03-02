<?php

require_once './classes/Groups.php';
$g = new Groups();
$group = $_POST['group'];
$g->add_new_practice_group(json_decode($group));
