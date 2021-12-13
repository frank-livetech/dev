<script>

// invoice Maker Script Blade
let customers_list = {!! json_encode($customers) !!};
let gooogle = {!! json_encode($gooogle) !!};
var countries = {!!json_encode($countries)!!};
let customer = {!! json_encode($customer_info) !!};
let new_customer = null; // save new customer details unlike other customer var from db
let add_new_customer = false; // flag for add new customer order

var total_fees = 0;
var total_discount = 0; 
var total_tax = 0;
var total_amount = 0;

$(document).ready(function () {
    if(customer) $('#inv-acts').find('.fa-eye').parent().show();
    var customer_id = $("#customer_id").val();

    console.log(line_items , "line_items");

    var edit_order_id = $("#edit_order_id").val();

    if (edit_order_id == "" || edit_order_id == null || edit_order_id == "NULL") {
        var row = ``;
        var random_no = Math.floor(Math.random() * 2) + Date.now();

        row +=
            `<tr>
                <td>  <input name="name[]" placeholder="Item Name" type="text" class="form-control" id="item_name"/> </td>
                <td> 
                    <p class="m-0"><button type="button" class="btn btn-primary btn-sm rounded" onclick="addDetails(`+random_no+`,'add')" style="font-size:12px">add Details?</button></p>
                    <p class="m-0 mt-1">
                        <div class="checkbox checkbox-primary checkbox-circle">
                            <input id="is_sub_checkbox_`+random_no+`" onchange="isSub(`+random_no+`,'add')" type="checkbox">
                            <label class="mb-0 " for="is_sub_checkbox_`+random_no+`"> is Sub? </label>
                        </div>
                    </p>
                </td>
                <td class="tgls cost-tgl">
                    <input name="price[]" type="text" onkeyup="costPrice(`+ random_no +`)" min="1" value="0" class="form-control price" id="cost_price_` +random_no +`"/>
                </td>
                <td class="tgls qty-tgl">
                    <input name="quantity[]" type="text" onkeyup="qtyPrice(` + random_no + `)" min="1" value="0" class="form-control qty" id="qty_` + random_no +`"/>
                </td>
                <td class="tgls ttl-tgl" style="text-align: right;">
                    <p id="total_` + random_no + `" class="amt mt-2 text-right">0.00</p>
                </td>

                <tr class="add_details_tr_` + random_no + `" style="display:none">
                    <td class="bg-light" colspan="6"> <textarea name="details[]" class="form-control" col="3" rows="3" placeholder="Add Details"></textarea> </td>
                </tr>

                <tr class="is_sub_tr_` + random_no + `" style="display:none">
                    <td colspan="6" class="bg-light">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="small"> Subscription Term </label>
                                    <select name="routine[]" class="form-control" id="routine_select">
                                        <option value="">Select</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quaterly">Quaterly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="small"> Subscription Cost </label>
                                    <input name="subscription_cost[]" input="number" class="form-control" id="subs_cost_${random_no}"/>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="small"> End Date </label>
                                    <input name="end_date[]" type="date" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tr>`;

        $("#result").html(row);
    } else {

        if (line_items.length > 0) {
            var row = ``;

            for (var i = 0; i < line_items.length; i++) {
                var random_no = Math.floor(Math.random() * 2) + Date.now() + "_" + i;
                var single_row_total = (line_items[i].price * line_items[i].quantity).toFixed(2);

                row +=
                    `<tr id="row_` + random_no+`">
                        <td>
                            <input name="name[]" placeholder="Item Name"  type="text" class="form-control" id="item_name" value="`+ line_items[i].name + `"/>
                        </td>

                        <td> 
                            <p class="m-0">
                                <button type="button" class="btn btn-primary btn-sm rounded" onclick="addDetails('`+random_no+`','edit')" style="font-size:12px">add Details? </button></p>
                            <div class="checkbox checkbox-primary checkbox-circle">
                                <input id="is_sub_checkbox_`+random_no+`" onchange="isSub('`+random_no+`','edit')" name="is_sub_checkbox_`+random_no+`" type="checkbox">
                                <label class="mb-0 " for="is_sub_checkbox_`+random_no+`"> is Sub? </label>
                            </div>
                        </td>

                        <td>
                            <input name="price[]" type="text" onkeyup="costPrice(`+ line_items[i].id +`)" 
                                min="1" value="`+ line_items[i].price +`" 
                                class="form-control price" id="cost_price_` + line_items[i].id +`"/>
                        </td>

                        <td>
                            <input name="quantity[]" type="text" onkeyup="qtyPrice(` + line_items[i].id +`)" 
                                min="1" value="` +line_items[i].quantity +`" class="form-control qty" 
                                id="qty_` + line_items[i].id + `"/>
                        </td>

                        <td style="text-align: right;">
                            <p id="total_` + line_items[i].id + `" class="amt mt-2 text-right">` + single_row_total +`</p>
                        </td>

                        <td> 
                            <button type="button" role="button" title="Remove Row" class="btn btn-danger" style="border-radius:50px;padding:6px 14px;" 
                            onclick="removeRow(` + line_items[i].id +`,`+single_row_total+`,'`+random_no+`')">X</button>
                        </td>


                        <tr class="edit_details_tr_` + random_no + `" style="display:none">
                            <td class="bg-light" colspan="6"> 
                                <textarea name="details[]" id="add_details_` + random_no + `" class="form-control" col="3" rows="3" placeholder="Add Details">`+ (line_items[i].item_details != null ?line_items[i].item_details :'') +`</textarea> 
                            </td>
                        </tr>

                        <tr class="edit_is_sub_tr_` + random_no + `" style="display:none">
                            <td colspan="6" class="bg-light">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="small"> Subscription Term </label>
                                            <select name="routine[]" class="form-control" id="routine_select_` + random_no + `">
                                                <option value="" >Select</option>
                                                <option value="daily" `+ (line_items[i].routine == "daily" ? 'selected' : '') +`>Daily</option>
                                                <option value="weekly" `+ (line_items[i].routine == "weekly" ? 'selected' : '') +`>Weekly</option>
                                                <option value="monthly" `+ (line_items[i].routine == "monthly" ? 'selected' : '') +`>Monthly</option>
                                                <option value="quaterly" `+ (line_items[i].routine == "quaterly" ? 'selected' : '') +`>Quaterly</option>
                                                <option value="yearly" `+ (line_items[i].routine == "yearly" ? 'selected' : '') +`>Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="small"> Subscription Cost </label>
                                            <input name="subscription_cost[]" value="`+line_items[i].subscription_cost +`" id="subs_cost_` + random_no + `" type="number" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="small"> End Date </label>
                                            <input name="end_date[]" type="date" value="`+line_items[i].item_end_date +`" id="end_date_` + random_no + `" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tr>`;
                
            }

            var order_fees = order.fees != null && order.fees != '' && order.fees != undefined ? order.fees : 0.0;
            var order_tax = order.tax != null && order.tax != '' && order.tax != undefined ? order.tax : 0.0;
            var order_discount = order.discount != null && order.discount != '' && order.discount != undefined ? order.discount : 0.0;
            var order_total = order.total != null && order.total != '' && order.total != undefined ? order.total : 0.0;
            var order_grand_total = order.grand_total != null && order.grand_total != '' && order.grand_total != undefined ? order.grand_total : 0.0;
           

            $("#fees_show").text((order_fees).toFixed(2));
            $("#sub_total").text((order_total).toFixed(2));

            $("#full_total").text((order_grand_total).toFixed(2));
            
            $("#discount_show").text((order_discount).toFixed(2))

            $("#tax_show").text((order_tax).toFixed(2))

            $("#fees").val(order_fees);
            $("#discount").val(order_discount);
            $("#tax").val(order_tax);

            $("#sub_tot").val(order_total);
            $("#total").val(order_grand_total);
            
            $("#result").html(row);

        }
    }

    $("#addItemForm").submit(function (event) {
        event.preventDefault();

        var item_name = $("#item_name").val();
        var billing_notes = $("#notes").val();

        var custom_id = $("#custom_id").text();

        var customer_list = $("#customer_list").val();
        if (customer_list == 0) {
            toastr.error("Select Customer First", { timeOut: 5000 });
        } else if (item_name == "") {
            toastr.error("Please Add Atleast One Order Item", {
                timeOut: 5000,
            });
        } else {
            var status_text = $("#status option:selected").text();
            var form_data = new FormData(this);
            
            var invoice_url = ``;
            var edit_order_id = $("#edit_order_id").val();

            if (edit_order_id == "" || edit_order_id == null) {
                invoice_url = create_invoice;
                form_data.append("custom_id", $.trim(custom_id));
            } else {
                invoice_url = update_order + "/" + edit_order_id;
            }

            form_data.append("notes",billing_notes);
            form_data.append("status_text", status_text);

            $.ajax({
                url: invoice_url,
                type: "POST",
                data: form_data,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function (data) {
                    $("#savebtn").hide();
                    $("#processbtn").show();
                },
                success: function (data) {
                    console.log(data, "data");
                    $("#ordr_id").val(data.order_id);

                    $("#pdf_btn").attr( "href", invoice_url + "/" + data.order_id);

                    $("#pdf_btn").removeClass("disabled");
                    if ((data.status_code == 200) & (data.success == true)) {
                        toastr.success(data.message, { timeOut: 5000 });
                        $("#publishBtn").prop("disabled", false);
                        $("#savebtn").prop("disabled", true);
                        $("#add_fees").prop("disabled", true);
                        $("#add_discount").prop("disabled", true);
                        $("#add_tax").prop("disabled", true);
                        $("#payAsCustomer").removeClass("disabled");

                        $("#billing_notes").prop("disabled", false);

                        $("#payAsCustomer").attr("href", checkout +"/" + $("#customer_id").val() +"/" + edit_order_id);
                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                    }

                    $("#savebtn").show();
                    $("#processbtn").hide();
                },
                error: function (e) {
                    console.log(e);
                    $("#savebtn").show();
                    $("#processbtn").hide();
                },
            });
        }
    });

    $('#inv-cols-toggle').multipleSelect({
        width: 300,
        onClick: function(view) {
            var selectedItems = $('#inv-cols-toggle').multipleSelect("getSelects");
            toggleElements(selectedItems);
        },
        onCheckAll: function() {
            toggleElements('checkAll');
        },
        onUncheckAll: function() {
            toggleElements('uncheckAll');
        }
    });

    $('#inv-cols-toggle').multipleSelect('checkAll');

    // update customer details
    $("#update_details").submit(function (event) {
        event.preventDefault();

        if(add_new_customer) {
            new_customer = {};
            $(this).serializeArray().forEach(e => {
                new_customer[e.name] = e.value;
            });
            console.log(new_customer);

            cancelDetails()
        } else {
            let formData = new FormData(this);
            formData.append('customer_id', customer.id);

            $.ajax({
                type: "POST",
                url: "{{url('/update_customer_profile')}}",
                data: formData,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                beforeSend: function (data) {
                    $(this).find("#save").hide();
                    $(this).find("#processing").show();
                },
                success: function (data) {
                    console.log(data, "submit");
                    if (data.status_code == 200 && data.success == true) {
                        toastr.success(data.message, { timeOut: 5000 });
                        $(this).find("#save").show();
                        $(this).find("#processing").hide();

                        getCustomerDetail(customer.id, 'updated');
                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                        $(this).find("#save").show();
                        $(this).find("#processing").hide();
                    }
                },
                complete: function (data) {
                    $(this).find("#save").show();
                    $(this).find("#processing").hide();
                },
                failure: function (errMsg) {
                    $(this).find("#save").show();
                    $(this).find("#processing").hide();
                    console.log(errMsg);
                },
            });
        }
    });
    // update customer address
    $("#update_address, #upd_ship_address").submit(function (event) {
        event.preventDefault();

        var id = $("#c_id").val();

        $.ajax({
            type: "POST",
            url: update_customer_address,
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function (data) {
                $("#address_modal").fadeIn();
                $("#save").hide();
                $("#processing").show();
            },
            success: function (data) {
                console.log(data, "submit");
                if (data.status_code == 200 && data.success == true) {
                    toastr.success(data.message, { timeOut: 5000 });
                    $("#save").show();
                    $("#processing").hide();

                    if(data.type == 'billing') {
                        // $("#billingModal").modal("hide");
                        $("#billing-box").find("form").html('');
                        $("#billing-box").find(".viewonly").show();
                    }else{
                        // $("#shippingModal").modal("hide");
                        $("#shipping-box").find("form").html('');
                        $("#shipping-box").find(".viewonly").show();
                    }

                    getCustomerDetail(customer.id, 'updated');
                    cancelAddress(data.type);
                } else {
                    toastr.error(data.message, { timeOut: 5000 });
                    $("#save").show();
                    $("#processing").hide();
                }
            },
            complete: function (data) {
                $("#address_modal").fadeOut(500);
                $("#save").show();
                $("#processing").hide();
            },
            failure: function (errMsg) {
                console.log(errMsg);
            },
        });
    });
});

