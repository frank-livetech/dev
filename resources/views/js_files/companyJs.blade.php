
<script>
// Company Js Script Blade
$(document).ready(function() {
    try {
        if(countries_list.length) {
            $('#country').trigger('change');
        }
    } catch(err) {
        console.log(err);
    }

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
        // var d = checkValidEmail(email, $("#err3"));


        if ($("#set_default").is(":checked")) {
            is_default = 1;
        } else {
            is_default = 0;
        }

        // var regex = new RegExp("^[0-9]+$");

        // if (!regex.test(phone)) {
        //     $("#err4").html("Only numeric values allowed");
        //     return false;
        // }

        if (a && b && c == true) {

            var formData = {
                poc_first_name: poc_first_name,
                poc_last_name: poc_last_name,
                name: name,
                domain: domain,
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
                        alertNotification('success', 'Success' ,data.message );
                        get_all_companies();
                        $("#save_company_form")[0].reset();
                        $("#company_model").modal('hide');
                    } else if (data.status_code == 500 && data.success == false) {
                        alertNotification('error', 'Error' ,data.message );
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
                    alertNotification('error', 'Error' , e.responseJSON.errors.email[0] );
                }
            });


        }


    });


    // $("#phone").keyup(function() {

    //     var regex = new RegExp("^[0-9]+$");

    //     if (!regex.test($(this).val())) {
    //         $("#err4").html("Only numeric values allowed");
    //     } else {
    //         $("#err4").html(" ");
    //     }
    //     if ($(this).val() == '') {
    //         $("#err4").html(" ");
    //     }
    // });



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
                alertNotification('success', 'Success' ,data.message );
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
            console.log(data.companies, "data");
            var system_date_format = data.date_format;
            select = $('.select2'),
            select.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
            // the following code is used to disable x-scrollbar when click in select input and
            // take 100% width in responsive also
            dropdownAutoWidth: true,
            width: '100%',
            dropdownParent: $this.parent()
            });
            });
            $('#companyTable').DataTable().destroy();
            let tt = $('#companyTable').DataTable({
            data:  data.companies,
            columns: [
                {
                    className: 'control',
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    render: function (data, type, full, meta) {

                        var $name = full.name,
                        $com_domain = full.domain != null ? `<a href ="` +full.domain+ `">Go To Domain</a>`: '-',
                        $image = full.com_logo;
                        if ($image) {
                        // For Avatar image
                        var $imgoutput =
                            '<img src=" ' + root + '/' + $image + '" alt="Avatar" height="32" width="32">';
                        } else {
                        // For Avatar badge
                        var stateNum = Math.floor(Math.random() * 6) + 1;
                        var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                        var $state = states[stateNum],
                            $name = full.name,
                            $initials = $name.match(/\b\w/g) || [];
                        $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                        $imgoutput = '<span class="avatar-content">' + $initials + '</span>';
                        }
                        var colorClass = $image === '' ? ' bg-light-' + $state + ' ' : '';
                        let poc_first_name = full.poc_first_name != null ? full.poc_first_name : '-';
                        let poc_last_name = full.poc_last_name != null ? full.poc_last_name : '-';
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
                            '<a href="company-profile/' + (full.id != null ? full.id : '-') + '" class="user_name text-truncate text-body"><span class="fw-bolder">' +
                            $name +
                            '</span></a>' +
                            '<small class="emp_post text-muted">'
                                 + poc_first_name + ' ' + poc_last_name +
                            '</small>' +
                            '</div>' +
                        '</div>';
                        return $row_output;
                    }
                },
                {
                    render: function(data, type, full, meta) {

                        const phone = (full.phone != null ? full.phone : '-');
                        // let newPhone = phone.replace(/[()\s-+]/g, '');
                        // let copynumber= phone != null ? `<svg xmlns="http://www.w3.org/2000/svg" onclick="copyToClipBoard(` +newPhone+ `)" style="cursor: pointer" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>` : '-';
                        // let phonenumber = phone != null ? `<a href ="tel:`+phone+`" class="text-body">` +phone+ `</a>` : '-';
                        return (`<a href ="tel:`+ phone +`" class="text-body">` +phone+ `</a>`);

                    }
                },
                // {
                //     render: function(data, type, full, meta) {
                //         var address = full.address != null ? full.address : '';
                //         var apt_address = full.apt_address == null ? '' : full.apt_address;
                //         var cn_name = full.cmp_country == null ? '' : full.cmp_country;
                //         var st_name = full.cmp_state == null ? '' : ',' + full.cmp_state;
                //         var ct_name = full.cmp_city == null ? '' : full.cmp_city;
                //         var zip = full.cmp_zip == null ? '' : ',' + full.cmp_zip;

                //         return  `<span>`+ address + `` + apt_address + `<br>` + ct_name + ` ` + st_name + ` ` + zip + `<br>` + cn_name + `</span>`
                //     }
                // },
                {
                    render: function(data, type, full, meta) {
                        return (moment(full.created_at).format(system_date_format));
                    }
                },
                // {
                //     render: function(data, type, full, meta) {
                //         return `<span class="badge text-capitalize badge-light-success badge-pill"> active </span>`;
                //     }
                // },
                {
                    render: function(data, type, full, meta) {
                        return `
                            <div class="dropdown ms-50">
                                <div role="button" class="dropdown-toggle hide-arrow" id="email_more" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-medium-2"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </div>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="email_more">
                                    <a href="company-profile/` + (full.id != null ? full.id : '-') + `" class="user_name text-truncate"> <div class="dropdown-item" ><svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text me-50"><path data-v-32017d0f="" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline data-v-32017d0f="" points="14 2 14 8 20 8"></polyline><line data-v-32017d0f="" x1="16" y1="13" x2="8" y2="13"></line><line data-v-32017d0f="" x1="16" y1="17" x2="8" y2="17"></line><polyline data-v-32017d0f="" points="10 9 9 9 8 9"></polyline></svg>Details</div></a>
                                   `+ (full.is_default == 1 ? '' : '<div class="dropdown-item" onclick="showdeleteModal(` + full.id + `)"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>Delete</div>') +`

                                </div>
                            </div>
                            `;
                    }
                },

            ],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                '<"col-sm-12 col-md-4 col-lg-4" l>' +
                '<"col-sm-12 col-md-8 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end align-items-center flex-sm-nowrap flex-wrap me-1"<"me-1"f>B>>' +
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
                extend: 'collection',
                className: 'btn btn-outline-secondary dropdown-toggle me-2',
                text: feather.icons['external-link'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
                buttons: [
                    {
                    extend: 'csv',
                    text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + 'Csv',
                    className: 'dropdown-item',
                    exportOptions: { columns: [1, 2, 3, 4] }
                    },
                    {
                    extend: 'excel',
                    text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
                    className: 'dropdown-item',
                    exportOptions: { columns: [1, 2, 3, 4] }
                    },
                    {
                    extend: 'copy',
                    text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + 'Copy',
                    className: 'dropdown-item',
                    exportOptions: { columns: [1, 2, 3, 4] }
                    }
                ],
                init: function (api, node, config) {
                    $(node).removeClass('btn-secondary');
                    $(node).parent().removeClass('btn-group');
                    setTimeout(function () {
                    $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex mt-50');
                    }, 50);
                }
                },
                {
                text: 'Add New Customer',
                className: 'add-new btn btn-primary',
                attr: {
                    'data-bs-toggle': 'modal',
                    'data-bs-target': '#company_model'
                },
                init: function (api, node, config) {
                    $(node).removeClass('btn-secondary');
                }
                }
            ],
            responsive: {
                details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) {
                    var data = row.data();
                    return 'Details  '  ;
                    }
                }),
                type: 'column',
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                    return col.columnIndex !== 6 // ? Do not show row in modal popup if title is blank (for check box)
                        ? '<tr data-dt-row="' +
                            col.rowIdx +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td>' +
                            col.title +
                            ':' +
                            '</td> ' +
                            '<td>' +
                            col.data +
                            '</td>' +
                            '</tr>'
                        : '';
                    }).join('');
                    return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
                }
                }
            },
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
// function get_all_companies() {
//     $.ajax({
//         type: "GET",
//         url: "get_company_lookup",
//         dataType: 'json',
//         beforeSend: function(data) {
//             $('.loader_container').show();
//         },
//         success: function(data) {
//             console.log(data, "data");
//             var system_date_format = data.date_format;
//             var row = ``;
//             var count = 1;
//             data = data.companies;
//             for (var i = 0; i < data.length; i++) {
//                 console.log(data[i].created_at)
//                 var created_at = data[i].created_at != null ? moment(data[i].created_at).format('MMMM Do YYYY, h:mm:ss a') : '-';


