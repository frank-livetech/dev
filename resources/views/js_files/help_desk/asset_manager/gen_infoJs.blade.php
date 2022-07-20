<script>
// Asset Manager Gen Info Script Blade
    $("#pass_icon").on('click', function () {
        $(this).toggleClass("fa-eye fa-eye-slash");

        var input = $("input[name='password']");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $(".show_password").css("display","block");
            $(".star_password").css("display","none");
        } else {
            input.attr("type", "password");
            $(".show_password").css("display","none");
            $(".star_password").css("display","block");
        }

    })

    $("#input_pass_icon").on('click', function () {
        $(this).toggleClass("fa-eye fa-eye-slash");

        var input = $("input[name='password']");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

    })


    function editRecord() {

        let template_fields =  {!! json_encode($asset_templates_fields) !!};

        var asset_id = $("#asset_id").val();
        var asset_forms_id = $("#asset_forms_id").val();
        var asset_title = $("#asset_title").val();

        var opts = {};
        var asset_data = [];
        var form_data = [];

        for(var i = 0; i < template_fields.length; i++) {

            var colname = {"keys" : "fl_" + template_fields[i].id};
            var value = {"value" : $("#fields_"+template_fields[i].id).val()};
            asset_data.push($.extend(true, {}, colname, value));

        }

        if( $(".fields_type").val() == 'address' ) {
            var comp_add = [];
            var field_ids = [];
            $(".field_id").each(function() {
                var field_id = $(this).val();
                field_ids.push(field_id);

                var address = $(".address_"+ field_id).val();
                var aprtment_no = $(".apartment_"+ field_id).val();
                var city = $(".city_"+ field_id).val();
                var state = $(".state_"+ field_id).val();
                var zipcode = $(".zipcode_"+ field_id).val();
                var country = $(".country_"+ field_id).val();

                var complete_address =  address + "|" + aprtment_no + "|" + city + "|" + state + "|" + zipcode + "|" + country ;

                comp_add.push(complete_address);

            });

            form_data = {
                    asset_title:asset_title,
                    asset_forms_id:asset_forms_id,
                    asset_id:asset_id,
                    address : "address",
                    data : comp_add,
                    field_id:field_ids,
                }


        }else{
            form_data = {
                data : asset_data,
                asset_title:asset_title,
                asset_id:asset_id,
                asset_forms_id:asset_forms_id,
                template:template_fields.length
            }
        }


        $.ajax({
            type: "POST",
            url: "{{url('update_asset_manager')}}",
            data: form_data,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.status_code == 200 && data.success == true) {
                    alertNotification('success', 'Success' , data.message);

                    $("#asset_title_text").text(asset_title);

                    $(".field").css('display','none');
                    $(".field_text").css('display','block');

                    $("#asset_title").css('display','none');
                    $("#asset_title_text").css('display','block');

                    $('#edit_request_btn').css('display','block');
                    $('#save_request_btn').css('display','none');
                    $('#cancel_request_btn').css('display','none');

                    for(var i = 0; i < template_fields.length; i++) {
                        var value = $("#fields_"+template_fields[i].id).val();
                        $(".showtext_"+template_fields[i].id).text(value);
                    }

                } else {
                    alertNotification('error', 'Error' , data.message);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function merger_objects(obj1,obj2){
        $.each(obj2, function(key, value) {
            obj1[key] = value;
        });
        return obj1;
    }

   function editRequest(){

        $('#edit_request_btn').css('display','none');
        $('#save_request_btn').css('display','block');
        $('#cancel_request_btn').css('display','block');

        $("#asset_title").css('display','block');
        $("#asset_title_text").css('display','none');

        $(".field").css('display','block');
        $(".field_text").css('display','none');


        $("#input_pass_icon").css('display','block');
        $("#pass_icon").css('display','none');


        // $("input[name='ipv4']").inputmask({
        //     alias: "ip",
        //     greedy: false
        // });




        if( $(".fields_type").val() == 'address' ) {


            $(".field_id").each(function() {
                var field_id  = $(this).val();

                $(".add_"+field_id).css('display','none');

                $(".this_div_"+field_id).css('display','block');

                var value = $(".add_field_value_"+field_id).val();
                let add_value = value.split("|");

                var div = `
                    <div id="all_input_`+field_id+`" class="all_input_`+field_id+`">
                        <input type='text' class="form-control mt-2 address_`+field_id+`" value="`+ add_value[0] +`" id="add1_`+field_id+`"/>
                        <input type='text' class="form-control mt-2 apartment_`+field_id+`" value="`+ add_value[1] +`" id="add2_`+field_id+`"/>
                        <input type='text' class="form-control mt-2 city_`+field_id+`" value="`+ add_value[2] +`" id="add3_`+field_id+`"/>
                        <select class="select2 form-control mt-2 state_`+field_id+`" id="state_`+field_id+`"></select>
                        <input type='text' class="form-control mt-2 zipcode_`+field_id+`" value="`+ add_value[4] +`" id="add5_`+field_id+`"/>
                        <select class="select2 form-control mt-2 country_`+field_id+`" id="country_`+field_id+`"></select>
                    </div>
                `;

                $(".this_div_"+field_id).html(div);


            $.ajax({
                type: "GET",
                url: "{{asset('get_all_statescountries')}}",
                dataType: 'json',
                success: function(data) {
                    console.log(data, "data");
                    var country_obj = data.countries;
                    var state_obj = data.states;

                    var countries = ``;
                    var states = ``;
                    var root = `<option>Select</option>`;
                    for(var i =0 ; i < country_obj.length; i++) {
                        countries += `<option value="`+country_obj[i].id+`" `+(country_obj[i].id == add_value[5] ? 'selected' : '-') +`>`+country_obj[i].name+`</option>`;
                    }

                    for(var i =0 ; i < state_obj.length; i++) {
                        states += `<option value="`+state_obj[i].id+`" `+(state_obj[i].id == add_value[3] ? 'selected' : '-') +`>`+state_obj[i].name+`</option>`;
                    }

                    $("#country_"+field_id).append(root + countries);
                    $("#state_"+field_id).append(root + states);
                },
                error: function(f) {
                    console.log('get assets error ', f);
                }
            });

        });



        $(".field").css('display','none');


        }




    }


    function cancelEditRequest(){

        $('#edit_request_btn').css('display','block');
        $('#save_request_btn').css('display','none');
        $('#cancel_request_btn').css('display','none');

        $("#asset_title").css('display','none');
        $("#asset_title_text").css('display','block');

        $(".field").css('display','none');
        $(".field_text").css('display','block');


        $("#input_pass_icon").css('display','none');
        $("#pass_icon").css('display','block');




        if( $(".fields_type").val() == 'address' ) {

            $(".field_id").each(function() {
                var field_id  = $(this).val();

                // show all address text
                $(".add_"+field_id).css('display','block');
                $(".this_div_"+field_id).css('display','none');


            });

        }


    }
</script>
