$('.form-group small').addClass('d-none');
$(function() {
    orders_table_list = $('#customer_order_table').DataTable();
    customer_subscription_table = $('#customer_subscription').DataTable();
    
    assets_table_list = $('#assets_table_list').DataTable();
    tickets_table_list = $('#ticket_table').DataTable({
        processing: true,
        "scrollX": true,
        pageLength: 20,
        fixedColumns: true,
        "autoWidth": false,
        'columnDefs': [
            { className: "overflow-wrap", targets: "_all" },
            { width: '10px', orderable: false, searchable: false, 'className': 'dt-body-center', targets: 0 },
            { width: '110px', targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] },
            { orderable: false, targets: 0 },
            { orderable: false, targets: 1 }
        ],
        order: [[2, 'asc']],
        createdRow: function(row, data, dataIndex){
            console.log($(data[1]).attr('class'));
            if($(data[1]).attr('class').match('flagged')){
                $(row).addClass('flagged-tr');
            }
        }
    });
    //get_assets_table_list();

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();

        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-secondary');
        
        if($(this).parent().parent().find('table').attr('id') == 'customer_order_table') {
            var column = orders_table_list.column( $(this).attr('data-column') );
            column.visible( ! column.visible() );
        }
        if($(this).parent().parent().find('table').attr('id') == 'ticket-departments-list') {
            var column = $('#ticket-departments-list').DataTable().column( $(this).attr('data-column') );
            column.visible( ! column.visible() );
        }
        
        if($(this).parent().parent().find('#ticket_table').length) {
            var column = tickets_table_list.column( $(this).attr('data-column') );
            column.visible( ! column.visible() );
        }
        
        if($(this).parent().parent().find('table').attr('id') == 'customer_subscription') {
            var column = customer_subscription_table.column( $(this).attr('data-column') );
            column.visible( ! column.visible() );
        }
    } );

    get_ticket_table_list();
    
    var id = '';
    $.ajax({
            type: "post",
            url: "?p=customer-manager&json=1&action=get-customer-orders",
            data:{id:id},
            dataType: 'json',
            cache: false,
            async: false,
            success: function (data) {
                console.log(data);
                $("#customer_order_table tbody").html("");
                    var count = 1;
                    $.each(data, function(key, val){
                        var json = JSON.stringify(data[key]);
                        orders_table_list.row.add([
                        
                        '<a href="#" data-toggle="modal" data-target="#show-order" style="cursor:pointer;">'+['#']+val['number']+'</a>',
                            val['billing']['first_name']+val['billing']['last_name'],
                            val['status'],
                            val['date_created'],
                            ['$']+val['total'],
                            
                // 			'<a style="cursor:pointer;text-align:center" title="Edit Type" onclick="event.stopPropagation();editDepartment('+val['id']+',`'+val['name']+'`);return false;"><i class="mdi mdi-grease-pencil" aria-hidden="true"></i></a>&nbsp;<a style="cursor:pointer;text-align:center" title="Delete Department" onclick="event.stopPropagation();deleteDepartment('+val['id']+');return false;"><i class="fa fa-trash " aria-hidden="true"></i></a>'
                
                        ]).draw( false );
                        count++;
                    });
            customers = data;
            }
        });
    
    var password = $(".user-password-div input[name='password']").val();
    var confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
    var password = $(this).val();
    $(".user-password-div").on('keyup', "input[name='password']", function() {
        var score = 0;
        password = $(".user-password-div input[name='password']").val();
        score = (password.length > 6) ? score+2 : score;
        score = ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) ? score+2 : score;
        score = (password.match(/\d+/)) ? score+2 : score;
        score = (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ? score+2 : score;
        score = (password.length > 10) ? score+2 : score;
        $(".user-password-div .progress .progress-bar").css("width", (score*10)+"%");
    });
    
    $(".user-confirm-password-div").on('keyup', "input[name='confirm_password']", function() {
        password = $(".user-password-div input[name='password']").val();
        confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
        $(".user-confirm-password-div .check-match").removeClass("fa-times fa-check red green");
        if(password==confirm_password){
            $(".user-confirm-password-div .check-match").addClass("fa-check green");
        }else{
        $(".user-confirm-password-div .check-match").addClass("fa-times red");
        }
    });
    
    $(".user-password-div").on('click', '.show-password-btn', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $(".user-password-div input[name='password']");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    
    $(".user-confirm-password-div").on('click','.show-confirm-password-btn',function(){
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $(".user-confirm-password-div input[name='confirm_password']");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });   
    
    $('#update_customer').submit(function (event) {
            event.preventDefault();
            
            let name = $('#update-name').val(),
            email = $('#update-email').val(),
            phone = $('#update-phone').val(),
            address = $('textarea#update-address').val();
            if(name == '' || name == null || typeof name == 'undefined'){
                $('#nameHelp').removeClass('d-none');
                return false
            }
            else if(email == '' || email == null || typeof email == 'undefined'){
                $('#emailHelp').removeClass('d-none');
                return false
            }
            else if(phone == '' || phone == null || typeof phone == 'undefined'){
                $('#phoneHelp').removeClass('d-none');
                return false
            }
            else if(address == '' || address == null || typeof address == 'undefined'){
                $('#addressHelp').removeClass('d-none');
                return false
            }
            var formData = new FormData($(this)[0]);
            var action = $(this).attr('action');
            var method = $(this).attr('method');
            $.ajax({
                type: method,
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data); 
                        $('.form-group small').addClass('d-none');
                        getCustomer();
                        Swal.fire({
                            position: 'top-end',
                            icon: data.success ? 'success' : 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500
                        });
                        // 
                },
                failure: function (errMsg) {
                    console.log(errMsg);
                }
            });
        });

        $('#new-company').click(function(){
            $('#save_company_form')[0].reset();
            $('#company_model').modal('show');
        })
        $("#save_company_form").submit(function (event) {

        event.preventDefault();
        var formData = new FormData($(this)[0]);
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        
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
                //console.log(data)
                if (data['success'] == true) {
                    $("#save_company_form").trigger("reset");
                    $('#company_model').modal('hide');

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2500
                    })
                    getCustomer()
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2500
                    })
                }
            }
        });

    });
    
    $('#customer_subscription tbody').on('click', 'tr', function () {
        detailSubscription(customer_subscription_table.row( this ).index());
    });

    detailSubscription();
    console.log({subscriptionsList});
    console.log({customer});
});

