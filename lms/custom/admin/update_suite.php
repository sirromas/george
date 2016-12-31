<?php

require_once './classes/Pages.php';
$p = new Pages();
$files = $_FILES;
$post = $_POST;
$list = $p->update_suite($files, $post);
echo $list;
