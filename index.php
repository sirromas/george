<?php
$host = $_SERVER['SERVER_NAME'];
$valid_passwords = array("mario" => "carbonell");
$valid_users = array_keys($valid_passwords);

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    die("Not authorized");
}
?>


<!DOCTYPE html>
<html  dir="ltr" lang="en" xml:lang="en">
    <head>
        <title>Practice Index</title>
        <link rel="shortcut icon" href="http://<?php echo $host; ?>/lms/theme/image.php/lambda/theme/1481704169/favicon" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="moodle, Practice Index" />
        <link rel="stylesheet" type="text/css" href="http://<?php echo $host; ?>/lms/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple-min.css" /><script id="firstthemesheet" type="text/css">/** Required in order to fix style inclusion problems in IE with YUI **/</script><link rel="stylesheet" type="text/css" href="http://<?php echo $host; ?>/lms/theme/styles.php/lambda/1481704169/all" />
        <script type="text/javascript">
            //<![CDATA[
            var M = {}; M.yui = {};
            M.pageloadstarttime = new Date();
            M.cfg = {"wwwroot":"http:\/\/<?php echo $host; ?>\/lms", "sesskey":"I23v6GWXIX", "loadingicon":"http:\/\/<?php echo $host; ?>\/lms\/theme\/image.php\/lambda\/core\/1481704169\/i\/loading_small", "themerev":"1481704169", "slasharguments":1, "theme":"lambda", "jsrev":"1481653553", "admin":"admin", "svgicons":true}; var yui1ConfigFn = function(me) {if (/-skin|reset|fonts|grids|base/.test(me.name)){me.type = 'css'; me.path = me.path.replace(/\.js/, '.css'); me.path = me.path.replace(/\/yui2-skin/, '/assets/skins/sam/yui2-skin')}};
            var yui2ConfigFn = function(me) {var parts = me.name.replace(/^moodle-/, '').split('-'), component = parts.shift(), module = parts[0], min = '-min'; if (/-(skin|core)$/.test(me.name)){parts.pop(); me.type = 'css'; min = ''}; if (module){var filename = parts.join('-'); me.path = component + '/' + module + '/' + filename + min + '.' + me.type} else me.path = component + '/' + component + '.' + me.type};
            YUI_config = {"debug":false, "base":"http:\/\/<?php echo $host; ?>\/lms\/lib\/yuilib\/3.17.2\/", "comboBase":"http:\/\/<?php echo $host; ?>\/lms\/theme\/yui_combo.php?", "combine":true, "filter":null, "insertBefore":"firstthemesheet", "groups":{"yui2":{"base":"http:\/\/<?php echo $host; ?>\/lms\/lib\/yuilib\/2in3\/2.9.0\/build\/", "comboBase":"http:\/\/<?php echo $host; ?>\/lms\/theme\/yui_combo.php?", "combine":true, "ext":false, "root":"2in3\/2.9.0\/build\/", "patterns":{"yui2-":{"group":"yui2", "configFn":yui1ConfigFn}}}, "moodle":{"name":"moodle", "base":"http:\/\/<?php echo $host; ?>\/lms\/theme\/yui_combo.php?m\/1481653553\/", "combine":true, "comboBase":"http:\/\/<?php echo $host; ?>\/lms\/theme\/yui_combo.php?", "ext":false, "root":"m\/1481653553\/", "patterns":{"moodle-":{"group":"moodle", "configFn":yui2ConfigFn}}, "filter":null, "modules":{"moodle-core-formchangechecker":{"requires":["base", "event-focus"]}, "moodle-core-chooserdialogue":{"requires":["base", "panel", "moodle-core-notification"]}, "moodle-core-dragdrop":{"requires":["base", "node", "io", "dom", "dd", "event-key", "event-focus", "moodle-core-notification"]}, "moodle-core-tooltip":{"requires":["base", "node", "io-base", "moodle-core-notification-dialogue", "json-parse", "widget-position", "widget-position-align", "event-outside", "cache-base"]}, "moodle-core-actionmenu":{"requires":["base", "event", "node-event-simulate"]}, "moodle-core-checknet":{"requires":["base-base", "moodle-core-notification-alert", "io-base"]}, "moodle-core-blocks":{"requires":["base", "node", "io", "dom", "dd", "dd-scroll", "moodle-core-dragdrop", "moodle-core-notification"]}, "moodle-core-notification":{"requires":["moodle-core-notification-dialogue", "moodle-core-notification-alert", "moodle-core-notification-confirm", "moodle-core-notification-exception", "moodle-core-notification-ajaxexception"]}, "moodle-core-notification-dialogue":{"requires":["base", "node", "panel", "escape", "event-key", "dd-plugin", "moodle-core-widget-focusafterclose", "moodle-core-lockscroll"]}, "moodle-core-notification-alert":{"requires":["moodle-core-notification-dialogue"]}, "moodle-core-notification-confirm":{"requires":["moodle-core-notification-dialogue"]}, "moodle-core-notification-exception":{"requires":["moodle-core-notification-dialogue"]}, "moodle-core-notification-ajaxexception":{"requires":["moodle-core-notification-dialogue"]}, "moodle-core-formautosubmit":{"requires":["base", "event-key"]}, "moodle-core-dock":{"requires":["base", "node", "event-custom", "event-mouseenter", "event-resize", "escape", "moodle-core-dock-loader"]}, "moodle-core-dock-loader":{"requires":["escape"]}, "moodle-core-event":{"requires":["event-custom"]}, "moodle-core-maintenancemodetimer":{"requires":["base", "node"]}, "moodle-core-handlebars":{"condition":{"trigger":"handlebars", "when":"after"}}, "moodle-core-languninstallconfirm":{"requires":["base", "node", "moodle-core-notification-confirm", "moodle-core-notification-alert"]}, "moodle-core-lockscroll":{"requires":["plugin", "base-build"]}, "moodle-core-popuphelp":{"requires":["moodle-core-tooltip"]}, "moodle-core_availability-form":{"requires":["base", "node", "event", "panel", "moodle-core-notification-dialogue", "json"]}, "moodle-backup-backupselectall":{"requires":["node", "event", "node-event-simulate", "anim"]}, "moodle-backup-confirmcancel":{"requires":["node", "node-event-simulate", "moodle-core-notification-confirm"]}, "moodle-calendar-info":{"requires":["base", "node", "event-mouseenter", "event-key", "overlay", "moodle-calendar-info-skin"]}, "moodle-course-util":{"requires":["node"], "use":["moodle-course-util-base"], "submodules":{"moodle-course-util-base":{}, "moodle-course-util-section":{"requires":["node", "moodle-course-util-base"]}, "moodle-course-util-cm":{"requires":["node", "moodle-course-util-base"]}}}, "moodle-course-dragdrop":{"requires":["base", "node", "io", "dom", "dd", "dd-scroll", "moodle-core-dragdrop", "moodle-core-notification", "moodle-course-coursebase", "moodle-course-util"]}, "moodle-course-categoryexpander":{"requires":["node", "event-key"]}, "moodle-course-modchooser":{"requires":["moodle-core-chooserdialogue", "moodle-course-coursebase"]}, "moodle-course-formatchooser":{"requires":["base", "node", "node-event-simulate"]}, "moodle-course-toolboxes":{"requires":["node", "base", "event-key", "node", "io", "moodle-course-coursebase", "moodle-course-util"]}, "moodle-course-management":{"requires":["base", "node", "io-base", "moodle-core-notification-exception", "json-parse", "dd-constrain", "dd-proxy", "dd-drop", "dd-delegate", "node-event-delegate"]}, "moodle-form-dateselector":{"requires":["base", "node", "overlay", "calendar"]}, "moodle-form-passwordunmask":{"requires":["node", "base"]}, "moodle-form-shortforms":{"requires":["node", "base", "selector-css3", "moodle-core-event"]}, "moodle-form-showadvanced":{"requires":["node", "base", "selector-css3"]}, "moodle-core_message-deletemessage":{"requires":["node", "event"]}, "moodle-core_message-messenger":{"requires":["escape", "handlebars", "io-base", "moodle-core-notification-ajaxexception", "moodle-core-notification-alert", "moodle-core-notification-dialogue", "moodle-core-notification-exception"]}, "moodle-question-qbankmanager":{"requires":["node", "selector-css3"]}, "moodle-question-chooser":{"requires":["moodle-core-chooserdialogue"]}, "moodle-question-searchform":{"requires":["base", "node"]}, "moodle-question-preview":{"requires":["base", "dom", "event-delegate", "event-key", "core_question_engine"]}, "moodle-availability_completion-form":{"requires":["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_date-form":{"requires":["base", "node", "event", "io", "moodle-core_availability-form"]}, "moodle-availability_grade-form":{"requires":["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_group-form":{"requires":["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_grouping-form":{"requires":["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_profile-form":{"requires":["base", "node", "event", "moodle-core_availability-form"]}, "moodle-qtype_ddimageortext-form":{"requires":["moodle-qtype_ddimageortext-dd", "form_filepicker"]}, "moodle-qtype_ddimageortext-dd":{"requires":["node", "dd", "dd-drop", "dd-constrain"]}, "moodle-qtype_ddmarker-dd":{"requires":["node", "event-resize", "dd", "dd-drop", "dd-constrain", "graphics"]}, "moodle-qtype_ddmarker-form":{"requires":["moodle-qtype_ddmarker-dd", "form_filepicker", "graphics", "escape"]}, "moodle-qtype_ddwtos-dd":{"requires":["node", "dd", "dd-drop", "dd-constrain"]}, "moodle-mod_assign-history":{"requires":["node", "transition"]}, "moodle-mod_forum-subscriptiontoggle":{"requires":["base-base", "io-base"]}, "moodle-mod_quiz-quizbase":{"requires":["base", "node"]}, "moodle-mod_quiz-randomquestion":{"requires":["base", "event", "node", "io", "moodle-core-notification-dialogue"]}, "moodle-mod_quiz-util":{"requires":["node"], "use":["moodle-mod_quiz-util-base"], "submodules":{"moodle-mod_quiz-util-base":{}, "moodle-mod_quiz-util-slot":{"requires":["node", "moodle-mod_quiz-util-base"]}, "moodle-mod_quiz-util-page":{"requires":["node", "moodle-mod_quiz-util-base"]}}}, "moodle-mod_quiz-questionchooser":{"requires":["moodle-core-chooserdialogue", "moodle-mod_quiz-util", "querystring-parse"]}, "moodle-mod_quiz-dragdrop":{"requires":["base", "node", "io", "dom", "dd", "dd-scroll", "moodle-core-dragdrop", "moodle-core-notification", "moodle-mod_quiz-quizbase", "moodle-mod_quiz-util-base", "moodle-mod_quiz-util-page", "moodle-mod_quiz-util-slot", "moodle-course-util"]}, "moodle-mod_quiz-autosave":{"requires":["base", "node", "event", "event-valuechange", "node-event-delegate", "io-form"]}, "moodle-mod_quiz-repaginate":{"requires":["base", "event", "node", "io", "moodle-core-notification-dialogue"]}, "moodle-mod_quiz-quizquestionbank":{"requires":["base", "event", "node", "io", "io-form", "yui-later", "moodle-question-qbankmanager", "moodle-core-notification-dialogue"]}, "moodle-mod_quiz-modform":{"requires":["base", "node", "event"]}, "moodle-mod_quiz-toolboxes":{"requires":["base", "node", "event", "event-key", "io", "moodle-mod_quiz-quizbase", "moodle-mod_quiz-util-slot", "moodle-core-notification-ajaxexception"]}, "moodle-message_airnotifier-toolboxes":{"requires":["base", "node", "io"]}, "moodle-block_navigation-navigation":{"requires":["base", "io-base", "node", "event-synthetic", "event-delegate", "json-parse"]}, "moodle-filter_glossary-autolinker":{"requires":["base", "node", "io-base", "json-parse", "event-delegate", "overlay", "moodle-core-event", "moodle-core-notification-alert", "moodle-core-notification-exception", "moodle-core-notification-ajaxexception"]}, "moodle-filter_mathjaxloader-loader":{"requires":["moodle-core-event"]}, "moodle-editor_atto-rangy":{"requires":[]}, "moodle-editor_atto-editor":{"requires":["node", "transition", "io", "overlay", "escape", "event", "event-simulate", "event-custom", "node-event-html5", "yui-throttle", "moodle-core-notification-dialogue", "moodle-core-notification-confirm", "moodle-editor_atto-rangy", "handlebars", "timers"]}, "moodle-editor_atto-plugin":{"requires":["node", "base", "escape", "event", "event-outside", "handlebars", "event-custom", "timers", "moodle-editor_atto-menu"]}, "moodle-editor_atto-menu":{"requires":["moodle-core-notification-dialogue", "node", "event", "event-custom"]}, "moodle-report_eventlist-eventfilter":{"requires":["base", "event", "node", "node-event-delegate", "datatable", "autocomplete", "autocomplete-filters"]}, "moodle-report_loglive-fetchlogs":{"requires":["base", "event", "node", "io", "node-event-delegate"]}, "moodle-gradereport_grader-gradereporttable":{"requires":["base", "node", "event", "handlebars", "overlay", "event-hover"]}, "moodle-gradereport_history-userselector":{"requires":["escape", "event-delegate", "event-key", "handlebars", "io-base", "json-parse", "moodle-core-notification-dialogue"]}, "moodle-tool_capability-search":{"requires":["base", "node"]}, "moodle-tool_monitor-dropdown":{"requires":["base", "event", "node"]}, "moodle-assignfeedback_editpdf-editor":{"requires":["base", "event", "node", "io", "graphics", "json", "event-move", "event-resize", "querystring-stringify-simple", "moodle-core-notification-dialog", "moodle-core-notification-exception", "moodle-core-notification-ajaxexception"]}, "moodle-atto_accessibilitychecker-button":{"requires":["color-base", "moodle-editor_atto-plugin"]}, "moodle-atto_accessibilityhelper-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_align-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_bold-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_charmap-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_clear-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_collapse-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_emoticon-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_equation-button":{"requires":["moodle-editor_atto-plugin", "moodle-core-event", "io", "event-valuechange", "tabview", "array-extras"]}, "moodle-atto_html-button":{"requires":["moodle-editor_atto-plugin", "event-valuechange"]}, "moodle-atto_image-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_indent-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_italic-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_link-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_managefiles-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_managefiles-usedfiles":{"requires":["node", "escape"]}, "moodle-atto_media-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_noautolink-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_orderedlist-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_rtl-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_strike-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_subscript-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_superscript-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_table-button":{"requires":["moodle-editor_atto-plugin", "moodle-editor_atto-menu", "event", "event-valuechange"]}, "moodle-atto_title-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_underline-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_undo-button":{"requires":["moodle-editor_atto-plugin"]}, "moodle-atto_unorderedlist-button":{"requires":["moodle-editor_atto-plugin"]}}}, "gallery":{"name":"gallery", "base":"http:\/\/<?php echo $host; ?>\/lms\/lib\/yuilib\/gallery\/", "combine":true, "comboBase":"http:\/\/<?php echo $host; ?>\/lms\/theme\/yui_combo.php?", "ext":false, "root":"gallery\/1481653553\/", "patterns":{"gallery-":{"group":"gallery"}}}}, "modules":{"core_filepicker":{"name":"core_filepicker", "fullpath":"http:\/\/<?php echo $host; ?>\/lms\/lib\/javascript.php\/1481653553\/repository\/filepicker.js", "requires":["base", "node", "node-event-simulate", "json", "async-queue", "io-base", "io-upload-iframe", "io-form", "yui2-treeview", "panel", "cookie", "datatable", "datatable-sort", "resize-plugin", "dd-plugin", "escape", "moodle-core_filepicker"]}}};
            M.yui.loader = {modules: {}};
            //]]>
        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <noscript>
        <link rel="stylesheet" type="text/css" href="http://<?php echo $host; ?>/lms/theme/lambda/style/nojs.css" />
        </noscript>
        <!-- Google web fonts -->

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400|Open+Sans:700" rel="stylesheet"></head>

    <body  id="page-site-index" class="format-site course path-site safari dir-ltr lang-en yui-skin-sam yui3-skin-sam mycodebusters-com--lms pagelayout-frontpage course-1 context-2 notloggedin two-column has-region-side-pre used-region-side-pre has-region-side-post used-region-side-post has-region-footer-left empty-region-footer-left has-region-footer-middle empty-region-footer-middle has-region-footer-right empty-region-footer-right has-region-hidden-dock empty-region-hidden-dock">

        <div class="skiplinks"><a class="skip" href="#maincontent">Skip to main content</a></div>
        <script type="text/javascript" src="http://<?php echo $host; ?>/lms/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple-min.js&amp;rollup/1481653553/mcore-min.js"></script><script type="text/javascript" src="http://<?php echo $host; ?>/lms/theme/jquery.php/core/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="http://<?php echo $host; ?>/lms/theme/jquery.php/theme_lambda/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="http://<?php echo $host; ?>/lms/theme/jquery.php/theme_lambda/camera.min.js"></script>
        <script type="text/javascript" src="http://<?php echo $host; ?>/lms/theme/jquery.php/theme_lambda/jquery.bxslider.js"></script>
        <script type="text/javascript" src="http://<?php echo $host; ?>/lms/lib/javascript.php/1481653553/lib/javascript-static.js"></script>
        <script type="text/javascript">
            //<![CDATA[
            document.body.className += ' jsenabled';
            //]]>
        </script>


        <div id="wrapper">


            <header id="page-header" class="clearfix" style="padding: 12px 0">

                <div class="container-fluid">    
                    <div class="row-fluid">
                        <!-- HEADER: LOGO AREA -->

                        <div class="span6">
                            <div class="title-text">
                                <h1 id="title"><img src="http://practiceindex.co.uk/skin/frontend/default/practiceindex/images/logo.png"></h1>
                            </div>
                        </div>


                        <div class="span6 login-header">
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
                        </div>

                    </div>
                </div>

            </header>

            <header role="banner" class="navbar" style="padding-top:10px;">
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


            <div id="page" class="container-fluid">

                <div id="page-content" class="row-fluid">

                    <li class="activity label modtype_label " id="module-280">
                        <div id="yui_3_17_2_1_1481703191444_138">
                            <div class="mod-indent-outer" id="yui_3_17_2_1_1481703191444_137"><div class="mod-indent"></div>
                                <div id="yui_3_17_2_1_1481703191444_136"><div class="contentwithoutlink " id="yui_3_17_2_1_1481703191444_135">
                                        <div class="no-overflow" id="yui_3_17_2_1_1481703191444_134">
                                            <div class="no-overflow" id="yui_3_17_2_1_1481703191444_133">
                                                <div class="row-fluid frontpage" id="yui_3_17_2_1_1481703191444_132">
                                                    <div class="span6" id="yui_3_17_2_1_1481703191444_141">
                                                        <img src="http://<?php echo $host; ?>/assets/img/lambda_responsive.jpg" alt="lambda responsive" class="img-responsive" id="yui_3_17_2_1_1481703191444_140"></div>
                                                    <div class="span6" id="yui_3_17_2_1_1481703191444_131">

                                                        <form class="navbar-form" method="post" action="http://<?php echo $host; ?>/lms/login/index.php?authldap_skipntlmsso=1">

                                                            <div class="row-fluid" style="">	
                                                                <span class="span12" style="padding-bottom:15px;padding-left:35px;"><input id="inputName" class="span2" type="text" name="username" placeholder="Username or Email" required style="width:435px;height:35px; "></span>
                                                            </div>   

                                                            <div class="row-fluid">
                                                                <span class="span12" style="padding-bottom:15px;padding-left:35px"><input id="inputPassword" class="span2" type="password" name="password" id="password" placeholder="Password" required style="width:435px;height:35px;"></span>
                                                            </div>

                                                            <div class="row-fluid">
                                                                <span class="span12" style="padding-left:31px;"><button class="btn btn-primary" style="width:435px;height:35px; ">Login Now</button></span>
                                                            </div>

                                                            <div class="forgotpass" style="padding-left:35px;">
                                                                <a style="" target="_self" href="http://<?php echo $host; ?>/lms/login/forgot_password.php">Forgotten your username or password?</a>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div></div></div></div></div></div></div></li>

                </div>


                <a href="#top" class="back-to-top"><i class="fa fa-chevron-circle-up fa-3x"></i><p></p></a>

            </div>

            <footer id="page-footer" class="container-fluid">
                <div class="row-fluid">
                    <span class="span6" style="text-align: right;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;">Terms & Conditions</a></span>
                    <span class="span6" style="text-align: left;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;">Privacy Policy</a></span>
                </div>

                <div class="footerlinks">
                    <div class="row-fluid" >
                        <p style="text-align:center;color: #bdc3c7;">&copy; Copyright 2017 - Practice Index Ltd All rights reserved</p>
                    </div>



                </div>	</footer>

            <script type="text/javascript">
                //<![CDATA[
                var require = {
                baseUrl : 'http://<?php echo $host; ?>/lms/lib/requirejs.php/1481653553/',
                        // We only support AMD modules with an explicit define() statement.
                        enforceDefine: true,
                        skipDataMain: true,
                        paths: {
                        jquery: 'http://<?php echo $host; ?>/lms/lib/javascript.php/1481653553/lib/jquery/jquery-1.11.3.min',
                                jqueryui: 'http://<?php echo $host; ?>/lms/lib/javascript.php/1481653553/lib/jquery/ui-1.11.4/jquery-ui.min',
                                jqueryprivate: 'http://<?php echo $host; ?>/lms/lib/javascript.php/1481653553/lib/requirejs/jquery-private'
                        },
                        // Custom jquery config map.
                        map: {
                        // '*' means all modules will get 'jqueryprivate'
                        // for their 'jquery' dependency.
                        '*': { jquery: 'jqueryprivate' },
                                // 'jquery-private' wants the real jQuery module
                                // though. If this line was not here, there would
                                // be an unresolvable cyclic dependency.
                                jqueryprivate: { jquery: 'jquery' }
                        }
                };
                //]]>
            </script>
            <script type="text/javascript" src="http://<?php echo $host; ?>/lms/lib/javascript.php/1481653553/lib/requirejs/require.min.js"></script>
            <script type="text/javascript">
                //<![CDATA[
                require(['core/first'], function() {
                ;
                require(["core/log"], function(amd) { amd.setConfig({"level":"warn"}); });
                });
                //]]>
            </script>
            <script type="text/javascript" src="http://<?php echo $host; ?>/lms/theme/javascript.php/lambda/1481704169/footer"></script>
            <script type="text/javascript">
                //<![CDATA[
                M.str = {"moodle":{"lastmodified":"Last modified", "name":"Name", "error":"Error", "info":"Information", "viewallcourses":"View all courses", "morehelp":"More help", "loadinghelp":"Loading...", "cancel":"Cancel", "yes":"Yes", "confirm":"Confirm", "no":"No", "areyousure":"Are you sure?", "closebuttontitle":"Close", "unknownerror":"Unknown error"}, "repository":{"type":"Type", "size":"Size", "invalidjson":"Invalid JSON string", "nofilesattached":"No files attached", "filepicker":"File picker", "logout":"Logout", "nofilesavailable":"No files available", "norepositoriesavailable":"Sorry, none of your current repositories can return files in the required format.", "fileexistsdialogheader":"File exists", "fileexistsdialog_editor":"A file with that name has already been attached to the text you are editing.", "fileexistsdialog_filemanager":"A file with that name has already been attached", "renameto":"Rename to \"{$a}\"", "referencesexist":"There are {$a} alias\/shortcut files that use this file as their source", "select":"Select"}, "block":{"addtodock":"Move this to the dock", "undockitem":"Undock this item", "dockblock":"Dock {$a} block", "undockblock":"Undock {$a} block", "undockall":"Undock all", "hidedockpanel":"Hide the dock panel", "hidepanel":"Hide panel"}, "langconfig":{"thisdirectionvertical":"btt"}, "admin":{"confirmation":"Confirmation"}};
                //]]>
            </script>
            <script type="text/javascript">
                //<![CDATA[
                var navtreeexpansions4 = [{"id":"expandable_branch_0_courses", "key":"courses", "type":0}];
                //]]>
            </script>
            <script type="text/javascript">
                //<![CDATA[
                (function() {M.util.load_flowplayer();
                setTimeout("fix_column_widths()", 20);
                Y.use("moodle-core-dock-loader", function() {M.core.dock.loader.initLoader();
                });
                Y.use("moodle-block_navigation-navigation", function() {M.block_navigation.init_add_tree({"id":"4", "instance":"4", "candock":true, "courselimit":"20", "expansionlimit":0});
                });
                Y.use("moodle-block_navigation-navigation", function() {M.block_navigation.init_add_tree({"id":"5", "instance":"5", "candock":true});
                });
                Y.use("moodle-calendar-info", function() {Y.M.core_calendar.info.init();
                });
                M.util.help_popups.setup(Y);
                Y.use("moodle-core-popuphelp", function() {M.core.init_popuphelp();
                });
                M.util.init_skiplink(Y);
                M.util.init_block_hider(Y, {"id":"inst4", "title":"Navigation", "preference":"block4hidden", "tooltipVisible":"Hide Navigation block", "tooltipHidden":"Show Navigation block"});
                M.util.init_block_hider(Y, {"id":"inst3", "title":"Calendar", "preference":"block3hidden", "tooltipVisible":"Hide Calendar block", "tooltipHidden":"Show Calendar block"});
                M.util.js_pending('random585103b3393f25'); Y.on('domready', function() { M.util.js_complete("init"); M.util.js_complete('random585103b3393f25'); });
                })();
                //]]>
            </script>

            <!--[if lte IE 9]>
            <script src="http://<?php echo $host; ?>/lms/theme/lambda/javascript/ie/iefix.js"></script>
            <![endif]-->

            <script>
                $(window).on('load resize', function () {
                if (window.matchMedia('(min-width: 980px)').matches) {
                $('.navbar .dropdown').hover(function() {
                $(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
                }, function() {
                $(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
                });
                } else {$('.dropdown-menu').removeAttr("style"); $('.navbar .dropdown').unbind('mouseenter mouseleave'); }
                });
                jQuery(document).ready(function() {
                var offset = 220;
                var duration = 500;
                jQuery(window).scroll(function() {
                if (jQuery(this).scrollTop() > offset) {
                jQuery('.back-to-top').fadeIn(duration);
                } else {
                jQuery('.back-to-top').fadeOut(duration);
                }
                });
                jQuery('.back-to-top').click(function(event) {
                event.preventDefault();
                jQuery('html, body').animate({scrollTop: 0}, duration);
                return false;
                })
                });



            </script>

    </body>
</html>