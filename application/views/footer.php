<!-- Footer view -->
<a href="#top" class="back-to-top"><i class="fa fa-chevron-circle-up fa-3x"></i><p></p></a>

</div>

<footer id="page-footer" class="container-fluid">
    <?php $host = $_SERVER['SERVER_NAME']; ?>
    <div class="row-fluid">
        <span class="span6" style="text-align: right;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://<?php echo $host; ?>/index.php/index/terms">Terms & Conditions</a></span>
        <span class="span6" style="text-align: left;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://<?php echo $host; ?>/index.php/index/privacy">Privacy Policy</a></span>
    </div>

    <div class="footerlinks">
        <div class="row-fluid" >
            <p style="text-align:center;color: #bdc3c7;">&copy; Copyright 2017 - Practice Index Ltd All rights reserved</p>
        </div>



    </div>	</footer>

<script type="text/javascript">
    //<![CDATA[
    var require = {
        baseUrl: 'http://<?php echo $host; ?>/lms/lib/requirejs.php/1481653553/',
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
            '*': {jquery: 'jqueryprivate'},
            // 'jquery-private' wants the real jQuery module
            // though. If this line was not here, there would
            // be an unresolvable cyclic dependency.
            jqueryprivate: {jquery: 'jquery'}
        }
    };
    //]]>
</script>
<script type="text/javascript" src="http://<?php echo $host; ?>/lms/lib/javascript.php/1481653553/lib/requirejs/require.min.js"></script>
<script type="text/javascript">
    //<![CDATA[
    require(['core/first'], function () {
        ;
        require(["core/log"], function (amd) {
            amd.setConfig({"level": "warn"});
        });
    });
    //]]>
</script>
<script type="text/javascript" src="http://<?php echo $host; ?>/lms/theme/javascript.php/lambda/1481704169/footer"></script>
<script type="text/javascript">
    //<![CDATA[
    M.str = {"moodle": {"lastmodified": "Last modified", "name": "Name", "error": "Error", "info": "Information", "viewallcourses": "View all courses", "morehelp": "More help", "loadinghelp": "Loading...", "cancel": "Cancel", "yes": "Yes", "confirm": "Confirm", "no": "No", "areyousure": "Are you sure?", "closebuttontitle": "Close", "unknownerror": "Unknown error"}, "repository": {"type": "Type", "size": "Size", "invalidjson": "Invalid JSON string", "nofilesattached": "No files attached", "filepicker": "File picker", "logout": "Logout", "nofilesavailable": "No files available", "norepositoriesavailable": "Sorry, none of your current repositories can return files in the required format.", "fileexistsdialogheader": "File exists", "fileexistsdialog_editor": "A file with that name has already been attached to the text you are editing.", "fileexistsdialog_filemanager": "A file with that name has already been attached", "renameto": "Rename to \"{$a}\"", "referencesexist": "There are {$a} alias\/shortcut files that use this file as their source", "select": "Select"}, "block": {"addtodock": "Move this to the dock", "undockitem": "Undock this item", "dockblock": "Dock {$a} block", "undockblock": "Undock {$a} block", "undockall": "Undock all", "hidedockpanel": "Hide the dock panel", "hidepanel": "Hide panel"}, "langconfig": {"thisdirectionvertical": "btt"}, "admin": {"confirmation": "Confirmation"}};
    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    var navtreeexpansions4 = [{"id": "expandable_branch_0_courses", "key": "courses", "type": 0}];
    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    (function () {
        M.util.load_flowplayer();
        setTimeout("fix_column_widths()", 20);
        Y.use("moodle-core-dock-loader", function () {
            M.core.dock.loader.initLoader();
        });
        Y.use("moodle-block_navigation-navigation", function () {
            M.block_navigation.init_add_tree({"id": "4", "instance": "4", "candock": true, "courselimit": "20", "expansionlimit": 0});
        });
        Y.use("moodle-block_navigation-navigation", function () {
            M.block_navigation.init_add_tree({"id": "5", "instance": "5", "candock": true});
        });
        Y.use("moodle-calendar-info", function () {
            Y.M.core_calendar.info.init();
        });
        M.util.help_popups.setup(Y);
        Y.use("moodle-core-popuphelp", function () {
            M.core.init_popuphelp();
        });
        M.util.init_skiplink(Y);
        M.util.init_block_hider(Y, {"id": "inst4", "title": "Navigation", "preference": "block4hidden", "tooltipVisible": "Hide Navigation block", "tooltipHidden": "Show Navigation block"});
        M.util.init_block_hider(Y, {"id": "inst3", "title": "Calendar", "preference": "block3hidden", "tooltipVisible": "Hide Calendar block", "tooltipHidden": "Show Calendar block"});
        M.util.js_pending('random585103b3393f25');
        Y.on('domready', function () {
            M.util.js_complete("init");
            M.util.js_complete('random585103b3393f25');
        });
    })();
    //]]>
</script>

<!--[if lte IE 9]>
<script src="http://<?php echo $host; ?>/lms/theme/lambda/javascript/ie/iefix.js"></script>
<![endif]-->

<script>
    $(window).on('load resize', function () {
        if (window.matchMedia('(min-width: 980px)').matches) {
            $('.navbar .dropdown').hover(function () {
                $(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
            }, function () {
                $(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
            });
        } else {
            $('.dropdown-menu').removeAttr("style");
            $('.navbar .dropdown').unbind('mouseenter mouseleave');
        }
    });
    jQuery(document).ready(function () {
        var offset = 220;
        var duration = 500;
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > offset) {
                jQuery('.back-to-top').fadeIn(duration);
            } else {
                jQuery('.back-to-top').fadeOut(duration);
            }
        });
        jQuery('.back-to-top').click(function (event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, duration);
            return false;
        })
    });



</script>

</body>
</html>
