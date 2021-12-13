<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{csrf_token()}}" />

    <link rel="stylesheet" href="{{asset('public/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">


    <link href="{{asset('/assets/extra-libs/jquery-steps/jquery.steps.css').'?ver='.rand()}}" rel="stylesheet">
    <link href="{{asset('/assets/extra-libs/jquery-steps/steps.css').'?ver='.rand()}}" rel="stylesheet">
    <script src="{{asset('/assets/dist/js/custom.min.js')}}"></script>

    <!-- font-awesome -->
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <style>
        :root {
            --primary-color: rgb(11, 78, 179);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: Montserrat, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: grid;
            place-items: center;
            min-height: 100vh;
        }

        /* Global Stylings */
        label {
            display: block;
            margin-bottom: 0.5rem;
        }

        input {
            display: block;
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        /* .width-50 {
            width: 50%;
        } */

        .ml-auto {
            margin-left: auto;
        }

        .text-center {
            text-align: center;
        }

        /* Progressbar */
        .progressbar {
            position: relative;
            display: flex;
            justify-content: space-between;
            counter-reset: step;
            margin: 2rem 0 4rem;
            z-index: 9;
        }

        .progressbar::before,
        .progress {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 4px;
            width: 100%;
            background-color: #dcdcdc;
            z-index: -1;
        }

        .progress {
            background-color: var(--primary-color);
            width: 0%;
            transition: 0.3s;
        }

        .progress-step {
            width: 2.1875rem;
            height: 2.1875rem;
            background-color: #dcdcdc;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .progress-step::before {
            counter-increment: step;
            content: counter(step);
        }

        .progress-step::after {
            content: attr(data-title);
            position: absolute;
            top: calc(100% + 0.5rem);
            font-size: 0.65rem;
            color: #666;
        }

        .progress-step-active {
            background-color: var(--primary-color);
            color: #f3f3f3;
        }

        /* Form */
        .form {
            /* width: clamp(320px, 30%, 430px); */
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 0.35rem;
            padding: 1.5rem;
        }

        .form-step {
            display: none;
            transform-origin: top;
            animation: animate 0.5s;
        }

        .form-step-active {
            display: block;
        }

        .input-group {
            margin: 2rem 0;
        }

        @keyframes animate {
            from {
                transform: scale(1, 0);
                opacity: 0;
            }

            to {
                transform: scale(1, 1);
                opacity: 1;
            }
        }

        /* Button */
        .btns-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .btn {
            padding: 8px 20px 8px 20px;
            display: block;
            text-decoration: none;
            background-color: var(--primary-color);
            color: #f3f3f3;
            text-align: center;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--primary-color);
        }
    </style>
</head>

<body class="bg-light">

    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow p-5">
                <!-- <form action="#" class="form"> -->
                <h1 class="text-center lead font-weight-bold">System Installation Setup</h1>
                <!-- Progress bar -->
                <div class="progressbar">
                    <div class="progress" id="progress"></div>
                    <div class="progress-step progress-step-active" data-title="Database Setup"></div>
                    <div class="progress-step" data-title="Admin User Setup"></div>
                    <div class="progress-step" data-title="Company Setup"></div>
                    <div class="progress-step" data-title="Modules Configuration"></div>
                    <div class="progress-step" data-title="System Configration"></div>
                </div>

                <!-- Step one -->
                <div class="form-step form-step-active">
                    <form action="{{url('installation-data')}}" id="dbConfigForm" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="server" class="small">Host <span class="text-danger">*</span> </label>
                                <input type="text" name="server" class="form-control" id="server"/>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="small">Username <span class="text-danger">*</span> </label>
                                <input type="text" name="username" class="form-control" id="username"/>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="password" class="small">Password <span class="text-danger">*</span> </label>
                                <input type="text" name="password" class="form-control" id="password" />
                            </div>
                            <div class="col-md-6">
                                <label for="dbname" class="small">Database Name <span class="text-danger">*</span> </label>
                                <input type="text" name="dbname" class="form-control" id="dbname"/>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="#" class="btn btn-primary step-one">Next</a>
                        </div>
                    </form>
                </div>

                <!-- Step two -->
                <div class="form-step">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="small">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="small">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="password" class="small">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="admin_password" class="form-control" />
                        </div>
                    </div>
                    <div class="btns-group mt-5">
                        <a href="#" class="btn btn-prev">Previous</a>
                        <a href="#" class="btn btn-primary step-two">Next</a>
                    </div>
                </div>

                <!-- Step three -->
                <div class="form-step">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="company_name" class="small">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="company_name" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label for="company_email" class="small">Company Email <span class="text-danger">*</span></label>
                            <input type="email" name="company_email" id="company_email" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="comp_first_name" class="small">Company Owner First Name <span class="text-danger">*</span></label>
                            <input type="text" name="comp_first_name" id="comp_first_name" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label for="comp_last_name" class="small">Company Owner Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="comp_last_name" id="comp_last_name" class="form-control" />
                        </div>
                    </div>

                    <div class="btns-group mt-5">
                        <a href="#" class="btn btn-prev">Previous</a>
                        <a href="#" class="btn btn-primary step-three">Next</a>
                    </div>
                </div>

                <!-- Step four -->
                <div class="form-step">
                    <div class="row">
                        @if(sizeof($features) > 0)
                            @foreach($features as $feature)
                                <div class="col-sm-3 mt-2">
                                    <div class="custom-control custom-checkbox mr-3 mt-2">
                                        <input type="checkbox" name="feature_checkbox" class="custom-control-input" id="check_{{$feature['f_id']}}" data-id="{{$feature['f_id']}}">
                                        <label class="custom-control-label" for="check_{{$feature['f_id']}}"> {{$feature['title']}} </label>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="btns-group mt-5">
                        <a href="#" class="btn btn-prev">Previous</a>
                        <a href="#" class="btn btn-primary step-four">Next</a>
                    </div>
                </div>

                <!-- Step five -->
                <div class="form-step">
                    <div class="alert alert-info p-2 rounded-0" role="alert">
                        <span class="small">After Successfully Save System Setting You will Redirect to Login page </span>
                    </div>
                    <form action="{{url('installation-data')}}" id="systemSettingForm" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="system_title" class="small">System Title <span class="text-danger">*</span></label>
                                <input type="text" name="system_title" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label for="domain_name" class="small">Domain Name <span class="text-danger">*</span></label>
                                <input type="text" name="domain_name" class="form-control" />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="system_footer" class="small">System Footer Text <span class="text-danger">*</span></label>
                                <input type="text" name="system_footer" class="form-control" />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <span class="small">System Logo <span class="text-danger">*</span> </span>
                                <input type="file" name="system_logo" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <span class="small">System Favicon <span class="text-danger">*</span> </span>
                                <input type="file" name="system_favicon" class="form-control" />
                            </div>
                        </div>
                        <div class="btns-group mt-5">
                            <a href="#" class="btn btn-prev">Previous</a>
                            <input type="submit" value="Submit" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