function isSub(random_no,mode) {

    if(mode == "add") {
        if( $("#is_sub_checkbox_"+random_no).is(':checked') ) {
        
            $(".is_sub_tr_"+random_no).show();
    
        }else{
    
            $(".is_sub_tr_"+random_no).hide();
    
        }
    }else{

        if( $("#result tr:nth-child(3)").find('edit_is_sub_tr_'+random_no) ) {
            $('.edit_is_sub_tr_'+random_no).toggle();
        }
    }
}

function addDetails(random_no,mode) {

    if(mode == "add") {
        $('.add_details_tr_'+random_no).toggle();
    }else{
        $('.edit_details_tr_'+random_no).toggle();
    }
}

function saveFees() {
    var fees_field = $("#fees_field").val();

    if (fees_field.replace(/[^0-9\.]/g, "")) {
        total_fees = parseFloat(fees_field);

        let totvalue = (total_tax + total_fees +  total_amount) - total_discount;

        $("#fees_show").text(total_fees.toFixed(2));
        $("#full_total").text(totvalue.toFixed(2));

        $("#exampleModal").modal("hide");

        $("#fees").val(total_fees);

        $("#sub_tot").val(total_amount);
        $("#total").val(totvalue);
    } else {
        $("#error").html("only numberice values allowed");
    }
}

