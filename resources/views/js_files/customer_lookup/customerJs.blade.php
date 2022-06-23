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


            // var regex = new RegExp("^[0-9]+$");

            // if (!regex.test(cmp_phone)) {
            //     $("#cmp_phone_error").html("Only numeric values allowed");
            //     return false;
            // } else {
            //     $("#cmp_phone_error").html(" ");
            // }

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

        // var regex = new RegExp("^[0-9]+$");

        // if (!regex.test(phone)) {
        //     $("#phone_error").html("Only numeric values allowed");
        //     return false;
        // }

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
                    alertNotification('success', 'Success' , data.message );
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
                    alertNotification('warning', 'Warning' , data.message );
                    $("#save_customer_form")[0].reset();
                    $("#addCustomerModal").modal('hide');
                    get_all_customers();
                    get_all_companies();
                } else {
                    alertNotification('error', 'Error' , data.message );
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
                alertNotification('error', 'Error' , emailerr);
            }
        });

    });


    // $("#phone").keyup(function() {

    //     var regex = new RegExp("^[0-9]+$");

    //     if (!regex.test($(this).val())) {
    //         $("#phone_error").html("Only numeric values allowed");
    //     } else {
    //         $("#phone_error").html(" ");
    //     }
    //     if ($(this).val() == '') {
    //         $("#phone_error").html(" ");
    //     }
    // });



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
                alertNotification('success', 'Success' , data.message );
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

// function get_all_customers() {
//     $.ajax({
//         type: "GET",
//         url: "get-all-customers",
//         dataType: 'json',
//         beforeSend: function(data) {
//             $('.loader_container').show();
//         },
//         success: function(data) {
//             var system_date_format = data.date_format;
//             console.log(data, "data");
//             var row = ``;
//             var count = 1;
//             var data = data.customers;

//             for (var i = 0; i < data.length; i++) {
//                 var address = data[i].address != null ? data[i].address : '';
//                 var apt_address = data[i].apt_address != null ? ',' + data[i].apt_address : '';

//                 var cn_name = data[i].country == null ? '' : data[i].country;
//                 var st_name = data[i].cust_state == null ? '' : ',' + data[i].cust_state;
//                 var ct_name = data[i].cust_city == null ? '' : data[i].cust_city;
//                 var zip = data[i].cust_zip == null ? '' : ',' + data[i].cust_zip;

//                 row += `<tr id="row_${data[i].id}">
//                     <td>
//                         <div class="custom-control custom-checkbox">
//                             <input type="checkbox" class="custom-control-input" id="customCheck_${data[i].id}">
//                             <label class="custom-control-label" for="customCheck_${data[i].id}"></label>
//                         </div>
//                     </td>
//                     <td> <a href="customer-profile/${data[i].id}"> ${(data[i].first_name != null ? data[i].first_name : '-')} </a> </td>
//                     <td> <a href="customer-profile/${data[i].id}"> ${(data[i].last_name != null ? data[i].last_name : '-')} </a> </td>
//                     <td>${(data[i].email != null ? data[i].email : '-')}</td>
//                     <td><a href="tel: ${(data[i].phone != null ? data[i].phone : '-')}">${(data[i].phone != null ? data[i].phone : '-')}</a></td>
//                     <td>${(data[i].company != null ? data[i].company.name : '-')}</td>
//                     <td>${address}, ${apt_address}<br>${ct_name} ${st_name} ${zip}<br>${cn_name}</td>
//                     <td>${moment(data[i].created_at).format({!! json_encode($date_format) !!})}</td>
//                     <td>
//                         <button type="button" onclick="showdeleteModal(` + data[i].id + `)" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
//                             <i class="fa fa-trash" aria-hidden="true"></i>
//                         </button>
//                     </td>
//                 </tr>`;
//                 count++;
//             }
            
