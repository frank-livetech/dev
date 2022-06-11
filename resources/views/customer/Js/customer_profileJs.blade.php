<script>

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
    });
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

    // update customer
    $('#update_customer').submit(function(event) {
        event.preventDefault();

        customerClass.updateCustomerProfile();
    });

    // new company add
    $("#companyForm").submit(function(e) {
        e.preventDefault();

        customerClass.addNewCompany();
        
    });


    // upload images
    $("#upload_customer_img").submit(function(e) {
        e.preventDefault();

        var form_data = new FormData(this);
        customerClass.uploadProfileImage(form_data);
    });


    $("#change_password_checkbox").click(function() {
        $(this).is(":checked") ? 
        $('.change_password_row').show() : 
        $('.change_password_row').hide();
    });

    const customerClass = {

        updateCustomerProfile : () => {
            var update_password = $('#password').val();
            var customer_id = $("#customer_id").val();

            var bill_st_add = '';
            var bill_apt_add = '';
            var bill_country = '';
            var bill_state = '';
            var bill_city = '';
            var bill_zip = '';
            var is_bill_add = '';
            var customer_login = 0;
            var cust_type = $("#cust_type").val();

            let cpwd = $(".user-confirm-password-div input[name='confirm_password']").val();

            if ($('#is_bill_add').prop("checked") == true) {

                bill_st_add = $('#bill_st_add').val();
                bill_apt_add = $('#bill_apt_add').val();
                bill_country = $('#bill_add_country').val()
                bill_state = $('#bill_add_state').val();
                bill_city = $('#bill_add_city').val();
                bill_zip = $('#bill_add_zip').val();
                is_bill_add = 1;
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

            // var regex = new RegExp("^[0-9]+$");

            // if(!regex.test(phone)) {
            //     $("#phone_error").html("Only numeric values allowed");
            //     return false;
            // }

            let pass_checkbox =  $("#change_password_checkbox").is(":checked") ? 1 : 0;

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
                bill_st_add: bill_st_add,
                bill_apt_add: bill_apt_add,
                bill_add_country: bill_country,
                bill_add_state: bill_state,
                bill_add_city: bill_city,
                bill_add_zip: bill_zip,
                is_bill_add: is_bill_add,
                customer_login: customer_login
            }

            $.ajax({
                type: "POST",
                url: "{{route('customer.updateCustomer')}}",
                data: form,
                dataType: 'json',
                beforeSend: function(data) {
                    $("#saveBtn").hide();
                    $("#processing").show();
                },
                success: function(data) {
                    console.log(data);
                    if(data.status_code == 200 && data.success == true) {

                        toastr.success(data.message, { timeOut: 5000 });

                        $("#cust_name").text($("#first_name").val() + " " + $("#last_name").val());
                        $("#cust_email").text($("#prof_email").val());
                        $("#cust_add").text($("#prof_address").val());
                        $("#cust_apprt").text($("#apt_address").val());
                        $("#cust_zip").text($("#prof_zip").val());
                        $("#cust_city").text($("#prof_city").val());


                        var state = $("#prof_state").val();
                        // $("#cust_state").text(state);
                        // if(state == "Select State"){
                        //     $("#cust_state").text('');
                        // }else{
                            $("#cust_state").text(state);
                        // }

                        var country = $("#prof_country").val();
                        // $("#cust_country").text(country);
                        // if(country == "Select Country"){
                        //     $("#cust_country").text('');
                        // }else{
                            $("#cust_country").text(country);
                        // }


                        $("#twt").attr('href', $("#prof_twitter").val());
                        $("#fb_icon").attr('href', $("#prof_fb").val());
                        $("#inst").attr('href', $("#prof_insta").val());
                        $("#lkdn").attr('href', $("#prof_linkedin").val());
                        $("#pintrst").attr('href', $("#prof_pinterest").val());
                    }else{
                        toastr.error(data.message, { timeOut: 5000 });
                    }

                },
                complete: function(data) {
                    $("#saveBtn").show();
                    $("#processing").hide();
                },
                error: function(e) {
                    $("#saveBtn").show();
                    $("#processing").hide();
                    console.log(e);
                    if (e.responseJSON.errors.email != null) {
                        toastr.error(e.responseJSON.errors.email[0], {
                            timeOut: 5000
                        });
                    }
                }
            });
        },
        
        addNewCompany : () => {

            var poc_first_name = $('#poc_first_name').val();
            var poc_last_name = $('#poc_last_name').val();
            var name = $('#name').val();
            var email = $('#cemail').val();
            var phone = $('#phone').val();
            var country = $("#country").val();
            var state = $("#state").val();
            var city = $("#city").val();
            var zip = $("#zip").val();
            var address = $('#address').val();
            var user_id = $("#user_id").val()


            var a = customerClass.checkEmptyFields(poc_first_name, $("#err"));
            var b = customerClass.checkEmptyFields(poc_last_name, $("#err1"));
            var c = customerClass.checkEmptyFields(name, $("#err2"));
            var d = customerClass.checkValidEmail(email, $("#err3"));
            var e = customerClass.checkEmptyFields(phone, $("#err4"));

            //var regex = new RegExp("^[0-9]+$");

            // if(!regex.test(phone)) {
            //     $("#err4").html("Only numeric values allowed");
            //     return false;
            // }

            if (a && b && c && d && e == true) {

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
                    user_id: user_id
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{route('customer.saveCompany')}}",
                    data: formData,
                    success: function(data) {
                        toastr.success(data.message, {
                            timeOut: 5000
                        });

                        $('#company_id').append('<option value="' + data.result +'" selected>' + $('#companyForm #name').val() + '</option>');

                        $('#addCompanyModal').modal('hide');
                        $('#companyForm').trigger('reset');
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });


            }
        }, 

        checkEmptyFields: (input , err) => {
            if (input == '') {
                err.html("this field is required");
                return false;
            } else {
                err.html("");
                return true;
            }
        },

        checkValidEmail : (input, err) => {
            var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);

            if (!pattern.test(input)) {
                err.html("please provide valid email");
                return false;
            } else {
                err.html("");
                return true;
            }
        },

        uploadProfileImage : (form_data) => {
            $.ajax({
                url: "{{route('customer.saveProfileImage')}}",
                type: 'POST',
                data: form_data,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200 && data.success == true) {
                        toastr.success(data.message, {
                            timeOut: 5000
                        });
                        let url = root + '/' + data.filename;
                        $('#profile-user-img').attr('src', url);
                        $('#modal_profile_user_img').attr('src', url);
                        $('#usr_pic').attr('src', url);

                        $("#editPicModal").modal('hide');

                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                error: function(e) {
                    console.log(e)
                }

            });
        },

    }
</script>