@extends('layouts.staff-master-layout')

@section('body-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"/>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <h3 class="page-title">Dashboard</h3>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('product-manager')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <input type="hidden" id="template_id" value="{{$id}}">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>
                        Add Product 
                    </h4>
                    <form method="post" id="saveProduct">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="card shadow-sm">
                                    <div class="card-header">Product Fields</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Asset Title</label>
                                            <input type="text" name="asset_title" id="asset_title" class="asset_title form-control">
                                        </div>
                                        <div id="product_fields"></div>
                                        
                                    </div>
                                    <div class="loader_container" id="fields_loader" style="display:none">
                                        <div class="loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow-sm">
                                    <div class="card-header">Product Image</div>
                                    <div class="card-body">
                                        <input type="file" id="product_image" name="product_iamge" class="form-control dropify">
                                    </div>

                                    <div class="loader_container" id="img_loader" style="display:none">
                                        <div class="loader"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success btn-sm rounded mt-1">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dropify').dropify();

        product_fields();
    });

    function product_fields() {
        let template_id = $("#template_id").val();
        $.ajax({
            type: 'POST',
            url: "{{url('get-asset-templates-by-id')}}",
            data: {id:template_id},
            beforeSend:function(data) {
                $("#fields_loader").show();
                $("#img_loader").show();
            },  
            success: function(data) {
                console.log(data , "data");
                
                $("#asset_title").val(data.templates.title);

                var data = data.templates.fields;
                
                
                var fields = ``;

                for (var i = 0; i < data.length; i++) {

                    var length = data.length;

                    let placeholder = data[i].placeholder != null ? data[i].placeholder : "";

                    let required = data[i].required == 1 ? "required" : "";

                    fields += `<div class="col-md-${data[i].col_width} p-0 mt-2">
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
                                        <select class="form-control select2"  id="all_states_${data[i].id}"
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
                                            <select class="select2 form-control" id="all_countries_${data[i].id}"
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



                    if(data[i].type == "address") {
                        // states and countries call
                        getAllStatesAndCountries();
                        
                    }
                }
                $("#product_fields").html(fields);


                $('.select2').select2();



                


                // var ipv4_address = $('#ipv4');
                //     ipv4_address.inputmask({
                //         alias: "ip",
                //         greedy: false
                // });


            },
            complete:function(data) {
                $("#fields_loader").hide();
                $("#img_loader").hide();
            },  
            error: function(e) {
                console.log(e)
            }
        });
    }


    function getAllStatesAndCountries() {
        $.ajax({
            type: "GET",
            url: "{{url('get_all_statescountries')}}",
            dataType: 'json',
            success: function(data) {
                var country_obj = data.countries;
                var state_obj = data.states;

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
                    $("#all_countries_"+$(this).val()).append(root + countries);
                    $("#all_states_"+$(this).val()).append(root + states);
                });
            },
            error: function(f) {
                console.log('get assets error ', f);
            }
        });
    }
</script>
@endsection
