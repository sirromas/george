<?php

require_once './classes/Courses.php';
$c = new Courses();
$list = $c->get_user_certificates();
echo $list;
