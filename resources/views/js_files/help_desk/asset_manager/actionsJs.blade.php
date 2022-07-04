<script type="text/javascript">
    let asset_arr = [];
    let assetFlag = false;

function get_asset_table_list() {
    $.ajax({
        type: "GET",
        url: "{{url('get-assets')}}",
        data: {
            customer_id: asset_customer_uid,
            company_id: asset_company_id,
            ticket_id: asset_ticket_id,
        },
        dataType: 'json',
        success: function(data) {
            var obj = data.assets;
            asset_arr = obj;

            $('.asset-table-list').DataTable().destroy();
            $.fn.dataTable.ext.errMode = 'none';
            var tbl = $('.asset-table-list').DataTable({
                data: obj,
                "pageLength": 50,
                "bInfo": false,
                "paging": true,
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('id', 'row__'+aData.id);
                },
                'columnDefs': [
                    {
                        'targets': 1,
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td).attr('id',rowData.id);
                        }
                    }
                ],
                columns: [
                    {
                        "render": function() {
                            return `<div class="text-center"><input type="checkbox" id="" name="" value=""></div>`;
                        }
                    },
                    {
                        "className":'details-control text-left',
                        "orderable":false,
                        "data":null,
                        "defaultContent": ''
                    },
                    {

                        "render": function(data, type, full, meta) {
                            if(full.asset_fields != null) {
                                let general_field = full.asset_fields[0]['id'];
                                let index = 'fl_'+general_field;
                                if(full.asset_record != null) {
                                    let gen_fld_record = full.asset_record[index];
                                    return `<a href="{{url('general-info')}}/` + full.id + `">` + gen_fld_record + `</a>`;
                                }else{
                                    return `-`;
                                }
                            }else{
                                return `-`;
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {

                            if (full.template != null) {
                                if (full.template.title != null) {
                                    return full.template.title;
                                } else {
                                    return '-';
                                }
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if(full.company != null) {
                                let url = `<a href="${root}/company-profile/${full.company.id}">${full.company.name}</a>`;
                                return full.company.name != null ? url : '-';
                            }else{
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if(full.customer != null) {
                                return `<a href="${root}/customer-profile/${full.customer.id}">${full.customer.first_name} ${full.customer.last_name}</a>`;
                            }else{
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                                <div class="d-flex justify-content-center">
                                    <button onclick="editAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button onclick="deleteAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`;
                        }
                    },
                ],
            });

            // Add event listener for opening and closing details
            $('.asset-table-list tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tbl.row( tr );
                var id = $(this).attr('id');

                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    row.child( showAssetDetails(id) ).show();
                    tr.addClass('shown');
                }
            });

        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });

}
function showAssetDetails(id) {

    let item = asset_arr.find(item => item.id == id);
    console.log(item , "item");
    let template_html  = ``;
    let customer_html = ``;
    let company_html = ``;
    let asset_field_html = ``;
    let asset_field_tr = ``;

    if(item != null) {

        // if(item.customer != null) {

        //     customer_html = `
        //     <div class="col-md-6 text-start rounded">
        //         <div class="bg-light p-2 rounded">
        //             <h4 class="fw-bolder"> Customer Detail </h4>
        //             <hr>
        //             <div>
        //                 <table class="table table -hover table-hover">
        //                     <tbody>
        //                         <tr>
        //                             <td class="fw-bolder"> Name </td>
        //                             <td> ${item.customer.first_name != null ? item.customer.first_name : '---' } ${item.customer.last_name != null ? item.customer.last_name : '---' }  ${item.customer.first_name != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.customer.first_name+'`)" style="float:right"></i>': ''}</td>
        //                         </tr>
        //                         <tr>
        //                             <td class="fw-bolder"> Email </td>
        //                             <td> ${item.customer.email != null ? item.customer.email : '---' }  ${item.customer.email != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.customer.email+'`)" style="float:right"></i>': ''}</td>
        //                         </tr>
        //                         <tr>
        //                             <td class="fw-bolder"> Phone </td>
        //                             <td> ${item.customer.phone != null ? item.customer.phone : '---' } ${item.customer.phone!= null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.customer.phone+'`)" style="float:right"></i>': ''}</td>
        //                         </tr>
        //                     </tbody>
        //                 </table>
        //             </div>
        //         </div>
        //     </div>`;
        // }

        // if(item.company != null) {

        //     company_html = `
        //     <div class="col-md-6 text-start rounded">
        //         <div class="bg-light p-2 rounded">

        //             <div class="d-flex justify-content-between">
        //                 <h4 class="fw-bolder"> Company Detail </h4>
        //                 <span class="small text-success fw-bolder url_copy_${item.id}"></span>
        //             </div>
        //             <hr>
        //             <div>
        //                 <table class="table table -hover table-hover">
        //                     <tbody>
        //                         <tr>
        //                             <td class="fw-bolder"> Name </td>
        //                             <td> ${item.company.name != null ? item.company.name : '---' } ${item.company.name != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.company.name+'`)" style="float:right"></i>': ''}</td>
        //                         </tr>
        //                         <tr>
        //                             <td class="fw-bolder"> Email </td>
        //                             <td> ${item.company.email != null ? item.company.email : '---'}  ${item.company.email != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.company.email+'`)" style="float:right"></i>': ''}</td>
        //                         </tr>
        //                         <tr>
        //                             <td class="fw-bolder"> Phone </td>
        //                             <td> ${item.company.phone != null ? item.company.phone : '---'} ${item.company.phone != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.company.phone+'`)" style="float:right"></i>': ''}</td>
        //                         </tr>
        //                     </tbody>
        //                 </table>
        //             </div>
        //         </div>
        //     </div>`;

        // }

        if(item.asset_fields != null) {

            for( let data of item.asset_fields) {

                if(item.asset_record) {
                    let custom_key = 'fl_' + data.id;
                    let key = item.asset_record[custom_key];


                    asset_field_tr += `
                        <tr>
                            <td class="fw-bolder" width="170"> ${data.label} </td>
                            <td width="300px">${key != null ? key : '-'}</td>
                            <td>${key != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+key+'`)" style="float:left"></i>': ''}</td>
                        </tr>`;

                }
            }

            asset_field_html += `
                <div class="col-md-12 text-start rounded">
                    <div class="bg-light p-2 rounded">

                        <div>
                            <table class="table table -hover table-hover">
                                <tbody>

                                    <tr>
                                        <td class="fw-bolder" width="170"> Asset Type </td>
                                        <td width="300px">${item.template.title != null ? item.template.title : '---'} </td>
                                        <td>${item.template.title != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.template.title+'`)" style="float:left"></i>': ''}</td>
                                    </tr>
                                    ${asset_field_tr}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>`;
        }


    }

    return `
        <div class="row">
            ${item.asset_fields != null ? asset_field_html : ''}
        </div>
        <div class="row p-1">
            ${template_html}
            ${customer_html}
            ${company_html}
        </div>
        `;
}

function copyToClipBoard(text) {

    let $input = $("<input>");
    $('body').append($input);

    $input.val(text).select();
    document.execCommand('copy');
    $input.remove();

    alertNotification('success', 'Success' , 'Text copied' );
}

function get_asset_temp_table_list() {
    assets_temp_table_list.clear().draw();

    $.ajax({
        type: "get",
        url: "{{asset('/get-assets')}}",
        data: {
            customer_id: asset_customer_uid,
            company_id: asset_company_id,
        },
        dataType: 'json',
        success: function(data) {
            console.log('assets', data.assets);

            var vendor_arr = data.assets;
            $("#asset-temp-table-list tbody").html("");

            var count = 1;
            $.each(vendor_arr, function(key, val) {


                let template = val.asset_forms_id;

                if (templates.length) {
                    template = templates.filter(itm => itm.id == template);
                    if (template.length) {
                        template = template[0].title;
                    }
                }

                assets_temp_table_list.row.add([

                    '<div class="text-center"><input type="checkbox" class="assets" name="assets" value=' + val['id'] + ' id=' + val['id'] + '></div>',
                    val['id'],
                    template,
                    '<a style="cursor:pointer;text-align:center" title="Edit Type" onclick="event.stopPropagation();editAsset(' +
                    val['id'] + ',`' + val['image'] + '`,`' + val['name'] + '`,`' + val[
                        'description'] + '`,`' + val['companies_assign_to'] + '`,`' +
                    val['customers_assign_to'] + '`,`' + val['categories'] + '`,`' +
                    val['model'] +
                    '`);return false;"><i class="mdi mdi-grease-pencil" aria-hidden="true"></i></a>&nbsp;<a style="cursor:pointer;text-align:center" title="Delete Asset" onclick="event.stopPropagation();deleteAsset(' +
                    val['id'] +
                    ');return false;"><i class="fa fa-trash " aria-hidden="true"></i></a>'

                ]).draw(false);
                count++;
            });

        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });
}

$("#pass_icon").on('click', function() {
    $(this).toggleClass("fa-eye fa-eye-slash");

    var input = $("input[name='password']");
    if (input.attr("type") === "password") {
        input.attr("type", "text");
        $(".show_password").css("display", "block");
        $(".star_password").css("display", "none");
    } else {
        input.attr("type", "password");
        $(".show_password").css("display", "none");
        $(".star_password").css("display", "block");
    }

})

function editAsset(id) {

    console.log(id , 'id');
    $("#update_asset_modal").modal('show');

    $.ajax({
        url: "{{url('show-single-assets')}}",
        type: 'POST',
        data: { id: id },
        dataType: 'JSON',
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            console.log(data, "data");

            if(data.AssetForm != null) {
                $("#modal-title").text(data.AssetForm.title);
            }

            if(data.asset != null) {
                $("#up_asset_title").val(data.asset.asset_title);
                $("#asset_title_id").val(data.asset.id);

                if(data.asset.company_id != null) {
                    $('.asset_company_id').val(data.asset.company_id).trigger('change');
                }else{
                    $('.asset_company_id').val("").trigger('change');
                }

                if(data.asset.customer_id != null) {
                    $('.asset_customer_id').val(data.asset.customer_id).trigger('change');
                }else{
                    $('.asset_customer_id').val("").trigger('change');
                }

            }


            var html_input = ``;
            var add_html = ``;

            for (var i = 0; i < data.AssetFields.length; i++) {

                var required = `<span class="text-danger">*</span>`;
                var end_point = 'fl_' + data.AssetFields[i].id;


                if (data.AssetFields[i].type == "address") {

                    var full_address = data.asset_record[end_point];
                    var split_address = full_address.split("|");

                    add_html += `
                        <div id="all_input_" class="all_input mt-4">
                        <input type='hidden' id="field_id" class="form-control mt-2 fields_id" value="` + data.AssetFields[i].id + `"/>
                        <label class="mt-2">` + data.AssetFields[i].label + `</label>  ` + (data.AssetFields[i].required == 1 ? required : '') + `

                        <input type='text' value="` + split_address[0] + `" id="address_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        <input type='text' value="` + split_address[1] + `" id="aprt_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        <input type='text' value="` + split_address[2] + `" id="city_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>

                        <select class="select2 form-control mt-2" id="state_` + data.AssetFields[i].id + `" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `>
                        </select>

                        <input type='text' value="` + split_address[4] + `" id="zipcode_` + data.AssetFields[i].id + `" class="form-control mt-2" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>

                        <select class="select2 form-control mt-2" id="country_` + data.AssetFields[i].id + `" ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `>
                        </select>
                    </div>
                    `;

                    showStatesAndCountry(data.AssetFields[i].id, split_address[5], split_address[3]);


                } else {

                    var password = `<span style="position:absolute;top:40px;right:10px" toggle="#password-field" id="pass_icon" class="fa fa-fw fa-eye mr-2 show-password-btn pass_icon"></span>`;
                    html_input += `
                    <input type='hidden' id="field_id" class="form-control mt-2 field_id" value="` + data.AssetFields[i].id + `"/>
                    <div class="form-group" style="position:relative">
                        <label>` + data.AssetFields[i].label + `</label>  ` + (data.AssetFields[i].required == 1 ? required : '') + `
                        <input type="` + data.AssetFields[i].type + `" value="` + data.asset_record[end_point] + `" id="input_` + data.AssetFields[i].id + `" class="form-control input_` + data.AssetFields[i].id + `" placeholder="` + data.AssetFields[i].placeholder + `"  ` + (data.AssetFields[i].required == 1 ? 'required' : '') + `/>
                        ` + (data.AssetFields[i].type == "password" ? password : '') + `
                    </div>
                `;

                }

            }

            $(".input_fields").html(html_input);
            $(".address_fields").html(add_html);

        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e);
        }

    });



}

function showStatesAndCountry(id, country_id, state_id) {
    $.ajax({
        type: "GET",
        url: "{{asset('get_all_statescountries')}}",
        dataType: 'json',
        success: function(data) {
            console.log(data, "state and countries");
            var country_obj = data.countries;
            var state_obj = data.states;

            var countries = ``;
            var states = ``;
            var root = `<option>Select</option>`;
            for (var i = 0; i < country_obj.length; i++) {
                countries += `<option value="` + country_obj[i].id + `" ` + (country_obj[i].id == country_id ? 'selected' : '-') + `>` + country_obj[i].name + `</option>`;
            }

            for (var i = 0; i < state_obj.length; i++) {
                states += `<option value="` + state_obj[i].id + `" ` + (state_obj[i].id == state_id ? 'selected' : '-') + `>` + state_obj[i].name + `</option>`;
            }

            $("#country_" + id).append(root + countries);
            $("#state_" + id).append(root + states);
        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });
}

function updateAssets() {

    var asset_id = $("#asset_title_id").val();
    var form_data = {};
    var asset_data = [];

    var complete_address = [];

    var asset_title = $("#up_asset_title").val()
    let asset_customer_id = $(".asset_customer_id").val()
    let asset_company_id = $(".asset_company_id").val()

    $(".fields_id").each(function() {
        var field_id = $(this).val();

        var add = $("#address_" + field_id).val();
        var aprt = $("#aprt_" + field_id).val();
        var city = $("#city_" + field_id).val();
        var state = $("#state_" + field_id).val();
        var zipcode = $("#zipcode_" + field_id).val();
        var country = $("#country_" + field_id).val();

        comp_address = add + "|" + aprt + "|" + city + "|" + state + "|" + zipcode + "|" + country;

        var colname = { "keys": "fl_" + field_id };
        var value = { "value": comp_address };
        asset_data.push($.extend(true, {}, colname, value));
    })

    $(".field_id").each(function() {

        var field_id = $(this).val();
        var colname = { "keys": "fl_" + field_id };
        var value = { "value": $("#input_" + field_id).val() };
        asset_data.push($.extend(true, {}, colname, value));


    });

    form_data = {
        asset_id: asset_id,
        asset_title: asset_title,
        data: asset_data,
        complete_address: complete_address,
        asset_customer_id : asset_customer_id,
        asset_company_id : asset_company_id,
    }

    $.ajax({
        type: "POST",
        url: "{{url('update-assets')}}",
        dataType: 'json',
        data: form_data,
        success: function(data) {
            console.log(data, "asset updated");
            if (data.status_code == 200 && data.success == true) {
                alertNotification('success', 'Success' , data.message );
                $("#update_asset_modal").modal('hide');
                get_asset_table_list();
                // location.reload();

                if (asset_ticket_id) ticket_notify('ticket_update', 'T_asset_update');
            } else {
                alertNotification('error', 'Error' , data.message);
            }
        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });
}

function deleteAsset(id) {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this asset will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "{{url('delete-asset')}}",
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Asset Deleted!',
                            showConfirmButton: false,
                            timer: 2500
                        })

                        get_asset_table_list();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 2500
                        })

                    }
                }
            });
        }
    })
}