function saveDiscount() {
    var discount_field = $("#discount_field").val();

    if (discount_field.replace(/[^0-9\.]/g, "")) {
        total_discount = parseFloat(discount_field);
        let totvalue = (total_tax + total_fees +  total_amount) - total_discount;

        $("#discount_show").text(total_discount.toFixed(2));
        $("#full_total").text(totvalue.toFixed(2));

        $("#discountModal").modal("hide");

        $("#discount").val(total_discount);
        
        $("#sub_tot").val(total_amount);
        $("#total").val(totvalue);
    } else {
        $("#discount_error").html("only numberice values allowed");
    }
}

function saveTax() {
    var tax_field = $("#tax_field").val();

    if (tax_field.replace(/[^0-9\.]/g, "")) {
        total_tax = parseFloat(tax_field);

        let totvalue = (total_tax + total_fees +  total_amount) - total_discount;

        $("#tax_show").text(total_tax.toFixed(2));
        $("#full_total").text(totvalue.toFixed(2));

        $("#taxModal").modal("hide");

        $("#tax").val(total_tax);

        $("#sub_tot").val(total_amount);
        $("#total").val(totvalue);
    } else {
        $("#tax_error").html("only numberice values allowed");
    }
}

function costPrice(random_no) {
    let qty = $("#qty_" + random_no).val();
    let cost_price = $("#cost_price_" + random_no).val();

    if (cost_price.replace(/[^0-9\.]/g, "")) {
        let row_total = cost_price * qty;
        var a = 0;

        $("#total_" + random_no).text(row_total.toFixed(2));
        $(".amt").each(function () {
            var value = $(this).text();
            a = parseFloat(a) + parseFloat(value);
            total_amount = a;


            $("#sub_total").text(a.toFixed(2));

            let fe = $('#fees_show').text();
            let dis = $('#discount_show').text();
            let taax = $('#tax_show').text();

            let grand_total = (parseFloat(a) + parseFloat(fe) + parseFloat(taax)) - parseFloat(dis)
            $("#full_total").text( (grand_total).toFixed(2) );   

            $("#sub_tot").val(total_amount);
            $("#total").val(grand_total);
        });

    } else {
        $("#cost_price_" + random_no).val(" ");
    }
    $('#subs_cost_'+random_no).val(cost_price);
}

