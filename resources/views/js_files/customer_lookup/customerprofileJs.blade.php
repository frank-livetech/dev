

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    let ticketsList = [];
    let get_filteredtkt_route = "{{asset('/get-filtered-tickets')}}"

    var customer_subscription_table = '';
    var subscriptionsList = {!!json_encode($subscriptions) !!};

    let customer = {!! json_encode($customer) !!};
    console.log(customer , "customercustomercustomercustomer");
    let all_customers_emails = {!! json_encode($customers) !!};

    let ticket_format = {!!json_encode($ticket_format) !!};
    let statuses_list = {!!json_encode($statuses) !!};

    let open_status_id = statuses_list[statuses_list.map(function(itm) {
        return itm.name
    }).indexOf('Open')].id;
    let closed_status_id = statuses_list[statuses_list.map(function(itm) {
        return itm.name
    }).indexOf('Closed')].id;


    var orders_table_list = '';

    // asset templates data
    var get_assets_route = "{{asset('/get-assets')}}";
    var del_asset_route = "{{asset('/delete-asset')}}";
    var save_asset_records_route = "{{asset('/save-asset-records')}}";
    var templates_fetch_route = "{{asset('/get-asset-templates')}}";
    var template_submit_route = "{{asset('/save-asset-template')}}";
    var templates = null;
    var asset_customer_uid = customer.id;
    var asset_company_id = '';

    var general_info_route = "{{asset('/general-info')}}";
    var asset_company_id = '';
    var asset_project_id = '';
    var asset_ticket_id = '';
    var invoice_url = "{{url('create_pdf_invoice')}}";

    var show_asset = "{{asset('/show-single-assets')}}";
    var update_asset = "{{asset('/update-assets')}}";
    let timeouts_list = [];
    let gl_color_notes = '';
    let gl_sel_note_index = null;
    let notes = [];
    let tkts_ids = [];
    let cust_tkt_ids = [];

    let get_tickets_route = "{{asset('/get-tickets')}}/customer/"+customer.id;
    let ticket_details_route = "{{asset('/ticket-details')}}";
    let ticket_notify_route = "{{asset('/ticket_notification')}}";
    let flag_ticket_route = "{{asset('/flag_ticket')}}";
    let loggedInUser = {!! json_encode(\Auth::user()->id) !!};
    let date_format = "{{Session::get('system_date')}}";
    let time_zone = "{{Session::get('timezone')}}";
    let url_type = '';

    let autocomplete;
    let autocomplete1;
    let address1Field;
    let address2Field;
    let address11Field;
    let address21Field;
    let postal1Field;


    document.addEventListener('DOMContentLoaded', function () {
        CollectJS.configure({
            "paymentSelector" : "#payButton",
            "variant" : "lightbox",
            "styleSniffer" : "false",
            "googleFont": "Montserrat:400",
            "customCss" : {
                "color": "#0000ff",
                "background-color": "#d0d0ff"
            },
            "invalidCss": {
                "color": "white",
                "background-color": "red"
            },
            "validCss": {
                "color": "black",
                "background-color": "#d0ffd0"
            },
            "placeholderCss": {
                "color": "green",
                "background-color": "#808080"
            },
            "focusCss": {
                "color": "yellow",
                "background-color": "#202020"
            },
            "fields": {
                "ccnumber": {
                    "selector": "#ccnumber",
                    "title": "Card Number",
                    "placeholder": "0000 0000 0000 0000"
                },
                "ccexp": {
                    "selector": "#ccexp",
                    "title": "Card Expiration",
                    "placeholder": "00 / 00"
                },
                "cvv": {
                    "display": "show",
                    "selector": "#cvv",
                    "title": "CVV Code",
                    "placeholder": "***"
                },
                
                
                
            },
            'validationCallback' : function(field, status, message) {
                if (status) {
                    var message = field + " is now OK: " + message;
                } else {
                    var message = field + " is now Invalid: " + message;
                }
                console.log(message);
            },
            "timeoutDuration" : 2000,
            "timeoutCallback" : function () {
                console.log("The tokenization didn't respond in the expected timeframe.  This could be due to an invalid or incomplete field or poor connectivity");
            },
            "fieldsAvailableCallback" : function () {
                console.log("Collect.js loaded the fields onto the form");
            },
            'callback' : function(response) {
                console.log(response);
                var input = document.createElement("input");
                $('#card_type').val(response.card.type)
                $('#cardlastDigits').val(response.card.number)
                $('#exp').val(response.card.exp)
                $('#payment_token').val(response.token).trigger('change')
                
                // CardSubmit();
                
            }
        });
    });
    
    document.addEventListener('DOMContentLoaded', function () {
        CollectJS.configure({
            "paymentSelector" : "#payButton",
            "variant" : "lightbox",
            "styleSniffer" : "false",
            "googleFont": "Montserrat:400",
            "customCss" : {
                "color": "#0000ff",
                "background-color": "#d0d0ff"
            },
            "invalidCss": {
                "color": "white",
                "background-color": "red"
            },
            "validCss": {
                "color": "black",
                "background-color": "#d0ffd0"
            },
            "placeholderCss": {
                "color": "green",
                "background-color": "#808080"
            },
            "focusCss": {
                "color": "yellow",
                "background-color": "#202020"
            },
            "fields": {
                "ccnumber": {
                    "selector": "#ccnumber",
                    "title": "Card Number",
                    "placeholder": "0000 0000 0000 0000"
                },
                "ccexp": {
                    "selector": "#ccexp",
                    "title": "Card Expiration",
                    "placeholder": "00 / 00"
                },
                "cvv": {
                    "display": "show",
                    "selector": "#cvv",
                    "title": "CVV Code",
                    "placeholder": "***"
                }, 
            },
            'validationCallback' : function(field, status, message) {
                if (status) {
                    var message = field + " is now OK: " + message;
                } else {
                    var message = field + " is now Invalid: " + message;
                }
                console.log(message);
            },
            "timeoutDuration" : 2000,
            "timeoutCallback" : function () {
                console.log("The tokenization didn't respond in the expected timeframe.  This could be due to an invalid or incomplete field or poor connectivity");
            },
            "fieldsAvailableCallback" : function () {
                console.log("Collect.js loaded the fields onto the form");
            },
            'callback' : function(response) {
                console.log(response);
                var input = document.createElement("input");
                $('#card_type').val(response.card.type)
                $('#cardlastDigits').val(response.card.number)
                $('#exp').val(response.card.exp)
                $('#payment_token').val(response.token).trigger('change')
            }
        });
    });

    function initMapReplace(){
    
        address1Field = document.querySelector("#prof_address");
        address2Field = document.querySelector("#apt_address");
        address11Field = document.querySelector("#bill_st_add");
        address21Field = document.querySelector("#bill_apt_add");
        postalField = document.querySelector("#prof_zip");
        postal1Field = document.querySelector("#bill_add_zip");
        
        // Create the autocomplete object, restricting the search predictions to
        // addresses in the US and Canada.
        // console.log(address1Field);google.maps.places.SearchBox
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: { country: ["us", "ca"] },
            fields: ["address_components", "geometry","name"],
           
            types: ["address"],
        });
        address1Field.focus();
        
         autocomplete.addListener("place_changed", fillInAddress);
        // console.log(address1Field);
        autocomplete1 = new google.maps.places.Autocomplete(address11Field, {
            componentRestrictions: { country: ["us", "ca"] },
            fields: ["address_components", "geometry"],
            types: ["address"],
        });
        if(address11Field != null) {
            address11Field.focus();
        }        
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        // $("#map_2").html('');
        if($("#prof_address").val()) {
            $("#map_2").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + $("#prof_address").val() + '&language=en"></iframe>')
        }

        autocomplete1.addListener("place_changed", fillInAddress1);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        

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
                document.querySelector("#prof_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#prof_state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#prof_country").value = component.long_name;
                break;
            }
        }
        address1Field.value = address1;
        if($("#prof_address").val()) {
            $("#map_2").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + $("#prof_address").val() + '&language=en"></iframe>')
        }

        postalField.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address2Field.focus();
        
    }
    function fillInAddress1() {
        // Get the place details from the autocomplete object.
        const place = autocomplete1.getPlace();
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
                document.querySelector("#bill_add_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#bill_add_state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#bill_add_country").value = component.long_name;
                break;
            }
        }
        address11Field.value = address1;
        postal1Field.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address21Field.focus();
        
    }
    
    $(document).ready(function() {

        // tickets
        var url  = window.location.href;
        console.log(url , "url");

        if(url.includes('#tickets')) {

            $("#pills-tickets-tab").click();
            $("#pills-setting-tab").removeClass("active")
            $("#previous-month").removeClass("show active");
            $("#pills-tickets-tab").addClass('active');
            $("#tickets").addClass("show active");

        }else if(url.includes('##ticket-open')) {
            
            $("#pills-tickets-tab").click();
            listTickets('open');

        }

        try {
            if(countries_list.length) {
                if($('#prof_country').val()) $('#prof_country').trigger('change');
                if($('#bill_add_country').val()) $('#bill_add_country').trigger('change');
                if($('#country').val()) $('#country').trigger('change');
            }
        } catch(err) {
            console.log(err);
        }

        values();
        orders_table_list = $('#customer_order_table').DataTable();
        domain_table_list = $('#domain_order_table').DataTable();

        customer_subscription_table = $('#customer_subscription').DataTable();
    
        initializeTicketTable();

        getCustomerOrders();
        $( "#customer_order_table_filter").find('input[type="search"]').attr("placeholder","Order Id or Customer Name");
        var password = $(".user-password-div input[name='password']").val();
        var confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
        var password = $(this).val();

        $(".user-password-div").on('keyup', "input[name='password']", function() {
            var score = 0;
            password = $(".user-password-div input[name='password']").val();
            score = (password.length > 6) ? score + 2 : score;
            score = ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) ? score + 2 : score;
            score = (password.match(/\d+/)) ? score + 2 : score;
            score = (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ? score + 2 : score;
            score = (password.length > 10) ? score + 2 : score;
            $(".user-password-div .progress .progress-bar").css("width", (score * 10) + "%");
        });

        $(".user-confirm-password-div").on('keyup', "input[name='confirm_password']", function() {
            password = $(".user-password-div input[name='password']").val();
            confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
            $(".user-confirm-password-div .check-match").removeClass("fa-times fa-check red green");
            if (password == confirm_password) {
                $(".user-confirm-password-div .check-match").addClass("fa-check green");
            } else {
                $(".user-confirm-password-div .check-match").addClass("fa-times red");
            }
        });

        var password = $(".user-password-div input[name='password']").val();
        var confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();

        var password = $(this).val();

        $(".user-password-div").on('keyup', "input[name='password']", function() {
            var score = 0;
            password = $(".user-password-div input[name='password']").val();
            score = (password.length > 6) ? score + 2 : score;
            score = ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) ? score + 2 : score;
            score = (password.match(/\d+/)) ? score + 2 : score;
            score = (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ? score + 2 : score;
            score = (password.length > 10) ? score + 2 : score;
            $(".user-password-div .progress .progress-bar").css("width", (score * 10) + "%");
        });

        $(".user-confirm-password-div").on('keyup', "input[name='confirm_password']", function() {
            password = $(".user-password-div input[name='password']").val();
            confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
            $(".user-confirm-password-div .check-match").removeClass("fa-times fa-check red green");
            if (password == confirm_password) {
                $(".user-confirm-password-div .check-match").addClass("fa-check green");
            } else {
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

        $(".user-confirm-password-div").on('click', '.show-confirm-password-btn', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $(".user-confirm-password-div input[name='confirm_password']");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });


        var googleObject = {!! json_encode($google) !!};
        console.log(googleObject);
        if(!$.isEmptyObject(googleObject)){

            if( googleObject.hasOwnProperty('api_key')){

                var api_key = googleObject.api_key;
                $("#google_api_key").val(api_key);
                console.log(api_key);
                
                if(api_key!=''){

                    var script ="https://maps.googleapis.com/maps/api/js?key="+api_key+"&libraries=places&sensor=false&callback=initMapReplace";
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
        
        get_cust_card();
        var url = window.location.href;
        if (window.location.href.indexOf("#Success") > -1) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "Order paid SuccessFully! ",
                showConfirmButton: false,
                timer: 2500
            });
            window.location = url.split("#")[0];
        }
        if (window.location.href.indexOf("#Error") > -1) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Order payment Failed! ",
                showConfirmButton: false,
                timer: 2500
            })
            window.location = url.split("#")[0];
        }
    });

    $("#prof_email").focusout(function(){
        let user_email = $(this).val();

        var item = all_customers_emails.find(item => item.email === user_email);
        if( item != null && item != "" && item != undefined) {

            if(item.email == user_email) {
                toastr.error('The email has already been taken.', { timeOut: 5000 });
            }

        }
        

    });
  
    // here
    $("#twt").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if (value == '') {
            $("#social-error").html("Twitter Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");
            }, 5000);
        } else {
            window.open(value, '_blank');
        }
    });

    $("#fb_icon").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if (value == '') {
            $("#social-error").html("Facebook Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");
            }, 5000);
        } else {
            window.open(value, '_blank');
        }
    });

    $("#pintrst").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if (value == '') {
            $("#social-error").html("Pinterest Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");
            }, 5000);
        } else {
            window.open(value, '_blank');
        }
    });

    $("#inst").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if (value == '') {
            $("#social-error").html("Instagram Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");
            }, 5000);
        } else {
            window.open(value, '_blank');
        }
    });

    $("#lkdn").click(function(e) {
        e.preventDefault();
        var value = $(this).attr('href');
        if (value == '') {
            $("#social-error").html("Linkedin Link is Missing");
            setTimeout(() => {
                $("#social-error").html("");
            }, 5000);
        } else {
            window.open(value, '_blank');
        }
    });

    $("#change_password_checkbox").click(function() {
        $(this).is(":checked") ? 
        $('.change_password_row').show() : 
        $('.change_password_row').hide();
    }); 

    $('#update_customer').submit(function(event) {
        event.preventDefault();

        var update_password = $('#password').val();
        var customer_id = $("#customer_id").val();

        var bill_st_add = '';
        var bill_apt_add = '';
        var bill_country = '';
        var bill_state = '';
        var bill_city = '';
        var bill_zip = '';
        var customer_login = 0;
        var cust_type = $("#cust_type").val();

        let cpwd = $(".user-confirm-password-div input[name='confirm_password']").val();

        if (cpwd && cpwd != update_password) {
            toastr.error('Passwords do not match!', { timeOut: 5000 });
            return false;
        }

        if (!update_password) {
            update_password = customer.password;
        }

        if (customer.password != update_password) {
            if (cpwd != update_password) {
                toastr.error('Passwords do not match!', { timeOut: 5000 });
                return false;
            }
        }

        if ($("#customer_login").is(':checked')) {
            customer_login = 1;
        } else {
            customer_login = 0;
        }


        var fb = $("#prof_fb").val();
        var pin = $("#prof_pinterest").val();
        var twt = $("#prof_twitter").val();
        var insta = $("#prof_insta").val();
        var link = $("#prof_linkedin").val();
        var phone = $('#prof_phone').val();

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
        if( link != '') {
            var FBurl = /^(http|https)\:\/\/linkedin.com|linkedin.com\/.*/i;
            if(!link.match(FBurl)) {
                toastr.error('Provide a valid Linkedin link', { timeOut: 5000 });
                return false;
            }
        }

        
        
        let pass_checkbox =  $("#change_password_checkbox").is(":checked") ? 1 : 0;

        var phone_type = $("#phone_type").val();
        var form = {
            customer_id: customer_id,
            first_name: $('#first_name').val(),
            last_name: $('#last_name').val(),
            email: $('#prof_email').val(),
            password: $('#password').val(),
            confirm_password : $('#confirm_password').val(),
            pass_checkbox : pass_checkbox,
            phone: phone,
            address: $('#prof_address').val(),
            apt_address: $('#apt_address').val(),

            company_id: $('#company_id').val(),
            cust_type: cust_type,
            country: $('#prof_country').val(),
            state: $('#prof_state').val(),
            city: $('#prof_city').val(),
            zip: $('#prof_zip').val(),
            fb: $('#prof_fb').val(),
            twitter: $('#prof_twitter').val(),
            insta: $('#prof_insta').val(),
            pinterest: $('#prof_pinterest').val(),
            linkedin: $('#prof_linkedin').val(),
            customer_login: customer_login,
            phone_type:phone_type,
        }

        $.ajax({
            type: "POST",
            url: "{{url('update_customer_profile')}}",
            data: form,
            dataType: 'json',
            beforeSend: function(data) {
                $("#saveBtn").hide();
                $("#processing").show();
            },
            success: function(data) {
                console.log(data);
                if(data.status_code == 200 && data.success == true) {

                    values();
                    toastr.success(data.message, { timeOut: 5000 });

                    let type = 'Wordpress';
                    let slug = 'customer-lookup';
                    let icon = 'fas fa-user-alt';
                    let title = 'WP Customer';
                    let desc = 'WP Customer Updated by ' + $("#curr_user_name").val();
                    sendNotification(type,slug,icon,title,desc);


                    customer.password = update_password;
                    $(".user-confirm-password-div input[name='confirm_password']").val('');


                    $("#cust_name").text($("#first_name").val() + " " + $("#last_name").val());
                    $("#cust_email").text($("#prof_email").val());
                    $("#cust_add").text($("#prof_address").val());
                    $("#cust_apprt").text($("#apt_address").val());
                    $("#cust_zip").text($("#prof_zip").val());
                    $("#cust_city").text($("#prof_city").val());


                    var state = $("#prof_state").val();

                    $("#cust_state").text(state);
                    
                    var country = $("#prof_country").val();

                    $("#cust_country").text(country);



                    $("#twt").attr('href', $("#prof_twitter").val());
                    $("#fb_icon").attr('href', $("#prof_fb").val());
                    $("#inst").attr('href', $("#prof_insta").val());
                    $("#lkdn").attr('href', $("#prof_linkedin").val());
                    $("#pintrst").attr('href', $("#prof_pinterest").val());
                }else if(data.status == 201 && data.success == true) {
                    toastr.warning(data.message, { timeOut: 5000 });
                }else{
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function(data) {
                $("#saveBtn").show();
                $("#processing").hide();
            },
            error: function(e) {
                console.log(e);
                if (e.responseJSON.errors.email != null) {
                    toastr.error(e.responseJSON.errors.email[0], { timeOut: 5000 });
                }
                if (e.responseJSON.errors.password != null) {
                    toastr.error(e.responseJSON.errors.password[0], { timeOut: 5000 });
                }


                $("#saveBtn").show();
                $("#processing").hide();
            }
        });
    });

    $("#prof_phone").keyup(function() {

        var regex = new RegExp("^[0-9]+$");

        if(!regex.test($(this).val())) {
            $("#phone_error").html("Only numeric values allowed");
        }else{
            $("#phone_error").html(" ");
        }
        if($(this).val() == '') {
            $("#phone_error").html(" ");
        }
    });

    $("#companyForm").submit(function(event) {
        event.preventDefault();
        var poc_first_name = $('#poc_first_name').val();
        var poc_last_name = $('#poc_last_name').val();
        var name = $('#name').val();
        
        var phone = $('#phone').val();
        var country = $("#country").val();
        var state = $("#state").val();
        var city = $("#city").val();
        var zip = $("#zip").val();
        var address = $('#address').val();
        var user_id = $("#user_id").val();
        var domain = $("#domain").val();


        var a = checkEmptyFields(poc_first_name, $("#err"));
        var b = checkEmptyFields(poc_last_name, $("#err1"));
        var c = checkEmptyFields(name, $("#err2"));
       // var d = checkValidEmail(email, $("#err3"));
        var e = checkEmptyFields(phone, $("#err4"));

        
        if (a && b && c  == true) {

            var formData = {
                poc_first_name: poc_first_name,
                poc_last_name: poc_last_name,
                name: name,
                email: email,
                phone: phone,
                country: country,
                state: state,
                city: city,
                zip: zip,
                address: address,
                user_id: user_id,
                domain: domain
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                url: "{{url('save-company')}}",
                data: formData,
                dataType: 'json',
                beforeSend: function(data) {
                    $('.loader_container').show();
                },
                success: function(data) {
                    toastr.success(data.message, {
                        timeOut: 5000
                    });

                    $('#company_id').append('<option value="' + data.result +'" selected>' + $('#companyForm #name').val() + '</option>');

                    $('#addCompanyModal').modal('hide');
                    $('#companyForm').trigger('reset');
                },
                complete: function(data) {
                    $('.loader_container').hide();
                },
                error: function(e) {
                    console.log(e)
                }
            });


        }

        // Handle click on "Select all" control
        $('#select-all').on('click', function() {
            // Get all rows with search applied
            var rows = customerTable.rows({
                'search': 'applied'
            }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
            
    });

    $("#payment_token").change(function(event) {
        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var address1 = $('#address1').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var zip = $('#zip').val();
        var card_type = $('#card_type').val();
        var  exp= $('#exp').val()
        var email= $('#cust_email1').val()
        var formData = {
            fname: fname,
            lname: lname,
            address1: address1,
            city: city,
            state: state,
            zip: zip,
            card_type: card_type,
            exp: exp,
            email:email,
            payment_token: $(this).val(),
            cardlastDigits:$('#cardlastDigits').val(),
            customer_id:$('#customer_id').val()
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            url: "{{url('save-cust-card')}}",
            data: formData,
            dataType: 'json',
            beforeSend: function(data) {
                $('.loader_container').show();
            },
            success: function(data) {
                Swal.fire({
                    position: 'top-end',
                    icon: data.success ? 'success' : 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2500
                });
                get_cust_card()
                console.log(data)
        
            },
            complete: function(data) {
                $('.loader_container').hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
    });

    $("#save_tickets").submit(function(event) {
            event.preventDefault();
        var formData = new FormData($(this)[0]);
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var subject = $('#subject').val().replace(/\s+/g, " ").trim();
        var dept_id = $('#dept_id').val();
        var priority = $('#priority').val();
        var type = $('#type').val();
        var ticket_detail = $('#ticket_detail').val();
        if (subject == '' || subject == null) {
            $('#select-subject').css('display', 'block');
            return false;
        } else if (dept_id == '' || dept_id == null) {
            $('#select-department').css('display', 'block');
            return false;
        } else if (priority == '' || priority == null) {
            $('#select-priority').css('display', 'block');
            return false;
        } else if (type == '' || type == null) {
            $('#select-type').css('display', 'block');
            return false;
        } else if (ticket_detail == '' || ticket_detail == null) {
            $('#pro-details').css('display', 'block');
            return false;
        }
        formData.append('status', open_status_id);
        formData.append('customer_id', customer.id);

        $(this).find('#btnSaveTicket').attr('disabled', true);
        $(this).find('#btnSaveTicket .spinner-border').show();

        setTimeout(() => {
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
                    // $('#btnSaveTicket').attr('disabled', false);
                    // $('#btnSaveTicket .spinner-border').show();

                    console.log(data, "ticket data");
                    if (data.success) {
                        $('#ticketModal').modal('hide');
                        $("#save_tickets").trigger("reset");
                        $('#dept_id').val('').trigger("change");
                        $('#priority').val('').trigger("change");
                        $('#type').val('').trigger("change");
                        get_ticket_table_list();
                        $('#btnSaveTicket').attr('disabled', false);
                        $('#btnSaveTicket .spinner-border').hide();

                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: (data.success) ? 'success' : 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500,

                        })
                        $('#btnSaveTicket').attr('disabled', false);
                        $('#btnSaveTicket .spinner-border').hide();
                    }

                },
                failure: function(errMsg) {

                    console.log(errMsg);
                    // $('#btnSaveTicket').attr('disabled', false);
                    // $('#btnSaveTicket .spinner-border').hide();
                }
            });
        }, 1000);

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

    $("#upload_customer_img").submit(function(e) {
        e.preventDefault();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: "{{url('upload_customer_img')}}",
            type: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#editPicModal").modal('hide');
                    let path = js_origin + data.img;
                    $('#customer_curr_img').attr('src', path);
                    $('#customer_modal_img').attr('src', path);
                } else {
                    toastr.error(data.message, { timeOut: 5000});
                }
                console.log(data, "data");
            },
            error: function(e) {
                console.log(e)
            }

        });
    });


    $('.form-group small').addClass('d-none');

    function checkEmptyFields(input, err) {
        if (input == '') {
            err.html("this field is required");
            return false;
        } else {
            err.html("");
            return true;
        }
    }

    function checkValidEmail(input, err) {
        var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);

        if (!pattern.test(input)) {
            err.html("please provide valid email");
            return false;
        } else {
            err.html("");
            return true;
        }
    }

    function ShowTicketsModel() {
        $('#dept_id').val('').trigger("change");
        $('#priority').val('').trigger("change");
        $('#type').val('').trigger("change");
        $("#save_tickets").trigger("reset");
        $('#ticketModal').modal('show');
    }

    function get_cust_card() {
        var v_name = $("#cust_name").text();
        $.ajax({
            type: "GET",
            url: "{{asset('/get-customer-card')}}",
            dataType: 'json',
            data: {
                customer_id: customer.id
            },
            cache: false,
            success: function(data) {
                // alert(v_name);
                console.log(data.types, "data");
                var cardSet = ``;
                var data = data.types;
                var masterCardimg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-mastercard.png" style="width:16%;position: absolute;top: 25px;">`;
                var visaImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-visa.png" style="width:15%;position: absolute;top: 25px;">`;
                var discoverImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-discover.png" style="width:15%;position: absolute;top: 25px;">`;
                var amexImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-amex.png" style="width:15%;position: absolute;top: 25px;">`;
                var maestroImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-maestro.png" style="width:16%;position: absolute;top: 25px;">`
                // alert(data.length);
                $('#pay-card').empty();
                for (var i = 0; i < data.length; i++) {
                    // alert(data[i].ccn);
                    $('#pay-card').append(`<div class="col-md-6">

                            <div class="card payCard" style="border:1px solid black">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <a href="#" class="btn btn-warning text-white btn-circle"><i class="far fa-star"  aria-hidden="true"></i></a>
                                            <a href="#" class="btn btn-success btn-circle"><i class="fas fa-pencil-alt"  aria-hidden="true"></i></a>
                                            <a href="#" class="btn btn-danger btn-circle"><i class="fas fa-trash" style="" aria-hidden="true"></i></a>
                                            <!-- <div id="default-star-rating" style="cursor: pointer;">
                                                <img alt="1" src="assets/images/rating/star-off.png" title="Rate" style="float:right;padding-right: 5px;position: relative;bottom: 4px;">
                                                <input name="score" type="hidden">

                                            </div>-->

                                        </div>
                                    </div>
                                    <!--<h1 class="mt-0">
                                    <i class="fab fa-cc-visa text-info" aria-hidden="true"></i></h1>-->
                                    `+(data[i].card_type == 'mastercard' ? masterCardimg : ' ')+`
                                    `+(data[i].card_type == 'visa' ? visaImg : ' ')+`
                                    `+(data[i].card_type == 'amex' ? amexImg : ' ')+`
                                    `+(data[i].card_type == 'discover' ? discoverImg : ' ')+`
                                    `+(data[i].card_type == 'maestro' ? maestroImg : ' ')+`
                                    <!--<h3>`+data[i].card_type+` Ended With `+data[i].cardlastDigits+`</h3>
                                    <span class="pull-right">Exp date: `+data[i].exp+ `</span>-->
                                    
                                    <h3 class="payCard-number">**** **** ****  `+data[i].cardlastDigits+`</h3>
                                    <p><span class="payCard-text">`+data[i].fname+` `+data[i].lname+ `</span> 
                                    <span class="payCard-text" style="float:right;"> Exp : `+data[i].exp+ `</span>
                                    </p>
                                    <!--<h4>`+data[i].card_type+` ending in `+data[i].cardlastDigits+`<span class="pull-right"> (expires `+data[i].exp+ `)</span></h4>-->
                                    <span class="font-500"></span>
                                </div>
                            </div>
                        </div>
                    
                `);
                    // $('#pay-card').append(cardSet);
                }
            },
            error: function(e) {
                console.log(e)
            }
        });
    }

    function values() {
        var state, country;
        var email = $("#prof_email").val();
        var phone = $("#prof_phone").val();
        var address = $("#prof_address").val();
        var apartment = $("#apt_address").val();
        var city = $("#prof_city").val();
        // if ($("#prof_state option:selected").val() == "") {
        //     state = "";
        // } else {
            state = $("#prof_state").val();
        // }

        var zip = $("#prof_zip").val();
        // if ($("#prof_country option:selected").val() == "") {
        //     country = "";
        // } else {
            country = $("#prof_country").val();
        // }
        // $("#adrs").html('');
        // $('#adrs').append ('<small class="text-muted pt-4 db">Phone</small> ' +
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

    function listTickets() {
        $( "#customer_order_table_filter").find('input[type="search"]').attr("placeholder","Order Id or Customer Name");
        var count = 1;

        let ticket_arr = ticketsList;

        tickets_table_list.clear().draw();
        $.each(ticket_arr, function(key, val) {
            var json = JSON.stringify(data[key]);

            let prior = val['priority_name'];
            if (val['priority_color']) {
                prior = '<div class="text-center text-white" style="background-color: ' + val['priority_color'] +
                    ';">' + val['priority_name'] + '</div>';
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
            var name = val['subject'];
            var shortname = '';
            if (name.length > 20) {
                shortname = name.substring(0, 20) + " ...";
            } else {
                shortname = name;
            }

            tickets_table_list.row.add([
                '<input type="checkbox" name="chk_list[]" value= "' + val['id'] + '">',
                '<div class="text-center ' + flagged +
                '"><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, ' + val[
                'id'] + ');"></span></div>',
                val['status_name'],
                '<a href="{{asset("/ticket-details")}}/' + val['coustom_id'] + '">' + shortname + '</a>',
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


    ////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////// Ticket notes methods 
    ////////////////////////////////////////////////////////////////////////

    function selectColor(color) {
        gl_color_notes = color;
        $('#note').css('background-color', color);
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

    $('#is_bill_add').change(function() {
        if (this.checked)
            $('#compBillAdd').show();
        else
            $('#compBillAdd').hide();
    });


    function New_Bill_Add() {
        if (document.getElementById("NewBillAdd").style.display == "none") {
            $('#NewBillAdd').show();
        } else {
            $('#NewBillAdd').hide();
        }
    }

    function getCustomerOrders() {
        $( "#customer_order_table_filter").find('input[type="search"]').attr("placeholder","Order Id or Customer Name");
        var customer_id = $("#customer_id").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'GET',
            url: "{{url('get_customer_order')}}/" + customer_id,
            dataType: "json",
            beforeSend: function() {
                $(".loader_container").show();
            },
            success: function(data) {
                console.log(data , "customer order");
                var date_format = data.date_format;
                var obj = data.data;

                console.log(obj, "obj");

                $('#customer_order_table').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#customer_order_table').DataTable({
                    data: obj,
                
                    "pageLength": 50,
                    processing: true,
                    language: {
                        "loadingRecords": "&nbsp;",
                        "processing": "Wait data is Loading..."
                    },
                    columns: [{
                            "className": 'details-control',
                            "orderable": false,
                            "data": null,
                            "defaultContent": ''
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.custom_id;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.order_by.first_name + " " + full.order_by.last_name;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                let completed =
                                    `<span class="badge bg-success text-white">Completed</span>`;
                                let pending = `<span class="badge bg-danger text-white">` + full
                                    .status_text + `</span>`;
                                return full.status_text == "Completed" ? completed : pending;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return moment(full.created_at).format(date_format);
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                let pdf_btn = `<a href="` + invoice_url + "/" + full.id +
                                    `" class="btn btn-danger btn-sm rounded"><i class="fas fa-file-pdf mt-1"></i></a>`;

                                let paypal_btn = `<a href="{{url('checkout')}}/` + full.customer_id + `/` + full.custom_id +`" class="btn btn-sm ml-1 rounded btn-success">Payment</a>
                                    <a class="btn btn-sm btn-danger rounded text-white ml-2"> PDF</a>`;
                                return full.status_text == "Completed" ? pdf_btn : paypal_btn;
                            }
                        },
                    ],
                });

                $('#customer_order_table tbody').on('click', 'td.details-control', function() {
                    var tr = $(this).closest('tr');
                    var row = tbl.row(tr);
                    var rowData = row.data();

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            type: 'GET',
                            url: "{{url('get_customer_order_items')}}/" + rowData.custom_id,
                            dataType: "json",
                            beforeSend: function() {
                                $(".loader_container").show();
                            },
                            success: function(data) {
                                var obj1 = data.data;

                                if (row.child.isShown()) {
                                    // This row is already open - close it
                                    row.child.hide();
                                    tr.removeClass('shown');
                                } else {
                                    tbl.rows().every(function() {
                                        // If row has details expanded
                                        if (this.child.isShown()) {
                                            // Collapse row details
                                            this.child.hide();
                                            $(this.node()).removeClass('shown');
                                        }
                                    });
                                    row.child(format(obj1)).show();
                                    tr.addClass('shown');
                                }
                            },
                            complete: function(data) {
                                $(".loader_container").hide();
                            },
                        });
                    }
                });

            },
            complete: function(data) {
                $(".loader_container").hide();
            },

            error: function(e) {
                console.log(e);
            }
        });
    }

    function payNowModel(id) {
        console.log(id)
        $('#payNow').modal('show');
        var newUrl = "{{url('paypal/ec-checkout')}}/" + id;
        $("#paypalHref").attr("href", newUrl);
    }

    function format(obj) {

        var count = 1;
        var row = ``;
        var totals = 0;

        for (var i = 0; i < obj.length; i++) {

            totals = obj[i].total;

            row += `
                        <tr>
                            <td>` + count + `</td>
                            <td>` + obj[i].name + `</td>
                            <td>` + obj[i].quantity + `</td>
                            <td>` + obj[i].price + `</td>
                            <td>` + obj[i].quantity * obj[i].price + `</td>
                            
                        </tr>
                    `;

            count++;
        }

        return `<table class="table table-hover w-75 text-center" cellpadding="5" cellspacing="0" border="0">
                        <div class="text-left">
                            <div class="header">
                                <h2 style="display: inline; margin-left:10px !important;">
                                    <strong>Order </strong> Details
                                </h2>
                            </div>
                        </div>
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Product Name</th>
                                <th>Quanity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ` + row + `
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>` + totals + `</th>
                            </tr>
                        </tfoot>
                    </table>`;
    }

    function searchDomain() {
        var search_domain = $("#search_domain").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: "{{url('domain_search')}}",
            data: {search_domain:search_domain},
            beforeSend:function() {
                $("#domain_loader").show();
            },
            success: function(data) {

                var table = `
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1"></label>
                                </div>
                            </td>
                            <td>  
                                <a type="button" data-toggle="modal" data-target="#domainModal">
                                    <i class="fas fa-angle-right"></i> <span style="font-size:20px;"> `+data[0]+`</span></a></td>
                            <td>-</td>
                            <td>`+ (data[1] == 'false' ? 'Taken' : 'Avaiable') +`</td>
                            <td><button class="btn btn-success"> Register </button></td>
                        </tr>
                `;

                $("#domain_order_table tbody").html(table);
            },
            complete:function(data) {
                $("#domain_loader").hide();
            },
            error: function(e) {
                $("#domain_loader").hide();
                console.log(e);
            }
        });
    }

    function getNotes() {
        tkts_ids = ticketsList.map(a => a.id);
        cust_tkt_ids = tkts_ids;
        notes.length == 0 ? get_ticket_notes(tkts_ids) : render_notes(notes);
        if(notes.length != 0) {
            $("#notes_count").addClass("badge bg-light-warning mx-1");
            $("#notes_count").text(notes.length)
        }        
    }

    function get_ticket_notes(tkts_ids) {
        $.ajax({
            type: 'GET',
            url: "{{asset('/get-ticket-notes')}}",
            data: {
                id: tkts_ids,
                type: 'User'
            },
            // contentType: 'json',
            // enctype: 'multipart/form-data',
            // processData: false,
            success: function(data) {
                if (data.success) {
                    // $('#ticket_notes .card-body').html(`<div class="col-12 px-0 text-right">
                    //     <button class="btn btn-success" data-target="#notes_manager_modal" data-toggle="modal"><i class="mdi mdi-plus-circle"></i> Add Note</button>
                    // </div>`);
                    $('#ticket_notes .card-body').html('');

                    notes = data.notes;

                    render_notes(notes);

                    if(data.notes.length != 0) {
                        $("#notes_count").addClass("badge bg-light-warning mx-1");
                        $("#notes_count").text(notes.length)
                    }  

                }
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }

    function render_notes(notes) {

        if (timeouts_list.length) {
            for (let i in timeouts_list) {
                clearTimeout(timeouts_list[i]);
            }
        }

        timeouts_list = [];
        let timeOut = '';
        let flup = ``;
        for (let i in notes) {
            
            let autho = '';
            if (notes[i].created_by == loggedInUser) {
                autho = `<div class="ml-auto mt-2">

                        <span class="btn btn-icon rounded-circle btn-outline-danger waves-effect fa fa-trash"
                            style= "float:right;cursor:pointer;position:relative;bottom:25px"
                            onclick="deleteTicketNote(this, '` + notes[i].id + `', '` + notes[i].ticket_id + `')"></span>
                    
                        <span class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-edit" 
                            style="float:right;padding-right:5px;cursor:pointer;position:relative;bottom:25px; margin-right:5px"
                            onclick="editNote(this, ` + (i) + `)"></span>

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

            var user_img = ``;
            let is_live = "{{Session::get('is_live')}}";
            let path = is_live == 0 ? '' : 'public/';

            if(notes[i].profile_pic != null) {

                user_img += `<img src="{{asset('${notes[i].profile_pic}')}}" 
                width="40px" height="40px" class="rounded-circle" style="border-radius: 50%;"/>`;

            }else{

                user_img += `<img src="{{asset('${path}default_imgs/customer.png')}}" 
                        width="40px" height="40px" style="border-radius: 50%;" class="rounded-circle" />`;

            }

            let tkt_subject = '';
            let tkt = ticketsList.filter(item => item.id == notes[i].ticket_id);
            if(tkt.length) tkt_subject = '<a href="{{asset("/ticket-details")}}/' + tkt[0].coustom_id + '">'+tkt[0].coustom_id+'</a>';

            flup += `
            <div class="col-12 p-2 my-2 d-flex" id="note-div-` + notes[i].id + `" style="background-color: ` + notes[i].color + `">
                <div style="margin-right: 10px; margin-left: -8px;">
                    ${user_img}
                </div>
                <div class="w-100">
                    <div class="col-12 p-0">
                    <h5 class="note-head">Original Posted to ${tkt_subject} by <strong>${notes[i].name}</strong>  <span class="small">${jsTimeZone(notes[i].created_at)}</span> </h5>
                        ${autho}
                    </div>
                    <p class="note-details">${notes[i].note} </p>
                </div>
            </div>`;
        }

        if (timeOut) {
            timeouts_list.push(setTimeout(function() {
                $('#ticket_notes .card-body').html(flup);
            }, timeOut));
        } else {
            $('#ticket_notes .card-body').html(flup);
        }

    }

</script>

@include('js_files.ticket_cmmnJs')
@include('js_files.statesJs')
