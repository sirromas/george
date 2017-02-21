<!-- Footer view -->
<a href="#top" class="back-to-top"><i class="fa fa-chevron-circle-up fa-3x"></i><p></p></a>

</div>

<footer id="page-footer" class="container-fluid">
    <?php $host = $_SERVER['SERVER_NAME']; ?>
    <div class="row-fluid">
        <!--
        <span class="span6" style="text-align: right;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/terms-and-conditions/">Terms & Conditions</a></span>
        <span class="span6" style="text-align: left;padding-left: 35px;"><a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/privacy-policy/">Privacy Policy</a></span>
        -->
        
        <p style="text-align:center;color: #bdc3c7;">
        <a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/terms-and-conditions/">Terms & Conditions</a>
        &nbsp;&nbsp;<a style="color:#bdc3c7;font-weight: bold;cursor: pointer;" href="http://practiceindex.co.uk/gp/privacy-policy/">Privacy Policy</a>
        </p>
        
        
    </div>

    <div class="footerlinks">
        <div class="row-fluid" >
            <p style="text-align:center;color: #bdc3c7;">&copy; Copyright 2017 - Practice Index Ltd. All rights reserved.</p>
        </div>



    </div>	</footer>


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