function getCustomer(){
    $.ajax({
        type: "get",
        url: "{{asset('/customer-profile/'.$customer->id.'/json')}}",
        dataType: 'json',
        cache: false,
        success: function (data) {
            $('#customer-name').text(data.customer.name);
            $('#company-name').text(data.customer.company.name);
            $('#customer-phone').text(data.customer.phone);
            $('#customer-address').text(data.customer.address);
            $('#customer-email').text(data.customer.email);
            $('#update-company').empty();
            data.company.forEach(element => {
                $('#update-company').append(`<option value="${element.id}" ${element.id == data.customer.company_id ? 'selected' : ''} > ${element.name}</option>`);
            });
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
    });
}

function detailSubscription(index) {
    let sub = subscriptionsList[index];
    let detail = `<h4 class="col-12">Subscription Details</h4>
    <div class="col-6  "><label>Woo ID : </label> <strong>`+sub.woo_id+`</strong></div>`;

    if(sub.status){
        detail += '<div class="col-6 "><label>Status : </label> <strong>'+sub.status+'</strong></div>';
    }
    if(sub.order_key){
        detail += '<div class="col-6 "><label>Order Key : </label><strong>'+sub.order_key+'</strong></div>';
    }
    if(sub.currency){
        detail += '<div class="col-6 "><label>Currency : </label><strong>'+sub.currency+'</strong></div>';
    }
    if(sub.version){
        detail += '<div class="col-6 "><label>Version : </label><strong>'+sub.version+'</strong></div>';
    }
    if(sub.prices_include_tax){
        detail += '<div class="col-6 "><label>Prices With Tax : </label><strong>'+sub.prices_include_tax+'</strong></div>';
    }
    if(sub.discount_total){
        detail += '<div class="col-6 "><label>Discount Total : </label><strong>'+sub.discount_total+'</strong></div>';
    }
    if(sub.discount_tax){
        detail += '<div class="col-6 "><label>Discount Tax : </label><strong>'+sub.discount_tax+'</strong></div>';
    }
    if(sub.shipping_total){
        detail += '<div class="col-6 "><label>Shipping Total : </label><strong>'+sub.shipping_total+'</strong></div>';
    }
    if(sub.shipping_tax){
        detail += '<div class="col-6 "><label>Shipping Tax : </label><strong>'+sub.shipping_tax+'</strong></div>';
    }
    if(sub.cart_tax){
        detail += '<div class="col-6 "><label>Cart Tax : </label><strong>'+sub.cart_tax+'</strong></div>';
    }
    if(sub.total){
        detail += '<div class="col-6 "><label>Total : </label><strong>'+sub.total+'</strong></div>';
    }
    if(sub.total_tax){
        detail += '<div class="col-6 "><label>Total Tax : </label> <strong>'+sub.total_tax+'</strong></div>';
    }
    if(sub.payment_method){
        detail += '<div class="col-6 "><label>Payment Method : </label> <strong>'+sub.payment_method+'</strong></div>';
    }
    if(sub.payment_method_title){
        detail += '<div class="col-6 "><label>Payment Method Title : </label> <strong>'+sub.payment_method_title+'</strong></div>';
    }
    if(sub.created_via){
        detail += '<div class="col-6 "><label>Created Via : </label> <strong>'+sub.created_via+'</strong></div>';
    }
    if(sub.customer_note){
        detail += '<div class="col-6 "><label>Customer Note : </label> <strong>'+sub.customer_note+'</strong></div>';
    }
    if(sub.date_completed){
        detail += '<div class="col-6 "><label>Date Completed : </label> <strong>'+sub.date_completed+'</strong></div>';
    }
    if(sub.date_paid){
        detail += '<div class="col-6 "><label>Date Paid : </label> <strong>'+sub.date_paid+'</strong></div>';
    }
    if(sub.cart_hash){
        detail += '<div class="col-6 "><label>Cart Hash : </label><strong>'+sub.cart_hash+'</strong></div>';
    }
    if(sub.billing_period){
        detail += '<div class="col-6 "><label>Billing Period : </label><strong>'+sub.billing_period+'</strong></div>';
    }
    if(sub.billing_interval){
        detail += '<div class="col-6 "><label>Billing Interval : </label><strong>'+sub.billing_interval+'</strong></div>';
    }
    if(sub.start_date){
        detail += '<div class="col-6 "><label>Start Date : </label><strong>'+sub.start_date+'</strong></div>';
    }
    if(sub.trial_end_date){
        detail += '<div class="col-6 "><label>Trial End Date : </label><strong>'+sub.trial_end_date+'</strong></div>';
    }
    if(sub.next_payment_date){
        detail += '<div class="col-6 "><label>Next Payment Date : </label><strong>'+sub.next_payment_date+'</strong></div>';
    }
    if(sub.end_date){
        detail += '<div class="col-6 "><label>End Date : </label><strong>'+sub.end_date+'</strong></div>';
    }
    if(sub.customer_ip_address){
        detail += '<div class="col-6 "><label>Customer IP Address : </label> <strong>'+sub.customer_ip_address+'</strong></div>';
    }
    if(sub.customer_user_agent){
        detail += '<div class="col-12"><label>Customer User Agent : </label> <strong>'+sub.customer_user_agent+'</strong></div>';
    }

    $('#detailsCard').html('<div class="row p-3">'+detail+'</div>');
    $('#detailsCard').parent().parent().show();
}

function ShowTicketModel(){
    $('#dept_id').val('').trigger("change");
    $('#priority').val('').trigger("change");
    $('#type').val('').trigger("change");
    $("#save_tickets").trigger("reset");
    $('#ticketModal').modal('show');
}

$("#save_tickets").submit(function (event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');

    var subject = $('#subject').val().replace(/\s+/g, " ").trim();
    var dept_id = $('#dept_id').val();
    var priority = $('#priority').val();
    var type = $('#type').val();
    var ticket_detail = $('#ticket_detail').val();
    if(subject == '' || subject == null){
        $('#select-subject').css('display', 'block');
        return false;
    }else if(dept_id == '' || dept_id == null){
        $('#select-department').css('display', 'block');
        return false;
    }
    else if(priority == '' || priority == null){
        $('#select-priority').css('display', 'block');
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

    formData.append('status', open_status_id);
    formData.append('customer_id', customer.id);

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

            if (data.success) {

                $('#ticketModal').modal('hide');
                $("#save_tickets").trigger("reset");
                $('#dept_id').val('').trigger("change");
                $('#priority').val('').trigger("change");
                $('#type').val('').trigger("change");
                get_ticket_table_list();
            }    
            Swal.fire({
                position: 'top-end',
                icon: (data.success) ? 'success' : 'error',
                title: data.message,
                showConfirmButton: false,
                timer: 2500
            })
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
    });

});