//             $('#customerTbody').html(row);
//             if(!customer_table) {
//                 customer_table = $('#customerTable').DataTable({
//                     order: [],
//                     columnDefs: [ {
//                         'targets': [0,1],
//                         'orderable': false,
//                     }],
//                 });
//             }
//         },
//         complete: function(data) {
//             $('.loader_container').hide();
//         },
//         error: function(e) {
//             console.log(e)
//         }
//     });
// }
function get_all_customers() {
    $.ajax({
        type: "GET",
        url: "get-all-customers",
        dataType: 'json',
        beforeSend: function(data) {
            $('.loader_container').show();
        },
        success: function(data) {
            console.log(data.customers, "data1");
            var system_date_format = data.date_format;
            $('#customerTable').DataTable().destroy();
            let tt = $('#customerTable').DataTable({
            data:  data.customers,
            columns: [
                {
                    render: function (data, type, full, meta) {

                return `<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck_` + full.id + `">
                            <label class="custom-control-label" for="customCheck_` + full.id + `"></label>
                        </div>`;
                }
                },
                {
                    render: function (data, type, full, meta) {

                        var $name = full.first_name,
                        $image = full.avatar_url;
                        if ($image) {
                        // For Avatar image
                        var $imgoutput =
                            '<img src=" ' + root + '/' + $image + '" alt="Avatar" height="32" width="32">';
                        } else {
                        // For Avatar badge
                        var stateNum = Math.floor(Math.random() * 6) + 1;
                        var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                        var $state = states[stateNum],
                            $name = full.first_name,
                            $initials = $name.match(/\b\w/g) || [];
                        $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                        $imgoutput = '<span class="avatar-content">'+ $initials +'</span>';
                        }
                        var colorClass = $image === '' ? ' bg-light-' + $state + ' ' : '';
                        
                        var $row_output =
                        '<div class="d-flex justify-content-left align-items-center">' +
                            '<div class="avatar-wrapper">' +
                            '<div class="avatar ' +
                            colorClass +
                            ' me-1">' +
                            $imgoutput +
                            '</div>' +
                            '</div>' +
                            '<div class="d-flex flex-column">' +
                            '<a href="customer-profile/' + (full.id != null ? full.id : '-') + '" class="user_name text-truncate"><span class="fw-bold">' +
                            full.first_name + ' ' + full.last_name +
                            '</span></a>' +
                            '<small class="emp_post text-muted"><a href="company-profile/' + (full.company != null ? full.company.name : '-') + '" class="user_name text-truncate"><span class="fw-bold">'
                                + (full.company != null ? full.company.name : '-') +
                            '</span>'  
                            '</a></small>' +
                            '</div>' +
                        '</div>';
                        return $row_output;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        
                        return full.email;
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        var cust_phone = full.phone;
                        // let newPhone = cust_phone.replace(/[()\s-+]/g, '');
                        // let copynumber= (cust_phone != null ? `<svg xmlns="http://www.w3.org/2000/svg" onclick="copyToClipBoard(` +cust_phone+ `)" style="cursor: pointer" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>` : '-');
                         let phonenumber = cust_phone != null ? `<a href ="tel:`+cust_phone+`">` +cust_phone+ `</a>` : '-';
                        return ('<span>' + phonenumber +'</span>');
                    }
                },
                {
                    render: function(data, type, full, meta) {
                       
                        return (full.company != null ? full.company.name : '-');
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        var address = full.address != null ? full.address : '';
                        var apt_address = full.apt_address != null ? ',' + full.apt_address : '';

                        var cn_name = full.country == null ? '' : full.country;
                        var st_name = full.cust_state == null ? '' : ',' + full.cust_state;
                        var ct_name = full.cust_city == null ? '' : full.cust_city;
                        var zip = full.cust_zip == null ? '' : ',' + full.cust_zip;
                        
                        return  `<span>`+ address + `` + apt_address + `<br>` + ct_name + ` ` + st_name + ` ` + zip + `<br>` + cn_name + `</span>`
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        return (moment(full.created_at).format(system_date_format));
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        return `<span class="badge text-capitalize badge-light-success badge-pill"> active </span>`;
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        return `
                            <div class="dropdown ms-50">
                                <div role="button" class="dropdown-toggle hide-arrow" id="email_more" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-medium-2"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </div>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="email_more">
                                    <a href="customer-profile/` + (full.id != null ? full.id : '-') + `" class="user_name text-truncate"> <div class="dropdown-item" ><svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text me-50"><path data-v-32017d0f="" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline data-v-32017d0f="" points="14 2 14 8 20 8"></polyline><line data-v-32017d0f="" x1="16" y1="13" x2="8" y2="13"></line><line data-v-32017d0f="" x1="16" y1="17" x2="8" y2="17"></line><polyline data-v-32017d0f="" points="10 9 9 9 8 9"></polyline></svg>Details</div></a>
                                   
                                    <div class="dropdown-item" onclick="showdeleteModal(` + full.id + `)"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>Delete</div>
                                </div>
                            </div>
                            `;
                    }
                },
                
            ],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                '<"col-sm-12 col-md-4 col-lg-6" l>' +
                '<"col-sm-12 col-md-8 col-lg-6 ps-xl-75 ps-0"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end align-items-center flex-sm-nowrap flex-wrap me-1"<"me-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'Search',
                searchPlaceholder: 'Search..'
            },
            // Buttons with Dropdown
            buttons: [
                {
                text: 'Add New Customer',
                className: 'add-new btn btn-primary mt-50',
                attr: {
                    'data-bs-toggle': 'modal',
                    'data-bs-target': '#addCustomerModal'
                },
                init: function (api, node, config) {
                    $(node).removeClass('btn-secondary');
                }
                }
            ],
            
            language: {
                paginate: {
                // remove previous & next text from pagination
                previous: '&nbsp;',
                next: '&nbsp;'
                }
            },
        });
    },
        complete: function(data) {
            $('.loader_container').hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}
function copyToClipBoard(text) {
    
    let $input = $("<input>");
    $('body').append($input);

    $input.val(text).select();
    document.execCommand('copy');
    $input.remove();

    toastr.success('Phone Number Copied!', { timeOut: 5000 });
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
                alertNotification('success', 'Success' , data.message );
                $('#row_' + id).remove();
                $("#delete_customer_model").modal('hide');

                let type = 'Wordpress';
                let slug = 'customer-lookup';
                let icon = 'fas fa-user-alt';
                let title = 'WP Customer';
                let desc = 'WP Customer Deleted by' + $("#curr_user_name").val();
                sendNotification(type,slug,icon,title,desc);
            } else {
                alertNotification('error', 'Error' , data.message );
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
