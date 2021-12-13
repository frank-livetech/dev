var total_fees = 0;
var total_discount = 0; 
var total_tax = 0;
var total_amount = 0;

$(document).ready(function () {

    var customer_id = $("#customer_id").val();

    console.log(line_items , "line_items");

    // update customer address
    $(".update_address").submit(function (event) {
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
                        $("#billingModal").modal("hide");
                    }else{
                        $("#shippingModal").modal("hide");
                    }

                    getCustomerDetail(id);

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
                <td>
                    <input name="price[]" type="text" onkeyup="costPrice(`+ random_no +`)" min="1" value="0" class="form-control price" id="cost_price_` +random_no +`"/>
                </td>
                <td>
                    <input name="quantity[]" type="text" onkeyup="qtyPrice(` + random_no + `)" min="1" value="0" class="form-control qty" id="qty_` + random_no +`"/>
                </td>
                <td style="text-align: right;">
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
                                    <input name="subscription_cost[]" input="number" class="form-control"/>
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
                    `<tr id="row_` + line_items[i].id +`">
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
                            <button type="button" title="Remove Row" class="btn btn-danger" style="border-radius:50px;padding:6px 14px;" 
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
            <td><input name="price[]" type="text" onkeyup="costPrice(` + random_no + `)" min="1" value="0" class="form-control price" id="cost_price_` + random_no + `"/></td>
            <td><input name="quantity[]" type="text" onkeyup="qtyPrice(` + random_no + `)" min="1" value="0" class="form-control" id="qty_` + random_no + `"/></td>
            <td style="text-align: right;"><p id="total_` + random_no + `" class="amt mt-2">0.00</p></td>
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
                                <input name="subscription_cost[]" type="number" class="form-control"/>
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
    // alert(random_no);
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

        $("#row_" + id).remove();
        $(".edit_details_tr_"+random_no).remove();
        $(".edit_is_sub_tr_"+random_no).remove();

    }
}

function getCustomerDetail(id) {
    if (id == 0) {
        $("#email_address").text("");
        $("#phone").text("");

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
                console.log(data, "customer list");
                var obj = data.data;

                let id = obj.id;
                let email = obj.email != null ? obj.email : '';
                let phone = obj.phone != null ? obj.phone : '';
                
                $("#c_id").val(id);
                $("#ship_cust_id").val(id);
                $("#customer_id").val(id);

                $("#email_address").text(email);
                $("#phone").text(phone);

                var billing_address = obj.bill_st_add != null ? obj.bill_st_add : '';
                var billing_apt_address = obj.bill_apt_add != null ? obj.bill_apt_add : '';
                var billing_city = obj.bill_add_city != null ? obj.bill_add_city : '';
                
                var billing_state = obj.bill_add_state != null ? obj.bill_add_state : '';
                var billing_zipcode = obj.bill_add_zip != null ? obj.bill_add_zip : '';
                var billing_country = obj.bill_add_country != null ? obj.bill_add_country :'';

                var ship_address = obj.shipping_st_add != null ? obj.shipping_st_add : '';
                var ship_apt_address = obj.shipping_apt_add != null ? obj.shipping_apt_add : '';
                var ship_city = obj.shipping_add_city != null ? obj.shipping_add_city : '';
                var ship_state = obj.shipping_add_state != null ? obj.shipping_add_state : '';
                var ship_zipcode = obj.shipping_add_zip != null ? obj.shipping_add_zip : '';
                var ship_country = obj.shipping_add_country != null ? obj.shipping_add_country :'';


                $("#billing_address").text(billing_address);
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


function billingAddress(){
    $('#billing_title').text('Update Billing Address');
    $('#billingModal').modal('show');
}

function shippingAddress(){
    $('#shipping_title').text('Update Shipping Address');
    $('#shippingModal').modal('show');
}