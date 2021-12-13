<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('files/brand_files')}}">
    <title>Forget Password</title>
	<link rel="canonical" href="https://www.wrappixel.com/templates/monsteradmin/" />
    <!-- Custom CSS -->
    <link href="{{asset('/css/style.min.css')}}" rel="stylesheet">
    <style>
        .footer{
            position:absolute;
            bottom:0px;
            width:100%;
        }
        .auth-wrapper .auth-box {
            box-shadow: none !important;
        }
        .auth-wrapper .auth-box #loginform{
            box-shadow: 1px 0 20px rgb(0 0 0 / 8%) !important;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
   
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" >
            <div class="auth-box ">
            <img src="{{asset('public/files/brand_files/login_logo.png')}}" style="margin-top:-180px;margin-bottom:60px;" class="img-fluid d-block mx-auto" alt="">
            <!-- <img src="{{asset('public/files/asset_img/live-tech-logo.png')}}" style="margin-top:-180px;margin-bottom:60px;" class="img-fluid d-block mx-auto" alt=""> -->
                <div id="loginform" class="p-4 bg-white rounded">
                    <div class="logo">
                        <h3 class="box-title mb-3 font-weight-bold">Set New Password</h3>
                    </div>
                    <!-- Form -->
                    <div class="row" >
                        <div class="col-12">
                            <form class="form-horizontal mt-3 form-material" method="POST" action="{{url('reset_password')}}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input name="fcm_token" id="fcm_token" type="hidden">
                                    <input type="hidden" name="email" value="{{$email}}">
                                    <input type="hidden" name="code" value="{{$code}}">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" autofocus placeholder=" New Password">                            
                                </div>
                                <span class="text-danger">@error('password'){{$message}}@enderror</span>
                                <div class="form-group mb-3">
                                    <input class="form-control @error('confirm_password') is-invalid @enderror" type="password" name="confirm_password" autofocus placeholder="Confirm Password">                                 
                                </div>
                                <span class="text-danger">@error('confirm_password'){{$message}}@enderror</span>
                                <div class="form-group text-center">
                                    <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Change Password</button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <footer class="footer">
        <div class="row">
            <div class="container">
                <p class="m-0 text-center">Copyright ©2021 Powered by Live-Tech Company Management System | Our support line number is 1 (888) 361-8511</p>
            </div>
        </div>
    </footer>
 
    <script src="{{asset('/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-analytics.js"></script>

    <script src="{{asset('/assets/dist/js/firebase.js')}}"></script>
 
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();

    </script>
</body>

</html>
