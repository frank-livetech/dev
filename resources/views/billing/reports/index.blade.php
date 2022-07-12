@extends('layouts.master-layout-new')
@section('Billing', 'open')
@section('title', 'Reports')
@section('Reports', 'active')
@php
$file_path = Session::get('is_live') == 1 ? 'public/' : '/';
$path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp
@section('body')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-fluid p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Reports
                                </li>
                            </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a>Billing</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="javascript:location.reload()">Reports</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content  ">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Recent Activity</h4>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mt-2">
                                                <div class="form-check form-check-inline">
                                                    <!-- <label for="customRadio">From</label> -->
                                                    <input type="radio" id="today" onclick="filterData('today')"
                                                        name="customRadio" class="form-check-input" checked>
                                                    <label class="form-check-label" for="today">Today</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <!-- <label for="customRadio">To</label> -->
                                                    <input type="radio" id="date_range" onclick="filterData('date_range')"
                                                        name="customRadio" class="form-check-input">
                                                    <label class="form-check-label" for="date_range">Date Range</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">

                                                <label class="form-label" for="">Staff</label>
                                                <select class="select2 form-control custom-select dropdown w-100"
                                                    id="staff" name="staff" style="width:100%">
                                                    <option value="">Select</option>

                                                    @if($staffs != null && $staffs != "")
                                                        @foreach($staffs as $key => $staff)
                                                        <option value="{{$staff->id}}" >{{$staff->name ?? ''}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="mt-1" id="ifYes" style="display: none;">
                                                    <input type="text" id="basic-icon-default-uname"
                                                        class="form-control dt-uname" placeholder="" aria-label="jdoe1"
                                                        aria-describedby="basic-icon-default-uname2" name="user-name" />

                                                </div>

                                            </div>
                                        </div>


                                        <div id="daterangediv" style="display:none">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label>Start Date & Time</label>
                                                    <input type="datetime-local" id="start_date" class="form-control">
                                                </div>
                                                <div class="col-md-5">
                                                    <label>End Date & Time</label>
                                                    <input type="datetime-local" id="end_date" class="form-control">
                                                </div>
                                                <div class="col-md-2">
                                                    <button onclick="getStaffDateWise()"
                                                        class="btn waves-effect waves-light mt-2 btn-success"
                                                        type="button">Search</button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="table-responsive">
                                        <table id="ticket-logs-list"
                                            class="table table-striped table-bordered no-wrap ticket-table-list w-100">
                                            <thead>
                                                <tr>
                                                    <!-- <th width="20">ID</th> -->
                                                    <th>Activity</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('scripts')
    <script>
        ticketLogs()

        $("#staff").select2()

        let ticketsList = [];
        let time_zone = "{{Session::get('timezone')}}";

        var tickets_logs_list = $('#ticket-logs-list').DataTable({
                ordering: false,
            });


        function ticketLogs(filterdata=null) {
            var data = null
            if(filterdata != null){
                data = filterdata;
            }else{
                data = {_token:"{{csrf_token()}}",day:"today"}
            }

            $('.dataTables_processing', $('#ticket-logs-list').closest('.dataTables_wrapper')).show();

            $.ajax({
                type: 'Post',
                url: "{{ asset('/activityLogReports') }}",
                data: data,
                success: function(data) {
                    if (data.success) {
                        tickets_logs_list.clear().draw();
                        $('.dataTables_processing', $('#ticket-logs-list').closest('.dataTables_wrapper')).hide();

                        for (let i = 0; i < data.logs.length; i++) {
                            const element = data.logs[i];
                            var ticket_acti_col = '';

                            if (element.action_perform.includes('by')) {
                                ticket_acti_col += element.action_perform.split('by')[0]
                            } else {
                                ticket_acti_col += element.action_perform.split('By')[0]
                            }
                            var user_id = element.created_by != null ? element.created_by.id : 0;
                            var staff = element.created_by != null ? element.created_by.name : "";
                            tickets_logs_list.row.add([
                                ticket_acti_col,
                                convertDate(element.created_at),
                                `<a href="/profile/` + user_id + `">${staff}</a>`
                            ]).draw(false).node();
                        }
                    } else {
                        console.log(data.message);
                    }
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });
        }

        function filterData(value) {
            var today = new Date();
            switch (value) {
                case "today":
                    let date = new Date();
                    let start = moment(date).startOf('month').format('YYYY-MM-DD');
                    let end = moment(date).endOf('month').format('YYYY-MM-DD');
                    let user_id = $("#user_id").val();
                    var data = {_token:"{{csrf_token()}}",filter: "today"}
                    ticketLogs(data)
                    // getStaffWorkDetail(start, end, user_id);

                    $("#daterangediv").css("display", "none");
                    break;
                case "date_range":


                    $("#daterangediv").css("display", "block");
                    break;
            }
        }

        function getStaffDateWise() {

        let start_date = moment($("#start_date").val()).format('YYYY-MM-DD hh:mm:ss');
        let end_date = moment($("#end_date").val()).format('YYYY-MM-DD hh:mm:ss');

        var staff = $("#staff").select2("val");
        var data = {_token:"{{csrf_token()}}",filter: "date_range",start_date:start_date,end_date:end_date,staff:staff}
        ticketLogs(data)

        // getStaffWorkDetail(start, end, user_id);
        }


        function getStaffWorkDetail(start, end, user_id) {

            var staff_data = {
                start_date: start,
                end_date: end,
                user_id: user_id
            }

            $.ajax({
                type: "POST",
                url: "{{ url('get_staff_attendance') }}",
                dataType: 'json',
                data: staff_data,
                beforeSend: function(data) {
                    $('.loader_container').show();
                },
                success: function(data) {
                    var obj = data.data;
                    // console.log(obj, "obj")
                    var total_hours = 0;

                    var date = new Date();

                    var day_in_month = moment(date).daysInMonth()

                    const countSeconds = (str) => {
                        const [hh = '0', mm = '0', ss = '0'] = (str || '0:0:0').split(':');
                        const hour = parseInt(hh, 10) || 0;
                        const minute = parseInt(mm, 10) || 0;
                        const second = parseInt(ss, 10) || 0;
                        return (hour * 3600) + (minute * 60) + (second);
                    };

                    obj.forEach(element => {
                        total_hours = total_hours + countSeconds(element.hours_worked);
                    });

                    $("#total_hours").text(secondsToTime(total_hours));

                    var avg_hours = total_hours / obj.length;
                    $("#avg_hours").text(secondsToTime(avg_hours * 7));
                    $("#avg_hours_in_day").text(secondsToTime(avg_hours));


                    $('#payroll_table').DataTable().destroy();
                    $.fn.dataTable.ext.errMode = 'none';
                    var tbl = $('#payroll_table').DataTable({
                        data: obj,
                        "pageLength": 10,
                        "bInfo": false,
                        "paging": true,
                        "searching": true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'copyHtml5',
                                exportOptions: {
                                    columns: [0, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                exportOptions: {
                                    columns: [0, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                exportOptions: {
                                    columns: [0, 2, 3, 4, 5]
                                }
                            },
                        ],
                        columns: [{
                                "render": function(data, type, full, meta) {
                                    return full.user_clocked != null ? (full.user_clocked
                                        .name != null ? full.user_clocked.name : '-') : '-';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    if (full.clock_out == null) {
                                        return `<span class="badge badge-success" style="background-color:#468847">Clocked In</span>`;
                                    } else {
                                        return `<span class="badge badge-danger" style="background-color:#b94a48">Clocked Out</span>`;
                                    }
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.date != null ? full.date : '';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.clock_in != null ? full.clock_in : '';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.clock_out != null ? full.clock_out : '';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.hours_worked != null ?
                                        `<span id="${full.id}-hw">${full.hours_worked}</span>` :
                                        `<span id="${full.id}-hw"></span>`;
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return `<button  onclick="editWorkHours(${full.id}, '${full.hours_worked}','${moment(full.clock_in).format('YYYY-MM-DD HH:MM:DD')}','${full.clock_out}')" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;" title="Edit Department">
                            <i class="fas fa-pencil-alt" aria-hidden="true"></i></button>`

                                }
                            },

                        ],
                    });

                },
                complete: function(data) {
                    $('.loader_container').hide();
                },
                error: function(e) {
                    console.log(e)
                }
            });
        }

        function convertDate(date) {
            var d = new Date(date);

            var min = d.getMinutes();
            var dt = d.getDate();
            var d_utc = d.getUTCHours();

            d.setMinutes(min);
            d.setDate(dt);
            d.setUTCHours(d_utc);

            let a = d.toLocaleString("en-US" , {timeZone: time_zone});
            let  date_format = $("#system_date_format").val();
            var converted_date = moment(a).format(date_format + ' ' +'hh:mm A');
            return converted_date;
        }
    </script>
@endsection
