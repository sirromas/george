<?php

require_once './classes/News.php';
$n = new News();
$id = $_POST['id'];
$n->del_news($id);