//                 var address = data[i].address != null ? data[i].address : '';
//                 var apt_address = data[i].apt_address != null ? ',' + data[i].apt_address : '';

//                 var cn_name = data[i].cmp_country == null ? '' : data[i].cmp_country;
//                 var st_name = data[i].cmp_state == null ? '' : ',' + data[i].cmp_state;
//                 var ct_name = data[i].cmp_city == null ? '' : data[i].cmp_city;
//                 var zip = data[i].cmp_zip == null ? '' : ',' + data[i].cmp_zip;

//                 row += `
//                     <tr id="row_` + data[i].id + `" >
//                         <td>
//                             <div class="custom-control custom-checkbox">
//                                 <input type="checkbox" class="custom-control-input" id="customCheck_` + data[i].id + `">
//                                 <label class="custom-control-label" for="customCheck_` + data[i].id + `"></label>
//                             </div>
//                         </td>
//                         <!--<td>` + count + `</td>-->
//                         <td><a href="company-profile/` + (data[i].id != null ? data[i].id : '-') + `">` + (data[i].name != null ? data[i].name : '-') + `</a></td>
//                         <td>` + (data[i].poc_first_name != null ? data[i].poc_first_name : '-') + `</td>
//                         <td>` + (data[i].poc_last_name != null ? data[i].poc_last_name : '-') + `</td>
//                         <td>` + (data[i].email != null ? data[i].email : '-') + `</td>
//                         <td><a href="tel:` + (data[i].phone != null ? data[i].phone : '-') + `">` + (data[i].phone != null ? data[i].phone : '-') + `</a></td>
//                         <td>` + address + `` + apt_address + `<br>` + ct_name + ` ` + st_name + ` ` + zip + `<br>` + cn_name + `</td>

