<script>
    
    var system_date_format = $("#system_date_format").val();
    var timezone = $("#timezone").val();
    $(document).ready(function() {
        
        window.location.href == "{{route('customer.tickets')}}" ? customer.getTickets() : '';

    });


    const customer = {


        getTickets : ()=> {

            $.ajax({
                url: "{{route('customer.getCustomerTickets')}}",
                type: "GET",
                dataType:"json",
                success:function(response){
                    var obj = response.tickets;

                    if(response.status_code == 200) {

                        $("#my_tickets_count").text(response.total_tickets_count);
                        $("#open_tickets_count").text(response.open_tickets_count);
                        $("#closed_tickets_count").text(response.late_tickets_count);


                        $('#customer_tickets_table').DataTable().destroy();
                        $.fn.dataTable.ext.errMode = 'none';
                        var tbl = $('#customer_tickets_table').DataTable({
                            data: obj,
                            "pageLength": 10,
                            "bInfo": false,
                            "paging": true,
                            columns: [
                            {
                                "render": function (data, type, full, meta) {
                                    let subject =  full.subject != null ? full.subject : '-';
                                    let coustom_id =  full.coustom_id != null ? full.coustom_id : '-';
                                    let url = "{{route('customer.tkt_dtl', ':id')}}";
                                    url = url.replace(':id', coustom_id);
                                    return `<div> <strong> <a href="${url}">${subject}</a> </strong> <br> ${coustom_id} </div>`;
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.updated_at != null ? customer.region_wise_dateTime(full.updated_at) : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.lastReplier != null && full.lastReplier != "" ? full.lastReplier : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.department_name != null ? full.department_name : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.type_name != null ? full.type_name : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let status = `<span class="badge" style="background-color:${full.status_color != null ? full.status_color : ''}"> ${full.status_name} </span>`;
                                    return full.status_name != null ? status : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let priority = `<span class="badge" style="background-color:${full.priority_color != null ? full.priority_color : ''}"> ${full.priority_name} </span>`;
                                    return full.priority_name != null ? priority : '-';
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


        region_wise_dateTime : (date) => {

            let d = new Date(date);
            var year = d.getFullYear();
            var month = d.getMonth();
            var day = d.getDay();
            var hour = d.getHours();
            var min = d.getMinutes();
            var mili = d.getMilliseconds();
                    
            // year , month , day , hour , minutes , seconds , miliseconds;
            let new_date = new Date(Date.UTC(year, month, day, hour, min, mili));
            let converted_date = new_date.toLocaleString("en-US", {timeZone: timezone});
            return moment(converted_date).format(system_date_format + ' ' + 'hh:mm a');
        },

        convertDate : (date) => {
            var d = new Date(date);

            var min = d.getMinutes();
            var dt = d.getDate();
            var d_utc = d.getUTCHours();

            d.setMinutes(min);
            d.setDate(dt);
            d.setUTCHours(d_utc);

            let a = d.toLocaleString("en-US" , {timeZone: timezone});
            // return a;
            var converted_date = moment(a).format(system_date_format + ' ' +'hh:mm a');
            return converted_date;
        },


        showdropdown : (value) => {

            $("#" + value + '_heading').toggle();
            $("#" + value +'_field').toggle();

        }

    }


</script>