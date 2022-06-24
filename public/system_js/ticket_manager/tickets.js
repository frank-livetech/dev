
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});
let tickets_table_list = '';
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
$(function () {
    console.log({ticket_format});
    console.log({activity_logs});
    tickets_table_list = $('#ticket-table-list').DataTable({
        createdRow: function(row, data, dataIndex){
            console.log($(data[1]).attr('class'));
            if($(data[1]).attr('class').match('flagged')){
                $(row).addClass('flagged-tr');
            }
        }
    });

    tickets_logs_list = $('#ticket-logs-list').DataTable();

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();

        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-secondary');
        
        if($(this).parent().parent().find('table').attr('id') == 'ticket-table-list') {
            var column = tickets_table_list.column( $(this).attr('data-column') );
            column.visible( ! column.visible() );
        }
        if($(this).parent().parent().find('table').attr('id') == 'ticket-logs-list') {
            var column = tickets_logs_list.column( $(this).attr('data-column') );
            column.visible( ! column.visible() );
        }
    } );

    get_ticket_table_list();
    $('input[type="checkbox"]').click(function () {
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

    // $('#ticket-table-list').parent().css('overflow', 'auto');
});

function filterTicketsList(){
    listTickets();
}

$('.master_check').click(function() {
    if ($(this).is(':checked')) {
        $('tbody input').attr('checked', true);
    } else {
        $('tbody input').attr('checked', false);
    }
});

function get_ticket_table_list() {
    tickets_table_list.clear().draw();
    $('#btnDelete,#btnBin').show();
    $('#btnBack,#btnRecycle').hide();
    $.ajax({
        type: "get",
        url: "{{asset('/get-tickets')}}",
        data: "",
        dataType: 'json',
        cache: false,
        success: function (data) {
            console.log(data.tickets);
            ticketsList = data.tickets;

            listTickets();
        }
    });
}

function get_deleted_ticket_table_list() {
    tickets_table_list.clear().draw();
    $('#btnDelete,#btnBin').hide();
    $('#btnBack,#btnRecycle').show();
    $.ajax({
        type: "get",
        url: "{{asset('/get-deleted-tickets')}}",
        data: "",
        dataType: 'json',
        cache: false,
        success: function (data) {
            console.log(data.tickets);
            ticketsList = data.tickets;

            listTickets();
        }
    });
}

function listTickets(){
    var count = 1;

    let ticket_arr = ticketsList;
    if($('#statusFilter').val() && $('#statusFilter').val() != 'all'){
        ticket_arr = ticket_arr.filter(item => item.status == $('#statusFilter').val());
    }
    if($('#deptFilter').val() && $('#deptFilter').val() != 'all'){
        ticket_arr = ticket_arr.filter(item => item.dept_id == $('#deptFilter').val());
    }

    console.log({ticket_arr});
    // $("#ticket-table-list tbody").html("");
    tickets_table_list.clear().draw();

    $.each(ticket_arr, function (key, val) {
        var json = JSON.stringify(data[key]);

        let prior = val['priority_name'];
        if(val['priority_color']){
            prior = '<div class="text-center text-white" style="background-color: '+val['priority_color']+';">'+val['priority_name']+'</div>';
        }
        let flagged = '';
        if(val['is_flagged'] == 1){
            flagged = 'flagged';
        }

        let custom_id = val['coustom_id'];
        if(Array.isArray(ticket_format)){
            ticket_format = ticket_format[0];
        }

        if(ticket_format.ticket_format == 'sequential'){
            custom_id = val['seq_custom_id'];
        }
        
        tickets_table_list.row.add([
            '<input type="checkbox" name="chk_list[]" value= "'+val['id']+'">',
            '<div class="text-center '+flagged+'"><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, '+val['id']+');"></span></div>',
            val['status_name'],
            '<a href="{{asset("/ticket-details")}}/'+val['id']+ '">' + val[
                'subject'] + '</a>',
            custom_id,
            prior,
            val['customer_name'],
            val['lastReplier'],
            val['replies'],
            '---',
            '---',
            '---',
            val['tech_name'],
            val['department_name'],
            val['created_at'],
        ]).draw(false);
        count++;
    });
}

