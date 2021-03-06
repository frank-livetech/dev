$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
});
// const { each } = require("jquery");
// const { forEach } = require("lodash");




function get_asset_table_list() {
    assets_table_list.clear().draw();

    $.ajax({
        type: "get",
        url: get_assets_route,
        data: {
            customer_id: asset_customer_uid,
            company_id: asset_company_id,
            ticket_id: asset_ticket_id,
        },
        dataType: 'json',
        success: function(data) {
            // console.log(data , "data this");
            console.log(data.assets, 'assets');
            var obj = data.assets;

            $('#asset-table-list').DataTable().destroy();
            $.fn.dataTable.ext.errMode = 'none';
            var tbl = $('#asset-table-list').DataTable({
                data: obj,
                "pageLength": 50,
                "bInfo": false,
                "paging": true,
                columns: [{
                        "render": function() {
                            return `<div class="text-center"><input type="checkbox" id="" name="" value=""></div>`;
                        }
                    },
                    {
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `<a href="` + general_info_route + '/' + full.id + `">` + full.asset_title + `</a>`;
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
                            return `
                        <button title="Edit Type" class="btn btn-success btn-circle" onclick="editAsset(` + full.id + `);">
                            <i class="mdi mdi-grease-pencil" aria-hidden="true"></i>
                        </button>
                    
                        <button class="btn btn-danger btn-circle" title="Delete Asset" onclick="deleteAsset(` + full.id + `);">
                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        </button>
                        `;
                        }
                    },
                ],
            });

            tbl.on('order.dt search.dt', function() {
                tbl.column(1, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();


            // var vendor_arr = data.assets;
            // $("#asset-table-list tbody").html("");


            // var count = 1;
            // $.each(vendor_arr, function(key, val) {

            //     let template_title = '';

            //     if(val.template.name != null) {
            //         template_title = val.template.name;
            //     }else{
            //         template_title = '-';
            //     }


            //     let customers = '';
            //     let companies = '';
            //     let projects = '';
            //     let monitor = '';
            //     if (val.customer_id) customers = val.customer_id;
            //     if (val.company_id) companies = val.company_id;
            //     if (val.project_id) projects = val.project_id;
            //     let name = val.asset_forms_id;
            //     let template = val.asset_forms_id;

            //     if (templates.length) {
            //         template = templates.filter(itm => itm.id == template);
            //         if (template.length) {
            //             template = template[0].title;
            //         }
            //     }

            //     assets_table_list.row.add([

            //         '<div class="text-center"><input type="checkbox" class="assets" name="assets" value=' + val['id'] + ' id=' + val['id'] + '></div>',
            //         val['id'],
            //         '<a href="' + general_info_route + '/' + val['id'] + '">' + val['asset_title'] + '</a>',
            //         template_title,
            //         '-',
            //         '-',
            //         '-',
            //         '-',
            //         '<button title="Edit Type" class="btn btn-success btn-circle" onclick="event.stopPropagation();editAsset(' + val['id'] + ');"><i class="mdi mdi-grease-pencil" aria-hidden="true"></i></button>&nbsp;<button class="btn btn-danger btn-circle" title="Delete Asset" onclick="event.stopPropagation();deleteAsset(' +
            //         val['id'] +
            //         ');return false;"><i class="fa fa-trash " aria-hidden="true"></i></button>'

            //     ]).draw(false);
            //     count++;
            // });

        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });

}

function get_asset_temp_table_list() {
    assets_temp_table_list.clear().draw();

    $.ajax({
        type: "get",
        url: get_assets_route,
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

    $("#update_asset_modal").modal('show');

    $.ajax({
        url: show_asset,
        type: 'POST',
        data: { id: id },
        dataType: 'JSON',
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            console.log(data, "data");

            $("#modal-title").text(data.AssetForm.title);
            $("#up_asset_title").val(data.asset.asset_title);
            $("#asset_title_id").val(data.asset.id);

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

function showStatesAndCountry(id, country_id, state_id) {
    $.ajax({
        type: "GET",
        url: states_and_countries,
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
    }

    $.ajax({
        type: "POST",
        url: update_asset,
        dataType: 'json',
        data: form_data,
        success: function(data) {
            console.log(data, "asset updated");
            if (data.status_code == 200 && data.success == true) {
                toastr.success(data.message, { timeOut: 5000 });
                $("#update_asset_modal").modal('hide');
                get_asset_table_list();
                // location.reload();

                if (asset_ticket_id) ticket_notify('ticket_update', 'T_asset_update');
            } else {
                toastr.error(data.message, { timeOut: 5000 });
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
                url: del_asset_route,
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
        url: templates_fetch_route,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            if (data.success == true) {
                templates = data.templates;
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
    console.log(templates, "before");
    let data = templates.filter(itm => itm.id == id);
    console.log(data, "before");
    data = data[0].fields;
    console.log(data);

    var fields = ``;

    for (var i = 0; i < data.length; i++) {
        var length = data.length;
        console.log(data.length, "dasdasdasdasd");
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
        url: states_and_countries,
        dataType: 'json',
        success: function(data) {
            console.log(data , "states_and_countries_list");
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

    formData.append('customer_id', asset_customer_uid);
    formData.append('company_id', asset_company_id);
    formData.append('project_id', asset_project_id);
    formData.append('ticket_id', asset_ticket_id);

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