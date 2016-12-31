<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Pages
 *
 * @author moyo
 */
class Pages extends Utils {

    public $file_path;

    function __construct() {
        parent::__construct();
        $this->file_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/img';
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

    function get_elearning_suites_page($id) {
        $list = "";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span1'><button id='add_suite'>Add</button></span>";
        $list.="<span class='span1'><button id='suite_back'>Back</button></span>";
        $list.="</div><br>";

        $query = "select * from uk_elearning_suites order by title";
        $num = $this->db->numrows($query);
        $list.="<div id='suites_container'>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span1'><img src='http://" . $_SERVER['SERVER_NAME'] . "/assets/img/" . $row['img_path'] . "' width='25px' height='25px'></span>";
                $list.="<span class='span3'>" . $row['title'] . "</span>";
                $list.="<span class='span1'><i class='fa fa-pencil-square-o' aria-hidden='true' style='cursor:pointer;' id='suite_edit_" . $row['id'] . "'></i></span>";
                $list.="<span class='span1'><i class='fa fa-trash' aria-hidden='true' style='cursor:pointer;' id='suite_del_" . $row['id'] . "'></i></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span8'><hr/></span>";
                $list.="</div>";
            }
        } // end if
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span9'>There are no any suites added</span>";
            $list.="</div>";
        } // end else
        $list.="</div>";

        return $list;
    }

    function get_add_suite_dialog() {
        $list = "";

        $list.="<div id='myModal' class='modal fade' style='width:875px;margin-left:0px;left:15%;min-height: 100%;'>
        <div class='modal-dialog' >
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add eLearning Suite</h4>
                </div>
                <div class='modal-body' style='min-height:620px;'>
                
                <div class='container-fluid'>
                <span class='span1'>Title*</span>
                <span class='span6'><input type='text' id='suite_title' style='width:670px;'></span>
                </div>
                   
                <div class='container-fluid'>
                <span class='span1'>Picture</span>
                <span class='span3'><label class='btn btn-default btn-file'>
                    Browse <input type='file' id='suite_pic' style='display: none;'>
                </label>
                </span>    
                </div>
                
                <div class='container-fluid' style='padding-top:12px;'>
                <span class='span1'>Description*:</span>
                <span class='span8'><textarea id='desc' name='desc' style=''></textarea></span> 
                <script>
                CKEDITOR.replace( 'desc' );
                </script>
                </div>
                
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='suite_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='add_new_suite'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_edit_suite_dialog($id) {
        $list = "";

        $query = "select * from uk_elearning_suites where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $s = new stdClass();
            foreach ($row as $key => $value) {
                $s->$key = $value;
            }
        }

        $list.="<div id='myModal' class='modal fade' style='width:875px;margin-left:0px;left:15%;min-height: 100%;'>
        <div class='modal-dialog' >
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit eLearning Suite</h4>
                </div>
                <input type='hidden' id='id' value='$id'>
                <div class='modal-body' style='min-height:620px;'>
                
                <div class='container-fluid'>
                <span class='span1'>Title*</span>
                <span class='span6'><input type='text' id='suite_title' style='width:670px;' value='$s->title'></span>
                </div>
                   
                <div class='container-fluid'>
                <span class='span1'>Picture</span>
                <span class='span3'><label class='btn btn-default btn-file'>
                    Browse <input type='file' id='suite_pic' style='display: none;'>
                </label>
                </span>    
                </div>
                
                <div class='container-fluid' style='padding-top:12px;'>
                <span class='span1'>Description*:</span>
                <span class='span8'><textarea id='desc' name='desc' style=''>$s->content</textarea></span> 
                <script>
                CKEDITOR.replace( 'desc' );
                </script>
                </div>
                
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='suite_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='update_suite'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_new_suite($file_data, $post) {

        $title = $post['title'];
        $content = $post['content'];
        $file = $file_data[0];

        $tmp_name = $file['tmp_name'];
        $error = $file['error'];
        $size = $file['size'];
        $ext = 'jpg';

        if ($tmp_name != '' && $error == 0 && $size > 0) {
            $img = time() . "." . $ext;
            $fullpath = $_SERVER['DOCUMENT_ROOT'] . "/assets/img/$img";
            move_uploaded_file($tmp_name, $fullpath);
            if (move_uploaded_file) {
                $img_name = $img;
            } // end if
            else {
                $img_name = 'lambda_responsive.jpg';
            } // end else
        } // end if $tmp_name != '' && $error == 0 && $size > 0
        else {
            $img_name = 'lambda_responsive.jpg';
        } // end else

        $query = "insert into uk_elearning_suites ("
                . "title, "
                . "content, "
                . "img_path) values ('$title','$content','$img_name')";
        $this->db->query($query);
    }

    function update_suite($file_data, $post) {
        $id = $post['id'];
        $title = $post['title'];
        $content = $post['content'];
        $file = $file_data[0];

        $tmp_name = $file['tmp_name'];
        $error = $file['error'];
        $size = $file['size'];
        $ext = 'jpg';

        if ($tmp_name != '' && $error == 0 && $size > 0) {
            $img = time() . "." . $ext;
            $fullpath = $_SERVER['DOCUMENT_ROOT'] . "/assets/img/$img";
            move_uploaded_file($tmp_name, $fullpath);
            if (move_uploaded_file) {
                $img_name = $img;
            } // end if
            else {
                $img_name = 'n/a';
            } // end else
        } // end if $tmp_name != '' && $error == 0 && $size > 0
        else {
            $img_name = 'n/a';
        } // end else

        if ($img_name != 'n/a') {
            $query = "update uk_elearning_suites "
                    . "set title='$title', "
                    . "content='$content', "
                    . "img_path='$img_name' where id=$id";
        } // end if
        else {
            $query = "update uk_elearning_suites "
                    . "set title='$title', "
                    . "content='$content' where id=$id ";
        } // end else
        $this->db->query($query);
    }

    function del_suite($id) {
        $query = "delete from uk_elearning_suites where id=$id";
        $this->db->query($query);
    }

    function get_suites_page() {
        $id = 1;
        $list = $this->get_get_elearning_suites_page($id);
        return $list;
    }

    function get_site_page($id) {
        $list = "";

        switch ($id) {
            case 1:
                $list.=$this->get_elearning_suites_page($id);
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
