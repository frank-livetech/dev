<script>
let customer_table = null;
// Customer Js Blade
$(function() {
    try {
        if(countries_list.length) {
            $('#country').trigger('change');
        }
    } catch (err) {
        console.log(err);
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });


    $('#save_customer_form').submit(function(event) {

        event.preventDefault();
        event.stopPropagation();

        var method = $(this).attr("method");
        var action = $(this).attr("action");
        var has_account = 0;

        var is_active = 0;
        var customer_login = 0;
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var company = $('#company_list').val();
        var cust_type = $('#cust_type').val();
        var country = $("#country").val();
        var state = $('#state').val();
        var city = $('#city').val();
        var zip = $('#zip').val();
        var address = $('#address').val();
        var apt_address = $('#apt_address').val();

        if ($("#active").is(":checked")) {
            is_active = 1;
        } else {
            is_active = 0;
        }

        if ($("#cust_login").is(":checked")) {
            customer_login = 1;
            has_account = 1;
        } else {
            customer_login = 0;
            has_account = 0;
        }

        if ($("#cmp_name").val() && $("#cmp_email").val()) {
            var cmp_name = $("#cmp_name").val();
            var poc_first_name = $("#poc_first_name").val();
            var poc_last_name = $("#poc_last_name").val();
            var cmp_email = $("#cmp_email").val();
            var cmp_phone = $("#cmp_phone").val();


            var regex = new RegExp("^[0-9]+$");

            if (!regex.test(cmp_phone)) {
                $("#cmp_phone_error").html("Only numeric values allowed");
                return false;
            } else {
                $("#cmp_phone_error").html(" ");
            }

        }

        if (first_name == "") {
            $(".name_error").html("First name is required.");
            return false;
        } else {
            $(".name_error").html(" ");
        }

        if (last_name == "") {
            $(".last_error").html("Last Name is required");
            return false;
        } else {
            $(".last_error").html(" ");
        }

        if (email == "") {
            $("#err_email").html("Email is required.");
            return false;
        } else {
            $("#err_email").html(" ");
        }

        var regex = new RegExp("^[0-9]+$");

        if (!regex.test(phone)) {
            $("#phone_error").html("Only numeric values allowed");
            return false;
        }

        var formData = {
            first_name: first_name,
            last_name: last_name,
            email: email,
            phone: phone,
            company_id: company,
            cust_type: cust_type,
            country: country,
            state: state,
            city: city,
            zip: zip,
            address: address,
            apt_address: apt_address,
            is_active: is_active,
            customer_login: customer_login,
            cmp_name: cmp_name,
            poc_first_name: poc_first_name,
            poc_last_name: poc_last_name,
            cmp_email: cmp_email,
            cmp_phone: cmp_phone,
            has_account: has_account
        }


        $.ajax({
            type: method,
            url: action,
            data: formData,
            dataType: 'json',
            beforeSend: function(data) {
                $('#cust_loader').fadeIn();
                $("#custsvebtn").hide();
                $("#custprocessbtn").show();
            },
            success: function(data) {
                console.log(data);
                if (data.status == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#save_customer_form")[0].reset();
                    $("#addCustomerModal").modal('hide');
                    get_all_customers();
                    get_all_companies();

                    let type = 'Wordpress';
                    let slug = 'customer-lookup';
                    let icon = 'fas fa-user-alt';
                    let title = 'WP Customer';
                    let desc = 'WP Customer Created by ' + $("#curr_user_name").val();
                    sendNotification(type,slug,icon,title,desc);

                    closeModal();

                }else if(data.status == 201 && data.success == true) {
                    toastr.warning(data.message, { timeOut: 5000 });
                    $("#save_customer_form")[0].reset();
                    $("#addCustomerModal").modal('hide');
                    get_all_customers();
                    get_all_companies();
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function(data) {
                $('#cust_loader').fadeOut(500);
                $("#custsvebtn").show();
                $("#custprocessbtn").hide();
            },
            error: function(error) {
                // console.log(error);
                $("#custsvebtn").show();
                $("#custprocessbtn").hide();
                let emailerr = error.responseJSON.errors != null ? error.responseJSON.errors.email[0] : '-';
                toastr.error(emailerr, { timeOut: 5000 });
            }
        });

    });


    $("#phone").keyup(function() {

        var regex = new RegExp("^[0-9]+$");

        if (!regex.test($(this).val())) {
            $("#phone_error").html("Only numeric values allowed");
        } else {
            $("#phone_error").html(" ");
        }
        if ($(this).val() == '') {
            $("#phone_error").html(" ");
        }
    });



    get_all_customers();
    get_all_companies();


});

function closeModal() {


    $("#phone_error").html(" ");
    $(".name_error").html(" ");
    $(".last_error").html(" ");
    $("#err_email").html(" ");
    $("#new-Company-div").hide();
    $("#cmp_phone_error").html(" ");

    $("#customer_email").show();

    $('#reverting').addClass('col-md-1');
    $('#reverting').removeClass('col-md-4');
    $("#new-Company-button").text("New");

    $("#country").val("").trigger('change');
    $("#state").val("").trigger('change');

}

function noEmail() {

    var first_name = $("#first_name").val();
    var last_name = $("#last_name").val();

    var site_domain = $("#site_domain").val();

    var a = checkEmptyFields(first_name, $(".name_error"));
    var b = checkEmptyFields(last_name, $(".last_error"));

    let random_no = Math.random().toString(36).substring(7);

    if (a == true && b == true) {
        var email = (first_name.replace(/\s/g, '')).toLowerCase() + "_" + (last_name.replace(/\s/g, '')).toLowerCase() + "_" + random_no + "@" + site_domain;
        $("#email").val(email);
        $('#email').prop('readonly', true);

        $(".name_error").text(" ");
        $(".last_error").text(" ");

    }

}

function resetEmail() {
    $('#email').prop('readonly', false);
    $("#email").val("");
}


function checkConfirmPassword(cpass, err, pass) {
    // if(cpass == '') {
    //     err.html("this field is required");
    //     return false;
    // }else 
    if (cpass != pass) {
        err.html("password not matched..");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function checkPassword(pass, err) {
    // if(pass == '') {
    //     err.html("this field is required");
    //     return false;
    // } else 
    if (pass.length && pass.length < 8) {
        err.html("minimum 8 character required");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function checkEmptyFields(input, err) {
    if (input == '') {
        err.html("This Field is Required");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function checkZipCode(input, err) {
    if (input == '') {
        err.html("this field is required");
        return false;
    } else if (input.length < 5) {
        err.html("invalid zip code.. please provide 5 digit");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function checkValidEmail(input, err) {
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

    if (input == '') {
        err.html("this field is required");
        return false;
    } else if (!pattern.test(input)) {
        err.html("please provide valid email");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function updateValue(element, column, id, old_value) {

    var value = element.innerText;

    if (value == old_value) {
        console.log("Asd");
    } else {

        var form = {
            value: value,
            column: column,
            id: id
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function(data) {
                $('.loader_container').fadeIn();
            },
            type: "POST",
            url: "update-customer",
            data: form,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                toastr.success(data.message, { timeOut: 5000 });
            },
            complete: function(data) {
                $('.loader_container').fadeOut(500);
            },
            error: function(e) {
                console.log(e)
            }
        });
    }



}

function get_all_customers() {
    $.ajax({
        type: "GET",
        url: "get-all-customers",
        dataType: 'json',
        beforeSend: function(data) {
            $('.loader_container').show();
        },
        success: function(data) {
            var system_date_format = data.date_format;
            console.log(data, "data");
            var row = ``;
            var count = 1;
            var data = data.customers;

            for (var i = 0; i < data.length; i++) {
                var address = data[i].address != null ? data[i].address : '';
                var apt_address = data[i].apt_address != null ? ',' + data[i].apt_address : '';

                var cn_name = data[i].country == null ? '' : data[i].country;
                var st_name = data[i].cust_state == null ? '' : ',' + data[i].cust_state;
                var ct_name = data[i].cust_city == null ? '' : data[i].cust_city;
                var zip = data[i].cust_zip == null ? '' : ',' + data[i].cust_zip;

                row += `<tr id="row_${data[i].id}">
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck_${data[i].id}">
                            <label class="custom-control-label" for="customCheck_${data[i].id}"></label>
                        </div>
                    </td>
                    <!-- <td>${count}</td>-->
                    <td><a href="customer-profile/${data[i].id}"><i class="fas fa-eye"></i></a></td>
                    <td>${(data[i].first_name != null ? data[i].first_name : '-')}</td>
                    <td>${(data[i].last_name != null ? data[i].last_name : '-')}</td>
                    <td>${(data[i].email != null ? data[i].email : '-')}</td>
                    <td><a href="tel: ${(data[i].phone != null ? data[i].phone : '-')}">${(data[i].phone != null ? data[i].phone : '-')}</a></td>
                    <td>${(data[i].company != null ? data[i].company.name : '-')}</td>
                    <td>${address}, ${apt_address}<br>${ct_name} ${st_name} ${zip}<br>${cn_name}</td>
                    <td>${moment(data[i].created_at).format({!! json_encode($date_format) !!})}</td>
                    <td><button onclick="showdeleteModal(` + data[i].id + `)" class="btn btn-danger btn-sm rounded"><i class="fas fa-trash"></i> delete</button></td>
                </tr>`;
                count++;
            }
            
            $('#customerTbody').html(row);
            if(!customer_table) {
                customer_table = $('#customerTable').DataTable({
                    order: [],
                    columnDefs: [ {
                        'targets': [0,1],
                        'orderable': false,
                    }],
                });
            }
            // var customer_table = $('#customerTable').DataTable();

            // $('#toggle_column').multipleSelect({
            //     width: 300,
            //     onClick: function(view) {
            //         var selectedItems = $('#toggle_column').multipleSelect("getSelects");
            //         for (var i = 0; i < 14; i++) {
            //             columns = customer_table.column(i).visible(0);
            //         }
            //         for (var i = 0; i < selectedItems.length; i++) {
            //             var s = selectedItems[i];
            //             customer_table.column(s).visible(1);
            //         }
            //         $('#contacts_table').css('width', '100%');
            //     },
            //     onCheckAll: function() {
            //         for (var i = 0; i < 14; i++) {
            //             columns = customer_table.column(i).visible(1);
            //         }
            //     },
            //     onUncheckAll: function() {
            //         for (var i = 0; i < 14; i++) {
            //             columns = customer_table.column(i).visible(0);
            //         }
            //         $('#contacts_table').css('width', '100%');
            //     }
            // });
        },
        complete: function(data) {
            $('.loader_container').hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}

function showdeleteModal(id) {
    $("#delete_customer_model").modal('show');
    $("#delete_id").val(id);
}


function deleteRecord() {
    let id = $("#delete_id").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: "POST",
        url: "delete_customers",
        data: { id: id },
        dataType: 'json',
        beforeSend: function(data) {
            $('.loader_container').fadeIn();
            $("#delbtn").hide();
            $("#cust_del").show();
        },
        success: function(data) {
            console.log(data);
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
                $('#row_' + id).remove();
                $("#delete_customer_model").modal('hide');

                let type = 'Wordpress';
                let slug = 'customer-lookup';
                let icon = 'fas fa-user-alt';
                let title = 'WP Customer';
                let desc = 'WP Customer Deleted by' + $("#curr_user_name").val();
                sendNotification(type,slug,icon,title,desc);
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete: function(data) {
            $('.loader_container').fadeOut(500);
            $("#delbtn").show();
            $("#cust_del").hide();
        },
        error: function(e) {
            console.log(e);
            $("#delbtn").show();
            $("#cust_del").hide();
        }
    });
}

function get_all_companies() {
    $.ajax({
        type: "GET",
        url: "get_company_lookup",
        dataType: 'json',
        beforeSend: function(data) {
            $('.loader_container').show();
        },
        success: function(data) {

            data = data.companies;
            var select = `<option value="">Select</option>`;
            var option = ``;

            for (var i = 0; i < data.length; i++) {
                option += `<option value="` + data[i].id + `">` + data[i].name + `</option>`;
            }

            $("#company_list").html(select + option);


        },
        complete: function(data) {
            $('.loader_container').hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}
</script>