function qtyPrice(random_no) {
    let cost_price = $("#cost_price_" + random_no).val();
    let qty = $("#qty_" + random_no).val();

    if(qty.replace(/[^0-9\.]/g, "")) {
        let row_total = qty * cost_price;
        var a = 0;

        $("#total_" + random_no).text(row_total.toFixed(2));
    
        $(".amt").each(function () {
            var value = $(this).text();
    
            a = parseFloat(a) + parseFloat(value);
            total_amount = a;
    
            let fe = $('#fees_show').text();
            let dis = $('#discount_show').text();
            let taax = $('#tax_show').text();

            let grand_total = (parseFloat(a) + parseFloat(fe) + parseFloat(taax)) - parseFloat(dis)
            $("#full_total").text( (grand_total).toFixed(2) );   

            $("#sub_total").text(a.toFixed(2));
    
            $("#sub_tot").val(total_amount);
            $("#total").val(grand_total);
        });
    }else{
        $("#qty_" + random_no).val(" ");
    }

}

function addItemRow() {
    var row = ``;
    var random_no = Math.floor(Math.random() * 2) + Date.now();

    row +=
        `
        <tr id="row_` + random_no + `">
            <td><input name="name[]" placeholder="Item Name"  type="text" class="form-control" id="item_name"/></td>
            <td> 
                <p class="m-0"><button type="button" class="btn btn-primary btn-sm rounded" onclick="addDetails(`+random_no+`,'add')" style="font-size:12px">add Details?</button></p>
                <p class="m-0 mt-1">
                    <div class="checkbox checkbox-primary checkbox-circle">
                        <input id="is_sub_checkbox_`+random_no+`" onchange="isSub(`+random_no+`,'add')" type="checkbox">
                        <label class="mb-0 " for="is_sub_checkbox_`+random_no+`"> is Sub? </label>
                    </div>
                </p>
            </td>
            <td class="tgls cost-tgl"><input name="price[]" type="text" onkeyup="costPrice(` + random_no + `)" min="1" value="0" class="form-control price" id="cost_price_` + random_no + `"/></td>
            <td class="tgls qty-tgl"><input name="quantity[]" type="text" onkeyup="qtyPrice(` + random_no + `)" min="1" value="0" class="form-control" id="qty_` + random_no + `"/></td>
            <td class="tgls ttl-tgl" style="text-align: right;"><p id="total_` + random_no + `" class="amt mt-2">0.00</p></td>
            <td> <button title="Remove Row" type="button" role="button" class="btn btn-danger" style="border-radius:50px;padding:6px 14px;" onclick="removeRow(0,0,` + random_no +`)">X</button> </td>
            
            <tr class="add_details_tr_` + random_no + `" style="display:none">
                    <td class="bg-light" colspan="6"> <textarea name="details[]" class="form-control" col="3" rows="3" placeholder="Add Details"></textarea> </td>
            </tr>

            <tr class="is_sub_tr_` + random_no + `" style="display:none">
                <td colspan="6" class="bg-light">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="small"> Subscription Term </label>
                                <select name="routine[]" class="form-control" id="routine_select">
                                    <option value="">Select</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quaterly">Quaterly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="small"> Subscription Cost </label>
                                <input name="subscription_cost[]" type="number" class="form-control" id="subs_cost_` + random_no + `"/>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="small"> End Date </label>
                                <input type="date" name="end_date[]" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

        </tr>`;

    $("#item_table").append(row);
}

