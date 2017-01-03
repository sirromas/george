<?php
require_once './classes/Pages.php';
require_once './classes/News.php';
require_once './classes/Suites.php';
require_once './classes/Requests.php';
require_once './classes/Subscribers.php';
$p = new Pages();
$n = new News();
$l = new Suites();
$r = new Requests();
$s = new Subscribers();
$id = $_POST['id'];
$list = $p->get_site_page($id);
$total = $l->get_total_suites();
$total2 = $n->get_total_news();
$total3=$s->get_subs_total();

echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#suites_pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $p->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#suites_pagination").click(function () {
            var page = $('#suites_pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/admin/get_suite_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#suites_container').html(data);
            });
        });

        // *****************************************************************//
        
        $(function () {
            $('#news_pagination').pagination({
                items: <?php echo $total2; ?>,
                itemsOnPage: <?php echo $n->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#news_pagination").click(function () {
            var page = $('#news_pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/admin/get_news_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#news_container').html(data);
            });
        });
        
        // *****************************************************************//
        
        $(function () {
            $('#subs_pagination').pagination({
                items: <?php echo $total3; ?>,
                itemsOnPage: <?php echo $s->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#subs_pagination").click(function () {
            var page = $('#subs_pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/admin/get_subs_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#subs_container').html(data);
            });
        });
        
        // *****************************************************************//
        
        $(function () {
            $('#contact_pagination').pagination({
                items: <?php echo $total4; ?>,
                itemsOnPage: <?php echo $r->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#contact_pagination").click(function () {
            var page = $('#contact_pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/admin/get_contact_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#contact_container').html(data);
            });
        });

    }); // end of document ready

</script>