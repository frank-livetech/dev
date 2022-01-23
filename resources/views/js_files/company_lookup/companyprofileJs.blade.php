

<script type="text/javascript">
    let customer = {!! json_encode($customer) !!};
    let ticketsList = [];
    // asset templates data
    var get_assets_route = "{{asset('/get-assets')}}";
    var del_asset_route = "{{asset('/delete-asset')}}";
    var save_asset_records_route = "{{asset('/save-asset-records')}}";
    var templates_fetch_route = "{{asset('/get-asset-templates')}}";
    var template_submit_route = "{{asset('/save-asset-template')}}";
    var templates = null;
    var asset_customer_uid = '';
    var asset_company_id = '{{$company->id}}';
    var general_info_route = "{{asset('/general-info')}}";
    var asset_project_id = '';
    var asset_ticket_id = '';
    var show_asset = "{{asset('/show-single-assets')}}";
    var update_asset = "{{asset('/update-assets')}}";

    let autocomplete;
    let address1Field;
    let address2Field;
    let postalField;
    let payment_billing_address;
    let tkts_ids = [];
    let timeouts_list = [];
    let loggedInUser_id = {!! json_encode(\Auth::user()->id) !!};

    function initMap(){
    address1Field = document.querySelector("#address");
    address2Field = document.querySelector("#apt_address");
    postalField = document.querySelector("#cmp_zip");
    payment_billing_address = document.querySelector('#payment_billing_address');

    // Create the autocomplete object, restricting the search predictions to
    // addresses in the US and Canada.
    console.log(address1Field);

    if(payment_billing_address.value) {
        autocomplete1 = new google.maps.places.Autocomplete(payment_billing_address, {
            componentRestrictions: { country: ["us", "ca"] },
            fields: ["address_components", "geometry"],
            types: ["address"],
        });
        payment_billing_address.focus();
    }

    if(address1Field.value) {
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: { country: ["us", "ca"] },
            fields: ["address_components", "geometry"],
            types: ["address"],
        });
        address1Field.focus();
        $("#map_2").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + address1Field.value + '&language=en"></iframe>');
    }

    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete.addListener("place_changed", fillInAddress);

    autocomplete1.addListener("place_changed", fillPaymentBillingFIelds);
    }
 
    function fillInAddress() {
        // Get the place details from the autocomplete object.

        if($("#address").val()) {
            $("#map_2").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + $("#address").val() + '&language=en"></iframe>')
        }

        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
            case "street_number": {
                address1 = `${component.long_name} ${address1}`;
                break;
            }

            case "route": {
                address1 += component.short_name;
                break;
            }

            case "postal_code": {
                postcode = `${component.long_name}${postcode}`;
                break;
            }

            case "postal_code_suffix": {
                postcode = `${postcode}-${component.long_name}`;
                break;
            }
            case "locality":
                document.querySelector("#cmp_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#cmp_state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#cmp_country").value = component.long_name;
                break;
            }
        }
        address1Field.value = address1;
        postalField.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address2Field.focus();
        
    }

    function fillPaymentBillingFIelds() {

        const place = autocomplete1.getPlace();
        let address1 = "";
        let postcode = "";

        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
            case "street_number": {
                address1 = `${component.long_name} ${address1}`;
                break;
            }

            case "route": {
                address1 += component.short_name;
                break;
            }

            case "postal_code": {
                postcode = `${component.long_name}${postcode}`;
                break;
            }

            case "postal_code_suffix": {
                postcode = `${postcode}-${component.long_name}`;
                break;
            }
            case "locality":
                document.querySelector("#payment_billing_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#payment_billing_state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#payment_billing_country").value = component.long_name;
                break;
            }
        }
        address1Field.value = address1;
        postalField.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address2Field.focus();
        
    }

    function selectColor(color) {
        gl_color_notes = color;
        $('#note').css('background-color', color);
    }

    function ticket_notify(tick_id, template, action_name) {
        if (tick_id && template) {
            $.ajax({
                type: 'POST',
                url: "{{asset('/ticket_notification')}}",
                data: { id: tick_id, template: template, action: action_name },
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
    }

    function saveCompanySLA() {
        var com_sla = $("#com_sla").val();
        var company_id = $("#company_id").val();

        com_sla_arr = '';

        $("#com_sla").each(function() {
            com_sla_arr = $(this).val() + ',';
        })

        var form_data = {
            company_id:company_id,
            com_sla: com_sla_arr.slice(0, -1),
        }
        if(com_sla != "") {
            $.ajax({
                type: "POST",
                url: "{{url('save_company_sla')}}",
                data: form_data,
                dataType: 'json',
                success: function(data) {
                    console.log(data);

                    if(data.status_code == 200 && data.success == true) {
                        toastr.success(data.message, { timeOut: 5000 });
                    }else{
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }else{
            toastr.error("Select SLA First", { timeOut: 5000 });
        }
        
    }
    

    function editNote(ele, index) {
        gl_sel_note_index = index;
        gl_color_notes = notes[index].color;
        $('#save_ticket_note').find('#note').val(notes[index].note);
        $('#save_ticket_note').find('#note').css('background-color', gl_color_notes);

        $('#notes_manager_modal').modal('show');
    }

    function deleteTicketNote(ele, id, tick_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "All data related to this note will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    url: "{{asset('/del-ticket-note')}}",
                    data: { id: id },
                    success: function(data) {

                        if (data.success) {
                            // send mail notification regarding ticket action
                            // ticket_notify(tick_id, 'ticket_update');

                            $(ele).closest('#note-div-' + id).remove();
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: data.message,
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
        });
    }

    function get_ticket_notes() {
        $.ajax({
            type: 'GET',
            url: "{{asset('/get-ticket-notes')}}",
            data: {
                id: tkts_ids,
                type: 'User Organization'
            },
            success: function(data) {
                if (data.success) {
                    // $('#ticket_notes .card-body').html(`<div class="col-12 px-0 text-right">
                    //     <button class="btn btn-success" data-target="#notes_manager_modal" data-toggle="modal"><i class="mdi mdi-plus-circle"></i> Add Note</button>
                    // </div>`);
                    $('#ticket_notes .card-body').html('');

                    notes = data.notes;

                    if (timeouts_list.length) {
                        for (let i in timeouts_list) {
                            clearTimeout(timeouts_list[i]);
                        }
                    }

                    timeouts_list = [];

                    for (let i in notes) {
                        let timeOut = '';
                        let autho = '';
                        if (notes[i].created_by == loggedInUser_id) {
                            autho = `<div class="ml-auto">
                                <span class="fas fa-edit text-primary ml-2" onclick="editNote(this, ` + (i) + `)" style="cursor: pointer;"></span>
                                
                                <span class="fas fa-trash text-danger" onclick="deleteTicketNote(this, '` + notes[i].id + `', '` + notes[i].ticket_id + `')" style="cursor: pointer;"></span>
                            </div>`;
                        }

                        if (notes[i].followup_id && notes[i].followUp_date) {
                            let timeOut2 = moment(notes[i].followUp_date).diff(moment(), 'seconds');

                            // set timeout for only 1 day's followups
                            if (moment(notes[i].followUp_date).diff(moment(), 'hours') > 23) continue;

                            if (timeOut2 > 0) {
                                timeOut = timeOut2 * 1000;
                            }
                        }

                        let tkt_subject = '';
                        let tkt = ticketsList.filter(item => item.id == notes[i].ticket_id);
                        if(tkt.length) tkt_subject = '<a href="{{asset("/ticket-details")}}/' + tkt[0].coustom_id + '">'+tkt[0].coustom_id+'</a>';

                        let flup = `<div class="col-12 p-2 my-2 d-flex" id="note-div-${notes[i].id}" style="background-color: ${notes[i].color}">
                            <div class="pr-2">
                                <img src="{{asset('/files/asset_img/1601560516.png')}}" alt="User" width="40">
                            </div>
                            <div class="w-100">
                                <div class="col-12 p-0 d-flex">
                                    <h5 class="note-head">Original Posted to ${tkt_subject} by ${notes[i].name} ${moment(notes[i].created_at).format('YYYY-MM-DD HH:mm:ss A')}</h5>
                                    ${autho}
                                </div>
                                <p class="note-details">${notes[i].note}</p>
                            </div>
                        </div>`;

                        if (timeOut) {
                            timeouts_list.push(setTimeout(function() {
                                $('#ticket_notes .card-body').append(flup);
                            }, timeOut));
                        } else {
                            $('#ticket_notes .card-body').append(flup);
                        }
                    }
                }
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }

    $("#save_ticket_note").submit(function(event) {
        event.preventDefault();

        var formData = new FormData($(this)[0]);
        formData.append('ticket_id', notes[gl_sel_note_index].ticket_id);
        formData.append('color', gl_color_notes);
        formData.append('type', notes[gl_sel_note_index].type);
        if (gl_sel_note_index !== null) {
            formData.append('id', notes[gl_sel_note_index].id);
        }
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
            success: function(data) {
                // console.log(data);

                if (data.success) {
                    // send mail notification regarding ticket action
                    ticket_notify(notes[gl_sel_note_index].ticket_id, 'ticket_update');
                    gl_sel_note_index = null;

                    $(this).trigger('reset');
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: swal_message_time
                    });
                    get_ticket_notes();

                    $('#notes_manager_modal').modal('hide');
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: swal_message_time
                    });
                }
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    });

    function get_ticket_table_list() {
        $.ajax({
            type: "get",
            url: "{{asset('/get-tickets')}}/customer/"+customer.id,
            dataType: 'json',
            cache: false,
            success: function(data) {
                console.log(data.tickets);
                ticketsList = data.tickets;

                tkts_ids = ticketsList.map(a => a.id);
                get_ticket_notes();
            }
        });
    }

  $(document).ready(function() {
    try {
        if(countries_list.length) {
            $('#payment_billing_country').trigger('change');
            $('#cmp_country').trigger('change');
            $('#country').trigger('change');
        }
    } catch (err) {
        console.log(err);
    }

    $("#staff_table").DataTable();
    if(customer) get_ticket_table_list();
      var googleObject = {!! json_encode($google) !!};
      console.log(googleObject);
      if(!$.isEmptyObject(googleObject)){
        if( googleObject.hasOwnProperty('api_key')){
            var api_key = googleObject.api_key;
            $("#google_api_key").val(api_key);
            console.log(api_key)
            if(api_key!=''){
                var script ="https://maps.googleapis.com/maps/api/js?key="+api_key+"&libraries=places&sensor=false&callback=initMap";
                var s = document.createElement("script");
                s.type = "text/javascript";
                s.src = script;
                $("head").append(s);

            }
                   const allScripts = document.getElementsByTagName( 'script' );
                    [].filter.call(
                    allScripts, 
                    ( scpt ) => scpt.src.indexOf( 'key='+api_key ) >0
                    )[ 0 ].remove();

                    // window.google = {};
        }
   }

    $("#twt").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if(value == '') {
            $("#social-error").html("Twitter Link is Missing");                
            setTimeout(() => {
                $("#social-error").html("");                
            },5000);
        }else{
            window.open(value, '_blank');
        }
    });

    $("#fb_icon").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if(value == '') {
            $("#social-error").html("Facebook Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");                
            },5000);
        }else{
            window.open(value, '_blank');
        }
    });

    $("#pintrst").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if(value == '') {
            $("#social-error").html("Pinterest Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");                
            },5000);
        }else{
            window.open(value, '_blank');
        }
    });

    $("#inst").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if(value == '') {
            $("#social-error").html("Instagram Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");                
            },5000);
        }else{
            window.open(value, '_blank');
        }
    });

    $("#web").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if(value == '') {
            $("#social-error").html("Website Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");                
            },5000);
        }else{
            window.open(value, '_blank');
        }
    });


    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#ppNew').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    function readURL1(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#profile-user-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#upload_company_img").submit(function(e) {
       e.preventDefault();

       $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: "{{url('upload_company_img')}}",
            type: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if(data.status == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#editPicModal").modal('hide');
                    console.log(js_origin);
                    let path = js_origin + data.img;
                    console.log(path);
                    $('#company_curr_img').attr('src', path );
                    $('#company_modal_img').attr('src', path );
                }else{
                    toastr.error(data.message, { timeOut: 5000 });
                }
                console.log(data , "data");
            },
            error: function(e) {
                console.log(e)
            }

        });
    });

    $("#customFilePP").change(function() {
        // alert("Bingo");
        var ter = $("#customFilePP").val();
        // alert(ter);
        var terun = ter.replace(/^.*\\/, "");
        $(".custom-file-label").text(terun);
          readURL(this);
    });

    $("#phone").keyup(function(){
        var regex = new RegExp("^[0-9]+$");

        if(!regex.test($(this).val())) {
            $("#err4").html("Only numeric values allowed");
        }else{
            $("#err4").html(" ");
        }
        if($(this).val() == '') {
            $("#err4").html(" ");
        }
    });

    $("#update_company").submit(function (event) {

        $('#adrs').innerHtml += "<h1>Yes</h1>";
        event.preventDefault();
        event.stopPropagation();

        var cmp_id = $("#cmp_id").val();

        var poc_first_name = $("#poc_first_name").val();
        var poc_last_name = $("#poc_last_name").val();
        var name = $("#name").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var is_default = 0;

        
        var a = checkEmptyFields(poc_first_name , $("#err"));
        var b = checkEmptyFields(poc_last_name , $("#err1"));
        var c = checkEmptyFields(name , $("#err2"));
        var e = checkEmptyFields(phone , $("#err4"));

        // if(!$('#is_bill_add').prop("checked")) {
        //     $('#bill_st_add').val('');
        //     $('#bill_apt_add').val('');
        //     $('#bill_add_country').val('')
        //     $('#bill_add_state').val('');
        //     $('#bill_add_city').val('');
        //     $('#bill_add_zip').val('');
        //     is_bill_add = '';
        // } else {
        //     is_bill_add = 1;
        // }

        if ($("#set_default").is(":checked")) {
            is_default = 1;
        } else {
            is_default = 0;
        }

        var regex = new RegExp("^[0-9]+$");

        if(!regex.test(phone)) {
            $("#phone_error").html("Only numeric values allowed");
            return false;
        }

        

        if(a && b && c && e == true) {


            var fb = $("#fb").val();
            var pin = $("#update_pinterest").val();
            var twt = $("#twitter").val();
            var insta = $("#insta").val();
            var website = $("#update_website").val();

            if( fb != '') {
                var FBurl = /^(http|https)\:\/\/facebook.com|facebook.com\/.*/i;
                if(!fb.match(FBurl)) {
                    toastr.error('Provide a valid facebook link', { timeOut: 5000 });
                    return false;
                }
            }

            if( pin != '') {
                var FBurl = /^(http|https)\:\/\/pinterest.com|pinterest.com\/.*/i;
                if(!pin.match(FBurl)) {
                    toastr.error('Provide a valid Pinterest link', { timeOut: 5000 });
                    return false;
                }
            }
            if( twt != '') {
                var FBurl = /^(http|https)\:\/\/twitter.com|twitter.com\/.*/i;
                if(!twt.match(FBurl)) {
                    toastr.error('Provide a valid Twitter link', { timeOut: 5000 });
                    return false;
                }
            }
            if( insta != '') {
                var FBurl = /^(http|https)\:\/\/instagram.com|instagram.com\/.*/i;
                if(!insta.match(FBurl)) {
                    toastr.error('Provide a valid Instagram link', { timeOut: 5000 });
                    return false;
                }
            }

            var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
            var urlregex = new RegExp(pattern);

            if(website != '') {
                if(!urlregex.test(website)) {
                    toastr.error('Provide a valid website link', { timeOut: 5000 });
                    return false;
                }
            }

            var form_data = $("#update_company").serialize() + "&cmp_id=" + cmp_id + '&is_default=' + is_default;

            $.ajax({
                type: "POST",
                url: "{{url('update_company_profile')}}",
                data: form_data,
                dataType:'json',
                beforeSend:function() {
                    $("#comp_pro_btn").show();
                    $("#comp_update_Btn").hide();
                },
                success: function (data) {
                    console.log(data);
                    // values();

                    toastr.success(data.message, { timeOut: 5000 });

                    $("#comp_name").text($("#name").val());
                    $("#comp_phone").text($("#phone").val());
                    $("#comp_email").text($("#email").val());
                    $("#comp_add").text($("#address").val());
                    $("#comp_apprt").text($("#apt_address").val());
                    $("#comp_zip").text($("#cmp_zip").val());
                    $("#comp_strt").text($("#cmp_city").val())

                    var state = $("#cmp_state").val();
                    // if(state == "Select State"){
                    //     $("#comp_state").text('');
                    // }else{
                        $("#comp_state").text(state);
                    // }

                    var country = $("#cmp_country").val();
                    
                    // if(country == "Select Country"){
                    //     $("#comp_country").text('');
                    // }else{
                        $("#comp_country").text(country);
                    // }


                    // twitter
                    $("#twt").attr('href', $("#twitter").val());
                    $("#fb").attr('href', $("#fb").val());
                    $("#pintrst").attr('href', $("#update_pinterest").val());
                    $("#inst").attr('href', $("#insta").val());
                    $("#web").attr('href', $("#update_website").val());

                },
                complete:function() {
                    $("#comp_pro_btn").hide();
                    $("#comp_update_Btn").show();
                },  
                error: function (e) {
                    console.log(e);
                    $("#comp_pro_btn").hide();
                    $("#comp_update_Btn").show();
                    if(e.responseJSON.errors.email) {
                        toastr.error(e.responseJSON.errors.email[0], { timeOut: 5000 });
                    }
                }
            });
        }
    });

  });

    const bridge = $("#is_bill_add"),
      bridgeDiv = $("#compBillAdd");

    bridge.on('change', ()=> bridgeDiv.toggle( this.checked ) );


    function checkEmptyFields(input, err) {
        if(input == '') {
            err.html("this field is required");
            return false;
        }else{
            err.html("");
            return true;
        }
    }

    function checkValidEmail(input,err) {
        var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

        if(input == '') {
            err.html("this field is required");
            return false;
        }else if(!pattern.test(input)) {
            err.html("please provide valid email address");
            return false;
        }else{
            err.html("");
            return true;
        }
    }


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    // let assets_table_list = '';
    let orders_table_list='';
    let users_table_list = '';
    let tags = {!! json_encode($tags) !!};

    $(document).ready(function() {
        if($('#is_bill_add').prop("checked")) {
            $('#is_bill_add').trigger('change');
        }
    
        orders_table_list = $('#customer_order_table').DataTable();
        $('#customer_subscription').DataTable();
        users_table_list = $('#user-table-list').DataTable();
        get_users_table_list();
        assets_table_list = $('#assets_table_list').DataTable();
        //get_assets_table_list();
            
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

        $(".companyUser").submit(function(event) {
            event.preventDefault();

            var action = $(this).attr('action');
            var method = $(this).attr('method');

            $.ajax({
                type: method,
                url: action,
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function (data) {
                    $(".savingBtn").hide();
                    $(".processingBtn").show();
                },
                success: function (data) {
                    console.log(data, "a");

                    if(data.status_code == 200 && data.success == true){

                        toastr.success(data.message, { timeOut: 5000 });

                        if(data.type == 'customer') {
                            $('#add_staff_model').modal('hide');
                        }else{
                            $('#add_comp_user_model').modal('hide');
                        }

                    }else{

                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                complete: function (data) {
                    $(".savingBtn").show();
                    $(".processingBtn").hide();
                },
                failure: function (errMsg) {
                    console.log(errMsg);
                    $(".savingBtn").show();
                    $(".processingBtn").hide();
                },
            });

        });
        
        function values(){
            var state;
            var country;
            var email = $("#email").val();
            var phone = $("#phone").val();
            var address = $("#address").val();
            var apartment = $("#apt_address").val();
            var city = $("#cmp_city").val();
            // if($("#cmp_state option:selected" ).val() == ""){
                state = "";    
            // }
            // else{
            //     state = $("#cmp_state option:selected" ).text();
            // }
            
            var zip = $("#cmp_zip").val();
            // if($("#cmp_country option:selected" ).val() == ""){
                country = "";    
            // }
            // else{
            //     country = $("#cmp_country option:selected" ).text();
            // }

            // $("#adrs").html("");
            // $('#adrs').append ('  <small class="text-muted pt-4 db">Phone</small> ' +
            //             '<h6><a href="tel:'+phone+'">'+phone+'</a></h6>'+
            //             '<small class="text-muted">Email address </small>'+
            //             '<h6><a href="tel:'+email+'">'+email+'</a></h6>'+
            //             '<small class="text-muted">Address </small>'+
            //             '<h6>'+address+',</h6>'+
            //             '<h6>'+apartment+'</h6>'+
            //             '<h6>'+city+', ' +state+', '+zip+'</h6>'+
            //             '<h6>'+country+'</h6>'+
            //             '<hr>');       
        }
    });

    $("#add_staff_form").submit(function (event) {

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
                    if (data['success'] == true) {
                        $("#add_staff_form").trigger("reset");
                        $('#add_staff_model').modal('hide');    
                        get_users_table_list()                
                    }
                        Swal.fire({
                            position: 'top-end',
                            icon: data.success ? 'success' : 'error',
                            title: data['message'],
                            showConfirmButton: false,
                            timer: 2500
                        })
                }
            });

        });

        function get_users_table_list() {

        users_table_list.clear().draw();
        $.ajax({
            type: "get",
            url: "{{asset('/company-get-staffs/'.$company->id)}}",
            success: function (data) {
                var user_arr = data.staffs;
                $("#user-table-list tbody").html("");
                var count = 1;
                $.each(user_arr, function (key, val) {
                    var status  = '';
                    if(val['status'] == 1){
                        status = 'Active'
                    }else{
                        status = 'Deactive'
                    }
                    var tagsArr = '';
                    // console.log(item);
                        if(val['staff_profile']['tags'] == null){
                        
                        }else{
                            tagsArr = val['staff_profile']['tags'].split(",");
                        }
                    // console.log(tagsArr);
                    var tags_data = '';
                    if(tagsArr.length == 0 && val['staff_profile']['tags'] == null){
                        tags_data = 'Nothing Selected';
                    }else{
                        var check = 0;
                    //  console.log(tags);
                        for(var j = 0 ; j< tags.length ; j++){
                            for(var k = 0; k<tagsArr.length;k++){
                            // console.log(tagsArr[k])
                                if(tagsArr[k] == tags[j]['id']){

                                    if(check > 0){
                                        tags_data += ',';
                                    }
                                    tags_data += tags[j]['name'];
                                    check++;
                                    break;
                                }
                            }
                            // echo "<option value='$key'>$val</option>";
                        }
                    }
                    let tickets = val.tickets.map(function(item){
                        return item.coustom_id ? `<a href="{{asset('ticket-details')}}/${item.id}">${item.coustom_id}</a>` : `<a href="{{asset('ticket-details')}}/${item.id}">#${item.id}</a>`;
                    }).join(',');
                    users_table_list.row.add([
                        count,
                        val['email'],
                        val['name'],
                        val['staff_profile']['phone'],
                        status,
                        tags_data,
                        tickets,
                        '<a style="cursor:pointer;text-align:center" title="Delete User" onclick="event.stopPropagation();deleteUsers(' +
                        val['id'] +
                        ');return false;"><i class="fa fa-trash " aria-hidden="true"></i></a>'

                    ]).draw(false);
                    count++;
                });

            }
        });
    }

    function deleteUsers(id){
        $.ajax({
            type: "get",
            url: "{{asset('/company-remove-staff/'.$company->id)}}/"+id,
            success: function (data) {
                get_users_table_list()
                        Swal.fire({
                            position: 'top-end',
                            icon: data.success ? 'success' : 'error',
                            title: data['message'],
                            showConfirmButton: false,
                            timer: 2500
                        })
            }
        });
    }


    var cmp_com_sla  = $("#cmp_com_sla").val(); 
    var arraySla = cmp_com_sla.split(',');
    $('#com_sla').val(arraySla).trigger('change');

</script>

@include('js_files/statesJs')