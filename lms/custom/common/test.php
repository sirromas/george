<?php

require_once '../mailer/Mailer.php';
$m = new Mailer();

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