function getFormsTemplates() {
    $.ajax({
        type: 'get',
        url: "{{asset('/get-asset-templates')}}",
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            if (data.success == true) {
                templates = data.templates;
                asset_type_arr = data.data;
                let opts = '<option value="">Select</option>';
                for (let i in templates) {
                    opts += `<option value="${templates[i].id}">${templates[i].title}</option>`;
                }
                $("#form_id").html(opts);
            }

        },
        error: function(e) {
            console.log(e)
        }
    });
}

function getFields(id) {

    $("#templateTitle").css("display", "block");

    if (!id) {
        $("#form-fields").html("");
        $("#templateTitle").css("display", "none");
        return;
    }
    let data = templates.filter(itm => itm.id == id);
    data = data[0].fields;
    var fields = ``;

    for (var i = 0; i < data.length; i++) {
        var length = data.length;
        let placeholder = data[i].placeholder != null ? data[i].placeholder : "";
        let required = data[i].required == 1 ? "required" : "";

        fields += `<div class="col-md-${data[i].col_width} form-group">
            <label>${data[i].label}</label> ${(data[i].required == 1 ? `<span class="text-danger">*</span>` : '')}`;


        switch(data[i].type) {
            case 'ipv4':
                fields += `<input type="${data[i].type}" id="ipv4" class="form-control" name="fl_${data[i].id}" placeholder="${placeholder}" ${required}/>`;
                break;
            case 'textbox':
                fields += `<textarea class="form-control" name="fl_${data[i].id}" placeholder="${placeholder}" ${required}></textarea>`;
                break;
            case 'selectbox':
                let opts = data[i].options.split('|');
                let multi = (data[i].is_multi) ? 'multiple' : '';
                fields += `<select class="form-control select2" name="fl_${data[i].id}" ${required} ${multi}>`
                for(let j in opts) {
                    fields += `<option value="${opts[j]}">${opts[j]}</option>`;
                }
            fields += `</select>`;
                break;
            case 'password':
                fields += `<div class="user-password-div">
                    <span class="block input-icon input-icon-right">
                        <input type="password" name="fl_${data[i].id}" placeholder="${placeholder}" class="form-control" ${required}>
                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon show-password-btn mr-2"></span>
                    </span>
                </div>`;
                break;
            case 'address':
                fields += `<div class="form-row">
                        <input type="hidden" id="field_length" value="`+length+`"/>
                        <div class="col-12 form-group">
                            <label>Street Address</label> <span class="text-danger">*</span>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class=" form-control" id="fl_address_${data[i].id}" placeholder="House number and street name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class=" form-control"  id="fl_aprt_${data[i].id}" placeholder="Apartment, suit, unit etc. (optional)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label >City</label> <span class="text-danger">*</span>
                            <input type="text" class="form-control"  id="fl_city_${data[i].id}" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <label >State</label> <span class="text-danger">*</span>
                            <select class="form-control select2 selected2"  id="all_states_${data[i].id}"
                                    style="width: 100%; height:36px;" required>
                            </select>
                        </div>

                        <input type="hidden" class="form-control" data-id="fl_${data[i].id}" value="123" id="demo_address">
                        <input type="hidden" class="form-control keysss" value="${data[i].id}" id="key_id">

                        <div class="col-md-3 form-group">
                            <label >Zip Code</label> <span class="text-danger">*</span>
                            <input type="tel" maxlength="5" class="form-control" id="fl_zip_code_${data[i].id}" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <div class="form-group">
                                <label>Country</label> <span class="text-danger">*</span>
                                <select class="select2 selected2 form-control" id="all_countries_${data[i].id}"
                                        style="width: 100%; height:36px;" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    `;
                break;
            default:
                fields += `<input type="${data[i].type}" class="form-control" name="fl_${data[i].id}" placeholder="${placeholder}" ${required}/>`;
        }
        fields += `</div>`;
    }
    $("#form-fields").html(fields);

    $('.selected2').select2();





    // states and countries call
    $.ajax({
        type: "GET",
        url: "{{asset('get_all_statescountries')}}",
        dataType: 'json',
        success: function(data) {
            var country_obj = data.countries;
            var state_obj = data.states;
            console.log(country_obj , "country_obj")
            console.log(state_obj , "state_obj")

            var countries = ``;
            var states = ``;
            var root = `<option>Select</option>`;
            for(var i =0 ; i < country_obj.length; i++) {
                countries += `<option value="`+country_obj[i].id+`">`+country_obj[i].name+`</option>`;
            }

            for(var i =0 ; i < state_obj.length; i++) {
                states += `<option value="`+state_obj[i].id+`">`+state_obj[i].name+`</option>`;
            }

            $('.keysss').each(function(){
                // alert($(this).val());
                $("#all_countries_"+$(this).val()).append(root + countries);
                $("#all_states_"+$(this).val()).append(root + states);
             });
        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });


    var ipv4_address = $('#ipv4');
        ipv4_address.inputmask({
            alias: "ip",
            greedy: false
    });
}

$("#save_asset_form").submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');


    formData.append('project_id', asset_project_id);
    formData.append('ticket_id', asset_ticket_id);

    let url = window.location.href;

    if(url.includes('asset-manager')) {


        if($("#company_id").val() == '' || $("#customer_id").val() == "") {
            alertNotification('error', 'Error' , 'Company or Customer field is required');
            return false;
        }

        formData.append('customer_id', $("#customer_id").val() );
        formData.append('company_id',  $("#company_id").val() );

    }else{

        if(asset_customer_uid == null || asset_customer_uid == "") {
            asset_customer_uid = $(".tkt_customer_id").val();
        }

        if(asset_company_id == null || asset_company_id == "") {
            asset_company_id = $(".tkt_company_id").val();
        }

        formData.append('customer_id', asset_customer_uid);
        formData.append('company_id', asset_company_id);
    }

    let demo_address = $("#demo_address").val();

    if(demo_address == 123) {

        $('.keysss').each(function(){

            var fl_address = $("#fl_address_" + $(this).val()).val();
            var fl_aprt = $("#fl_aprt_"+ $(this).val()).val();
            var fl_city = $("#fl_city_"+ $(this).val()).val();
            var all_states = $("#all_states_" + $(this).val()).val();
            var fl_zip_code = $("#fl_zip_code_"+ $(this).val()).val();
            var all_countries = $("#all_countries_" + $(this).val()).val();

            var value = fl_address + "|" + fl_aprt + "|" + fl_city + "|" + all_states + "|" + fl_zip_code + "|" + all_countries ;

            formData.append("fl_"+$(this).val(), value);
         });


    }else{
        console.log(formData , "formData");
    }

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
            if (data.success == true) {
                $("#save_asset_form").trigger("reset");
                $("#form-fields").html("");
                $('#asset').modal('hide');

                if(typeof assets_table_list != "undefined") get_asset_table_list();

                if(asset_ticket_id) ticket_notify('ticket_update', 'T_asset_add');

                $("#templateTitle").removeAttr('style');
                $("#templateTitle").attr('style','display:none !important');
            }
            Swal.fire({
                position: 'top-end',
                icon: (data.success) ? 'success' : 'error',
                title: data['message'],
                showConfirmButton: false,
                timer: 2500
            });
        }
    });
});

