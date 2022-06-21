<script>
    
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        

        flagged_tickets.getTickets();

    });


    const flagged_tickets = {


        getTickets : ()=> {

            $.ajax({
                url: "{{route('admin.flagTickets')}}",
                type: "POST",
                dataType:"json",
                success:function(response){
                    var obj = response.tickets;
                    if(response.status_code == 200) {
                        
                        $('#flagged_ticket_table').DataTable().destroy();
                        $.fn.dataTable.ext.errMode = 'none';
                        var tbl = $('#flagged_ticket_table').DataTable({
                            data: obj,
                            "pageLength": 10,
                            "bInfo": false,
                            "paging": true,
                            columns: [
                            {
                                "render": function (data, type, full, meta) {
                                    return `
                                        <div class="text-center" >
                                            <input type="checkbox" name="select_all[]" id="select-all">
                                        </div>`;
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return `
                                        <div class="text-center flagged-tr">
                                            <span class="fas fa-flag" title="Flag" 
                                                style="cursor:pointer;" onclick="flagged_tickets.flaggedTickets(this, ${full.id});">
                                                </span>
                                        </div>
                                    `;
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let status = `<div class="text-center text-white badge"
                                         style="background-color:${full.status_color}">${full.status_name}</div>`;
                                    return full.status_name != null ? status : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let link = `<a href="{{url('ticket-details')}}/${full.coustom_id}" style="color:black">${full.subject}</a>`;
                                    return full.subject != null ? link : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let link = `<a href="{{url('ticket-details')}}/${full.coustom_id}" style="color:black">${full.coustom_id}</a>`;
                                    return full.coustom_id != null ? link : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let prio = `<div class="text-center text-white badge"
                                         style="background-color:${full.priority_color}">${full.priority_name}</div>`;
                                    return full.priority_name != null ? prio : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.customer_name != null ? full.customer_name : '-';
                                }
                            },
                            ],
                        });
                    }else{
                        toastr.error( 'Something Went Wrong' , { timeOut: 5000 });
                    }
                },
                error:function(e) {
                    toastr.error( 'Something Went Wrong' , { timeOut: 5000 });
                }
            });

        },

        flaggedTickets : (ele , id) => {
            $.ajax({
                type: 'post',
                url: "{{url('flag_ticket')}}",
                data: { id: id },
                success: function(data) {
                    if (data.success) {
                        // send mail notification regarding ticket action
                        flagged_tickets.ticket_notify(id, 'ticket_update', 'Flagged');
                        $(ele).closest('tr').toggleClass('flagged-tr');
                        flagged_tickets .getTickets()

                        toastr.success( data.message , { timeOut: 5000 });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    }
                },
                failure: function(errMsg) {
                    // console.log(errMsg);
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: errMsg,
                        showConfirmButton: false,
                        timer: swal_message_time
                    });
                }
            });
        },

        ticket_notify : (id, template, action_name) => {
            $.ajax({
                type: 'POST',
                url: "{{url('ticket_notification')}}",
                data: { id: id, template: template, action: action_name},
                success: function(data) {
                    if (!data.success) {
                        // console.log(data.message);
                    }
                },
                failure: function(errMsg) {
                    // console.log(errMsg);
                }
            });
        }


    }


</script>