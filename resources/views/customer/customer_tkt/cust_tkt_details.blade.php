@extends('customer.layout.customer_master')
@section('body')


<div class="content-body">

    <h1>View Ticket : {{$ticket->coustom_id}}</h1>

    <div class="card mt-2">
        <div class="card-body">
            <h3>{{$ticket->subject}}</h3>

            Created :  <span id="tkt_created_at"> {{$ticket->created_at}} </span> 
            Updated :  <span id="tkt_created_at"> {{$ticket->updated_at}} </span> 

        </div>
    </div>

    <div class="card">
        <div class="card-body p-0" style="background-color:{{($current_status == null) ? '' : ($current_status->color != null ? $current_status->color : ' ')}}">
            <div class="row">
                <div class="col-md-2 p-2" id="dep-label" >
                    <span class="text-muted tw-bold"> Department </span>
                    <h3 class="mt-1"> {{ $department->name != null ? $department->name : '-' }} </h3>
                </div>
                <div class="col-md-2 p-2" id="tech-label" >
                    <span class="text-muted tw-bold"> Owner </span>
                    <h3 class="mt-1"> Owner Name </h3>
                </div>
                <div class="col-md-2 p-2" id="type-label" >
                    <span class="text-muted tw-bold"> Department </span>
                    <h3 class="mt-1"> {{ $type->name != null ? $type->name : '-' }} </h3>
                </div>
                <div class="col-md-2 p-2" id="status_html" onclick="customer.showdropdown('status')">
                    <span class="text-muted tw-bold"> Status </span>
                    <h3 class="mt-1" id="status_heading"> {{ ($current_status == null) ? '' : ($current_status->name != null ? $current_status->name : ' ') }} </h3>
                    <div id="status_field" style="display:none">
                        <select class="select2 form-control " id="status" name="status">
                            @foreach($statuses as $status)
                            <option value="{{$status->id}}" data-color="{{$status->color}}" {{ $status->id == $ticket->status ? 'selected' : '' }}>{{$status->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 p-2" id="priority_html" onclick="customer.showdropdown('priority')" style="background-color:{{($current_priority == null) ? '' : ($current_priority->priority_color != null ? $current_priority->priority_color : ' ')}}">
                    <span class="text-muted tw-bold"> Priority </span>
                    <h3 class="mt-1" id="priority_heading"> {{ ($current_priority == null) ? '' : ($current_priority->name != null ? $current_priority->name : ' ') }} </h3>
                    <div id="priority_field" style="display:none">
                        <select class="select2 form-control " id="priority" name="priority" style="display:none">
                            @foreach($priorities as $priority)
                            <option value="{{$priority->id}}" data-color="{{$priority->priority_color}}" {{$priority->id == $ticket->priority ? 'selected' : ''}}>{{$priority->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>


@endsection

@section('scripts')
@include('customer.Js.customer_tktJs')
@endsection