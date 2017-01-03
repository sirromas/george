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
        $query = "select * from uk_subscribers order by name limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $s = new stdClass();
                foreach ($row as $key => $value) {
                    $s->$key = $value;
                }
                $subscribers[] = $s;
            } // end while
        } // end if $num > 0
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

        if (count($subscribers) > 0) {
            $list.="<div id='subs_container'>";
            foreach ($subscribers as $s) {
                $status = ($s->status == 1) ? "<a style='cursor:pointer;' title='Click here to unsubscribe' id='subs_status_$s->id' data-status='$s->status'>Subscribed</a>" : "<a style='cursor:pointer;' data-status='$s->status' title='Click here to subscribe' id='subs_status_$s->id'>Unsubscribed</a>";
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span3'>" . $s->name . "</span>";
                $list.="<span class='span3'>" . $s->email . "</span>";
                $list.="<span class='span3'>$status</span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span8'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
        } // end if
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span9'>There are no any subscribers</span>";
            $list.="</div>";
        } // end else

        if ($toolbar) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span6' id='subs_pagination'></span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_subs_total() {
        $query = "select count(id) as total from uk_subscribers ";
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

    function get_subs_item($page) {
        $subscribers = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from uk_subscribers "
                . "order by name LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $s = new stdClass();
            foreach ($row as $key => $value) {
                $s->$key = $value;
            } // end foreach
            $subscribers[] = $s;
        } // end while
        $list = $this->create_subscribers_page($subscribers, false);
        return $list;
    }

    function update_subs($s) {
        $status = ($s->status == 1) ? 0 : 1;
        $query = "update uk_subscribers "
                . "set status='$status' "
                . "where id=$s->id";
        $this->db->query($query);
    }

}
