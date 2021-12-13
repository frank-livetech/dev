
<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function () {
                CollectJS.configure({
                    "paymentSelector" : "#payButton",
                    "variant" : "lightbox",
                    "styleSniffer" : "false",
                    "googleFont": "Montserrat:400",
                    "customCss" : {
                        "color": "#0000ff",
                        "background-color": "#d0d0ff"
                    },
                    "invalidCss": {
                        "color": "white",
                        "background-color": "red"
                    },
                    "validCss": {
                        "color": "black",
                        "background-color": "#d0ffd0"
                    },
                    "placeholderCss": {
                        "color": "green",
                        "background-color": "#808080"
                    },
                    "focusCss": {
                        "color": "yellow",
                        "background-color": "#202020"
                    },
                    "fields": {
                        "ccnumber": {
                            "selector": "#ccnumber",
                            "title": "Card Number",
                            "placeholder": "0000 0000 0000 0000"
                        },
                        "ccexp": {
                            "selector": "#ccexp",
                            "title": "Card Expiration",
                            "placeholder": "00 / 00"
                        },
                        "cvv": {
                            "display": "show",
                            "selector": "#cvv",
                            "title": "CVV Code",
                            "placeholder": "***"
                        },
                       
                      
                      
                    },
                    'validationCallback' : function(field, status, message) {
                        if (status) {
                            var message = field + " is now OK: " + message;
                        } else {
                            var message = field + " is now Invalid: " + message;
                        }
                        console.log(message);
                    },
                    "timeoutDuration" : 2000,
                    "timeoutCallback" : function () {
                        console.log("The tokenization didn't respond in the expected timeframe.  This could be due to an invalid or incomplete field or poor connectivity");
                    },
                    "fieldsAvailableCallback" : function () {
                        console.log("Collect.js loaded the fields onto the form");
                    },
                    'callback' : function(response) {
                        console.log(response);
                        var input = document.createElement("input");
                        $('#card_type').val(response.card.type)
                        $('#cardlastDigits').val(response.card.number)
                        $('#exp').val(response.card.exp)
                        $('#payment_token').val(response.token).trigger('change')
                      
                        // CardSubmit();
                        
                    }
                });
            });

    $("#openSignIn").click(function(){
    $("#signInForm").toggleClass("hide");
    });

    $("#creditPay").click(function(e){
        e.preventDefault();
        var payment_token=$("input[name=paymentCardRadio]:checked").val();
        var orderId=$("#orderId").val();
        $.ajax({
            type: "GET",
            url: "{{asset('/creditCardPyment')}}/"+orderId+'/'+payment_token,
            dataType: 'json',
            success: function(data) {
                
                console.log(data, "data");
                if ( data.success == true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: "Order paid SuccessFully! ",
                        showConfirmButton: false,
                        timer: 2500
                    });
                    
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
            
            },
            error: function(e) {
                console.log(e)
            }
        });


    });

    $("#check4").change(function(){
        if (this.checked) {
                $('.bill_address').removeClass('hide');
            }
            else {
                $('.bill_address').addClass('hide');
            }     
    });
    function get_cust_card() {
        var v_name = $("#cust_name").text();
        $.ajax({
            type: "GET",
            url: "{{asset('/get-customer-card')}}",
            dataType: 'json',
            data: {
                customer_id:"{{$customer->id}}"
            },
            cache: false,
            success: function(data) {
                // alert(v_name);
                console.log(data.types, "data");
                var cardSet = ``;
                var data = data.types;
                var masterCardimg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-mastercard.png" style="width:7%;">`;
                var visaImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-visa.png" style="width:7%;">`;
                var discoverImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-discover.png" style="width:7%;">`;
                var amexImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-amex.png" style="width:7%;">`;
                var maestroImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-maestro.png" style="width:7%;">`
                // alert(data.length);
                $('#payment_cards').empty();
                for (var i = 0; i < data.length; i++) {
                    // alert(data[i].ccn);
                    $('#payment_cards').append(`


                    <div class="custom-control custom-radio payment-radio br-0">
                        <input type="radio" id="paymentCardExist`+i+`"  name="paymentCardRadio" value="`+data[i].payment_token+`" class="custom-control-input" >
                        <label class="custom-control-label" for="paymentCardExist`+i+`"> <p>`+(data[i].card_type == 'mastercard' ? masterCardimg : ' ')+`
                                    `+(data[i].card_type == 'visa' ? visaImg : ' ')+`
                                    `+(data[i].card_type == 'amex' ? amexImg : ' ')+`
                                    `+(data[i].card_type == 'discover' ? discoverImg : ' ')+`
                                    `+(data[i].card_type == 'maestro' ? maestroImg : ' ')+ 
                                    data[i].card_type+` ending in `+data[i].cardlastDigits+`(expires `+data[i].exp+ `) <span class="ml-auto"><a data-toggle="modal" data-target="#editPayment"><i class="fa fa-pencil-alt"></i></a><a data-toggle="modal" data-target="#delPayment"><i class="fas fa-trash pl-3"></i></a></span></p>
                        </label>
                    </div>
                                    
                                        
                `);
                    // $('#pay-card').append(cardSet);
                }
            },
            error: function(e) {
                console.log(e)
            }
        });
    }

    $(document).ready(function() {
        try {
            if(countries_list.length) {
                $('#prof_country').trigger('change');
            }
        } catch (err) {
            console.log(err);
        }

        get_cust_card()
        $("#payment_token").change(function(event) {
        

        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var address1 = $('#address1').val();
        var city = $('#city').val();
        var state = $('#prof_state').val();
        var zip = $('#zip').val();
        var card_type = $('#card_type').val();
        var  exp= $('#exp').val()
        var email= $('#cust_email1').val()
        var formData = {
            fname: fname,
            lname: lname,
            address1: address1,
            city: city,
            customer_id:"{{$customer->id}}",
            state: state,
            zip: zip,
            card_type: card_type,
            exp: exp,
            email:email,
            payment_token: $(this).val(),
            cardlastDigits:$('#cardlastDigits').val()
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            url: "{{url('save-cust-card')}}",
            data: formData,
            dataType: 'json',
            beforeSend: function(data) {
                $('.loader_container').show();
            },
            success: function(data) {
                Swal.fire({
                        position: 'top-end',
                        icon: data.success ? 'success' : 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2500
                    });
                    get_cust_card()
                console.log(data)
            
            },
            complete: function(data) {
                $('.loader_container').hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
        });
        
        $("div.credit").hide();
        
        $("#addMsg").hide();
        $("#addAcc").hide();

        $("input[name$='paymentRadio']").click(function() {
            var test = $(this).val();
            $("div.credit").hide();
            $("#cred-card").hide();
            $("#" + test).show();
        });
        $("#checkMsg").change(function(){
            
            if (this.checked) {
                    $('#addMsg').show();
                }
                else {
                    $('#addMsg').hide();
                }     
        });
        $("#checkAcc").change(function(){
            if (this.checked) {
                    $('#addAcc').show();
                }
                else {
                    $('#addAcc').hide();
                }     
    });
    });
</script>