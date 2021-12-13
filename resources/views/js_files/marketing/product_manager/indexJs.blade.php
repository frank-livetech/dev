
<script>
    // Product Manager Script Blade
    $(document).ready(function() {

        $('#dropi-0').dropify();
        $('#dropi-01').dropify();

        get_all_products();
        product_template();

        $('#advanceStart').hide();

        $("input[name$='startingQuiz']").click(function() {
            var test = $(this).val();
            if(test = "quick"){
                $('#quickStart').toggle();
                $('#advanceStart').toggle();
            }
        });

        $("#product_template").on('change',function() {
            $("#product_template_error").html("");
        });

    });
    
    function product_template() {
        $.ajax({
            type: 'GET',
            url: "{{url('get-asset-templates')}}",
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                console.log(data , "data");
                if (data.success == true) {
                    templates = data.templates;
                    let opts = '<option value="">Select</option>';
                    for (let i in templates) {
                        opts += `<option value="${templates[i].id}">${templates[i].title}</option>`;
                    }
                    $("#product_template").html(opts);
                }
            },
            error: function(e) {
                console.log(e)
            }
        });
    }



    function templatePage() {
        let product_template = $("#product_template").val();
            alert(product_template);
        if(product_template == '') {
            $("#product_template_error").html("please select product template");
        }else{
            $("#product_template_error").html("");
            window.location = "{{url('product-template')}}/" + product_template;
        }

    }


    /*Full CRUD of products */

    $("#proTypeClick").click(function(){

        let product_type = $("#product_type").val();

        let custom_id = product_type == "hard-goods" ? 1 : 0;


        if(product_type == '') {
            $("#product_type_error").html("please select product template");
        }else{
            $("#product_type_error").html("");
            window.location = "{{url('digital-goods')}}/" + custom_id;
        };
    });

    function delPro(id){
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: 'del-product',
                    data: { id: id },
                    success: function(data) {
                        if (data.status_code == 200 && data.success == true) {
                            Swal.fire("Success", data.message, "success");
                            get_all_products();
                        } else {
                            Swal.fire("Cancelled!", data.message, "error");
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            }
        });
    }

    function get_all_products() {
        $.ajax({
            type: "GET",
            url: "get-products",
            // dataType: 'json',
            success: function(data) {
                var system_date_format = data.date_format;
                console.log(data, "data");

                var row = ``;
                var count = 1;
                var data = data.products;

                $("#totalProducts").text(data.length);

                for (var i = 0; i < data.length; i++) {

                    var ptitle = data[i].title;

                    if(ptitle == null){ptitle = '--';}

                    var pspecs = data[i].specs;

                    if(pspecs == null){pspecs = '--';}
                    
                    var psku = data[i].sku;

                    if(psku == null){psku = '--';}

                    var psale = data[i].oor_sale_price;

                    if(psale == null){psale = '--';}
                    row += `<tr>
                                <td>
                                    <div class="text-center">
                                        <input type="checkbox" id="checkAll" name="assets[]" value="0">
                                    </div>
                                </td>

                                <td class="text-center"><i class="fas fa-image"></i></td>

                                <td style=""> <span class="plugName">`+data[i].id+` </span> </td>

                                <td><span class="text-success"> `+ptitle+`</span> </td>

                                <td style="width:32px;"><a href="#">`+pspecs+`</a></td>

                                <td>`+psku+`</td>

                                <td>`+psale+`</td>

                                <td>`+data[i].created_at+`</td>

                                <td>
                                    <a  class="btn-success btn rounded" href="{{url('edit-product')}}/`+data[i].id+`"><i class="fas fa-user" aria-hidden="true"></i> </a>
                                    <button class="btn btn-danger rounded" onclick="delPro(`+data[i].id+`)"><i class="fas fa-trash" aria-hidden="true"></i> </button>
                                </td>

                            </tr>`;
                    count++;

                }
                $('#proTypes').html(row);
                var product_table = $('#pro-type-table-list').DataTable();

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
