<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Pages
 *
 * @author moyo
 */
class Pages extends Utils {

    function __construct() {
        parent::__construct();
    }

    function get_page_list() {
        $list = "";
        $list.="<div class='container-fluid' style='font-weight:bolder;text-align:left;'>";
        $list.="<span class='span6'>Page Title</span>";
        $list.="<span class='span3' style='padding-left:15px;'>Ops</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span7'><hr/></span>";
        $list.="</div>";
        $query = "select * from uk_site_pages order by title";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span6'>" . $row['title'] . "</span>";
            $list.="<span class='span3'>";
            $list.="<button id='" . $row['id'] . "' data-id='" . $row['id'] . "' class='pages-list'>Edit</button>";
            $list.="</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span7'><hr/></span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_user_site_pages() {
        $list = "";
        $pages = $this->get_page_list();
        $list.=$pages;
        return $list;
    }

    function get_common_editor_page($id) {
        $list = "";
        $query = "select * from uk_site_pages where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row['content'];
        }

        $list.="<input type='hidden' id='id' value='$id'>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span12'>";
        $list.="<textarea name='editor1' id='editor1' rows='10' cols='80'>";
        $list.=$content;
        $list.="</textarea>";
        $list.="<script>
                CKEDITOR.replace( 'editor1' );
                </script>";
        $list.="</span>";
        $list.="</div>";

        $list.="<br><div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span1'><button class='update-user-page'>Update</button></span>";
        $list.="<span class='span1'><button class='cancel-edit-pages'>Cancel</button></span>";
        $list.="</div>";

        return $list;
    }

    function update_common_page($id, $data) {
        $query = "update uk_site_pages set content='$data' where id=$id";
        $this->db->query($query);
    }

    function get_subscribers_page($id) {
        
    }

    function get_contact_page($id) {
        
    }

    function get_site_page($id) {
        $list = "";

        switch ($id) {
            case 1:
                $list.=$this->get_common_editor_page($id);
                break;
            case 2:
                $list.=$this->get_common_editor_page($id);
                break;
            case 3:
                $list.=$this->get_common_editor_page($id);
                break;
            case 4:
                $list.=$this->get_common_editor_page($id);
                break;
            case 5:
                $list.=$this->get_subscribers_page($id);
                break;
            case 6:
                $list.=$this->get_common_editor_page($id);
                break;
            case 7:
                $list.=$this->get_contact_page($id);
                break;
            case 8:
                $list.=$this->get_news_page();
                break;
        } // end of switch

        return $list;
    }

}
