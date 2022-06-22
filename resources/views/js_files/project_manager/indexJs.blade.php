<script>
// Project Manger Script Blade


    // $("#tsk_tble").DataTable();
    let users =  {!! json_encode($users) !!};
    let projects =  {!! json_encode($projects) !!};
    let folders =  {!! json_encode($projectsfolder) !!};
    var free_staff =  {!! json_encode($free_staff) !!};
    let acting_id = -1;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

     $(document).ready(function() {

        setTimeout(() => {
            $("#folder_card").hide();
            $("#tsk_card").hide();
        }, 2000);

        $('.collapsed-back').click(function(){
            if($(this).parent().hasClass('dd-collapsed')){
                $(this).parent().removeClass('dd-collapsed');
                $(this).prev().prev().css('display','block');
                $(this).prev().css('display','none');
                
            }else{
                $(this).parent().addClass('dd-collapsed');
                $(this).prev().css('display','block');
                $(this).prev().prev().css('display','none');
            }
        });

        let date = new Date();
        let from = moment(date).format('YYYY-MM-DD');
        let to = moment(date).format('YYYY-MM-DD');
        
        let user_id = $("#users").val();

        getAllTasks(from,to,user_id);
    });
    
    function showFolderModel( ) {
        $("#save_folder").trigger("reset");
        $("#fld_id").attr("readonly" , true);

        $("#myModalLabel1").text('Add Folder');
        $('#add-folders').modal('show');
    }

    function editFolder(id){
        $("#fld_id").removeAttr("readonly" , true);
        $("#fld_id").val(id);
        $("#myModalLabel1").text( 'Edit Folder' );

        let item = folders.find(item => item.id == id);
        console.log(item , "item");
        if(item != null) {
            $("#title").val( item.name != null ? item.name : '');
        }
        
        
        $('#add-folders').modal('show');
    }

    $("#save_folder").submit(function (event) {
        event.preventDefault();

        var formData = new FormData(this);
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
            beforeSend: function (data) {
                $("#fldr_btn").css('display','none');
                $("#process_btn").removeAttr('style');
            },
            success: function (data) {
                console.log(data);
                if (data['success'] == true) {

                    $("#save_folder").trigger("reset");
                    alertNotification('success', 'Success' , data['message'] );

                    if(acting_id != -1){
                        $('#nestable-menu').find('li[data-id="'+acting_id+'"]').find('.dd-handle').find('label').html(data.result.name);
                        acting_id = -1;
                    }else{
                        $('#nestable-menu').first('ol').append(`<li class="dd-item dd-collapsed" data-id="`+data.result.id+`">
                            <a class="dd-handle collapsed-back">
                                <label class="m-0">`+data.result.name+`</label>
                                <div class="float-right">
                                    <i class="fas fa-edit" onclick="editFolder(this);"></i>
                                    <i class="fas fa-trash text-danger" onclick="removeFolder(${data.result.id})"></i>
                                </div>
                            </a>
                        </li>`);
                    }
                } else {
                    alertNotification('success', 'Success' , data['message'] );
                }
            },
            complete: function (data) {
                setTimeout(() => {
                    $("#fldr_btn").css('display','block');
                    $("#process_btn").attr('style','display:none');    
                    $('#add-folders').modal('hide');
                    // location.reload();
                },1000);
            },
            failure: function (errMsg) {
                setTimeout(() => {
                    $("#fldr_btn").css('display','block');
                    $("#process_btn").attr('style','display:none');    
                    $('#add-folders').modal('hide');
                },1000);
            },
        });

    });

    function removeFolder(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "All data related to this project folder will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{asset('delete-folder')}}",
                    type: "POST",
                    data: { id:id},
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        console.log(data)
                        if (data.success==true) {
                            alertNotification('success', 'Success' , data['message'] );
                            $("#main_folder_"+ id).remove();
                        } else {
                            alertNotification('error', 'Error' , data['message'] );
                        }
                    },
                    failure: function (errMsg) {
                        console.log(errMsg);
                    }
                });
            }
        });
    }

    function showProjectModel() {
        $("#new-project-add").trigger("reset");
        $('#folder_id').val('').trigger("change");
        $('#project_type').val('').trigger("change");
        $('#new-project-add').find('h2').html('Add Project');
        $('#new-project-modal').modal('show');

        $("#pro_id").val("");
    }

    function editProject(id){

        $('#new-project-add').find('h2').html('Update Project');
        $("#pro_id").val(id);
        
        let item = projects.find(item => item.id == id);
        console.log(item , "item");

        if(item != null) {
            $("#project_name").val(item.name);
            $("#folder_id").val(item.folder_id).trigger("change");
            $("#project_type").val(item.project_type).trigger("change");
        }
        

        $('#new-project-modal').modal('show');

    }

    $("#new-project-add").submit(function (event) {
        event.preventDefault();

        var formData = new FormData(this);
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
            dataType:'json',
            beforeSend:function(data) {
                $("#done_btn").css('display','none');
                $("#proces_btn").removeAttr('style');
            },
            success: function (data) {
                console.log(data);
                if (data['success'] == true) {

 		            $('#folder_id').val('').trigger("change");
        	        $('#project_type').val('').trigger("change");
		            $("#new-project-add").trigger("reset");
                    alertNotification('success', 'Success' , data['message'] );

                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2500
                    })
                }
            },
            complete:function(data) {
                setTimeout(() => {
                    $("#done_btn").css('display','block');
                    $("#proces_btn").attr('style','display:none');    
                    $('#new-project-modal').modal('hide');
                    location.reload();
                },1000);
                
            },error:function(e) {
                console.log(e);
                setTimeout(() => {
                    $("#done_btn").css('display','block');
                    $("#proces_btn").attr('style','display:none');    
                    $('#new-project-modal').modal('hide');
                    location.reload();
                },1000);
            }
        });

    });

    function removeProject(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "All data related to this project folder will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{asset('delete-project')}}",
                    type: "POST",
                    data: { id:id },
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        console.log(data)
                        if (data.success==true) {
                            alertNotification('success', 'Success' , data['message'] );
                            $("#project_"+ id).remove();
                        } else {
                            alertNotification('error', 'Error' , data['message'] );
                        }
                    },
                    failure: function (errMsg) {
                        console.log(errMsg);
                    }
                });
            }
        });
    }
    
    function getAllTasks(from,to,user_id) {
        $.ajax({
            type: "POST",
            url: "{{url('get_tasks_by_date')}}",
            dataType:'json',
            data: {from:from,to:to,user_id,user_id},
            beforeSend:function(data) {
                $("#all_tsks").show();
            },
            success: function(data) {
                console.log(data, "all tasks");
                var obj = data.data;
                var system_date_format = data.date_format;
                var pending_arr = [];
                var working_arr = [];
                var completed_arr = [];
                var overdue_arr = [];
                var current_date = new Date();


                obj.forEach(element => {
                    var task_due_date = new Date(element.due_date);

                    if(element.task_status == 'danger') {
                        pending_arr.push(element);
                    }
                    if(element.task_status == 'default') {
                        working_arr.push(element);
                    }
                    if(element.task_status == 'success') {
                        completed_arr.push(element);
                    }

                    if( element.worked_time > HmsToSeconds(element.estimated_time + ":00") || task_due_date < current_date) {
                        overdue_arr.push(element);
                        element["overdue_tasks"] = 'overdue';
                    }
                });

                $("#all_counts").text(obj.length);
                $("#pending_counts").text(pending_arr.length);
                $("#completed_counts").text(completed_arr.length);
                $("#working_tasks").text(working_arr.length);

                $("#overdue_tasks").text(overdue_arr.length);

                $("#tsk_tble").DataTable().destroy();
                $.fn.dataTable.ext.errMode = "none";
                var tbl = $("#tsk_tble").DataTable({
                    data: obj,
                    "pageLength":10,
                    "bInfo": false,
                    "paging": true,
                    "searching" : true,
                    dom: 'Bfrtip',
                    buttons: [
                        { extend: 'copy', className: 'btn btn-light' },
                        { extend: 'excel', className: 'btn btn-success' },
                        { extend: 'pdf', className: 'btn btn-danger' },
                    ],
                    columns: [
                        {
                            "data": null,
                            "defaultContent": ""
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<a href="{{asset('task_details')}}/`+full.id+`">`+full.id+`</a>`;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<a href="{{asset('task_details')}}/`+full.id+`">`+full.title+`</a>`;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.task_project != null ? full.task_project.name : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.version;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.estimated_time != null ? full.estimated_time : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.task_creator != null ? full.task_creator.name : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if(full.task_assigned_to != null) {
                                    return full.task_assigned_to.name != null ? full.task_assigned_to.name : '-';    
                                }
                            }
                        },
                        {
                            "className": "hide",
                            "render": function(data, type, full, meta) {
                                if(full.task_status == "danger") {
                                    return "Pending";
                                }else if(full.task_status == "default") {
                                    return "Work in Progress";
                                }else{
                                    return "Completed";
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if(full.task_status == "danger") {
                                    return "<span class='badge bg-danger text-white'>Pending</span>";
                                }else if(full.task_status == "default") {
                                    return "<span class='badge bg-warning text-white'>Work in Progress</span>";
                                }else{
                                    return "<span class='badge bg-success text-white'>Completed</span>";
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return secondsToHMS(full.worked_time);
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.created_at != null ? moment(full.created_at).format(system_date_format) : '-';
                            }
                        },
                        {
                            "className": "hide",
                            "render": function(data, type, full, meta) {
                                return full.overdue_tasks != null ? full.overdue_tasks: '-';
                            }
                        },
                        {
                            "className": "hide",
                            "render": function(data, type, full, meta) {
                                return full.assign_to;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                let status_class = '';
                                let task_priority =  full.task_priority != null ? full.task_priority : '-';

                                if(full.task_priority == "Low") {

                                    status_class = "badge text-info badge-light-info";

                                }else if(full.task_priority == "Normal") {

                                    status_class = "text-warning small badge-light-warning";

                                }else if(full.task_priority == "Urgent") {

                                    status_class = "badge text-danger badge-light-danger";

                                }else if(full.task_priority == "High") {

                                    status_class = "badge text-megna badge-light-megna";

                                }
                                return `<span class="`+status_class+`">`+task_priority+`</span>`;
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


                $("#users").on('change', function () {
                    tbl.column(13).search($(this).val()).draw();
                });
                $("#projects").on('change', function () {
                    tbl.column(3).search($(this).val()).draw();
                });

                $("#all_card").on('click', function() {
                    let all = ' ';
                    tbl.column(8).search(all).draw();
                })

                $("#pending_card").on('click', function() {
                    let pending = 'Pending';
                    tbl.column(8).search(pending).draw();
                });

                $("#completed_card").on('click', function() {
                    let completed = 'Completed';
                    tbl.column(8).search(completed).draw();
                })

                $("#working_card").on('click', function() {
                    let wip = 'Work in Progress';
                    tbl.column(8).search(wip).draw();
                })

            },
            complete:function(data) {
                $("#all_tsks").hide();
                
            }, 
            error: function(e) {
                console.log(e);
            }
        });
    }

    function overDueTasks() {
        $.ajax({
            type: "POST",
            url: "{{url('get_overdue_tasks')}}",
            dataType:'json',
            beforeSend:function(data) {
                $(".loader_container").show();
            },
            success: function(data) {
                console.log(data, "all tasks");
                var obj = data.data;
                var system_date_format = data.date_format;

                console.log(obj , "obj");
               
                $("#tsk_tble").DataTable().destroy();
                $.fn.dataTable.ext.errMode = "none";
                var tbl = $("#tsk_tble").DataTable({
                    data: obj,
                    "pageLength":10,
                    "bInfo": false,
                    "paging": true,
                    "searching" : true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ],
                    columns: [
                        {
                            "data": null,
                            "defaultContent": ""
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<a href="{{asset('task_details')}}/`+full.id+`">`+full.id+`</a>`;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<a href="{{asset('task_details')}}/`+full.id+`">`+full.title+`</a>`;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.task_project != null ? full.task_project.name : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.version;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.estimated_time != null ? full.estimated_time : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.task_creator != null ? full.task_creator.name : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if(full.task_assigned_to != null) {
                                    return full.task_assigned_to.name != null ? full.task_assigned_to.name : '-';    
                                }
                            }
                        },
                        {
                            "className": "hide",
                            "render": function(data, type, full, meta) {
                                if(full.task_status == "danger") {
                                    return "Pending";
                                }else if(full.task_status == "default") {
                                    return "Work in Progress";
                                }else{
                                    return "Completed";
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if(full.task_status == "danger") {
                                    return "<span class='badge bg-danger text-white'>Pending</span>";
                                }else if(full.task_status == "default") {
                                    return "<span class='badge bg-warning text-white'>Work in Progress</span>";
                                }else{
                                    return "<span class='badge bg-success text-white'>Completed</span>";
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return secondsToHMS(full.worked_time);
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.created_at != null ? moment(full.created_at).format(system_date_format) : '-';
                            }
                        },
                        {
                            "className": "hide",
                            "render": function(data, type, full, meta) {
                                return full.overdue_tasks != null ? full.overdue_tasks: '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                
                                let status_class = '';
                                let task_priority =  full.task_priority != null ? full.task_priority : '-';

                                if(full.task_priority == "Low") {

                                    status_class = "badge text-info badge-light-info";

                                }else if(full.task_priority == "Normal") {

                                    status_class = "text-warning small badge-light-warning";

                                }else if(full.task_priority == "Urgent") {

                                    status_class = "badge text-danger badge-light-danger";

                                }else if(full.task_priority == "High") {

                                    status_class = "badge text-megna badge-light-megna";

                                }
                                return `<span class="`+status_class+`">`+task_priority+`</span>`;
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

            },
            complete:function(data) {
                $(".loader_container").hide();
            }, 
            error: function(e) {
                console.log(e);
            }
        });
    }

    function getFreeStaffDetail() {

        $("#free_staff_loader").show();
        let obj = [];
        
        console.log(free_staff);

        free_staff.forEach(element => {
            // if(element.free_staff_tasks == 0 && element.user_type == 3) {
                obj.push(element);
            // }
        });

        $("#free_staff_tble").DataTable().destroy();
        $.fn.dataTable.ext.errMode = "none";
        var tbl = $("#free_staff_tble").DataTable({
            data: obj,
            "pageLength":10,
            "bInfo": false,
            "paging": true,
            "searching" : true,
            columns: [
                {
                    "data": null,
                    "defaultContent": ""
                },
                {
                    "render": function(data, type, full, meta) {
                        return `<a href="{{url('profile')}}/`+full.id+`">`+ (full.name != null ? full.name : '-') +`</a>`;
                    }
                },
                {
                    "render": function(data, type, full, meta) {
                        return full.email != null ? full.email : '-';
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

        setTimeout(() => {
            $("#free_staff_loader").hide();
        }, 2000);

    }


    function filterData(value) {
        var today = new Date();
        switch (value) {
            case "today":
                let date = new Date();
                let from = moment(date).format('YYYY-MM-DD');
                let to = moment(date).format('YYYY-MM-DD');
                
                getAllTasks(from,to);
                $("#daterangediv").css("display", "none");
                break;
            case "date_range":
                $("#daterangediv").css("display", "block");
                break;
        }
    }


    function secondsToHMS(totalSeconds) {
        var hours   = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
        var seconds = totalSeconds - (hours * 3600) - (minutes * 60);

        // round seconds
        seconds = Math.round(seconds * 100) / 100

        var result = (hours < 10 ? "0" + hours : hours);
            result += ":" + (minutes < 10 ? "0" + minutes : minutes);
            result += ":" + (seconds  < 10 ? "0" + seconds : seconds);
        return result;
    }

    function HmsToSeconds(hms) {
        // var hms = '02:04:33';
        var a = hms.split(':'); // split it at the colons

        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
        return seconds;
    }

    function getTaskResults() {
        var from = $("#from").val();
        var to = $("#to").val();
        let user_id = $("#users").val();

        if(from == '' || to == '') {
            alertNotification('error', 'Error' , 'Please select the date' );
        }else{
            getAllTasks(from,to,user_id);

        }


    }

</script>