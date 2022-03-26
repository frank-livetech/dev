<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

let status_table_list = '';
let type_table_list = '';
let customer_type_table_list = '';
let dispatch_status_table_list = '';
let project_type_table_list = '';
let priority_table_list = '';
let mail_table_list = '';
let g_mails_arr = null;
let g_depts_arr = null;
let g_types_arr = null;
let st_types = null;
let sr_proirity_arr = null;
let g_priority_arr = null;
let g_status_arr = null;
let response_temp_arr = [];

$(document).ready(function() {
    get_mails_table_list();
 // save sla 
    $("#sla_form").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);

        if ($("#customRadio1").is(":checked")) {
            form_data.append("sla_status", 1);
        } else {
            form_data.append("sla_status", 0);
        }

        $.ajax({
            url: action,
            type: method,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#save-SLA-plan").modal('hide');
                    $("#sla_form")[0].reset();
                    getAllSLA();
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {
                console.log(e)
            }

        });

    });

    // update sla
    $("#edit_sla_form").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);

        if ($("#edit_customRadio1").is(":checked")) {
            form_data.append("sla_status", 1);
        } else {
            form_data.append("sla_status", 0);
        }
        if ($("#edit_custumCheck").is(":checked")) {
            form_data.append("is_default", 1);
        } else {
            form_data.append("is_default", 0);
        }

        // if($('#reply_deadline').val() > 12) {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'error',
        //         title: 'Deadline cannnot be greater than 12 hours',
        //         showConfirmButton: false,
        //         timer: swal_message_time
        //     });
        //     return false;
        // }
        // if($('#due_deadline').val() > 12) {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'error',
        //         title: 'Deadline cannnot be greater than 12 hours',
        //         showConfirmButton: false,
        //         timer: swal_message_time
        //     });
        //     return false;
        // }

        $.ajax({
            url: action,
            type: method,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#edit-SLA-plan").modal('hide');
                    getAllSLA();
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {
                console.log(e)
            }

        });

    });

      // save sla setting
      $("#sla_setting_form").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);

        if ($("#flexRadioDefault12").is(':checked')) {
            form_data.append("reply_due_deadline", 1);
        } else if ($("#flexRadioDefault22").is(':checked')) {
            form_data.append("reply_due_deadline", 0);
        }

        if ($("#flexRadioDefault13").is(':checked')) {
            form_data.append("default_reply_and_resolution_deadline", 1);
        } else if ($("#flexRadioDefault23").is(':checked')) {
            form_data.append("default_reply_and_resolution_deadline", 0);
        }

        if ($("#flexRadioDefault14").is(':checked')) {
            form_data.append("reply_due_deadline_when_adding_ticket_note", 1);
        } else if ($("#flexRadioDefault24").is(':checked')) {
            form_data.append("reply_due_deadline_when_adding_ticket_note", 0);
        }

        $.ajax({
            url: action,
            type: method,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {
                console.log(e)
            }

        });

    });

    var today = new Date();
    var dt_option = ``;
    var tm_option = ``;

    var arr = [
        { frmt: "DD/MM/YYYY", date: moment(today).format('DD/MM/YYYY') },
        { frmt: "DD-MM-YYYY", date: moment(today).format('DD-MM-YYYY') },
        { frmt: "DD/MM/YY", date: moment(today).format('DD/MM/YY') },
        { frmt: "D MMMM YYYY", date: moment(today).format('D MMMM YYYY') },
        { frmt: "DD.MM.YYYY", date: moment(today).format('DD.MM.YYYY') },
        { frmt: "MM/DD/YYYY", date: moment(today).format('MM/DD/YYYY') },
    ]

    var time_arr = [
        { frmt: "hh:mm:ss", tm: moment(today).format('hh:mm:ss') },
        { frmt: "hh:mm:ss AM/PM", tm: moment(today).format('hh:mm:ss A') },
        { frmt: "hh:mm", tm: moment(today).format('hh:mm') },
        { frmt: "hh:mm AM/PM", tm: moment(today).format('hh:mm A') },
        { frmt: "LT", tm: moment(today).format('LT') },
    ]

    for (var i = 0; i < arr.length; i++) {
        dt_option += `<option value="${arr[i].frmt}" ${arr[i].frmt == datetime.date ? "selected" : '-'}> ` + arr[i].frmt + ` ` + " e.g. " + `    ` + arr[i].date + `</option>`;
    }

    for (var i = 0; i < time_arr.length; i++) {
        tm_option += `<option value="${time_arr[i].frmt}" ${time_arr[i].frmt == datetime.time ? 'selected' : ''}>` + time_arr[i].frmt + ` ` + " e.g. " + `` + time_arr[i].tm + `</option>`;
    }

    $("#sys_dt_frmt").html(dt_option);
    $("#sys_time_frmt").html(tm_option);



    getAllresTemp();
    showresponseTemp();
   
    $("#edit_temp_response").hide();

    $("#email_recap_notification_form").submit(function(event) {
        event.preventDefault();

        var formData = {};
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var check_off_email = [];
        var tag_emails = $("#tag_emails").val();



        if ($("#recapNoti2").is(':checked')) {
            formData['email_recap_notifications'] = 'no';
        }

        if ($("#recapNoti1").is(':checked')) {

            formData['email_recap_notifications'] = 'yes';
            formData['emails'] = tag_emails;

            if ($("#dailyDetails").is(':checked')) {
                check_off_email.push('daily');
            }

            if ($("#weeklyDetails").is(':checked')) {
                check_off_email.push('weekly');
            }

            if ($("#monthlyDetails").is(':checked')) {
                check_off_email.push('monthly');
            }

            if ($("#yearlyDetails").is(':checked')) {
                check_off_email.push('yearly');
            }
            formData['check_off_emails'] = check_off_email.join(",");
        }

        $.ajax({
            type: method,
            url: action,
            data: { data: formData },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(data) {
                console.log(data);
            }
        });

    });

    $("#php_mailer").on('change', function() {

        if ($(this).is(':checked')) {

            $("#mail_queue_address").attr('disabled', true);
            $("#queue_type").attr('disabled', true);

            $("#protocol").attr('disabled', true);
            $("#queue_template").attr('disabled', true);

            $("#is_enabled").attr('disabled', true);

            $("#mail_dept_id").attr('disabled', true);
            $("#mail_type_id").attr('disabled', true);

            $("#mail_status_id").attr('disabled', true);
            $("#mail_priority_id").attr('disabled', true);

            $("#registration_required").attr('disabled', true);
            $("#autosend_ticket").attr('disabled', true);


        } else {

            $("#mail_queue_address").attr('disabled', false);
            $("#queue_type").attr('disabled', false);

            $("#protocol").attr('disabled', false);
            $("#queue_template").attr('disabled', false);

            $("#is_enabled").attr('disabled', false);

            $("#mail_dept_id").attr('disabled', false);
            $("#mail_type_id").attr('disabled', false);

            $("#mail_status_id").attr('disabled', false);
            $("#mail_priority_id").attr('disabled', false);

            $("#registration_required").attr('disabled', false);
            $("#autosend_ticket").attr('disabled', false);
        }
    });

    $("#edit_php_mailer").on('change', function() {

        if ($(this).is(':checked')) {

            $("#edit_email_emp").attr('disabled', true);
            $("#edit_queue_type").attr('disabled', true);

            $("#edit_protocol").attr('disabled', true);
            $("#edit_queue_template").attr('disabled', true);

            $("#edit_is_enabled").attr('disabled', true);

            $("#edit_mail_dept_id").attr('disabled', true);
            $("#edit_mail_type_id").attr('disabled', true);

            $("#edit_mail_status_id").attr('disabled', true);
            $("#edit_mail_priority_id").attr('disabled', true);

            $("#edit_reg").attr('disabled', true);
            $("#edit_autosend_ticket").attr('disabled', true);


        } else {

            $("#edit_email_emp").attr('disabled', false);
            $("#edit_queue_type").attr('disabled', false);

            $("#edit_protocol").attr('disabled', false);
            $("#edit_queue_template").attr('disabled', false);

            $("#edit_is_enabled").attr('disabled', false);

            $("#edit_mail_dept_id").attr('disabled', false);
            $("#edit_mail_type_id").attr('disabled', false);

            $("#edit_mail_status_id").attr('disabled', false);
            $("#edit_mail_priority_id").attr('disabled', false);

            $("#edit_reg").attr('disabled', false);
            $("#edit_autosend_ticket").attr('disabled', false);
        }
    });

    $("#is_enabled").on('change', function() {
        if ($(this).is(':checked')) {
            $("#is_enabled_text").html("<span class='text-success'>enabled</span>");
        } else {
            $("#is_enabled_text").html("<span class='text-danger'>disabled</span>");
        }
    });

    $("#edit_is_enabled").on('change', function() {
        if ($(this).is(':checked')) {
            $("#edit_is_enabled_text").html("<span class='text-success'>enabled</span>");
        } else {
            $("#edit_is_enabled_text").html("<span class='text-danger'>disabled</span>");
        }
    });

    

    getAllSLA();
    get_departments_table_list();
    get_status_table_list();
    get_type_table_list();
    get_customer_type_table_list();
    get_dispatch_status_table_list();
    get_project_type_table_list();
    get_priority_table_list();
    get_mails_table_list();
    
    $("#dept_is_enabled").bootstrapSwitch();
    //Response Template


    $("#save_temp_response").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);

        var content = tinymce.get("mymce").getContent();

        if ($("#onlyMe").is(':checked')) {
            form_data.append("view_access", 'only_me');
        } else if ($("#allStaff").is(':checked')) {
            form_data.append("view_access", 'all_staff');
        }

        if(content == '<p></p>' || content == '') {
            toastr.error('Response Detail is required', { timeOut: 5000 });
            return false;
        }

        if( $("#title").val() == '') {
            toastr.error('Title is required', { timeOut: 5000 });
            return false;
        }

        form_data.append("temp_html", content);


        $.ajax({
            url: action,
            type: method,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });

                    $("#save_temp_response")[0].reset();
                    showresponseTemp();

                    $("#res_id").val(" ");

                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {
                console.log(e)
            }

        });

    });

    function showresponseTemp() {
        $.ajax({
            type: "GET",
            url: show_response_template,
            dataType: 'json',
            beforeSend: function(data) {
                $(".loader_container").show();
            },
            success: function(data) {
                var obj = data.data;
                response_temp_arr = obj;
                var html = "";
                console.log(data, "Category data 2");
                console.log(obj.length, 'obj');

                for (var i = 0; i < obj.length; i++) {

                    if( obj[i].created_by != "{{auth()->id()}}" && obj[i].view_access == 'all_staff') {

                        html += `<div class="d-flex justify-content-between mt-3 border-bottom" id="resTemp_${obj[i].id}">
                                    <div><h5>${obj[i].title}</h5></div>
                                    <div>
                                        <a type="button" onclick="updateresponseTemp(${obj[i].id})" class="text-dark px-1"> <i class="fas fa-pencil-alt"></i> </a>
                                        <a type="button" onclick="deleteResponseTemp(${obj[i].id})"  class="text-dark"> <i class="fas fa-trash"></i> </a>
                                    </div>
                                </div>`;
                    }

                    if( obj[i].created_by == "{{auth()->id()}}" ) {
                        html += `<div class="d-flex justify-content-between mt-3 border-bottom" id="resTemp_${obj[i].id}">
                                    <div><h5>${obj[i].title} </h5></div>
                                    <div>
                                        <a type="button" onclick="updateresponseTemp(${obj[i].id})" class="text-dark px-1"> <i class="fas fa-pencil-alt"></i> </a>
                                        <a type="button" onclick="deleteResponseTemp(${obj[i].id})"  class="text-dark"> <i class="fas fa-trash"></i> </a>
                                    </div>
                                </div>`;
                    }
                }
                $("#alltempResponse").html(html);

            },
            complete: function(data) {
                $(".loader_container").hide();
            },
            error: function(e) {
                console.log(e);
            }
        });
    }

    // search template
    $("#search_res_template").on('keyup', function() {
        let value = $(this).val().toLowerCase();

        var filtertext = $.grep(response_temp_arr , function(object) {
            let re_title = object.title.toLowerCase();
            console.log(re_title);
            if(re_title != null) {
                return re_title.includes(value);
            }
        });

        let html = ``;
        if(filtertext.length > 0) {
            for (var i = 0; i < filtertext.length; i++) {
                html += `<div class="d-flex justify-content-between mt-3 border-bottom" id="resTemp_${filtertext[i].id}">
                    <div><h5>${filtertext[i].title}</h5></div>
                    <div>
                        <a type="button" onclick="updateresponseTemp(${filtertext[i].id})" class="text-dark px-1"> <i class="fas fa-pencil-alt"></i> </a>
                        <a type="button" onclick="deleteResponseTemp(${filtertext[i].id})"  class="text-dark"> <i class="fas fa-trash"></i> </a>
                    </div>
                </div>`;
            }
        }else{
            html += `<div class="d-flex justify-content-between mt-3 border-bottom" id="resTemp_">
                    No response template found
                </div>`;
        }

        $("#alltempResponse").html(html);
    });



    //// Response Category Template

    $("#cat_form").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);


        $.ajax({
            url: action,
            type: method,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#cat_form').find('.btn').attr('disabled', true);
            },
            success: function(data) {
                $('#cat_form').find('.btn').attr('disabled', false);
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#save-category-modal").modal('hide');
                    $("#cat_form")[0].reset();
                    getAllresTemp();
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function() {
                $('#cat_form').find('.btn').attr('disabled', false);
            },
            error: function(e) {
                $('#cat_form').find('.btn').attr('disabled', false);
                console.log(e)
            }
        });

    });

    // update Response Category Template
    $("#edit_cat_form").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);
        $.ajax({
            url: action,
            type: method,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#edit-category-modal").modal('hide');
                    getAllresTemp();
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {
                console.log(e)
            }

        });

    });


   



  

    // save sla setting
    $("#customer_setting_form").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);

        if ($("#delete").is(':checked')) {
            form_data.append("customer_delete", 1);
        } else {
            form_data.append("customer_delete", 0);
        }

        if ($("#disable").is(':checked')) {
            form_data.append("customer_disable", 1);
        } else {
            form_data.append("customer_disable", 0);
        }

        if ($("#create").is(':checked')) {
            form_data.append("customer_create", 1);
        } else {
            form_data.append("customer_create", 0);
        }

        if ($("#customer_login").is(':checked')) {
            form_data.append("customer_login", 1);
        } else {
            form_data.append("customer_login", 0);
        }

        $.ajax({
            url: action,
            type: method,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {
                console.log(e);
            }

        });

    });
    
    // save billing settings
    $("#save_order_format").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);

        $.ajax({
            type: method,
            url: action,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $(this).find('.btn').attr('disabled', true);
            },
            success: function(data) {
                $(this).find('.btn').attr('disabled', false);
                console.log(data);
                if (data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function() {
                $(this).find('.btn').attr('disabled', false);
            },
            error: function(e) {
                $(this).find('.btn').attr('disabled', false);
                console.log(e);
                toastr.error(data.message, { timeOut: 5000 });
            }
        });
    });
    
    // save payroll settings
    $("#save_payroll_settings").submit(function(event) {
        event.preventDefault();

        let action = $(this).attr('action');
        let method = $(this).attr('method');
        var form_data = new FormData(this);
        if($('#selected_staff_members').val()) {
            form_data.append('selected_staff_members', $('#selected_staff_members').val().toString());
        }

        $.ajax({
            type: method,
            url: action,
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $(this).find('.btn').attr('disabled', true);
            },
            success: function(data) {
                $(this).find('.btn').attr('disabled', false);
                console.log(data);
                if (data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function() {
                $(this).find('.btn').attr('disabled', false);
            },
            error: function(e) {
                $(this).find('.btn').attr('disabled', false);
                console.log(e);
                toastr.error(data.message, { timeOut: 5000 });
            }
        });
    });

    departments_table_list = $('#ticket-departments-list').DataTable();
    status_table_list = $('#ticket-status-list').DataTable();
    type_table_list = $('#ticket-type-list').DataTable();
    customer_type_table_list = $("#customer-type-list").DataTable();
    dispatch_status_table_list = $("#dispatch-status-list").DataTable();
    project_type_table_list = $("#project-type-list").DataTable();
    priority_table_list = $('#ticket-priority-list').DataTable();
    mail_table_list = $('#ticket-mails-list').DataTable({
        "paging": false,
    });

    //////////////////////////
    // show hide column 
    ///////////////////////////////
    // department
    $('#department_column').multipleSelect({
        width: 300,
        onClick: function(view) {
            var selectedItems = $('#department_column').multipleSelect("getSelects");
            for (var i = 0; i < 3; i++) {
                columns = departments_table_list.column(i).visible(0);
            }
            for (var i = 0; i < selectedItems.length; i++) {
                var s = selectedItems[i];
                departments_table_list.column(s).visible(1);
            }

        },
        onCheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = departments_table_list.column(i).visible(1);
            }
        },
        onUncheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = departments_table_list.column(i).visible(0);
            }

        }
    });

    // status
    $('#status_column').multipleSelect({
        width: 300,
        onClick: function(view) {
            var selectedItems = $('#status_column').multipleSelect("getSelects");
            for (var i = 0; i < 3; i++) {
                columns = status_table_list.column(i).visible(0);
            }
            for (var i = 0; i < selectedItems.length; i++) {
                var s = selectedItems[i];
                status_table_list.column(s).visible(1);
            }

        },
        onCheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = status_table_list.column(i).visible(1);
            }
        },
        onUncheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = status_table_list.column(i).visible(0);
            }

        }
    });

    // type
    $('#type_column').multipleSelect({
        width: 300,
        onClick: function(view) {
            var selectedItems = $('#type_column').multipleSelect("getSelects");
            for (var i = 0; i < 3; i++) {
                columns = type_table_list.column(i).visible(0);
            }
            for (var i = 0; i < selectedItems.length; i++) {
                var s = selectedItems[i];
                type_table_list.column(s).visible(1);
            }

        },
        onCheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = type_table_list.column(i).visible(1);
            }
        },
        onUncheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = type_table_list.column(i).visible(0);
            }

        }
    });

    // priority
    $('#priority_column').multipleSelect({
        width: 300,
        onClick: function(view) {
            var selectedItems = $('#priority_column').multipleSelect("getSelects");
            for (var i = 0; i < 4; i++) {
                columns = priority_table_list.column(i).visible(0);
            }
            for (var i = 0; i < selectedItems.length; i++) {
                var s = selectedItems[i];
                priority_table_list.column(s).visible(1);
            }

        },
        onCheckAll: function() {
            for (var i = 0; i < 4; i++) {
                columns = priority_table_list.column(i).visible(1);
            }
        },
        onUncheckAll: function() {
            for (var i = 0; i < 4; i++) {
                columns = priority_table_list.column(i).visible(0);
            }

        }
    });

    //////////////////////////
    // show hide column 
    ///////////////////////////////


    $('a.toggle-vis').on('click', function(e) {
        e.preventDefault();

        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-secondary');

        if ($(this).parent().parent().find('table').attr('id') == 'ticket-departments-list') {
            var column = departments_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }

        if ($(this).parent().parent().find('table').attr('id') == 'ticket-status-list') {
            var column = status_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }

        if ($(this).parent().parent().find('table').attr('id') == 'ticket-type-list') {
            var column = type_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }

        if ($(this).parent().parent().find('table').attr('id') == 'ticket-priority-list') {
            var column = priority_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }

        if ($(this).parent().parent().find('table').attr('id') == 'ticket-mails-list') {
            var column = mail_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }
    });

    // get_departments_table_list();
    // get_status_table_list();
    // get_type_table_list();
    // get_customer_type_table_list();
    // get_dispatch_status_table_list();
    // get_project_type_table_list();
    // get_priority_table_list();
    // get_mails_table_list();

    // const textDark = Pickr.create({
    //     el: '#text-dark',
    //     theme: 'nano',
    //     default: "{{session('text_dark')}}",
    //     components: {

    //         // Main components
    //         preview: true,
    //         opacity: true,
    //         hue: true,

    //         // Input / output Options
    //         interaction: {
    //             save: true
    //         }
    //     }
    // });

    // textDark.on('change', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     dark(newColor, "{{Session::get('bg_dark')}}");
    // }).on('save', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     $.ajax({
    //         type: "post",
    //         url: save_colors_route,
    //         data: { action: 'textDark', color: newColor },
    //         success: function(data) {
    //             if (data['success'] == true) {
    //                 dark(newColor, "{{Session::get('bg_dark')}}");
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'success',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             } else {
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'error',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             }
    //         }
    //     });
    // });
    // const bgDark = Pickr.create({
    //     el: '#bg-dark',
    //     theme: 'nano',
    //     default: "{{session('bg_dark')}}",
    //     components: {

    //         // Main components
    //         preview: true,
    //         opacity: true,
    //         hue: true,

    //         // Input / output Options
    //         interaction: {
    //             save: true
    //         }
    //     }
    // });

    // bgDark.on('change', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     dark("{{Session::get('text_dark')}}", newColor)
    // }).on('save', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     $.ajax({
    //         type: "post",
    //         url: save_colors_route,
    //         data: { action: 'bgDark', color: newColor },
    //         success: function(data) {
    //             if (data['success'] == true) {
    //                 dark("{{Session::get('text_dark')}}", newColor)
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'success',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             } else {
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'error',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             }
    //         }
    //     });
    // });
    // const textLight = Pickr.create({
    //     el: '#text-light',
    //     theme: 'nano',
    //     default: "{{session('text_light')}}",
    //     components: {

    //         // Main components
    //         preview: true,
    //         opacity: true,
    //         hue: true,

    //         // Input / output Options
    //         interaction: {
    //             save: true
    //         }
    //     }
    // });

    // textLight.on('change', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     light(newColor, "{{Session::get('text_light')}}")
    // }).on('save', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     $.ajax({
    //         type: "post",
    //         url: save_colors_route,
    //         data: { action: 'textLight', color: newColor },
    //         success: function(data) {
    //             if (data['success'] == true) {
    //                 light(newColor, "{{Session::get('text_light')}}")
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'success',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             } else {
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'error',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             }
    //         }
    //     });
    // });
    // const bglight = Pickr.create({
    //     el: '#bg-light',
    //     theme: 'nano',
    //     default: "{{session('bg_light')}}",
    //     components: {

    //         // Main components
    //         preview: true,
    //         opacity: true,
    //         hue: true,

    //         // Input / output Options
    //         interaction: {
    //             save: true
    //         }
    //     }
    // });

    // bglight.on('change', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     light("{{Session::get('text_light')}}", newColor)
    // }).on('save', function(color) {
    //     const newColor = color.toHEXA().toString();
    //     $.ajax({
    //         type: "post",
    //         url: save_colors_route,
    //         data: { action: 'bgLight', color: newColor },
    //         success: function(data) {
    //             if (data['success'] == true) {
    //                 light("{{Session::get('text_light')}}", newColor)
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'success',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             } else {
    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'error',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: swal_message_time
    //                 })
    //             }
    //         }
    //     });
    // });

    // radioswitch.init();

});

