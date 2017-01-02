<?php

require_once './classes/News.php';
$n = new News();
$list = $n->get_news_page();
$total2 = $n->get_total_news();
echo $list;

?>

<script type="text/javascript">

    $(document).ready(function () {

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
