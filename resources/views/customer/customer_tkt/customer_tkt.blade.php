@extends('customer.layout.customer_master')
@section('body')

@php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
@endphp

<input type="hidden" id="for_customer_profile_id" value="{{auth()->id()}}">
<input type="hidden" id="previous_url" value="{{url()->previous()}}">
<input type="hidden" id="for_customer_role" value="{{auth()->user()->user_type}}">

<div class="content-body">
    <section id="statistics-card">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="needs-validation was-validated" novalidate="" method="POST" action="{{route('customer.saveTicket')}}" id="save_tickets">
                            <div class="form-group">
                                <div class="row g-1">
                                    <div class="col-md-8 col-12 position-relative">
                                        <label class="form-label" for="validationTooltip01">Subject <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="subject" name="subject" required="">
                                        <span class="text-danger small" id="subject_error"></span>
                                    </div>
                                    <!-- Dept file chanegs  -->
                                    <div class="col-md-4 mb-1">
                                        <label class="form-label" for="dept_id">Select Department<span class="text-danger">*</span></label>
                                        <select class="select2 form-select" style="width:100%" name="dept_id" id="dept_id">
                                            @if($departments != null && $departments != "")
                                            @foreach($departments as $key => $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-md-4 col-sm-4">
                                        <label class="form-label">Select Priority<span class="text-danger">*</span></label>
                                        <select class="select2 form-control dropdown" style="width:100%" id="priority" name="priority" required>
                                            @if($priorities != null && $priorities != "")
                                            @foreach($priorities as $key=> $priority)
                                            <option value="{{$priority->id}}" {{$priority->name == 'Medium' ? 'selected' : ''}}>{{$priority->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>


                                <div class="row mt-2">
                                    <div class="col-md-12 ">
                                        <label class="control-label">Problem Details<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" id="ticket_detail"></textarea>
                                        <span class="text-danger small" id="ticket_detail_error"></span>
                                    </div>
                                    <div id="ticket_attachments"></div>
                                    <div class="col-md-12 mt-3" id="">
                                        <button class="btn btn-outline-primary btn-sm" type="button" onclick="customer.addAttachment()"><span class="fa fa-plus"></span> Add Attachment</button>
                                        <div class="text-right" style="float: right">
                                            <button type="submit" class="btn waves-effect waves-light btn-success" id="btnSaveTicket"> <i class="fas fa-check-circle"></i> Create</button>
                                            <button type="button" style="display:none" disabled id="publishing" class="btn rounded btn-success"> <i class="fas fa-circle-notch fa-spin"></i> Creating... </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
<div style="display: none;" id="tinycontenteditor"></div>
@endsection
@section('scripts')
    <script src="{{asset($file_path . 'assets/libs/tinymce/tinymce.min.js')}}"></script>
    @include('customer.Js.customer_tktJs')
@endsection