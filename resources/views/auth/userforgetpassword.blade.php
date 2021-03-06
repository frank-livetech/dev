<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>Forget Password</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('files/brand_files')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    @php
    $file_path = $live->sys_value == 1 ? 'public/' : '/';
    $path = $live->sys_value == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/components.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/pages/authentication.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'assets/css/style.css')}}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
     <!-- BEGIN: Content-->
     <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-cover">
                    <div class="auth-inner row m-0">
                         <!-- Brand logo-->
                         <a class="brand-logo" href="#">
                            <img src="{{asset($file_path . 'default_imgs/logo.png')}}" alt="" height="45">
                            <h2 class="brand-text text-primary ms-1 " style="margin-bottom: 0px;margin-top: 8px">Mylive-Tech</h2>
                        </a>
                        <!-- /Brand logo-->
                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-7 align-items-center p-5">
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="{{asset($file_path . 'app-assets/images/pages/register-v2.svg')}}" alt="Register V2" /></div>
                        </div>
                        <!-- /Left Text-->
                        <!-- Register-->
                        <div class="d-flex col-lg-5 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <h2 class="card-title fw-bold mb-1">Forgot Password? ????</h2>
                                <p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>
                                <form class="auth-forgot-password-form mt-2" action="{{route('user-resetpassword')}}" method="POST">
                                    @csrf
                                    <div class="mb-1">
                                        <label class="form-label" for="forgot-password-email">Email</label>
                                        <input class="form-control" id="forgot-password-email" name="email" type="email" name="forgot-password-email" aria-describedby="forgot-password-email" autofocus="" tabindex="1" />
                                        @if (Session::has('message'))
                                            <p class="text-success">{{Session::get('message')}}</p>
                                        @endif
                                        @foreach (['warning','success','danger'] as $session)
                                            @if (\Session::has($session))
                                            <span class="text-{{$session}} small">
                                                {{ Session::get($session) }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <button class="btn btn-primary w-100" type="submit" tabindex="2">Send reset link</button>
                                </form>
                                <p class="text-center mt-2"><a href="{{ url('user-login') }}"><i data-feather="chevron-left"></i> Back to login</a></p>
                            </div>
                        </div>
                        <!-- /Forgot password-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{asset($file_path . 'app-assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset($file_path . 'app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset($file_path . 'app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/auth-forgot-password.js')}}"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
