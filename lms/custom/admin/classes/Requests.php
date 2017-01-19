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
        $query = "select * from uk_user_contacts order by added desc ";
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
        
        $list.="<table id='requests_table' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>User Name</th>";
        $list.="<th>User Email</th>";
        $list.="<th>User Phone</th>";
        $list.="<th>User Comment</th>";
        $list.="<th>Date</th>";
        $list.="</tr>";
        $list.="</thead>";
        
        if (count($requests) > 0) {
            $list.="<tbody>";
            foreach ($requests as $r) {
                $date = date('m-d-Y h:i:s', $r->added);
                $list.="<tr>";
                $list.="<td>" . $r->name . "</td>";
                $list.="<td>" . $r->email . "</td>";
                $list.="<td>" . $r->phone . "</td>";
                $list.="<td>" . $r->comment . "</td>";
                $list.="<td>" . $date . "</td>";
                $list.="</tr>";
            }
            $list.="</tbody>";
        } // end if
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span12'>There are no any contact request</span>";
            $list.="</div>";
        } // end else
        $list.="</table>";
        
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
