@extends('layouts.staff-master-layout')
<!-- This page CSS -->
<link href="{{asset('assets/extra-libs/jquery-steps/jquery.steps.css')}}" rel="stylesheet">
<link href="{{asset('assets/extra-libs/jquery-steps/steps.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/dropify-master/dist/css/dropify.min.css')}}">

<style>
@media (min-width: 1200px){
.modal-xl {
    max-width: 1140px !important;
}
}
    .plugName {
        font-size: 14px;
        font-weight: 600;
        color: blue;
    }

    .metaplus {
        font-size: 13px;
    }

    .ids {
        font-size: 10px;
        color: grey;
    }

    .edibles {
        padding-left: 3px;
        border-left: 1px solid grey;
        font-size: 14px;
        margin-left: 3px;
    }
    .wizOption label{
        padding-top:7px;
    }
    .progress-xxl{
        height: 30px !important;

    }
    .jin .dropify-wrapper{
        height:117px;
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
                        <li class="breadcrumb-item active" aria-current="page">Product Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-bottom border-success">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2 id="totalProducts"></h2>
                                                <h6 class="text-success">Total Products</h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-success display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card border-bottom border-info">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>#</h2>
                                                <h6 class="text-info">Value Name </h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-info display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-bottom border-info">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>7</h2>
                                                <h6 class="text-info">New Items</h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-info display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card border-bottom border-info">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>#</h2>
                                                <h6 class="text-info">Value Name </h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-info display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-bottom border-success">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>7</h2>
                                                <h6 class="text-success">Low Stocks</h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-success display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card border-bottom border-success">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>#</h2>
                                                <h6 class="text-success">Value Name </h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-success display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-bottom border-danger">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>7</h2>
                                                <h6 class="text-danger">Pending Updates</h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-danger display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card border-bottom border-danger">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>#</h2>
                                                <h6 class="text-danger">Value Name </h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-danger display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-bottom border-warning">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>7</h2>
                                                <h6 class="text-warning">Apx Items Value </h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-warning display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card border-bottom border-warning">
                                <div class="card-body ">
                                    <a href="http://localhost/framework/staff-manager">
                                        <div class="d-flex no-block align-items-cente">
                                            <div>
                                                <h2>#</h2>
                                                <h6 class="text-warning">Value Name </h6>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="text-warning display-6"><i class="ti-notepad"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-success" data-toggle="modal" data-target="#launchWizard">
                                Launch Import Wizard
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <h4 class="card-title">
                        Products
                    </h4>

                    <div class="col-md-12 text-right mb-2">
                        <button class="btn btn-primary btn-sm rounded" data-toggle="modal" data-target="#addNewProduct"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp; Add New Product </button>
                        <button class="btn btn-success btn-sm rounded" data-toggle="modal" data-target="#importProduct"> Import Product </button>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="pro-type-table-list" class="table table-striped table-bordered no-wrap w-100 asset-temp-table-list">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="text-center">
                                                <input type="checkbox" id="checkAll" name="assets[]" value="0">
                                            </div>
                                        </th>
                                        <th class="text-center"><i class="fas fa-image"></i></th>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Specs</th>
                                        <th>Sku</th>
                                        <th>Price</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="proTypes">
                                     
                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Create Feed & Download</h5>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ullam suscipit excepturi ratione ut laudantium, ea, necessitatibus quo aliquid et sequi, magni omnis sint molestiae corporis odio? Laudantium soluta quos debitis consequatur quidem. Reprehenderit, quod.</p>
                                <ul style="list-style-type:none;">
                                    <li>Feed for RESELLERS NAME | Created On TIMESTAMP | By STAFFERS NAME | Contains X# Items | Feed URL(copy) | File 
                                        <a href="#" style="color:inherit;" ><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" style="color:inherit;"><i class="fas fa-trash-alt"></i></a>
                                        <a href="#" style="color:inherit;"><i class="fas fa-sync"></i></a> 
                                    </li>
                                    <li>Feed for RESELLERS NAME | Created On TIMESTAMP | By STAFFERS NAME | Contains X# Items | Feed URL(copy) | File 
                                        <a href="#" style="color:inherit;" ><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" style="color:inherit;"><i class="fas fa-trash-alt"></i></a>
                                        <a href="#" style="color:inherit;"><i class="fas fa-sync"></i></a> 
                                    </li>
                                    <li>Feed for RESELLERS NAME | Created On TIMESTAMP | By STAFFERS NAME | Contains X# Items | Feed URL(copy) | File 
                                        <a href="#" style="color:inherit;" ><i class="fas fa-cloud-download-alt"></i></a>
                                        <a href="#" style="color:inherit;"><i class="fas fa-trash-alt"></i></a>
                                        <a href="#" style="color:inherit;"><i class="fas fa-sync"></i></a> 
                                    </li>
                                </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add new certificate model -->
<div id="addNewProduct" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <div class="modal-content">
                <div class="modal-header">
                    <h3 class="blue" style="color:#009efb;">Select Product Type</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Product Type</label>
                            <select class="select2 form-control" id="product_type" name="" style="width: 100%; height:36px;">
                                <option value="">Choose....</option>
                                <option value="hard-goods">Hard Goods Product </option>
                                <option value="digital-goods ">Digital Goods Product </option>
                            </select>
                            <span class="small text-danger" id="product_type_error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success btn-sm rounded" id="proTypeClick" > Go <i class="fas fa-arrow-circle-right"></i></button>
                </div>
        </div>
    </div>
</div>

<!-- Launch Wizard model -->
<div id="launchWizard" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="blue" style="color:#009efb;">Import Wizard</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row " style="padding-top:7px;">
                                <div class="form-check ml-3">
                                    <input class="form-check-input" type="radio" name="startingQuiz" id="quick" value="quick" checked="">
                                    <label class="form-check-label" for="quick"> Quick Start (Recommended) </label>
                                </div>
                                <div class="form-check ml-3">
                                    <input class="form-check-input " type="radio" name="startingQuiz" value="advance" id="advance">
                                    <label class="form-check-label" for="advance"> Advanced Mode </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary">
                            Download Example Import File
                        </button>
                    </div>
                    <div class="col-md-12 " id="quickStart" >
                        <hr>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem soluta, nemo odio id optio possimus doloribus maxime laborum omnis aut sit qui rerum libero cum mollitia? Ad et dolores placeat dicta sunt.</p>

                        <div class="row mt-5">
                            <div class="col-md-4">
                                <div class="form-group col-md-12">
                                    <div class="row ">
                                        <label class="col-md-5">Choose the type of product you wish to Import</label>
                                        <div class="col-md-7">
                                            <select name="" id="" class=" form-control  ">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="file" id="dropi-0" data-show-errors="true" onchange="loadFile(this);" data-max-file-size="2M"/>
                                </div>
                                <div class="col-md-12 mt-3" >
                                        <ul class="p-4" style=" border:1px solid #000;">
                                            <li>File Type Detected: CSV Found</li>
                                            <li>Issues Found: 03</li>
                                            <li>Line 3 has unknown format</li>
                                            <li>Line 10 is missing all values</li>
                                            <li>LINE X -ISSUE TO DISPLAY</li>
                                        </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis! psum dolor sit amet consectetur adipisicing elit. Debitis</p>
                                <p>Lorem ipsum dolor sit amet conitis</p>

                                <div class="form-group col-md-12">
                                    <div class="row wizOption">
                                        <label class="col-sm-4">Item Name</label>
                                        <div class="col-sm-8">
                                            <select class=" form-control " >
                                                <option value="">Select</option>
                                            </select>
                                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row wizOption">
                                        <label class="col-sm-4">Item Name</label>
                                        <div class="col-sm-8">
                                            <select class=" form-control " >
                                                <option value="">Select</option>
                                            </select>
                                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row wizOption">
                                        <label class="col-sm-4">Item Name</label>
                                        <div class="col-sm-8">
                                            <select class=" form-control " >
                                                <option value="">Select</option>
                                            </select>
                                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row wizOption">
                                        <label class="col-sm-4">Item Name</label>
                                        <div class="col-sm-8">
                                            <select class=" form-control " >
                                                <option value="">Select</option>
                                            </select>
                                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row wizOption">
                                        <label class="col-sm-4">Item Name</label>
                                        <div class="col-sm-8">
                                            <select class=" form-control " >
                                                <option value="">Select</option>
                                            </select>
                                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row wizOption">
                                        <label class="col-sm-4">Item Name</label>
                                        <div class="col-sm-8">
                                            <select class=" form-control " >
                                                <option value="">Select</option>
                                            </select>
                                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row wizOption">
                                        <label class="col-sm-4">Item Name</label>
                                        <div class="col-sm-8">
                                            <select class=" form-control " >
                                                <option value="">Select</option>
                                            </select>
                                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Preview Your Imports</h4>
                                        <hr>
                                    </div>
                                    <div class="col-md-12 mt-3" >
                                        <ul class="p-4" style=" border:1px solid #000;">
                                            <li>File Type Detected: CSV Found</li>
                                            <li>Issues Found: 03</li>
                                            <li>Line 3 has unknown format</li>
                                            <li>Line 10 is missing all values</li>
                                            <li>LINE X -ISSUE TO DISPLAY</li>
                                        </ul>
                                        
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-success rounded ">
                                            <i class="fas fa-check-square"></i> Start Import
                                        </button>   
                                    </div>
                                    <div class="col-md-12">
                                        <div class="progress progress-xxl mt-4 mb-4">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 38%" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100" style="font-size:17px;">
                                                XX% COMPLETED
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Mrrupti?</p>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="row ">
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control">  
                                            </div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-success">Confirm</button>
                                            </div>
                                            
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 " id="advanceStart">
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <div class="row pt-4">
                                    <label class="col-md-5">Choose the type of product you wish to Import</label>
                                    <div class="col-md-4">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 jin" >
                                <input type="file" id="dropi-01" data-show-errors="true" onchange="loadFile(this);" data-max-file-size="2M" />
                            </div>
                            
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <label for="" class="col-md-2">Upload Header</label>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="row">
                                    <label for="" class="col-md-2">System Headers</label>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="" id="" class=" form-control  ">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3 mb-3">
                                <div class="table-responsive">
                                    <table id="asset-temp-table-list" class="table table-striped table-bordered no-wrap w-100 asset-temp-table-list">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-center">
                                                        <input type="checkbox" id="checkAll" name="" value="0">
                                                    </div>
                                                </th>
                                                <th>Name</th>
                                                <th>SKU</th>
                                                <th>Stock</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td>
                                                <div class="text-center">
                                                    <input type="checkbox" id="checkAll" name="" value="0">
                                                </div>
                                            </td>
                                            <td>Test003</td>
                                            <td>Test003</td>
                                            <td>Test003</td>
                                            <td>Test003</td>
                                            
                                        </tbody>

                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="row  ">
                            <div class="col-md-4">
                                <button class="btn btn-success mt-2"> Remove HTML </button>
                                <button class="btn btn-danger mt-2">Remove Duplicates</button>
                                <button class="btn btn-info mt-2">Duplicate Selected</button>
                                <button class="btn btn-warning mt-2">Duplicate Selected</button>
                            </div>

                            <div class="col-md-8 text-right">
                                <div class="form-group col-md-12">
                                    <div class="row ">
                                        <div class="col-md-4">
                                        
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" class="form-control">  
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-success">Confirm</button>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button class="btn btn-success">Validate Items</button>
                                        </div>
                                        <div class="col-md-6 text-left">
                                            <ul style="list-style-type:none;">
                                                <li>100 items will be imported</li>
                                                <li>2 items will be skipped due to error</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success">
                                                <i class="fas fa-check-square"></i> Start Imports
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button class="btn btn-success btn-sm rounded" onclick="templatePage()"> Go <i class="fas fa-arrow-circle-right"></i></button>
            </div> -->
        </div>
    </div>
</div>


<script src="{{asset('assets/extra-libs/dropify-master/dist/js/dropify.js')}}"></script>



@endsection
@section('scripts')
    @include('js_files.marketing.product_manager.indexJs')
@endsection
