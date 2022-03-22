<script>
// Project Details Script Blade
var project_slug = $("#project_slug").val();
var project_id = $('#project_id').val();
var notes_current_color = '';
var system_date_format = $("#system_date_format").val();
let tasks_list = [];
let tasks_flt_list = [];
let tasks_table_list = null;
let tasks_arr = [];
var data_editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
        
    if(localStorage.getItem('df_task_version')) {
        $('#selc-ver').val(localStorage.getItem('df_task_version'));
    }

    $("#newTaskBtn").click(function() {
        // $('.newTask').show('1000');
        $('.bigger').text('Add New Task');
        $('.newTask').show('1000').animate({ right: '0' });
        $('.taskTable').css('height', '900px');
        $('.taskHeader').css('height', '600px');

        $("#save-task")[0].reset();

        $(".dropify-clear").trigger("click");
        $(".attchment_body").remove();

        $("#task_id").val("");

    });

    get_all_project_task();

    // notes tag dropdown
    var userlist = [];
    users.forEach(element => {
        userlist.push(element.name + ' (' + element.email + ')');
    });

    $('#note').atwho({
        at: "@",
        data: userlist,
    });

    $('#edit_note_field').atwho({
        at: "@",
        data: userlist,
    });
    // notes tag dropdown


    getFormsTemplates();

    get_asset_table_list();

    $("#save_asset_form").submit(function(event) {
        event.preventDefault();
        event.stopPropagation();

        var formData = new FormData($(this)[0]);
        var action = $(this).attr('action');
        var method = $(this).attr('method');

        formData.append('project_id', asset_project_id);
        let demo_address = $("#demo_address").val();

        if (demo_address == 123) {

            $('.keysss').each(function() {

                var fl_address = $("#fl_address_" + $(this).val()).val();
                var fl_aprt = $("#fl_aprt_" + $(this).val()).val();
                var fl_city = $("#fl_city_" + $(this).val()).val();
                var all_states = $("#all_states_" + $(this).val()).val();
                var fl_zip_code = $("#fl_zip_code_" + $(this).val()).val();
                var all_countries = $("#all_countries_" + $(this).val()).val();

                var value = fl_address + "|" + fl_aprt + "|" + fl_city + "|" + all_states + "|" + fl_zip_code + "|" + all_countries;

                formData.append("fl_" + $(this).val(), value);
            });


        } else {
            console.log(formData, "formData");
        }

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
                if (data.success == true && data.status_code == 200) {
                    $("#save_asset_form").trigger("reset");
                    $("#form-fields").html("");
                    $('#asset').modal('hide');

                    get_asset_table_list();



                    $("#templateTitle").removeAttr('style');
                    $("#templateTitle").attr('style', 'display:none !important');
                }
                Swal.fire({
                    position: 'center',
                    icon: (data.success) ? 'success' : 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        });
    });

    // save tasks
    $("#save-task").submit(function(event) {
        event.preventDefault();

        let form_data = new FormData(this);
        let url = $(this).attr('action');
        let method = $(this).attr('method');
        // var tsk_desc = tinymce.get('tsk_desc').getContent();
        var tsk_desc = $('#tsk_desc').val();

        var estimated_time_hours = $("#estimated_time_hours").val();
        var estimated_time_mins = $("#estimated_time_mins").val();

        form_data.append('estimated_time', estimated_time_hours + ":" + estimated_time_mins);

        for (let i in g_attachments) {
            form_data.append('attachment_' + i, g_attachments[i]);
        }

        if (g_attachments_delete.length > 0) {
            form_data.append('delete_Attachments', g_attachments_delete);
        }

        var completion_time_hours = $("#completion_time_hours").val();
        var completion_time_mins = $("#completion_time_mins").val();

        form_data.append('completion_time', completion_time_hours + ":" + completion_time_mins);


        form_data.append('slug', project_slug);
        form_data.append('project_id', project_id);
        form_data.append('task_description', tsk_desc);


        $.ajax({
            url: url,
            type: method,
            data: form_data,
            dataType: 'JSON',
            async: true,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(data) {
                $('.project_btn').hide();
                $('.project_loader').show();
            },
            success: function(data) {
                
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#new-task-modal").modal('hide');
                    $("#save-task")[0].reset();

                    get_all_project_task();

                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function(data) {
                $('.project_btn').show();
                $('.project_loader').hide();
            },
            error: function(e) {
                console.log(e);
                $('.project_btn').show();
                $('.project_loader').hide();
            }

        });
    });

    $('#changeLog_table').DataTable({
        data: change_logs_list,
        "bInfo": false,
        dom: 'Bfrtip',
        initComplete: function() {
            $('table.dataTable').hide();
            $('#changeLog_table_filter').hide();
            $('#changeLog_table_paginate').hide();
        },
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
        ],
        columns: [
            {"data": "id"},
            {"data": "version"},
            {"data": "title"},
            {"data": "task_priority"},
            {"data": "task_status"},
            {"data": "task_type"},
            {"data": "assign_to"},
            {"data": "completed_at"},
            {"data": "completion_time"},
            {"data": "due_date"},
            {"data": "estimated_time"},
            {"data": "is_overdue"},
            {"data": "other_tech"},
            {"data": "remarks"},
            {"data": "started_at"},
            {"data": "work_tech"},
            {"data": "worked_time"},
            {"data": "task_description"}
        ],
    });

    $('#selc-ver').change(function() {
        if(this.value) localStorage.setItem('df_task_version', this.value);
    });
});

function editPageTitle() {

    $('#page_title').css('display', 'none');
    $('#edit_title_div').css('display', 'flex');

    $('#title_input').val($('#title_span').text())

}

function cancelEdit() {
    $('#page_title').css('display', 'block');
    $('#edit_title_div').css('display', 'none');
}

function saveTitle() {
    var title = $('#title_input').val();

    $('#title_span').text(title);
    $('#page_title').css('display', 'block');
    $('#edit_title_div').css('display', 'none');

    $.ajax({
        url: update_project_title,
        type: "POST",
        data: {
            title: title,
            project_slug: project_slug
        },

        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data)
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });

}

