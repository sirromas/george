<?php
require_once './classes/Subscribers.php';
$s = new Subscribers();
$id = 10;
$total3 = $s->get_subs_total();
$list = $s->get_subscribers_page($id);
echo $list;
?>

<script type="text/javascript">

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




</script>