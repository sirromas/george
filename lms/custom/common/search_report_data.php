<?php

require_once './classes/Reports.php';
$r = new Reports();
$search = $_POST['search'];
$list = $r->search_report_data(json_decode($search));
echo $list;
