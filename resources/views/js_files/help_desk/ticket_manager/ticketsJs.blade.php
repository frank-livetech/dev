<script>
    // Ticket Script Blade
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    var usrtimeZone = $("#usrtimeZone").val();
    let modalCal = document.getElementById('calendarModal');
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
        ModelChange: modalCal
    };
    let in_recycle_mode = false;

    $(function() {

        initializeTicketTable('tickets');
        getLatestLogs();

        $('input[type="checkbox"]').click(function() {
            if ($(this).prop("checked") == false) {

                $('#first_name').val("");
                $('#last_name').val("");
                $('#Ph_number').val("");
                $('#email').val("");
                $('#new-customer').css('display', 'none');

            } else if ($(this).prop("checked") == true) {
                $('#new-customer').css('display', 'block');
            }
        });
    });

    $('.master_check').click(function() {
        if ($(this).is(':checked')) {
            $('tbody input').attr('checked', true);
        } else {
            $('tbody input').attr('checked', false);
        }
    });

    $("#btnDelete").click(function() {
        var tickets = [];
        $("tbody input:checked").each(function() {
            tickets.push($(this).val());
        });
        if (!tickets.length) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'No ticket selected!',
                showConfirmButton: false,
                timer: swal_message_time
            })
            return false;
        }

        Swal.fire({
            title: 'Move selected tickets to trash?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    url: move_to_trash_route,
                    data: { tickets , tkt_del : 1},
                    success: function(data) {

                        if (data.success) {

                            get_ticket_table_list();

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Data Deleted Successfully!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Something went wrong!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                        }
                    },
                    failure: function(errMsg) {
                        console.log(errMsg);
                    }
                })
            }
        })
    });

    function moveToTrash() {
        if (in_recycle_mode) return false;

        var tickets = [];
        $("tbody input:checked").each(function() {
            tickets.push($(this).val());
        });
        if (!tickets.length) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'No ticket selected!',
                showConfirmButton: false,
                timer: swal_message_time
            })
            return false;
        }

        Swal.fire({
            title: 'Move selected tickets to trash?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!',
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    url: move_to_trash_route,
                    data: {
                        tickets
                    },
                    success: function(data) {

                        if (data.success) {
                            get_ticket_table_list();

                            // send mail notification regarding ticket action
                            ticket_notify(tickets[tickets.length - 1], 'ticket_update', 'Trashed');
                        }

                        Swal.fire({
                            position: 'center',
                            icon: (data.success) ? 'success' : 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });

                        $('.show_tkt_btns').hide();
                    },
                    failure: function(errMsg) {
                        console.log(errMsg);
                    }
                })
            }
        });
    }

    function restoreTicket(id) {
        let tickets = [id];
        Swal.fire({
            title: 'Restore this ticket?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    url: rec_ticket_route,
                    data: {
                        tickets
                    },
                    success: function(data) {

                        if (data.success) {

                            get_ticket_table_list();

                            // send mail notification regarding ticket action
                            ticket_notify(tickets[tickets.length - 1], 'ticket_update', 'Restored');

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Recycled Successfully!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Something went wrong!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                        }
                    },
                    failure: function(errMsg) {
                        console.log(errMsg);
                    }

                })
            }
        });
    }

    $("#show-clndr").click(function() {

        $("#calendarModal").modal('show');

        ShowCalendarModel();

        $('.cld-day').addClass('rounded');
        $('.cld-day').css('margin', '10px !important');

        $('.cld-labels').addClass('bg-primary text-white p-1 rounded mt-1')
    });

    function ShowCalendarModel() {
        var element = document.getElementById('calendar');
        element.innerHTML = '';
        var events = [];

        let f_counts = 0;
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

                if (moment(followUpDate).diff(moment(), 'seconds') < 0) continue;

                f_counts++;

                let dd = new Date(followUpDate);

                events.push({
                    Date: new Date(dd.getFullYear(), dd.getMonth(), dd.getDate()),
                    Title: tid,
                    Link: 'ticket-details/' + tid
                });
            }
        }

        document.getElementById('show-clndr').innerHTML = '<i class="fas fa-calendar"></i>&nbsp;Calendar (' + f_counts + ')';
        caleandar(element, events, settings);

        // $('#calendarModal').modal('show');
    }

    function ShowTicketModel() {
        $('#dept_id').val('').trigger("change");
        $('#status').val('').trigger("change");
        $('#priority').val('').trigger("change");
        $('#type').val('').trigger("change");
        $('#customer_id').val('').trigger("change");
        $("#save_tickets").trigger("reset");
        $('#new-customer').css('display', 'none');
        $('#ticket').modal('show');
    }

    $("#save_tickets").submit(function(event) {
        event.preventDefault();

        var subject = $('#subject').val().replace(/\s+/g, " ").trim();
        $('#subject').val(subject);

        var formData = new FormData($(this)[0]);
        var action = $(this).attr('action');
        var method = $(this).attr('method');

        var dept_id = $('#dept_id').val();
        var status = $('#status').val();
        var priority = $('#priority').val();
        var assigned_to = $('#assigned_to').val();
        var type = $('#type').val();
        var customer_id = $('#customer_id').val();
        var ticket_detail = $('#ticket_detail').val();
        if (subject == '' || subject == null) {
            $('#select-subject').css('display', 'block');
            return false;
        } else if (dept_id == '' || dept_id == null) {
            $('#select-department').css('display', 'block');
            return false;
        } else if (status == '' || status == null) {
            $('#select-status').css('display', 'block');
            return false;
        } else if (priority == '' || priority == null) {
            $('#select-priority').css('display', 'block');
            return false;
        } else if (type == '' || type == null) {
            $('#select-type').css('display', 'block');
            return false;
        } else if (ticket_detail == '' || ticket_detail == null) {
            $('#pro-details').css('display', 'block');
            return false;
        }
        if (!$('#new-form').prop('checked')) {
            if (customer_id == '' || customer_id == null) {
                $('#select-customer').css('display', 'block');
                return false;
            }
        }
        if ($('#new-form').prop('checked')) {

            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var is_new = true;

            if (first_name == '' || first_name == null) {
                $('#save-firstname').css('display', 'block');
                return false;

            } else if (last_name == '' || last_name == null) {
                $('#save-lastname').css('display', 'block');
                return false;
            } else if (phone == '' || phone == null) {
                $('#save-number').css('display', 'block');
                return false;
            } else if (email == '' || email == null) {
                $('#save-email').css('display', 'block');
                return false;
            }

        }
        $(this).find('#btnSaveTicket').attr('disabled', true);
        $(this).find('#btnSaveTicket .spinner-border').show();

        setTimeout(() => {
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
                    $('#btnSaveTicket').attr('disabled', false);
                    $('#btnSaveTicket .spinner-border').hide();

                    if (data.success) {
                        $('#ticket').modal('hide');
                        $("#save_tickets").trigger("reset");
                        $('#dept_id').val('').trigger("change");
                        $('#status').val('').trigger("change");
                        $('#priority').val('').trigger("change");
                        $('#assigned_to').val('').trigger("change");
                        $('#type').val('').trigger("change");
                        $('#customer_id').val('').trigger("change");
                        $('#new-customer').css('display', 'none');
                        get_ticket_table_list();

                        // send mail notification regarding ticket action
                        ticket_notify(data.id, 'ticket_create', 'Created');
                    }
                    Swal.fire({
                        position: 'center',
                        icon: (data.success) ? 'success' : 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: swal_message_time,
                    });
                },
                failure: function(errMsg) {
                    $('#btnSaveTicket').attr('disabled', false);
                    $('#btnSaveTicket .spinner-border').hide();
                    console.log(errMsg);
                }
            });
        }, 1000);

    });

    function getLatestLogs() {
        $.ajax({
            type: 'GET',
            url: "{{url('get_ticket_log')}}",
            success: function(data) {
                if (data.success) {
                    console.log(data);
                    let obj = data.logs;
                    $("#ticket-logs-list").DataTable().destroy();
                    $.fn.dataTable.ext.errMode = "none";
                    var tbl = $("#ticket-logs-list").DataTable({
                        "order": [[ 0, "desc" ]],
                        data: obj,
                        "pageLength": 10,
                        // "bInfo": false,
                        // "paging": true,
                        // "searching": true,
                        columns: [
                            {
                                className : 'd-none',
                                "render": function(data, type, full, meta) {
                                    return full.id;
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.action_perform + ' at ' + convertDate(full.created_at);
                                }
                            },
                        ],
                    });
                } else {
                    console.log(data.message);
                }
            },
            failure: function(errMsg) {
                console.log(errMsg);
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

        let a = d.toLocaleString("en-US" , {timeZone: usrtimeZone});
        let format = $('#system_date_format').val();
        var converted_date = moment(a).format(format + ' ' +'hh:mm A');
        return converted_date;
    }

    function merge_tickets() {
        var tickets = [];
        $("tbody input:checked").each(function() {
            tickets.push($(this).val());
        });
        if (!tickets.length || tickets.length < 2) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Please select two or more tickets!',
                showConfirmButton: false,
                timer: swal_message_time
            })
            return false;
        }

        Swal.fire({
            title: 'Merge selected tickets?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!',
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: merge_tickets_route,
                    data: {
                        tickets
                    },
                    success: function(data) {

                        if (data.success) {
                            get_ticket_table_list();

                            // send mail notification regarding ticket action
                            ticket_notify(tickets[tickets.length - 1], 'ticket_update', 'Merged');
                        }

                        Swal.fire({
                            position: 'center',
                            icon: (data.success) ? 'success' : 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    },
                    failure: function(errMsg) {
                        console.log(errMsg);
                    }
                })
            }
        });
    }

    function showDepartStatus(value) {

        $.ajax({
            type: "POST",
            url: get_department_status,
            data: {
                id: value
            },
            dataType: 'json',
            beforeSend: function(data) {
                $("#status_modal").show();
            },
            success: function(data) {
                console.log(data);
                let obj = data.status;

                let option = ``;
                let select = `<option value="">Select</option>`;

                for (var i = 0; i < obj.length; i++) {
                    option += `<option value="` + obj[i].id + `">` + obj[i].name + `</option>`;
                }
                $("#status").html(select + option);
            },
            complete: function(data) {
                $("#status_modal").hide();
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    /*New card js from ticket details */

    $(document).ready(function() {
        $("#dropD ").find(".select2").hide();
        $("#dropD ").find("h5").show();
        selectD();
    })

    $('.br-white').click(function(event) {
        event.stopPropagation();

        $("#dropD ").find(".select2").toggle();
        $("#dropD ").find("h5").toggle();
        $(".chim ").find("a").toggleClass("pt-6");
        $(".upBtn").toggleClass("mt-2");
    });

    $(".select2").change(function() {
        selectD();
    })

    function selectD() {
        var Priority = $("#prio-label").find(".select2 option:selected").text();
        $("#prio-h5").text(Priority);
        var Dep = $("#dep-label").find(".select2 option:selected").text();
        $("#dep-h5").text(Dep);
        var Tech = $("#tech-label").find(".select2 option:selected").text();
        $("#tech-h5").text(Tech);
        var Status = $("#status-label").find(".select2 option:selected").text();
        $("#status-h5").text(Status);
        var Type = $("#type-label").find(".select2 option:selected").text();
        $("#type-h5").text(Type);

    }


    // status change bar coor
    $('#status').change(function() {
        var color = $('#status option:selected').data('color');
        $('.drop-dpt').css('background-color', color);
    });

    $('#priority').change(function() {
        var color = $('#priority option:selected').data('color');
        $('#prio-label').css('background-color', color);
    });

    function ticketTableLength(val) {
        
        $.ajax({
            type: "POST",
            url: "{{route('saveGeneralInfo')}}",
            data: { per_page: val , user_id : {{auth()->id()}} },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if(data.status_code == 200 && data.success == true) {
                    toastr.success( 'Table length Saved Successfully' , { timeOut: 5000 });
                }
                
            },
            error: function(error) {
                console.log(error);
            }
        });

    }
</script>