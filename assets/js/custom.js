
$(document).ready(function () {
    console.log("ready!");

    $("#subs").click(function () {
        var name = $('#name').val();
        var email = $('#email').val();
        if (name != '' && email != '') {
            $('#subs_err').html('');
            var subs = {name: name, email: email};
            var url = "/index.php/index/subscribe_user";
            $.post(url, {subs: JSON.stringify(subs)}).done(function (data) {
                $('#subscribe_content').html(data);
            }); // end of post
        } // end if
        else {
            $('#subs_err').html('Please provide name and email');
        }

    });

    $("#contact_submit").click(function () {
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var comment = $('#comment').val();
        if (name != '' && email != '' && phone != '' && comment != '') {
            $('#contact_err').html('');
            var contact = {name: name, email: email, phone: phone, comment: comment};
            var url = "/index.php/index/send_contact_request";
            $.post(url, {contact: JSON.stringify(contact)}).done(function (data) {
                $('#contact_container').html(data);
            }); // end of post
        } // end if
        else {
            $('#contact_err').html('Please provide all required fields');
        }
    });


});