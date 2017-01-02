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


    }); // end of document ready

</script>