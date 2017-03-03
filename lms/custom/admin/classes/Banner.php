<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

class Banner extends Utils {
    
    function __construct() {
        parent::__construct();
        $this->limit = 3;
    }
    
    
    
}
