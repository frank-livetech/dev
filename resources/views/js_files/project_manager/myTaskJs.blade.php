<script>

    // My Task Script Blade
    
    $(document).ready(function(){


    $("#completeTaskForm").submit(function(event) {
        event.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');

        var remarks = $("#remarks").val();
        var task_id = $("#task_id").val();

        if(remarks != null && remarks != "") {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type:method,
                url: url,
                data: {task_status: 'success',task_id:task_id,remarks:remarks},
                dataType:'json',
                beforeSend:function(data) {
                    $(".loader_container").show();
                },
                success: function(data) {
                    console.log(data);
                    if(data.status_code == 200 & data.success == true) {
                        alertNotification('success', 'Success' ,data.message );
                    }else{
                        alertNotification('error', 'Error' ,data.message );
                    }

                    $("#closingRemarks").modal('hide');

                    $("#remarks").val("");

                    get_all_tasks();

                },
                complete:function(data) {
                    $(".loader_container").hide();
                },
                error: function(e) {
                    console.log(e)
                }
            });

        }else{
            alertNotification('error', 'Error' , 'Please Provide Closing Remarks');
        }

       
    });

    get_all_tasks();


    
    });

    function memeber_status_event_time() {
        $(".mb_event_time").each(function (k, v) {
            var stime = $(this).text();
            var ct = stime.split(':');
            s = parseInt(ct[2]);
            m = parseInt(ct[1]);
            h = parseInt(ct[0]);
            var divSpan = $(this).attr("id");
            sSeconds = s + (m * 60) + (h * 3600);

            $("#" + divSpan).timer({
                action: "start",
                seconds: sSeconds
            });
        });
    }

    function get_all_tasks() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "GET",
            url: "{{url('get_all_tasks')}}",
            dataType: 'json',
            beforeSend:function(data) {
                $(".loader_container").show();
            },
            success: function(data) {
                console.log(data , "task list");
                var obj = data.data;

                

                $('#taskTable').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#taskTable').DataTable({
                    data: obj,
                    "pageLength":50,
                    "bInfo": false,
                    "paging": true,
                    createdRow: function (row, data, dataIndex) {
                        $(row).attr('id', data != null ? "row_" + data.id : '---')
                    },
                    columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function (data, type, full, meta) {
                            return `<a href="{{asset('task-details')}}/`+full.id+`">`+full.id+`</a>`;
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {
                            return `<a href="{{asset('task-details')}}/`+full.id+`">`+full.title+`</a>`;
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {
                            if(full.task_project != null ) {
                                return full.task_project.name;
                            }
                        }
                    },
                    {
                        "data" : "version",
                    },
                    {
                        "render": function (data, type, full, meta) {
                            return full.estimated_time != null ? full.estimated_time : '-';
                        }
                    },
                
                    {
                        "render": function (data, type, full, meta) {
                            if(full.task_creator != null ) {
                                return full.task_creator.name;
                            }else{
                                return '---';
                            }
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {
                            // return full.task_status;
                            if(full.task_status == "danger" && full.started_at == null) {
                                return "<span class='badge badge-pill bg-danger text-white align-middle'>Pending</span>";
                            }else if(full.task_status == "default" && full.started_at != null) {
                                return "<span class='badge badge-pill bg-warning text-white'>Work in Progress</span>";
                            }else{
                                return "<span class='badge badge-pill bg-danger text-white align-middle'>Pending</span>";
                            }
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {
                            if(full.task_status == "default") {
                                return full.worked_time != null ? `<span class="badge bg-success mb_event_time text-white badge-pill" style="font-size:0.8rem" id="event_time_active_`+full.id+`">`+secondsToHMS(full.worked_time)+`</span>` : '-';
                            }else{
                                return full.worked_time != null ? `<span class="badge bg-success mb_event_time text-white badge-pill" style="font-size:0.8rem">`+secondsToHMS(full.worked_time)+`</span>` : '-';
                            }
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {

                            // default === >work in Progress
                            // danger =====> pending
                            // success ===== > completed

                            let wrk = `<button onclick="ChangeTaskStatus(`+full.id+`, 'danger')" class="btn btn-primary btn-sm ml-2 roundBtn" title="Work in progress"><i class="fas fa-pause" title=></i></button>`;

                            let pending = `<button onclick="ChangeTaskStatus(`+full.id+`,'default')" class="btn btn-warning btn-sm ml-2 roundBtn text-white" title="pending">
                            <i class="fas fa-play"></i></button>`;

                            let complete_btn = `<button title="complete" onclick="completeTask(`+full.id+`)" class="btn btn-success btn-sm ml-2 roundBtn"><i class="fas fa-check"></i></button>`;

                            if(full.task_status == "danger") {
                                return `<div class="d-flex justify-content-center">
                                        `+pending +  complete_btn +`
                                        </div>`;
                            }else if(full.task_status == "default"){
                                return `<div class="d-flex justify-content-center">
                                        `+wrk + complete_btn +`
                                    </div>`;
                            }else{
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {
                            return full.created_at != null ? moment(full.created_at).format("DD-MM-YYYY h:m:s") : '-';
                        }
                    },
                    ],
                });

                tbl.on('order.dt search.dt', function () {
                    tbl.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();


                for(var i = 0; i < obj.length; i++) {
                    if(obj[i].task_status == "default") {
                        
                        $("#row_"+obj[i].id).css("background","#43a047");
                        $("#row_"+obj[i].id).find('a').css('color','aqua');
                        $(".livetime").each(function() {
                        var wrktime = $(this).text();
                        var a = wrktime.split(':');

                        if(a.length > 1 )  {

                        console.log(a , "a");

                        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 

                        }
                    });
                    }                
                }

            },
            complete:function(data) {
                $(".loader_container").hide();
                memeber_status_event_time();
            },  
            error: function(e) {
                console.log(e)
            }
        });
    }

    function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
    }

    function ChangeTaskStatus(id,status) {

        if(status == "danger") {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                url: "{{url('change-my-task-status')}}",
                data: {task_status: status,task_id:id},
                dataType:'json',
                beforeSend:function(data) {
                    $(".loader_container").show();
                },
                success: function(data) {
                    console.log(data);
                    if(data.status_code == 200 & data.success == true) {
                        alertNotification('success', 'Success' ,data.message );
                    }else{
                        alertNotification('error', 'Error' ,data.message );
                    }
                    get_all_tasks();

                },
                complete:function(data) {
                    $(".loader_container").hide();
                },
                error: function(e) {
                    console.log(e)
                }
            });
        

        }else{


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            url: "{{url('change-my-task-status')}}",
            data: {task_status: status,task_id:id},
            dataType:'json',
            beforeSend:function(data) {
                $(".loader_container").show();
            },
            success: function(data) {
                console.log(data);
                if(data.status_code == 200 & data.success == true) {
                    alertNotification('success', 'Success' ,data.message );
                }else{
                    alertNotification('error', 'Error' ,data.message );
                }
                get_all_tasks();

            },
            complete:function(data) {
                $(".loader_container").hide();
            },
            error: function(e) {
                console.log(e)
            }
        });

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


    function clockUpdate() {
    var date = new Date();
    $('.digital-clock').css({'color': '#fff', 'text-shadow': '0 0 6px #ff0'});
    function addZero(x) {
        if (x < 10) {
        return x = '0' + x;
        } else {
        return x;
        }
    }

    function twelveHour(x) {
        if (x > 12) {
        return x = x - 12;
        } else if (x == 0) {
        return x = 12;
        } else {
        return x;
        }
    }

    var h = addZero(twelveHour(date.getHours()));
    var m = addZero(date.getMinutes());
    var s = addZero(date.getSeconds());

    $('.digital-clock').text(h + ':' + m + ':' + s)
    }


    function completeTask(id) {
        $("#task_id").val(id);
        $("#closingRemarks").modal('show');
    }





</script>
