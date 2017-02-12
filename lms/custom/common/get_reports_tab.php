<?php

require_once './classes/Reports.php';
$r = new Reports();
$list = $r->get_reports_tab();
echo $list;
