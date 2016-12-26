<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Parent theme: Bootstrapbase by Bas Brands
 * Built on: Essential by Julian Ridden
 *
 * @package   theme_lambda
 * @copyright 2016 redPIthemes
 *
 */
$login_link = theme_lambda_get_setting('login_link');
$login_custom_url = theme_lambda_get_setting('custom_login_link_url');
$login_custom_txt = theme_lambda_get_setting('custom_login_link_txt');
$home_button = theme_lambda_get_setting('home_button');
$shadow_effect = theme_lambda_get_setting('shadow_effect');
$auth_googleoauth2 = theme_lambda_get_setting('auth_googleoauth2');
$haslogo = (!empty($PAGE->theme->settings->logo));
$hasheaderprofilepic = (empty($PAGE->theme->settings->headerprofilepic)) ? false : $PAGE->theme->settings->headerprofilepic;
$moodle_global_search = 0;

$checkuseragent = '';
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
    $checkuseragent = $_SERVER['HTTP_USER_AGENT'];
}
$username = get_string('username');
if (strpos($checkuseragent, 'MSIE 8')) {
    $username = str_replace("'", "&prime;", $username);
}

?>
<header id="page-header" class="clearfix">

    <div class="container-fluid">    
        <div class="row-fluid">
            <!-- HEADER: LOGO AREA -->

            <?php if (!$haslogo) { ?>
                <div class="span6">
                    <div class="title-text">
                        <h1 id="title"><img src="http://practiceindex.co.uk/skin/frontend/default/practiceindex/images/logo.png"></h1>
                    </div>
                </div>
            <?php } else { ?>
                <div class="span6">
                    <div class="logo-header">
                        <a class="logo" href="<?php echo $CFG->wwwroot; ?>" title="<?php print_string('home'); ?>">

                            <?php
                            echo html_writer::empty_tag('img', array('src'=>$PAGE->theme->setting_file_url('logo', 'logo'), 'class'=>'img-responsive', 'alt'=>'logo'));
                            ?>
                        </a>
                    </div>
                </div>
            <?php } ?>      	

            <div class="span6 login-header">
                <div class="profileblock">

                    <?php

                    function get_content() {
                        global $USER, $CFG, $SESSION, $COURSE;
                        $wwwroot = '';
                        $signup = '';
                    }

                    if (empty($CFG->loginhttps)) {
                        $wwwroot = $CFG->wwwroot;
                    } else {
                        $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
                    }

                    if (!isloggedin() or isguestuser()) {

                        $login_link_url = '';
                        $login_link_txt = '';
                        if ($login_link == '1') {
                            $login_link_url = $wwwroot . '/login/signup.php';
                            $login_link_txt = get_string('startsignup');
                        } else if ($login_link == '2') {
                            $login_link_url = $wwwroot . '/login/forgot_password.php';
                            $login_link_txt = get_string('forgotten');
                        } else if ($login_link == '3') {
                            $login_link_url = $wwwroot . '/login/index.php';
                            $login_link_txt = get_string('moodle_login_page', 'theme_lambda');
                        }
                        if ($login_custom_url != '') {
                            $login_link_url = $login_custom_url;
                        }
                        if ($login_custom_txt != '') {
                            $login_link_txt = $login_custom_txt;
                        }

                        if ($auth_googleoauth2) {
                            require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');
                            auth_googleoauth2_display_buttons();
                            ?>
                            <div style="clear:both;"></div>
                            <div class="forgotpass oauth2">
                                <?php if ($login_link_url != '' and $login_link_txt != '') { ?>
                                    <a target="_self" href="<?php echo $login_link_url; ?>"><?php echo $login_link_txt; ?></a>
                                <?php } ?> 
                            </div>
                        <?php } else { ?>

                            <div class="profileblock">

                                <div class="row-fluid" style="">
                                    <span class="span12" style="text-align:right;"><a style='cursor: pointer;color:grey;'>About us</a> | <a style='cursor: pointer;color:grey;'>FAQs</a> | <a style='cursor: pointer;color:grey;'>Contact us</a></span>
                                </div>
                                <div class="row-fluid" style="">
                                    <span class='span12' style='color: #EBA600;font-size:18px;text-align: right;'>0207 0995510</span>
                                </div>

                                <div class="row-fluid" style="">
                                    <span class='span12' style='color: #EBA600;text-align: right;'><a href='mailto:support@practiceindex.co.uk' style="font-size:18px;">support@practiceindex.co.uk</a></span>
                                </div>

                            </div>
                        <?php } ?>

                        <?php
                    }
                    // your case is here ....
                    else {


                        echo "<div class='row-fluid' style=''>
                  <span class='span12' style='text-align:right;'><a style='cursor: pointer;color:grey;'>About us</a> | <a style='cursor: pointer;color:grey;'>FAQs</a> | <a style='cursor: pointer;color:grey;'>Contact us</a></span>
                  </div>
                  
                  <div class='row-fluid' style=''>
                  <span class='span12' style='color: #EBA600;font-size:18px;text-align: right;'>0207 0995510</span>
                  </div>

                  <div class='row-fluid' style=''>
                  <span class='span12' style='color: #EBA600;text-align: right;'><a href='mailto:support@practiceindex.co.uk' style='font-size:18px;'>support@practiceindex.co.uk</a></span>
                  </div>";
                    }
                    ?>

                </div>
            </div>

        </div>
    </div>

</header>

<?php
$url = $_SERVER['REQUEST_URI'];
if (strpos($url, 'forgot_password.php')) {
    ?>
    <header role="banner" class="navbar">
        <nav role="navigation" class="navbar-inner">
                    <div class="container-fluid">
                        <a class="brand" href="http://<?php echo $host; ?>/lms">Home</a>            
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <div class="nav-collapse collapse">
                            <ul class="nav pull-left">
                                <li><a style='cursor: pointer;'>eLearning Suites</a></li>
                                <li><a style='cursor: pointer;'>News</a></li>
                                <li><a style='cursor: pointer;'>FAQs</a></li>
                                <li><a style='cursor: pointer;'>Testimonials</a></li>
                                <li><a style='cursor: pointer;'>Our Policies</a></li>
                                <li><a style='cursor: pointer;'>Subscribe</a></li>
                                <li><a style='cursor: pointer;'>About Us</a></li>
                                <li><a style='cursor: pointer;'>Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
    </header>
<?php } ?>
<?php if ($shadow_effect) { ?>
    <div class="container-fluid"><img src="<?php echo $OUTPUT->pix_url('bg/lambda-shadow', 'theme'); ?>" class="lambda-shadow" alt=""></div>
<?php } ?>