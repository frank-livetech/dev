@extends('customer.layout.customer_master')
@section('breadcrumb')
    <h2 class="content-header-title float-start mb-0">Dashboard</h2>
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="">
                    My Tickets
            </a>
            </li>
        </ol>
    </div>
@endsection
<style>
    .text-center{
        text-align: center
    }
     table.dataTable .custom {
            padding-right: 230px !important;
        }

        table.dataTable .pr-ticket {
            min-width: 69px !important;
        }

        .pr-replies {
            min-width: 95px !important;
        }

        .pr-due {
            min-width: 125px !important;
        }

        .pr-activity {
            min-width: 97px !important;
            padding-right: 19px !important;
        }

        .pr-tech {
            min-width: 109px !important;
        }

        table.dataTable .custom-cst {
            padding-right: 37px !important;
        }

        table.dataTable th {
            padding: 0.2rem 1.5rem;
        }

        table.dataTable td {
            padding: 7px !important;
            font-size: 12px;
        }
    table.dataTable .custom {
            padding-right: 230px !important;
        }
    .carousel-control-prev, .carousel-control-next{
        bottom: 26px !important;
    }
     @media (max-width: 548px) {
        #carouselExampleDark{
            display: block !important
        }
        #full-view{
            display: none !important
        }
     }
</style>
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
                    <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel" style="display:none">
                        {{-- <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div> --}}
                        {{-- <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="10000">
                                <div class="card card-hover border-bottom border-success">
                                    <div class="box p-2 rounded success text-center">
                                        <h1 id="my_tickets_count">0</h1>
                                        <h6 class="text-success">All Tickets</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <div class="card card-hover border-bottom border-warning">
                                    <div class="box p-2 rounded warning text-center">
                                        <h1 id="open_tickets_count">0</h1>
                                        <h6 class="text-warning">Open</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card card-hover border-bottom border-danger">
                                    <div class="box p-2 rounded danger text-center">
                                        <h1 id="closed_tickets_count">0</h1>
                                        <h6 class="text-danger">Overdue</h6>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <div class="row mt-3" id="full-view">
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
                        <table id="customer_tickets_table" class="table table-bordered display mb-0">
                            <thead>
                                <tr>
                                    <th class='pr-ticket'>Ticket ID</th>
                                    <th class='custom'>Subject</th>
                                    <th >Status</th>
                                    <th>Priority</th>
                                    <th>Type</th>
                                    <th class='pr-activity'>Last Activity</th>
                                    <th class='pr-replies custom-cst'>Last Replier</th>
                                    <th class='custom-cst'>Department </th>
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
