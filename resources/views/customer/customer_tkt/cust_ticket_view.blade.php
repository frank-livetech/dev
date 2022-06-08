@extends('customer.layout.customer_master')
@section('body')

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
                        <table id="customer_tickets_table" class="table table-striped table-bordered display w-100">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Subject</th>
                                    <th> Status </th>
                                    <th> Priority </th>
                                    <th> Type </th>
                                    <th> Last Activity </th>
                                    <th> Last Replier </th>
                                    <th>Department </th>
                                    
                                   
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

</div>

@endsection
@section('scripts')
@include('customer.Js.customer_tktJs')
@endsection