function removeRow(id,row_total, random_no) {
    
    var edit_order_id = $("#edit_order_id").val();

    if (edit_order_id == "" || edit_order_id == null || edit_order_id == "NULL") {

        let fe = $('#fees_show').text();
        let dis = $('#discount_show').text();
        let taax = $('#tax_show').text();
        let sub_total = $("#sub_total").text();

        var row_tot = $("#total_" +id).text();
        
        $("#sub_total").text((parseFloat(sub_total) - parseFloat(row_tot)).toFixed(2));
        
        let grand_total = (( (parseFloat(sub_total) + parseFloat(fe) + parseFloat(taax)) - parseFloat(dis) ) - parseFloat(row_tot))

        $("#full_total").text( ( grand_total ).toFixed(2) );

        $("#row_" + random_no).remove();
        $(".add_details_tr_"+random_no).remove();
        $(".is_sub_tr_"+random_no).remove();
    
    }else{


        let fe = $('#fees_show').text();
        let dis = $('#discount_show').text();
        let taax = $('#tax_show').text();
        let sub_total = $("#sub_total").text();
        
        let others =(parseFloat(fe) + parseFloat(taax)) - parseFloat(dis);
        let subTotal = (parseFloat(sub_total) - parseFloat(row_total)).toFixed(2);
        let grandTotal = (parseFloat(subTotal) + parseFloat(others));

        $("#sub_total").text(subTotal);
        $("#full_total").text( (grandTotal).toFixed(2) );   

        $("#row_" + random_no).remove();
        $(".edit_details_tr_"+random_no).remove();
        $(".edit_is_sub_tr_"+random_no).remove();

    }
}

