<?php

require_once './classes/Pages.php';
$p = new Pages();
$id = $_POST['id'];
$list = $p->get_site_page($id);
echo $list;
