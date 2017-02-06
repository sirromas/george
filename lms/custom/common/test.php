<?php

/*
  require_once './classes/Completion.php';
  $comp = new Completion();
  $userid = 321;
  $courseid = 30;
  $list = $comp->create_user_certificate2($courseid, $userid);
  echo $list;
 */

$date = '03/18/2015';
$dateh = strtotime($date);

echo "Human date: " . $date . "<br>";
echo "Unix date: " . $dateh . "<br>";
