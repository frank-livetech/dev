$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    get_all_companies();
    // add_company();
    ///////////////////////////////////

    //////////////////////////////////
    $('#save_company_form').submit(function(e) {
        e.preventDefault();

        var method = $(this).attr("method");
        var action = $(this).attr("action");
        var is_default = 0;

        var poc_first_name = $('#poc_first_name').val();
        var poc_last_name = $('#poc_last_name').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var country = $("#country").val();
        var state = $("#state").val();
        var city = $("#city").val();
        var zip = $("#zip").val();
        var address = $('#address').val();
        var apt_address = $('#apt_address').val();
        var user_id = $("#user_id").val()
        var a = checkEmptyFields(poc_first_name, $("#err"));
        var b = checkEmptyFields(poc_last_name, $("#err1"));
        var c = checkEmptyFields(name, $("#err2"));
        var d = checkValidEmail(email, $("#err3"));


        if ($("#set_default").is(":checked")) {
            is_default = 1;
        } else {
            is_default = 0;
        }

        var regex = new RegExp("^[0-9]+$");

        if (!regex.test(phone)) {
            $("#err4").html("Only numeric values allowed");
            return false;
        }

        if (a && b && c && d == true) {

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
                apt_address: apt_address,
                user_id: user_id,
                is_default: is_default
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: method,
                url: action,
                data: formData,
                dataType: 'json',
                beforeSend: function(data) {
                    $('.loader_container').show();
                    $('#savebtn').hide();
                    $('#processing').show();
                },
                success: function(data) {
                    console.log(data);

                    if (data.status_code == 200 && data.success == true) {
                        toastr.success(data.message, { timeOut: 5000 });
                        get_all_companies();
                        $("#save_company_form")[0].reset();
                        $("#company_model").modal('hide');
                    } else if (data.status_code == 500 && data.success == false) {
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                complete: function(data) {
                    $('.loader_container').hide();
                    $('#savebtn').show();
                    $('#processing').hide();
                },
                error: function(e) {
                    console.log(e)
                    $('#savebtn').show();
                    $('#processing').hide();
                    toastr.error(e.responseJSON.errors.email[0], { timeOut: 5000 });
                }
            });


        }


    });


    $("#phone").keyup(function() {

        var regex = new RegExp("^[0-9]+$");

        if (!regex.test($(this).val())) {
            $("#err4").html("Only numeric values allowed");
        } else {
            $("#err4").html(" ");
        }
        if ($(this).val() == '') {
            $("#err4").html(" ");
        }
    });



    wp_data();
});

