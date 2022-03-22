<script>
    const project_id = $("#project_id").val();
    const project_slug = $("#project_slug").val();
    let tasks_arr = [];
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        // getting tasks
        project_details.getTasks();



    });
    function openTaskModal() {
        $("#new-task-modal").modal('show');
        $("#task_id").val("");
        $(".project_btn").text("Save");
    }

    const project_details = {


        getTasks() {

            $.ajax({
                type: "GET",
                url: get_all_tasks + '/' + project_id,
                success: function(data) {
                    let obj = data.data;
                    console.log(obj, "data")
                    tasks_arr = data.data;
                    let task_data = ``;
                    let status = ``;
                    if (obj.length > 0) {

                        for (const data of obj) {

                            if (data.task_status == 'success') {
                                status = `<span class="badge rounded-pill badge-light-success"> ${data.task_status} </span>`;
                            }

                            if (data.task_status == 'danger') {
                                status = `<span class="badge rounded-pill badge-light-danger"> ${data.task_status} </span>`;
                            }

                            if (data.task_status == 'default') {
                                status = `<span class="badge rounded-pill badge-light-warning"> ${data.task_status} </span>`;
                            }


                            task_data += `
                                <li class="todo-item" onclick="project_details.showTaskDetail(${data.id})">
                                    <div class="todo-title-wrapper">
                                        <div class="todo-title-area">
                                            <i data-feather="more-vertical" class="drag-icon"></i>
                                            <div class="title-wrapper">
                                                <span class="todo-title">
                                                    <a href="javascript:void(0)"> ${data.title != null ? data.title : '-'}  </a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="todo-item-action">
                                            <div class="badge-wrapper me-1">
                                                ${status}
                                            </div>
                                            <small class="text-nowrap text-muted me-1"> ${moment(data.created_at).format('MMM DD')}</small>
                                            <div class="avatar">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-4.jpg" alt="user-avatar" height="32" width="32" />
                                            </div>
                                        </div>
                                    </div>
                                </li>`
                        }

                        $('.show_project_tasks').html(task_data);


                    } else {
                        $('.show_project_tasks').html('<spa class="text-danger"> No Tasks </spa>');
                    }

                },
                complete: function(data) {
                    console.log('Success', data);
                },
                error: function(data) {
                    console.log('Error', data);
                }
            });

        },

        showTaskDetail : (id) => {
            $("#task_id").val(id);
            $("#new-task-modal").modal('show');
            $(".project_btn").text("Update");

            let item = tasks_arr.find( item => item.id == id);
            
            if(item != null) {

                $("#version").val(item.version != null ? item.version : '');
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
                    console.log(time);

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

            }
        }

    }

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

                    project_details.getTasks();

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
</script>