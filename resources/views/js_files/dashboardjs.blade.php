<script>
    let tickets_logs_list = null;
    let tickets_followups = [];
    let ticketsList = [];
    let date_format = $("#system_date_format").val();
    let time_zone = "{{Session::get('timezone')}}";
    let atte_data = {!! json_encode($staff_att_data) !!};


    var settings = {
        Color: '',
        LinkColor: '',
        NavShow: true,
        NavVertical: false,
        NavLocation: '',
        DateTimeShow: true,
        DateTimeFormat: 'mmm, yyyy',
        DatetimeLocation: '',
        EventClick: '',
        EventTargetWholeDay: false,
        DisabledDays: [],
    };
    // console.clear();
    $(document).ready(function() {
        $("#flagged").DataTable();
        tickets_logs_list = $('#ticket-logs-list').DataTable({
            ordering: false,
        });

        ticketLogs();

        $("#tsearch").keyup(function(e) {

            let value = $(this).val();

            if (e.keyCode == 8) {
                $('#show_ticket_results').html('');
                $("#tkt_loader").hide();
            }

            if ($(this).val().length > 1) {

                $.ajax({
                    url: "{{asset('/search-ticket')}}",
                    type: "POST",
                    data: {
                        id: value
                    },
                    dataType: 'json',
                    beforeSend: function(data) {
                        $("#tkt_loader").show();
                    },
                    success: function(data) {
                        $('#show_ticket_results').show();
                        var result = ``;

                        if (data.length > 0) {
                            data.forEach(element => {
                                result += `
                                <a href="{{asset('ticket-details/` + element.coustom_id + `')}}">
                                    <div class="bg-white text-left text-dark mt-0 px-1 py-1 border search_result rounded">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <p class="m-0">`+element.coustom_id +` | `+element.subject+` | `+element.created_at+`</p>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <!-- <img class="rounded-circle w-50" src="https://picsum.photos/id/3/80/80" data-holder-rendered="true"> -->
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                `;
                            });
                        } else {
                            result = `<span class="text-center">No result found</span>`;
                        }

                        $('#show_ticket_results').html(result);
                    },
                    complete: function(data) {
                        $("#tkt_loader").hide();
                    },
                    failure: function(data) {
                        console.log(data);
                        $("#tkt_loader").hide();
                        $('#show_ticket_results').append(`<center class="text-danger">Error: Failed to Fetch Tickets</center>`);
                        $('#show_ticket_results').show();
                    }
                });
            }

            if (value.length == 0 || value == '') {
                $('#show_ticket_results').html('');
                $("#tkt_loader").hide();
            }

        });

        $("#csearch").keyup(function(e) {

            let value = $(this).val();

            if (e.keyCode == 8) {
                $('#search_customer_result').html('');
                $("#cust_loader").hide();
            }

            if ($(this).val().length > 1) {

                $.ajax({
                    url: "{{asset('/search-customer')}}",
                    type: "POST",
                    data: {
                        id: value
                    },
                    dataType: 'json',
                    beforeSend: function(data) {
                        $("#cust_loader").show();
                    },
                    success: function(data) {
                        console.log(data, "search result");
                        $('#search_customer_result').show();
                        var result = ``;

                        if (data.length > 0) {
                            data.forEach(element => {
                                var phone = element.phone != null && element.phone != "" ? element.phone : 'no phone no added';
                                var company = element.name != null && element.name != "" ? element.name : 'company not provided';

                                result += `
                                <a href="{{asset('customer-profile/` + element.id + `')}}">
                                    <div style="font-size:14px" class="bg-light text-left font-weight-bold text-dark mt-2 p-2 border shadow-sm rounded">` + element.first_name + ` ` + element.last_name + ` (ID : ` + element.id + `) | ` + company + ` | ` + element.email + ` | ` + phone + ` ` + `</div>
                                </a>
                                `;
                            });
                        } else {
                            result = `<span class="text-center">No result found</span>`;
                        }
                        $('#search_customer_result').html(result);
                    },
                    complete: function(data) {
                        $("#cust_loader").hide();
                    },
                    failure: function(data) {
                        console.log(data);
                        $("#cust_loader").hide();
                    }
                });
            }

            if (value.length == 0 || value == '') {
                $('#search_customer_result').html('');
                $("#cust_loader").hide();
            }

        });

        searchFollowUps(true);
        staff_table_draw();

    });

    function staff_table_draw(){

        let tt = $('#staff_table').DataTable({
            ordering: false,
            data:  atte_data ,
            columns: [
                {
                    render: function (data, type, full, meta) {
                        return full.id;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        var name = '---';
                        if(full.name != null) {
                            name = full.name != null ? full.name + '' : '-';
                        }else{
                            name = '-';
                        }

                        return name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        if(full.clock_out == null)
                            return '<span class="badge bg-success">Clocked In</span>';
                        else
                            return '<span class="badge bg-danger">Clocked Out</span>';
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return moment(full.date).format($("#system_date_format").val());
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        // alert({{Session::get("timezone")}});
                        // alert($("#system_date_format").val());
                        let clock_in = moment.tz(full.created_at,'{{Session::get("timezone")}}').format($("#system_date_format").val() + ' ' +'hh:mm A');
                        return clock_in;
                        // return moment(full.clock_in).format($("#system_date_format").val());
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        let clock_in = moment.tz(full.created_at,'{{Session::get("timezone")}}').format($("#system_date_format").val() + ' ' +'hh:mm A');
                        let clock_out = full.clock_out != null ? moment(clock_in).add( HmsToSeconds(full.hours_worked) , 'seconds').format($("#system_date_format").val() + ' ' +'hh:mm A') : '-'
                        // return full.clock_out ? moment(full.clock_out).format($("#system_date_format").val()) : '-';
                        return clock_out;
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        return full.hours_worked == null ? '-' : full.hours_worked;
                    }
                }
            ]
        });

        tt.on( 'order.dt search.dt', function () {
            tt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        }).draw();

    }

    function ticketLogs() {
        $.ajax({
            type: 'GET',
            url: "{{asset('/get_ticket_log')}}",
            success: function(data) {
                if (data.success) {
                    tickets_logs_list.clear().draw();

                    for (let i = 0; i < data.logs.length; i++) {
                        const element = data.logs[i];
                        var ticket_acti_col = '';

                        if(element.action_perform.includes('by')){
                            ticket_acti_col += element.action_perform.split('by')[0]
                        }else{
                            ticket_acti_col += element.action_perform.split('By')[0]
                        }
                        var user_id = element.created_by != null ? element.created_by.id : 0;
                        var staff = element.created_by != null ? element.created_by.name : "";
                        tickets_logs_list.row.add([
                                        ticket_acti_col,
                                        convertDate(element.created_at),
                                        `<a href="/profile/`+user_id+`">${staff}</a>`
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

    function staffatt(btn_text, btn) {

        let url = "{{asset('add_checkin')}}";

        if(btn_text == 'clockout') {
            url = `{{asset('add_checkout')}}`;
        }

        $(btn).text("Processing..");
        $(btn).attr('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            async: true,
            success: function(data) {
                console.log(data);
                atte_data = data.staff_att_data;
                // staff_table_draw();

                if (data.success == true) {
                    $('.clock_btn').remove();
                    let btn = ``;

                    if(btn_text == 'clockin') {
                        btn = `<button type="button" class="btn btn-danger clock_btn" onclick="staffatt('clockout', this)"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>`;
                        $('.clock_in_section').attr('style','display:none !important');

                        $(".user-status").after(`<span class="badge bg-success clockin_timer" style="margin-top:4px"></span>`);

                        clockintime = moment(data.clock_in_time , "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
                    }else{
                        btn = `<button type="button" class="btn btn-success clock_btn" onclick="staffatt('clockin', this)"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>`;

                        $('.clockin_timer').hide();
                        $('.clock_in_section').removeAttr('style');

                        let clockSection = `<div class="d-flex w-100 fw-bolder clock_in_section">
                            <h5 class="ms-1 fw-bolder text-danger">You are not clocked in!</h5>
                            <h5 class="mx-2 fw-bolder text-danger">Do you wish to clock in Now?</h5>
                            <div class="d-flex">
                                <a href="#" class="mx-1 text-danger" onclick="staffatt('clockin')"> Yes </a> | <a href="#" class="ms-1 text-danger">Ignore</a>
                            </div>
                        </div>`;

                        $('.showClockInSection').html(clockSection);
                    }

                    $('.clock_btn_div').append(btn);

                    var curr_user_name = $("#curr_user_name").val();
                    var system_date_format = $("#system_date_format").val();
                    var today = new Date();
                    let time = moment(today).format('h:mm:ss');
                    let date = moment(today).format(system_date_format);

                    let clock_out_time = ``;

                    if( data.hasOwnProperty('clock_out_time') ) {
                        clock_out_time =convertDate( data.clock_out_time );
                    }else{
                        clock_out_time = `-`;
                    }

                    let clock_in_time = ``;
                    let clock_in = ``;

                    if(btn_text == 'clockin') {
                        clock_in_time = convertDate(new Date());
                        clock_in = `<span class="badge bg-success">Clocked In</span>`;
                    }else{
                        clock_in_time = convertDate( data.clock_in_time );
                        clock_in = `<span class="badge bg-danger">Clocked Out</span>`;
                    }

                    let working_hour = data.hasOwnProperty('worked_time');;

                    if(working_hour) {
                        working_hour = data.worked_time;
                    }else{
                        working_hour = `-`;
                    }

                    let trLength = $("#showstaffdata tr").length;


                    // if($("#showstaffdata tr").hasClass('new_entry')){
                    //     trLength  = trLength- 1;
                    // }

                    // $('.new_entry').remove();

                    $('#staff_table').DataTable().destroy();

                    $("#staff_table tbody").append(
                        `<tr id="new_entry" class="new_entry">
                            <td>${trLength + 1}</td>
                            <td>${curr_user_name} </td>
                            <td>${clock_in}</td>
                            <td>${date}</td>
                            <td>${clock_in_time}</td>
                            <td>${clock_out_time}</td>
                            <td>${working_hour}</td>
                        </tr>`);

                    $('#staff_table').DataTable().draw();


                    if(data.status_code == 201) {
                        alertNotification('warning', 'Warning' ,data.message );
                    } else {
                        alertNotification('success', 'Success' ,data.message );
                    }
                } else {
                    $('.clock_btn').remove();

                    btn = `<button type="button" class="btn btn-success clock_btn" onclick="staffatt('clockin', this)"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>`;

                        $('.clockin_timer').hide();
                        $('.clock_in_section').removeAttr('style');

                        let clockSection = `<div class="d-flex w-100 fw-bolder clock_in_section">
                            <h5 class="ms-1 fw-bolder text-danger">You are not clocked in!</h5>
                            <h5 class="mx-2 fw-bolder text-danger">Do you wish to clock in Now?</h5>
                            <div class="d-flex">
                                <a href="#" class="mx-1 text-danger" onclick="staffatt('clockin')"> Yes </a> | <a href="#" class="ms-1 text-danger">Ignore</a>
                            </div>
                        </div>`;
                        $('.clock_btn_div').append(btn);

                        $('.showClockInSection').html(clockSection);
                    alertNotification('error', 'Error' ,data.message );
                }
            },
            failure: function(data) {
                console.log(data);
                alertNotification('error', 'Error' ,data.message );
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

    function searchTicket() {
        $formData = new FormData($('#search-ticket')[0]);

        $('#show_ticket_results').html('');
        $('#show_ticket_results').append('<h5>Tickets</h5>');

        $.ajax({
            url: $('#search-ticket').attr('action'),
            type: $('#search-ticket').attr('method'),
            data: $formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                console.log(data, "search result");

                if (data.length > 0) {
                    for (let i in data) {
                        $('#show_ticket_results').append(`<button class="btn btn-block btn-primary text-white text-left" onclick="window.location = '{{asset('ticket-details/` + data[i].coustom_id + `')}}'">` + data[i].subject + `</button>`);
                    }
                } else {
                    $('#show_ticket_results').append(`<center>No Matching Tickets Found</center>`);
                }
                $('#show_ticket_results').show();
            },
            failure: function(data) {
                console.log(data);
                $('#show_ticket_results').append(`<center class="text-danger">Error: Failed to Fetch Tickets</center>`);
                $('#show_ticket_results').show();
            }
        });
    }

    function searchCustomer() {
        $formData = new FormData($('#search-customer')[0]);

        $('#search_customer_result').html('');
        $('#search_customer_result').append('<h5>Customers</h5>');

        $.ajax({
            url: $('#search-customer').attr('action'),
            type: $('#search-customer').attr('method'),
            data: $formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                console.log(data);

                if (data.length > 0) {
                    for (let i in data) {
                        var phone = data[i].phone != null && data[i].phone != "" ? data[i].phone : '-';
                        var company = data[i].name != null && data[i].name != "" ? data[i].name : '-';
                        $('#search_customer_result').append(`<button style="font-size:14px" class="btn btn-block btn-primary text-white text-left" onclick="window.location = '{{asset('customer-profile/` + data[i].id + `')}}'">` + data[i].first_name + ` ` + data[i].last_name + ` (ID # ` + data[i].id + `) | ` + company + ` | ` + data[i].email + ` | ` + phone + ` ` + `</button>`);
                    }
                } else {
                    $('#search_customer_result').append(`<center>No Matching Customer Found</center>`);
                }
                $('#search_customer_result').show();
            },
            failure: function(data) {
                console.log(data);
                $('#search_customer_result').append(`<center class="text-danger">Error: Failed to Fetch Customers</center>`);
                $('#search_customer_result').show();
            }
        });
    }

    function searchFollowUps(restOfM=false) {
        let startDate = $('#startDate').val();

        tickets_followups = [];

        $.ajax({
            type: 'post',
            url: "{{asset('fetch-followups')}}",
            data: {startDate: startDate, restOfMonth: restOfM},
            async: false,
            success: function(data) {
                if (data.success) {
                    ticketsList = data.tickets;
                    tickets_followups = data.followups;
                    for (let i in tickets_followups) {
                        let tick = ticketsList.filter(item => item.id == tickets_followups[i].ticket_id);

                        if(tick.length) tickets_followups[i].ticket = tick[0];
                        else tickets_followups[i].ticket = '';
                    }
                    show_followups(restOfM);
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
            failure: function(errMsg) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Error',
                    text: errMsg,
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        })
    }

    function show_followups(restOfM) {
        // ShowCalendar(restOfM);

        var system_date_format = $("#system_date_format").val();

        $('#followup_table').DataTable().destroy();
        $.fn.dataTable.ext.errMode = 'none';
        var tbl = $('#followup_table').DataTable({
            data: tickets_followups,
            "pageLength": 10,
            "bInfo": false,
            "paging": true,
            columns: [
                {
                    "render": function (data, type, full, meta) {
                        return `<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck_`+full.id+`">
                            <label class="custom-control-label" for="customCheck_`+full.id+`"></label>
                        </div>`;
                    }
                },
                {
                    "data": null,
                    "defaultContent": ""
                },
                {
                    "render": function (data, type, full, meta) {
                        if(full.ticket) {
                            let name = full.ticket.customer_name;
                            let id = full.ticket.customer_id;
                            return `<a href="{{url('customer-profile')}}/`+id+`">`+name+`</a>`;
                        } else {
                            return `-`;
                        }
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        if(full.ticket) {
                            let id = full.ticket.coustom_id != null ? full.ticket.coustom_id : '-';
                            return `<a href="{{url('ticket-details')}}/`+id+`">`+id+`</a>`;
                        } else {
                            return `-`;
                        }
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        return moment(full.created_at).format(system_date_format);
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        if(full.ticket) {
                            let name = full.ticket.tech_name;
                            let id = full.ticket.assigned_to;
                            if(id) return `<a href="{{url('profile')}}/`+id+`">`+name+`</a>`;
                            else return 'Unassigned';
                        }else{
                            return '-';
                        }
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        if(full.ticket) {
                            let phone = full.ticket.customer_phone != null ? full.ticket.customer_phone : '-' ;
                            return `<a href="tel:`+phone+`">`+phone+`</a>`
                        }else{
                            return `-`;
                        }
                    }
                }
            ]
        });

        tbl.on('order.dt search.dt', function () {
            tbl.column(1, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    }

    function ShowCalendar(restOfM) {
        var element = document.getElementById('calendar');
        element.innerHTML = '';
        var events = [];

        for (let i in tickets_followups) {
            let tid = ticketsList.filter(item => item.id == tickets_followups[i].ticket_id);

            if (tid.length) {
                tid = tid[0].coustom_id;

                let followUpDate = '';
                if (tickets_followups[i].is_recurring == 1) {
                    followUpDate = moment(moment.utc(tickets_followups[i].date).toDate()).local();
                    if (tickets_followups[i].schedule_type == 'time' && tickets_followups[i].recurrence_time) {
                        let rec_time = tickets_followups[i].recurrence_time.split(':');
                        followUpDate.set('hour', rec_time[0]);
                        followUpDate.set('minute', rec_time[1]);
                    }
                } else {
                    if ((tickets_followups[i].schedule_time || tickets_followups[i].custom_date) && tickets_followups[i].schedule_type != 'time') {
                        if (tickets_followups[i].schedule_type == 'custom') {
                            followUpDate = moment.utc(tickets_followups[i].custom_date).toDate();
                            followUpDate = moment(followUpDate).local();
                        } else {
                            followUpDate = moment.utc(tickets_followups[i].created_at).toDate();
                            followUpDate = moment(followUpDate).local();
                            followUpDate.add(tickets_followups[i].schedule_time, tickets_followups[i].schedule_type);
                        }
                    }
                }

                // if(restOfM) {
                //     if(followUpDate.diff(moment(moment(new Date($('#startDate').val())).endOf('month').toDate()), 'days') > 0) {
                //         tickets_followups.splice(i, 1);
                //         continue;
                //     }
                // }

                let dd = new Date(followUpDate);

                events.push({
                    Date: new Date(dd.getFullYear(), dd.getMonth(), dd.getDate()),
                    Title: tid,
                    Link: 'ticket-details/' + tid
                });
            }
        }
        caleandar(element, events, settings);
    }

    // $( ".search_result" ).hover(
    // function() {
    //     $(this).addClass('shadow-lg').css('cursor', 'pointer');
    // }, function() {
    //     $(this).removeClass('shadow-lg');
    // })
</script>
