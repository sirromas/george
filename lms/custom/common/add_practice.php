<?php

require_once './classes/Groups.php';
$g = new Groups();
$practice = $_POST['practice'];
$g->add_new_practice(json_decode($practice));

