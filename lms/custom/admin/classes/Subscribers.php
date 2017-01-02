<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Subscribers
 *
 * @author moyo
 */
class Subscribers extends Utils {

    public $limit;

    function __construct() {
        parent::__construct();
        $this->limit = 3;
    }

    function get_subscribers_page($id) {
        $list = "";
        $subscribers = array();
        $list.=$this->create_subscribers_page($subscribers);
        return $list;
    }

    function create_subscribers_page($subscribers, $toolbar = true) {
        $list = "";

        if ($toolbar) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span1'><button id='suite_back'>Back</button></span>";
            $list.="</div><br>";
        }

        return $list;
    }

}