function updateValue(element, column, id, old_value) {


    var value = element.innerText;

    if (value != old_value) {
        var form = {
            value: value,
            column: column,
            id: id
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            url: "update-company",
            data: form,
            dataType: 'json',
            beforeSend: function(data) {
                $('.loader_container').show();
            },
            success: function(data) {
                console.log(data);
                toastr.success(data.message, { timeOut: 5000 });
            },
            complete: function(data) {
                $('.loader_container').hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
    }


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
            console.log(data, "data");
            var system_date_format = data.date_format;
            var row = ``;
            var count = 1;
            data = data.companies;
            for (var i = 0; i < data.length; i++) {
                console.log(data[i].created_at)
                var created_at = data[i].created_at != null ? moment(data[i].created_at).format('MMMM Do YYYY, h:mm:ss a') : '-';


                var address = data[i].address != null ? data[i].address : '';
                var apt_address = data[i].apt_address != null ? ',' + data[i].apt_address : '';

                var cn_name = data[i].cmp_country == null ? '' : data[i].cmp_country;
                var st_name = data[i].cmp_state == null ? '' : ',' + data[i].cmp_state;
                var ct_name = data[i].cmp_city == null ? '' : data[i].cmp_city;
                var zip = data[i].cmp_zip == null ? '' : ',' + data[i].cmp_zip;

                row += `
                    <tr id="row_` + data[i].id + `" >
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck_` + data[i].id + `">
                                <label class="custom-control-label" for="customCheck_` + data[i].id + `"></label>
                            </div>
                        </td>
                        <!--<td>` + count + `</td>-->
                        <td><a href="company-profile/` + data[i].id + `"><i class="fas fa-eye"></i></a></td>
                        
                        <td contenteditable="true"  onBlur="updateValue(this,'name',` + data[i].id + `,'` + data[i].name + `')">` + (data[i].name != null ? data[i].name : '-') + `</td>
                        <td contenteditable="true"  onBlur="updateValue(this,'poc_first_name',` + data[i].id + `,'` + data[i].poc_first_name + `')">` + (data[i].poc_first_name != null ? data[i].poc_first_name : '-') + `</td>
                        <td contenteditable="true"  onBlur="updateValue(this,'poc_last_name',` + data[i].id + `,'` + data[i].poc_last_name + `')">` + (data[i].poc_last_name != null ? data[i].poc_last_name : '-') + `</td>
                        <td contenteditable="true"  onBlur="updateValue(this,'email',` + data[i].id + `,'` + data[i].email + `')">` + (data[i].email != null ? data[i].email : '-') + `</td>
                        <td><a href="tel:` + (data[i].phone != null ? data[i].phone : '-') + `">` + (data[i].phone != null ? data[i].phone : '-') + `</a></td>
                        <td>` + address + `` + apt_address + `<br>` + ct_name + ` ` + st_name + ` ` + zip + `<br>` + cn_name + `</td>
                        
                        <td>` + moment(data[i].created_at).format(system_date_format) + `</td>
                        <td><button onclick="showdeleteModal(` + data[i].id + `)" class="btn btn-danger btn-sm rounded"><i class="fas fa-trash"></i> delete</button></td>
                        </tr>
                `;
                count++;
            }


            $('#companyTBody').html(row);
            var company_table = $('#companyTable').DataTable();

            $('#select_column').multipleSelect({
                width: 300,
                onClick: function(view) {
                    var selectedItems = $('#select_column').multipleSelect("getSelects");
                    for (var i = 0; i < 11; i++) {
                        columns = company_table.column(i).visible(0);
                    }
                    for (var i = 0; i < selectedItems.length; i++) {
                        var s = selectedItems[i];
                        company_table.column(s).visible(1);
                    }

                },
                onCheckAll: function() {
                    for (var i = 0; i < 11; i++) {
                        columns = company_table.column(i).visible(1);
                    }
                },
                onUncheckAll: function() {
                    for (var i = 0; i < 11; i++) {
                        columns = company_table.column(i).visible(0);
                    }

                }
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

function checkEmptyFields(input, err) {
    if (input == '') {
        err.html("This field is required");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function checkValidEmail(input, err) {
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

    if (input == '') {
        err.html("This field is required");
        return false;
    } else if (!pattern.test(input)) {
        err.html("please provide valid email");
        return false;
    } else {
        err.html("");
        return true;
    }
}


function showdeleteModal(id) {
    $("#delete_company_model").modal('show');
    $("#delete_id").val(id);
}


function deleteRecord() {
    let id = $("#delete_id").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: "POST",
        url: delete_company,
        data: { id: id },
        dataType: 'json',
        beforeSend: function(data) {
            $('.loader_container').show();
            $("#delbtn").hide();
            $("#cust_del").show();
        },
        success: function(data) {
            console.log(data);
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
                get_all_companies();
                $("#delete_company_model").modal('hide');
            } else if (data.status_code == 500 && data.success == false) {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete: function(data) {
            $('.loader_container').hide();
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


function closeModal() {

    $("#err").html(" ");
    $("#err1").html(" ");
    $("#err2").html(" ");
    $("#err3").html(" ");
    $("#err4").html(" ");

    $("#country").val("").trigger('change');
    $("#state").val("").trigger('change');

}

function wp_data() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: "GET",
        url: 'testcompany',
        dataType: 'json',
        beforeSend: function(data) {},
        success: function(data) {
            console.log(data);
        },
        complete: function(data) {},
        error: function(e) {
            console.log(e);
        }
    });
}