function updateresponseTemp(id) {

    let item = response_temp_arr.find(item => item.id === id);
    if(item != null) {
        $("#res_id").val(id);
        $(".res_title").val(item.title);
        $("#cat_id").val(item.cat_id).trigger('change');

        tinymce.activeEditor.setContent(item.temp_html);

        if(item.view_access != null) {

            if(item.view_access == "all_staff") {
                $("#allStaff").prop("checked" , true);
            }else{
                $("#allStaff").prop("checked" , false);
            }

            if(item.view_access == "only_me") {
                $("#onlyMe").prop("checked" , true);
            }else{
                $("#onlyMe").prop("checked" , false);
            }
        }else{
            $("#onlyMe").prop("checked" , false);
            $("#allStaff").prop("checked" , false);
        }


    }
};

// delete response template
function deleteResponseTemp(id) {
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: "{{url('delete_response_template')}}",
                data: {id:id},
                dataType: 'json',
                success: function(data) {
                    
                    if (data.status_code == 200 && data.success == true) {
                        toastr.success(data.message, { timeOut: 5000 });
                        $("#resTemp_"+id).remove();
                        $("#res_id").val("");
                        $("#save_temp_response").trigger("reset");

                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    });
   
}

function sendOnDemandRecap() {

    var recap_dropdown = $("#recap_dropdown").val();
    var recap_emails = $("#recap_emails").val();
    var formdata = {
        recap: recap_dropdown,
        emails: recap_emails,
        today: moment(new Date()).format('YYYY-MM-DD'),
    }

    $.ajax({
        type: 'POST',
        url: send_recap_mails,
        data: formdata,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        error: function(data) {
            console.log(data);
        }
    });


}

