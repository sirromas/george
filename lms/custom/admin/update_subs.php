<?php

require_once './classes/Subscribers.php';
$s = new Subscribers();
$subs = $_POST['subs'];
$s->update_subs(json_decode($subs));
