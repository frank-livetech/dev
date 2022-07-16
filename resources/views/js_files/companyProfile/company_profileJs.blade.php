<script>
// Company Profile Script Blade


$(document).ready(function() {



    // save company staff

    $(".companyUser").submit(function(event) {
        event.preventDefault();

        var action = $(this).attr('action');
        var method = $(this).attr('method');

        $.ajax({
            type: method,
            url: action,
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function (data) {
                $(".savingBtn").hide();
                $(".processingBtn").show();
            },
            success: function (data) {
                console.log(data, "a");

                if(data.status_code == 200 && data.success == true){

                    alertNotification('success', 'Success' , data.message );

                    if(data.type == 'customer') {
                        $('#add_staff_model').modal('hide');
                    }else{
                        $('#add_comp_user_model').modal('hide');
                    }

                }else{

                    alertNotification('error', 'Error' , data.message );
                }
            },
            complete: function (data) {
                $(".savingBtn").show();
                $(".processingBtn").hide();
            },
            failure: function (errMsg) {
                console.log(errMsg);
                $(".savingBtn").show();
                $(".processingBtn").hide();
            },
        });


    });




});




</script>