function getAllSLA() {
    $.ajax({
        type: "GET",
        url: get_all_sla,
        dataType: 'json',
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            var obj = data.data;
            console.log(data, "sla data");

            $("#sla_table").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#sla_table").DataTable({
                data: obj,
                pageLength: 25,
                bInfo: true,
                paging: true,
                columns: [{
                        data: null,
                        defaultContent: ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            let active = `` + full.title + `<small class="badge-pill bg-primary text-white ml-3">Default</small>`;
                            let deactive = full.title;
                            return full.is_default == 1 ? active : deactive;

                        },

                    },
                    {
                        "render": function(data, type, full, meta) {
                            let active = `<span class="badge bg-success text-white">Active</span>`;
                            let deactive = `<span class="badge bg-danger text-white">Deactive</span>`;
                            return full.sla_status == 1 ? active : deactive;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return (
                                `<div class="d-flex justify-content-center">
                                    <button onclick="viewRecord(` + full.id + `, '` + full.title + `',` + full.reply_deadline + `,` + full.due_deadline + `,` + full.sla_status + `,` + full.is_default + `)" 
                                    type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button  onclick="deleteRecord(` + full.id + `)" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`
                            );
                        }
                    }
                ]
            });

            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e);
        }
    });
}

function get_mails_table_list() {
    
    // mail_table_list.clear().draw();
    $.ajax({
        type: "GET",
        url: mails_route,
        dataType: 'json',
        beforeSend: function(data) {
            $("#emailtableloader").show();
        },
        success: function(data) {

            if(data.status_code == 200 && data.success == true) {
                var obj = data.mails;

                $("#ticket-mails-list").DataTable().destroy();
                $.fn.dataTable.ext.errMode = "none";
                var tbl = $("#ticket-mails-list").DataTable({
                    data: obj,
                    columns: [{
                            data: null,
                            defaultContent: ""
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.from_mail != null ? full.from_mail : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if (full.mail_type != null && full.mail_type != '') {
                                    return full.mail_type.name != null ? full.mail_type.name : '-'
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if (full.department != null && full.department != '') {
                                    return full.department.name != null ? full.department.name : '-'
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.registration_required != null ? full.registration_required : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.is_default != null ? full.is_default : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<div class="d-flex justify-content-center">
                                        <button onclick="getEmailByID(${full.id})"  class="mx-1 btn btn-icon rounded-circle btn-outline-warning waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" onclick="deleteMail(${full.id})" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>`;
                            }
                        }
                    ]
                });

                tbl.on("order.dt search.dt", function() {
                    tbl.column(0, {
                            search: "applied",
                            order: "applied"
                        })
                        .nodes()
                        .each(function(cell, i) {
                            cell.innerHTML = i + 1;
                        });
                }).draw();
            }
        },
        complete: function(data) {
            $("#emailtableloader").hide();
        },
        error: function(e) {
            console.log(e);
        }
    });
}

function viewRecord(id, title, reply_deadline, due_deadline, status, is_default) {

    $("#edit-SLA-plan").modal('show');

    $("#reply_title").val(title);
    $("#sla_id").val(id);
    $("#reply_deadline").val(reply_deadline);
    $("#due_deadline").val(due_deadline);

    if (status == 1) {
        $("#edit_customRadio1").prop("checked", true);
    } else {
        $("#edit_customRadio2").prop("checked", true);
        $("#edit_customRadio1").prop("checked", false);
    }
    if (is_default == 1) {
        $("#edit_custumCheck").prop("checked", true);
    } else {

        $("#edit_custumCheck").prop("checked", false);
    }
}

function viewCatRecord(id, name) {
    // alert(id);
    $("#edit-category-modal").modal('show');
    $("#cat_id2").val(id);
    $("#cat_name2").val(name);
}

function getAllresTemp() {
    $.ajax({
        type: "GET",
        url: get_all_resTemplate,
        dataType: 'json',
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            var obj = data.data;
            console.log(data, "Category data");
            var option = ``;
            for(var i =0 ; i< obj.length; i++) {
                option += `<option value="`+obj[i].id+`">`+obj[i].name+`</option>`;
            }   

            $('#cat_id').html(option);

            $("#temp_cat_table").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#temp_cat_table").DataTable({
                data: obj,
                pageLength: 25,
                bInfo: true,
                paging: true,
                columns: [{
                        data: null,
                        defaultContent: ""
                    },
                    {
                        "data": "id"

                    },
                    {
                        "data": "name"

                    },

                    {
                        "render": function(data, type, full, meta) {
                            return `<div class="d-flex justify-content-center">
                                    <button onclick="viewCatRecord(` + full.id + `, '` + full.name + `')" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;" title="Edit Department">
                                        <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
                                        &nbsp;
                                        <button type="button" onclick="deleteCatRecord(` + full.id + `)" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                </div>`;
                        }
                    }
                ]
            });

            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e);
        }
    });
}

function deleteCatRecord(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: delete_cat,
                data: { id: id },
                success: function(data) {
                    
                    if (data.status_code == 200 && data.success == true) {
                        toastr.success(data.message, { timeOut: 5000 });
                        getAllresTemp();
                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
    });
}

function deleteRecord(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: delete_sla,
                data: { id: id },
                success: function(data) {
                    if (data.status_code == 200 && data.success == true) {
                        Swal.fire("Success", data.message, "success");
                        getAllSLA();
                    } else {
                        Swal.fire("Cancelled!", data.message, "error");
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
    });
}

$("#brand_settings").submit(function(event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data['success'] == true) {
                // $("#brand_settings").trigger("reset");
                // console.log($('#site_footer').val());
                $('#footer').text('');
                $('#footer').text($('#site_footer').val());
                $imglogo = $('#site_logo_preview').attr('src');
                $('#logo_image').attr('src', $imglogo);
                $('#logo_title').text($('#site_logo_title').val())

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });
});

function saveSystemDateAndTime() {
    var sys_dt_frmt = $("#sys_dt_frmt").val();
    var sys_tm_frmt = $("#sys_time_frmt").val();
    var timezone = $("#timezone").val();

    $.ajax({
        type: "POST",
        url: save_sys_date_time,
        data: {
            sys_dt_frmt: sys_dt_frmt,
            sys_tm_frmt: sys_tm_frmt,
            timezone: timezone,
        },
        beforeSend: function(data) {
            $("#saveBtn").hide();
            $("#processing").show();
        },
        success: function(data) {
            console.log(data);
            if (data.status_code == 200 & data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete: function(data) {
            $("#saveBtn").show();
            $("#processing").hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}


function get_departments_table_list() {

    // departments_table_list.clear().draw();
    $.ajax({
        type: "get",
        url: get_deps_route,
        data: "",
        success: function(data) {

            g_depts_arr = data.departments;
            var dept_arr = data.departments;
            console.log(dept_arr);

            dept_arr.forEach(element => {
                $('#department_id').append('<option value="' + element.id + '">' + element.name + '</option>');
                $('#department_id').trigger('change');

                $('#department_id1').append('<option value="' + element.id + '">' + element.name + '</option>');
                $('#department_id1').trigger('change');

                $('#department_id2').append('<option value="' + element.id + '">' + element.name + '</option>');
                $('#department_id2').trigger('change');

                $('#mail_dept_id').append('<option value="' + element.id + '">' + element.name + '</option>');
                $('#mail_dept_id').trigger('change');

                $('#edit_mail_dept_id').append('<option value="' + element.id + '">' + element.name + '</option>');
                $('#edit_mail_dept_id').trigger('change');
            });

            $("#ticket-departments-list").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#ticket-departments-list").DataTable({
                data: dept_arr,
                "pageLength": 10,
                "bInfo": false,
                "paging": true,
                "searching": true,
                columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.name != null ? `<a href="${dept_details_route}/${full.id}">${full.name}</a>` : '-';
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                            <div class="d-flex justify-content-center">
                                <button onclick="editDepartment(` + full.id + `)" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;" title="Edit Department">
                                    <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
                                    &nbsp;
                                    <button type="button" onclick="deleteDepartment(` + full.id + `)" onclick="deleteStatus(42)" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                            </div>`;
                        }
                    },
                ],
            });
            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();

        }
    });
}

function get_status_table_list() {
    // status_table_list.clear().draw();
    $.ajax({
        type: "get",
        url: get_status_route,
        data: "",
        success: function(data) {
            g_status_arr = data.statuses;
            var status_arr = data.statuses;

            console.log(data, "ticket status");

            $("#ticket-status-list").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#ticket-status-list").DataTable({
                data: status_arr,
                pageLength: 25,
                bInfo: true,
                paging: true,
                columns: [{
                        data: null,
                        defaultContent: ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.name != null ? full.name : '--';
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `<span class="fa fa-square" style="color:` + full.color + `" aria-hidden="true"></span>`;
                        }
                    },

                    {
                        "render": function(data, type, full, meta) {
                            return `<div class="d-flex justify-content-center">
                                <button class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;" title="Edit Department" onclick="editStatus(${full.id})">
                                    <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
                                    &nbsp;
                                    <button type="button" onclick="deleteStatus(${full.id})" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                            </div>`;
                        }
                    },
                ]
            });

            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();

            $.each(status_arr, function(key, val) {

                $('#mail_status_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#mail_status_id').trigger('change');

                $('#edit_mail_status_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#edit_mail_status_id').trigger('change');
            });
        }
    });
}