function getAssetDetails(id=1) {
    $.ajax({
        type: 'get',
        url: get_asset_details_route+'/'+id,
        success: function (data) {

        }
    });
}

// function editAsset(id, image, name, description, companies_assign_to, customers_assign_to, categories, model) {

//     var cat_names = categories.split(',');
//     var cust_names = customers_assign_to.split(',');
//     //var com_names = companies_assign_to.split(',');


//     if (cat_names.length > 0 && cat_names != '') {

//         var cat_count = 0;

//         $("#categories").empty();

//         for (var m = 0; m < categoriesArr.length; m++) {

//             $("#categories").append("<option value='" + categoriesArr[m]['id'] + "'>" + categoriesArr[m]['name'] +
//                 "</option>");
//             $('#categories').trigger('change');
//         }

//         for (var k = 0; k < categoriesArr.length; k++) {
//             for (var l = 0; l < cat_names.length; l++) {

//                 if (categoriesArr[k]['id'] == cat_names[l]) {

//                     $('#categories option[value=' + categoriesArr[k]['id'] + ']').attr('selected', 'selected');
//                     $('#categories').trigger('change');
//                     break;

//                 }
//             }
//         }
//     } else {
//         cat_names = '---';
//     }

//     if (cust_names.length > 0 && cust_names != '') {

