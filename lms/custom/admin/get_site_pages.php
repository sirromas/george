<?php
require_once './classes/Pages.php';
require_once './classes/Suites.php';
$p = new Pages();
$l = new Suites();
$list = $p->get_page_list();
$total = $l->get_total_suites();
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

    }); // end of document ready

</script>