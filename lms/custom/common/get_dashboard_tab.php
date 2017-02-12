<?php

require_once './classes/Dashboard.php';
$dash = new Dashboard();
$list = $dash->get_dashboard_tab();
echo $list;