<script src="{{asset('assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('public/toastr/toastr.min.js')}}"></script>

<script>
    let formStepsNum = 0;
    $(document).ready(function() {

        var action = $("#dbConfigForm").attr('action');
        var method = $("#dbConfigForm").attr('method');


        $('.step-one').on('click', function() {

            var formData = {
                type : 'dbconfig',
                server_name :$("#server").val(),
                username :$("#username").val(),
                password :$("#password").val(),
                dbname: $("#dbname").val(),
            }

            saveInstallationRecord(action , method, formData);

        });

        $('.step-two').on('click', function() {

            var formData = {
                type : 'adminUser',
                name :$("#name").val(),
                email :$("#email").val(),
                password :$("#admin_password").val(),
            }

            saveInstallationRecord(action , method,formData);

        });

        $('.step-three').on('click', function() {

            var formData = {
                type : 'company',
                company_name :$("#company_name").val(),
                company_email :$("#company_email").val(),
                comp_first_name :$("#comp_first_name").val(),
                comp_last_name :$("#comp_last_name").val(),
            }

            saveInstallationRecord(action , method, formData);

        });

        $('.step-four').on('click', function() {

            var feature_id = [];

            $("input:checkbox[name=feature_checkbox]:checked").each(function() {
                feature_id.push( $(this).attr('data-id') ); 
            });

            var type = 'module';

            var formData = {
                type : 'module',
                id :feature_id,
            }

            saveInstallationRecord(action , method, formData);

        });

        $("#systemSettingForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                url: action,
                type: method,
                data: new FormData(this),
                dataType: 'JSON',
                async:true,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if(data.status == 200) {
                        toastr.success(data.message, { timeOut: 5000 });
                        setTimeout(() => { window.location.href = "{{url('login')}}"; }, 1000);
                    }else{
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });

    });

    const prevBtns = document.querySelectorAll(".btn-prev");
    const progress = document.getElementById("progress");
    const formSteps = document.querySelectorAll(".form-step");
    const progressSteps = document.querySelectorAll(".progress-step");


    prevBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            formStepsNum--;
            updateFormSteps();
            updateProgressbar();
        });
    });

    function updateFormSteps() {
        formSteps.forEach((formStep) => {
            formStep.classList.contains("form-step-active") &&
                formStep.classList.remove("form-step-active");
        });

        formSteps[formStepsNum].classList.add("form-step-active");
    }

    function updateProgressbar() {
        progressSteps.forEach((progressStep, idx) => {
            if (idx < formStepsNum + 1) {
                progressStep.classList.add("progress-step-active");
            } else {
                progressStep.classList.remove("progress-step-active");
            }
        });

        const progressActive = document.querySelectorAll(".progress-step-active");

        progress.style.width =
            ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
    }


    function saveInstallationRecord(action, method,formdata) {
   
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: method,
            url: action,
            data: formdata,
            dataType: 'JSON',
            success: function(data) {
                console.log(data);

                if(data.status == 200) {

                    toastr.success(data.message, { timeOut: 5000 });

                    setTimeout(() => {

                        formStepsNum++;
                        updateFormSteps();
                        updateProgressbar();

                    }, 1500);

                }else if(data.status == 302 && data.success == false) {
                    window.location.href = "{{url('login')}}";
                }else{
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {
                console.log(e);
                if(e.responseJSON.message.includes("mysqli_connect(): (HY000/1049): Unknown database")) {
                    toastr.error( "Database not found" , { timeOut: 5000 });
                }
                if(e.responseJSON.message.includes("mysqli_connect(): (HY000/1045): Access denied for user")) {
                    toastr.error( "User not found" , { timeOut: 5000 });
                }
                if(e.responseJSON.message.includes("mysqli_connect(): php_network_getaddresses: getaddrinfo failed: No such host is known")) {
                    toastr.error( "Unkown Host" , { timeOut: 5000 });
                }
                
            }
        });
    }


</script>
</body>
</html>