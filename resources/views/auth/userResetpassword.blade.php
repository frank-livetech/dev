<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Customer Register</title>
    @php
        $file_path = $is_live == 1 ? 'public/' : '/';
        $path = $is_live == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
    <link rel="apple-touch-icon" href="{{asset($file_path . 'files/brand_files')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset($file_path . 'files/brand_files')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/themes/semi-dark-layout.css')}}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/pages/authentication.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'assets/css/style.css')}}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
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
                
                        <!-- Reset password-->
                        <div class="d-flex col-lg-5 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <h2 class="card-title fw-bold mb-1">Reset Password 🔒</h2>
                                <p class="card-text mb-2">Your new password must be different from previously used passwords</p>
                                <form class="auth-reset-password-form mt-2" action="auth-login-cover.html" method="POST">
                                    <div class="mb-1">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="reset-password-new">New Password</label>
                                        </div>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input class="form-control form-control-merge" id="reset-password-new" type="password" name="reset-password-new" placeholder="············" aria-describedby="reset-password-new" autofocus="" tabindex="1" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="mb-1">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="reset-password-confirm">Confirm Password</label>
                                        </div>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input class="form-control form-control-merge" id="reset-password-confirm" type="password" name="reset-password-confirm" placeholder="············" aria-describedby="reset-password-confirm" tabindex="2" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary w-100" tabindex="3">Set New Password</button>
                                </form>
                                <p class="text-center mt-2"><a href="{{ url('user-login') }}"><i data-feather="chevron-left"></i> Back to login</a></p>
                            </div>
                        </div>
                        <!-- /Reset password-->
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
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/auth-register.js')}}"></script>
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