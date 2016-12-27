/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    console.log("ready!");

    /*****************************************************************
     * 
     *                  Site pages manipulation
     * 
     *****************************************************************/

    $('body').click(function (event) {
        console.log('Element ID: ' + event.target.id);
        console.log('Element class: ' + $(event.target).attr('class'));

        if ($(event.target).attr('class') == 'pages-list') {
            var id = event.target.id;
            var url = "/lms/custom/admin/get_site_page.php";
            $.post(url, {id: id}).done(function (data) {
                $('#pages').html(data);
            });

        }

        if ($(event.target).attr('class') == 'cancel-edit-pages') {
            document.location.reload();
        }

        if ($(event.target).attr('class') == 'update-user-page') {
            var id = $('#id').val();
            var data = CKEDITOR.instances.editor1.getData();
            var url = "/lms/custom/admin/update_site_page.php";
            $.post(url, {id: id, data: data}).done(function () {
                document.location.reload();
            });
        }





    });






}); // end of document ready