function editcutomerassign() {
    $('#assigned_customer').css('display', 'none');
    $('#customer_section').css('display', 'flex');
    $('#customer_id').empty();

    var project_customer_id = $("#project_customer_id").val();

    let option = `<option value="">Select</option>`;
    $('#customer_id').append(option).trigger('change');
    for (var i = 0; i < customers.length; i++) {
        var name = '';

        if (customers[i].name == '' || customers[i].name == null || customers[i].name == ' ') {
            name = customers[i].email;
        } else {
            name = customers[i].name;
        }

        var newOption = `<option value="` + customers[i].id + `" ` + (project_customer_id == customers[i].id ? "selected" : " ") + ` > ` + name + ` </option>`;
        $('#customer_id').append(newOption).trigger('change');
    }
}

function cancelCustomerEdit() {
    $('#assigned_customer').css('display', 'block');
    $('#customer_section').css('display', 'none');
}

function saveCustomer() {

    var customer_id = $('#customer_id').val();

    $('#customer_name').text($('#customer_id option:selected').text());
    $('#assigned_customer').css('display', 'block');
    $('#customer_section').css('display', 'none');

    $.ajax({
        url: update_customer,
        type: "POST",
        data: {
            customer_id: customer_id,
            project_slug: project_slug
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data)
            if (data) {
                toastr.success(data.message, { timeOut: 5000 });
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });

}

function editmanagerassign() {
    $('#assigned_manager').css('display', 'none');
    $('#project_manager_section').css('display', 'flex');
    $('#project_manager_id').empty();

    var project_manager_id = $("#selected_project_manager_id").val();

    let option = `<option value="">Select</option>`;
    $('#project_manager_id').append(option).trigger('change');

    for (var i = 0; i < users.length; i++) {
        var newOption = `<option value="` + users[i].id + `" ` + (project_manager_id == users[i].id ? "selected" : " ") + ` > ` + users[i].name + ` </option>`;
        $('#project_manager_id').append(newOption).trigger('change');
    }
}

function cancelManagerEdit() {
    $('#assigned_manager').css('display', 'block');
    $('#project_manager_section').css('display', 'none');
}

function saveManager() {
    var project_manager_id = $('#project_manager_id').val();

    $('#manager_name').text($('#project_manager_id option:selected').text());
    $('#assigned_manager').css('display', 'block');
    $('#project_manager_section').css('display', 'none');

    $.ajax({
        url: update_project_manager,
        type: "POST",
        data: {
            manager_id: project_manager_id,
            project_slug: project_slug
        },

        dataType: 'json',
        cache: false,

        success: function(data) {
            console.log(data)
            if (data) {
                toastr.success(data.message, { timeOut: 5000 });
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });

}

function openTaskModal() {
    $("#new-task-modal").modal('show');
    $("#task_id").val("");
    $(".project_btn").text("Save");
    $("#task_att_type").val("open");
}

// search template
$("#todo-search").on('keyup', function() {
    let value = $(this).val().toLowerCase();

    var filtertext = $.grep(tasks_arr , function(object) {
        let re_title = object.title.toLowerCase();
        if(re_title != null) {
            return re_title.includes(value);
        }
    });

    let html = ``;
    let status = ``;

    if(filtertext.length > 0) {
        for (var i = 0; i < filtertext.length; i++) {
            
            if (filtertext[i].task_status == 'success') {
                status = `<span class="badge rounded-pill badge-light-success"> Completed </span>`;
            }

            if (filtertext[i].task_status == 'danger') {
                status = `<span class="badge rounded-pill badge-light-danger"> Pending </span>`;
            }

            if (filtertext[i].task_status == 'default') {
                status = `<span class="badge rounded-pill badge-light-warning"> Working </span>`;
            }

            html += `
                <li class="todo-item" onclick="showTaskDetail(${filtertext[i].id})">
                    <div class="todo-title-wrapper">
                        <div class="todo-title-area">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical drag-icon"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                            <div class="title-wrapper">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="customCheck1">
                                    <label class="form-check-label" for="customCheck1"></label>
                                </div>
                                <span class="todo-title"> ${filtertext[i].title != null ? filtertext[i].title : '-'}  </span>
                            </div>
                        </div>
                        <div class="todo-item-action">
                            <div class="badge-wrapper me-1">
                                ${status}
                            </div>
                            <small class="text-nowrap text-muted me-1"> ${moment(filtertext[i].created_at).format('MMM DD')} </small>
                            <div class="avatar">
                                <img src="../../../app-assets/images/portrait/small/avatar-s-4.jpg" alt="user-avatar" height="32" width="32">
                            </div>
                        </div>
                    </div>
                </li>`;
        }
    }else{
        html += `<div class="d-flex justify-content-between border-bottom p-1 text-danger" id="resTemp_">
                No task found
            </div>`;
    }
    $('.show_project_tasks').html(html);
});


function filterTasks(task_type) {
    $('.loading__').attr('style','display:block !important');

    let task_data = ``;
    let status = ``;
    if (tasks_arr.length > 0) {

        let items = ``;
        if(task_type == 'all') {
            items = tasks_arr;
        }else{
            items = tasks_arr.filter( item => item.task_status == task_type);
        }

        if(items.length > 0) {

            for (const data of items) {

                let attachment_icon = `<i onclick="openTaskAttachments(${data.id})" class="fa fa-paperclip" aria-hidden="true" style="margin-top:2px; margin-left:4px; color:#5f6c73;" title="Tsak Has Attachments"></i>`;

                if (data.task_status == 'success') {
                    status = `<span class="badge rounded-pill badge-light-success"> Completed </span>`;
                }

                if (data.task_status == 'danger') {
                    status = `<span class="badge rounded-pill badge-light-danger"> Pending </span>`;
                }

                if (data.task_status == 'default') {
                    status = `<span class="badge rounded-pill badge-light-warning"> Working </span>`;
                }

                let title = `<a href="javascrip:void(0)" onclick="showTaskDetail(${data.id})"> ${data.title}  </a>`;
                task_data += `
                        <li class="todo-item">
                            <div class="todo-title-wrapper">
                                <div class="todo-title-area">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical drag-icon"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    <div class="title-wrapper">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1"></label>
                                        </div>
                                        <span class="todo-title"> ${data.title != null ? title : '-'}  ${data.task_attachments.length > 0 ? attachment_icon : ''} </span>
                                    </div>
                                </div>
                                <div class="todo-item-action">
                                    <div class="badge-wrapper me-1">
                                        ${status}
                                    </div>
                                    <small class="text-nowrap text-muted me-1"> ${moment(data.created_at).format('MMM DD')} </small>
                                    <div class="avatar">
                                        <img src="../../../app-assets/images/portrait/small/avatar-s-4.jpg" alt="user-avatar" height="32" width="32">
                                    </div>
                                </div>
                            </div>
                        </li>`;
            }

            $('.show_project_tasks').html(task_data);
        }else{
            $('.show_project_tasks').html('<li class="text-danger"> No Tasks </li>');
        }

    } else {
        $('.show_project_tasks').html('<li class="text-danger"> No Tasks </li>');
    }

    setTimeout(() => {
        $('.loading__').attr('style','display:none !important');
    }, 1000);
}


function get_all_project_task() {
    var project_id = $("#project_id").val();

    $.ajax({
        type: "GET",
        url: get_all_tasks + '/' + project_id,
        beforeSend:function(data) {
            $('.loading__').attr('style','display:block !important');
        },
        success: function(result) {
            if(result.success) {
                let obj = result.data;
                console.log(obj , "obj");
                tasks_arr = result.data;
                let task_data = ``;
                let status = ``;
                if (obj.length > 0) {

                    for (const data of obj) {

                        let attachment_icon = `<i onclick="openTaskAttachments(${data.id})" class="fa fa-paperclip" aria-hidden="true" style="margin-top:2px; margin-left:4px; color:#5f6c73;" title="Tsak Has Attachments"></i>`;

                        if (data.task_status == 'success') {
                            status = `<span class="badge rounded-pill badge-light-success"> Completed </span>`;
                        }

                        if (data.task_status == 'danger') {
                            status = `<span class="badge rounded-pill badge-light-danger"> Pending </span>`;
                        }

                        if (data.task_status == 'default') {
                            status = `<span class="badge rounded-pill badge-light-warning"> Working </span>`;
                        }

                        let title = `<a href="javascrip:void(0)" onclick="showTaskDetail(${data.id})"> ${data.title}  </a>`;
                        task_data += `
                            <li class="todo-item">
                                <div class="todo-title-wrapper">
                                    <div class="todo-title-area">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical drag-icon"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                        <div class="title-wrapper">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </div>
                                            <span class="todo-title"> ${data.title != null ? title : '-'}  ${data.task_attachments.length > 0 ? attachment_icon : ''} </span>
                                        </div>
                                    </div>
                                    <div class="todo-item-action">
                                        <div class="badge-wrapper me-1">
                                            ${status}
                                        </div>
                                        <small class="text-nowrap text-muted me-1"> ${moment(data.created_at).format('MMM DD')} </small>
                                        <div class="avatar">
                                            <img src="../../../app-assets/images/portrait/small/avatar-s-4.jpg" alt="user-avatar" height="32" width="32">
                                        </div>
                                    </div>
                                </div>
                            </li>`;
                    }

                    $('.show_project_tasks').html(task_data);


                } else {
                    $('.show_project_tasks').html('<spa class="text-danger"> No Tasks </spa>');
                }
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: result.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        complete: function(data) {
            $('.loading__').attr('style','display:none !important');
            console.log('Success', data);
        },
        error: function(data) {
            console.log('Error', data);
        }
    });
}

function openTaskAttachments(id) {
    loadAttachments(id);
    $('#attachmentsModal').modal('show');
}

function showTaskDetail(id) {

    $("#task_id").val(id);
    $("#new-task-modal").modal('show');
    $(".project_btn").text("Update");

    $("#task_att_type").val("edit");

    let item = tasks_arr.find( item => item.id == id);
    
    if(item != null) {

        $("input[name='version']").val( item.version != null ? item.version : '' );
        $("#title").val( item.title != null ? item.title : '' );

        if(item.task_status != null) {
            $("#selc-st").val( item.task_status ).trigger("change");
        }
        
        if(item.task_priority != null) {
            $("#task_priority").val( item.task_priority ).trigger("change");
        }
        
        if(item.start_date != null) {
            $("input[name='start_date']").val( item.start_date );
        }

        if(item.due_date != null) {
            $("input[name='due_date']").val( item.due_date );
        }

        if(item.estimated_time != null) {
            let time = item.estimated_time.split(':');
            $("#estimated_time_hours").val(time[0]);
            $("#estimated_time_mins").val(time[1]);
        }

        $("#assign_to").val(item.assign_to).trigger("change");
        
        if(item.work_tech != null) {
            $("#work_tech").val(item.work_tech).trigger("change");
        }

        if(item.task_type != null) {
            $("#task_type").val(item.task_type).trigger("change");
        }

        $("#tsk_desc").val(item.task_description != null ? item.task_description : '');

        let files_html = ``;
        // reInitailizeAttachmentsDialog();
        if(item.task_attachments.length > 0) {


            // loadAttachments(item.id);

            // for (let i = 0; i < item.task_attachments.length; i++) {
            //     files_html += `
            //         <div class="col-4 mb-3 -${i}" name="${item.task_attachments[i].attachment}">
            //             <input type="file" id="dropi-${g_dropi_index}" class="task_attachments" data-show-errors="true" onchange="loadFile(this);" data-max-file-size="2M"/>
            //         </div>`;

            //         reDropify(g_dropi_index, true);
            //         // $('#dropi-'+g_dropi_index).attr('disabled', 'true');
            //         g_dropi_index++;
            // }
        }else{
            files_html = ``
        }

        
        // $("#showUploadedAttachments").html(files_html);

    }
}

function get_all_project_taskold() {
    var project_id = $("#project_id").val();

    $("#project_all_tasks").DataTable().destroy();
    $.fn.dataTable.ext.errMode = "none";

    $.ajax({
        type: "GET",
        url: get_all_tasks + '/' + project_id,
        success: function(result) {
            if(result.success) {
                tasks_list = result.data;

                tasks_list.forEach((itm, index) => {
                    itm.sort_id = index+1;
                });

                tasks_table_list = $("#project_all_tasks").DataTable({
                    searching: true,
                    pageLength: 10,
                    data: tasks_list,
                    columnDefs: [
                        {
                            orderable: false,
                            targets: 0
                        },{
                            targets: [0],
                            visible: false
                        },{
                            targets: [1],
                            className: 'reorder'
                        }
                    ],
                    createdRow: function(row, data, dataIndex){
                        $(row).attr('id', 'row-' + data[1]);
                    },
                    rowReorder: {
                        dataSrc: 'sort_id',
                        // selector: 'tr'
                    },
                    order: [[0, 'asc']]
                });

                tasks_table_list.on( 'row-reorder', function ( e, diff, edit ) {
                    console.log('Reorder ', {e, diff, edit})
                
                    if(diff.length > 1) {
                        let new_orders = [];
                        let new_list = [];
                        let list = [];
                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            // get id row
                            let idQ = diff[i].node.id;
                            let idNewQ = idQ.replace("row-", "");

                            // get position
                            // let position = diff[i].newPosition+1;

                            let obj = tasks_list.map(function(item) { return parseInt(item.id)}).indexOf(parseInt(idNewQ));

                            if(obj > -1) {
                                new_orders.push(tasks_list[obj].sort_id);
                                list.push(tasks_list[obj].id);
                            }
                        }
                        new_orders.sort(function(a, b) {
                            return a - b;
                        });
                        for ( var i=0; i<list.length ; i++ ) {
                            let obj = tasks_list.map(function(item) { return parseInt(item.id)}).indexOf(parseInt(list[i]));

                            if(obj > -1) {
                                tasks_list[obj].sort_id = new_orders[i];
                                new_list.push({id: tasks_list[obj].id, sort_id: new_orders[i]});
                            }
                        }

                        //call funnction to update data
                        updateOrder(new_list);
                    }

                    applyTasksFilter(true);
                } );

                applyTasksFilter();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: result.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        complete: function(data) {
            console.log('Success', data);
        },
        error: function(data) {
            console.log('Error', data);
        }
    });
}

function updateOrder(list) {
    console.log(list);

    $.ajax({
        type: "POST",
        url: "{{url('update-tasks-order')}}",
        data: {order: list},
        dataType: 'json',
        success: function(data) {
            if(!data.success) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        complete: function(data) {
            // 
        },
        error: function(data) {
            console.log(data);
        }
    });
}

// edit task
function editTask(task_id) {
    $(".edit_tsk_loader").show();

    $("#task_id").val(task_id)

    $('.bigger').text('Edit Task');
    $('.newTask').show('1000').animate({ right: '0' });

    $.ajax({
        type: "POST",
        url: get_task_byid,
        data: { task_id: task_id },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            var obj = data.task;
            var estimate_time = obj.estimated_time != null ? obj.estimated_time : '--';;
            var time = estimate_time.split(':');

            let version = obj.version != null ? obj.version : '--';
            let title = obj.title != null ? obj.title : '--';
            let task_status = obj.task_status != null ? obj.task_status : '--';
            let task_priority = obj.task_priority != null ? obj.task_priority : '--';
            let due_date = obj.due_date != null ? obj.due_date : '--';
            let assign_to = obj.assign_to != null ? obj.assign_to : '--';
            let work_tech = obj.work_tech != null ? obj.work_tech : '--';
            let task_type = obj.task_type != null ? obj.task_type : '--';
            let task_description = obj.task_description != null ? obj.task_description : '--';
            let start_date = obj.start_date != null ? obj.start_date : '---';

            $("input[name='version']").val(version);

            $("#title").val(title);

            $("#task_status").val(task_status).trigger('change');
            $("#task_priority").val(task_priority).trigger('change');

            $("#start_date").val(start_date);
            $("#due_date").val(due_date);

            $("#estimated_time_hours").val(time[0]);
            $("#estimated_time_mins").val(time[1]);

            $("#assign_to").val(assign_to);

            $("#work_tech").val(work_tech);
            $("#task_type").val(task_type);

            // $("#task_description").val(task_description);

            tinymce.get("tsk_desc").setContent(task_description);

        },
        complete: function(data) {
            $(".edit_tsk_loader").hide();
        },
        error: function(data) {
            $(".edit_tsk_loader").hide();
            console.log(data);
        }
    });

}

// delete task
function deleteTask(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this asset will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: delete_task,
                data: {
                    task_id: id,
                },
                success: function(data) {
                    console.log(data);
                    if (data.status_code == 200 && data.success == true) {
                        toastr.success(data.message, { timeOut: 5000 });
                        getAllProjectTasks();
                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                }
            });
        }
    })
}
// asset manager

function getFields(id) {
    $("#templateTitle").css("display", "block");

    if (!id) {
        $("#form-fields").html("");
        $("#templateTitle").css("display", "none");
        return;
    }
    console.log(templates, "before");
    let data = templates.filter(itm => itm.id == id);
    console.log(data, "before");
    data = data[0].fields;
    console.log(data);

    var fields = ``;

    for (var i = 0; i < data.length; i++) {
        var length = data.length;
        console.log(data.length, "dasdasdasdasd");
        let placeholder = data[i].placeholder != null ? data[i].placeholder : "";
        let required = data[i].required == 1 ? "required" : "";

        fields += `<div class="col-md-${data[i].col_width} form-group">
            <label>${data[i].label}</label> ${(data[i].required == 1 ? `<span class="text-danger">*</span>` : '')}`;
        

        switch(data[i].type) {
            case 'ipv4':
                fields += `<input type="${data[i].type}" id="ipv4" class="form-control" name="fl_${data[i].id}" placeholder="${placeholder}" ${required}/>`;                
                break;
            case 'textbox':
                fields += `<textarea class="form-control" name="fl_${data[i].id}" placeholder="${placeholder}" ${required}></textarea>`;
                break;
            case 'selectbox':
                let opts = data[i].options.split('|');
                let multi = (data[i].is_multi) ? 'multiple' : '';
                fields += `<select class="form-control select2" name="fl_${data[i].id}" ${required} ${multi}>`
                for(let j in opts) {
                    fields += `<option value="${opts[j]}">${opts[j]}</option>`;
                }
            fields += `</select>`;
                break;
            case 'password':
                fields += `<div class="user-password-div">
                    <span class="block input-icon input-icon-right">
                        <input type="password" name="fl_${data[i].id}" placeholder="${placeholder}" class="form-control" ${required}>
                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon show-password-btn mr-2"></span>
                    </span>
                </div>`;
                break;
            case 'address':
                fields += `<div class="form-row">
                        <input type="hidden" id="field_length" value="`+length+`"/>
                        <div class="col-12 form-group">
                            <label>Street Address</label> <span class="text-danger">*</span>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class=" form-control" id="fl_address_${data[i].id}" placeholder="House number and street name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class=" form-control"  id="fl_aprt_${data[i].id}" placeholder="Apartment, suit, unit etc. (optional)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label >City</label> <span class="text-danger">*</span>
                            <input type="text" class="form-control"  id="fl_city_${data[i].id}" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <label >State</label> <span class="text-danger">*</span>
                            <select class="form-control select2"  id="all_states_${data[i].id}"
                                    style="width: 100%; height:36px;" required>
                            </select>
                        </div>

                        <input type="hidden" class="form-control" data-id="fl_${data[i].id}" value="123" id="demo_address">
                        <input type="hidden" class="form-control keysss" value="${data[i].id}" id="key_id">

                        <div class="col-md-3 form-group">
                            <label >Zip Code</label> <span class="text-danger">*</span>
                            <input type="tel" maxlength="5" class="form-control" id="fl_zip_code_${data[i].id}" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <div class="form-group">
                                <label>Country</label> <span class="text-danger">*</span>
                                <select class="select2 form-control" id="all_countries_${data[i].id}"
                                        style="width: 100%; height:36px;" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    `;
                break;
            default:
                fields += `<input type="${data[i].type}" class="form-control" name="fl_${data[i].id}" placeholder="${placeholder}" ${required}/>`;
        }
        fields += `</div>`;
    }
    $("#form-fields").html(fields);

    $('.select2').select2();

 



    // states and countries call
    $.ajax({
        type: "GET",
        url: "{{asset('get_all_statescountries')}}",
        dataType: 'json',
        success: function(data) {
            var country_obj = data.countries;
            var state_obj = data.states;
            console.log(country_obj , "country_obj")
            console.log(state_obj , "state_obj")

            var countries = ``;
            var states = ``;
            var root = `<option>Select</option>`;
            for(var i =0 ; i < country_obj.length; i++) {
                countries += `<option value="`+country_obj[i].id+`">`+country_obj[i].name+`</option>`;
            }

            for(var i =0 ; i < state_obj.length; i++) {
                states += `<option value="`+state_obj[i].id+`">`+state_obj[i].name+`</option>`;
            }

            $('.keysss').each(function(){
                // alert($(this).val());
                $("#all_countries_"+$(this).val()).append(root + countries);
                $("#all_states_"+$(this).val()).append(root + states);
             });
        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });


    var ipv4_address = $('#ipv4');
        ipv4_address.inputmask({
            alias: "ip",
            greedy: false
    });
}

// asset template dropdown
function getFormsTemplates() {
    $.ajax({
        type: 'get',
        url: templates_fetch_route,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            if (data.success == true) {
                templates = data.templates;
                let opts = '<option value="">Select</option>';
                for (let i in templates) {
                    opts += `<option value="${templates[i].id}">${templates[i].title}</option>`;
                }
                $("#form_id").html(opts);
            }

        },
        error: function(e) {
            console.log(e)
        }
    });
}

function get_asset_table_list() {
  
    $.ajax({
        type: "get",
        url: get_assets_route,
        data: {
            project_id: asset_project_id,
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.assets, 'assets');
            var obj = data.assets;

            $('#asset-table-list').DataTable().destroy();
            $.fn.dataTable.ext.errMode = 'none';
            var tbl = $('#asset-table-list').DataTable({
                data: obj,
                "pageLength": 50,
                "bInfo": false,
                "paging": true,
                columns: [
                    {
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `<a href="` + general_info_route + '/' + full.id + `">` + full.asset_title + `</a>`;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {

                            if (full.template != null) {
                                if (full.template.title != null) {
                                    return full.template.title;
                                } else {
                                    return '-';
                                }
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                                <div class="d-flex justify-content-center">
                                    <button onclick="editAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button onclick="deleteAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`;
                        }
                    },
                ],
            });

            tbl.on('order.dt search.dt', function() {
                tbl.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });

}

function editAsset(id) {

    $("#update_asset_modal").modal('show');

    $.ajax({
        url: show_asset,
        type: 'POST',
        data: { id: id },
        dataType: 'JSON',
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            console.log(data, "data");

            $("#modal-title").text(data.AssetForm.title);
            $("#up_asset_title").val(data.asset.asset_title);
            $("#asset_title_id").val(data.asset.id);

            var html_input = ``;
            var add_html = ``;

            for (var i = 0; i < data.AssetFields.length; i++) {

                var required = `<span class="text-danger">*</span>`;
                var end_point = 'fl_' + data.AssetFields[i].id;


                if (data.AssetFields[i].type == "address") {

                    var full_address = data.asset_record[end_point];
                    var split_address = full_address.split("|");

                    add_html += `
                        <div id="all_input_" class="all_input mt-4">
                        <input type='hidden' id="field_id" class="form-control mt-2 fields_id" value="` + data.AssetFields[i].id + `"/>
                        <label class="mt-2">` + data.AssetFields[i].label + `</label>  ` + (data.AssetFields[i].required == 1 ? required : '') + `

                        <input type='text' value="` + split_address[0] + `" id="address_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        <input type='text' value="` + split_address[1] + `" id="aprt_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        <input type='text' value="` + split_address[2] + `" id="city_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        
                        <select class="select2 form-control mt-2" id="state_` + data.AssetFields[i].id + `" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `>
                        </select>

                        <input type='text' value="` + split_address[4] + `" id="zipcode_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        
                        <select class="select2 form-control mt-2" id="country_` + data.AssetFields[i].id + `" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `>
                        </select>
                    </div>
                    `;

                    showStatesAndCountry(data.AssetFields[i].id, split_address[5], split_address[3]);


                } else {

                    var password = `<span style="position:absolute;top:40px;right:10px" toggle="#password-field" id="pass_icon" class="fa fa-fw fa-eye mr-2 show-password-btn pass_icon"></span>`;
                    html_input += `
                    <input type='hidden' id="field_id" class="form-control mt-2 field_id" value="` + data.AssetFields[i].id + `"/>
                    <div class="form-group" style="position:relative">
                        <label>` + data.AssetFields[i].label + `</label>  ` + (data.AssetFields[i].required == 1 ? required : '') + `
                        <input type="` + data.AssetFields[i].type + `" value="` + data.asset_record[end_point] + `" id="input_` + data.AssetFields[i].id + `" class="form-control input_` + data.AssetFields[i].id + `" placeholder="` + data.AssetFields[i].placeholder + `"  ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        ` + (data.AssetFields[i].type == "password" ? password : '') + `
                    </div>
                `;

                }

            }

            $(".input_fields").html(html_input);
            $(".address_fields").html(add_html);

        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e);
        }

    });



}

function deleteAsset(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this asset will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: del_asset_route,
                data: {
                    id: id,
                    module: 'Project Asset',
                    ref: 'project_asset_deleted',
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Asset Deleted!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })

                        get_asset_table_list();
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

function updateAssets() {

    var asset_id = $("#asset_title_id").val();
    var form_data = {};
    var asset_data = [];

    var complete_address = [];

    var asset_title = $("#up_asset_title").val()

    $(".fields_id").each(function() {
        var field_id = $(this).val();

        var add = $("#address_" + field_id).val();
        var aprt = $("#aprt_" + field_id).val();
        var city = $("#city_" + field_id).val();
        var state = $("#state_" + field_id).val();
        var zipcode = $("#zipcode_" + field_id).val();
        var country = $("#country_" + field_id).val();

        comp_address = add + "|" + aprt + "|" + city + "|" + state + "|" + zipcode + "|" + country;

        var colname = { "keys": "fl_" + field_id };
        var value = { "value": comp_address };
        asset_data.push($.extend(true, {}, colname, value));
    })

    $(".field_id").each(function() {

        var field_id = $(this).val();
        var colname = { "keys": "fl_" + field_id };
        var value = { "value": $("#input_" + field_id).val() };
        asset_data.push($.extend(true, {}, colname, value));


    });

    form_data = {
        asset_id: asset_id,
        asset_title: asset_title,
        data: asset_data,
        complete_address: complete_address,
        module: 'Project Asset',
        ref: 'project_asset_updated',
    }

    $.ajax({
        type: "POST",
        url: update_asset,
        dataType: 'json',
        data: form_data,
        success: function(data) {
            console.log(data, "asset updated");
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
                $("#update_asset_modal").modal('hide');
                get_asset_table_list();
                // location.reload();

                if (asset_ticket_id) ticket_notify('ticket_update', 'T_asset_update');
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });
}

function saveProjectDescription() {

    var project_desc = $("#project_desc").val();
    $.ajax({
        url: save_project_desc,
        type: "POST",
        data: {
            project_desc: project_desc,
            project_id: project_id
        },

        dataType: 'json',
        cache: false,
        beforeSend:function(data) {
            $('#proj_desc_loader').show();
        },
        success: function (data) {
            console.log(data)
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete:function(data) {
            $('#proj_desc_loader').hide();
        },  
        failure: function (errMsg) {
            $('#proj_desc_loader').hide();
            console.log(errMsg);
        }
    });

}

function chnageBGColor(color) {
    notes_current_color = color;
    $('#note').css('background-color', color);

    $("#edit_note_field").css('background-color', color);
}

function saveProjectNotes() {

    var project_note = $("#note").val();
    let extract_notes_email = project_note.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);

    var form_data = {
        project_note: project_note,
        project_id: project_id,
        color:notes_current_color,
    }

    if(extract_notes_email != null && extract_notes_email!= '') {
        form_data['tag_emails'] = extract_notes_email.join(',');
    }

    $.ajax({
        url: save_project_notes,
        type: "POST",
        data: form_data,
        dataType: 'json',
        cache: false,
        beforeSend:function(data) {
            $('.notes_loader').show();
            $('#proj_notes_loader').show();
        },
        success: function (data) {
            console.log(data)
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
                $("#add_notes").modal('hide');
                getAllProjectNotes();
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete:function(data) {
            $('.notes_loader').hide();
            $('#proj_notes_loader').hide();
        },  
        failure: function (errMsg) {
            $('.notes_loader').hide();
            $('#proj_notes_loader').hide();
            console.log(errMsg);
        }
    });

}

function getAllProjectNotes() {
  
    $.ajax({
        type: "GET",
        url: get_project_notes + '/' + project_id,
        dataType: 'json',
        beforeSend:function(data) {
            $('.notes_loader').show();
        },
        success: function(data) {
            console.log(data , "notes");
            var obj = data.notes;
            var html = '';
            var image = '';
            var name = '';
            obj.forEach(element => {
                var note = element.note != null && element.note != "" ? element.note.replace(/"|'/g,'') : '-';
                if(element.created_by != null && element.created_by != "") {

                    name = element.created_by.name;
                    
                    if(element.created_by.profile_pic != null) {
                        image = `<img style="width: 80px;
                        border-radius: 50%;
                        padding: 8px 10px;
                        height: 73px;" src='`+ user_photo_path +'/' + element.created_by.profile_pic +`'/>`;
                    }else{
                        image = `<i style="margin: 19px; padding: 8px 9px;" class="fas fa-times-circle"></i>`;
                    }

                }
                
                html += `
                    <div class="d-flex row justify-content-between mt-2 p-2 card_shadow" style="background-color:`+element.color+`">
                        <div class="col-md-1">`+ (element.created_by.profile_pic != null && element.created_by.profile_pic != "" ? image : ' ')+` </div>
                        <div class="col-md-9">
                            <div class="row text-left">
                                
                                <div class="col-12 mt-2">
                                    Note by <strong>`+(element.created_by.name != null && element.created_by.name != "" ? name : '-')+` `+moment(element.created_at).format(system_date_format + ' hh:mm:ss') +`</strong> <br>
                                    `+ note +`
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <button  title="Edit Note" class="btn btn-success btn-circle" onclick="editProjectNotes(` + element.id + `,'`+element.color+`','`+note+`');">
                                <i class="mdi mdi-grease-pencil" aria-hidden="true"></i>
                            </button>
                    
                            <button class="btn btn-danger btn-circle" title="Delete Note" onclick="deleteProjectNotes(` + element.id + `);">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                            </button>

                        </div>
                    </div>
                `;


            });
            $("#project_notes").html(html);

        },
        complete:function(data) {
            $('.notes_loader').hide();
        },
        error: function(f) {
            console.log('get assets error ', f);
            $('.notes_loader').hide();
        }
    });

}

function editProjectNotes(id,color,note) {
    $('#edit_notes').modal('show');
    notes_current_color = color;
    $("#note_id").val(id);
    $("#edit_note_field").val(note);
    $("#edit_note_field").attr('style','background-color:'+color);

}

function deleteProjectNotes(id) {

    $.ajax({
        url: del_project_notes,
        type: "POST",
        data: { id: id},
        dataType: 'json',
        beforeSend:function(data) {
            $('.notes_loader').show();
        },
        success: function (data) {
            console.log(data)
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
                getAllProjectNotes();
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete:function(data) {
            $('.notes_loader').hide();
        },  
        failure: function (errMsg) {
            $('.notes_loader').hide();
            console.log(errMsg);
        }
    });

}

function updateProjectNotes() {

    var note_id = $("#note_id").val();
    var edit_note_field = $("#edit_note_field").val();
    let extract_notes_email = edit_note_field.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);

    var form_data = {
        id: note_id,
        note: edit_note_field,
        color:notes_current_color,
    }

    if(extract_notes_email != null && extract_notes_email!= '') {
        form_data['tag_emails'] = extract_notes_email.join(',');
    }

    $.ajax({
        url: update_project_notes,
        type: "POST",
        data: form_data,
        dataType: 'json',
        cache: false,
        beforeSend:function(data) {
            $('.notes_loader').show();
            $('#update_proj_notes_loader').show();
        },
        success: function (data) {
            console.log(data)
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
                $("#edit_notes").modal('hide');
                getAllProjectNotes();
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete:function(data) {
            $('.notes_loader').hide();
            $('#update_proj_notes_loader').hide();
        },  
        failure: function (errMsg) {
            $('.notes_loader').hide();
            $('#update_proj_notes_loader').hide();
            console.log(errMsg);
        }
    });

}

function getAllProjectActivityLogs() {
  
    $.ajax({
        type: "GET",
        url: get_activity_logs,
        dataType: 'json',
        beforeSend:function(data) {
            $('.activity_loader').show();
        },
        success: function(data) {
            console.log(data , "logs");
            var html = '';
            var image = '';
            var type = ``;
            var obj = data.logs;
            obj.forEach(element => {

                type = `<small id="name13" class="badge badge-default badge-danger form-text text-white"> `+ element.type +` </small>`;

                html += `
                    <div class="row" >
                        <div class="col-md-9">
                            <div style="margin-left:50px;border-left:3px solid #E2E2E2;">
                            <small id="name13" class="badge badge-default badge-danger form-text text-white"> `+ moment(element.created_at).format(system_date_format) +` </small>
                                <div class="icon">
                                    <small ><i class="fa fa-envelope" ></i></small>
                                </div>
                                <div class="pt-3 pl-5">
                                    <h4 class="p-2">
                                        Action By: `+element.created_by.name+` @ `+ moment(element.created_at).format(system_date_format) +`
                                    </h4>
                                    <hr class="m-0"/>
                                    <p class="p-2">
                                       `+element.action_perform+`
                                    </p>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-3">
                            <p class="pt-3 text-grey"><i class="far fa-clock"></i> `+ moment(element.created_at).format('h:mm:ss') +`</p>
                        </div>
                        <hr style="width:65%;">
                    </div>
                `;


            });

            $("#show_activity_logs").html(html);

        },
        complete:function(data) {
            $('.activity_loader').hide();
        },
        error: function(f) {
            console.log('get assets error ', f);
            $('.activity_loader').hide();
        }
    });

}

function noteClose() {
    $("#note").val(" ");
}

function closeAsset() {
    $("#form_id").val("").trigger('change');
}

function listAttachments(){
    $('#attachmentsModal').modal('show');
}

function loadFile(ele){
    $(ele).attr('onchange', 'replaceFile(\''+ele.id+'\', '+(g_dropi_index-1)+')');

    $('#attachmentsModal').find('.modal-body').append(`<div class="col-4 mb-3 attchment_body">
        <input type="file" id="dropi-`+g_dropi_index+`" data-show-errors="true" onchange="loadFile(this);" data-max-file-size="2M"/>
    </div>`);

    reDropify(g_dropi_index);

    g_dropi_index++;

    let files = $(ele).prop('files');

    g_attachments.push(files[0]);

    console.log(g_attachments , "g_attachments");
}

function reDropify(indx, editingTask) {
    $('#dropi-'+indx).dropify().on('dropify.beforeClear', function(event, element){
        if(editingTask){
            g_attachments_delete.push(tasks[g_clickedTask].task_attachments[indx-1].attachment);
            console.log(g_attachments_delete);
            $('#dropi-'+indx).closest('.col-4').remove();
        }else{
            for(let i in g_attachments){
                if(g_attachments[i].name == event.target.files[0].name){
                    g_attachments.splice(i, 1);
                    break;
                }
            }
        }
    });
}

function replaceFile(eleId, ind){
    let files = $('#'+eleId).prop('files');

    g_attachments[ind] = files[0];
}

function checkTaskStatus(ele){
    if($(ele).val() == 'success'){
        $('#completion_time').closest('.form-group').show();
        $('#completed_at').closest('.form-group').show();
        $('#completed_at').attr('required', true);
    }else{
        $('#completion_time').closest('.form-group').hide();
        $('#completed_at').closest('.form-group').hide();
        $('#completed_at').attr('required', false);
    }
}

function listTasks(tasks_pr=tasks_flt_list) {
    tasks_table_list.clear().draw();

    tasks_pr = tasks_pr.sort((a,b) => (a.sort_id > b.sort_id) ? 1 : ((b.sort_id > a.sort_id) ? -1 : 0));
    
    tasks_pr.forEach(full => {
        // console.log(full.id+' '+full.sort_id);
        let title = full.title != null ? full.title.substr(0, 50) + '...' : '---';
        title = `<a href="` + task_detail + `/` + full.id + `">` + title + `</a>`;
        if (full.is_overdue == 1 && full.task_status != "success") {
            title += `<br><span class="badge bg-danger text-white">overdue</span>`;
        }

        let cr_name = '---';
        if (full.task_creator && full.task_creator.hasOwnProperty('name') && full.task_creator.name) cr_name = full.task_creator.name;
        let as_name = '---';
        if (full.task_assigned_to && full.task_assigned_to.hasOwnProperty('name') && full.task_assigned_to.name) as_name = full.task_assigned_to.name;

        let status_class = '';
        let task_priority = full.task_priority ? full.task_priority : '---';
        if (full.task_priority == "Low") status_class = "badge text-info badge-light-info";
        else if (full.task_priority == "Normal") status_class = "text-warning small badge-light-warning";
        else if (full.task_priority == "Urgent") status_class = "badge text-danger badge-light-danger";
        else if (full.task_priority == "High") status_class = "badge text-megna badge-light-megna";

        let t_st = "<span class='badge small badge-pill bg-success text-white align-middle'>Completed</span>";
        if (full.task_status == "danger") t_st = "<span class='badge small badge-pill bg-danger text-white align-middle'>Pending</span>";
        else if (full.task_status == "default") t_st = "<span class='badge small badge-pill bg-warning text-white'>Work in Progress</span>";

        tasks_table_list.row.add([
            full.sort_id,
            full.id,
            title,
            cr_name,
            full.version,
            as_name,
            full.created_at ? moment(full.created_at).format(system_date_format) : '---',
            full.due_date ? moment(full.due_date).format(system_date_format) : '---',
            `<span class="` + status_class + `">` + task_priority + `</span>`,
            t_st,
            `<button onclick="editTask(` + full.id + `)" class="btn btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
            <button onclick="deleteTask(` + full.id + `)" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></button>`
        ]);
        // ]).draw(false).node().id = 'tr-' + full.id;
    });

    tasks_table_list.order([[0, 'asc']]).draw(false);

    // $("#project_all_tasks").DataTable().destroy();
    // if ( $.fn.DataTable.isDataTable('#project_all_tasks') ) {
    //     $('#project_all_tasks').DataTable().destroy();
    // }

    // $('#project_all_tasks tbody').empty();
    // $.fn.dataTable.ext.errMode = "none";

    // if(tasks_pr.length && !tasks_pr[0].hasOwnProperty('sort_id')) {
    //     tasks_pr.forEach((itm, index) => {
    //         itm.sort_id = index+1;
    //     });
    // }

    // tasks_pr.sort((a,b) => (a.sort_id >  b.sort_id) ? 1 : ((b.sort_id > a.sort_id) ? -1 : 0));

    // console.log('tasks_pr', tasks_pr);

    // tasks_table_list = $("#project_all_tasks").DataTable({
    //     // processing: true,
    //     // serverSide: true,
    //     searching: true,
    //     pageLength: 10,
    //     data: tasks_pr,
    //     columnDefs: [
    //         {
    //             orderable: false,
    //             targets: 0
    //         }
    //     ],
    //     createdRow: function(row, data, dataIndex){
    //         $(row).attr('id', 'row-' + data.id);
    //     },
    //     rowReorder: {
    //         dataSrc: 'sort_id',
    //         selector: 'tr'
    //     },
    //     order: [[0, 'asc']],
    //     columns: [
    //         {
    //             data: "sort_id"
    //         },{
    //             data: "id"
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 let title = full.title != null ? full.title.substr(0, 50) + '...' : '---';
    //                 title = `<a href="` + task_detail + `/` + full.id + `">` + title + `</a>`;
    //                 if (full.is_overdue == 1 && full.task_status != "success") {
    //                     title += `<br><span class="badge bg-danger text-white">overdue</span>`;
    //                 }
    //                 return title;
    //             }
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 let cr_name = '---';
    //                 if (full.task_creator && full.task_creator.hasOwnProperty('name') && full.task_creator.name) cr_name = full.task_creator.name;
    //                 return cr_name;
    //             }
    //         },{
    //             data: "version"
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 let as_name = '---';
    //                 if (full.task_assigned_to && full.task_assigned_to.hasOwnProperty('name') && full.task_assigned_to.name) as_name = full.task_assigned_to.name;
    //                 return as_name;
    //             }
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 return full.created_at ? moment(full.created_at).format(system_date_format) : '---';
    //             }
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 return full.due_date ? moment(full.due_date).format(system_date_format) : '---';
    //             }
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 let status_class = '';
    //                 let task_priority = full.task_priority ? full.task_priority : '---';
    //                 if (full.task_priority == "Low") status_class = "badge text-info badge-light-info";
    //                 else if (full.task_priority == "Normal") status_class = "text-warning small badge-light-warning";
    //                 else if (full.task_priority == "Urgent") status_class = "badge text-danger badge-light-danger";
    //                 else if (full.task_priority == "High") status_class = "badge text-megna badge-light-megna";
                    
    //                 return `<span class="` + status_class + `">` + task_priority + `</span>`;
    //             }
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 let t_st = "<span class='badge small badge-pill bg-success text-white align-middle'>Completed</span>";
    //                 if (full.task_status == "danger") t_st = "<span class='badge small badge-pill bg-danger text-white align-middle'>Pending</span>";
    //                 else if (full.task_status == "default") t_st = "<span class='badge small badge-pill bg-warning text-white'>Work in Progress</span>";

    //                 return t_st;
    //             }
    //         },{
    //             "render": function(data, type, full, meta) {
    //                 return `<button onclick="editTask(` + full.id + `)" class="btn btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button><button onclick="deleteTask(` + full.id + `)" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></button>`;
    //             }
    //         }
    //     ]
    // });

    // tasks_table_list.on( 'row-reorder', function ( e, diff, edit ) {
    //     console.log({e, diff, edit})
    //     for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
    //         // get id row
    //         let idQ = diff[i].node.id;
    //         let idNewQ = idQ.replace("row-", "");
    //         console.log('Old = '+idQ+' / '+'New = '+idNewQ);
    //         // get position
    //         let position = diff[i].newPosition+1;
    //         //call funnction to update data
    //         // updateOrder(idNewQ, position);
    //         let obj = tasks_pr.map(function(item) { return parseInt(item.id)}).indexOf(parseInt(idNewQ));
            
    //         if(obj > -1) tasks_pr[obj].sort_id = position;
    //     }

    //     console.log(tasks_pr);

    //     applyTasksFilter();
    // } );
}

function applyTasksFilter() {
    let std = $('#start-date').val();
    let end = $('#end-date').val();
    let st = $('#selc-st').val();
    let ver = $('#selc-ver').val();

    let tasks = tasks_list;
    if(std) tasks = tasks.filter(item => new Date(item.created_at) > new Date(std));
    if(end) tasks = tasks.filter(item => item.due_date && new Date(item.due_date) < new Date(end));
    if(st) tasks = tasks.filter(item => item.task_status == st);
    if(ver) tasks = tasks.filter(item => item.version == ver);

    tasks_flt_list = tasks;
    listTasks(tasks);
}
</script>