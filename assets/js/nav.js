/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    console.log("ready!");
    var dialog_loaded;
    var domain = 'mycodebusters.com';
    /*****************************************************************
     * 
     *                  Site pages manipulation
     * 
     *****************************************************************/

    function get_news_page() {
        var url = "/lms/custom/admin/get_news_page.php";
        $.post(url, {id: id}).done(function (data) {
            $('#pages').html(data);
        });
    }

    function get_elearning_suites_page() {
        var url = "http://" + domain + "/lms/custom/admin/get_suites_page.php";
        var request = {id: 1};
        $.post(url, request).done(function (data) {
            $('#pages').html(data);
        });
    }

    function get_subscribers_page() {
        var id = 10;
        var url = "/lms/custom/admin/get_subscribers_page.php";
        $.post(url, {id: id}).done(function (data) {
            $('#pages').html(data);
        });
    }

    $('body').click(function (event) {
        console.log('Element ID: ' + event.target.id);
        console.log('Element class: ' + $(event.target).attr('class'));

        if ($(event.target).attr('class') == 'pages-list') {
            var id = event.target.id;
            var url = "/lms/custom/admin/get_site_page.php";
            $.post(url, {id: id}).done(function (data) {
                $('#pages').html(data);
            }); // end of post
        }

        if ($(event.target).attr('class') == 'cancel-edit-pages') {
            var url = "/lms/custom/admin/get_site_pages.php";
            $.post(url, {id: id}).done(function (data) {
                $('#pages').html(data);
            });
        }

        if ($(event.target).attr('class') == 'update-user-page') {
            var id = $('#id').val();
            var data = CKEDITOR.instances.editor1.getData();
            var url = "/lms/custom/admin/update_site_page.php";
            var url2 = "/lms/custom/admin/get_site_pages.php";
            $.post(url, {id: id, data: data}).done(function () {
                $.post(url2, {id: id}).done(function (data) {
                    $('#pages').html(data);
                });
            });
        }

        if (event.target.id == 'suite_back') {
            var url = "/lms/custom/admin/get_site_pages.php";
            $.post(url, {id: id}).done(function (data) {
                $('#pages').html(data);
            });
        }

        if (event.target.id == 'cancel') {
            $("#myModal").remove();
            dialog_loaded = false;
        }

        if (event.target.id == 'add_suite') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/admin/get_add_suite_dialog.php";
                            var request = {id: 1};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'add_news') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/admin/get_add_news_dialog.php";
                            var request = {id: 1};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'add_news_button') {
            var title = $('#news_title').val();
            var content = CKEDITOR.instances.desc.getData();
            var url = '/lms/custom/admin/add_news.php';

            if (title != '' && content != '') {
                $('#news_err').html('');
                var news = {title: title, content: content};
                $.post(url, {news: JSON.stringify(news)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_news_page();
                });
            } // end if
            else {
                $('#news_err').html('Please provide all required fields');
            }
        }

        if (event.target.id == 'add_new_suite') {
            var title = $('#suite_title').val();
            var file_data = $('#suite_pic').prop('files');
            var content = CKEDITOR.instances.desc.getData();
            var url = '/lms/custom/admin/add_new_suite.php';
            if (title != '' && content != '') {
                $('#suite_err').html('');
                var form_data = new FormData();
                $.each(file_data, function (key, value) {
                    form_data.append(key, value);
                });
                form_data.append('title', title);
                form_data.append('content', content);
                $.ajax({
                    url: url,
                    data: form_data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (data) {
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        get_elearning_suites_page();
                    }
                });

            } // end if
            else {
                $('#suite_err').html('Please provide all required fields');
            } // end else
        }

        if (event.target.id.indexOf("suite_edit_") >= 0) {
            var id = event.target.id.replace("suite_edit_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/admin/get_edit_suite_dialog.php";
                            $.post(url, {id: id}).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'update_suite') {
            var id = $('#id').val();
            var title = $('#suite_title').val();
            var content = CKEDITOR.instances.desc.getData();
            var file_data = $('#suite_pic').prop('files');
            var url = '/lms/custom/admin/update_suite.php';
            if (title != '' && content != '') {
                $('#suite_err').html('');
                var form_data = new FormData();
                $.each(file_data, function (key, value) {
                    form_data.append(key, value);
                });
                form_data.append('id', id);
                form_data.append('title', title);
                form_data.append('content', content);
                $.ajax({
                    url: url,
                    data: form_data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (data) {
                        console.log(data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        get_elearning_suites_page();
                    }
                });
            } // end if
            else {
                $('#suite_err').html('Please provide all required fields');
            } // end else


        }

        if (event.target.id.indexOf("suite_del_") >= 0) {
            var id = event.target.id.replace("suite_del_", "");
            var url = '/lms/custom/admin/del_suite.php';
            if (confirm('Delete current eLearning suite?')) {
                $.post(url, {id: id}).done(function () {
                    get_elearning_suites_page();
                });
            }
        }

        if (event.target.id.indexOf("news_edit_") >= 0) {
            var id = event.target.id.replace("news_edit_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/admin/get_edit_news_dialog.php";
                            $.post(url, {id: id}).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("#myModal").modal('show');
            }

        }

        if (event.target.id.indexOf("news_del_") >= 0) {
            var id = event.target.id.replace("news_del_", "");
            var url = '/lms/custom/admin/del_news.php';
            if (confirm('Delete currene news?')) {
                $.post(url, {id: id}).done(function () {
                    get_news_page();
                });
            }
        }

        if (event.target.id == 'update_news_button') {
            var title = $('#news_title').val();
            var content = CKEDITOR.instances.desc.getData();
            var id = $('#id').val();
            var url = '/lms/custom/admin/update_news.php';

            if (title != '' && content != '') {
                $('#news_err').html('');
                var news = {title: title, content: content, id: id};
                $.post(url, {news: JSON.stringify(news)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_news_page();
                });
            } // end if
            else {
                $('#news_err').html('Please provide all required fields');
            }
        }

        if (event.target.id.indexOf("subs_status_") >= 0) {
            var id = event.target.id.replace("subs_status_", "");
            var el = '#' + event.target.id;
            var status = $(el).data('status');
            var url = '/lms/custom/admin/update_subs.php';
            var subs = {id: id, status: status};
            console.log('Subs object:' + JSON.stringify(subs));
            if (confirm('Change current subscriber status?')) {
                $.post(url, {subs: JSON.stringify(subs)}).done(function () {
                    get_subscribers_page();
                });
            } // end if
        }

        /*****************************************************************
         * 
         *                  Profile section
         * 
         *****************************************************************/

        if (event.target.id == 'update_profile') {
            console.log('Clicked ...');
            var id = $('#id').val();
            var firstname = $('#firstname').val();
            var lastname = $('#lastname').val();
            var email = $('#email').val();
            var pwd = $('#pwd').val();
            var profile = {id: id, firstname: firstname, lastname: lastname, email: email, pwd: pwd};
            console.log('Profile: ' + JSON.stringify(profile));

            if (firstname != '' && lastname != '' && email != '') {
                $('#profile_err').html('');
                if (pwd != '') {
                    if (pwd.length < 8) {
                        $('#profile_err').html('Password length should be at least 8 characters');
                    } // end if pwd.length < 8
                    else {
                        $('#profile_err').html('');
                    } // end else
                } // end if pwd != ''
                if ($('#profile_err').html() == '') {
                    var url = '/lms/custom/common/update_profile.php';
                    $.post(url, {profile: JSON.stringify(profile)}).done(function (data) {
                        $('#profile_err').html("<span style='color:black;'>" + data + "</span>");
                    });
                } // end if $('#profile_err').html()==''
            } // end if firstname != '' && lastname != '' && email != ''
            else {
                $('#profile_err').html('Please provide all required fields');
            }
        }

    }); // end of $('body').click(function (event) {

}); // end of document ready