function get_ticket_table_list() {
    tickets_table_list.clear().draw();
    $('#btnDelete,#btnBin').show();
    $('#btnBack,#btnRecycle').hide();
    $.ajax({
        type: "get",
        url: "{{asset('/get-customer-tickets')}}",
        data: {customer_id: customer.id},
        dataType: 'json',
        cache: false,
        success: function (data) {
            console.log(data.tickets);
            ticketsList = data.tickets;

            $('#my_tickets_count').html(ticketsList.length);
            $('#open_tickets_count').html(ticketsList.filter(itm => itm.status == open_status_id).length);
            $('#closed_tickets_count').html(ticketsList.filter(itm => itm.status == closed_status_id).length);

            listTickets();
        }
    });
}

function listTickets(){
    var count = 1;

    let ticket_arr = ticketsList;

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
        var name = val['subject'];
            var shortname = '';
            if (name.length > 20) {
                shortname = name.substring(0, 20) + " ...";
            }else{
                shortname = name;
            }
            
            tickets_table_list.row.add([
                '<input type="checkbox" name="chk_list[]" value= "'+val['id']+'">',
                '<div class="text-center '+flagged+'"><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, '+val['id']+');"></span></div>',
                val['status_name'],
                '<a href="{{asset("/ticket-details")}}/'+val['id']+ '">' + shortname + '</a>',
                custom_id,
                prior,
                val['customer_name'],
                val['lastReplier'],
                val['replies'],
                '---',
                '---',
                '---',
                (val.hasOwnProperty('tech_name')) ? val['tech_name'] : '---',
                val['department_name'],
                val['created_at'],
            ]).draw(false);
            count++;
    });
}

function flagTicket(ele, id){
    $.ajax({
        type:'post',
        url: "{{asset('/flag_ticket')}}",
        data:{id:id},
        success: function (data) {

            if (data) {

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

// Handle click on "Select all" control
$('#select-all').on('click', function() {
    // Get all rows with search applied
    var rows = customerTable.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
});

// Handle click on checkbox to set state of "Select all" control
$('#ticket_table tbody').on('change', 'input[type="checkbox"]', function() {
    // If checkbox is not checked
    if (!this.checked) {
        var el = $('#select-all').get(0);
        // If "Select all" control is checked and has 'indeterminate' property
        if (el && el.checked && ('indeterminate' in el)) {
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
        }
    }
});