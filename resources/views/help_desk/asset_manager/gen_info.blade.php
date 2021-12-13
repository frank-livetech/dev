@extends('layouts.staff-master-layout')
@section('body-content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .table td, .table th {
        padding: .35rem !important;
    }
    .field-icon {
        margin-top:-20px !important;
    }
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('asset_manager.index')}}"> Asset Manager </a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$asset->asset_title}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">   
    <div class="row card">
        <div class="col-lg-12 col-md-12 col-sm-12 card-body">

            <h4 class="card-title">General Info &nbsp;&nbsp;<span id="ticket-timestamp" style="font-size:12px; font-weight:400;"></span>
                <span style="float:right;cursor:pointer" title="Edit Initial Request" id="edit_request_btn">
                    <a onclick="editRequest()"><i class="mdi mdi-lead-pencil"></i></a>
                </span>
                
                <span style="float:right;cursor:pointer;display:none" title="Cancel" id="cancel_request_btn">
                    <a onclick="cancelEditRequest()"><i class="mdi mdi-window-close text-danger " style="margin-left:12px;"></i></a>
                </span>
                
                <span style="float:right;cursor:pointer;display:none" title="Save" id="save_request_btn">
                    <a onclick="editRecord()"><i class="mdi mdi-floppy text-success"></i></a>
                </span>
            </h4>

            <div class="table-responsive">
                <input type="hidden" value="{{$asset->id}}" id="asset_id">
                <input type="hidden" value="{{$asset->asset_forms_id}}" id="asset_forms_id">

                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>Asset Title</td>
                            <td>
                                <input type="text" id="asset_title" style="display:none" class="form-control form-control-sm" value="{{$asset->asset_title}}">
                                <span id="asset_title_text">{{$asset->asset_title}}</span>
                            </td>
                        </tr>

                        <tr>
                            <td>Template Type</td>
                            <td>{{$asset_templates_form[0]->title}} </td>
                        </tr>
                        <?php //dd($asset_templates_fields) ?>
                        @foreach($asset_templates_fields  as $fields)
                        <tr>
                            <td>{{$fields->label}}</td>
                            <td style="position:relative">
                                <div id="this_div_{{$fields->id}}" class="this_div_{{$fields->id}}"></div>
                                <input type="{{$fields->type}}" name="{{$fields->type}}" id="fields_{{$fields->id}}" placeholder="{{$fields->placeholder}}" style="display:none" class="form-control form-control-sm field field_{{$fields->id}}" value="{{$fields->input_data[0]}}">
                                @if($fields->type == "password")
                                    <span class="field_text star_password" >**********</span>
                                    <span class="show_password" style="display:none">{{$fields->input_data[0]}}</span>
                                    <span style="position:absolute;top:10px;right:10px" toggle="#password-field" id="pass_icon" class="fa fa-fw fa-eye mr-2 show-password-btn pass_icon"></span>

                                    <span style="position:absolute;top:10px;right:10px; display:none" toggle="#password-field" id="input_pass_icon" class="fa fa-fw fa-eye mr-2 show-password-btn input_pass_icon"></span>
                                @elseif($fields->type == "address")
                                    <input type="hidden" value="{{$fields->type}}" id="fields_type" class="fields_type">
                                    <input type="hidden" value="{{$fields->input_data[0]}}" id="add_field_value" class="add_field_value_{{$fields->id}}">
                                    <input type="hidden" value="{{$fields->id}}" id="field_id" class="field_id">
                                    <input type="{{$fields->type}}" id="fields_{{$fields->id}}" placeholder="{{$fields->placeholder}}" style="display:none" class="form-control form-control-sm field" value="{{$fields->input_data[0]}}">
                                    <?php

                                        $ab = explode("|",$fields->input_data[0]);
                                        print_r("<div id='add_$fields->id' class='add_$fields->id''>");
                                        print_r("<span class='text-muted small'>Address</span>");
                                        print_r("<br>");
                                        print_r($ab[0] . " , " . $ab[1]);

                                        print_r("<br>");
                                        print_r($ab[2] . " " . $fields->state->name . " " . $ab[4]);

                                        print_r("<br>");
                                        print_r($fields->country[0]->name);
                                        print_r("</div>");
                                    ?>
                                @else
                                    <span class="field_text showtext_{{$fields->id}}" id="field_text_{{$fields->id}}">{{$fields->input_data[0]}}</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach

                        @if($customer != NULL && $customer != '') 
                        <tr>
                            <td>Customer Name</td>
                            <td>{{$customer->first_name}} {{$customer->last_name}}</td>

                        </tr>
                        @endif

                        @if($company != NULL && $company != '') 
                        <tr>
                            <td>Company Name</td>
                            <td>{{$company->name}}</td>

                        </tr>
                        <tr>
                            <td>Owner Name</td>
                            <td>{{$company->poc_first_name}} {{$company->poc_last_name}}</td>

                        </tr>
                        @endif
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@include('js_files.help_desk.asset_manager.gen_infoJs')


@endsection
