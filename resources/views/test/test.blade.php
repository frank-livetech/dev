<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
  </head>

  <body>
    <script src="https://www.paypal.com/sdk/js?client-id=ATLowvk8ykj1PILCGoUOKOSz3F0jtYm8ankP35G8TD7ROVjTdZE8ZWO5_1h8Ayb4L_GFP3_P_EbcOCPL">
    </script>

    <div id="paypal-button-container"></div>

    <!-- Add the checkout buttons, set up the order and approve the order -->
    <script>
      paypal.Buttons({
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: '0.01'
              }
            }]
          });
        },
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(details) {
            alert('Transaction completed by ' + details.payer.name.given_name);
          });
        }
      }).render('#paypal-button-container'); // Display payment options on your web page
    </script>
  </body>
</html>




{{-- @extends('layouts.staff-master-layout')
@section('body-content')

    <!-- Accompanying JS file -->


    <style type="text/css">

        [contenteditable=true]:empty:before{
            content: attr(placeholder);
            pointer-events: none;
            display: block; /* For Firefox */
        }

        h1, h2, p, li[contenteditable=true] {
            border: none;
            width: 290px;
            padding: 5px;
            margin-right: 5px;
        }

    </style>
<body style="overflow: hidden;" class="h-100">

<div class="container-fluid">
    <div id="listMessage" class="my-3" style="display: none"></div>
    <!-- Page Header Which will not Scroll -->
    <div class="page-static-header col-12" style="min-height: 50px; max-height: 50px; height: 50px;">
        <h4>Editor</h4>
    </div>

    <div class="page-body row" id="editor" style="overflow: auto">
        

    </div>

</div>

</body>

@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/pages/test/editor_lib.js').'?ver='.rand()}}"></script>
<script src="{{ asset('js/pages/test/editor.js').'?ver='.rand()}}"></script>


 --}}
