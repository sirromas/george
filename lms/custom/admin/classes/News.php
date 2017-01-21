<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

class News extends Utils {

    public $limit;

    function __construct() {
        parent::__construct();
        $this->limit = 3;
    }

    function get_news_page() {
        $list = "";
        $query = "select * from uk_news order by added desc ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $n = new stdClass();
                foreach ($row as $key => $value) {
                    $n->$key = $value;
                }
                $news[] = $n;
            } // end while
        } // end if $num > 0
        $list.=$this->create_news_page($news);
        return $list;
    }

    function create_news_page($news, $toolbar = true) {
        $list = "";

        if ($toolbar) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span1'><button id='add_news'>Add</button></span>";
            $list.="<span class='span1'><button id='suite_back'>Back</button></span>";
            $list.="</div><br>";
        }
        $list.="<table id='news_table' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Title</th>";
        $list.="<th>Actions</th>";
        $list.="</tr>";
        $list.="</thead>";
        if (count($news) > 0) {
            $list.="<tbody>";
            foreach ($news as $n) {
                $list.="<tr>";
                $list.="<td>" . $n->title . "</td>";
                $list.="<td><i class='fa fa-pencil-square-o' aria-hidden='true' style='cursor:pointer;' id='news_edit_" . $n->id . "'></i></span>";
                $list.="<i class='fa fa-trash' aria-hidden='true' style='cursor:pointer;padding-left:30px;' id='news_del_" . $n->id . "'></i></span>";
                $list.="</td>";
                $list.="</tr>";
            } // end foreach
            $list.="</tbody>";
        } // end if count($suites) > 0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span9'>There are no any news added</span>";
            $list.="</div>";
        }
        $list.="</table>";
        
        return $list;
    }

    function get_add_news_dialog() {
        $list = "";

        $list.="<div id='myModal' class='modal fade' style='overflow:visible;width:936px;margin-left:0px;left:15%;'>
        <div class='modal-dialog modal-lg' >
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add News</h4>
                </div>
                <div class='modal-body' style='min-height:1024px;'>
                
                <div class='container-fluid'>
                <span class='span1'>Title*</span>
                <span class='span6'><input type='text' id='news_title' style='width:670px;'></span>
                </div>
                
                <div class='container-fluid' style='padding-top:12px;'>
                <span class='span1'>Content*:</span>
                <span class='span8'><textarea id='desc' name='desc' style=''></textarea></span> 
                <script>
                CKEDITOR.replace( 'desc' );
                </script>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='news_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='add_news_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";


        return $list;
    }

    function get_edit_news_dialog($id) {
        $list = "";

        $query = "select * from uk_news where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $title = $row['title'];
            $content = $row['content'];
        }

        $list.="<div id='myModal' class='modal fade' style='width:934px;margin-left:0px;left:15%;'>
        <div class='modal-dialog' modal-lg style='width:902px;'>
            <div class='modal-content' style='min-height:100%;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit News</h4>
                </div>
                <div class='modal-body' style='min-height:875px;'>
                <input type='hidden' id='id' value='$id'>
                <div class='container-fluid'>
                <span class='span1'>Title*</span>
                <span class='span6'><input type='text' id='news_title' style='width:670px;' value='$title'></span>
                </div>
                
                <div class='container-fluid' style='padding-top:12px;'>
                <span class='span1'>Content*:</span>
                <span class='span8'><textarea id='desc' name='desc' style=''>$content</textarea></span> 
                <script>
                CKEDITOR.replace( 'desc' );
                </script>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='news_err'></span>
                </div>
             
                <div class='container-fluid' style='text-align:left;padding-left:50px;padding-top:10px;'>
                    <span align='center'><button type='button'  data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button'  id='update_news_button'>OK</button></span>
                </div>
                
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_news($news) {
        $date = time();
        $query = "insert into uk_news "
                . "(title,"
                . "content,"
                . "added) "
                . "values ('$news->title',"
                . "'$news->content',"
                . "'$date')";
        $this->db->query($query);
    }

    function get_total_news() {
        $query = "select count(id) as total from uk_news ";
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

    function get_news_item($page) {
        $news = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from uk_news "
                . "order by added desc LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $n = new stdClass();
            foreach ($row as $key => $value) {
                $n->$key = $value;
            } // end foreach
            $news[] = $n;
        } // end while
        $list = $this->create_news_page($news, false);
        return $list;
    }

    function del_news($id) {
        $query = "delete from uk_news where id=$id";
        $this->db->query($query);
    }

    function update_news($news) {
        $query = "update uk_news "
                . "set title='$news->title', "
                . "content='$news->content' "
                . "where id=$news->id";
        $this->db->query($query);
    }

}
