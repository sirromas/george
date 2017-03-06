<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

class Banner extends Utils {
    
    function __construct() {
        parent::__construct();
        $this->limit = 3;
    }
    
    function get_baner_edit_page() {
        $list="";
        
        $query="select * from uk_banner";
        
        return $list;
    }
    
}
