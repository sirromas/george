<?php

require_once './classes/Courses.php';
$c = new Courses();
$email = $_POST['email'];
$list = $c->is_email_exists($email);
echo $list;