function get_type_table_list() {
    // type_table_list.clear().draw();
  
    $.ajax({
        type: "get",
        url: get_types_route,
        data: "",
        success: function(data) {
            g_types_arr = data.types;
            var types_arr = data.types;
            st_types = data.types;
            console.log(types_arr,"Ticket Types array");


            $("#ticket-type-list").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#ticket-type-list").DataTable({
                data: types_arr,
                "pageLength": 10,
                "bInfo": false,
                "paging": true,
                "searching": true,
                columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.name;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                            <div class="d-flex justify-content-center">
                                <button onclick="editType(${full.id})" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;" title="Edit Department">
                                    <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
                                    &nbsp;
                                    <button type="button" onclick="deleteType(` + full.id + `)" onclick="deleteStatus(42)" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                            </div>`
                        }
                    },
                ],
            });
            
            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
            
            $.each(types_arr, function(key, val) {

            $('#mail_types_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            $('#mail_types_id').trigger('change');

            $('#edit_mail_types_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            $('#edit_mail_types_id').trigger('change');
            });
        }
    });
}

/*Customer Type List */

function get_customer_type_table_list() {
    // customer_type_table_list.clear().draw();
    $.ajax({
        type: "get",
        url: get_customer_types_route,
        data: "",

        success: function(data) {
            g_types_arr = data.types;
            var types_arr = data.types;
            console.log(types_arr,"Customer Types array");


            $("#customer-type-list").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#customer-type-list").DataTable({
                data: types_arr,
                "pageLength": 10,
                "bInfo": false,
                "paging": true,
                "searching": true,
                columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.name;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                                <div class="d-flex justify-content-center">
                                    <button onclick="editCustomerType(${full.id},'${full.name}')" 
                                    type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button onclick="deleteCustomerType(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div`;
                        }
                    },
                ],
            });
            
            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
            
            $.each(types_arr, function(key, val) {

                $('#mail_type_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#mail_type_id').trigger('change');

                $('#edit_mail_type_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#edit_mail_type_id').trigger('change');
            });
        }
    });
}


/* Dispatch Status List */

