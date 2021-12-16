<script>
    // Ticket CMMN Script Blade 
    let tickets_table_list = '';
let page_name = '';
function initializeTicketTable(p_name='') {
    page_name = p_name;
    tickets_table_list = $('#ticket-table-list').DataTable({
        processing: true,
        // "scrollX": true,
        pageLength: 10,
        fixedColumns: true,
        "autoWidth": false,
        'columnDefs': [
            { className: "overflow-wrap", targets: "_all" },
            { width: '20px', orderable: false, searchable: false, 'className': 'dt-body-center', targets: 0 },
            { width: '20px', orderable: false, searchable: false, 'className': 'dt-body-center', targets: 1 },
            { width: '250px', targets: 3 },
            // { width: '110px', targets: [2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] },
            // { orderable: false, targets: 0 },
            // { orderable: false, targets: 1 },
            {
                targets: [9], // cell target
                render: function(data, type, full, meta) {
                    if(type === "sort") {
                        var api = new $.fn.dataTable.Api(meta.settings);
                        var td = api.cell({row: meta.row, column: meta.col}).node(); // the td of the row
                        data = $(td).attr('data-order'); // the data it should be sorted by
                    }
                    return data;
                }
            },
        ],
        order: [
            [2, 'asc']
        ],
        createdRow: function(row, data, dataIndex) {
            if ($(data[1]).attr('class') && $(data[1]).attr('class').match('flagged')) {
                $(row).addClass('flagged-tr');
            }
        }
    });

    $('#ticket-table-list').parent().css('overflow', 'auto');

    // $('#tk_select').multipleSelect({
    //     width: 300,
    //     onClick: function(view) {
    //         var selectedItems = $('#tk_select').multipleSelect("getSelects");
    //         for (var i = 0; i < 15; i++) {
    //             columns = tickets_table_list.column(i).visible(0);
    //         }
    //         for (var i = 0; i < selectedItems.length; i++) {
    //             var s = selectedItems[i];
    //             tickets_table_list.column(s).visible(1);
    //         }
    //         $('#ticket-table-list').css('width', '100%');
    //     },
    //     onCheckAll: function() {
    //         for (var i = 0; i < 15; i++) {
    //             columns = tickets_table_list.column(i).visible(1);
    //         }
    //     },
    //     onUncheckAll: function() {
    //         for (var i = 0; i < 15; i++) {
    //             columns = tickets_table_list.column(i).visible(0);
    //         }
    //         $('#ticket-table-list').css('width', '100%');
    //     }
    // });
    get_ticket_table_list();

    $('#select-all').change(function() {
        let chck = $(this).prop('checked');
        $('#ticket-table-list tbody input[type="checkbox"]').each(function() {
            if(chck) $(this).prop('checked', true);
            else $(this).prop('checked', false);
        });
    });
}

