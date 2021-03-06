
<script>
    // Ticket Details Script Blade
    var sla_plans_list =  {!! json_encode($sla_plans) !!};
    var ticket_slaPlan =  {!! json_encode($ticket_slaPlan) !!};
    var companies_list =  {!! json_encode($companies) !!};
    var ticket_customer =  {!! json_encode($ticket_customer) !!};
    var temp_sel_customer =  ticket_customer.id;
    var ticket_details =  {!! json_encode($details) !!};
    let res_templates_list = {!! json_encode($responseTemplates) !!};
    var all_users = {!! json_encode($allusers) !!};
    var tagUsers = {!! json_encode($tagUsers) !!};
    var g_followUps = '';
    var active_user = {!! json_encode($active_user) !!};
    var ticket =  {!! json_encode($details) !!};
    var ticketReplies =  {!! json_encode($details->ticketReplies) !!};
    var staff_list =  {!! json_encode($users) !!};
    let all_staff_ids = staff_list.map(a => a.id);

    var gl_color_notes = 'rgb(255, 230, 177)';
    var user_photo_url = "{{ URL::asset('files/user_photos/user-photo.jpg') }}";
    var update_ticket_route = "{{asset('update_ticket')}}";
    let get_codes_route = "{{asset('/get_all_short_codes')}}";

    // var ticket_attach_path = `{{asset('public/files')}}`;
    // var ticket_attach_path_search = 'public/files';
    var ticket_attach_path = `{{asset('storage')}}`;
    var ticket_attach_path_search = 'storage';
    var publish_reply_route = "{{asset('publish-ticket-reply')}}";
    var ticket_followup_route = "{{asset('get-ticket-follow-up')}}";
    var delete_followup_route = "{{asset('del-ticket-follow-up')}}";
    var update_followup_route = "{{asset('update-ticket-follow-up')}}";
    var profile_img_path = "{{asset('/files/asset_img/1601560516.png')}}";
    var del_ticket_route = "{{asset('/del-ticket-note')}}";
    var ticket_notes_route = "{{asset('/get-ticket-notes')}}";
    let customers = @json($all_customers);
    let companies = @json($all_companies);

    //Flag Ticket Definings
    var flag_ticket_route = "{{asset('/flag_ticket')}}";
    let currentTime = [];

    // assets data definitions
    var get_assets_route = "{{asset('/get-assets')}}";
    var del_asset_route = "{{asset('/delete-asset')}}";
    var save_asset_records_route = "{{asset('/save-asset-records')}}";
    var templates_fetch_route = "{{asset('/get-asset-templates')}}";
    var ticket_notify_route = "{{asset('/ticket_notification')}}";
    var get_ticket_latest_log = "{{asset('/get_ticket_log')}}";
    var route_searchEmails = "{{asset('/searchEmails')}}";
    var templates = null;
    var asset_customer_uid = ticket_customer.id;
    var asset_company_id = ticket_customer.company_id;
    var asset_project_id = '';
    var asset_ticket_id = ticket.id;

    var show_asset = "{{asset('/show-single-assets')}}";
    var update_asset = "{{asset('/update-assets')}}";
    var general_info_route = "{{asset('/general-info')}}";
    var set_sla_plan_route = "{{asset('/set-sla-plan')}}";


    $( document ).ready(function() {

        $("#dropD ").find(".select2").hide();
        $("#dropD ").find("h5").show();
        selectD();

        var userlist = [];
        all_users.forEach((element,index) => {
            // userlist.push(element.name + ' (' + element.email + ')');
            userlist[index] = {key: element.name,value:  element.name + ' (' + element.email + ')'};
        });


        $('#note').atwho({
            at: "@",
            data:userlist,
        });




        if(ticket.customer_id != null) {
            let findCustomer = customers.find(item => item.id == ticket.customer_id);

            if(findCustomer != null) {
                $("#tkt_customer_id").val(findCustomer.id).trigger("change");

                if(findCustomer.company != null) {
                    $("#tkt_company_id").val(findCustomer.company.id).trigger("change");
                }
            }
        }
    });

    $("#tkt_customer_id").on("change" , function() {
        $("#tkt_company_id").empty();
        let root = `<option value="">Choose</option>`;
        if($(this).val() != '') {
            let item = customers.find(item => item.id == $(this).val() );
            if(item != null) {
                if(item.company_id != null) {
                    let company = companies.find(com => com.id == item.company_id);
                    let option = `<option value="${company.id}"> ${company.name} </option>`;
                    $("#tkt_company_id").append(root + option).trigger('change');
                }
            }
        }else{
            let option = ``;
            for(let data of companies)  {
                option += `<option value="${data.id}"> ${data.name} </option>`;
            }
            $("#tkt_company_id").append(root + option).trigger('change');
        }
    });

    $('.br-white').click(function(event){
        event.stopPropagation();

        $("#dropD ").find(".select2").toggle();
        $("#dropD ").find("h5").toggle();
        $(".chim ").find("a").toggleClass("pt-6");
    });

    $("#flag").click(function(){
        flagTicket();
        $('.chim').toggleClass("flagSpot");
    });

    $(".select2").change(function () {
        selectD();
    })

    function selectD(){
        var Priority =  $("#prio-label").find(".select2 option:selected").text();
        $("#prio-h5").text(Priority);
        var Dep =  $("#dep-label").find(".select2 option:selected").text();
        $("#dep-h5").text(Dep);

        var Tech = ``
        $("#tech-label").find(".select2 option:selected").each(function () {
            Tech += '<span class="badge bg-primary tech-spa" >' +$(this).text()+ '</span>';
        });
        $("#tech-h5").html(Tech);

        var Status =  $("#status-label").find(".select2 option:selected").text();
        $("#status-h5").text(Status);
        var Type =  $("#type-label").find(".select2 option:selected").text();
        $("#type-h5").text(Type);

    }


    $("#csearch").keyup(function(e) {
        let value = $(this).val();
        if (!value) {
            $('#search_customer_result').html('');
            $("#cust_loader").hide();
        } else {
            if (e.keyCode == 8) {
                $('#search_customer_result').html('');
                $("#cust_loader").hide();
            }

            // key is either alphabet or number or dot or @ or backspace or space is pressed
            if ((e.keyCode >= 64 && e.keyCode <= 90) || (e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode == 64 || e.keyCode == 46 || e.keyCode == 8 || e.keyCode == 32) {
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
                                    var phone = element.phone ? element.phone : 'Phone not added';
                                    var company = element.name ? element.name : 'Company not provided';

                                    if(ticket_customer.id == element.id) {
                                        result += `<div style="font-size:14px" class="bg-success text-left font-weight-bold text-dark mt-2 p-2 border shadow-sm rounded">${element.first_name} ${element.last_name} (ID : ${element.id}) | ${company} | ${element.email} | ${phone}</div>`;
                                    } else {
                                        result += `<a href="javascript:setCustomerUpdates(${element.id}, '${element.company_id}')">
                                            <div style="font-size:14px" class="bg-light text-left font-weight-bold text-dark mt-2 p-2 border shadow-sm rounded">${element.first_name} ${element.last_name} (ID : ${element.id}) | ${company} | ${element.email} | ${phone}</div>
                                        </a>`;
                                    }
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
            }
        }
    });

    function setCustomerUpdates(cid='', comp='') {
        if(!cid && temp_sel_customer) cid = temp_sel_customer;

        if(!$('#username-fill').val()) {
            $('#username-fill-err').show();
            $('#username-fill').focus();
            return false;
        } else $('#username-fill-err').hide();

        var phoneno = $('#phone-fill').val();
        if(!isNaN(phoneno) && phoneno.length > 10) {
            $('#phone-fill-err').hide();
        } else {
            $('#phone-fill-err').show();
            $('#phone-fill').focus();
            return false;
        }

        Swal.fire({
            title: 'Do you want to update ticket customer?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{url('update-ticket-customer')}}",
                    data: {
                        ticket_id: asset_ticket_id,
                        customer_id : cid,
                        email: $('#username-fill').val(),
                        phone: $('#phone-fill').val()
                    },
                    dataType: 'json',
                    beforeSend: function(data) {
                        // $("#saveBtn").hide();
                        $("#processbtn").show();
                    },
                    success: function(data) {
                        if (data.status_code == 200 && data.success == true) {
                            alertNotification('success', 'Success' , data.message);

                            ticket_customer = data.data;
                            temp_sel_customer = ticket_customer.id;

                            $("#tkt_cust_id").val(cid);
                            $('#cst-name').text(ticket_customer.first_name+' '+ticket_customer.last_name);
                            $('#cst-email').text(ticket_customer.email);
                            $('#cst-direct-line').text(ticket_customer.phone);

                            // ticket_customer.company_id = comp;
                            setCustomerCompany();
                            $("#pro_edit").modal('hide');
                        } else {
                            alertNotification('error', 'Error' , data.message);
                        }
                    },
                    complete: function(data) {
                        // $("#saveBtn").show();
                        $("#processbtn").hide();
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });
            }
        });
    }

    function selTicketCustomer(id, email, phn) {
        temp_sel_customer = id;

        $('#search_customer_result .bg-success').addClass('bg-light');
        $('#search_customer_result .bg-success').removeClass('bg-success');
        $('#cts-'+id).removeClass('bg-light');
        $('#cts-'+id).addClass('bg-success');

        $('#username-fill').val(email);
        $('#phone-fill').val(phn);
    }

    function ticketUrlCopy() {

        let url = window.location.href;
        let $input = $("<input>");
        $('body').append($input);

        $input.val(url).select();
        document.execCommand('copy');
        $input.remove();

        $("#c_url").fadeIn();

        setInterval(() => {
            $("#c_url").fadeOut();
        }, 1000);
    }

    function openProModal() {

        $("#up_tkt_cust_title").text("Update Ticket Properties");
        $('#csearch').val('');
        $('#ct-search').val('');

        var phone = ticket_customer.phone ? ticket_customer.phone : 'Phone not added';
        var company = ticket_customer.company_name ? ticket_customer.company_name : 'Company not provided';

        $('#search_customer_result').html(`<div style="font-size:14px;font-weight:600;color:white !important" class="bg-success text-left font-weight-bold text-dark p-2 border shadow-sm rounded">${ticket_customer.first_name} ${ticket_customer.last_name} (ID : ${ticket_customer.id}) | ${company} | ${ticket_customer.email} | ${phone}</div>`);

        $('#username-fill').val(ticket_customer.email);
        $('#phone-fill').val(ticket_customer.phone);


        $("#tkt_cust_id").val("");
        $("#tkt_cust_comp_id").val("");

        $('#pro_edit').modal('show');
    }

    function newCustomer(mode) {
        if(mode == 'cancel') {
            $("#up_tkt_cust_title").text("Update Ticket Properties");
            $('#new-cust-cont').hide();
            $('.newcustbtn').hide();
            $('#normal-cut-selc').show();
            $('.upt-cust-btn').show();
            $("#new_company").hide();
        } else if(mode == 'save') {
            let formData = new FormData($('#save_newtickcust_form')[0]);
            formData.append('ticket_id', asset_ticket_id);
            formData.append('new_customer', 1);
            if($('#login_account').prop('checked')) formData.append('customer_login', 1);

            $.ajax({
                type: "POST",
                url: $('#save_newtickcust_form').attr('action'),
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success: function(data) {
                    if (data.success) {
                        alertNotification('success', 'Success' , data.message);

                        ticket_customer = data.data;

                        $("#tkt_cust_id").val(ticket_customer.id);
                        $('#cst-name').text(ticket_customer.first_name+' '+ticket_customer.last_name);
                        $('#cst-email').text(ticket_customer.email);
                        $('#cst-direct-line').text(ticket_customer.phone);

                        // ticket_customer.company_id = comp;
                        setCustomerCompany();
                        $("#pro_edit").modal('hide');

                        newCustomer('cancel');
                        $('#save_newtickcust_form').trigger('reset');
                    } else {
                        alertNotification('success', 'Success' , data.message);
                    }
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });

        } else {
            $("#up_tkt_cust_title").text("Ticket New Customer");
            $('#normal-cut-selc').hide();
            $('.upt-cust-btn').hide();
            $('.newcustbtn').show();
            $('#new-cust-cont').show();
            $("#new_company").hide();
        }
    }

    function closeModal() {
        $("#pro_edit").modal('hide');

        $("#first_name_update").val() != ticket_customer.first_name ?  $("#first_name_update").val(ticket_customer.first_name) : '';
        $("#last_name_update").val() != ticket_customer.last_name ?  $("#last_name_update").val(ticket_customer.last_name) : '';
        $("#phn_update").val() != ticket_customer.phone ?  $("#phn_update").val(ticket_customer.phone) : '';
        $("#company_list").val() != ticket_customer.company_id ?  $("#company_list").val(ticket_customer.company_id) : '';
    }

    function searchTicketCustomer() {
        let value = $('#ct-search').val();
        if (value) {
            $.ajax({
                url: "{{asset('/search-customer')}}",
                type: "POST",
                data: {
                    id: value
                },
                dataType: 'json',
                beforeSend: function(data) {
                    $("#search-customer .fa-search").hide();
                    $("#cust_loader").show();
                },
                success: function(data) {
                    console.log(data, "search result");
                    $('#search_customer_result').show();
                    var result = ``;

                    if (data.length > 0) {

                        data.forEach(element => {
                            var phone = element.phone ? element.phone : 'Phone not added';
                            var company = element.name ? element.name : 'Company not provided';

                            if(ticket_customer.id == element.id) {
                                result += `<div style="font-size:14px" class="bg-success text-left font-weight-bold text-dark mt-2 p-2 border shadow-sm rounded">${element.first_name} ${element.last_name} (ID : ${element.id}) | ${company} | ${element.email} | ${phone}</div>`;
                            } else {
                                result += `<a href="javascript:selTicketCustomer(${element.id}, '${element.email}', '${phone}')">
                                    <div style="font-size:14px" class="bg-light text-left font-weight-bold text-dark mt-2 p-2 border shadow-sm rounded" id="cts-${element.id}">${element.first_name} ${element.last_name} (ID : ${element.id}) | ${company} | ${element.email} | ${phone}</div>
                                </a>`;
                            }
                        });
                    } else {
                        result = `<span class="text-center">No customer matched! Click the new customer button below to add new one</span>`;
                    }
                    $('#search_customer_result').html(result);
                },
                complete: function(data) {
                    $("#cust_loader").hide();
                    $("#search-customer .fa-search").show();
                },
                failure: function(data) {
                    console.log(data);
                    $("#cust_loader").hide();
                    $("#search-customer .fa-search").show();
                }
            });
        }
    }

    const ticketCustomer = {

        select_customer : (value) => {
            var comp_id = $("#tkt_all_customers").find(':selected').data('comp');
            $("#tkt_cust_id").val(value);
            $("#tkt_cust_comp_id").val(comp_id);
        },

        update_customer: () => {

            var tkt_cc = $("#tkt_cc").val();
            var tkt_bcc = $("#tkt_bcc").val();
            var cust_id = $("#tkt_cust_id").val();
            var tkt_merge_id = $("#tkt_merge_id").val();

            if(!cust_id && temp_sel_customer) cust_id = temp_sel_customer;

            // Swal.fire({
            //     title: 'Do you want to update ticket customer?',
            //     icon: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#d33',
            //     confirmButtonText: 'Yes'
            // }).then((result) => {
            //     if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{url('update-ticket-customer')}}",
                        data: {
                            ticket_id: asset_ticket_id,
                            customer_id : cust_id,
                            tkt_cc: tkt_cc,
                            tkt_bcc: tkt_bcc,
                            tkt_merge_id:tkt_merge_id
                        },
                        dataType: 'json',
                        beforeSend: function(data) {
                            // $("#saveBtn").hide();
                            $("#processbtn").show();
                        },
                        success: function(data) {
                            if (data.status_code == 200 && data.success == true) {
                                if(data.message == 'Ticket merged successfully'){

                                    alertNotification('success', 'Success' , data.message);
                                    window.location.href = "{{route('ticket_management.index')}}";

                                }else{
                                    console.log('here')
                                    alertNotification('success', 'Success' , data.message);
                                    $("#pro_edit").modal('hide');
                                    ticket_customer = data.data;
                                    temp_sel_customer = ticket_customer.id;
                                    $("#staff_as_customer").css('display','none');
                                    $(".type_bdge").text('User')
                                    // $("#tkt_cust_id").val(cid);
                                    $('#cst-name').text(ticket_customer.first_name+' '+ticket_customer.last_name);
                                    $('#cst-email').text(ticket_customer.email);
                                    $('#cst-direct-line').text(ticket_customer.phone);
                                    var cc_vals = $("#tkt_cc").val();
                                    var bcc_vals = $("#tkt_bcc").val();

                                    if(cc_vals != '' || cc_vals != null || cc_vals != undefined){
                                        $('#to_mails').tagsinput()[0].removeAll();
                                        cc_vals = cc_vals.split(",");
                                        var tagInputEle = $('#to_mails');
                                        tagInputEle.tagsinput();

                                        for(var i = 0; i<cc_vals.length;i++){
                                            tagInputEle.tagsinput('add', cc_vals[i]);
                                        }
                                            $('.cc_email_field').show();
                                            $('#show_cc_email').prop('checked', true);

                                    }
                                    if(bcc_vals != '' || bcc_vals != null || bcc_vals != undefined){
                                        $('#bcc_emails').tagsinput()[0].removeAll();
                                        bcc_vals = bcc_vals.split(",");
                                        var tagInputEle = $('#bcc_emails');
                                        tagInputEle.tagsinput();

                                        for(var i = 0; i<bcc_vals.length;i++){
                                            tagInputEle.tagsinput('add', bcc_vals[i]);
                                        }
                                        $('.bcc_email_field').show();
                                        $('#show_bcc_emails').prop('checked', true);
                                    }
                                    if($("#tkt_cc").val() == ''){
                                        $('.cc_email_field').hide();
                                        $('#show_cc_email').prop('checked', false);
                                    }
                                    if($("#tkt_bcc").val() == ''){
                                        $('.bcc_email_field').hide();
                                        $('#show_bcc_emails').prop('checked', false);
                                    }

                                    $('#to_mails').val($("#tkt_cc").val());
                                    $('#bcc_emails').val($("#tkt_bcc").val());
                                    // ticket_customer.company_id = comp;
                                    setCustomerCompany();

                                }

                            } else {
                                alertNotification('error', 'Error' , data.message );
                            }
                        },
                        complete: function(data) {
                            // $("#saveBtn").show();
                            $("#processbtn").hide();
                        },
                        error: function(e) {
                            console.log(e)
                        }
                    });
            //     }
            // });
        },

        openCompany : () => {
            $("#new_company_field").val("1");
            $("#new_company_input").hide();
            $("#new_company").show();
            $("#exists_comp").show();
        },

        closeCompany: () => {
            $("#new_company_input").show();
            $("#new_company").hide();
            $("#new_company_field").val("0");
            $("#exists_comp").hide();
        }

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
                    url: "{{url('recycle_tickets')}}",
                    data: {
                        tickets
                    },
                    success: function(data) {

                        if (data.success) {

                            // send mail notification regarding ticket action
                            ticket_notify(tickets[tickets.length - 1], 'ticket_update', 'Restored');

                            alertNotification('success', 'Success' , 'Recycled Successfully!');
                            window.location.href = "{{route('ticket_management.index')}}";

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


    function spamUser(id){

        Swal.fire({
            title: 'Are you sure you want to spam this user? All data will be removed!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    url: "{{url('spam_user')}}",
                    data: {
                        ticket_id:id
                    },
                    success: function(data) {

                        if (data.success) {
                            alertNotification('success', 'Success' , data.message);
                            window.location.href = "{{route('ticket_management.index')}}";
                            console.log(data);

                        } else {

                        }
                    },
                    failure: function(errMsg) {
                        console.log(errMsg);
                    }

                })
            }
        });

    }

</script>
