<?php

require_once '../mailer/Mailer.php';
require_once './classes/Completion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/classes/Certificate.php';
$m = new Mailer();
$comp = new Completion();
$cert = new Certificate();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
  $date = '03/18/2015';
  $dateh = strtotime($date);

  echo "Human date: " . $date . "<br>";
  echo "Unix date: " . $dateh . "<br>";

  $user = new stdClass();
  $user->firstname = 'John';
  $user->lastname = 'Connair';
  $user->email = 'john@akka.com';
  $user->pwd = 'abba1abba2';

  $m->send_account_confirmation_message($user);
  echo "Email has been sent ....<br>";
 */

$courseid = 33;
$userid = 312;

$list = $comp->create_user_certificate2($courseid, $userid);
echo $list . "<br>";
$cert->create_user_certificate($courseid, $list, $userid);
//echo "Certificate was created ...<br>";


