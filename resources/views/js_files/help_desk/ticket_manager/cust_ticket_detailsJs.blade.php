
<script>

    // Cust Ticket Details Script Blade
    
    $( document ).ready(function() {
        $("#dropD ").find(".select2").hide();
        $("#dropD ").find("h5").show();
        selectD();
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
        var Tech =  $("#tech-label").find(".select2 option:selected").text();
        $("#tech-h5").text(Tech);    
        var Status =  $("#status-label").find(".select2 option:selected").text();
        $("#status-h5").text(Status);    
        var Type =  $("#type-label").find(".select2 option:selected").text();
        $("#type-h5").text(Type);    

    }
  
</script>
<script>
    $(document).ready(function() {
        // $.ajax({
        //     type: "GET",
        
        //     url: "{{asset('/get_company_lookup')}}",
        //     dataType: 'json',
        //     beforeSend:function(data) {
        //         $('.loader_container').show();
        //     },
        //     success: function(data) {
                
        //         data = data.companies;
        //         var select= `<option value="">Select</option>`;
        //         var option = ``;
                
        //         for(var i =0; i < data.length; i++) {
        //             option += `<option value="`+data[i].id+`">`+data[i].name+`</option>`;
        //         }

        //         $("#company_list").html(select + option);


        //     },
        //     complete:function(data) {
        //         $('.loader_container').hide();
        //     },
        //     error: function(e) {
        //         console.log(e)
        //     }
        // });
    }
    );

    $("#update_ticket_customer").submit(function(id) { 
        id.preventDefault();

        var method = $(this).attr("method");
        var action = $(this).attr("action");

        var first_name = $("#first_name_update").val();
        var last_name = $("#last_name_update").val();
        var email = $("#email_update").val();
        var phone = $("#phn_update").val();
        var company = $("#company_list").val();
        var id = $("#tkt_cust_id").val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: method,
            url: action,
            data: { id:id,
                first_name : first_name,
                last_name : last_name,
                email : email,
                phone : phone,
                company : company    
            },
            dataType: 'json',
            beforeSend: function(data) {
                $("#saveBtn").hide();
                $("#processbtn").show();
            },
            success: function(data) {
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });

                    $('#cst-name').html(first_name+' '+last_name);
                    $('#cst-email').html(email);
                    $('#cst-direct-line').html(phone);
                    ticket_customer.company_id = company;
                    setCustomerCompany();
                    $("#pro_edit").modal('hide');
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function(data) {
                $("#saveBtn").show();
                $("#processbtn").hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
    });


    let ticket_details =  {!! json_encode($details) !!};
    let ticket_customer =  {!! json_encode($ticket_customer) !!};
    let companies_list =  {!! json_encode($companies) !!};
    let g_followUps = '';
    
    var active_user = {!! json_encode($active_user) !!};
    var ticket =  {!! json_encode($details) !!};
    var ticketReplies =  {!! json_encode($details->ticketReplies) !!};
    let gl_color_notes = 'rgb(255, 230, 177)';
    let user_photo_url = "{{ URL::asset('files/user_photos/user-photo.jpg') }}";
    let update_ticket_route = "{{asset('update_ticket')}}";
    let replies_attach_path = `{{asset('public/files/replies')}}`;
    let publish_reply_route = "{{asset('publish-ticket-reply')}}";
    let ticket_followup_route = "{{asset('get-ticket-follow-up')}}";
    let delete_followup_route = "{{asset('del-ticket-follow-up')}}";
    let update_followup_route = "{{asset('update-ticket-follow-up')}}";
    let profile_img_path = "{{asset('/files/asset_img/1601560516.png')}}";
    let del_ticket_route = "{{asset('/del-ticket-note')}}";
    let ticket_notes_route = "{{asset('/get-ticket-notes')}}";

    //Flag Ticket Definings
    let flag_ticket_route = "{{asset('/flag_ticket')}}";

    // assets data definitions
    let get_assets_route = "{{asset('/get-assets')}}";
    let del_asset_route = "{{asset('/delete-asset')}}";
    let save_asset_records_route = "{{asset('/save-asset-records')}}";
    let templates_fetch_route = "{{asset('/get-asset-templates')}}";
    let ticket_notify_route = "{{asset('/ticket_notification')}}";
    let get_ticket_latest_log = "{{asset('/get_ticket_log')}}";
    let route_searchEmails = "{{asset('/searchEmails')}}";
    let templates = null;
    let asset_customer_uid = '';
    let asset_company_id = '';
    let asset_project_id = '';
    let asset_ticket_id = ticket.id;

    var show_asset = "{{asset('/show-single-assets')}}";
    var update_asset = "{{asset('/update-assets')}}";

    var general_info_route = "{{asset('/general-info')}}";
</script>