

<script>  
// Watch Script Blade


var address1Field ,address2Field, postalField ;
function initMap(){ 
   address1Field = document.querySelector("#shortDescription1");
   address2Field = document.querySelector("#street_address");

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
  if($("#shortDescription1").val()) {
    $("#mapTech").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + $("#shortDescription1").val() + '&language=en"></iframe>')
  }

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
// $(document).ready(function(){


//     //******************************************//
//     // Map Types
//     //******************************************//
//     // var map_7;
//     // map_7 = new GMaps({
//     //     div: '#mapTech1',
//     //     lat: -12.043333,
//     //     lng: -77.028333,
//     //     mapTypeControlOptions: {
//     //         mapTypeIds: ["hybrid", "roadmap", "satellite", "terrain", "osm"]
//     //     }
//     // });
//     // map_7.addMapType("osm", {
//     //     getTileUrl: function(coord, zoom) {
//     //         return "https://a.tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
//     //     },
//     //     tileSize: new google.maps.Size(256, 256),
//     //     name: "OpenStreetMap",
//     //     maxZoom: 18
//     // });
//     // map_7.setMapTypeId("osm");
// });
        function Customer(e){
            var address = $("#customer_id option:selected").attr('data-address');
            console.log(address)
            $('#shortDescription1').val(address).trigger('change');
            autocomplete = new google.maps.places.Autocomplete( document.querySelector("#shortDescription1"), {
                componentRestrictions: { country: ["us", "ca"] },
                fields: ["address_components", "geometry"],
                types: ["address"],
            });
            
            autocomplete.addListener("place_changed", fillInAddress);

            if(address) {
              $("#mapTech").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + address + '&language=en"></iframe>')
            }
        }
        function Ticket(e){
            var address = $("#ticket_id option:selected").attr('data-address');
            console.log(address)
            $('#shortDescription1').val(address).trigger('change');
            autocomplete = new google.maps.places.Autocomplete( document.querySelector("#shortDescription1"), {
                componentRestrictions: { country: ["us", "ca"] },
                fields: ["address_components", "geometry"],
                types: ["address"],
            });
            
            autocomplete.addListener("place_changed", fillInAddress);

            if(address) {
              $("#mapTech").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + address + '&language=en"></iframe>')
            }
        }
</script>

@endsection

@section('scripts')
<script>

    $(document).ready(function() {

        var googleObject = {!! json_encode($google) !!};
      console.log(googleObject);
      if(!$.isEmptyObject(googleObject)){
        if( googleObject.hasOwnProperty('api_key')){
            var api_key = googleObject.api_key;
            $("#google_api_key").val(api_key);
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

      

        $(".mine").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                next: "Next",
                finish: "Submit!"
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex ==2 ) {
                    $("#date-show").text( $("#date1").val());
                    $("#address-show").text($("#shortDescription1").val()+$("#street_address").val());
                    $("#phone-show").text($("#phone").val());
                }
            },
            onFinished: function (event, currentIndex) {
                $("#watch_form")[0].submit();
                }
        });
        $(".first").find(".btn").text("Verify");


    });

    $( document ).ready(function() {
        $('input:radio[name="radio-stacked"]').change(function(){
            if($(this).val() == 'customer'){
                $("#selectCustomer").css("display","block");
                $("#selectTicket").css("display","none");
            }else{
                $("#selectCustomer").css("display","none");
                $("#selectTicket").css("display","block");
            }
        });
    });

   



    
   
</script>