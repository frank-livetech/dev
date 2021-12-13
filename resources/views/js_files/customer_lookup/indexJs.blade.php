<script>

    //Kill me
let autocomplete;
let address1Field;
let address2Field;
let postalField;

function initMap(){
  address1Field = document.querySelector("#address");
  address2Field = document.querySelector("#apt_address");
  postalField = document.querySelector("#zip");
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  console.log(address1Field);
  autocomplete = new google.maps.places.Autocomplete(address1Field, {
    componentRestrictions: { country: ["us", "ca"] },
    fields: ["address_components", "geometry"],
    types: ["address"],
  });
  address1Field.focus();
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();
  let address1 = "";
  let postcode = "";

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  // place.address_components are google.maps.GeocoderAddressComponent objects
  // which are documented at http://goo.gle/3l5i5Mr
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
        document.querySelector("#city").value = component.long_name;
        break;

      case "administrative_area_level_1": {
        document.querySelector("#state").value = component.short_name;
        break;
      }
      case "country":
        document.querySelector("#country").value = component.long_name;
        break;
    }
  }
  address1Field.value = address1;
  postalField.value = postcode;
  // After filling the form with address components from the Autocomplete
  // prediction, set cursor focus on the second address line to encourage
  // entry of subpremise information such as apartment, unit, or floor number.
  address2Field.focus();
    
}

 $(document).ready(function(){

   
      var googleObject = {!! json_encode($google) !!};
      console.log(googleObject);
      if(!$.isEmptyObject(googleObject)){
        if( googleObject.hasOwnProperty('api_key')){
            var api_key = googleObject.api_key;
            console.log(api_key)
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

                    // window.google = {};
        }
   }


        $("#new-Company-button").click(function(){
            $("#new-Company-div").toggle();
            $("#customer_email").toggle(); 
            var bing = $("#new-Company-button").text();
            
           
            if(bing == 'Existing Company'){
                // alert("ikl");
                $('#reverting').addClass('col-md-1');
                $('#reverting').removeClass('col-md-4');
                $("#new-Company-button").text("New");
            }
            else if(bing = 'New'){
                $('#reverting').removeClass('col-md-1');
                $('#reverting').addClass('col-md-4');
                $("#new-Company-button").text("Existing Company");
            }
        });

    });
    $("#showtooltop").tooltip();
    $("#customer_add_btn").click(function(){
        $("#addCustomerModal").find(".form-control").val('');
        $("#addCustomerModal").find(".form-control").removeAttr("readonly");
    })
       
    $(document).ready(function() {
        
        // $("[data-labelfor]").click(function() {
        //     $('#' + $(this).attr("data-labelfor")).prop('checked',
        //                     function(i, oldVal) { return !oldVal; });
        //                     if ($("#new-Company_check").prop("checked") == false) {

        //         $('#new-Company').css('display', 'none');
        //         $("#customer_email").css('display', 'block')

        //         } else if ($("#new-Company_check").prop("checked") == true) {
        //         $('#new-Company').css('display', 'block');
        //         $("#customer_email").css('display', 'none')
        //         }
        // });

    });
    </script>