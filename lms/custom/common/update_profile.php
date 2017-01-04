<?php

require_once './classes/Profile.php';
$pr = new Profile();
$profile = $_POST['profile'];
$list = $pr->update_profile(json_decode($profile));
echo $list;
