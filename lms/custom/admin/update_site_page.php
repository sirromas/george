<?php

require_once './classes/Pages.php';
$p = new Pages();
$id = $_POST['id'];
$data = $_POST['data'];
$p->update_common_page($id, $data);
