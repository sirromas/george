<?php
require_once './classes/Requests.php';
$r = new Requests();
$id = 10;
$list = $r->get_contact_requests_page($id);
$total4 = $r->get_request_total();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {
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
    });


</script>