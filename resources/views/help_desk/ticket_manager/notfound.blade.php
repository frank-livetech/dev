@extends('layouts.master-layout-new')
@section('Help Desk','open')
@section('title', 'Ticket Detail')
@section('Ticket Manager','active')
@php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
    $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp
@section('body')
<div class="app-content content ">
    <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Error page-->
                <div class="misc-wrapper">
                    
                    <div class="misc-inner p-2 p-sm-3">
                        <div class="w-100 text-center">
                            <h2 class="mb-1">Ticket Not Found 🕵🏻‍♀️</h2>
                            <p class="mb-2">Oops! 😖 The requested URL was not found on this server.</p>
                            <img class="img-fluid" src="{{ asset($file_path . 'app-assets/images/pages/error.svg') }}" alt="Error page" />
                        </div>
                    </div>
                </div>
                <!-- / Error page-->
            </div>
        </div>
</div>
@endsection