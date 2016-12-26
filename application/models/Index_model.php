<?php

class Index_model extends CI_Model {

    public $host;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->host = $_SERVER['SERVER_NAME'];
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

                                                        <form class='navbar-form' method='post' action='http://$this->host/lms/login/index.php?authldap_skipntlmsso=1'>

                                                            <div class='row-fluid' style=''>	
                                                                <span class='span12' style='padding-bottom:15px;padding-left:35px;'><input id='inputName' class='span2' type='text' name='username' placeholder='Username or Email' required style='width:435px;height:35px; '></span>
                                                            </div>   

                                                            <div class='row-fluid'>
                                                                <span class='span12' style='padding-bottom:15px;padding-left:35px'><input id='inputPassword' class='span2' type='password' name='password' id='password' placeholder='Password' required style='width:435px;height:35px;'></span>
                                                            </div>

                                                            <div class='row-fluid'>
                                                                <span class='span12' style='padding-left:31px;'><button class='btn btn-primary' style='width:435px;height:35px; '>Login Now</button></span>
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

        $list.="<div class='row-fluid' style=''>";
        $list.="<span class='span6'>aaaAAAA ...</a>";
        $list.="</div>";

        return $list;
    }

}
