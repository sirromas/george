<?php

require_once './classes/News.php';
$n = new News();
$page = $_POST['id'];
$list = $n->get_news_item($page);
echo $list;