function get_dispatch_status_table_list() {
    // dispatch_status_table_list.clear().draw();
    $.ajax({
        type: "get",
        url: get_dispatch_status_route,
        data: "",



        success: function(data) {
            g_types_arr = data.types;
            var types_arr = data.types;
            console.log(types_arr,"dispatch Types array");


            $("#dispatch-status-list").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#dispatch-status-list").DataTable({
                data: types_arr,
                "pageLength": 10,
                "bInfo": false,
                "paging": true,
                "searching": true,
                columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.name;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `<div class="d-flex justify-content-center">
                                    <button onclick="editDispatchStatus(${full.id},'${full.name}')" 
                                    type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button  onclick="deleteDispatchStatus(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`
                        }
                    },
                ],
            });
            
            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
            
            $.each(types_arr, function(key, val) {

                $('#mail_type_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#mail_type_id').trigger('change');

                $('#edit_mail_type_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#edit_mail_type_id').trigger('change');
            });
        }
    });
}

/* Project Task Type List */

function get_project_type_table_list() {
    // project_type_table_list.clear().draw();
 
    $.ajax({
        type: "get",
        url: get_project_type_route,
        data: "",
        success: function(data) {
            console.log(data);
            g_types_arr = data.types;
            var types_arr = data.types;
            console.log(types_arr, "project type");


            $("#project-type-list").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#project-type-list").DataTable({
                data: types_arr,
                "pageLength": 10,
                "bInfo": false,
                "paging": true,
                "searching": true,
                columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.name;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                                return `<div class="d-flex justify-content-center">
                                    <button onclick="editProjectType(${full.id},'${full.name})" 
                                    type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button  onclick="deleteProjectType(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`
                        }
                    },
                ],
            });
            
            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
            
            $.each(types_arr, function(key, val) {

                $('#mail_type_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#mail_type_id').trigger('change');

                $('#edit_mail_type_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#edit_mail_type_id').trigger('change');
            });
        }
    });
}


