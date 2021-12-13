@extends('layouts.staff-master-layout')
@section('body-content')
<style>
    .btn-list>a:hover {
        color: #009efb;
    }
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">System Manager</li>
                        <li class="breadcrumb-item active" aria-current="page">Integrations</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Left Part -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-3 col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="p-3 " card-title="">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search ...">
                    </div>

                    <div class="dd myadmin-dd" id="nestable-menu">
                        <ol class="dd-list">

                            @foreach ($categories as $item)
                            <li class="dd-item integrations-menu {{ $loop->index == 0 ? 'bg-dark text-white': ''}}" data-id="integration-{{$item->id}}">
                                <div class="dd-handle">{{$item->title}}</div>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @foreach ($categories as $item)
            <div class="row integrations-content {{ $loop->index > 0 ? 'd-none' : ''}}" id="integration-{{$item->id}}">
                @foreach($item->integrations as $integration)
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body position-relative">
                            <i class="fas fa-cog mr-2 mt-2" onclick="showFolderModel({{ json_encode($integration) }})" style="position: absolute; right: 0; top: 0;font-size: 16px;cursor:pointer;"></i>
                            <div class="mr-2 mt-2" style="position: absolute; right: 0; top: 25px;">
                                <input type="checkbox" data-id="{{ $integration->id }}" {{ ($integration->status=='0') ? '' : 'checked' }} name="status" class="js-switch" onchange="integrationStatus(this)">
                            </div>
                            <h2 class="mt-0">
                                <!-- i just change icon right 10 or top 10  -->
                                <img src="assets/images/{{$integration->image}}" style="width: 45px;height: 45px;">

                            </h2>
                            <h6>{{$integration->name}}</h6>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        <!-- Modal -->
    </div>

    <div id="add-integration" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="text-left bg-info p-3">
                    <button type="button" class="close ml-auto" data-dismiss="modal" style="color:#fff;" aria-hidden="true">×</button>
                    <a class="text-success">

                        <span class="d-flex align-items-center"><img class="mr-2 mb-2 integration-logo" src="https://mylive-tech.com/framework/files/brand_files/logo.png" alt="" height="50">
                            <h2 style="color:#fff" class="integration-title">Add API Settings</h2>
                        </span>


                    </a>
                </div>
                <div class="modal-body">
                    <form id="save-details" class="widget-box widget-color-dark user-form" method="POST" action="{{asset('integrations')}}">
                        @csrf
                        <div class="form-group" id="api_url">
                            <label for="api_url">API URL</label>
                            <input type="url" class="form-control" name="api_url">
                        </div>
                        <div id="conent-body">

                        </div>
                        <input type="hidden" id="google_verification" value="1">
                        <input type="hidden" name="integration_id" id="integration_id">
                        <div class="form-group d-flex justify-content-between">
                            <button type="button" class="btn btn-rounded btn-info btn-sm rounded" id="verify"><i class="fas fa-check"></i> verify</button>
                            <button type="submit" class="btn btn-rounded btn-success btn-sm rounded" id="btn-save" > <i class="fas fa-save"></i> Save </button>
                        </div>
                    </form>
                    
                </div>

                <div class="loader_container" id="form_loader" style="display:none">
                    <div class="loader"></div>
                </div>

            </div>
        </div>
    </div>




    <!-- wordpress -->
    <div class="modal fade" id="wordPress" data-backdrop="static" tabindex="-1" aria-labelledby="wordPressLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="wordPressLabel"> WordPress </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="box-shadow: 0 13px 10px 0px rgb(0 0 0 / 0%);">
                                <div class=" wizard-content">
                                    <form id="watch_form" class="tab-wizard wizard-circle">
                                    @csrf
                                        <input type="hidden" name="integration_id" id="wordpress_id">

                                        <div class="alert alert-success alert-dismissible fade show" id="alertmsg" style="display:none" role="alert">
                                            <strong id="msg"></strong>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        
                                        <div class="alert alert-success alert-dismissible fade show" id="alertbar" role="alert" style="display:none">
                                            <strong id="show_msg">Holy guacamole!</strong>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        
                                        <section id="first">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group" id="api_url">
                                                        <label for="api_url">API URL</label>
                                                        <input type="url" class="form-control" id="api_url_field">
                                                        <span class="text-danger small" id="url_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="consumer_key">Consumer Key</label>
                                                        <input type="text" class="form-control" id="consumer_key" name="consumer_key">
                                                        <span class="text-danger small" id="key_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="consumer_secret">Consumer secret</label>
                                                        <input type="text" class="form-control" id="consumer_secret" name="consumer_secret">
                                                        <span class="text-danger small" id="secret_error"></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="">Enable any of these for their respective functions.</label>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheckCustomers">
                                                        <label class="custom-control-label" for="customCheckCustomers">Customers</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheckOrders">
                                                        <label class="custom-control-label" for="customCheckOrders">Orders</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheckProducts">
                                                        <label class="custom-control-label" for="customCheckProducts">Products</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheckSubscriptions">
                                                        <label class="custom-control-label" for="customCheckSubscriptions">Subscriptions</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <span class="text-danger">This only works if WooCommerce is installed.</span>
                                                </div>
                                            </div>

                                            <div class="form-group mt-2">
                                                <a href="#" id="verifyBtn" class="btn btn-primary btn-sm rounded"> <i class="fas fa-check"></i> Verifiy</a>
                                                <button type="button" onclick="getWpCustomers(1)" id="submitBtn" class="btn btn-success btn-sm rounded" style="float:right;"> <i class="fas fa-sync"></i> Sync</button>
                                                <button style="display:none;float:right" id="processing" class="btn btn-sm btn-success rounded" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> </button>
                                            </div>
                                        </section>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="loader_container wp_loader" style="display:none">
                <div class="loader"></div> <br>
                <strong style="position: absolute;bottom: 100px;" class="loadertext"></strong>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('js_files.system_manager.integrations.indexJs')

@endsection