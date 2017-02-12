/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    console.log("ready!");

    // Monitor only few events ...
    $('body').on("click change click  blur select change submit reset", function (e) {
        var e_id = e.target.id;
        //console.log('Event id: ' + e_id);

        if (e_id == 'dash_icon' || e_id == 'dash_span') {
            console.log('Dash tab clicked ...');
        }

        if (e_id == 'courses_icon' || e_id == 'courses_span') {
            console.log('Courses tab clicked ...');
        }

        if (e_id == 'users_icon' || e_id == 'users_span') {
            console.log('Users tab clicked ...');

        }

        if (e_id == 'reports_icon' || e_id == 'reports_span') {
            console.log('Reports tab clicked ...');

        }

    }); // end of $('body').on("click change click  blur select change



    $('#my_courses').DataTable();
    $('#all_courses').DataTable();
    $('#my_external_courses').DataTable();
    $('#all_external_courses').DataTable();
    $('#all_groups').DataTable();
    $('#data-users').DataTable();
    $('#repeat_courses').DataTable();
    $('#courses_policy').DataTable();
    $('#user_detailes_container').DataTable();

    $.post('/lms/custom/common/data/cohorts.json', {id: 1}, function (data) {
        $('#r_cohort').typeahead({source: data, items: 240});
    }, 'json');

    $.post('/lms/custom/common/data/practices.json', {id: 1}, function (data) {
        $('#r_practice').typeahead({source: data, items: 240});
    }, 'json');

    $.post('/lms/custom/common/data/courses.json', {id: 1}, function (data) {
        $('#r_courses').typeahead({source: data, items: 240});
    }, 'json');

    $.post('/lms/custom/common/data/users.json', {id: 1}, function (data) {
        $('#r_users').typeahead({source: data, items: 240});
    }, 'json');

    $('#date1').datepicker();
    $('#date2').datepicker();


    var course_stat_url = "/lms/custom/common/get_courses_progress.php";
    $.post(course_stat_url, {id: 1}).done(function (data) {
        console.log('Server response: ' + data);
        console.log('------------------------------');
        jQuery.each(JSON.parse(data), function (index, value) {
            var divid = '#pr_' + value.courseid;
            var progress = Math.round(value.stat);
            if (progress === null) {
                progress = 0;
            }

            $(divid).progressbar({
                value: parseInt(progress)
            });

            $(divid).tooltip({
                position: {my: "left+5 center", at: "right center"}
            });

        }); // end jQuery.each
    }); // end of post

    var dialog_loaded;
    var domain = 'mycodebusters.com';
    /*****************************************************************
     * 
     *                  Site pages manipulation
     * 
     *****************************************************************/

    function get_news_page() {
        var url = "/lms/custom/admin/get_news_page.php";
        $.post(url, {id: 1}).done(function (data) {
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

    function get_external_courses_page() {
        var id = 10; // fake value;
        var url = "/lms/custom/common/get_external_training_page.php";
        $.post(url, {id: id}).done(function (data) {
            $('#external').html(data);
        });
    }

    function get_my_courses_page() {
        var userid = $('#userid').val();
        var url = "/lms/custom/common/get_my_courses_page.php";
        $.post(url, {userid: userid}).done(function (data) {
            $('#external').html(data);
        });
    }

    function get_groups_page() {
        var url = "/lms/custom/common/get_groups_page.php";
        $.post(url, {userid: 2}).done(function (data) {
            $('#groups').html(data);
            $('#all_groups').DataTable();
        });
    }

    function get_users_page() {
        var url = "/lms/custom/common/get_users_page.php";
        $.post(url, {userid: 2}).done(function (data) {
            $('#users').html(data);
            $('#data-users').DataTable();
        });
    }

    function get_policy_page() {
        var url = "/lms/custom/common/get_policy_page.php";
        $.post(url, {userid: 2}).done(function (data) {
            $('#policy').html(data);
            $('#courses_policy').DataTable();
        });
    }

    $('body').click(function (event) {
        console.log('Element ID: ' + event.target.id);
        console.log('Element class: ' + $(event.target).attr('class'));

        if (event.target.id == 'refresh_dash') {
            var url = "/lms/custom/common/get_dashboard_tab.php";
            $.post(url, {id: 1}).done(function (data) {
                $('#dash').html(data);
            });
        }

        if (event.target.id == 'refresh_courses') {
            var url = "/lms/custom/common/get_courses_tab.php";
            $.post(url, {id: 1}).done(function (data) {
                $('#courses').html(data);
                $('#my_courses').DataTable();
                $('#all_courses').DataTable();

                var course_stat_url = "/lms/custom/common/get_courses_progress.php";
                $.post(course_stat_url, {id: 1}).done(function (data) {
                    console.log('Server response: ' + data);
                    console.log('------------------------------');
                    jQuery.each(JSON.parse(data), function (index, value) {
                        var divid = '#pr_' + value.courseid;
                        var progress = Math.round(value.stat);
                        if (progress === null) {
                            progress = 0;
                        }

                        $(divid).progressbar({
                            value: parseInt(progress)
                        });

                        $(divid).tooltip({
                            position: {my: "left+5 center", at: "right center"}
                        });

                    }); // end jQuery.each
                }); // end of post

            });
        }

        if (event.target.id == 'refresh_users') {
            var url = "/lms/custom/common/get_users_tab.php";
            $.post(url, {id: 1}).done(function (data) {
                $('#users').html(data);
                $('#data-users').DataTable();
            });
        }

        if (event.target.id == 'refresh_report') {
            var url = "/lms/custom/common/get_reports_tab.php";
            $.post(url, {id: 1}).done(function (data) {
                $('#reports').html(data);
                $('#user_detailes_container').DataTable();
            });
        }



        if ($(event.target).attr('class') == 'pages-list') {
            var id = event.target.id;
            var url = "/lms/custom/admin/get_site_page.php";
            $.post(url, {id: id}).done(function (data) {
                $('#pages').html(data);
                $('#news_table').DataTable();
                $('#suites_table').DataTable();
                $('#subscribers_table').DataTable();
                $('#requests_table').DataTable();

            }); // end of post
        }

        if ($(event.target).attr('class') == 'cancel-edit-pages') {
            var url = "/lms/custom/admin/get_site_pages.php";
            $.post(url, {id: id}).done(function (data) {
                $('#pages').html(data);
            });
        }

        if ($(event.target).attr('class') == 'update-user-page') {
            var id = $('#pageid').val();
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


            /*
             var url = "http://" + domain + "/lms/custom/admin/get_add_news_dialog.php";
             var request = {id: 1};
             $.post(url, request).done(function (data) {
             $("body").append(data);
             $("#myModal").modal('show');
             });
             */
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
                    if (confirm('Update profile?')) {
                        var url = '/lms/custom/common/update_profile.php';
                        $.post(url, {profile: JSON.stringify(profile)}).done(function (data) {
                            $('#profile_err').html("<span style='color:black;'>" + data + "</span>");
                        });
                    }
                } // end if $('#profile_err').html()==''
            } // end if firstname != '' && lastname != '' && email != ''
            else {
                $('#profile_err').html('* Please provide all required fields');
            }
        }

        /*****************************************************************
         * 
         *                  External training section
         * 
         *****************************************************************/

        if (event.target.id == 'add_ext_training') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_add_external_dialog.php";
                            $.post(url, {id: id}).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $('#ext_course_date').datepicker();
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

        if (event.target.id == 'add_external_course_button') {
            var userid = $('#userid').val();
            var name = $('#name').val();
            var provider = $('#provider').val();
            var date = $('#ext_course_date').val();
            var retake_duration = $('#ext_course_retake_duration').val();
            var notes = $('#ext_notes').val();
            var status = $('#ext_course_status').val();

            var course = {userid: userid,
                name: name,
                provider: provider,
                date: date,
                status: status,
                retake_duration: retake_duration,
                notes: notes};

            console.log('External Course Object: ' + course);


            if (name != '' && provider != '' && date != '' && retake_duration > 0 && status > 0) {
                $('#ext_err').html('');

                var url = '/lms/custom/common/add_external_training.php';
                $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                    console.log(data);
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_external_courses_page()
                });

            }  // end if
            else {
                $('#ext_err').html('Please provide all required fields');
            }
        }

        /*****************************************************************
         * 
         *                  Courses section
         * 
         *****************************************************************/

        if (event.target.id.indexOf("my_course_title") >= 0) {
            var courseid = event.target.id.replace("my_course_title_", "");
            var userid = $('#' + event.target.id).data('userid');
            console.log('Course id: ' + courseid);
            console.log('User id: ' + userid);
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var course = {courseid: courseid, userid: userid};
                            var url = "http://" + domain + "/lms/custom/common/get_my_course_details_dialog.php";
                            $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                                console.log(data);
                                $("body").append(data);
                                $("#myModal").css('background-color:', 'red');
                                $("#myModal").modal('show');
                                $('#ext_course_date').datepicker();
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

        if (event.target.id == 'add_course_category') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var course = {courseid: courseid, userid: userid};
                            var url = "http://" + domain + "/lms/custom/common/get_add_course_catergory_dialog.php";
                            $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                                console.log(data);
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

        if (event.target.id == 'add_course') {

        }

        if (event.target.id == 'add_course_category_button') {
            var name = $('#catname').val();
            if (name != '') {
                $('#cat_err').html('');
                var url = "http://" + domain + "/lms/custom/common/add_course_catergory.php";
                $.post(url, {name: name}).done(function (data) {
                    console.log(data);
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_my_courses_page();
                });
            } // end if name!=''
            else {
                $('#cat_err').html('Please provide category name');
            } // end else
        }

        if (event.target.id == 'add_gp') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var course = {courseid: courseid, userid: userid};
                            var url = "http://" + domain + "/lms/custom/common/get_add_gp_dialog.php";
                            $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                                console.log(data);
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

        if (event.target.id == 'add_gp_button') {
            var cohortid = $('#cohort').val();
            var new_ccg = $('#new_ccg').val();
            var gpname = $('#gpname').val();
            var firstname = $('#gpfirstname').val();
            var lastname = $('#gplastname').val();
            var gpcourses = $('#gpcourses').val();
            var email = $('#gpemail').val();
            var pwd = $('#gppwd').val();
            var courses_length = gpcourses.length;
            var practice = {new_ccg: new_ccg,
                cohortid: cohortid,
                gpname: gpname,
                firstname: firstname,
                lastname: lastname,
                email: email,
                pwd: pwd,
                gpcourses: gpcourses};
            console.log('Courses length ' + courses_length);
            //console.log('Practice:' + JSON.stringify(practice));
            if ((cohortid > 0 || new_ccg != '') && gpname != '' && firstname != '' && lastname != '' && email != '' && pwd != '' && gpcourses.length > 0 && gpcourses[0] != 0) {
                $('#gp_err').html('');
                var check_url = "http://" + domain + "/lms/custom/common/is_email_exists.php";
                $.post(check_url, {email: email}).done(function (data) {
                    if (data == 0) {
                        $('#gp_err').html('');
                        $('#gp_loader').show();
                        var url = "http://" + domain + "/lms/custom/common/add_practice.php";
                        $.post(url, {practice: JSON.stringify(practice)}).done(function (data) {
                            console.log(data);
                            $('#gp_loader').hide();
                            $("[data-dismiss=modal]").trigger({type: "click"});
                            get_groups_page();
                        });
                    } // end if data==0
                    else {
                        $('#gp_err').html('Email already in use');
                    }
                });
            } // end if gpname != '' && firstname != '' ...
            else {
                $('#gp_err').html('Please provide all required fields');
            }
        }

        if (event.target.id == 'create_new_ccg') {
            if ($('#create_new_ccg').prop('checked')) {
                console.log('Checked ...');
                $('#new_ccg').prop("disabled", false);
            } // end if
            else {
                console.log('Unchecked ...');
                $('#new_ccg').prop("disabled", true);
            } // end else 
        }

        if (event.target.id.indexOf("group_delete_userid_") >= 0) {
            var userid = event.target.id.replace("group_delete_userid_", "");
            if (confirm('All Practice data will be deleted. Continue anyway?')) {
                var url = "http://" + domain + "/lms/custom/common/del_practice.php";
                $.post(url, {userid: userid}).done(function (data) {
                    console.log(data);
                    get_groups_page();
                });
            }
        }


        if (event.target.id.indexOf("group_info_userid_") >= 0) {
            var userid = event.target.id.replace("group_info_userid_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var course = {courseid: courseid, userid: userid};
                            var url = "http://" + domain + "/lms/custom/common/gel_practice_admin_modal_dialog.php";
                            $.post(url, {userid: userid}).done(function (data) {
                                console.log(data);
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

        if (event.target.id.indexOf("group_practiceid_") >= 0) {
            var practiceid = event.target.id.replace("group_practiceid_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/gel_practice_courses_dialog.php";
                            $.post(url, {practiceid: practiceid}).done(function (data) {
                                console.log(data);
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

        if (event.target.id == 'gpadmin_update_button') {
            var userid = $('#gpadmin_userid').val();
            var firstname = $('#gpadmin_firstname').val();
            var lastname = $('#gpadmin_lastname').val();
            var email = $('#gpadmin_email').val();
            var pwd = $('#gpadmin_pwd').val();
            if (firstname != '' && lastname != '' && email != '') {
                $('#gpadmin_err').html('');
                if (confirm('Update user profile?')) {
                    var url = "http://" + domain + "/lms/custom/common/update_gpadmin.php";
                    var user = {userid: userid,
                        firstname: firstname,
                        lastname: lastname,
                        email: email,
                        pwd: pwd};
                    $.post(url, {user: JSON.stringify(user)}).done(function (data) {
                        console.log(data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        get_groups_page();
                    });
                }
            } // end if
            else {
                $('#gpadmin_err').html('Please provide all required fields');
            }
        }


        if (event.target.id.indexOf("practice_course_") >= 0) {
            var userid = $('#gpadmin_userid').val();
            var groupid = event.target.id.replace("practice_course_", "");
            if (confirm('Remove current course from the Practice?')) {
                var url = "http://" + domain + "/lms/custom/common/remove_course_from_practice.php";
                $.post(url, {userid: userid, groupid: groupid}).done(function (data) {
                    $('#existing_practice_courses_container').html(data);
                });
            }
        }

        if (event.target.id == 'practice_add_course_button') {
            var practiceid = $('#practiceid').val();
            var userid = $('#gpadmin_userid').val();
            var courses = $('#gpcourses').val();
            var practice = {practiceid: practiceid, userid: userid, courses: courses};
            var url = "http://" + domain + "/lms/custom/common/add_courses_to_practice.php";
            $.post(url, {practice: JSON.stringify(practice)}).done(function (data) {
                console.log(data);
                $("[data-dismiss=modal]").trigger({type: "click"});
                get_groups_page();
            });
        }

        if (event.target.id.indexOf("users_info_userid_") >= 0) {
            var current_user = $('#current_user').val();
            var userid = event.target.id.replace("users_info_userid_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_edit_user_dialog.php";
                            $.post(url, {current_user: current_user, userid: userid}).done(function (data) {
                                console.log(data);
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

        if (event.target.id.indexOf("users_courses_") >= 0) {
            var current_user = $('#current_user').val();
            var userid = event.target.id.replace("users_courses_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_user_courses_dialog.php";
                            $.post(url, {current_user: current_user, userid: userid}).done(function (data) {
                                console.log(data);
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

        if (event.target.id.indexOf("users_delete_userid_") >= 0) {
            var userid = event.target.id.replace("users_delete_userid_", "");
            if (confirm('Delete current user?')) {
                var url = "http://" + domain + "/lms/custom/common/delete_user.php";
                $.post(url, {userid: userid}).done(function (data) {
                    console.log(data);
                    get_users_page();
                });
            }
        }

        if (event.target.id.indexOf("user_course_") >= 0) {
            var courseid = event.target.id.replace("user_course_", "");
            var userid = $('#user_section_userid').val();
            console.log('Course ID: ' + courseid);
            if (confirm('Unenroll current user from this course?')) {
                var url = "http://" + domain + "/lms/custom/common/remove_user_from_course.php";
                $.post(url, {courseid: courseid, userid: userid}).done(function (data) {
                    $('#existing_user_courses_container').html(data);
                });
            }
        }


        if (event.target.id == 'update_user_section_user') {
            var userid = $('#user_section_userid').val();
            var firstname = $('#user_firstname').val();
            var lastname = $('#user_lastname').val();
            var email = $('#user_email').val();
            var pwd = $('#user_pwd').val();

            var user = {userid: userid,
                firstname: firstname,
                lastname: lastname,
                email: email,
                pwd: pwd
            };
            //console.log('User obj: ' + JSON.stringify(user));
            if (firstname != '' && lastname != '' && email != '') {
                $('#user_err').html('');
                var url = "http://" + domain + "/lms/custom/common/update_user_section_user.php";
                $.post(url, {user: JSON.stringify(user)}).done(function (data) {
                    console.log(data);
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_users_page();
                });
            } // end if
            else {
                $('#user_err').html('Please provide require fieds');
            }

        }

        if (event.target.id == 'update_user_courses') {
            var userid = $('#user_section_userid').val();
            var courses = $('#gpcourses').val();
            var user = {userid: userid, courses: courses};
            var url = "http://" + domain + "/lms/custom/common/update_user_courses.php";
            $.post(url, {user: JSON.stringify(user)}).done(function (data) {
                console.log(data);
                $("[data-dismiss=modal]").trigger({type: "click"});
                get_users_page();
            });
        }

        if (event.target.id == 'users_add_user') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_add_user_dialog.php";
                            var userid = $('#current_user').val();
                            $.post(url, {userid: userid}).done(function (data) {
                                console.log(data);
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

        if (event.target.id == 'add_new_user') {
            var firstname = $('#user_firstname').val();
            var lastname = $('#user_lastname').val();
            var email = $('#user_email').val();
            var pwd = $('#user_pwd').val();
            var practiceid = $('#practices_list').val();

            var user = {firstname: firstname,
                lastname: lastname,
                email: email,
                pwd: pwd,
                practiceid: practiceid};
            console.log('User object: ' + JSON.stringify(user));

            if (firstname != '' && lastname != '' && email != '' && pwd != '' && practiceid > 0) {
                $('#user_err').html('');
                var check_url = "http://" + domain + "/lms/custom/common/is_email_exists.php";
                $.post(check_url, {email: email}).done(function (data) {
                    if (data == 0) {
                        $('#user_err').html('')
                        var url = "http://" + domain + "/lms/custom/common/add_new_user.php";
                        $.post(url, {user: JSON.stringify(user)}).done(function (data) {
                            console.log(data);
                            $("[data-dismiss=modal]").trigger({type: "click"});
                            get_users_page();
                        });
                    } // end if data == 0
                    else {
                        $('#user_err').html('Email already in use');
                    }
                });
            } // end if firstname!=''
            else {
                $('#user_err').html('Please provide all required fields');
            } // end else
        }


        if (event.target.id.indexOf("p_upload_") >= 0) {
            var courseid = event.target.id.replace("p_upload_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_upload_policy_dialog.php";
                            $.post(url, {courseid: courseid}).done(function (data) {
                                console.log(data);
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

        if (event.target.id == 'add_policy_button') {
            var courseid = $('#policy_course').val();
            var title = $('#policy_title').val();
            var file_data = $('#policy_file').prop('files');
            var url = '/lms/custom/common/add_policy.php';
            if (title != '' && file_data != '') {
                $('#policy_err').html('');
                var form_data = new FormData();
                $.each(file_data, function (key, value) {
                    form_data.append(key, value);
                });
                form_data.append('courseid', courseid);
                form_data.append('title', title);
                $.ajax({
                    url: url,
                    data: form_data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (data) {
                        console.log(data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        get_policy_page();
                    }
                });
            } // end if 
            else {
                $('#policy_err').html('Please provide all required fields');
            } // end else
        }

        if (event.target.id.indexOf("update_external_course_") >= 0) {
            var courseid = event.target.id.replace("update_external_course_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_update_external_training_dialog.php";
                            $.post(url, {courseid: courseid}).done(function (data) {
                                console.log(data);
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

        if (event.target.id == 'update_external_course_status') {
            var courseid = $('#external_training_courseid_to_update').val();
            var duration = $('#ext_course_status').val();
            //console.log('Course ID: ' + courseid);
            //console.log('Course Status: ' + duration);
            var url = "http://" + domain + "/lms/custom/common/update_external_status.php";
            course = {courseid: courseid, duration: duration};
            $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                console.log(data);
                $("[data-dismiss=modal]").trigger({type: "click"});
                get_external_courses_page();
            });

        }

        if ($(event.target).attr('class') == 'courses_list') {

        }



        if (event.target.id == 'progress_stat') {
            var userid = $('#progress_stat').data('userid');
            var courses = $('#progress_stat').data('courses');
            console.log('User ID: ' + userid);
            console.log('Courses: ' + courses);

            var course = {userid: userid, courses: courses};
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_course_stat_dialog.php";
                            $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $('#courses_list').DataTable();
                            }); // end of post
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

        if (event.target.id == 'completed_stat') {
            var userid = $('#completed_stat').data('userid');
            var courses = $('#completed_stat').data('courses');
            console.log('User ID: ' + userid);
            console.log('Courses: ' + courses);

            var course = {userid: userid, courses: courses};
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_course_stat_dialog.php";
                            $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $('#courses_list').DataTable();
                            }); // end of post
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

        if (event.target.id == 'overdue_stat') {
            var userid = $('#overdue_stat').data('userid');
            var courses = $('#overdue_stat').data('courses');

            var course = {userid: userid, courses: courses};
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "http://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "http://" + domain + "/lms/custom/common/get_course_stat_dialog.php";
                            $.post(url, {course: JSON.stringify(course)}).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $('#courses_list').DataTable();
                            }); // end of post
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

        if (event.target.id == 'training_report') {
            $('#training_report_form').submit();
        }

        if (event.target.id == 'user_certificates') {
            $('#user_certificates_form0').submit();
        }

        if (event.target.id.indexOf("data_user_report_certificates_") >= 0) {
            var data = event.target.id.replace("data_user_report_certificates_", "");
            var data_arr = data.split('/');
            var courseid = data_arr[0];
            var userid = data_arr[1];
            var formid = '#data_user_certificates_report_form_' + courseid + "_" + userid;
            console.log('Form ID: ' + formid);
            $(formid).submit();
        }

        if (event.target.id == 'training_certificates') {
            var url = "/lms/custom/admin/get_user_certificates.php";
            $.post(url, {id: id}).done(function (data) {
                window.open(data, "print");
            });
        }

        if (event.target.id == 'report_search_ad') {
            var cohort = $('#r_cohort').val();
            var practice = $('#r_practice').val();
            var course = $('#r_courses').val();
            var user = $('#r_users').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();

            if (cohort == '' && practice == '' && course == '' && user == '' && (date1 != '' || date2 != '')) {
                return;
            }

            var search = {
                cohort: cohort,
                practice: practice,
                course: course,
                user: user,
                date1: date1,
                date2: date2,
                src: 'ad'};
            $('#report_ajax_loader').show();
            var url = "/lms/custom/common/search_report_data.php";
            $.post(url, {search: JSON.stringify(search)}).done(function (data) {
                $('#report_ajax_loader').hide();
                $('#report_data').html(data);
                $('#user_detailes_container').DataTable();
            });
        }

        if (event.target.id == 'report_search_gp') {
            var course = $('#r_courses_g').val();
            var user = $('#r_users_g').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();

            if (course == '' && user == '' && (date1 != '' || date2 != '')) {
                return;
            }

            var search = {
                course: course,
                user: user,
                date1: date1,
                date2: date2,
                src: 'gp'};
            $('#report_ajax_loader').show();
            var url = "/lms/custom/common/search_report_data.php";
            $.post(url, {search: JSON.stringify(search)}).done(function (data) {
                $('#report_ajax_loader').hide();
                $('#report_data').html(data);
                $('#user_detailes_container').DataTable();
            });
        }

        if (event.target.id.indexOf("get_user_training_report_") >= 0) {
            var userid = event.target.id.replace("get_user_training_report_", "");
            console.log('User id: ' + userid);
            var formid = '#report_form_' + userid;
            console.log('Form id: ' + formid);
            $(formid).submit();
        }

    }); // end of $('body').click(function (event) {


    $('body').change(function (event) {
        console.log('Event ID: ' + event.target.id);

        if (event.target.id == 'my_courses_year_selection_box') {
            var year = $('#' + event.target.id).val();
            var userid = $('#userid').val();
            console.log('Year selected: ' + year);
            var url = "http://" + domain + "/lms/custom/common/get_my_course_by_year.php";
            $.post(url, {year: year, userid: userid}).done(function (data) {
                $('#my_courses_container').html(data);
                $('#my_courses_wrapper').html(data);
                $('#my_courses').DataTable();
            });

        }

        if (event.target.id == 'gptypes') {
            var catid = $('#gptypes').val();
            if (catid > 0) {
                var url = "http://" + domain + "/lms/custom/common/get_course_by_category.php";
                $.post(url, {catid: catid}).done(function (data) {
                    $('#courses_container').html(data);
                });
            }
        }

        if (event.target.id.indexOf("course_duration_") >= 0) {
            var courseid = event.target.id.replace("course_duration_", "");
            var duration = $('#' + event.target.id).val();
            if (confirm('Change duration for current course?')) {
                var url = "http://" + domain + "/lms/custom/common/update_course_duration.php";
                $.post(url, {courseid: courseid, duration: duration}).done(function () {
                    console.log('Done ...');
                });
            }
        }

        if (event.target.id.indexOf("practice2_") >= 0) {
            var courseid = event.target.id.replace("practice2_", "");
            var practiceid = $('#repeat_practice_id').val();
            var duration = $('#' + event.target.id).val();
            if (confirm('Change duration for current course?')) {
                var url = "http://" + domain + "/lms/custom/common/update_practice_course_duration.php";
                var course = {courseid: courseid, duration: duration, practiceid: practiceid};
                $.post(url, {course: JSON.stringify(course)}).done(function () {
                    console.log('Done ...');
                });
            }
        }

        if (event.target.id == 'r_users_g') {
            $('#r_courses_g').val('All Courses');
            $('#date1').val('');
            $('#date2').val('');
        }



    }); // end of  $('body').change(function (event) {

    $("#r_users").focus(function () {
        $('#r_cohort').val('');
        $('#r_practice').val('');
        $('#r_courses').val('');
        $('#date1').val('');
        $('#date2').val('');
    });

    $('#r_courses_g').val('All Courses');
    $('#r_users_g').val('All users');

}); // end of document ready