/*Customer Type List */
function get_priority_table_list() {
    // priority_table_list.clear().draw();
   
    $.ajax({
        type: "get",
        url: get_priorities_route,
        data: "",
        success: function(data) {
            console.log(data);
            g_priority_arr = data.priorities;
            var priorities_arr = data.priorities;
            sr_proirity_arr = data.priorities;

            $("#ticket-priority-list").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            var tbl = $("#ticket-priority-list").DataTable({
                data: priorities_arr,
                "pageLength": 10,
                "bInfo": false,
                "paging": true,
                "searching": true,
                columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.name;
                        }
                    },
                    {
                        "render": function(data,type, full, meta){
                            let color = '';
                            if (full.priority_color) {
                                color = '<div class="text-center"><span class="fa fa-square" style="color: ' + full.priority_color + '"></span></div>';
                            } else {
                                color = '<div class="text-center"><span class="fa fa-square" style="color:#111"></span></div>';
                            }
                            return color;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                            <div class="d-flex justify-content-center">
                                <button onclick="editPriority(${full.id})" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;" title="Edit Department">
                                    <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
                                    &nbsp;
                                    <button type="button" onclick="deletePriority(` + full.id + `)" onclick="deleteStatus(42)" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                            </div>`;
                        }
                    },
                ],
            });
            
            tbl.on("order.dt search.dt", function() {
                tbl.column(0, {
                        search: "applied",
                        order: "applied"
                    })
                    .nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
            
            $.each(priorities_arr, function(key, val) {

                $('#mail_priority_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#mail_priority_id').trigger('change');

                $('#edit_mail_priority_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                $('#edit_mail_priority_id').trigger('change');
            });
        }


    });
}


$('#nestable-menu').on('click', function(e) {
    var target = $(e.target);
    // var div = target[0].textContent;

    $('.gears').hide();
    $('.dd-handle').removeClass("menu_active");
    $(target[0].getAttribute('data-trg')).show();
    $(target[0].getAttribute('data-cls')).addClass("menu_active");
});


function deleteStatus(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this Status will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_status_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Status Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_status_table_list()
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                    }
                }
            });
        }
    })
}

function deleteType(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this Type will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_type_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Type Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_type_table_list()
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                    }
                }
            });
        }
    })
}

function deleteCustomerType(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this Type will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_customer_type_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Type Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_customer_type_table_list()
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                    }
                }
            });
        }
    })
}

function deleteDispatchStatus(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this Type will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_dispatch_status_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Status Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_dispatch_status_table_list();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                    }
                }
            });
        }
    })
}

function deleteProjectType(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this Type will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_project_type_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Project Type Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_project_type_table_list();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                    }
                }
            });
        }
    })
}

function deletePriority(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this priority will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_priority_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Priority Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_priority_table_list()
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                    }
                }
            });
        }
    })
}

function deleteDepartment(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this department will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_dept_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Department Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_departments_table_list();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                    }
                }
            });
        }
    })
}

function editDepartment(id) {

    let item = g_depts_arr.find(item => item.id === id);
    $("#dept").html('Edit Department');
    if(item != null) {
        $('#dep_name').val(item.name);
        $('#dep_id').val(id);
        $('#dept_slug').val(item.dept_slug);

        if(item.dept_counter == 1){
            $('#dept_counter').prop('checked' , true);
        }else{
            $('#dept_counter').prop('checked' , false);
        }

        $('#save-department').modal('show');
    
    }





}

function editResponseTemp(id, title, cat_id, temp_html, view_access) {
    $('#title').val(title);
    $('#id').val(id);
    $('#cat_id').val(cat_id);
    $('#temp_html').val(temp_html);
    if (view_access == "only_me") {
        $('#onlyMe').attr("checked", "checked");
    } else {
        $('#allStaff').attr("checked", "checked");
    }

    // alert('DOne');


}

function editStatus(id) {

    var item = g_status_arr.find(item => item.id === id);
    console.log(item , "item");
    $("#stat").html('Edit Status');
    if(item != null) {

        $('#status_name').val(item.name);
        $('#status_id').val(id);
        $('#status_color').val(item.color);
        $('#slug').val(item.slug);
        $('#seq_no').val(item.seq_no);

        let depid = item.department_id.split(',');
        $('#department_id2').val(depid).trigger('change');

        item.status_counter == 1 ? 
            $('#status_counter').prop('checked', true) :  
            $('#status_counter').prop('checked', false);
        

    
        $('#save-status').modal('show');

    }
}

function editType(id) {
    $("#typeh2").html('Edit Type');
    var item = st_types.find(type => type.id === id);
    if(item != null) {
        $('#type_name').val(item.name);
        $('#type_id').val(id);

        if(item.department_id != null) {
            let depid = item.department_id.split(',');
            $('#department_id1').val(depid).trigger('change');
        }
        

        $('#save-type').modal('show');
    }
}

function editCustomerType(id, name) {

    $('#customer_type_name').val(name);
    $('#customer_type_id').val(id);
    $('#save-customer-type').modal('show');

        $("#customer_typeh2").html('Edit Customer Type');


}

function editDispatchStatus(id, name) {

    $('#dispatch_status_name').val(name);
    $('#dispatch_status_id').val(id);
    $('#save-dispatch-status').modal('show');
    $("#dispatch_statush2").html('Edit Type');


}

function editProjectType(id, name) {

    $('#project_type_name').val(name);
    $('#project_type_id').val(id);
    $('#save-project-type').modal('show');

        $("#project_typeh2").html('Edit Project Task Type');


}


function editPriority(id) {

    $("#prior").html('Edit Priority');

    var item = sr_proirity_arr.find(item => item.id === id);
    console.log(item );
    console.log(id);
    if(sr_proirity_arr != null) {
        $('#priority_name').val(item.name);
        $('#priority_id').val(id);
        $('#priority_color').val(item.priority_color);

        if(item.department_id != null) {
            let depid = item.department_id.split(',');
            $('#department_id').val(depid).trigger('change');
        }


        $('#save-priority').modal('show');
    }
}

function showDepModel(id, name) {
    $('#dep_name').val(name);
    $('#dep_id').val(id);
     $("#dept").html('New Department');
    $(".form-control").val("");

    $('#save-department').modal('show');


}

function showStatusModel(id, name) {
    $('#status_name').val(name);
    $('#status_id').val(id);
    $("#stat").html('New Status');

    $(".form-control").val("");

    $('#save-status').modal('show');


}

function showTypeModel(id, name) {
    $('#type_name').val(name);
    $('#type_id').val(id);
        $("#typeh2").html('New Type');
   
    $(".form-control").val("");
    // $('.select2-container').select2('val', '');
    $('#save-type').modal('show');
    $('#department_id1').val("").trigger('change');
    $('.select2-container').select2('val', '');



}

function showPriorityModel(id, name) {
    $('#priority_name').val(name);
    $('#priority_id').val(id);
    var label = $("#prior").text();
        $("#prior").html('New Priority');
    $(".form-control").val("");
    // $('.select2-container').select2('val', '');
    $('#save-priority').modal('show');


}

function showCustomerTypeModel(id, name) {
    $('#customer_type_name').val(name);
    $('#customer_type_id').val(id);
    var label = $("#customer_typeh2").text();
        $("#customer_typeh2").html('New Type');
    $(".form-control").val("");
    $('#save-customer-type').modal('show');


}

function showDispatchStatusModel(id, name) {
    $('#dispatch_status_name').val(name);
    $('#dispatch_status_id').val(id);
    var label = $("#dispatch_statush2").text();
        $("#dispatch_statush2").html('New Status');
    $(".form-control").val("");
    $('#save-dispatch-status').modal('show');


}

function showProjectTypeModel(id, name) {
    $('#project_type_name').val(name);
    $('#project_type_id').val(id);
    var label = $("#project_typeh2").text();
        $("#project_typeh2").html('New Project Type');
    $(".form-control").val("");
    $('#save-project-type').modal('show');


}

function showSLAModel() {
    // $('#SLA_name').val(name);
    // $('#project_type_id').val(id);
    // var label = $("#project_typeh2").text();
    // if (label == 'Add Project Type') {
    //     $("#project_typeh2").html('New Project Type');
    // };
    $(".form-control").val("");
    $('#save-SLA-plan').modal('show');


}

function showPop3Model(type, edit = false, id = '') {
    if (edit) {
        $('#save-mail').find('#modalheader').html('Edit Mail');

        let mail = g_mails_arr[g_mails_arr.map(function(item) { return item.id; }).indexOf(id)];
        $('#mail_queue_address').val(mail.mail_queue_address);
        $('#queue_type').val(mail.queue_type);
        $('#protocol').val(mail.protocol);
        $('#queue_template').val(mail.queue_template);
        $('#edit_queue_template').val(mail.queue_template);
        if (mail.is_enabled == 'yes') {
            $('#is_enabled').prop('checked');
        }
        if (mail.autosend == 'yes') {
            $('#autosend_ticket').prop('checked');
        }
        if (mail.outbound == 'yes') {
            $('#email_outbound').prop('checked');
        }
        if (mail.registration_required == 'yes') {
            $('#registration_required').prop('checked');
        }
        $('#mailserver_hostname').val(mail.mailserver_hostname);
        $('#mailserver_port').val(mail.mailserver_port);
        $('#mailserver_username').val(mail.mailserver_username);
        $('#mailserver_password').val(mail.mailserver_password);
        $('#from_name').val(mail.from_name);
        $('#from_mail').val(mail.from_mail);
        $('#mail_dept_id').val(mail.mail_dept_id);
        $('#mail_type_id').val(mail.mail_type_id);
        $('#mail_status_id').val(mail.mail_status_id);
        $('#mail_priority_id').val(mail.mail_priority_id);
    } else {
        $('#save-mail').find('#modalheader').html('Add New Mail');
    }
    $('#save-mail').find('#editId').val(id);
    if (type != 'tickets') {
        $('#tickets_area').css('display', 'none')
    } else {
        $('#tickets_area').css('display', 'flex')
    }
    $('#save-mail').modal('show');
}


   

function getEmailByID(id) {
    $("#email_id").val(id);
    $("#edit_email_modal").modal('show');
    $.ajax({
        type: "POST",
        url: edit_email_by_id,
        data: { id: id },
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            console.log(data, "sd");

            $("#edit_email_emp").val(data.mail_queue_address);
            $("#edit_mailserver_hostname").val(data.mailserver_hostname);
            $("#edit_mailserver_port").val(data.mailserver_port);
            $("#edit_mailserver_username").val(data.mailserver_username);
            $("#edit_mailserver_password").val(data.mailserver_password);
            $("#edit_from_name").val(data.from_name);
            $("#edit_from_mail").val(data.from_mail);

            // dropdown

            $("#edit_queue_type").val(data.queue_type).trigger('change');
            $("#edit_protocol").val(data.protocol).trigger('change');
            $("#edit_queue_template").val(data.queue_template).trigger("change");
            $("#edit_mail_dept_id").val(data.mail_dept_id).trigger("change");
            $("#edit_mail_type_id").val(data.mail_type_id).trigger("change");
            $("#edit_mail_status_id").val(data.mail_status_id).trigger("change");
            $("#edit_mail_priority_id").val(data.mail_priority_id).trigger("change");

            // checkboxes

            if (data.registration_required == "yes") {
                $("#edit_reg").prop("checked", true);
            } else {
                $("#edit_reg").prop("checked", false);
            }

            if (data.is_enabled == "yes") {
                $("#edit_is_enabled").prop("checked", true);
            } else {
                $("#edit_is_enabled").prop("checked", false);
            }


            if (data.autosend == "yes") {
                $("#edit_autosend_ticket").prop("checked", true);
            } else {
                $("#edit_autosend_ticket").prop("checked", false);
            }
            
            if (data.is_default == "yes") {
                $("#edit_is_dept_default").prop("checked", true);
            } else {
                $("#edit_is_dept_default").prop("checked", false);
            }
            
            if(data.outbound == 'yes') $("#edit_outbound_ticket").prop("checked", true);
            else $("#edit_outbound_ticket").prop("checked", false);

            if (data.php_mailer == "yes") {
                $("#edit_php_mailer").prop("checked", true);
                $("#edit_email_emp").attr('disabled', true);
                $("#edit_queue_type").attr('disabled', true);

                $("#edit_protocol").attr('disabled', true);
                $("#edit_queue_template").attr('disabled', true);

                $("#edit_is_enabled").attr('disabled', true);

                $("#edit_mail_dept_id").attr('disabled', true);
                $("#edit_mail_type_id").attr('disabled', true);

                $("#edit_mail_status_id").attr('disabled', true);
                $("#edit_mail_priority_id").attr('disabled', true);

                $("#edit_reg").attr('disabled', true);
                $("#edit_autosend_ticket").attr('disabled', true);

            } else {
                $("#edit_php_mailer").prop("checked", false);

                $("#edit_email_emp").attr('disabled', false);
                $("#edit_queue_type").attr('disabled', false);

                $("#edit_protocol").attr('disabled', false);
                $("#edit_queue_template").attr('disabled', false);

                $("#edit_is_enabled").attr('disabled', false);

                $("#edit_mail_dept_id").attr('disabled', false);
                $("#edit_mail_type_id").attr('disabled', false);

                $("#edit_mail_status_id").attr('disabled', false);
                $("#edit_mail_priority_id").attr('disabled', false);

                $("#edit_reg").attr('disabled', false);
                $("#edit_autosend_ticket").attr('disabled', false);
            }


        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e);
        }
    });
}

function updateEmailQueue() {

    var email_id = $("#email_id").val();
    var mail_queue_address = $('#edit_email_emp').val();
    var queue_type = $('#edit_queue_type').val();
    var protocol = $('#edit_protocol').val();
    var queue_template = $('#edit_queue_template').val();

    var is_enabled = '';
    var registration_required = '';
    var autosend = '';
    var isDefault = '';
    var outbnd = '';
    var form_data = '';

    if ($('#edit_autosend_ticket').prop('checked')) {
        autosend = 'yes';
    } else {
        autosend = 'no';
    }
    
    if ($('#edit_is_dept_default').prop('checked')) {
        isDefault = 'yes';
    } else {
        isDefault = 'no';
    }
    
    if ($('#edit_outbound_ticket').prop('checked')) {
        outbnd = 'yes';
    } else {
        outbnd = 'no';
    }

    if ($('#edit_reg').prop('checked')) {
        registration_required = 'yes';
    } else {
        registration_required = 'no';
    }

    if ($('#edit_is_enabled').prop('checked')) {
        is_enabled = 'yes';
    } else {
        is_enabled = 'no';
    }

    var mailserver_hostname = $('#edit_mailserver_hostname').val();
    var mailserver_port = $('#edit_mailserver_port').val();
    var mailserver_username = $('#edit_mailserver_username').val();
    var mailserver_password = $('#edit_mailserver_password').val();
    var from_name = $('#edit_from_name').val();
    var from_mail = $('#edit_from_mail').val();

    var mail_dept_id = $('#edit_mail_dept_id').val();
    var mail_type_id = $('#edit_mail_type_id').val();
    var mail_status_id = $('#edit_mail_status_id').val();
    var mail_priority_id = $('#edit_mail_priority_id').val();

    if ($("#edit_php_mailer").is(':checked')) {

        form_data = {
            mailserver_hostname: mailserver_hostname,
            mailserver_port: mailserver_port,
            mailserver_username: mailserver_username,
            mailserver_password: mailserver_password,
            from_name: from_name,
            from_mail: from_mail,
            is_default: isDefault,
            mail_dept_id: mail_dept_id,
            mail_type_id: mail_type_id,
            mail_status_id: mail_status_id,
            mail_priority_id: mail_priority_id,
            id: email_id,
            php_mailer: 'yes',
        }

    } else {
        if (mail_queue_address == '' || mail_queue_address == null) {
            $('#email_emp').css('display', 'block');
            return false;

        } else if (queue_type == '' || queue_type == null) {
            $('#queue_emp').css('display', 'block');
            return false;

        } else if (protocol == '' || protocol == null) {
            $('#protocol_emp').css('display', 'block');
            return false;

        } else if (queue_template == '' || queue_template == null) {
            $('#queue_group').css('display', 'block');
            return false;

        } else if (mailserver_hostname == '' || mailserver_hostname == null) {
            $('#server_emp').css('display', 'block');
            return false;

        } else if (mailserver_port == '' || mailserver_port == null) {
            $('#port_emp').css('display', 'block');
            return false;

        } else if (mailserver_username == '' || mailserver_username == null) {
            $('#user_emp').css('display', 'block');
            return false;

        } else if (mailserver_password == '' || mailserver_password == null) {
            $('#password_emp').css('display', 'block');
            return false;

        } else if (mail_dept_id == '' || mail_dept_id == null) {
            $('#dept_emp').css('display', 'block');
            return false;

        } else if (mail_type_id == '' || mail_type_id == null) {
            $('#type_emp').css('display', 'block');
            return false;

        } else if (mail_status_id == '' || mail_status_id == null) {
            $('#status_emp').css('display', 'block');
            return false;

        } else if (mail_priority_id == '' || mail_priority_id == null) {
            $('#priority_emp').css('display', 'block');
            return false;
        }


        form_data = {
            mail_queue_address: mail_queue_address,
            queue_type: queue_type,
            protocol: protocol,
            queue_template: queue_template,
            is_enabled: is_enabled,
            registration_required: registration_required,
            autosend: autosend,
            is_default: isDefault,
            mailserver_hostname: mailserver_hostname,
            mailserver_port: mailserver_port,
            mailserver_username: mailserver_username,
            mailserver_password: mailserver_password,
            from_name: from_name,
            from_mail: from_mail,
            mail_dept_id: mail_dept_id,
            mail_type_id: mail_type_id,
            mail_status_id: mail_status_id,
            mail_priority_id: mail_priority_id,
            id: email_id,
            php_mailer: 'no',
            outbound: outbnd,
        }
    }

    $.ajax({
        type: "POST",
        url: update_email,
        data: form_data,
        success: function(data) {
            console.log(data);
            if (data.status_code == 200 && data.success == true) {
                $('#edit_email_modal').modal('hide');
                get_mails_table_list();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        error: function(e) {
            console.log(e);
        }
    });

}

function verify_connection(el, value) {

    if (value == 'add') {

        var mailserver_hostname = $('#mailserver_hostname').val();
        var mailserver_port = $('#mailserver_port').val();
        var mailserver_username = $('#mailserver_username').val();
        var mailserver_password = $('#mailserver_password').val();
        var protocol = $('#protocol').val();

        if (mailserver_hostname == '' || mailserver_hostname == null) {
            $('#server_emp').css('display', 'block');
            return false;
        } else if (mailserver_port == '' || mailserver_port == null) {
            $('#port_emp').css('display', 'block');
            return false;
        } else if (mailserver_username == '' || mailserver_username == null) {
            $('#user_emp').css('display', 'block');
            return false;
        } else if (mailserver_password == '' || mailserver_password == null) {
            $('#password_emp').css('display', 'block');
            return false;
        }

        $(el).prop('disabled', true);

    } else {

        var mailserver_hostname = $('#edit_mailserver_hostname').val();
        var mailserver_port = $('#edit_mailserver_port').val();
        var mailserver_username = $('#edit_mailserver_username').val();
        var mailserver_password = $('#edit_mailserver_password').val();
        var protocol = $('#edit_protocol').val();

        if (mailserver_hostname == '' || mailserver_hostname == null) {
            $('#edit_server_emp').css('display', 'block');
            return false;
        } else if (mailserver_port == '' || mailserver_port == null) {
            $('#protocol_emp').css('display', 'block');
            return false;
        } else if (mailserver_username == '' || mailserver_username == null) {
            $('#edit_user_emp').css('display', 'block');
            return false;
        } else if (mailserver_password == '' || mailserver_password == null) {
            $('#edit_password_emp').css('display', 'block');
            return false;
        }

        $(el).prop('disabled', true);


    }


    try {
        $.ajax({
            type: "POST",
            url: verify_conn_route,
            cache: false,
            data: {
                queue_type: $('#queue_type').val(),
                protocol: protocol,
                mailserver_hostname: mailserver_hostname,
                mailserver_port: mailserver_port,
                mailserver_username: mailserver_username,
                mailserver_password: mailserver_password
            },
            success: function(data) {
                $(el).prop('disabled', false);
                Swal.fire({
                    position: 'center',
                    icon: (data.success) ? 'success' : 'error',
                    title: (data.success) ? 'Connection verified!' : data.error,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
                // 'Failed to verify connection!'
            }
        });
    } catch (err) {
        $(el).prop('disabled', false);
        console.log(err);
    }
}

function save_pop3_mail() {

    var mail_queue_address = $('#mail_queue_address').val();
    var queue_type = $('#queue_type').val();
    var protocol = $('#protocol').val();
    var queue_template = $('#queue_template').val();

    var is_enabled = '';
    var registration_required = '';
    var autosend = '';
    var isDefault = '';
    var outbnd = '';
    var form_data = '';

    if ($('#autosend_ticket').prop('checked')) {
        autosend = 'yes';
    } else {
        autosend = 'no';
    }
    if ($('#is_dept_default').prop('checked')) {
        isDefault = 'yes';
    } else {
        isDefault = 'no';
    }
    if ($('#email_outbound').prop('checked')) {
        outbnd = 'yes';
    } else {
        outbnd = 'no';
    }

    if ($('#registration_required').prop('checked')) {
        registration_required = 'yes';
    } else {
        registration_required = 'no';
    }

    if ($('#is_enabled').prop('checked')) {
        is_enabled = 'yes';
    } else {
        is_enabled = 'no';
    }

    var mailserver_hostname = $('#mailserver_hostname').val();
    var mailserver_port = $('#mailserver_port').val();
    var mailserver_username = $('#mailserver_username').val();
    var mailserver_password = $('#mailserver_password').val();
    var from_name = $('#from_name').val();
    var from_mail = $('#from_mail').val();

    var mail_dept_id = $('#mail_dept_id').val();
    var mail_type_id = $('#mail_type_id').val();
    var mail_status_id = $('#mail_status_id').val();
    var mail_priority_id = $('#mail_priority_id').val();

    if ($("#php_mailer").is(':checked')) {

        form_data = {
            mailserver_hostname: mailserver_hostname,
            mailserver_port: mailserver_port,
            mailserver_username: mailserver_username,
            mailserver_password: mailserver_password,
            from_name: from_name,
            from_mail: from_mail,
            is_default: isDefault,
            mail_dept_id: mail_dept_id,
            mail_type_id: mail_type_id,
            mail_status_id: mail_status_id,
            mail_priority_id: mail_priority_id,
            php_mailer: 'yes',
            id: $('#save-mail').find('#editId').val(),
        }

    } else {
        if (mail_queue_address == '' || mail_queue_address == null) {
            $('#email_emp').css('display', 'block');
            return false;

        } else if (queue_type == '' || queue_type == null) {
            $('#queue_emp').css('display', 'block');
            return false;

        } else if (protocol == '' || protocol == null) {
            $('#protocol_emp').css('display', 'block');
            return false;

        } else if (queue_template == '' || queue_template == null) {
            $('#queue_group').css('display', 'block');
            return false;

        } else if (mailserver_hostname == '' || mailserver_hostname == null) {
            $('#server_emp').css('display', 'block');
            return false;

        } else if (mailserver_port == '' || mailserver_port == null) {
            $('#port_emp').css('display', 'block');
            return false;

        } else if (mailserver_username == '' || mailserver_username == null) {
            $('#user_emp').css('display', 'block');
            return false;

        } else if (mailserver_password == '' || mailserver_password == null) {
            $('#password_emp').css('display', 'block');
            return false;

        } else if (mail_dept_id == '' || mail_dept_id == null) {
            $('#dept_emp').css('display', 'block');
            return false;

        } else if (mail_type_id == '' || mail_type_id == null) {
            $('#type_emp').css('display', 'block');
            return false;

        } else if (mail_status_id == '' || mail_status_id == null) {
            $('#status_emp').css('display', 'block');
            return false;

        } else if (mail_priority_id == '' || mail_priority_id == null) {
            $('#priority_emp').css('display', 'block');
            return false;
        }


        form_data = {
            mail_queue_address: mail_queue_address,
            queue_type: queue_type,
            protocol: protocol,
            queue_template: queue_template,
            is_enabled: is_enabled,
            registration_required: registration_required,
            autosend: autosend,
            is_default: isDefault,
            mailserver_hostname: mailserver_hostname,
            mailserver_port: mailserver_port,
            mailserver_username: mailserver_username,
            mailserver_password: mailserver_password,
            from_name: from_name,
            from_mail: from_mail,
            mail_dept_id: mail_dept_id,
            mail_type_id: mail_type_id,
            mail_status_id: mail_status_id,
            mail_priority_id: mail_priority_id,
            id: $('#save-mail').find('#editId').val(),
            php_mailer: 'no',
            outbound: outbnd,
        }
    }

    $.ajax({
        type: "POST",
        url: save_mail_route,
        data: form_data,
        beforeSend: function() {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', true);
        },
        success: function(data) {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', false);
            console.log(data);
            if (data.status_code == 200 && data.success == true) {
                $('#save-mail').modal('hide');
                $('#mail-form').trigger('reset');
                get_mails_table_list();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        complete: function() {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', false);
        },
        error: function(e) {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', false);
            console.log(e);
        }
    });
}

function verify_save_pop3_mail() {

    var mail_queue_address = $('#mail_queue_address').val();
    var queue_type = $('#queue_type').val();
    var protocol = $('#protocol').val();
    var queue_template = $('#queue_template').val();

    var is_enabled = '';
    var registration_required = '';
    var autosend = '';
    var isDefault = '';
    var outbnd = '';

    if ($('#autosend_ticket').prop('checked')) {
        autosend = 'yes';
    } else {
        autosend = 'no';
    }
    
    if ($('#is_dept_default').prop('checked')) {
        isDefault = 'yes';
    } else {
        isDefault = 'no';
    }

    if ($('#email_outbound').prop('checked')) {
        outbnd = 'yes';
    } else {
        outbnd = 'no';
    }

    if ($('#registration_required').prop('checked')) {
        registration_required = 'yes';
    } else {
        registration_required = 'no';
    }

    if ($('#is_enabled').prop('checked')) {
        is_enabled = 'yes';
    } else {
        is_enabled = 'no';
    }

    var mailserver_hostname = $('#mailserver_hostname').val();
    var mailserver_port = $('#mailserver_port').val();
    var mailserver_username = $('#mailserver_username').val();
    var mailserver_password = $('#mailserver_password').val();
    var from_name = $('#from_name').val();
    var from_mail = $('#from_mail').val();

    var mail_dept_id = $('#mail_dept_id').val();
    var mail_type_id = $('#mail_type_id').val();
    var mail_status_id = $('#mail_status_id').val();
    var mail_priority_id = $('#mail_priority_id').val();

    if (mail_queue_address == '' || mail_queue_address == null) {
        $('#email_emp').css('display', 'block');
        return false;

    } else if (queue_type == '' || queue_type == null) {
        $('#queue_emp').css('display', 'block');
        return false;

    } else if (protocol == '' || protocol == null) {
        $('#protocol_emp').css('display', 'block');
        return false;

    } else if (queue_template == '' || queue_template == null) {
        $('#queue_group').css('display', 'block');
        return false;

    } else if (mailserver_hostname == '' || mailserver_hostname == null) {
        $('#server_emp').css('display', 'block');
        return false;

    } else if (mailserver_port == '' || mailserver_port == null) {
        $('#port_emp').css('display', 'block');
        return false;

    } else if (mailserver_username == '' || mailserver_username == null) {
        $('#user_emp').css('display', 'block');
        return false;

    } else if (mailserver_password == '' || mailserver_password == null) {
        $('#password_emp').css('display', 'block');
        return false;

    } else if (mail_dept_id == '' || mail_dept_id == null) {
        $('#dept_emp').css('display', 'block');
        return false;

    } else if (mail_type_id == '' || mail_type_id == null) {
        $('#type_emp').css('display', 'block');
        return false;

    } else if (mail_status_id == '' || mail_status_id == null) {
        $('#status_emp').css('display', 'block');
        return false;

    } else if (mail_priority_id == '' || mail_priority_id == null) {
        $('#priority_emp').css('display', 'block');
        return false;

    }

    $.ajax({

        type: "POST",
        url: save_mail_route,
        data: {
            verify: 'yes',
            mail_queue_address: mail_queue_address,
            queue_type: queue_type,
            protocol: protocol,
            queue_template: queue_template,
            is_enabled: is_enabled,
            registration_required: registration_required,
            autosend: autosend,
            is_default: isDefault,
            mailserver_hostname: mailserver_hostname,
            mailserver_port: mailserver_port,
            mailserver_username: mailserver_username,
            mailserver_password: mailserver_password,
            from_name: from_name,
            from_mail: from_mail,
            mail_dept_id: mail_dept_id,
            mail_type_id: mail_type_id,
            mail_status_id: mail_status_id,
            mail_priority_id: mail_priority_id,
            id: $('#save-mail').find('#editId').val(),
            outbound: outbnd
        },
        beforeSend: function() {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', true);
        },
        success: function(data) {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', false);
            console.log(data);
            if (data.success) {
                $('#save-mail').modal('hide');

                $('#mail-form').trigger('reset');

                get_mails_table_list();
            }

            Swal.fire({
                position: 'center',
                icon: (data.success) ? 'success' : 'error',
                title: data.message,
                showConfirmButton: false,
                timer: swal_message_time
            });
        },
        complete: function() {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', false);
        },
        error: function() {
            $('#mail-form').closest('.modal-body').find('.btn').attr('disabled', false);
        }
    });

}

function deleteMail(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this Mail will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_mail_route,
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data.status_code == 200 && data.success == true) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                        get_mails_table_list();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    }

                }
            });
        }
    })
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#site_logo_preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#site_logo").change(function() {
    readURL(this);
});

function readfavURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#site_favicon_preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#site_favicon").change(function() {
    readfavURL(this);
});

$("#login_logo").change(function() {
    readloginURL(this);
});

$("#customer_logo").change(function() {
    let name = "customer_logo_preview";
    showImagePreview(this , name);
});

$("#company_logo").change(function() {
    let name = "company_logo_preview";
    showImagePreview(this , name);
});

$("#user_logo").change(function() {
    let name = "user_logo_preview";
    showImagePreview(this , name);
});

function showImagePreview(input , id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}


function readloginURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#login_logo_preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#save_department").submit(function(event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');

    formData.append('is_enabled', ($('#dept_is_enabled').prop('checked')) ? 'yes' : 'no');

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data['success'] == true) {

                $("#save_department").trigger("reset");
                $('#save-department').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })

                get_departments_table_list();

            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });

});

function showStatusModel() {
    $("#stat").text("Add Status");
    $('#save-status').modal('show');

    $("#status_name").val("");
    $(".form-control").val("");
    $('#department_id2').val("").trigger('change');
    $('.select2-container').select2('val', '');

}

$("#save_status").submit(function(event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var selectedDepartments = [];

    for (var option of document.getElementById('department_id2').options) {
        if (option.selected) {
            selectedDepartments.push(option.value);
        }
    }

    formData.append('department_id', selectedDepartments);

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data.status_code == 200 && data.success == true) {
                get_status_table_list();
                $("#save_status").trigger("reset");
                $('#save-status').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })

            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });

});

$("#save_priority").submit(function(event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var selectedPriority = [];

    for (var option of document.getElementById('department_id').options) {
        if (option.selected) {
            selectedPriority.push(option.value);
        }
    }

    formData.append('department_id', selectedPriority);

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data['success'] == true) {

                $("#save_priority").trigger("reset");
                $('#save-priority').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                get_priority_table_list();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });

});

$("#save_ticket").submit(function(event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var selectedTicketType = [];

    for (var option of document.getElementById('department_id1').options) {
        if (option.selected) {
            selectedTicketType.push(option.value);
        }
    }

    formData.append('department_id', selectedTicketType);

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data['success'] == true) {

                $("#save_type").trigger("reset");
                $('#save-type').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                get_type_table_list();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });

});

$("#save_customer_ticket").submit(function(event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');


    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data['success'] == true) {

                $("#save_customer_type").trigger("reset");
                $('#save-customer-type').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                get_customer_type_table_list();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });

});

$("#save_dispatch_status").submit(function(event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');


    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data['success'] == true) {

                $("#save_dispatch_status").trigger("reset");
                $('#save-dispatch-status').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                get_dispatch_status_table_list();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });

});


$("#save_project_type").submit(function(event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');


    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if (data['success'] == true) {

                $("#save_project_type").trigger("reset");
                $('#save-project-type').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                get_project_type_table_list();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        }
    });

});

$('#ticket_format').change(function() {

    var ticket_format = $(this).val();


    $.ajax({
        type: "post",
        url: ticket_format_route,
        data: {
            ticket_format: ticket_format,
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.success == true) {
                var d = flashy('Ticket ID Format Updated Successfully!', {
                    type: 'flashy__success',
                    stop: true
                });

            }
        }
    });
});

$(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
var radioswitch = function() {
    var bt = function() {
        $(".radio-switch").on("switch-change", function() {
            $(".radio-switch").bootstrapSwitch("toggleRadioState")
        }), $(".radio-switch").on("switch-change", function() {
            $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
        }), $(".radio-switch").on("switch-change", function() {
            $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
        })
    };
    return {
        init: function() {
            bt()
        }
    }
}()

// ticket auto refresh code


const saveTicketRefreshTime  = ()  => {
    let tkt_refresh = $("#tkt_refresh").val();

    if(tkt_refresh != '') {
        $.ajax({
            type: 'post',
            url: "{{route('ticketRefreshTime')}}",
            data: { tkt_refresh: tkt_refresh },
            beforeSend: function(data ){
                $('.tkt_btn').hide();
                $('.tkt_loader').show();
            },
            success: function(data) {
                if(data.status_code == 200 && data.success == true)  {
                    toastr.success( data.message , { timeOut: 5000 });
                }else{
                    toastr.error( data.message , { timeOut: 5000 });
                }
            },
            complete : function (data) {
                $('.tkt_btn').show();
                $('.tkt_loader').hide();
            },
            failure: function(errMsg) {
                $('.tkt_btn').show();
                $('.tkt_loader').hide();
                toastr.error( 'Somthing went wrong' , { timeOut: 5000 });
            }
        });
    }else{
        toastr.error( 'Ticket Data Refresh field is required..' , { timeOut: 5000 });
    }

}
</script>