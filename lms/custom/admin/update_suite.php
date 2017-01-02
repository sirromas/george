<?php

require_once './classes/Suites.php';
$l = new Suites();
$files = $_FILES;
$post = $_POST;
$list = $l->update_suite($files, $post);
echo $list;