//                         <td>` + moment(data[i].created_at).format(system_date_format) + `</td>
//                         <td>
//                             <button type="button" onclick="showdeleteModal(` + data[i].id + `)" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
//                                 <i class="fa fa-trash" aria-hidden="true"></i>
//                             </button>
//                         </td>
//                         </tr>
//                 `;
//                 count++;
//             }


//             $('#companyTBody').html(row);
//             var company_table = $('#companyTable').DataTable();

//             // $('#select_column').multipleSelect({
//             //     width: 300,
//             //     onClick: function(view) {
//             //         var selectedItems = $('#select_column').multipleSelect("getSelects");
//             //         for (var i = 0; i < 11; i++) {
//             //             columns = company_table.column(i).visible(0);
//             //         }
//             //         for (var i = 0; i < selectedItems.length; i++) {
//             //             var s = selectedItems[i];
//             //             company_table.column(s).visible(1);
//             //         }

//             //     },
//             //     onCheckAll: function() {
//             //         for (var i = 0; i < 11; i++) {
//             //             columns = company_table.column(i).visible(1);
//             //         }
//             //     },
//             //     onUncheckAll: function() {
//             //         for (var i = 0; i < 11; i++) {
//             //             columns = company_table.column(i).visible(0);
//             //         }

//             //     }
//             // });
//         },
//         complete: function(data) {
//             $('.loader_container').hide();
//         },
//         error: function(e) {
//             console.log(e)
//         }
//     });
// }

function checkEmptyFields(input, err) {
    if (input == '') {
        err.html("This field is required");
        return false;
    } else {
        err.html("");
        return true;
    }
}

// function checkValidEmail(input, err) {
//     var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

//     if (input == '') {
//         err.html("This field is required");
//         return false;
//     } else if (!pattern.test(input)) {
//         err.html("please provide valid email");
//         return false;
//     } else {
//         err.html("");
//         return true;
//     }
// }


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
                alertNotification('success', 'Success' ,data.message );
                get_all_companies();
                $("#delete_company_model").modal('hide');
            } else if (data.status_code == 500 && data.success == false) {
                alertNotification('error', 'Error' ,data.message );
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
function copyToClipBoard(text) {

    let $input = $("<input>");
    $('body').append($input);

    $input.val(text).select();
    document.execCommand('copy');
    $input.remove();

    toastr.success('Phone Number Copied!', { timeOut: 5000 });
}
</script>

@include('js_files.statesJs')