function getCustomerDetail(id, action='') {
    customer = null;
    add_new_customer = false;

    if(!action) {
        cancelAddress('billing');
        cancelAddress('shipping');
        cancelDetails();
    }

    if (id == 0) {
        $('#inv-acts').find('.fa-eye').parent().hide();
        $('#inv-acts').find('.fa-pencil-alt').hide();

        $('#invoice_box').hide();

        $("#name_text").text("");
        $("#email_address").text("");
        $("#phone").text("");
        $("#altphone").text("");
        $("#po").text("");

        $("#add").text("");
        $("#add1").text("");
        $("#add2").text("");
        $("#add3").text("");

        $("#sadd").text("");
        $("#sadd1").text("");
        $("#sadd2").text("");
        $("#sadd3").text("");

        $("#bill_st_add").val("");
        $("#bill_apt_add").val("");
        $("#bill_add_city").val("");
        $("#bill_add_zip").val("");

        $("#bill_add_state").val("0").change();
        $("#bill_add_country").val("0").change();

        $("#invoice_box").css("display", "none");
        $("#shipping_box").css("display", "none");
        
    } else {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
            type: "GET",
            url: get_customer_by_id + "/" + id,
            dataType: "json",
            beforeSend: function (data) {
                $("#invoice").fadeIn();
                $("#address").fadeIn();
            },
            success: function (data) {
                $('#invoice_box').show();
                $('#inv-acts').find('.fa-eye').parent().show();
                $('#inv-acts').find('.fa-pencil-alt').show();

                console.log(data, "customer list");

                var obj = data.data;

                customer = obj;
                try {
                    if(action == 'updated') {
                        let ind = customers_list.map(function(itm) { return itm.id; }).indexOf(customer.id);
                        if(ind > -1) {
                            if(customers_list[ind].first_name != customer.first_name || customers_list[ind].last_name != customer.last_name) {
                                customers_list[ind].first_name = customer.first_name;
                                customers_list[ind].last_name = customer.last_name;
    
                                $('#customer_list').select2('destroy');
                                $('#customer_list').find('option[value="'+customer.id+'"]').text(customer.first_name+' '+customer.last_name);
    
                                $('#customer_list').select2();
                            }
                        }
                    }

                    if(countries_list.length) {
                        if(customer.bill_add_country) {
                            $('#bill_add_country').val(customer.bill_add_country);
                        }
                        if(customer.hasOwnProperty('ship_add_country') && customer.ship_add_country) {
                            $('#ship_add_country').val(customer.ship_add_country);
                        }
                        $('#bill_add_country').trigger('change');
                        $('#ship_add_country').trigger('change');
                    }
                } catch (err) {
                    console.log(err);
                }

                let id = obj.id;
                let email = obj.email != null ? obj.email : '';
                let phone = obj.phone != null ? obj.phone : '';
                
                $("#c_id").val(id);
                // $("#ship_cust_id").val(id);
                $("#customer_id").val(id);

                $("#name_text").text(obj.first_name+' '+obj.last_name);
                $("#email_address").text(email);
                $("#phone").text(phone);
                $("#altphone").text(obj.alt_phone);
                $("#po").text(obj.po);

                var billing_address = obj.bill_st_add != null ? obj.bill_st_add : '';
                var billing_apt_address = obj.bill_apt_add != null ? obj.bill_apt_add : '';
                var billing_city = obj.bill_add_city != null ? obj.bill_add_city : '';
                
                var billing_state = obj.bill_add_state != null ? obj.bill_add_state : '';
                var billing_zipcode = obj.bill_add_zip != null ? obj.bill_add_zip : '';
                var billing_country = obj.bill_add_country != null ? obj.bill_add_country :'';

                var ship_address = obj.address != null ? obj.address : '';
                var ship_apt_address = obj.apt_address != null ? obj.apt_address : '';
                var ship_city = obj.cust_city != null ? obj.cust_city : '';
                var ship_state = obj.cust_state != null ? obj.cust_state : '';
                var ship_zipcode = obj.cust_zip != null ? obj.cust_zip : '';
                var ship_country = obj.country != null ? obj.country :'';

                $("#billing_address").text(billing_address);
                $("#billing_address_val").val(billing_address);
                $("#billing_house_address").text(billing_apt_address);
                $("#billing_city").text(billing_city);
                $("#billing_state").text(billing_state);
                $("#billing_zip").text(billing_zipcode);
                $("#billing_country").text(billing_country);

                $("#shipping_address").text(ship_address);
                $("#shipping_house_address").text(ship_apt_address);
                $("#shipping_city").text(ship_city);
                $("#shipping_state").text(ship_state);
                $("#shipping_zip").text(ship_zipcode);
                $("#shipping_country").text(ship_country);


                $("#bill_st_add").val(billing_address);
                $("#bill_apt_add").val(billing_apt_address);
                $("#bill_add_city").val(billing_city);
                $("#bill_add_zip").val(billing_zipcode);
                $("#bill_add_state").val(billing_state);
                
                $("#bill_add_country").val(billing_country);

                $("#ship_st_add").val(ship_address);
                $("#ship_apt_add").val(ship_apt_address);
                $("#ship_add_city").val(ship_city);
                $("#ship_add_zip").val(ship_zipcode);
                $("#ship_add_state").val(ship_state);
                $("#ship_add_country").val(ship_country);

                $('#customer_det').attr('href', "{{url('/customer-profile')}}/"+customer.id);
                $("#invoice_box").css("display", "block");
                $("#shipping_box").css("display", "block");
            },
            complete: function (data) {
                $("#invoice").fadeOut(500);
                $("#address").fadeOut(500);
            },
            error: function (e) {
                console.log(e);
                $("#invoice").fadeOut(500);
                $("#address").fadeOut(500);
            },
        });
    }
}

