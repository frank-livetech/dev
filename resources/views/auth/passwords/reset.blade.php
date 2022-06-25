<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>Forget Password</title>
    <link rel="apple-touch-icon" href=" <link rel="icon" type="image/png" sizes="16x16" href="{{asset('files/brand_files')}}">
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
                <div class="auth-wrapper auth-basic px-2">
                    <div class="auth-inner my-2">
                        <!-- Forgot Password basic -->
                        <div class="card mb-0">
                            <div class="card-body">
                                @if($settings != null && $settings != "")
                                @if($settings->login_logo != null && $settings->login_logo != "")
                                    @if(file_exists( public_path().'/'. $file_path . $settings->login_logo ))
                                        <img src="{{asset( $file_path . $settings->login_logo)}}"  class="img-fluid d-block mx-auto" alt="" />
                                    @else
                                        <img src="{{asset($file_path . 'default_imgs/login_logo.png')}}"  class="img-fluid d-block mx-auto" alt="" />
                                    @endif
                                @else
                                <img src="{{asset($file_path . 'default_imgs/login_logo.png')}}"  class="img-fluid d-block mx-auto" alt="" />
                                @endif
                            @endif
                                <h4 class="card-title mb-1 mt-1">Forgot Password? 🔒</h4>
                                <p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>

                                <form class="auth-forgot-password-form mt-2" action="{{url('recoverPassword')}}" method="POST">
                                    @csrf
                                    <div class="mb-1">
                                        <input name="fcm_token" id="fcm_token" type="hidden">
                                        <label for="forgot-password-email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" placeholder="john@example.com" aria-describedby="forgot-password-email" tabindex="1" autofocus />
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror 
                                        @if (\Session::has('success'))
                                            <span class="text-success small">
                                                {!! \Session::get('success') !!}
                                            </span>
                                        @endif
                                    </div>
                                    <button class="btn btn-primary w-100" tabindex="2" type="submit">Send reset link</button>
                                </form>

                                <p class="text-center mt-2">
                                    <a href="{{url('login')}}"> <i data-feather="chevron-left"></i> Back to login </a>
                                </p>
                            </div>
                        </div>
                        <!-- /Forgot Password basic -->
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