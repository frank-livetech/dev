

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
    let time_zone = "{{Session::get('timezone')}}";
    let date_format =  "{{Session::get('system_date')}}";
    var ticket_notes_route = "{{asset('/get-ticket-notes')}}";
    let gl_color_notes = '';
    let gl_sel_note_index = null;
    let autocomplete;
    let autocomplete1;
    let address1Field;
    let address2Field;
    let postalField;
    let payment_billing_address;
    let tkts_ids = [];
    let timeouts_list = [];
    let loggedInUser_id = {!! json_encode(\Auth::user()->id) !!};
    let loggedInUser_role = {!! json_encode(\Auth::user()->user_type) !!};
    var staff_list =  {!! json_encode($users) !!};
    let all_staff_ids = staff_list.map(a => a.id);

    function initMap(){
        address1Field = document.querySelector("#address");
        address2Field = document.querySelector("#apt_address");
        postalField = document.querySelector("#cmp_zip");
        payment_billing_address = document.querySelector('#payment_billing_address');

        // Create the autocomplete object, restricting the search predictions to
        // addresses in the US and Canada.

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
        // autocomplete.addListener("place_changed", fillInAddress);
        // autocomplete1.addListener("place_changed", fillPaymentBillingFIelds);
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
                        alertNotification('success', 'Success' , data.message);
                    }else{
                        alertNotification('error', 'Error' , data.message);
                    }

                },
                error: function(e) {
                    console.log(e);
                }
            });
        }else{
            alertNotification('error', 'Error' , "Select SLA First");
        }

    }

    tinymce.init({
        selector: '#note',
        plugins: ["advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
        ],
        toolbar: 'bold italic underline alignleft link',
        menubar: false,
        statusbar: false,
        relative_urls : 0,
        remove_script_host : 0,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    });



    function openNotesModal() {
        $("#note_title").text("Add Notes");
        $("#notes_manager_modal").modal('show');
        $("#note").val(" ");
        $("#note-type-ticket").prop('checked',true);
        $('#note-visibilty').prop('disabled', false);
        $("#note-visibilty").val("Everyone").trigger('change');
        $("#note-id").val("");
        tinyMCE.get(0).getBody().style.backgroundColor = '#FFEFBB';
        gl_color_notes = '#FFEFBB';
    }

    function notesModalClose() {
        $("#notes_manager_modal").modal('hide');
    }

    function selectColor(color) {
        gl_color_notes = color
        tinyMCE.get(0).getBody().style.backgroundColor = color;
    }

    $("#save_ticket_note").submit(function(event) {
        event.preventDefault();

        $("#note").val(tinymce.activeEditor.getContent())
        var formData = new FormData($(this)[0]);
        formData.append('ticket_id', '');
        formData.append('color', gl_color_notes);
        formData.append('type', 'User Organization');
        formData.append('visibility', all_staff_ids.toString());
        formData.append('company_id', asset_company_id);
        if (gl_sel_note_index !== null) {
            formData.append('id', notes[gl_sel_note_index].id);
        }

        $.ajax({
            type: "POST",
            url: "{{asset('save-ticket-note_cc')}}" ,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend:function(data) {
                $("#note_save_btn").hide();
                $('#note_processing').attr('style', 'display: block !important');
            },
            success: function(data) {
                // console.log(data);
                if (data.success) {

                    alertNotification('success', 'Success' , data.message);

                    let b  = new Date(data.tkt_update_at).toLocaleString('en-US', { timeZone: time_zone });
                    let tkt_updted_date = moment(b).format(date_format + ' ' + 'hh:mm A');
                    // send mail notification regarding ticket action
                    $("#updation-date").html(tkt_updted_date);

                    let note_status = 'added';
                    let note_temp = 'ticket_note_create';
                    if ($('#note-id').val()) {
                        note_status = 'updated';
                        note_temp = 'ticket_note_update';
                    }
                    ticket_notify(note_temp, 'Note ' + note_status, data.data.id);


                    $(this).trigger('reset');

                    // get_ticket_notes();

                    $('#notes_manager_modal').modal('hide');

                } else {
                    alertNotification('error', 'Error' , data.message );
                }
            },
            complete:function(data) {
                $("#note_save_btn").show();
                $('#note_processing').attr('style', 'display: none !important');
            },
            failure: function(errMsg) {
                $("#note_save_btn").show();
                $('#note_processing').attr('style', 'display: none !important');
            }
        });
    });

    // function editNote(id) {
    //     let item = notes.find(item => item.id === id);

    //     if(item != null || item != undefined || item != "") {

    //         $("#note_title").text("Edit Notes");
    //         $('#notes_manager_modal').modal('show');

    //         $('#note-id').val(id);
    //         tinymce.activeEditor.setContent(item.note != null ? item.note : '')
    //         tinyMCE.get(0).getBody().style.backgroundColor = item.color != null ? item.color : '';
    //         gl_color_notes = item.color != null ? item.color : '';

    //     }
    // }

    // function get_ticket_notes() {
    //     $('#show_ticket_notes').html('');
    //     $.ajax({
    //         type: 'GET',
    //         url: ticket_notes_route,
    //         data: { company_id: asset_company_id, type:"User Organization" },
    //         success: function(data) {
    //             if (data.success) {
    //                 if(data.notes_count  != 0) {
    //                     $('.notes_count').addClass('badge badge-light-danger rounded-pill mx-1');
    //                     $('#notes_count').text(data.notes_count);
    //                 }

    //                 notes = data.notes;
    //                 var type = '';

    //                 if (timeouts_list.length) {
    //                     for (let i in timeouts_list) {
    //                         clearTimeout(timeouts_list[i]);
    //                     }
    //                 }

    //                 timeouts_list = [];

    //                 let notes_html = ``;

    //                 for (let i in notes) {

    //                     let timeOut = '';
    //                     let autho = '';
    //                     if (loggedInUser_role == 1) {

    //                         autho = `<div class="mt-2">
    //                                     <span class="btn btn-icon rounded-circle btn-outline-danger waves-effect fa fa-trash"
    //                                         style= "float:right;cursor:pointer;position:relative;bottom:25px"
    //                                         onclick="deleteTicketNote(this, '` + notes[i].id + `')" ></span>

    //                                     <span class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-edit"
    //                                         style="float:right;padding-right:5px;cursor:pointer;position:relative;bottom:25px; margin-right:5px"
    //                                         onclick="editNote(`+ notes[i].id +`)"></span>
    //                                 </div>`;
    //                     }





    //                     type = '<i class="far fa-building"></i>';

    //                     var user_img = ``;
    //                     let is_live = "{{Session::get('is_live')}}";
    //                     let path = is_live == 0 ? '' : 'public/';

    //                     if(notes[i].profile_pic != null) {

    //                         user_img += `<img src="{{asset('${notes[i].profile_pic}')}}"
    //                         width="40px" height="40px" class="rounded-circle" style="border-radius: 50%;"/>`;

    //                     }else{

    //                         user_img += `<img src="{{asset('${path}default_imgs/customer.png')}}"
    //                                 width="40px" height="40px" style="border-radius: 50%;" class="rounded-circle" />`;

    //                     }

    //                     let flup = `<div class="col-12 rounded p-2 my-1 d-flex" id="note-div-` + notes[i].id + `" style="background-color: ` + notes[i].color + `">
    //                         <div style="margin-right: 10px; margin-left: -8px;">
    //                             ${user_img}
    //                         </div>
    //                         <div class="w-100">
    //                             <div class="d-flex justify-content-between">
    //                                 <h5 class="note-head" style="margin-top:10px"> <strong> ${notes[i].name} </strong> on <span class="small"> ${jsTimeZone(notes[i].created_at)} </span>  ${type} </h5>
    //                                 ` + autho + `
    //                             </div>
    //                             <blockquote>
    //                             <p class="col text-dark" style="margin-top:-20px; word-break:break-all; color:black !important">
    //                                 ${notes[i].note.replace(/\r\n|\n|\r/g, '<br />')}
    //                             </p>
    //                             </blockquote>
    //                         </div>
    //                     </div>`;

    //                     $('#show_ticket_notes').append(flup);
    //                 }
    //             }
    //         },
    //         failure: function(errMsg) {

    //         }
    //     });
    // }

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
        let converted_date = new_date.toLocaleString("en-US", {timeZone: time_zone});
        return moment(converted_date).format(date_format + ' ' +'hh:mm A');
    }

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
                    alertNotification('success', 'Success' , data.message);
                    $("#editPicModal").modal('hide');
                    console.log(js_origin);
                    let path = js_origin + data.img;
                    console.log(path);
                    $('#company_curr_img').attr('src', path );
                    $('#company_modal_img').attr('src', path );
                }else{
                    alertNotification('error', 'Error' , data.message);
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

    // $("#phone").keyup(function(){
    //     var regex = new RegExp("^[0-9]+$");

    //     if(!regex.test($(this).val())) {
    //         $("#err4").html("Only numeric values allowed");
    //     }else{
    //         $("#err4").html(" ");
    //     }
    //     if($(this).val() == '') {
    //         $("#err4").html(" ");
    //     }
    // });

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

        // if(!regex.test(phone)) {
        //     $("#phone_error").html("Only numeric values allowed");
        //     return false;
        // }



        if(a && b && c && e == true) {


            var fb = $("#fb").val();
            var pin = $("#update_pinterest").val();
            var twt = $("#twitter").val();
            var insta = $("#insta").val();
            var website = $("#update_website").val();

            if( fb != '') {
                var FBurl = /^(http|https)\:\/\/facebook.com|facebook.com\/.*/i;
                if(!fb.match(FBurl)) {
                    alertNotification('error', 'Error' , 'Provide a valid facebook link');
                    return false;
                }
            }

            if( pin != '') {
                var FBurl = /^(http|https)\:\/\/pinterest.com|pinterest.com\/.*/i;
                if(!pin.match(FBurl)) {
                    alertNotification('error', 'Error' , 'Provide a valid Pinterest link');
                    return false;
                }
            }
            if( twt != '') {
                var FBurl = /^(http|https)\:\/\/twitter.com|twitter.com\/.*/i;
                if(!twt.match(FBurl)) {
                    alertNotification('error', 'Error' , 'Provide a valid Twitter link');
                    return false;
                }
            }
            if( insta != '') {
                var FBurl = /^(http|https)\:\/\/instagram.com|instagram.com\/.*/i;
                if(!insta.match(FBurl)) {
                    alertNotification('error', 'Error' , 'Provide a valid Instagram link');
                    return false;
                }
            }

            var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
            var urlregex = new RegExp(pattern);

            if(website != '') {
                if(!urlregex.test(website)) {
                    alertNotification('error', 'Error' , 'Provide a valid website link');
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

                    alertNotification('success', 'Success' , data.message);

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
                        alertNotification('error', 'Error' ,e.responseJSON.errors.email[0]);
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
        // get_users_table_list();
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

                        alertNotification('success', 'Success' , data.message);

                        if(data.type == 'customer') {
                            $('#add_staff_model').modal('hide');
                        }else{
                            $('#add_comp_user_model').modal('hide');
                        }

                    }else{

                        alertNotification('error', 'Error' , data.message);
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
