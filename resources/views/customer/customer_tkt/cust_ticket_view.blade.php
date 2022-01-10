
@extends('customer.layout.customer_master')
@section('body')
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500&display=swap" rel="stylesheet">
<script id="script1" src="https://secure.merchantonegateway.com/token/Collect.js" data-tokenization-key="zBkgJ9-6r24y2-FeFXkD-Kyxr9P" ></script>

<!-- <script>
   var nmi_integration = {!! json_encode($nmi_integration) !!};

   if(!$.isEmptyObject(nmi_integration)){
        if( nmi_integration.hasOwnProperty('tokenization_key')){
            var data_key = nmi_integration.tokenization_key;
            var scriptTag = document.getElementById("script1");
            console.log(scriptTag)
            scriptTag.setAttribute("data-tokenization-key", data_key);
        }
   }
   
    
</script> -->

<style>
.CollectJSInlineIframe {
    /* height: auto !important; */
}

/* .form-group {
    width: 290px;
} */

.formInner {
    font-family: 'Abel' !important;
    width: 500px;
    max-width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin: 20px auto;
}
.gmaps, .gmaps-panaroma {
    height: 300px !important;
    background: #e9ecef;
    width:100% !important;
    border-radius: 4px;
}

.payment-field {
    border-radius: 2px;
    width: 48%;
    margin-bottom: 14px;
    box-shadow: 0 2px 8px #dddddd;
    font-size: 16px;
    transition: 200ms;
}

.payment-field input:focus {
    border: 3px solid #1AD18E;
    outline: none !important;
}

.payment-field:hover {
    box-shadow: 0 2px 4px #dddddd;
}

.payment-field input {
    border: 3px solid #ffffff;
    width: 100%;
    border-radius: 2px;
    padding: 4px 8px;
}

#payment-fields {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

#ccnumber {
    width: 100%;
    font-size: 24px;
}

#ccexp,
#cvv {
    font-size: 20px;
}

#paymentTokenInfo {
    width: 600px;
    display: block;
    margin: 30px auto;
}

.separator {
    margin-top: 30px;
    width: 100%;
}

@media only screen and (max-width: 600px) {
    .pageTitle {
        font-size: 30px;
    }

    .theForm {
        width: 300px;
        max-width: 90%;
        margin: auto;
    }

    .form-group {
        width: 100%;
    }
}
select,
textarea,
input,
.btn-new{
    margin: 10px 0 15px 0;

}
.btn-new{
    min-width: 100px;
    
}

input {
    border: 5px inset #808080;
    background-color: #c0c0c0;
    color: green;
    font-size: 25px;
    font-family: monospace;
    padding: 5px;
    margin: 5px 0;
}

.appends {
    cursor: grab;
    cursor: -moz-grab;
    cursor: -webkit-grab;
}

.appends.highlight {
    border-left: 3px solid transparent !important;
}

.highlight {
    border-left: 3px solid blue;
    height: 70px;
}

td.details-control {
    background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}

tr.shown td.details-control {
    background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
}

.bg-pPal {
    color: #fff;
    background-color: #005ea6;
}

.credit-pic img {
    width: 12%;
    padding-left: 6px;
}
table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
    /* position: absolute; */
    /* bottom: 0.9em; */
    display: none;
    opacity: 0;
}
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting {
    background-image: none !important;
}
.payCard-number{
    font-family: Orbitron;
    letter-spacing: 4px;
    margin-top: 40px;
}
.payCard-text{
    /* width: 39px; */
    font-size: 16px;
    font-family: Orbitron;
}
.payCard{
background-color:rgba(218,165,32,0.3);
}
#domainModal p{
    margin-bottom:0;
}
.flagged-tr {
    background-color: #FFE4C4 !important;
}

blockquote {
    margin: unset !important;
}

.sl-item {
    margin: unset !important;
}

.profile-pic-div label {
    background: black;
    border-radius: 50%;
    cursor: pointer;
}

.profile-pic-div label:hover img {
    opacity: 0.5;
}

.profile-pic-div label:hover span {
    display: inline-block;
}

.profile-pic-div label span {
    color: white;
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    margin-top: 35px;
}

.soc-ico {
    font-size: 32px;
}

.soc-card {
    justify-content: space-between;
    display: flex;
}

.select2-selection,
.select2-container--default,
.select2-selection--single {
    border-color: #848484 !important;
}
</style>
<div class="container-fluid">

    @if(Session::get('system_date')) 
        <input type="hidden" id="system_date_format" value="{{Session::get('system_date')}}">
    @else
        <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
    @endif

    @if(Session::get('timezone')) 
        <input type="hidden" id="timezone" value="{{Session::get('timezone')}}">
    @else
        <input type="hidden" id="timezone" value="DD-MM-YYYY">
    @endif

    <div class="row">
        <div class="col-md-12">
        @if($errors->any())
        
        <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            <p><strong>Opps Something went wrong</strong></p>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        
        </div>
        
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @endif

        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-6 col-md-4">
                            <div class="card card-hover border-bottom border-success">
                                <div class="box p-2 rounded success text-center">
                                    <h1 id="my_tickets_count">0</h1>
                                    <h6 class="text-success">All Tickets</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="card card-hover border-bottom border-warning">
                                <div class="box p-2 rounded warning text-center">
                                    <h1 id="open_tickets_count">0</h1>
                                    <h6 class="text-warning">Open</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="card card-hover border-bottom border-danger">
                                <div class="box p-2 rounded danger text-center">
                                    <h1 id="closed_tickets_count">0</h1>
                                    <h6 class="text-danger">Overdue</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="ticket-table-list"
                            class="table table-striped table-bordered display w-100">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="text-center">
                                            <input type="checkbox" name="select_all[]" id="select-all">
                                        </div>
                                    </th>
                                    <th></th>
                                    <th>Status</th>
                                    <th class='custom'>Subject</th>
                                    <th class='pr-ticket'>Ticket ID</th>
                                    <th >Priority</th>
                                    <th class='custom-cst'>Customer</th>
                                    <th class='pr-replies custom-cst'>Last Replier</th>
                                    <th>Replies</th>
                                    <th class='pr-activity '>Last Activity</th>
                                    <th class='pr-ticket'>Reply Due</th>
                                    <th class='pr-due'>Resolution Due</th>
                                    <th class='pr-tech custom-cst'>Assigned Tech</th>
                                    <th class='custom-cst'>Department</th>
                                    <th class='pr-tech custom-cst'>Creation Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="customer_id" value="{{$customer->id}}">
</div>

@endsection
@section('scripts')
<script>

    let ticketsList = null;
    let date_format = $('#system_date_format').val();

    let move_to_trash_route = "{{asset('/move_to_trash_tkt')}}";
    let del_ticket_route = "{{asset('/del_tkt')}}";
    let rec_ticket_route = "{{asset('/recycle_tickets')}}";
    let flag_ticket_route = "{{asset('/flag_ticket')}}";
    let merge_tickets_route = "{{asset('/merge_tickets')}}";
    let get_ticket_latest_log = "{{asset('/get_ticket_log')}}";
    let ticket_notify_route = "{{asset('/ticket_notification')}}";
    let ticket_details_route = "{{asset('/ticket-details')}}";
    let get_department_status = "{{asset('/get_department_status')}}"
    let get_tickets_route = "{{asset('/get-tickets')}}";
    let get_filteredtkt_route = "{{asset('/get-filtered-tickets')}}"

</script>
    @include('customer.Js.customer_tktJs')
@endsection