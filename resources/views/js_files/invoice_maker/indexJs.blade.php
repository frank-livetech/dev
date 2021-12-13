<script>

    // invoice Maker Index Script Blade
    
    var edit_order_id = $("#edit_order_id").val();
    var order = {!!json_encode($order)!!};
    var line_items = {!!json_encode($order_line_items)!!};
    
    console.log(line_items , "line_items");
    
    var published_url = '{{url("/billing/published")}}';
    var billingHomePage = '{{url("/billing/home")}}';
    var update_customer_address = "{{url('update_customer_address')}}";
    var create_invoice = "{{url('create_invoice')}}";
    var get_customer_by_id = "{{url('get_customer_by_id')}}";

    var update_order = "{{url('update_order')}}";

    var invoice_url = "{{url('create_pdf_invoice')}}";

    var checkout = "{{url('checkout')}}";
    var googleObject = {!! json_encode($google) !!};

</script>
<script>
    let autocomplete;
    let billing_address;

    function initMap(){
        billing_address = document.querySelector('#billing_address_val');

        autocomplete = new google.maps.places.Autocomplete(billing_address, {
            componentRestrictions: { country: ["us", "ca"] },
            fields: ["address_components", "geometry"],
            types: ["address"],
        });
        billing_address.focus();

        autocomplete.addListener("place_changed", fillInvoiceBillingAddress);
    }
    function fillInvoiceBillingAddress() {

        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";

        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
            case "street_number": {
                address1 = `${component.long_name} ${address1}`;
                break;
            }

            case "route": {
                address1 += component.short_name;
                break;
            }

            case "postal_code": {
                postcode = `${component.long_name}${postcode}`;
                break;
            }

            case "postal_code_suffix": {
                postcode = `${postcode}-${component.long_name}`;
                break;
            }
            case "locality":
                document.querySelector("#bill_add_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#bill_add_state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#bill_add_country").value = component.long_name;
                break;
            }
        }
        address1Field.value = address1;
        postalField.value = postcode;

        address2Field.focus();

    }
    
    $(document).ready(function(){
        

        if(!$.isEmptyObject(googleObject)){
            if( googleObject.hasOwnProperty('api_key')){
                var api_key = googleObject.api_key;
                console.log(api_key , "api");
                $("#google_api_key").val(api_key);
                if(api_key!=''){
                    var script ="https://maps.googleapis.com/maps/api/js?key="+api_key+"&libraries=places&sensor=false&callback=initMap";
                    var s = document.createElement("script");
                    s.type = "text/javascript";
                    s.src = script;
                    $("head").append(s);
                }
                const allScripts = document.getElementsByTagName( 'script' );
                [].filter.call(
                allScripts, 
                ( scpt ) => scpt.src.indexOf( 'key='+api_key ) >0
                )[ 0 ].remove();
            }
        }

    });

   

</script>