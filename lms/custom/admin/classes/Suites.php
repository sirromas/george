<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Suites
 *
 * @author moyo
 */
class Suites extends Utils {

    public $limit;

    function __construct() {
        parent::__construct();
        $this->limit = 3;
    }

    function get_elearning_suites_page($id) {
        $list = "";
        $query = "select * from uk_elearning_suites order by title limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $s = new stdClass();
                foreach ($row as $key => $value) {
                    $s->$key = $value;
                }
                $suites[] = $s;
            } // end while
        } // end if $num > 0
        $list.=$this->create_suites_page($suites);
        return $list;
    }

    function create_suites_page($suites, $toolbar = true) {
        $list = "";

        if ($toolbar) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span1'><button id='add_suite'>Add</button></span>";
            $list.="<span class='span1'><button id='suite_back'>Back</button></span>";
            $list.="</div><br>";
        }

        if (count($suites) > 0) {
            $list.="<div id='suites_container'>";
            foreach ($suites as $s) {
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span1'><img src='http://" . $_SERVER['SERVER_NAME'] . "/assets/img/" . $s->img_path . "' width='25px' height='25px'></span>";
                $list.="<span class='span3'>" . $s->title . "</span>";
                $list.="<span class='span1'><i class='fa fa-pencil-square-o' aria-hidden='true' style='cursor:pointer;' id='suite_edit_" . $s->id . "'></i></span>";
                $list.="<span class='span1'><i class='fa fa-trash' aria-hidden='true' style='cursor:pointer;' id='suite_del_" . $s->id . "'></i></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;padding-left:45px;'>";
                $list.="<span class='span8'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
        } // end if count($suites) > 0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span9'>There are no any suites added</span>";
            $list.="</div>";
        }
        if ($toolbar) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span6' id='suites_pagination'></span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_suite_item($page) {
        $suites = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from uk_elearning_suites "
                . "order by title LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $s = new stdClass();
            foreach ($row as $key => $value) {
                $s->$key = $value;
            } // end foreach
            $suites[] = $s;
        } // end while
        $list = $this->create_suites_page($suites, false);
        return $list;
    }

    function get_total_suites() {
        $query = "select count(id) as total from uk_elearning_suites ";
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

}
