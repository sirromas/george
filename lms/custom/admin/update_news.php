<?php

require_once './classes/News.php';
$n = new News();
$news = $_POST['news'];
$n->update_news(json_decode($news));
