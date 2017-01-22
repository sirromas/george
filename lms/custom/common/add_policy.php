<?php

require_once './classes/Courses.php';
$c = new Courses();
$files = $_FILES;
$post = $_POST;
$c->add_new_policy($files, $post);

