<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Requests
 *
 * @author moyo
 */
class Requests extends Utils {

    public $limit;

    function __construct() {
        parent::__construct();
        $this->limit = 3;
    }

    function get_contact_requests_page($id) {
        $list = "";
        $requests = array();
        $list.=$this->create_requests_page($requests);
        return $list;
    }

    function create_requests_page($requests, $toolbar = true) {
        $list = "";

        if ($toolbar) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span1'><button id='suite_back'>Back</button></span>";
            $list.="</div><br>";
        }

        return $list;
    }

}
