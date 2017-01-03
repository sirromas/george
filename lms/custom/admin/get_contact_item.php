<?php

require_once './classes/Requests.php';
$r = new Requests();
$page = $_POST['id'];
$list = $r->get_contact_item($page);
echo $list;
