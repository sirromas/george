<?php

require_once './classes/Users.php';
$u = new Users();
$list = $u->get_users_tab();
echo $list;

