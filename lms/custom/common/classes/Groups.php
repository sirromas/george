<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Groups
 *
 * @author moyo
 */
class Groups extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_groups_page($userid) {
        $list = "";
        $cohorts = array();
        $query = "select * from uk_cohort order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $c = new stdClass();
                foreach ($row as $key => $value) {
                    $c->$key = $value;
                }
                $cohorts[] = $c;
            } // end while
        } // end if $num > 0
        return $list;
    }

    function create_gropus_page($cohorts) {
        $list = "";

        return $list;
    }

}