//         var cust_count = 0;

//         $("#customers_assign_to").empty();

//         for (var m = 0; m < customersArr.length; m++) {


//             $("#customers_assign_to").append("<option value='" + customersArr[m]['id'] + "'>" + customersArr[m][
//                     'name'
//                 ] +
//                 "</option>");
//             $('#customers_assign_to').trigger('change');
//         }

//         for (var k = 0; k < customersArr.length; k++) {
//             for (var l = 0; l < cust_names.length; l++) {

//                 if (customersArr[k]['id'] == cust_names[l]) {
//                     console.log(customersArr[k]['id']);
//                     $('#customers_assign_to option[value=' + customersArr[k]['id'] + ']').attr('selected',
//                         'selected');
//                     $('#customers_assign_to').trigger('change');
//                     break;

//                 }
//             }
//         }
//     } else {
//         cust_names = '---';
//     }
//     asset_id
//     $('#asset_id').val(id);
//     $('#name').val(name);
//     $('#image').text(image);
//     $('#description').val(description);
//     $('#model').val(model);


//     var label = $("#edit-asset").text();
//     if (label == 'Save Asset') {
//         $("#edit-asset").html('Edit Asset');
//     }
//     $('#asset').modal('show');

// }



// $("#asset_customer").change(function(){

//     if($('#asset_customer :selected').text() == 'All'){
//         console.log("chose customer")
//         // let option_customer = [];
//         // for (const data of customers) {
//         //     option_customer += `<option value="${data.id}"> ${data.first_name} ${data.last_name}  </option>`;
//         // }
//         // $('#asset_customer').empty();
//         // $("#asset_customer").html(option_customer);
//         // $('#asset_customer').trigger('change');