function get_ticket_table_list() {
    $('#select-all').prop('checked', false);
    tickets_table_list.clear().draw();

    dept = $('#dept').val();
    sts = $('#sts').val();

    url = get_filteredtkt_route +'/'+dept+'/'+sts ;

    if(dept == '' && sts == ''){
        url = get_tickets_route ;
    }
    $.ajax({
        type: "get",
        url: url,
        data: "",
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data, "data");
            date_formate = data.date_format;
            console.log(data.tickets);
            ticketsList = data.tickets;
            listTickets(url_type);
            if(page_name == 'tickets') ShowCalendarModel();
            if(data.hasOwnProperty('total_tickets_count')) $('#total_tickets_count').html(data.total_tickets_count);
            if(data.hasOwnProperty('my_tickets_count')) $('#my_tickets_count').html(data.my_tickets_count);
            if(data.hasOwnProperty('open_tickets_count')) $('#open_tickets_count').html(data.open_tickets_count);
            if(data.hasOwnProperty('unassigned_tickets_count')) $('#unassigned_tickets_count').html(data.unassigned_tickets_count);
            if(data.hasOwnProperty('late_tickets_count')) $('#closed_tickets_count').html(data.late_tickets_count);
            // if(data.hasOwnProperty('closed_tickets_count')) $('#closed_tickets_count').html(data.closed_tickets_count);
            if(data.hasOwnProperty('trashed_tickets_count')) $('#trashed_tickets_count').html(data.trashed_tickets_count);
        }
    });
}
function listTickets(f_key = '') {
    if(f_key) {
        $("#date1").val("");
        $("#statusFilter1").val("all").trigger('change');
        $("#statusFilter2").val("all").trigger('change');
        $("#statusFilter").val("all").trigger('change');
        $("#deptFilter").val("all").trigger('change');
    }
    
    in_recycle_mode = false;
    $('#btnDelete').hide();
    $('#btnMovetotrash').show();
    if (f_key == 'closed' || f_key == 'trash') {
        getClosedOrTrashedTickets(f_key);
        return true;
    }
    let ticket_arr = ticketsList;
    console.log(ticket_arr , "ticket_arr abc");
    if (f_key) {
        switch (f_key) {
            case 'self':
                ticket_arr = ticket_arr.filter(item => item.assigned_to == loggedInUser);
                break;
            case 'open':
                ticket_arr = ticket_arr.filter(item => item.status_name == 'Open');
                break;
            case 'unassigned':
                ticket_arr = ticket_arr.filter(item => item.assigned_to == null);
                break;
            case 'overdue':
                ticket_arr = ticket_arr.filter(item => item.is_overdue == 1);
                break;
            default:
                break;
        }
    } else {
        if ($('#statusFilter').val() && $('#statusFilter').val() != 'all') {
            ticket_arr = ticket_arr.filter(item => item.status == $('#statusFilter').val());
        }
        if ($('#deptFilter').val() && $('#deptFilter').val() != 'all') {
            ticket_arr = ticket_arr.filter(item => item.dept_id == $('#deptFilter').val());
        }
    }
    redrawTicketsTable(ticket_arr);
}
function redrawTicketsTable(ticket_arr) {
    var la_color = ``;
    tickets_table_list.clear().draw();
    $.each(ticket_arr, function(key, val) {
        let prior = '<div class="text-center">' + val['priority_name'] + '</div>';
        if (val['priority_color']) {
            prior = '<div class="text-center text-white badge" style="background-color: ' + val['priority_color'] + ';">' + val['priority_name'] + '</div>';
        }
        let status = '<div class="text-center">' + val['status_name'] + '</div>';
        if (val['status_name']) {
            status = '<div class="text-center text-white badge" style="background-color: ' + val['status_color'] + ';">' + val['status_name'] + '</div>';
        }
        let flagged = '';
        if (val['is_flagged'] == 1) {
            flagged = 'flagged';
        }
        let custom_id = val['coustom_id'];
        if (Array.isArray(ticket_format)) {
            
            ticket_format = ticket_format['tkt_value'];
        }
        if (ticket_format.tkt_value == 'sequential') {
            custom_id = val['seq_custom_id'];
        }
        var name = val['subject'] ?? '';
        var shortname = '';
        if (name && name.length > 40) {
            shortname = name.substring(0, 40) + " ...";
        } else {
            shortname = name;
        }
        let restore_flag_btn = '';
        if (in_recycle_mode) {
            restore_flag_btn = '<div class="text-center ' + flagged + '"><span class="fas fa-trash-restore text-primary" title="Restore" style="cursor:pointer;" onclick="restoreTicket(' + val['id'] + ');"></span></div>';
            $('#btnMovetotrash').hide();
            $('#btnDelete').show();
        } else {
            restore_flag_btn = '<div class="text-center ' + flagged + '"><span class="fas fa-flag" title="Flag" style="cursor:pointer;" onclick="flagTicket(this, ' + val['id'] + ');"></span></div>';
        }
        let res_due = '';
        let rep_due = '';
        
        if(val.sla_plan.title != 'No SLA Assigned') {
            if(val.hasOwnProperty('resolution_deadline') && val.resolution_deadline) {
                // use ticket reset deadlines
                res_due = moment(moment(val.resolution_deadline).toDate()).local();
            } else {
                // use sla deadlines
                if(val.sla_plan.due_deadline) {
                    let hm = val.sla_plan.due_deadline.split('.');
                    res_due = moment(moment(val.sla_res_deadline_from).toDate()).local().add(hm[0], 'hours');
                    if(hm.length > 1) res_due.add(hm[1], 'minutes');
                }
            }
            if(res_due) {
                // overdue or change format of the date
                if(res_due.diff(moment(), "seconds") < 0) res_due = `<div class="text-center" title="${res_due.format('YYYY-MM-DD hh:mm')}"><span class="text-white badge" style="background-color: ${val.sla_plan.bg_color};">Overdue</span></div>`;
                else {
                    // do the date formatting
                    // res_due = res_due.format('YYYY-MM-DD hh:mm');
                    res_due = getClockTime(res_due, 1);
                    // res_due = getDateDiff(res_due);
                }
            }
    
            if(val.hasOwnProperty('reply_deadline') && val.reply_deadline) {
                // use ticket reset deadlines
                if(val.reply_deadline != 'cleared') rep_due = moment(moment(val.reply_deadline).toDate()).local();
            } else {
                // use sla deadlines
                let hm = val.sla_plan.reply_deadline.split('.');
                rep_due = moment(moment(val.sla_rep_deadline_from).toDate()).local().add(hm[0], 'hours');
                if(hm.length > 1) rep_due.add(hm[1], 'minutes');
            }
            if(rep_due) {
                // overdue or change format of the date
                if(rep_due.diff(moment(), "seconds") < 0)  {
                    rep_due = `<div class="text-center" title="${rep_due.format('YYYY-MM-DD hh:mm')}">
                                <span class="text-white badge" style="background-color: ${val.sla_plan.bg_color};">Overdue</span>
                            </div>`;
                }else {
                    // do the date formatting
                    // rep_due = rep_due.format('YYYY-MM-DD hh:mm');
                    rep_due = getClockTime(rep_due, 1);
                    // rep_due = getDateDiff(rep_due);
                }
            }
        }

        let la = new Date(val.lastActivity);
        let replies = 0
        if(val['replies'] > 0){
            replies = val['replies'];
        }
        let replier = val['lastReplier'];
        if(!replier && val['creator_name']) replier = val['creator_name'];

        var last_activity = getDateDiff(moment(moment(val.lastActivity).toDate()).local());

        if(last_activity.includes('d')) {
            la_color = `red`;
        }else if(last_activity.includes('h')) {
            la_color = `#5c83b4`;
        }else if(last_activity.includes('m')) {
            la_color = `#ff8c5a`;
        }

        let row = `<tr>
            <td><div class="text-center"><input type="checkbox" name="select_all" value="${val['id']}"></div></td>
            <td>${restore_flag_btn}</td>
            <td>${status}</td>
            <td><a href="${ticket_details_route}/${val['coustom_id']}">${(shortname.length > 35 ? shortname.substring(1,35) + '...' : shortname)}</a></td>
            <td><a href="${ticket_details_route}/${val['coustom_id']}">${custom_id}</a></td>
            <td>${prior}</td>
            <td><a href="customer-profile/${val['customer_id']}">${val['customer_name']}</a></td>
            <td>${replier}</td>
            <td>${replies}</td>
            <td data-order="${la.getTime()}" style="color:${la_color}">${last_activity}</td>
            <td>${rep_due}</td>
            <td>${res_due}</td>
            <td>${val['tech_name']}</td>
            <td>${val['department_name']}</td>
            <td>${moment(val['created_at']).format(date_formate)}</td>
        </tr>`;
        
        tickets_table_list.row.add($(row)).draw();
        // tickets_table_list.row.add([
        //     '<div class="text-center"><input type="checkbox" name="select_all" value= "' + val['id'] + '"></div>',
        //     restore_flag_btn,
        //     status,
        //     `<a href="${ticket_details_route}/${val['coustom_id']}">${shortname}</a>`,
        //     `<a href="${ticket_details_route}/${val['coustom_id']}">${custom_id}</a>`,
        //     prior,
        //     `<a href="customer-profile/${val['customer_id']}">${val['customer_name']}</a>`,
        //     val['lastReplier'],
        //     val['replies'],
        //     getDateDiff(moment(moment(val.lastActivity).toDate()).local()),
        //     rep_due,
        //     res_due,
        //     val['tech_name'],
        //     val['department_name'],
        //     moment(val['created_at']).format(date_formate),
        // ]).draw(false).node().id = 'tr-' + val['id'];
    });

    $(".list_department").on('click', function () {
        var name = $(this).attr('id');
        tickets_table_list.column(13).search( name ).draw();
    });
}
function flagTicket(ele, id) {
    $.ajax({
        type: 'post',
        url: flag_ticket_route,
        data: { id: id },
        success: function(data) {
            if (data.success) {
                // send mail notification regarding ticket action
                ticket_notify(id, 'ticket_update', 'Flagged');
                if(page_name == 'tickets') getLatestLogs();
                $(ele).closest('tr').toggleClass('flagged-tr');
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
}
function getClosedOrTrashedTickets(key) {
    let ret = $.ajax({
        type: "get",
        url: get_tickets_route + '/' + key,
        async: false,
        data: "",
        dataType: 'json',
        cache: false,
        success: function(data) {
            // console.log(data, "data");
            if (key = 'trashed') in_recycle_mode = true;
            redrawTicketsTable(data.tickets);
        }
    });
}
function ticket_notify(id, template, action_name) {
    $.ajax({
        type: 'POST',
        url: ticket_notify_route,
        data: { id: id, template: template, action: action_name},
        success: function(data) {
            if (!data.success) {
                console.log(data.message);
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}
function getClockTime(followUpDate, timediff) {
    if (timediff >= 0) {
        let today = new Date();
        let remTime = '';
        followUpDate = new Date(Date.parse(new Date()) + (followUpDate - today));
        let rem = getTimeRemaining(followUpDate);
        var color = ``;
        if (rem && rem.hasOwnProperty('years') && rem.years > 0) remTime += rem.years + 'y ';
        if (rem && rem.hasOwnProperty('months') && rem.months > 0) remTime += rem.months + 'm ';
        if (rem && rem.hasOwnProperty('days') && rem.days > 0) remTime += rem.days + 'd ';
        if (rem && rem.hasOwnProperty('hours') && rem.hours > 0) remTime += rem.hours + 'h ';
        if (rem && rem.hasOwnProperty('minutes') && rem.minutes > 0) remTime += rem.minutes + 'min';

        if(remTime.includes('d')) {
            color = `#8BB467`;
        }else if(remTime.includes('h')) {
            color = `#5c83b4`;
        }else if(remTime.includes('min')) {
            color = `#ff8c5a`;
        }
        remTime = `<span style="color:${color}">${remTime}</span>`;
        return remTime;
    }
}
function getDateDiff(date1, date2=new Date()) {
    var a = moment(date1);
    var b = moment(date2);
    // var years = b.diff(a, 'year');
    // a.add(years, 'years');

    // var months = b.diff(a, 'months');
    // a.add(months, 'months');

    var days = b.diff(a, 'days');
    a.add(days, 'days');

    var hours = b.diff(a, 'hours');
    a.add(hours, 'hours');

    var mins = b.diff(a, 'minutes');

    let ret = '';
    // if(years > 0) ret += years+'y ';
    // if(months > 0) ret += months+'m ';
    if(days > 0) ret += days+'d ';
    if(hours > 0) ret += hours+'h ';
    if(mins > 0) ret += mins+'m';

    if (ret == ''){
        ret = 'Just Now'
    }
    return ret;
}


function getTimeRemaining(endtime) {
  let time = Date.parse(endtime) - Date.parse(new Date());
  time = new Date(time);
  // let mn = 30.416666666666;
  // var minutes = Math.floor((t / 1000 / 60) % 60);
  // var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  // var days = Math.floor(t / (1000 * 60 * 60 * 24) % mn);
  // var months = Math.floor(t / (1000 * 60 * 60 * 24 * mn) % 12);
  // var years = Math.floor(t / (1000 * 60 * 60 * 24 * mn * 12));

  let tend = moment(endtime);
  let t = moment();
  let temp = t;
  
  let years = 0;
  let months = Math.abs(tend.diff(t, 'months'));
  while(months > 12){
    years++;
    months = months % 12;
  }
  
  temp.add(years, 'years');
  temp.add(months, 'months');

  let days = Math.abs(temp.diff(tend, 'days'));
  temp.add(days, 'days');
  let hours = Math.abs(temp.diff(tend, 'hours'));
  temp.add(hours, 'hours');
  let minutes = Math.abs(temp.diff(tend, 'minutes'));

  return {
    'total': time,
    'years': years,
    'months': months,
    'days': days,
    'hours': hours,
    'minutes': minutes+1
  };
}
</script>