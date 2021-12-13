
$(document).ready(function() {

    staff_list();

})

// ===================================================================
//get all staff attendance detail according to dates provided by user
//====================================================================

function getStaffAttendance() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var user_id = $("#staff").val();
    
    var staff_data = {
        start_date: moment(start_date).format('YYYY-MM-DD hh:mm:ss'),
        end_date: moment(end_date).format('YYYY-MM-DD hh:mm:ss'),
        user_id: user_id
    }

    
    $.ajax({
        type: "POST",
        url: staff_att_url,
        dataType: 'json',
        data : staff_data,  
        beforeSend:function(data) {
            $('.loader_container').show();
        },
        success: function(data) {
            console.log(data , "data");
            var date_format = data.date_format;

            var obj = data.data;
            var total_hours = 0;
            var date = new Date();
            var day_in_month = moment(date).daysInMonth();

            const countSeconds = (str) => {
                const [hh = '0', mm = '0', ss = '0'] = (str || '0:0:0').split(':');
                const hour = parseInt(hh, 10) || 0;
                const minute = parseInt(mm, 10) || 0;
                const second = parseInt(ss, 10) || 0;
                return (hour*3600) + (minute*60) + (second);
            };

            obj.forEach(element => {
                total_hours = total_hours + countSeconds(element.hours_worked);
            });

            var avg_hours = total_hours / obj.length;

            $("#total_hours").text(secondsToTime(total_hours));
            $("#avg_hours").text(secondsToTime(avg_hours / 7));
            $("#avg_hours_in_day").text(secondsToTime(total_hours / day_in_month));


            $('#user_table_list').DataTable().destroy();
            $.fn.dataTable.ext.errMode = 'none';
            var tbl = $('#user_table_list').DataTable({
                data: obj,
                "pageLength":10,
                "bInfo": false,
                "paging": true,
                columns: [{
                    "data": null,
                    "defaultContent": ""
                },
                {
                    "render": function (data, type, full, meta) {
                        return full.user[0] != null ? full.user[0].name :'-';
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        if(full.clock_out == null) {
                            return `<span class="badge badge-success py-1">Clocked In</span>`;
                        }else{
                            return `<span class="badge badge-danger py-1">Clocked Out</span>`;
                        }
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        return full.date != null ? moment(full.date).format(date_format) : '-';
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        return full.clock_in != null ? full.clock_in : '-';
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        return full.clock_out != null ? full.clock_out : '-';
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        return full.hours_worked != null ? full.hours_worked : '-';
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
        },
        complete:function(data) {
            $('.loader_container').hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}


// ===================================================================
// staff list populate in dropdown 
//====================================================================

function staff_list() {
    $.ajax({
        type: "GET",
        url: staff_list_url,
        dataType: 'json',
        beforeSend:function(data) {
            $('.loader_container').show();
        },
        success: function(data) {
            console.log(data , "data");
            
            var obj = data.data

            var user_arr = [];

            obj.forEach(element => {
                if(element.user_type != 4 && element.user_type != 5 && element.is_support_staff != 1) {
                    user_arr.push(element);
                }
            });


            var option =``;
            var select = `<option value="">Select</option>`;

            for(var i =0; i < user_arr.length; i++) {
                option +=`<option value="`+user_arr[i].id+`">`+user_arr[i].name+`</option>`;
            }
            $("#staff").html(select + option);
        },
        complete:function(data) {
            $('.loader_container').hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}

function secondsToTime(seconds) {
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds % 3600) / 60); 
    var seconds = Math.floor(seconds % 60);
    return (hours < 10 ? "0" + hours : hours) + ":" + 
           (minutes < 10 ? "0" + minutes : minutes) + ":" + 
           (seconds < 10 ? "0" + seconds : seconds);
}