//         let option_company = [];
//         for (const data of companies) {
//             option_company += `<option value="${data.id}"> ${data.name} </option>`;
//         }

//         console.log(option_company)
//         // $('#asset_company').empty();
//         $("#asset_company").html(option_company);
//         $('#asset_company').trigger('change');

//     }
// });

// $("#asset_company").change(function(){

//     if($('#asset_company :selected').text() == 'All'){
//         let option_customer = [];
//         for (const data of customers) {
//             option_customer += `<option value="${data.id}"> ${data.first_name} ${data.last_name}  </option>`;
//         }
//         $('#asset_customer').empty();
//         $("#asset_customer").html(option_customer);
//         $('#asset_customer').trigger('change');

//         let option_company = [];
//         for (const data of companies) {
//             option_company += `<option value="${data.id}"> ${data.name} </option>`;
//         }
//         $('#asset_company').empty();
//         $("#asset_company").html(option_company);
//         $('#asset_company').trigger('change');

//     }
// });


function selectCustomer(value , customerId , companyId) {

        let root = `<option>N/A</option>`;
        if (value != '') {

            if($('#asset_customer :selected').text() == 'All'){

                let option_company = [];
                for (const data of companies) {
                    option_company += `<option value="${data.id}"> ${data.name} </option>`;
                }

                $('#asset_company').empty();
                $('#asset_company').html(`<option selected>Choose</option>`+option_company);
                $('#asset_company').trigger('change');


                let option_customer = [];
                for (const data of customers) {
                    option_customer += `<option value="${data.id}"> ${data.first_name} ${data.last_name}  </option>`;
                }
                $('#asset_customer').empty();
                $("#asset_customer").html(`<option selected>Choose</option>`+option_customer);


            }else{
                $("#"+companyId).empty();
                let item = customers.find(item => item.id == value);
                if(item != null) {
                    if(item.company_id != null) {
                        if(assetFlag) {
                            $("#"+companyId).empty();
                            let option = `<option value="${item.company_id}" selected> ${item.company_name} </option>`;
                            $("#"+companyId).html(root+option);
                        }else{
                            let option = `<option value="${item.company_id}" selected> ${item.company_name} </option>`;
                            $("#"+companyId).html(root+option);
                        }
                    }
                }
            }


        } else {
            assetFlag = false;
            $('#'+customerId).empty();
            let option = ``;
            for (let [i, data] of customers.entries()) {
                option += `<option value="${data.id}" ${i == 0 ? 'selected' : ''}> ${data.first_name} ${data.last_name} </option>`;
            }
            $("#"+customerId).html(option);
        }
    }


    function selectCompany(value , customerId , companyId) {
        let root = `<option>All</option><option selected>N/A</option>`;

        if(value != '') {
            assetFlag = true;

            if($('#asset_company :selected').text() == 'N/A'){
                let option_company = [];
                for (const data of companies) {
                    option_company += `<option value="${data.id}"> ${data.name} </option>`;
                }

                $('#asset_company').empty();
                $('#asset_company').html(`<option selected>Choose</option>`+option_company);
                $('#asset_company').trigger('change');


                let option_customer = [];
                for (const data of customers) {
                    option_customer += `<option value="${data.id}"> ${data.first_name} ${data.last_name}  </option>`;
                }
                $('#asset_customer').empty();
                $("#asset_customer").html(`<option selected>Choose</option>`+option_customer);

            }


            let custs = customers.filter(item => item.company_id == value);
            if(custs.length > 0) {
                let option = ``;
                for (let [i, data] of custs.entries()) {
                    option += `<option value="${data.id}"> ${data.first_name} ${data.last_name} </option>`;
                }

                if($('#'+customerId+' :selected').text() == 'All'){
                    let option_company = '';
                    for (const [i, data] of companies.entries()) {
                            option_company += `<option value="${data.company_id}"> ${data.company_name} </option>`;
                    }

                    $("#"+companyId).html(option_company);
                }


                $('#'+customerId).empty();
                $("#"+customerId).html(root + option);
            }else{
                if($('#'+customerId+' :selected').text() != 'Choose'){
                    $('#'+customerId).empty();
                }
            }

        }else{
            assetFlag = false;
            $('#'+companyId).empty();
            let option = ``;
            for (let data of companies) {
                option += `<option value="${data.id}"> ${data.name} </option>`;
            }
            $("#"+companyId).html(root + option);
        }
    }

</script>