// publised order
function publishOrder() {
    let status_text = $("#status option:selected").text();
    let form_data = $("#addItemForm").serialize();
    form_data += "&status_text=" + status_text;
    var ord_id = $("#edit_order_id").val();

    if (ord_id != "" && ord_id != null) {
        form_data += "&odr_id=" + ord_id;
    } else {
        form_data += "&odr_id=" + 0;
    }

    $.ajax({
        url: published_url,
        type: "POST",
        data: form_data,
        dataType: "JSON",
        beforeSend: function (data) {
            $("#publishBtn").hide();
            $("#publishing").show();
        },
        success: function (data) {
            console.log(data, "data");
            if ((data.status_code == 200) & (data.success == true)) {
                toastr.success(data.message, { timeOut: 5000 });
                window.location = billingHomePage;
            } else {
                toastr.error(data.message, { timeOut: 5000 });
            }
        },
        complete: function (data) {
            $("#publishBtn").show();
            $("#publishing").hide();
        },
        error: function (e) {
            console.log(e);
        },
    });
}

function cancelDetails() {
    $('#update_details').hide();
    $('#inv-acts').find('.fa-window-close').hide();
    if(customer || (add_new_customer && new_customer)) {
        if(new_customer) {
            $("#name_text").text(new_customer.first_name+' '+new_customer.last_name);
            $("#email_address").text(new_customer.email);
            $("#phone").text(new_customer.phone);
            $("#altphone").text(new_customer.alt_phone);
            $("#po").text(new_customer.po);
        }
        $('#inv-acts').find('.fa-pencil-alt').show();
        $('#invoice_box').show();
    }
}

function customerDetails() {
    $('#invoice_box').hide();
    $('#inv-acts').find('.fa-pencil-alt').hide();
    $('#inv-acts').find('.fa-window-close').show();

    let fn = '', ln = '', em = '', ph='', al='', po='';
    if(add_new_customer && new_customer) {
        fn = new_customer.first_name;
        ln = new_customer.last_name;
        em = new_customer.email;
        ph = new_customer.phone;
        al = new_customer.alt_phone;
        po = new_customer.po;
    } else if(customer) {
        fn = customer.first_name;
        ln = customer.last_name;
        em = customer.email;
        ph = customer.phone;
        al = customer.alt_phone;
        po = customer.po;
    }

    $('#update_details').html(`<div class="row">
        <div class="col-sm-12 col-xs-12">
            <fieldset>
                <div class="row">
                    <div class="col-12 form-group">
                        <label>First Name</label>
                        <input type="text" value="${fn}" class="form-control" name="first_name" required>
                    </div>
                    <div class="col-12 form-group">
                        <label>Last Name</label>
                        <input type="text" value="${ln}" class="form-control" name="last_name" required>
                    </div>
                    <div class="col-12 form-group">
                        <label>Email</label>
                        <input type="text" value="${em}" class="form-control" name="email" required>
                    </div>
                    <div class="col-12 form-group">
                        <label>Main Phone</label>
                        <input type="text" value="${ph}" class="form-control" name="phone" required>
                    </div>
                    <div class="col-12 form-group">
                        <label>Alt. Phone</label>
                        <input type="text" value="${al}" class="form-control" name="alt_phone">
                    </div>
                    <div class="col-12 form-group">
                        <label>PO</label>
                        <input type="text" value="${po}" class="form-control" name="po">
                    </div>
                </div>
            </fieldset>
            <div class="text-right">
                <button type="submit" class="btn waves-effect waves-light btn-success" id="save">Save</button>
                <button style="display:none" id="processing" class="btn btn-sm btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
            </div>
        </div>
    </div>`);
    $('#update_details').show();
}

