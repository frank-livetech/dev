<script>
    // Ticket CMMN Script Blade 
    let tickets_table_list = '';
    let page_name = '';
    var system_date_format = $("#system_date_format").val();
    var usrtimeZone = $("#usrtimeZone").val();
    let ticketDataTableLength = 10;

    function initializeTicketTable(p_name = '') {
        page_name = p_name;
        tickets_table_list = $('#ticket-table-list').DataTable({
            processing: true,
            // "scrollX": true,
            pageLength: (ticketLengthCount == null ? 10 : (ticketLengthCount.per_page !=null ? ticketLengthCount.per_page : 10)),
            fixedColumns: true,
            "autoWidth": false,
            'columnDefs': [{
                    className: "overflow-wrap",
                    targets: "_all"
                },
                {
                    width: '20px',
                    orderable: false,
                    searchable: false,
                    'className': 'dt-body-center',
                    targets: 0
                },
                {
                    width: '20px',
                    orderable: false,
                    searchable: false,
                    'className': 'dt-body-center',
                    targets: 1
                },
                {
                    width: '250px',
                    targets: 3
                },
                // { width: '110px', targets: [2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] },
                // { orderable: false, targets: 0 },
                // { orderable: false, targets: 1 },
                {
                    targets: [9], // cell target
                    render: function(data, type, full, meta) {
                        if (type === "sort") {
                            var api = new $.fn.dataTable.Api(meta.settings);
                            var td = api.cell({
                                row: meta.row,
                                column: meta.col
                            }).node(); // the td of the row
                            data = $(td).attr('data-order'); // the data it should be sorted by
                        }
                        return data;
                    }
                },
            ],
            // ,
            // order: [
            //     [2, 'asc']
            // ],
            createdRow: function(row, data, dataIndex) {
                if ($(data[1]).attr('class') && $(data[1]).attr('class').match('flagged')) {
                    $(row).addClass('flagged-tr');
                }
            }
        });
        $('#ticket-table-list').parent().css('overflow', 'auto');

        // if(p_name == 'staff_self'){
        //     let ret = $.ajax({
        //         type: "get",
        //         url: get_tickets_route + '/self' ,
        //         async: false,
        //         data: "",
        //         dataType: 'json',
        //         cache: false,
        //         success: function(data) {
        //             // console.log(data, "data tickets");

        //             redrawTicketsTable(data.tickets);
        //         }
        //     });
        // }else{
        get_ticket_table_list();
        // }

        $('#select-all').change(function() {
            let chck = $(this).prop('checked');

            // show hide tkt buttons
            (chck == true ? $('.show_tkt_btns').show() : $('.show_tkt_btns').hide());
            // 

            $('#ticket-table-list tbody input[type="checkbox"]').each(function() {
                if (chck) $(this).prop('checked', true);
                else $(this).prop('checked', false);
            });
        });
    }

    function selectSingle(id) {
        let chck = $("#select_single_" + id).prop('checked');
        $("#select_single_" + id).toggleClass('chkd');
        if (chck == true) {
            $('.show_tkt_btns').show()
        }
        var checked_tkt = jQuery(".chkd").length;
        if (checked_tkt == 0) {
            $('.show_tkt_btns').hide()
        }

        $("#dept_id").val("nochange").trigger("change");
        $("#assigned_to").val("nochange").trigger("change");
        $("#type").val("nochange").trigger("change");
        $("#status").val("nochange").trigger("change");
        $("#priority").val("nochange").trigger("change");

        $("#prio-label").removeAttr('style', true);
        $("#prio-label").attr('style', 'border-right: 1px solid white; padding: 12px;');

        $('.drop-dpt').attr('style', 'background-color:#b0bec5;border-radius:9px;');
    }

    function updateTickets() {

        var tkt_id = [];

        $("input:checkbox[name=select_all]:checked").each(function() {
            tkt_id.push($(this).val());
        });

        if (tkt_id.length == 0) {
            toastr.error('select ticket first', {
                timeOut: 5000
            });
        } else {

            let dept_id = $("#dept_id").val();
            let assigned_to = $("#assigned_to").val();
            let type = $("#type").val();
            let status = $("#status").val();
            let priority = $("#priority").val();

            let form_data = new FormData();

            if (dept_id != 'nochange') {
                form_data.append('dept_id', dept_id)
            }

            if (assigned_to != 'nochange') {
                form_data.append('assigned_to', assigned_to)
            }

            if (type != 'nochange') {
                form_data.append('type', type)
            }


            if (status != 'nochange') {
                form_data.append('status', status)
            }

            if (priority != 'nochange') {
                form_data.append('priority', priority)
            }

            if (dept_id == 'nochange' && assigned_to == 'nochange' && type == 'nochange' && dept_id == 'nochange' && status == 'nochange' && priority == 'nochange') {
                toastr.error('select action to perform', {
                    timeOut: 5000
                });
                return false;
            }

            form_data.append('tkt_id', tkt_id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{route('admin.updateTkt')}}",
                type: 'POST',
                data: form_data,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    // console.log(data , "data");
                    if (data.status_code == 200 && data.success == true) {
                        toastr.success(data.message, {
                            timeOut: 5000
                        });

                        getLatestLogs();

                        get_ticket_table_list();
                        $('.show_tkt_btns').hide();


                        $("#dept_id").val("nochange").trigger("change");
                        $("#assigned_to").val("nochange").trigger("change");
                        $("#type").val("nochange").trigger("change");
                        $("#status").val("nochange").trigger("change");
                        $("#priority").val("nochange").trigger("change");
                        $("#prio-label").removeAttr('style', true);
                        $("#prio-label").attr('style', 'border-right: 1px solid white; padding: 12px;');

                        $('.drop-dpt').attr('style', 'background-color:#b0bec5;border-radius:9px;');

                    } else {
                        toastr.error(data.message, {
                            timeOut: 5000
                        });
                    }
                },
                error: function(e) {
                    console.log(e)
                }

            });
        }


    }


    $("#ShowGeneralSettings").click(function() {
        $("#general-opt").toggle();
    });

    function get_ticket_table_list() {
        $('#select-all').prop('checked', false);
        tickets_table_list.clear().draw();

        let dept = $('#dept').val();
        let sts = $('#sts').val();

        var url = get_tickets_route;
        if (dept == '' || dept == undefined && sts == '' || sts == undefined) {
            url = get_tickets_route;
        } else {
            url = get_filteredtkt_route + '/' + dept + '/' + sts;
        }

        $.ajax({
            type: "get",
            url: url,
            data: "",
            dataType: 'json',
            cache: false,
            success: function(data) {
                // console.log(data, "data 123123123");
                date_formate = data.date_format;
                // console.log(data.tickets);
                ticketsList = data.tickets;
                listTickets(url_type);
                if (page_name == 'tickets') ShowCalendarModel();
                if (data.hasOwnProperty('open_ticket_count')) $('#open_ticket_count').html(data.open_ticket_count);
                if (data.hasOwnProperty('total_tickets_count')) $('#total_tickets_count').html(data.total_tickets_count);
                if (data.hasOwnProperty('my_tickets_count')) $('#my_tickets_count').html(data.my_tickets_count);
                if (data.hasOwnProperty('flagged_tickets_count')) $('#flagged_tickets_count').html(data.flagged_tickets_count);
                if (data.hasOwnProperty('unassigned_tickets_count')) $('#unassigned_tickets_count').html(data.unassigned_tickets_count);
                if (data.hasOwnProperty('late_tickets_count')) $('#late_tickets_count').html(data.late_tickets_count);
                if (data.hasOwnProperty('closed_tickets_count')) $('#closed_tickets_count').html(data.closed_tickets_count);
                if (data.hasOwnProperty('trashed_tickets_count')) $('#trashed_tickets_count').html(data.trashed_tickets_count);

                // ticket datatable per page length start
                let titalPage = 0;
                let ticket_view = data.ticket_view == null ? '10' : (data.ticket_view.per_page != null ? data.ticket_view.per_page : '10');
                totalPage = ticket_view;
                if (ticket_view != 10 && ticket_view != 25 && ticket_view != 50 && ticket_view != 100) {
                    let option = `<option value="${ticket_view}" selected> ${ticket_view} </option>`;
                    $('select[name=ticket-table-list_length]').append(option);
                }else{
                    totalPage = (data.ticket_view == null ? 10 : (data.ticket_view.per_page !=null ? data.ticket_view.per_page : 10))
                    $('select[name=ticket-table-list_length]').val(data.ticket_view.per_page);
                }

                $('select[name=ticket-table-list_length]').attr('onchange','ticketTableLength(this.value)');
                // $('#ticket-table-list').DataTable().page.len(ticket_view).draw();
                // ticket datatable per page length end

            }
        });
    }

    function getCounterTickets(key) {
        in_recycle_mode = false;
        let dept_id = $('#dept').val();
        // if (dept_id != '' && key == 'total') {

        //     // let new_url = '';

        //     // if (key == 'total') {
        //     //     new_url = get_tickets_route + '/' + key;
        //     // } else {
        //     //     new_url = get_filteredtkt_route + '/' + dept_id;
        //     // }
        //     // console.log(dept_id , "dept_id");
        //     // console.log(new_url , "new_url");
        //     let ret = $.ajax({
        //         type: "get",
        //         // url: new_url,
        //         url: get_filteredtkt_route + '/' + dept_id,
        //         async: false,
        //         data: "",
        //         dataType: 'json',
        //         cache: false,
        //         success: function(data) {
        //             // console.log(data, "data tickets");

        //             redrawTicketsTable(data.tickets);
        //         }
        //     });
        // } else {

            $.ajax({
                type: "get",
                url: get_tickets_route + '/' + key,
                async: false,
                data: "",
                dataType: 'json',
                cache: false,
                success: function(data) {
                    // console.log(data, "data tickets");

                    redrawTicketsTable(data.tickets);
                }
            });

            // }


        // }
    }

    function listTickets(f_key = '') {
        if (f_key) {
            $("#date1").val("");
            $("#statusFilter1").val("all").trigger('change');
            $("#statusFilter2").val("all").trigger('change');
            $("#statusFilter").val("all").trigger('change');
            $("#deptFilter").val("all").trigger('change');
        }

        in_recycle_mode = false;

        $('#btnMovetotrash').show();
        if (f_key == 'closed' || f_key == 'trash') {
            getClosedOrTrashedTickets(f_key);
            return true;
        }
        let ticket_arr = ticketsList;
        // console.log(ticket_arr , "ticket_arr abc");
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

    function convertDate(date) {
        
        var d = new Date(date);

        var min = d.getMinutes();
        var dt = d.getDate();
        var d_utc = d.getUTCHours();

        d.setMinutes(min);
        d.setDate(dt);
        d.setUTCHours(d_utc);

        let a = d.toLocaleString("en-US" , {timeZone: "{{Session::get('timezone')}}"} );
        
        // return a;
        var converted_date = moment(a).format("{{Session::get('system_date')}}" + ' ' +'hh:mm A');
        return converted_date;
    }

    function redrawTicketsTable(ticket_arr) {
        tkt_arr = ticket_arr;
        var la_color = ``;
        tickets_table_list.clear().draw();
        // console.log(ticket_arr, "ticket_arr");
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

            var cust_name = val['customer_name'] ?? '';
            var short_cust_name = '';

            if (cust_name && cust_name.length > 15) {
                short_cust_name = cust_name.substring(0, 15) + " ...";
            } else {
                short_cust_name = cust_name;
            }


            var dep_name = val['department_name'] ?? '';
            var short_dep_name = '';

            if (dep_name && dep_name.length > 15) {
                short_dep_name = dep_name.substring(0, 15) + " ...";
            } else {
                short_dep_name = dep_name;
            }

            let restore_flag_btn = '';
            // console.log(in_recycle_mode, "in_recycle_mode");
            if (in_recycle_mode) {
                restore_flag_btn = '<div class="text-center ' + flagged + '"><span class="fas fa-trash-restore text-primary" title="Restore" style="cursor:pointer;" onclick="restoreTicket(' + val['id'] + ');"></span></div>';
                $('#btnMovetotrash').hide();
                $('#btnDelete').show();
            } else {
                $('#btnDelete').hide();
                restore_flag_btn = '<div class="text-center ' + flagged + '"><span class="fas fa-flag" title="Flag" style="cursor:pointer;" onclick="flagTicket(this, ' + val['id'] + ');"></span></div>';
            }
            let res_due = '';
            let rep_due = '';

            if (val.sla_plan.title != 'No SLA Assigned') {
                if (val.hasOwnProperty('resolution_deadline') && val.resolution_deadline) {
                    // use ticket reset deadlines
                    res_due = moment(moment(val.resolution_deadline).toDate()).local();
                } else {
                    // use sla deadlines
                    if (val.sla_plan.due_deadline) {
                        let hm = val.sla_plan.due_deadline.split('.');
                        res_due = moment(moment(val.sla_res_deadline_from).toDate()).local().add(hm[0], 'hours');
                        if (hm.length > 1) res_due.add(hm[1], 'minutes');
                    }
                }
                if (res_due) {
                    // overdue or change format of the date
                    if (res_due.diff(moment(), "seconds") < 0) res_due = `<div class="text-center cursor" onclick="resetSLAPlan(${val['id']})" title="${res_due.format('YYYY-MM-DD hh:mm')}"><span class="text-white badge" style="background-color: ${val.sla_plan.bg_color};cursor:pointer">Overdue</span></div>`;
                    else {
                        // do the date formatting
                        // res_due = res_due.format('YYYY-MM-DD hh:mm');
                        res_due = getClockTime(res_due, 1);
                        // res_due = getDateDiff(res_due);
                    }
                }

                if (val.hasOwnProperty('reply_deadline') && val.reply_deadline) {
                    // use ticket reset deadlines
                    if (val.reply_deadline != 'cleared') rep_due = moment(moment(val.reply_deadline).toDate()).local();
                } else {

                    // use sla deadlines
                    // console.log(val.sla_plan.reply_deadline , "deadline");
                    let hm = val.sla_plan.reply_deadline.split('.');
                    rep_due = moment(moment(val.sla_rep_deadline_from).toDate()).local().add(hm[0], 'hours');
                    if (hm.length > 1) rep_due.add(hm[1], 'minutes');
                }
                if (rep_due) {
                    // overdue or change format of the date
                    if (rep_due.diff(moment(), "seconds") < 0) {
                        rep_due = `<div class="text-center cursor" onclick="resetSLAPlan(${val['id']})" title="${rep_due.format('YYYY-MM-DD hh:mm')}">
                                <span class="text-white badge" style="background-color: ${val.sla_plan.bg_color};cursor:pointer">Overdue</span>
                            </div>`;
                    } else {
                        // do the date formatting
                        // rep_due = rep_due.format('YYYY-MM-DD hh:mm');
                        rep_due = getClockTime(rep_due, 1);
                        // rep_due = getDateDiff(rep_due);
                    }
                }
            }
            let new_rep_due = ``;
            let new_res_due = ``;

            if (val['reply_deadline'] == null) {
                new_rep_due = `<span class="cursor" onclick="resetSLAPlan(${val['id']})"> ${rep_due.replace("60m", "59m")} </span>`;
            }

            if (val['resolution_deadline'] == null) {
                new_res_due = `<span class="cursor" onclick="resetSLAPlan(${val['id']})"> ${rep_due.replace("60m", "59m")} </span>`;
            }


            // this is demo comment
            if (val['reply_deadline'] != null ) {

                let currTime = new Date().toLocaleString('en-US', {
                    timeZone: usrtimeZone
                });
                let con_currTime = moment(currTime).format('YYYY-MM-DD hh:mm A');

                if (val['reply_deadline'] != "cleared") {
                    let tkt_rep_due = moment(val['reply_deadline']).format('YYYY-MM-DD hh:mm A');
                    // let timediff_rep = moment(tkt_rep_due).diff( moment(con_currTime) , 'seconds');

                    let timediff_rep = getDatesSeconds(val['reply_deadline'], con_currTime);

                    if (timediff_rep <= 0) {
                        new_rep_due = `<div class="text-center cursor" onclick="resetSLAPlan(${val['id']})" title="${tkt_rep_due}">
                                <span class="text-white badge" style="background-color: ${val.sla_plan.bg_color};">Overdue</span>
                            </div>`;
                    } else {
                        new_rep_due = `<span class="cursor" onclick="resetSLAPlan(${val['id']})">${getHoursMinutesAndSeconds(val['reply_deadline'], con_currTime)}</span>`;
                        // let cal_rep_due = momentDiff(tkt_rep_due , con_currTime);
                        // new_rep_due = cal_rep_due.replace("60m", "59m");
                    }
                } else {
                    new_rep_due = `<span onclick="resetSLAPlan(${val['id']})" class="cursor badge bg-light"> Reset </span>`;
                }
            }

            if ( val['resolution_deadline'] != null) {

                let currTime = new Date().toLocaleString('en-US', {
                    timeZone: usrtimeZone
                });
                let con_currTime = moment(currTime).format('YYYY-MM-DD hh:mm A');

                if (val['resolution_deadline'] != "cleared") {
                    // console.log("")
                    let tkt_res_due = moment(val['resolution_deadline']).format('YYYY-MM-DD hh:mm A');
                    // let timediff_res = moment(tkt_res_due).diff( moment(con_currTime) , 'seconds');
                    let timediff_res = getDatesSeconds(val['resolution_deadline'], con_currTime);
                    if (timediff_res <= 0) {
                        new_res_due = `<div class="text-center cursor" onclick="resetSLAPlan(${val['id']})" title="${tkt_res_due}">
                                <span class="text-white badge" style="background-color: ${val.sla_plan.bg_color};">Overdue</span>
                            </div>`;
                    } else {
                        new_res_due = `<span class="cursor" onclick="resetSLAPlan(${val['id']})">${getHoursMinutesAndSeconds(val['resolution_deadline'], con_currTime)}</span>`
                        // let cal_res_due = momentDiff(tkt_res_due , con_currTime);   
                        // new_res_due = cal_res_due.replace("60m", "59m");
                    }
                } else {
                    new_res_due = `<span onclick="resetSLAPlan(${val['id']})" class="cursor badge bg-light"> Reset </span>`;
                }
            }

            // var last_act = val.lastActivity;
            // let region_current_date = new Date().toLocaleString('en-US', { timeZone: usrtimeZone });

            let la = new Date(val.lastActivity);
            let replies = 0
            if (val['replies'] > 0) {
                replies = val['replies'];
            }
            let replier = '---';

            if (val['lastReplier'] != null) {
                replier = val['lastReplier'];
            }
            // if(!replier && val['creator_name']) replier = val['creator_name'];

            var short_replier = '';
            var assignee = '-- Unassigned --';

            if (val['assignee_name'] != null) {
                assignee = val['assignee_name'];
            }

            // let c = moment(last_act).parseZone(usrtimeZone).format('MM/DD/YYYY h:mm:ss A');
            var last_activity = getDateDiff(val.lastActivity);

            if (last_activity.includes('d')) {
                la_color = `#FF0000`;
            } else if (last_activity.includes('h')) {
                la_color = `#FF8C5A`;
            } else if (last_activity.includes('m')) {
                la_color = `#5C83B4`;
            } else if (last_activity.includes('s')) {
                la_color = `#8BB467`;
            }


            let notes_icon = `<i class="fas fa-comment-alt-lines mx-1" style="margin-top:2px" title="This Ticket Has One or More Ticket Notes"></i>`;
            let attachment_icon = `<i class="fa fa-paperclip" aria-hidden="true" style="margin-top:2px; margin-left:4px; color:#5f6c73;" title="Has Attachments"></i>`;
            let follow_up_icon = `<span title="Has Followup"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f7b51b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg></span>`;

            let row = `<tr class="${val['is_flagged'] == 1 ? 'flagged-tr' : ''}">
            <td>
                <div class="text-center">
                    <input type="checkbox" id="select_single_${val['id']}" onchange="selectSingle(${val['id']})" 
                        class="tkt_chk" name="select_all" value="${val['id']}">
                        </div>
            </td>
            <td>
                <div class="d-flex">
                    ${restore_flag_btn} ${val['tkt_notes'] > 0 ? notes_icon : ''}
                </div>
            </td>
            <td class='text-center'>${status}</td>
            <td class="ticketName" id="${val['id']}">
                <div class="d-flex justify-content-between">

                    <a href="${ticket_details_route}/${val['coustom_id']}" id="ticket_name_${val['id']}" class="ticket_name fw-bolder" data-id="${val['id']}">   
                        ${(shortname.length > 35 ? shortname.substring(0,35) + '...' : shortname)}
                    </a>

                    <span>
                        ${val['attachments'] != null ? attachment_icon : ''}
                        ${val['tkt_follow_up'] > 0 ? follow_up_icon : ''}
                    </span>

                    <div class="hover_content_${val['id']} bg-white border rounded p-1" 
                        style="position:absolute;width:clamp(auto, 80%, auto);height:auto; overflow:hidden; white-space: initial; display:none;
                            z-index:999;transition:0.5s; cursor:pointer; margin-left:90px; margin-top:22px;">
                    </div>
                </div>
                
            </td>
            <td>
                <span class="text-dark"><a href="${ticket_details_route}/${val['coustom_id']}">${custom_id}</a></span>
            </td>
            <td class='text-center'>${prior}</td>
            <td><a href="customer-profile/${val['customer_id']}" style="color:black">${(short_cust_name.length > 15 ? short_cust_name.substring(0,15) + '...' : short_cust_name)}</a></td>
            <td>${ val['lastReplier'] != null ? val['lastReplier'] : val['creator_name']}</td>
            <td class='text-center'>${replies}</td>
            <td class='text-center' data-order="${la.getTime()}" style="color:${la_color}">${last_activity}</td>
            <td class='text-center'>${new_rep_due}</td>
            <td class='text-center'>${new_res_due}</td>
            <td>${assignee}</td>
            <td>${(short_dep_name.length > 15 ? short_dep_name.substring(0,15) + '...' : short_dep_name)}</td>
            
        </tr>`;

            tickets_table_list.row.add($(row)).draw();
        });

        $(".list_department").on('click', function() {
            var name = $(this).attr('id');
            tickets_table_list.column(13).search(name).draw();
        });

        $('.loading__').addClass('d-none');
    }

    function getHoursMinutesAndSeconds(date_one, date_two) {
        let greater = moment(date_one, "YYYY-MM-DD hh:mm A").valueOf();
        let smaller = moment(date_two, "YYYY-MM-DD hh:mm A").valueOf();

        let diff = ((greater - smaller) / 1000).toFixed(2);
        let days = Math.floor(diff / 86400);
        let hours = Math.floor(diff / 3600) % 24;
        let minutes = Math.floor(diff / 60) % 60;
        let seconds = diff % 60;


        let remainTime = (days > 0 ? days + 'd ' : '') + (hours > 0 ? hours + 'h ' : '') + minutes + 'm ' + (seconds > 0 ? seconds + 's ' : '');

        let color = ``;
        if (remainTime.includes('d')) {
            color = `#8BB467`;
        } else if (remainTime.includes('h')) {
            color = `#5c83b4`;
        } else if (remainTime.includes('m')) {
            color = `#ff8c5a`;
        }
        return `<span style="color: ${color}">${remainTime}</span>`;
    }

    function getDatesSeconds(greater_date, smaller_date) {
        let greater = moment(greater_date, "YYYY-MM-DD hh:mm A").valueOf();
        let smaller = moment(smaller_date, "YYYY-MM-DD hh:mm A").valueOf();
        let time = greater - smaller;

        let sec = 1000;
        return (time / sec);
    }

    function momentDiff(end, start) {

        let diff = moment.preciseDiff(end, start);
        diff = diff.replace(" days", "d");
        diff = diff.replace(" day", "d");
        diff = diff.replace(" hour", "h");
        diff = diff.replace("hs", "h");
        diff = diff.replace(" minutes", "m");
        diff = diff.replace(" minute", "m");


        let color = ``;
        if (diff.includes('d')) {
            color = `#8BB467`;
        } else if (diff.includes('h')) {
            color = `#5c83b4`;
        } else if (diff.includes('m')) {
            color = `#ff8c5a`;
        }


        let time = `<span style="color: ${color}">${diff}</span>`;
        return time;
    }

    function jsTimeZone(date) {
        let d = new Date(date);

        var year = d.getFullYear();
        var month = d.getMonth();
        var date = d.getDate();
        var hour = d.getHours();
        var min = d.getMinutes();
        var mili = d.getMilliseconds();

        // year , month , day , hour , minutes , seconds , miliseconds;
        let new_date = new Date(Date.UTC(year, month, date, hour, min, mili));
        let converted_date = new_date.toLocaleString("en-US", {
            timeZone: usrtimeZone
        });
        return moment(converted_date).format(date_format + ' ' + 'hh:mm a');
    }

    function flagTicket(ele, id) {
        $.ajax({
            type: 'post',
            url: flag_ticket_route,
            data: {
                id: id
            },
            success: function(data) {
                if (data.success) {
                    // send mail notification regarding ticket action
                    ticket_notify(id, 'ticket_update', 'Flagged');
                    if (page_name == 'tickets') getLatestLogs();
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
                // if(key == 'trashed') {
                //     in_recycle_mode = true;
                // }
                console.log(key , "key");
                in_recycle_mode = (key == 'trash' ? true : false);
                console.log(in_recycle_mode , "in_recycle_mode");
                redrawTicketsTable(data.tickets);
            }
        });
    }

    function ticket_notify(id, template, action_name) {
        $.ajax({
            type: 'POST',
            url: ticket_notify_route,
            data: {
                id: id,
                template: template,
                action: action_name
            },
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

    function getClockTime(followUpDate, timediff) {
        if (timediff >= 0) {
            let today = new Date();
            let remTime = '';
            followUpDate = new Date(Date.parse(new Date()) + (followUpDate - today));
            let rem = getTimeRemaining(followUpDate);
            // console.log(rem, "rem");
            var color = ``;
            if (rem && rem.hasOwnProperty('years') && rem.years > 0) remTime += rem.years + 'y ';
            if (rem && rem.hasOwnProperty('months') && rem.months > 0) remTime += rem.months + 'm ';
            if (rem && rem.hasOwnProperty('days') && rem.days > 0) remTime += rem.days + 'd ';
            if (rem && rem.hasOwnProperty('hours') && rem.hours > 0) remTime += rem.hours + 'h ';
            if (rem && rem.hasOwnProperty('minutes') && rem.minutes > 0) remTime += rem.minutes + 'm ';

            if (rem && rem.hasOwnProperty('seconds') && rem.days == 0) {
                remTime += rem.seconds + 's';
            }

            if (remTime.includes('d')) {
                color = `#8BB467`;
            } else if (remTime.includes('h')) {
                color = `#5c83b4`;
            } else if (remTime.includes('m')) {
                color = `#ff8c5a`;
            }
            remTime = `<span style="color:${color}">${remTime}</span>`;
            return remTime;
        }
    }

    function getDateDiff(date1, date2 = new Date()) {
        var a = moment(date1);
        var b = moment(date2);

        var days = b.diff(a, 'days');
        a.add(days, 'days');

        var hours = b.diff(a, 'hours');
        a.add(hours, 'hours');

        var mins = b.diff(a, 'minutes');
        var sec = b.diff(a, 'seconds');

        let ret = '';

        if (days > 0) ret += days + 'd ';
        if (hours > 0) ret += hours + 'h ';
        if (mins > 0) ret += mins + 'm ';

        // check if day pass then seconds not shown
        if (days == 0) {
            var ms = moment(b).diff(moment(a));
            let d = moment.duration(ms);
            ret += d.seconds() + 's ';
        }

        if (ret == '') {
            ret = '0s'
        }
        return ret;
    }

    function calculateDateDiff(date1, date2) {
        var a = moment(date1);
        var b = moment(date2);
        var days = b.diff(a, 'days');
        a.add(days, 'days');

        var hours = b.diff(a, 'hours');
        a.add(hours, 'hours');

        var mins = b.diff(a, 'minutes');

        let ret = '';
        if (days > 0) ret += days + 'd ';
        if (hours > 0) ret += hours + 'h ';
        if (mins > 0) ret += mins + 'm';

        if (ret == '') {
            ret = '0m'
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

        var ms = moment(tend).diff(moment(t));
        let d = moment.duration(ms);

        let years = 0;
        let months = Math.abs(tend.diff(t, 'months'));
        while (months > 12) {
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
            'minutes': minutes + 1,
            'seconds': d.seconds(),
        };
    }

    // refresh ticket page 
    function refreshTickets() {
        get_ticket_table_list();
    }


    // reset sla
    function resetSLAPlan(id) {
        let ticket = ticketsList.find(item => item.id == id);
        if(ticket != null) {
            let currentTime = convertDate(ticket.created_at);
            if( ticket.reply_deadline == null || ticket.resolution_deadline == null ) {

                if( ticket.reply_deadline == null ) {
                    if(ticket.sla_plan != null && ticket.sla_plan != "") {
                        let reply_date = moment(currentTime).add(ticket.sla_plan.reply_deadline , 'h');
                        $("#reply_date").val( moment(reply_date , "YYYY-MM-DD").format('YYYY-MM-DD') );
                        $("#reply_hour").val(  reply_date.format('h') );
                        $("#reply_minute").val(  reply_date.format('mm') );
                        $("#reply_type").val(  reply_date.format('A') );
                    }
                }

                if( ticket.reply_deadline == null ) {
                    if(ticket.sla_plan != null && ticket.sla_plan != "") {
                        let due_deadline = moment(currentTime).add(ticket.sla_plan.due_deadline , 'h');
                        $("#res_date").val( moment(due_deadline , "YYYY-MM-DD").format('YYYY-MM-DD') );
                        $("#res_hour").val(  due_deadline.format('h'));
                        $("#res_minute").val(  due_deadline.format('mm') );
                        $("#res_type").val(  due_deadline.format('A') );
                    }
                }
            }

            if(ticket.reply_deadline != null || ticket.resolution_deadline != null ) {
            
                if(ticket.reply_deadline != "cleared") {
                    let rep_deadline = moment(ticket.reply_deadline , "YYYY-MM-DD h:mm A").format("YYYY-MM-DD h:mm A");
                    let time  = rep_deadline.split(' ');
                    let split_hours = time[1].split(':');

                    $("#reply_date").val( time[0] );
                    $("#reply_hour").val(  split_hours[0] );
                    $("#reply_minute").val( split_hours[1] );
                    $("#reply_type").val(  time[2] );
                }else{
                    $("#reply_date").val("");
                    $("#reply_hour").val(12);
                    $("#reply_minute").val('00');
                    $("#reply_type").val('PM');
                }

                if(ticket.resolution_deadline != 'cleared') {

                    let res_deadline = moment(ticket.resolution_deadline , "YYYY-MM-DD h:mm A").format("YYYY-MM-DD h:mm A");
                    let time  = res_deadline.split(' ');
                    let split_hours = time[1].split(':');

                    $("#res_date").val(time[0] );
                    $("#res_hour").val( split_hours[0] );
                    $("#res_minute").val(  split_hours[1] );
                    $("#res_type").val( time[2] );
                }else{
                    $("#res_date").val("");
                    $("#res_hour").val(12);
                    $("#res_minute").val('00');
                    $("#res_type").val('PM');
                }

                // setSlaPlanDeadlines();
            }

            $("#sla_ticket_id").val(id);
            $("#reset_sla_plan_modal").modal('show');
        }
    }

    function resetSLA(value) {
        if(value == 'reply_due') {
            $("#reply_date").val("");
            $("#reply_hour").val("12");
            $("#reply_minute").val("00");
            $("#reply_type").val("PM");
        }else{
            $("#res_date").val("");
            $("#res_hour").val("12");
            $("#res_minute").val("00");
            $("#res_type").val("PM");
        }
    }

    function updateDeadlines() {

        let ticketid = $("#sla_ticket_id").val();

        let currdate = new Date().toLocaleString('en-US', { timeZone: "{{Session::get('timezone')}}" });
        currdate = moment(currdate).format("YYYY-MM-DD h:mm A");

        let rp_date = $("#reply_date").val();
        let rp_hour = $("#reply_hour").val();
        let rp_min = $("#reply_minute").val();
        let rp_type = $("#reply_type").val();

        let rep_deadline = rp_date + ' ' +rp_hour + ':' + rp_min + ' ' + rp_type;

        let res_date = $("#res_date").val();
        let res_hour = $("#res_hour").val();
        let res_min = $("#res_minute").val();
        let res_type = $("#res_type").val();

        let res_deadline = res_date + ' ' +res_hour + ':' + res_min + ' ' + res_type;

        let overdue = '';

        let timediff_rep = getDatesSeconds( rep_deadline  , currdate  );
        let timediff_res = getDatesSeconds( res_deadline  , currdate  );

        if(timediff_rep < 0) {
            overdue = 1;
        }else{
            overdue = 0;
        }

        if(overdue == 0) {
            if(timediff_res <= 0) {
                overdue = 1;
            }else{
                overdue = 0;
            }
        }

        if(res_date == '') {
            res_deadline  = 'cleared';
            overdue = 0;
        }

        if(rp_date == '') {
            rep_deadline  = 'cleared';
            overdue = 0;
        }

        let formData = {
            ticket_id: ticketid,
            rep_deadline: rep_deadline,
            res_deadline: res_deadline,
            overdue : overdue,
        };

        $.ajax({
            type: "post",
            url: "{{url('update-ticket-deadlines')}}",
            data: formData,
            dataType: 'json',
            // cache: false,
            success: function(data) {

                if (data.success) {
                    toastr.success( data.message , { timeOut: 5000 });
                    $("#reset_sla_plan_modal").modal("hide");
                    
                    get_ticket_table_list();
                }
            }
        });
    }


</script>