$("#btnDelete").click(function () {
        var tickets = [];
    $("tbody input:checked").each(function () {
        tickets.push( $(this).val());
        //console.log(value);
    });
        $.ajax({
        type:'post',
        url: "{{asset('/del_tkt')}}",
        data:{tickets},
        success: function (data) {

            if (data.success) {

                get_ticket_table_list();
                
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data Deleted Successfully!',
                    showConfirmButton: false,
                    timer: 2500
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                })
            }
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
        
    })
});

$("#btnRecycle").click(function () {
    var tickets = [];
    $("tbody input:checked").each(function () {
        tickets.push( $(this).val());
    });
        $.ajax({
        type:'post',
        url: "{{asset('/recycle_tickets')}}",
        data:{tickets},
        success: function (data) {

            if (data.success) {

                get_ticket_table_list();
                
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Restored Successfully!',
                    showConfirmButton: false,
                    timer: 2500
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                })
            }
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
        
    })
});

function flagTicket(ele, id){
    $.ajax({
        type:'post',
        url: "{{asset('/flag_ticket')}}",
        data:{id:id},
        success: function (data) {

            if (data) {

                // get_ticket_table_list();
                $(ele).closest('tr').toggleClass('flagged-tr');
                
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        },
        failure: function (errMsg) {
            console.log(errMsg);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: errMsg,
                showConfirmButton: false,
                timer: 2500
            });
        } 
    });
}

function ShowCalendarModel(){
    var element = document.getElementById('calendar');
    element.innerHTML = '';
    var events = [
        // {'Date': new Date(2020, 11, 7), 'Title': 'Doctor appointment at 3:25pm.'},
        // {'Date': new Date(2020, 11, 18), 'Title': 'New Garfield movie comes out!', 'Link': 'https://garfield.com'},
        // {'Date': new Date(2020, 11, 31), 'Title': '25 year anniversary', 'Link': 'https://www.google.com.au/#q=anniversary+gifts'},
    ];
    for(let i in tickets_followups){
        if(tickets_followups[i].date != null){
            let dd = new Date(tickets_followups[i].date);
            events.push({
                Date: new Date(dd.getFullYear(), dd.getMonth(), dd.getDate()),
                Title: tickets_followups[i].follow_up_notes
            });
        }
    }
    caleandar(element, events, settings);

    $('#calendarModal').modal('show');
}

function ShowTicketModel(){

        $('#dept_id').val('').trigger("change");
        $('#status').val('').trigger("change");
        $('#priority').val('').trigger("change");
        $('#type').val('').trigger("change");
        $('#customer_id').val('').trigger("change");
        $("#save_tickets").trigger("reset");
        $('#new-customer').css('display', 'none');
        $('#ticket').modal('show');
}

$("#save_tickets").submit(function (event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');

    var subject = $('#subject').val();
    var dept_id = $('#dept_id').val();
    var status = $('#status').val();
    var priority = $('#priority').val();
    var assigned_to = $('#assigned_to').val();
    var type = $('#type').val();
    var customer_id = $('#customer_id').val();
    var ticket_detail = $('#ticket_detail').val();
    if(subject == '' || subject == null){
        $('#select-subject').css('display', 'block');
        return false;
    }else if(dept_id == '' || dept_id == null){
        $('#select-department').css('display', 'block');
        return false;
    }
    else if(status == '' || status == null){
        $('#select-status').css('display', 'block');
        return false;
    }
    else if(priority == '' || priority == null){
        $('#select-priority').css('display', 'block');
        return false;
    }
    else if(assigned_to == '' || assigned_to == null){
        $('#select-assign').css('display', 'block');
        return false;
    }
    else if(type == '' || type == null){
        $('#select-type').css('display', 'block');
        return false;
    }
    else if(ticket_detail == '' || ticket_detail == null){
        $('#pro-details').css('display', 'block');
        return false;
    }
    if(!$('#new-form').prop('checked')){
        if(customer_id == '' || customer_id == null){
        $('#select-customer').css('display', 'block');
        return false;}
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
    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function (data) {

            if (data) {

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
                
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Ticket Added Successfully!',
                    showConfirmButton: false,
                    timer: 2500
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                })
            }
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
    });

});