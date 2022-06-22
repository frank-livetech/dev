

<script>
    // Digital Goods Script Blade
      $(document).ready(function() {
        $('#feature_image').dropify();
        $('#feature_video').dropify();
        var ter = $("#ptitle").text()
        $("#title").val(ter);
        let hung = $("#is_type").val();
            if(hung == '1'){
                $('.hard_goods_field').show();
                $('.digital_goods_field').hide();
            }
            else if(hung == '0'){
                $('.hard_goods_field').hide();
                $('.digital_goods_field').show();
            }
        });
        $("#publish").click(function(e) {
            $('#is_submit').val(1);
            e.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{url('add-product')}}",
                type: 'POST',
                data: new FormData(document.getElementById("addProducts")),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200 && data.success == true) {
                        alertNotification('success', 'Success' , data.message );

                        setTimeout(() => {
                            location.href = document.referrer;
                        }, 1500);

                    } else {
                        alertNotification('error', 'Error' , data.message );
                    }
                    console.log(data, "data");
                },
                error: function(e) {
                    console.log(e)
                }

            });
        });

        $("#draft").click(function(e) {
            $('#is_submit').val(0);
            e.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{url('add-product')}}",
                type: 'POST',
                data: new FormData(document.getElementById("addProducts")),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200 && data.success == true) {
                        toastr.success(data.message, {
                            timeOut: 5000
                        });
                    } 
                    else {
                        toastr.error(data.message, {
                            timeOut: 5000
                        });
                    }
                    console.log(data, "data");
                },
                error: function(e) {
                    console.log(e)
                }

            });
        });

</script>