<?php

require_once './classes/Credentials.php';
$cr = new Credentials();
$user = $_POST['user'];
$list = $cr->check_credentials(json_decode($user));
echo $list;