function cancelAddress(box) {
    $('#'+box+'-box').find('.add-form').hide();
    $('#'+box+'-box').find('.fa-window-close').hide();
    $('#'+box+'-box').find('.fa-pencil-alt').show();
    $('#'+box+'-box').find('.viewonly').show();
}

function billingAddress(box){
    // $('#billing_title').text('Update Billing Address');
    // $('#billingModal').modal('show');
    let opts = '';
    for(let i in countries) {
        let cty = countries[i];
        if (customer.bill_add_country)
            opts += `<option value="${cty.name}" ${cty.name == customer.bill_add_country ? 'selected' : ''}>${cty.name}</option>`;
        else
            opts += `<option value="${cty.name}" ${cty.short_name == 'US' ? 'selected' : ''}>${cty.name}</option>`
    }

    let address = customer.bill_st_add ?? '';
    let apt = customer.bill_apt_add ?? '';
    let city = customer.bill_add_city ?? '';
    let state = customer.bill_add_state ?? '';
    let zip = customer.bill_add_zip ?? '';
    let cty = customer.bill_add_country ?? '';
    if(box == 'shipping') {
        address = customer.address ?? '';
        apt = customer.apt_address ?? '';
        city = customer.cust_city ?? '';
        state = customer.cust_state ?? '';
        zip = customer.cust_zip ?? '';
        cty = customer.country ?? '';
    }

    $('#'+box+'-box').find('.viewonly').hide();
    $('#'+box+'-box').find('.fa-pencil-alt').hide();
    $('#'+box+'-box').find('.fa-window-close').show();

    $('#'+box+'-box').find('form').html(`<input type="hidden" id="ship_cust_id" name="id" value="${customer.id}"><input type="hidden" name="address_type" value="${box}"><div class="row">
        <div class="col-sm-12 col-xs-12">
            <fieldset>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>First Name</label>
                        <input type="text" value="${customer.first_name}" class="form-control" name="first_name" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Last Name</label>
                        <input type="text" value="${customer.last_name}" class="form-control" name="last_name" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Street Address</label>
                        <input type="text" value="${address}" class="form-control" id="bill_st_add" name="address" placeholder="House number and street name">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Apt Address</label>
                        <input type="text" value="${apt}" class="form-control" id="bill_apt_add" name="apt_address" placeholder="Apartment, suit, unit etc. (optional)">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>City</label>
                        <input type="text" class="form-control" value="${city}" id="bill_add_city" name="cust_city">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>State</label>
                        ${gooogle == 1 ? `<input type="text" class=" form-control" value="${state}" id="bill_add_state" name="cust_state" style="width: 100%; height:36px;">` : `<select class="select2 form-control" id="bill_add_state" name="cust_state" style="width: 100%; height:36px;"></select>`}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Zip Code</label>
                        <input type="number" maxlength="5" value="${zip}" class="form-control" id="bill_add_zip" name="cust_zip">
                    </div>
                    <div class="col-md-6 form-group">
                        <div class="form-group">
                            <label>Country</label>
                            ${ gooogle == 1 ? `<input type="text" class="form-control" value="${cty}" id="bill_add_country" name="country" style="width: 100%; height:36px;">` : `<select class="select2 form-control" id="bill_add_country" name="country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'bill_add_state', 'bill_add_state')">${opts}</select>`}
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="text-right">
                <button type="submit" class="btn waves-effect waves-light btn-success" id="save">Save</button>
                <button style="display:none" id="processing" class="btn btn-sm btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
            </div>
        </div>
    </div>`);
    $('#'+box+'-box').find('.add-form').show();
}

function shippingAddress(){
    $('#shipping_title').text('Update Shipping Address');
    $('#shippingModal').modal('show');
}

function toggleElements(list) {
    $('.tgls').hide();

    if(list == 'checkAll') {
        $('.tgls').show();
    } else {
        list.forEach(el => {
            switch(el) {
                case 'cost':
                    $('.cost-tgl').show();
                    break;
                case 'qty':
                    $('.qty-tgl').show();
                    break;
                case 'total':
                    $('.ttl-tgl').show();
                    break;
                case 'disc':
                    $('#add_discount').show();
                    break;
                case 'msrp':
                    // $('#add_discount').show();
                    break;
                case 'fee':
                    $('#add_fees').show();
                    break;
                case 'tax':
                    $('#add_tax').show();
                    break;
                default:
                    break;
            }
        });
    }
}

function addNewOrder() {
    $('#customer_list').val('').trigger('change');
    add_new_customer = true;
    customer = null;
    $('#invoice_box').hide();
    customerDetails();
}
</script>

@include('js_files/statesJs')