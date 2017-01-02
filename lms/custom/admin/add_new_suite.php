<?php

require_once './classes/Suites.php';
$l = new Suites();
$files = $_FILES;
$post = $_POST;
$l->add_new_suite($files, $post);
