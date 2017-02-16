<?php

class Index_model extends CI_Model {

    public $host;
    public $limit;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->host = $_SERVER['SERVER_NAME'];
        $this->limit = 4;
    }

    public function get_index_page() {
        $list = "";

        $list.="<li class='activity label modtype_label' id='module-280'>
                        <div id='yui_3_17_2_1_1481703191444_138'>
                            <div class='mod-indent-outer' id='yui_3_17_2_1_1481703191444_137'><div class='mod-indent'></div>
                                <div id='yui_3_17_2_1_1481703191444_136'><div class='contentwithoutlink ' id='yui_3_17_2_1_1481703191444_135'>
                                        <div class='no-overflow' id='yui_3_17_2_1_1481703191444_134'>
                                            <div class='no-overflow' id='yui_3_17_2_1_1481703191444_133'>
                                                <div class='row-fluid frontpage' id='yui_3_17_2_1_1481703191444_132'>
                                                    <div class='span6' id='yui_3_17_2_1_1481703191444_141'>
                                                        <img src='http://$this->host/assets/img/lambda_responsive.jpg' alt='lambda responsive' class='img-responsive' id='yui_3_17_2_1_1481703191444_140'></div>
                                                    <div class='span6' id='yui_3_17_2_1_1481703191444_131'>

                                                        <form class='navbar-form' id='login-form' method='post' action='http://$this->host/lms/login/index.php?authldap_skipntlmsso=1'>

                                                            <div class='row-fluid' style=''>	
                                                                <span class='span12' style='margin-top:0px;padding-bottom:15px;padding-left:35px;'><input id='inputName' class='span2' type='text' name='username' placeholder='Email address' required style='width:435px;height:35px; '></span>
                                                            </div>   

                                                            <div class='row-fluid'>
                                                                <span class='span12' style='padding-left:35px;padding-bottom:15px;'><input id='inputPassword' class='span2' type='password' name='password' id='password' placeholder='Password' required style='width:435px;height:35px;'></span>
                                                            </div>
                                                            
                                                            <div class='row-fluid'>
                                                                <span class='span12' style='padding-left:31px;'><button id='login_button' class='btn btn-primary' style='width:435px;height:35px;background-color:#22b14c; '>Login</button></span>
                                                            </div>
                                                            
                                                            <div class='row-fluid'>
                                                                <span class='span12' style='padding-left:35px;color:red;' id='login_err'></span>
                                                            </div>

                                                            <div class='forgotpass' style='padding-left:35px;'>
                                                                <a style='' target='_self' href='http://$this->host/lms/login/forgot_password.php'>Forgotten your username or password?</a>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div></div></div></div></div></div></div></li>";
        $list.="</div>";

        return $list;
    }

    function get_elearning_suites_page() {
        $list = "";
        $query = "select * from uk_elearning_suites order by title";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        if ($num > 0) {
            foreach ($result->result() as $row) {

                $summary_string = (strlen(strip_tags($row->content)) > 375) ? substr(strip_tags($row->content), 0, 275) . ' ...' : strip_tags($row->content);

                $list.="<div class='container-fluid'>";
                $list.="<span class='span12' style='background:heading;color:#fff;padding:2px;margin-bottom:10px;'>$row->title</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span5'><img src='http://" . $_SERVER['SERVER_NAME'] . "/assets/img/$row->img_path' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;' height='220' width='325'></span>";
                $list.="<span class='span6'><strong>$row->title</strong><br>$summary_string &nbsp;&nbsp;<a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/index/suite_detailes/$row->id'>More</a></span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";
            }
            $list.="</div>";
            return $list;
        }
    }

    function get_suite_detailes($id) {
        $list = "";
        $query = "select * from uk_elearning_suites where id=$id";
        $result = $this->db->query($query);
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        foreach ($result->result() as $row) {

            $list.="<div class='container-fluid'>";
            $list.="<span class='span12' style='background:heading;color:#fff;padding:2px;margin-bottom:10px;'>$row->title</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span5'><img src='http://" . $_SERVER['SERVER_NAME'] . "/assets/img/$row->img_path' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;' height='220' width='325'></span>";
            $list.="<span class='span6'><strong>$row->title</strong><br>$row->content</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span12'><br><br></span>";
            $list.="</div>";
        }
        $list.="</div>";

        return $list;
    }

    function get_news_block() {
        $list = "";
        $query = "select * from uk_news order by added desc limit 0, $this->limit";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $summary_string = (strlen(strip_tags($row->content)) > 375) ? substr(strip_tags($row->content), 0, 275) . ' ...' : strip_tags($row->content);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'><strong>$row->title</strong></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'>$summary_string &nbsp;&nbsp;<a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/index/fullnews/$row->id'>More</a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";
            }
        }
        return $list;
    }

    function get_news_page() {
        $list = "";
        $news_block = $this->get_news_block();
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='background:heading;color:#fff;padding:2px;margin-bottom:10px;'>News</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span5'><img src='http://" . $_SERVER['SERVER_NAME'] . "/assets/img/news.jpg' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;' height='220' width='325'></span>";
        $list.="<span class='span6'>$news_block</span>";
        $list.="</div>";
        $list.="</div>";

        return $list;
    }

    function get_fullnews_page($id) {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $query = "select * from uk_news where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span12'><strong>$row->title</strong></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        $list.="</div>";
        return $list;
    }

    function get_faq_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $query = "select * from uk_site_pages where id=2";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<br><div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        $list.="</div>";
        return $list;
    }

    function get_testimonials_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $query = "select * from uk_site_pages where id=3";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<br><div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        $list.="</div>";
        return $list;
    }

    function get_policy_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $query = "select * from uk_site_pages where id=4";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<br><div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        $list.="</div>";
        return $list;
    }

    function get_about_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $query = "select * from uk_site_pages where id=6";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<br><div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        $list.="</div>";
        return $list;
    }

    function get_terms_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $query = "select * from uk_site_pages where id=9";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<br><div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        $list.="</div>";
        return $list;
    }

    function get_privacy_policy_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $query = "select * from uk_site_pages where id=10";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<br><div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        $list.="</div>";
        return $list;
    }

    function get_subscribe_form() {
        $list = "";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='padding-left:8px;font-size:110%'><strong>GP Practice Management news direct to your inbox.</strong></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='padding-left:8px;'>Sign up below to receive the free Practice Index Weekly.</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='padding-left:8px;padding-bottom:25px;'><input style='width:380px;' type='text' id='name' required placeholder='Name'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' style='padding-left:8px;'><input style='width:380px;' type='text' id='email' type='email' required placeholder='Email'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' id='subs_err' style='color:red;padding-left:8px;'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'><button class='btn btn-primary' id='subs' style='width:400px;'>Subscribe</button></span>";
        $list.="</div>";

        $list.="<br><div class='container-fluid'>";
        $list.="<spacn class='span12' style='padding-left:8px;'><i class='fa fa-check-square-o' aria-hidden='true'></i>&nbsp;News, alerts, legislation changes, CQC advice and updates</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<spacn class='span12' style='padding-left:8px;'><i class='fa fa-check-square-o' aria-hidden='true'></i>&nbsp;New resources and forum discussions</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<spacn class='span12' style='padding-left:8px;'><i class='fa fa-check-square-o' aria-hidden='true'></i>&nbsp;Latest jobs</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' >";
        $list.="<spacn class='span12' style='padding-left:8px;'><i class='fa fa-check-square-o' aria-hidden='true'></i>&nbsp;Blog articles and guides</span>";
        $list.="</div>";

        return $list;
    }

    function get_subscribe_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $form = $this->get_subscribe_form();

        $list.="<br><div class='container-fluid'>";
        $list.="<span class='span6' id='subscribe_content'>$form</span>";
        $list.="<span class='span6'><img class='img-responsive' src='http://" . $_SERVER['SERVER_NAME'] . "/assets/img/bulletin.jpg'></span>";
        $list.="</div>";

        $list.="</div>";


        return $list;
    }

    function subscribe_user($subs_data) {
        $query = "insert into uk_subscribers "
                . "(name, email) "
                . "values('$subs_data->name','$subs_data->email')";
        $this->db->query($query);
        $list = "<p>Thank you for subscribing!</p><br>";
        return $list;
    }

    function get_contact_block() {
        $list = "";
        $query = "select * from uk_site_pages where id=7";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span12'>$row->content</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_contact_form_block() {
        $list = "";
        $list.="<div id='contact_container'>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Name*<br><input type='text' id='name' placeholder='Name' style='width:175px;'></span>";
        $list.="<span class='span6'>Email*<br><input id='email' type='email' placeholder='Email' style='width:175px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Phone*<input type='text' id='phone' placeholder='Phone' style='width:175px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="Comment*<br><textarea id='comment' placeholder='Comment' style='width:383px;height:175px;' ></textarea>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' id='contact_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'><button class='btn btn-primary' id='contact_submit'>Submit</button></span>";
        $list.="</div></div><br>";

        return $list;
    }

    function get_campus_data() {
        $query = "select * from uk_campus";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $lat = $row->lat;
            $lon = $row->lon;
            $desc = $row->desc;
            $name = $row->name;
        }
        $data = array('lat' => $lat, 'lon' => $lon, 'desc' => $desc, 'name' => $name);
        return json_encode($data);
    }

    function get_contact_page() {
        $list = "";
        $list.="<div style='width: 86%;margin: 0 auto; ' class='panel panel-default'>";
        $contact = $this->get_contact_block();
        $contact_form = $this->get_contact_form_block();
        $list.="<br><div class='container-fluid'>";
        $list.="<span class='span6'>$contact</span>";
        $list.="<span class='span6'><img class='img-responsive' src='http://" . $_SERVER['SERVER_NAME'] . "/assets/img/contact.jpg'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>$contact_form</span>";
        $list.="<span class='span6' id='map' style='height:339px;width:433px;'></span>";
        $list.="</div>";

        $list.="</div>";
        return $list;
    }

    function add_contact_request($c) {
        $date = time();
        $query = "insert into uk_user_contacts "
                . "(name,"
                . "email,"
                . "phone,"
                . "comment, "
                . "added) "
                . "values "
                . "('$c->name',"
                . "'$c->email',"
                . "'$c->phone',"
                . "'$c->comment',"
                . "'$date')";
        $this->db->query($query);

        $list = "Thank you! We get back to you within 24h";
        return $list;
    }

}
