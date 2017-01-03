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
        $query = "select * from uk_user_contacts order by added desc limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $r = new stdClass();
                foreach ($row as $key => $value) {
                    $r->$key = $value;
                }
                $requests[] = $r;
            }
        }
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

        if (count($requests) > 0) {
            $list.="<div id='contact_container'>";
            foreach ($requests as $r) {
                $date = date('m-d-Y h:i:s', $r->added);
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span2'>" . $r->name . "</span>";
                $list.="<span class='span2'>" . $r->email . "</span>";
                $list.="<span class='span2'>" . $r->phone . "</span>";
                $list.="<span class='span4'>" . $r->comment . "</span>";
                $list.="<span class='span2'>" . $date . "</span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";
            }
            $list.="</div>";
        } // end if
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span12'>There are no any contact request</span>";
            $list.="</div>";
        } // end else

        if ($toolbar) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span6' id='contact_pagination'></span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_contact_item($page) {
        $requests = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from uk_user_contacts "
                . "order by added desc LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $c = new stdClass();
            foreach ($row as $key => $value) {
                $c->$key = $value;
            } // end foreach
            $requests[] = $c;
        } // end while
        $list = $this->create_requests_page($requests, false);
        return $list;
    }

    function get_request_total() {
        $query = "select count(id) as total from uk_user_contacts ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $total = $row['total'];
            }
        } // end if
        else {
            $total = 0;
        }
        return $total;
    }

}
