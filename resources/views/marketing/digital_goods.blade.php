@extends('layouts.staff-master-layout')
<!-- This page CSS -->
<link href="{{asset('assets/extra-libs/jquery-steps/jquery.steps.css')}}" rel="stylesheet">
<link href="{{asset('assets/extra-libs/jquery-steps/steps.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/dropify-master/dist/css/dropify.min.css')}}">
<style>
    .text-red{
        color:red;
        font-weight:500;
    }
    .featImage{
        padding-top: 15px;
    height: 147px;
    }
</style>

@section('body-content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Marketing</li>
                        <li class="breadcrumb-item active" aria-current="page">Digital Goods Template</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <form  id="addProducts">    
        <input type="hidden" id="is_type" name="is_type" value="{{$id}}">
        <input type="hidden" id="is_submit" name="is_submit" >
        <div class="row">
        
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                    <h4 id="ptitle" >Product Title</h4>
                    <input type="hidden" id="title" name="title">
                    <p>Permalink: mailkdejbh_mylivetech.com <a href="#" class="text-dark"><i class="fas fa-pencil-alt "></i></a></p>
                    </div>
                    <div class=" col-md-12 pb-5">

                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Long Description</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile" data-toggle="tab" aria-expanded="true" class="nav-link">
                                    <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Specs</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link ">
                                    <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Extra Detail</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#custom" data-toggle="tab" aria-expanded="false" class="nav-link ">
                                    <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Add Custom Tab <i class="fas fa-plus"></i></span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                    <label for="" class="text-red">Standard TinyMCE here</label>
                                    <textarea name="long_desc" id="long_desc" class="form-control " rows="10"></textarea>
                            </div>
                            <div class="tab-pane show" id="profile">
                                    <label for="" class="text-red">Standard TinyMCE here</label>
                                    <textarea name="specs" id="specs" class="form-control " rows="10"></textarea>
                            </div>
                            <div class="tab-pane " id="settings">
                                    <label for="" class="text-red">Standard TinyMCE here</label>
                                    <textarea name="extra_details" id="extra_details" class="form-control " rows="10"></textarea>
                            </div>
                            <div class="tab-pane " id="custom">
                                    <label for="" class="text-red">Standard TinyMCE here</label>
                                    <textarea name="" id="" class="form-control " rows="10"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="pt-3">Sell As a Subscription</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="sell_as_sub_enable" name="sell_as_sub">
                                        <label class="custom-control-label" for="sell_as_sub_enable">Enable</label>
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="sell_as_sub_disable" name="sell_as_sub">
                                        <label class="custom-control-label" for="sell_as_sub_disable">Disable</label>
                                    </div>
                                </div>
                                <!-- <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked">
                                        <label class="custom-control-label" for="customControlValidation3">2</label>
                                    </div>
                                </div> -->
                            </div>

                            <div class="col-md-12 form-row pt-5">
                                <label for="" class="col-sm-3 pt-2">How Often?</label>
                                <input type="text " class="form-control col-sm-4" name="how_often_quantity" id="how_often_quantity" placeholder="5">
                                <select type="text " name="how_often_term" id="how_often_term" class="form-control col-sm-3 ml-3">
                                    <option value="">
                                        Term
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12 form-row ">
                                <label for="" class="col-sm-3 pt-2">Requires Shipping?</label>
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="shipping_yes" name="shipping">
                                        <label class="custom-control-label" for="shipping_yes">Yes</label>
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="shipping_no" name="shipping">
                                        <label class="custom-control-label" for="shipping_no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>Listing Details</h4>
                        <p>Created By: Name  <a href="#" class="text-dark" style="float:right;"><i class="fas fa-trash"></i></a></p> 
                        <p>Created On: 27-Jan-2021  </p> 
                        <p>Status: -------  </p> 
                        <p>Revision: WXXX  </p> 
                        <div class="text-right">
                            <button class="btn btn-success" id="publish" type="button"> Publish </button>
                            <button class="btn btn-info" id="draft" type="button"> Save Draft </button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4>Short Description</h4>
                        <label for="" class="text-red">Standard TinyMCE here</label>

                        <textarea name="short_desc" id="short_desc"  class="form-control col-md-12 mb-3"></textarea>
                        <div class="text-right">
                            <button class="btn btn-success"> Publish </button>
                            <button class="btn btn-info"> Save Draft </button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4>Sync With</h4>
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="Amazone" name="Amazone">
                                <label class="custom-control-label" for="Amazone"> Amazone </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="Bonanza" name="Bonanza">
                                <label class="custom-control-label" for="Bonanza"> Bonanza </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="E-Bay" name="E-Bay">
                                 <label class="custom-control-label" for="E-Bay"> E-Bay </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="New-Egg" name="New-Egg">
                                <label class="custom-control-label" for="New-Egg"> New-Egg </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="Wallmart" name="Wallmart">
                                <label class="custom-control-label" for="Wallmart"> Wallmart </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Product Properties</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <input type="text" class=" form-control mb-3 hard_goods_field" id="upc_gtin" name="upc_gtin" placeholder="UPC# / GTIN#" >
                                        <input type="text" class=" form-control " id="isbn" name="isbn" placeholder="ISBN# for books only" >
                                        <input type="text" class=" form-control mt-3 hard_goods_field" placeholder="Part Number" id="part_no" name="">
                                        <input type="text" class=" form-control mt-3" placeholder="Brand Name" id="brand_no" name="brand_no">
                                        <input type="text" class=" form-control mt-3" placeholder="SKU" id="sku" name="sku">
                                        <input type="text" class=" form-control mt-3" placeholder="Internal ID" id="internal_id" name="internal_id">



                                    </div>
                                    <div class="col-md-6 digital_goods_field">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="has_down" name="has_down">
                                            <label class="custom-control-label" for="has_down">  Has File Download </label>
                                        </div>
                                        <div class="input-group mb-3 mt-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="inputGroupFile01">
                                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-checkbox mt-3">
                                            <input type="checkbox" class="custom-control-input" id="Restrictions">
                                            <label class="custom-control-label" for="Restrictions">  Require Restrictions </label>
                                        </div>
                                        <select name="allow_download_for" id="allow_download_for" class="form-control mt-3">
                                            <option value="Allow Download for">Allow Download for</option>
                                            <option value="30 Days">30 Days</option>
                                            <option value="60 Days">60 Days</option>
                                            <option value="90 Days">90 Days</option>
                                            <option value="Do not Appear in Account">Do not Appear in Account</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 hard_goods_field">
                                        <div class="row form-group">
                                            <div class="col-md-12">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="in_stock" name="in_stock">
                                                    <label class="custom-control-label" for="stock">  How Many in Stock? </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <input type="number" class=" form-control" id="stock_quantity" name="stock_quantity" placeholder="$xx.xx">
                                            </div>                            
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-12">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_stock_on_mul_loc" name="is_stock_on_mul_loc">
                                                    <label class="custom-control-label" for="stock">  Is stock in more than 1 location? </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <input type="text" class=" form-control" placeholder="Location 2 Optional" id="stock_loc_2" name="stock_loc_2">
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <input type="text" class=" form-control" placeholder="Location 3 Optional" id="stock_loc_3" name="stock_loc_3">
                                            </div>                             
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" >
                                            <label class="custom-control-label" for="setup_fee_required">  Require Setup Fee </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class=" form-control" placeholder="$xx.xx" id="setup_fee_required" name="setup_fee_required">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="has_special_con" id="has_special_con">
                                            <label class="custom-control-label" for="setupFee">  Has Special Condition </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <textarea name="special_condition_text" id="special_condition_text" class="form-control"  rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <label for="" class="col-sm-4" data-toggle="tooltip" data-placement="top" title="This is the price your company pays the supplier for the item. This will not be displayed to customers. This will show only to admin staff of your company.">Vendor Pricing</label>
                                    <input for="" class="col-sm-5 form-control" id="vendor_price" name="vendor_price">
                                </div>
                                <div class="row form-group">
                                <label for="" class="col-sm-4" data-toggle="tooltip" data-placement="top" title="Optional put a discounted rate the customer will see">My Sale Price</label>
                                    <input for="" class="col-sm-5 form-control" id="oor_sale_price" name="oor_sale_price">
                                    <select name="" id="" class="form-control col-sm-3">
                                        <option value="">Till?</option>
                                    </select>
                                </div>
                                <div class="row form-group">
                                <label for="" class="col-sm-4" data-toggle="tooltip" data-placement="top" title="This is the rate your item will sell for when not on sale at discounted rate.">My Regular Price</label>

                                    <input for="" class="col-sm-5 form-control" id="oor_regular_price" name="oor_regular_price">
                                </div>
                                <div class="row form-group">
                                <label for="" class="col-sm-4" data-toggle="tooltip" data-placement="top" title="This is the cost the menufacture suggests should be sold at">MSRP</label>
                                    <input for="" class="col-sm-5 form-control" id="msrp" name="msrp">
                                </div>
                                <div class="row form-group">
                                <label for="" class="col-sm-4" data-toggle="tooltip" data-placement="top" title="This is special discounted rrate to sell your  items to resellers. Requires customer users to have Wholesale role added first before they will see pricing.">Wholesale</label>
                                    <input for="" class="col-sm-5 form-control" id="wholesale_price" name="wholesale_price">
                                </div>

                                <div class="row form-group">
                                    <label for="" class="col-sm-12"> Shopping Details</label>
                                    <input type="text" class="col-sm-3 ml-3 form-control" name="length" id="length" placeholder="Length">
                                    <input type="text" class="col-sm-3 ml-2 form-control" name="width" id="width" placeholder="Width">
                                    <input type="text" class="col-sm-3 ml-2 form-control" name="height" id="height" placeholder="Height">
                                    <input type="text" class="col-sm-10 mt-2 ml-3 form-control" name="weight" id="weight" placeholder="Weight 15lbs">
                                </div>
                                <div class="row form-group ml-2">
                                    
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="shipping_type" id="USPS">
                                            <label class="custom-control-label" for="USPS">USPS</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="shipping_type" id="FEDEX">
                                            <label class="custom-control-label" for="FEDEX">FedEX</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="shipping_type" id="UPS" >
                                            <label class="custom-control-label" for="UPS">UPS</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="shipping_type" id="Other" >
                                            <label class="custom-control-label" for="Other">Other</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="pt-3">Advance Settings</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="advSetting2" name="radio-stacked2">
                                        <label class="custom-control-label" for="advSetting2">Enable</label>
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="advSetting3" name="radio-stacked2">
                                        <label class="custom-control-label" for="advSetting3">Disable</label>
                                    </div>
                                </div>
                                <!-- <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked">
                                        <label class="custom-control-label" for="customControlValidation3">2</label>
                                    </div>
                                </div> -->
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4 " style="border-bottom:1px solid #eee;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="blockBot">
                                    <label class="custom-control-label" for="blockBot"> Block Bot </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="seoIndex">
                                    <label class="custom-control-label" for="seoIndex"> Block SEO Indexing </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="addon">
                                    <label class="custom-control-label" for="addon"> Sell as addon Only - (select box) </label>
                                </div>
                                <div class="custom-control custom-checkbox hard_goods_field">
                                    <input type="checkbox" class="custom-control-input" id="addon">
                                    <label class="custom-control-label" for="addon"> Assign Extra Email after Purchase</label>
                                </div>
                                <div class="extraEmail hard_goods_field">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4" style="border-bottom:1px solid #eee;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="pass">
                                    <label class="custom-control-label" for="pass"> Require Password To View </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="selectAcc">
                                    <label class="custom-control-label" for="selectAcc"> View By Select Accounts </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="Restrict">
                                    <label class="custom-control-label" for="Restrict"> Restrict Purchase to only (select box) </label>
                                </div>
                                <div class="col-md-12">
                                <small class="" for=""> To customize email template click here to go to settings <a href="#"> LINK </a></small>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-md-12">
                                    <label for="" class=" col-sm-12 pl-0"> <h4 class="mb-0">Assign Provisioning Policy</h4></label>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="policy2" name="radio-stacked3">
                                            <label class="custom-control-label" for="policy2">Yes</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="policy3" name="radio-stacked3">
                                            <label class="custom-control-label" for="policy3">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 row mt-3">
                                    <select name="" id="" class="form-control col-sm-6">
                                        <option value="">Pick Policy </option>
                                    </select>
                                    <button class="btn btn-success col-sm-5 ml-2"><i class="fas fa-plus"></i> Add New</button>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <h4 class="col-md-12">Custom Fields</h4>
                            <div class="col-md-3">
                                <input type="text" placeholder="Field Name" class="form-control">
                            </div>
                            <div class="col-md-3">
                            <input type="text" placeholder="Field Type" class="form-control">

                            </div>
                            <div class="col-md-3">
                                <input type="text" placeholder="Notes" class="form-control">

                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success"> Add </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="pt-3">Advance Settings</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="advSetting2" name="radio-stacked2">
                                        <label class="custom-control-label" for="advSetting2">Enable</label>
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="advSetting3" name="radio-stacked2">
                                        <label class="custom-control-label" for="advSetting3">Disable</label>
                                    </div>
                                </div>
                                <!-- <div class="form-check form-check-inline">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked">
                                        <label class="custom-control-label" for="customControlValidation3">2</label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                            <input type="file"  data-show-errors="true" id="feature_image" name="feature_image" onchange="loadFile(this);" data-max-file-size="2M"/>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5>Featured Image</h5>
                                <img src="../assets/images/wordpress.png" alt="" style="max-height:172px;width:auto;">
                            </div>
                            <div class="col-md-7">
                                <div class="col-md-12">
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="zoomIn">
                                            <label class="custom-control-label" for="zoomIn">Enable Zoom on</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="waterMark">
                                            <label class="custom-control-label" for="waterMark">Enforce Watermark</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <input type="file" data-show-errors="true" id="feature_video" name="feature_video" onchange="loadFile(this);" data-max-file-size="2M"/>
                                <h5 class="text-center">OR</h5>
                                <input type="url" id="video_link" name="video_link" class="form-control" placeholder="Add Youtube Link or vid Link">
                            </div>
                            <div class="col-md-2 text-center pt-5">
                                <h5>Featured Video</h5>
                                <img src="../assets/images/wordpress.png" alt="" style="max-height:172px;width:auto;">
                            </div>
                            <div class="col-md-7 pt-5">
                                <div class="col-md-12">
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="playList">
                                            <label class="custom-control-label" for="playList">Hide Playlist At End of the Video</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="newTab">
                                            <label class="custom-control-label" for="newTab">Open In Tab</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                    <a href="#">
                                        <img src="../assets/images/ssl.png" alt="" class=" featImage" style="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
{{-- Dropify Files --}}
<script src="{{asset('assets/extra-libs/dropify-master/dist/js/dropify.js')}}"></script>

@endsection
@section('scripts')
    @include('js_files.marketing.digital_goodsJs')

@endsection
