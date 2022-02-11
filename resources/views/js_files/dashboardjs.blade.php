<script>
    let tickets_logs_list = null;
    let tickets_followups = [];
    let ticketsList = [];
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
            ordering: false
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
                        console.log(data, "search result");
                        $('#show_ticket_results').show();
                        var result = ``;

                        if (data.length > 0) {
                            data.forEach(element => {
                                result += `
                                <a href="{{asset('ticket-details/` + element.coustom_id + `')}}">
                                    <div class="bg-light text-left text-dark mt-2 p-2 border shadow-sm rounded">

                                        <p class="m-0">`+element.coustom_id +` | `+element.subject+` | `+element.created_at+`</p> 

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

        document.getElementById('startDate').valueAsDate = new Date();
        // get_all_followups();
        searchFollowUps(true);


        let tt = $('#staff_table').DataTable({
            ordering: false,
            data:  {!! json_encode($staff_att_data) !!} ,
            columns: [
                {
                    render: function (data, type, full, meta) {
                        return full.id;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.user_clocked.name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        if(full.clock_out == null)
                            return '<span class="badge badge-success py-1">Clocked In</span>';
                        else
                            return '<span class="badge badge-danger py-1">Clocked Out</span>';
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return moment(full.date).format($("#system_date_format").val());
                    }
                }, 
                {
                    render: function(data, type, full, meta) {
                        return moment(full.clock_in).format($("#system_date_format").val());
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        return full.clock_out ? moment(full.clock_out).format($("#system_date_format").val()) : '-';
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
    });

    function ticketLogs() {
        $.ajax({
            type: 'GET',
            url: "{{asset('/get_ticket_log')}}",
            success: function(data) {
                if (data.success) {
                    console.log(data);
                    tickets_logs_list.clear().draw();

                    for (let i = 0; i < data.logs.length; i++) {
                        const element = data.logs[i];

                        tickets_logs_list.row.add([
                            element.id,
                            element.action_perform+' at '+moment(element.created_at).format($("#system_date_format").val() +' '+  'hh:mm A'),
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

    function staffatt(btn_text) {
        // var btn_text = $('.clock_btn').text();

        if (btn_text == 'clockin') {
            $.ajax({
                url: "{{asset('add_checkin')}}",
                type: 'POST',
                async: true,
                success: function(data) {
                    console.log(data);
                    if (data.success == true) {
                        $('.clock_btn').remove();
                        var btn = `<button type="button" class="btn btn-danger clock_btn" onclick="staffatt('clockout')"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>`;
                        $('.clock_btn_div').append(btn);

                        var curr_user_name = $("#curr_user_name").val();
                        var system_date_format = $("#system_date_format").val();
                        let clock_in = `<span class="badge badge-success py-1">Clocked In</span>`;
                        var today = new Date();
                        let time = moment(today).format('h:mm:ss');
                        let date = moment(today).format(system_date_format);
                        let clock_out = `<span class="badge badge-danger py-1">Clocked Out</span>`;

                        $("#staff_table tbody").append(
                        `<tr id="new_entry">
                            <td></td>
                            <td>${curr_user_name} </td>
                            <td>` + clock_in + `</td>
                            <td>` + date + `</td>
                            <td>` + time + `</td>
                            <td></td>
                            <td></td>
                        </tr>`);

                        if(data.status_code == 201) {
                            toastr.warning(data.message, {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success(data.message, {
                                timeOut: 5000
                            });
                        }
                    } else {
                        toastr.error(data.message, {
                            timeOut: 5000
                        });
                    }
                },
                failure: function(data) {
                    console.log(data);
                    toastr.error(data.message, {
                        timeOut: 5000
                    });
                }
            });
        } else {
            $.ajax({
                url: "{{asset('add_checkout')}}",
                type: 'POST',
                async: true,
                success: function(data) {
                    console.log(data);
                    if (data.success == true) {
                        $('.clock_btn').remove();
                        var btn = `<button type="button" class="btn btn-success clock_btn" onclick="staffatt('clockin')"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>`;
                        $('.clock_btn_div').append(btn);

                        $("#new_entry").remove();

                        var curr_user_name = $("#curr_user_name").val();
                        var system_date_format = $("#system_date_format").val();
                        let clock_in = `<span class="badge badge-success py-1">Clocked In</span>`;
                        var today = new Date();
                        let time = moment(today).format('h:mm:ss');
                        let date = moment(today).format(system_date_format);
                        let clock_out = `<span class="badge badge-danger py-1">Clocked Out</span>`;

                        $("#staff_table tbody").append(
                        `<tr id="new_entry">
                            <td></td>
                            <td> {{auth()->user()->name}} </td>
                            <td>` + clock_out + `</td>
                            <td>` + date + `</td>
                            <td>` + moment(data.clock_in_time).format(system_date_format + ' h:mm:ss') + `</td>
                            <td> ` + moment(data.clock_out_time).format(system_date_format + ' h:mm:ss') + ` </td>
                            <td> ` + data.worked_time + `</td>
                        </tr>`);

                        if(data.status_code == 201) {
                            toastr.warning(data.message, {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success(data.message, {
                                timeOut: 5000
                            });
                        }
                    } else {
                        toastr.error(data.message, {
                            timeOut: 5000
                        });
                    }
                },
                failure: function(data) {
                    console.log(data);
                }
            });
        }
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
</script>