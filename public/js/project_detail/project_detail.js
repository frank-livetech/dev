var project_slug = $("#project_slug").val();
var project_id = $('#project_id').val();
var notes_current_color = '';
var system_date_format = $("#system_date_format").val();
$(document).ready(function() {


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

    getAllProjectTasks();

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
                    position: 'top-end',
                    icon: (data.success) ? 'success' : 'error',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: 2500
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
        var tsk_desc = tinymce.get('tsk_desc').getContent();

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
            beforeSend: function(data) {},
            success: function(data) {
                console.log(data);
                $("#save-task")[0].reset();
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $('.newTask').hide('1000');
                    getAllProjectTasks();
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
                // g_attachments = [];
                // g_attachments_delete = [];
            },
            complete: function(data) {},
            error: function(e) {
                console.log(e)
            }

        });
    })

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

function getAllProjectTasks() {

    var project_id = $("#project_id").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: "GET",
        url: get_all_tasks + '/' + project_id,
        dataType: 'json',
        beforeSend: function(data) {
            $("#project_all_tasks_loader").show();
        },
        success: function(data) {
            console.log(data, "task list");
            var obj = data.data;

            $("#project_all_tasks").DataTable().destroy();
            $.fn.dataTable.ext.errMode = "none";
            $("#project_all_tasks").DataTable({
                data: obj,
                pageLength: 10,
                bInfo: true,
                paging: true,
                "order": [
                    [0, "desc"]
                ],
                columns: [{
                        "render": function(data, type, full, meta) {
                            return full.id;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            // let current_date = new Date();
                            // current_date = moment(current_date).format('Y-MM-DD');
                            // let due_date = full.due_date != null ? full.due_date : '--';
                            let title = full.title != null ? full.title.substr(0, 50) + '...' : '-';
                            let overdue = `<span class="badge bg-danger text-white">overdue</span>`;

                            let task_title = `<a href="` + task_detail + `/` + full.id + `">` + title + `</a>`;

                            if (full.is_overdue == 1 && full.task_status != "success") {
                                return task_title + `<br>` + overdue;
                            } else {
                                return task_title;
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if (full.task_creator != null && full.task_creator != '') {
                                if (full.task_creator.name != null && full.task_creator.name != '') {
                                    return full.task_creator.name;
                                } else {
                                    return '--'
                                }
                            } else {
                                return '--'
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.version != null ? full.version : '-';
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if (full.task_assigned_to != null && full.task_assigned_to != '') {
                                if (full.task_assigned_to.name != null && full.task_assigned_to.name != '') {
                                    return full.task_assigned_to.name;
                                } else {
                                    return '--'
                                }
                            } else {
                                return '--'
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if (full.created_at != null) {
                                return moment(full.created_at).format(system_date_format);
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if (full.due_date != null) {
                                return moment(full.due_date).format(system_date_format);
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            let status_class = '';
                            let task_priority = full.task_priority != null ? full.task_priority : '-';
                            if (full.task_priority == "Low") {
                                status_class = "badge text-info badge-light-info";
                            } else if (full.task_priority == "Normal") {
                                status_class = "text-warning small badge-light-warning";
                            } else if (full.task_priority == "Urgent") {
                                status_class = "badge text-danger badge-light-danger";
                            } else if (full.task_priority == "High") {
                                status_class = "badge text-megna badge-light-megna";
                            }
                            return `<span class="` + status_class + `">` + task_priority + `</span>`;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if (full.task_status == "danger") {
                                return "<span class='badge small badge-pill bg-danger text-white align-middle'>Pending</span>";

                            } else if (full.task_status == "default") {

                                return "<span class='badge small badge-pill bg-warning text-white'>Work in Progress</span>";

                            } else {
                                return "<span class='badge small badge-pill bg-success text-white align-middle'>Completed</span>";
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                            <button onclick="editTask(` + full.id + `)" class="btn btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="deleteTask(` + full.id + `)" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></button>`;
                        }
                    },
                ]
            });


        },
        complete: function(data) {
            $("#project_all_tasks_loader").hide();
        },
        error: function(e) {
            console.log(e);
            $("#project_all_tasks_loader").hide();
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
        url: states_and_countries,
        dataType: 'json',
        success: function(data) {
            console.log(data , "states_and_countries_list");
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
                        <button title="Edit Type" class="btn btn-success btn-circle" onclick="editAsset(` + full.id + `);">
                            <i class="mdi mdi-grease-pencil" aria-hidden="true"></i>
                        </button>
                    
                        <button class="btn btn-danger btn-circle" title="Delete Asset" onclick="deleteAsset(` + full.id + `);">
                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        </button>
                        `;
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
                            position: 'top-end',
                            icon: 'success',
                            title: 'Asset Deleted!',
                            showConfirmButton: false,
                            timer: 2500
                        })

                        get_asset_table_list();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 2500
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