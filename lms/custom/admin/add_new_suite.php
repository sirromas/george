<?php

require_once './classes/Pages.php';
$p = new Pages();
$files = $_FILES;
$post = $_POST;
$p->add_new_suite($files, $post);
