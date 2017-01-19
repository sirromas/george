<?php

require_once './classes/Utils.php';
$u = new Utils();
for ($i = 0; $i <= 280; $i++) {
    $status = $u->create_random_user();
    if ($status == true) {
        echo "User was successfully created ...<br>";
        echo "<br>-----------------------------------------<br>";
    } // end if 
    else {
        echo "Error creation user ... <br> ";
        echo "<br>-----------------------------------------<br>";
    } // end else
}