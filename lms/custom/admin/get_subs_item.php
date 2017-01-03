<?php

require_once './classes/Subscribers.php';
$s = new Subscribers();
$page = $_POST['id'];
$list = $s->get_subs_item($page);
echo $list;

