<?php

require_once './classes/News.php';
$n = new News();
$id = $_POST['id'];
$list = $n->get_edit_news_dialog($id);
echo $list;
