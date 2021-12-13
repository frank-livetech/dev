@extends('layouts.staff-master-layout')
<style>
    .select2-selection,
    .select2-container--default,
    .select2-selection--single {
        border-color: #848484 !important;
    }

    .head-cou p {
        font-weight: 400;
        color: #b1b4b1;
    }

    .f-400 {
        font-weight: 400;
    }

    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>
@section('body-content')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
    
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">

            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Billing</li>
                        <li class="breadcrumb-item active" aria-current="page">Invoice Maker</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value="{{$id}}" id="edit_order_id">
<input type="hidden" id="google_api_key">
<form id="addItemForm">
    <form action="" style="display: none;"></form>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <input type="hidden" name="order_id" id="ordr_id">
                    <div class="card-body">
                        <h3 class="font-weight-bold mb-3"> Invoice # <span id="custom_id">
                                <?php
                                if ($id == '') {
                                    if ($order == NULL || $order == '') {
                                        echo "1";
                                    } else {
                                        echo $order->id;
                                    }
                                } else {
                                    if ($order->custom_id != NULL || $order->custom_id != '') {
                                        echo $order->custom_id;
                                    } else {
                                        echo $id;
                                    }
                                }
                                ?>
                            </span>
                            <button class="btn btn-primary btn-sm float-right" onclick="addNewOrder()">Add New</button>
                        </h3>
                        @if($id == '')
                            <h4 id="inv-acts">Customer Details <a href="#" class="float-right" style="display: none;" id="customer_det" target="_blank"><span class="fas fa-eye ml-2"></span></a><span class="fas fa-pencil-alt float-right" style="cursor: pointer; display: none;" onclick="customerDetails()"></span><span class="fas fa-window-close float-right" style="cursor: pointer; display: none;" onclick="cancelDetails()"></span></h4>
                                
                            <div id="invoice_box" style="display:none">
                                <span class="text-muted small">First Name</span>
                                <h5 id="name_text"></h5>
                                
                                <span class="text-muted small">Email Address</span>
                                <h5 id="email_address"></h5>

                                <span class="text-muted small mt-2">Main Phone</span>
                                <h5 id="phone"></h5>

                                <span class="text-muted small mt-2">Alt Phone</span>
                                <h5 id="altphone"></h5>

                                <span class="text-muted small mt-2">PO</span>
                                <h5 id="po"></h5>
                            </div>
                            <form class="form-horizontal" id="update_details" onsubmit="return false;" style="display: none;"></form>
                        @else
                            @if($customer_info != null && $customer_info != "")
                            <h4 id="inv-acts">Customer Details <a href="{{url('customer-profile')}}/{{$customer_info->id}}" class="float-right" style="display: none;" id="customer_det" target="_blank"><span class="fas fa-eye"></span></a><span class="fas fa-pencil-alt" style="cursor: pointer; display: none;" onclick="customerDetails()"></span><span class="fas fa-window-close float-right" style="cursor: pointer; display: none;" onclick="cancelDetails()"></span></h4>

                            <div id="invoice_box">
                                <span class="text-muted small">Name</span>
                                <h5 id="name_text">{{$customer_info->first_name}} {{$customer_info->last_name}}</h5>

                                <span class="text-muted small">Email Address</span>
                                <h5 id="email_address">{{$customer_info->email}}</h5>

                                <span class="text-muted small mt-2">Phone</span>
                                <h5 id="phone">{{$customer_info->phone}}</h5>

                                <span class="text-muted small mt-2">Alt Phone</span>
                                <h5 id="altphone"></h5>

                                <span class="text-muted small mt-2">PO</span>
                                <h5 id="po"></h5>
                            </div>
                            <form class="form-horizontal" id="update_details" onsubmit="return false;" style="display: none;"></form>
                            @else
                                <span class="text-danger">Missing Customer Information</span>
                            @endif
                        @endif
                        <div class="loader_container" id="invoice" style="display:none">
                            <div class="loader"></div>
                        </div>
                    </div>

                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h4 class="card-title"> Create PDF </h4>
                        @if($id == '')
                        <a href="#" id="pdf_btn" type="button" class="btn btn-outline-primary disabled" disabled="disabled">PDF Invoice</a>
                        @else
                        <a href="{{url('create_pdf_invoice')}}/{{$id}}" class="btn btn-outline-primary">PDF Invoice</a>
                        @endif

                        <button type="button" class="btn btn-outline-primary ">PDF Packing Slip</button>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold"> Customer Address </h3>
                        @if($id == '')
                        <div class="row" id="shipping_box" style="display:none">
                            <div class="col-12 mb-3" id="billing-box">
                                <h5 class="font-weight-bold">Billing <a href="#" onclick="billingAddress('billing')" class="fas fa-pencil-alt float-right" style="color:inherit;"></a><a href="#" onclick="cancelAddress('billing')" class="fas fa-window-close float-right" style="color:inherit; display: none;"></a></h5>
                                
                                <div class="viewonly">
                                    <input type="hidden" id="billing_address_val">
                                    <p class="m-0" id="billing_address"></p>
                                    <p class="m-0" id="billing_house_address"></p>
                                    <p class="m-0">
                                        <span id="billing_city"></span>
                                        <span id="billing_state"></span>
                                        <span id="billing_zip"></span>
                                    </p>
                                    <p class="" id="billing_country"></p>
                                </div>

                                <div class="add-form" style="display: none;">
                                    <form class="form-horizontal update_address" id="update_address" onsubmit="return false;"></form>
                                </div>
                            </div>
                            <div class="col-12" id="shipping-box">
                                <h5 class="font-weight-bold">Shipping <a href="#" onclick="billingAddress('shipping')" class="fas fa-pencil-alt float-right" style="color:inherit;"></a><a href="#" onclick="cancelAddress('shipping')" class="fas fa-window-close float-right" style="color:inherit; display: none;"></a></h5>
                                <div class="viewonly">
                                    <p class="m-0" id="sadd"></p>
                                    <p class="m-0" id="sadd1"></p>
                                    <p class="m-0" id="sadd2"></p>
                                    <p class="" id="sadd3"></p>
        
                                    <p class="m-0" id="shipping_address"></p>
                                    <p class="m-0" id="shipping_house_address"></p>
                                    <p class="m-0">
                                        <span id="shipping_city"></span>
                                        <span id="shipping_state"></span>
                                        <span id="shipping_zip"></span>
                                    </p>
                                    <p class="" id="shipping_country"></p>
                                </div>

                                <div class="add-form" style="display: none;">
                                    <form class="form-horizontal upd_ship_address" id="upd_ship_address" onsubmit="return false;"></form>
                                </div>
                            </div>
                        </div>
                        @else
                            @if(!empty($customer_info))
                            <div id="shipping_box" class="row">
                                <div class="col-12 mb-3" id="billing-box">
                                    <h5>Billing <a href="#" onclick="billingAddress('billing')" class="fas fa-pencil-alt float-right" style="color:inherit;"></a><a href="#" onclick="cancelAddress('billing')" class="fas fa-window-close float-right" style="color:inherit; display: none;"></a></h5>
                                    <div class="viewonly">
                                        <input type="hidden" id="billing_address_val" value="{{$customer_info->bill_st_add != null ? $customer_info->bill_st_add : ''}}">
                                        <p class="m-0" id="billing_address">{{$customer_info->bill_st_add != null ? $customer_info->bill_st_add : ''}}</p>
                                        <p class="m-0" id="billing_house_address">{{$customer_info->bill_apt_add != null ? $customer_info->bill_apt_add : ''}}</p>
                                        <p class="m-0">
                                            <span id="billing_city">{{$customer_info->bill_add_city != null ? $customer_info->bill_add_city : ''}}</span>
                                            <span id="billing_state"></span>
                                            <span id="billing_zip">{{$customer_info->bill_add_zip != null ? $customer_info->bill_add_zip : ''}}</span>
                                        </p>
                                        <p class="" id="billing_country"></p>
                                    </div>

                                    <div class="add-form" style="display: none;">
                                        <form class="form-horizontal update_address" id="update_address" onsubmit="return false;"></form>
                                    </div>
                                </div>
                                <div class="col-12" id="shipping-box">
                                    <h5>Shipping <a href="#" onclick="billingAddress('shipping')" class="fas fa-pencil-alt float-right" style="color:inherit;"></a><a href="#" onclick="cancelAddress('shipping')" class="fas fa-window-close float-right" style="color:inherit; display: none;"></a></h5>

                                    <div class="viewonly">
                                        <p class="m-0" id="sadd"></p>
                                        <p class="m-0" id="sadd1"></p>
                                        <p class="m-0" id="sadd2"></p>
                                        <p class="" id="sadd3"></p>
        
                                        <p class="m-0" id="shipping_address">{{$customer_info->address != null ? $customer_info->address : ''}}</p>
                                        <p class="m-0" id="shipping_house_address">{{$customer_info->apt_address != null ? $customer_info->apt_address : ''}}</p>
                                        <p class="m-0">
                                            <span id="shipping_city">{{$customer_info->cust_city != null ? $customer_info->cust_city : ''}}</span>
                                            <span id="shipping_state"></span>
                                            <span id="shipping_zip">{{$customer_info->cust_zip != null ? $customer_info->cust_zip : ''}}</span>
                                        </p>
                                        <p class="" id="shipping_country"></p>
                                    </div>

                                    <div class="add-form" style="display: none;">
                                        <form class="form-horizontal upd_ship_address" id="upd_ship_address" onsubmit="return false;"></form>
                                    </div>
                                </div>
                            </div>
                            @else
                                <span class="text-danger">Missing Customer Information</span>
                            @endif

                        @endif

                        <div class="loader_container" id="address" style="display:none">
                            <div class="loader"></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card">
                    <div class="card-body p-2">
                        <div class="col-md-12 form-group">
                            <input type="hidden" name="customer_id" id="customer_id">
                            <label>Customer</label>
                            <select onchange="getCustomerDetail(this.value)" class="select2 form-control " id="customer_list" name="customer_list" style="width: 100%; height:36px;">
                                <option value="0">Guest</option>
                                @if($id == '')
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                                    @endforeach
                                @else
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}" {{$order->customer_id == $customer->id ? "selected" : "-"}}>{{$customer->first_name}} {{$customer->last_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Status</label>
                            <select class="select2 form-control " id="status" name="status" style="width: 100%; height:36px;">
                                @if($id == '')
                                    @foreach($billing_statuses as $status)
                                        <option value="{{$status->id}}">{{$status->title}}</option>
                                    @endforeach
                                @else
                                    @foreach($billing_statuses as $status)
                                        <option value="{{$status->id}}" {{$order->status == $status->id ? "selected" : '-'}}>{{$status->title}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-12">
                            @if($id == '')
                            <a href="" type="button" id="payAsCustomer" class="btn btn-success text-white btn-sm rounded disabled">Pay as Customer</a>
                            @else
                            <a href="{{url('checkout')}}/{{$order->customer_id}}/{{$id}}" class="btn btn-success btn-sm rounded text-white">Pay as Customer</a>
                            @endif
                            <button type="button" onclick="publishOrder()" id="publishBtn" class="btn btn-info btn-sm rounded text-white" disabled> <i class="fas fa-check-circle"></i> Publish</button>

                            <button type="button" style="display:none" disabled id="publishing" class="btn btn-sm rounded btn-info"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" id="fees" name="fees">
        <input type="hidden" id="discount" name="discount">
        <input type="hidden" id="tax" name="tax">
        <input type="hidden" id="sub_tot" name="sub_total">
        <input type="hidden" id="total" name="total">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <select class="multiple-select mt-2 mb-2 float-right" id="inv-cols-toggle" placeholder="Show/Hide" multiple="multiple">
                                <option value="cost">Cost</option>
                                <option value="qty">Quantity</option>
                                <option value="total">Total</option>
                                <option value="disc">Discount</option>
                                <option value="msrp">MSRP</option>
                                <option value="fee">Fee</option>
                                <option value="tax">tax</option>
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderd" id="item_table">
                                <thead>
                                    <tr>
                                        <th width="50%">Item</th>
                                        <th width="10%">Extras</th>
                                        <th width="10%" class="tgls cost-tgl">Cost</th>
                                        <th width="10%" class="tgls qty-tgl">Quantity</th>
                                        <th width="15%" class="tgls ttl-tgl text-right">Total</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="result">

                                </tbody>
                            </table>
                            <a href="javascript:void(0)" class="btn btn-sm rounded btn-success ml-2" onclick="addItemRow()"> <i class="mdi mdi-plus-circle"></i> Add Item</a>
                        </div>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <form action="billing_notes">
                                    <div class="form-group">
                                        <textarea id="notes" placeholder="Order Notes" cols="30" rows="5" class="form-control"></textarea>
                                        <span id="notes_error" class="text-danger"></span>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-9">
                                        <p class="float-right mb-0 font-weight-bold">Total :</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex">
                                            <span class="mt-1 mr-1"><?php echo $currency_symbol; ?></span>
                                            <p class="float-right pr-2 mb-0 font-weight-bold" id="sub_total">0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <p class="float-right mb-0">Fees :</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex">
                                            <span class="mt-1 mr-1"><?php echo $currency_symbol; ?></span>
                                            <p class="float-right pr-2 mb-0" id="fees_show">0.00</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-9">
                                        <p class="float-right mb-0">Discount :</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex">
                                            <span class="mt-1 mr-1"><?php echo $currency_symbol; ?></span>
                                            <p class="float-right pr-2 mb-0" id="discount_show">0.00</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-9">
                                        <p class="float-right mb-0">Tax :</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex">
                                            <span class="mt-1 mr-1"><?php echo $currency_symbol; ?></span>
                                            <p class="float-right pr-2 mb-0" id="tax_show">0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-9">
                                        <b class="float-right">Grand Total :</b>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex">
                                            <span class="mt-2 mr-1"><?php echo $currency_symbol; ?></span>
                                            <p class="float-right font-weight-bold lead pr-2 full_total" id="full_total">0.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- <button id="customer_add_btn" data-toggle="modal" data-target="#addItemModal" class="btn btn-success btn-sm rounded">
                                    <i class="mdi mdi-plus-circle"></i> Add Products </button> -->
                            </div>
                            <div class="col-md-6">
                                <button type="button" style="display:none" disabled id="processbtn" class="btn btn-success btn-sm rounded float-right"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                                <button type="submit" id="savebtn" class="btn btn-success btn-sm rounded float-right"> <i class="fas fa-save"></i> Save </button>
                                <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-success btn-sm rounded float-right mr-2 tgls" id="add_fees"> <i class="fas fa-plus-circle"></i> Fees </button>

                                <button type="button" data-toggle="modal" data-target="#discountModal" class="btn btn-success btn-sm rounded float-right mr-2 tgls" id="add_discount"> <i class="fas fa-plus-circle"></i> Discount </button>

                                <button type="button" data-toggle="modal" data-target="#taxModal" class="btn btn-success btn-sm rounded float-right mr-2 tgls" id="add_tax"> <i class="fas fa-plus-circle"></i> Tax </button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card">
            <div class="card-body">
                <div class="col-12">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                            </div>
                        </div>

                        <table id="ticket-logs-list" class="table table-striped table-bordered no-wrap ticket-table-list w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>TicketID</th>
                                    <th>Activity</th>
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
</form>

<!--  add item modal  -->
<div class="modal fade" id="addItemModal" role="dialog" data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Item</h4>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="add_item_form">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <fieldset>
                                <div class="row">
                                    <div class="col-8 form-group">
                                        <label>Item</label> <br>
                                        <select name="product" id="product" class="select2" style="width:100%">
                                            <option value="ads">adsfafd</option>
                                            <option value="ads">adsfafd</option>
                                            <option value="ads">adsfafd</option>
                                            <option value="ads">adsfafd</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="qty">Quantity</label>
                                        <input type="number" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="text-right mt-2">
                                <button type="button" class="btn waves-effect waves-light btn-success">
                                    Add</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- add fees modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Fees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fees_form" onsubmit="return false">
                    <div class="form-group">
                        <label for="fees">Fees</label>
                        <input type="text" class="form-control" id="fees_field">
                        <span id="error" class="text-danger small"></span>
                    </div>
                    <button onclick="saveFees()" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- add discount modal -->
<div class="modal fade" id="discountModal" tabindex="-1" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Fees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="discount_form" onsubmit="return false">
                    <div class="form-group">
                        <label for="fees">Discount</label>
                        <input type="text" class="form-control" id="discount_field">
                        <span id="discount_error" class="text-danger small"></span>
                    </div>
                    <button onclick="saveDiscount()" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- add tax modal -->
<div class="modal fade" id="taxModal" tabindex="-1" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Tax</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tax_form" onsubmit="return false">
                    <div class="form-group">
                        <label for="fees">Tax</label>
                        <input type="text" class="form-control" id="tax_field">
                        <span id="tax_error" class="text-danger small"></span>
                    </div>
                    <button onclick="saveTax()" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!--<script src="theme_assets/libs/jsgrid/db.js"></script>-->
@endsection
@section('scripts')
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<!-- <script src="{{asset('public/js/invoice_maker/invoice_maker.js').'?ver='.rand()}}"></script> -->
@include('js_files.invoice_maker.indexJs')
@include('js_files.invoice_maker.invoice_makerJs')


@endsection