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
    @if($settings != null && $settings != " " && $settings->site_favicon != null && $settings->site_favicon !=  " ")
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('files/brand_files')}}/{{$settings->site_favicon}}">
    @endif

    @if($settings != null && $settings != " " && $settings->site_title != null && $settings->site_title !=  " ")
        <title>{{$settings->site_title}}</title>
    @else
        <title>Login Page</title>
    @endif
    @php
        $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
        $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
    <link rel="canonical" href="https://www.wrappixel.com/templates/monsteradmin/" />
    <!-- Custom CSS -->
    <link href="{{asset($path .'/css/style.min.css')}}" rel="stylesheet">
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
        .checkbox label::before {
            border:1px solid #a2a2a2ee;
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
        <div class="auth-wrapper mb-5 d-flex no-block justify-content-center align-items-center container" >
            <div class="row m-5">
                <div class="col-md-12 text-center" style="background-color:#0075BE;">
                    @if($settings != null && $settings != "")
                        @if($settings->login_logo != null && $settings->login_logo != "")
                            <img src="{{asset('public/files/brand_files')}}/{{$settings->login_logo}}" width="300" class=" d-block mx-auto" alt="">
                        @else
                            <img src="{{asset('files/user_photos/logo.gif')}}" class=" d-block mx-auto" width="300" style=" " />
                        @endif
                    @else
                        <img src="{{ asset('files/user_photos/logo.gif')}}" class=" d-block mx-auto" width="300" style=" " />
                    @endif
                   
                </div>
                <div id="loginform" class="p-4 bg-white rounded col-md-12">
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if(Session::has('message'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <form id="registerForm" method="POST" action="{{route('user.register')}}">
                        @csrf
                        <div class="row">
                            <div class="logo col-md-12">
                                <h4 class="box-title mb-3 font-weight-bold">Create New Account</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <p>Please Fill in the fields below to register a new helpdesk account</p>
                            </div>
                            <div class="col-md-12">
                                <p><strong>General Information</strong></p>
                                <hr>
                            </div>

                            <input type="hidden" name="customer_login" value="1" required>
                            <input type="hidden" name="has_account" value="1" required>
                            
                            <div class="col-md-6">
                                <label for="first_name">First Name<span class="text-danger ml-1">*</span></label>
                                <input type="text" id="first_name" name="first_name" class="form-control" required>
                                @error('first_name')
                                    <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name">Last Name<span class="text-danger ml-1">*</span></label>
                                <input type="text" id="last_name" name="last_name" class="form-control" required>
                                @error('last_name')
                                    <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email Address<span class="text-danger ml-1">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" required>
                                @error('email')
                                    <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone">Phone<span class="text-danger ml-1">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                                @error('phone')
                                    <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password">Password<span class="text-danger ml-1">*</span></label>
                                <input type="text" id="password" name="password" class="form-control" required>
                                @error('password')
                                    <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password">Password (Repeat)<span class="text-danger ml-1">*</span></label>
                                <input type="text" id="confirm_password" name="confirm_password" class="form-control" required>
                                @error('confirm_password')
                                    <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="address">Street Address</label>
                                <input type="text" class="form-control" name="address" id="address">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="apt_address">Apartment, suit, unit etc. (optional)</label>
                                <input type="text" class="form-control" name="apt_address" id="apt_address">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="zip">Zip Code</label>
                                <input type="text" class="form-control" id="zip" name="zip">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3 d-flex">
                                    <div class="checkbox checkbox-info float-left pt-0 ml-2 mb-3">
                                        <input id="checkbox-signup" type="checkbox" name="remember" id="remember" required>
                                        <label for="checkbox-signup" class="text-dark pt-1"> I consent for Live-Tech to process my data and agreed to the terms of the <a href="#">Privacy Policy</a></label>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Company Details (Optional)</strong></p>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <label for="poc_first_name">Owner First Name</label>
                                <input type="text" id="poc_first_name" name="poc_first_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="poc_last_name">Owner Last Name</label>
                                <input type="text" class="form-control" id="poc_last_name" name="poc_last_name">
                            </div>
                            <div class="col-md-4">
                                <label for="cmp_name">Company Name</label>
                                <input type="text" id="cmp_name" name="cmp_name" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="cmp_email">Company Email</label>
                                <input type="text" class="form-control" id="cmp_email" name="cmp_email">
                            </div>
                            <div class="col-md-6">
                                <label for="cmp_phone">Phone Number</label>
                                <input type="text" class="form-control" id="cmp_phone" name="cmp_phone">
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="">Company Name</label>
                                <input type="text" name="company_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Address</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="">Phone</label>
                                <input type="tel" name="comp_phone" class="form-control">
                                <small>Prefered Contact Phone Number</small>
                            </div> --}}
                            <div class="col-md-12 mt-3">
                                <div class="form-group text-right">
                                    <button class="btn btn-info btn-lg text-uppercase waves-effect waves-light" type="submit">Register Now</button>
                                </div>
                            </div>
                        </div>
                            <!-- Form -->
                            <!-- <div class="row" >
                                <div class="col-12 p-0">
                                    <form class="form-horizontal mt-3 form-material" method="POST" action="{{url('login')}}">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <input name="fcm_token" id="fcm_token" type="hidden">
                                            <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                        <div class="form-group mb-3 d-flex">
                                            <div class="checkbox checkbox-info float-left pt-0 ml-2 mb-3">
                                                <input id="checkbox-signup" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="checkbox-signup" class="text-dark pt-1"> Remember me </label>
                                            </div> 
                                            <a href="{{url('forgetPassword')}}" id="to-recover" class="text-dark ml-auto mb-3"><i class="fa fa-lock mr-1"></i> Forgot pwd?</a> 
                                        </div>
                                        <div class="form-group text-center">
                                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <p>OR</p>
                                            </div>
                                            <div class="col-md-12 p-0">
                                                <div class="form-group">
                                                    <a class="oauth-container btn darken-4 white black-text w-100" href="{{url('auth/google')}}" style="text-transform:none">
                                                        <div class="left">
                                                            <img width="20px" style="" alt="Google sign-in" 
                                                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
                                                        </div>
                                                        Login with Google
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-0">
                                                <div class="form-group">
                                                    <a class="oauth-container btn darken-4 white black-text w-100" href="{{url('auth/google')}}" style="text-transform:none">
                                                        <div class="left">
                                                            <img width="20px" style="" alt="Google sign-in" 
                                                                src="https://upload.wikimedia.org/wikipedia/en/thumb/0/04/Facebook_f_logo_%282021%29.svg/100px-Facebook_f_logo_%282021%29.svg.png" />
                                                        </div>
                                                        Login with Facebook
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-0">
                                                <div class="form-group">
                                                    <a class="oauth-container btn darken-4 white black-text w-100" href="{{url('auth/google')}}" style="text-transform:none">
                                                        <div class="left">
                                                            <img width="20px" style="" alt="Google sign-in" 
                                                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/df/Microsoft_Office_Outlook_%282018%E2%80%93present%29.svg/69px-Microsoft_Office_Outlook_%282018%E2%80%93present%29.svg.png" />
                                                        </div>
                                                        Login with Outlook 365
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-0">
                                                <div class="form-group mb-3 d-flex">
                                                    <div class=" float-left pt-0 ml-2 mb-3">
                                                        
                                                        <label for="" class="text-dark pt-1"> Don't have an account? </label>
                                                    </div> 
                                                    <a href="{{url('forgetPassword')}}" id="to-recover" class="text-dark ml-auto mb-3"><i class="fa fa-lock mr-1"></i> Register Now</a> 
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> -->
                        </div>
                    </form>
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
 
    <script src="{{asset($file_path .'/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset($file_path .'/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset($file_path .'/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-analytics.js"></script>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <script src="{{asset($file_path .'/assets/dist/js/firebase.js')}}"></script>
 
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(".preloader").fadeOut();

        $('#registerForm').submit(function(event) {
            $(this).find('button').attr('disabled', true);
        //     event.preventDefault();
        //     event.stopPropagation();

        //     var method = $(this).attr("method");
        //     var action = $(this).attr("action");

        //     let formData = $(this).serialize();

        //     $.ajax({
        //         type: method,
        //         url: action,
        //         data: formData,
        //         dataType: 'json',
        //         success: function(data) {
        //             console.log(data);
        //             // if(data.success) location.href = {{url('/user-login')}}
        //         },
        //         error: function(error) {
        //             console.log(error);
        //         }
        //     });
        });
    </script>
</body>

</html>
