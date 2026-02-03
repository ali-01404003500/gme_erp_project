<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} - GMEProject</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon1.png') }}">
</head>
<body>
    <main class="main-content">
        <div class="admin" style="background-image:url({{ asset('assets/img/admin-bg-light.png') }});">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
                        <div class="edit-profile">
                            <div class="edit-profile__logos">
                                {{-- <img class="dark" src="{{ asset('assets/img/logo-dark.png') }}" alt="">
                                <img class="light" src="{{ asset('assets/img/logo-white.png') }}" alt=""> --}}
                                {{-- <img class="dark" src="{{ asset('assets/img/icon.png') }}" alt="" style="width:100px !important; height:100px!important ">
                                <img class="light" src="{{ asset('assets/img/icon.png') }}" alt="" style="width:100px !important; height:100px!important "> --}}
                                <img class="dark" src="{{ s3FileToBase64(App\Models\AccessControl\CompanyInfo::first()->company_logo??'assets/img/logo-dark.png') }}" alt="" style="height:100px!important; width: auto; ">
                                <img class="light" src="{{ s3FileToBase64(App\Models\AccessControl\CompanyInfo::first()->company_logo??'assets/img/logo-white.png') }}" alt=""  style="height:100px!important; width: auto; ">
                            </div>
                            <div id="login-buttons" class="d-flex flex-column gap-1 ">
                                <a href="https://gmartbd.net"  class="btn btn-white w-100">
                                    Go to Old System
                                </a>
                                <button id="show-login-btn" class="btn btn-white w-100">
                                    Go to New System
                                </button>
                            </div>
                            <div class="card border-0 d-none" id="login-card" >
                                <div class="card-header">
                                    <div class="edit-profile__title">
                                        <h6>Sign in  {{ App\Models\AccessControl\CompanyInfo::first()->company_name??'Opzo Technologies' }}</h6>
                                    </div>
                                </div>
                                <div class="card-body" id="login-form">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="edit-profile__body">
                                            <div class="form-group mb-20">
                                                <label for="email">Username Or Email Address</label>
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Email address">
                                                @if($errors->has('email'))
                                                    <p class="text-danger">{{$errors->first('email')}}</p>
                                                @endif
                                            </div>
                                            <div class="form-group mb-15">
                                                <label for="password-field">Password</label>
                                                <div class="position-relative">
                                                    <input id="password-field" type="password" class="form-control" name="password" placeholder="Password">
                                                    <span toggle="#password-field" class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                                </div>
                                                @if($errors->has('password'))
                                                    <p class="text-danger">{{$errors->first('password')}}</p>
                                                @endif
                                            </div>
                                            <div class="admin-condition" style="color: #0b2e33 !important" >
                                                {{-- <div class="checkbox-theme-default custom-checkbox ">
                                                    <input class="checkbox" type="checkbox" id="check-1">
                                                    <label for="check-1">
                                                        <span class="checkbox-text">Keep me logged in</span>
                                                    </label>
                                                </div> --}}
                                                <a href="{{ route('password.request') }}">forget password?</a>
                                            </div>
                                            <div class="admin__button-group button-group d-flex pt-1 justify-content-md-start justify-content-center">
                                                <button class="btn btn-primary btn-default w-100 btn-squared text-capitalize lh-normal px-50 signIn-createBtn" style="background-color: #0b2e33">
                                                    sign in
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="px-20">
                                    {{-- <p class="social-connector social-connector__admin text-center">
                                        <span>Or</span>
                                    </p>
                                    <div class="button-group d-flex align-items-center justify-content-center">
                                        <p class="text-center">
                                            Are you want to register as dealer? <br>
                                            <a href="{{ route('Dealer.signup') }}#dealer-form" class="color-primary">
                                                Sign up as Dealer
                                            </a>
                                        </p>
                                    </div> --}}
                                    {{-- <div class="button-group d-flex align-items-center justify-content-center">
                                        <ul class="admin-socialBtn">
                                            <li>
                                                <button class="btn text-dark google">
                                                    <img class="svg" src="{{ asset('assets/img/google-Icon.svg') }}" alt="img" />
                                                </button>
                                            </li>
                                            <li>
                                                <button class=" radius-md wh-48 content-center facebook">
                                                    <i class="uil uil-facebook-f"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button class="radius-md wh-48 content-center twitter">
                                                    <i class="uil uil-twitter"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button class="radius-md wh-48 content-center github">
                                                    <i class="uil uil-github"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </div> --}}
                                </div>
                                {{-- <div class="px-20"> --}}
                                    {{-- <p class="social-connector social-connector__admin text-center">
                                        <span>Or</span>
                                    </p>
                                    <div class="button-group d-flex align-items-center justify-content-center">

                                    <p>
                                        Don't have an account?
                                        <a href="{{ route('register') }}" class="color-primary">
                                            Sign up
                                        </a>
                                    </p> --}}
                                    {{-- </div> --}}
                                    {{-- <div class="button-group d-flex align-items-center justify-content-center">
                                        <ul class="admin-socialBtn">
                                            <li>
                                                <button class="btn text-dark google">
                                                    <img class="svg" src="{{ asset('assets/img/google-Icon.svg') }}" alt="img" />
                                                </button>
                                            </li>
                                            <li>
                                                <button class=" radius-md wh-48 content-center facebook">
                                                    <i class="uil uil-facebook-f"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button class="radius-md wh-48 content-center twitter">
                                                    <i class="uil uil-twitter"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button class="radius-md wh-48 content-center github">
                                                    <i class="uil uil-github"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </div> --}}
                                {{-- </div> --}}
                                {{-- <div class="admin-topbar">
                                    <p class="mb-0">
                                        Don't have an account?
                                        <a href="{{ route('register') }}" class="color-primary">
                                            Sign up
                                        </a>
                                    </p>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div id="overlayer">
        <div class="loader-overlay">
            <div class="dm-spin-dots spin-lg">
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
            </div>
        </div>
    </div>
    <div class="enable-dark-mode dark-trigger">
        <ul>
            <li>
                <a href="#">
                    <i class="uil uil-moon"></i>
                </a>
            </li>
        </ul>
    </div>
    <script src="{{ asset('assets/js/plugins.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".enable-dark-mode a").click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: `/dark-mode-switcher?dark_mode=${$('body').hasClass('layout-dark') ? 0 : 1}`,
                    type: "GET",
                    success: function(data) {
                        if (data == 'dark-on') {
                            $('body').addClass('dark-mode');
                        } else {
                            $('body').removeClass('dark-mode');
                        }
                    }
                });
            });


            
        });

        $(document).ready(function() {
            $('#show-login-btn').on('click', function() {
                $('#login-buttons').addClass('d-none');
                $('#login-card').removeClass('d-none');
            });
        });
    </script>
</body>
</html>
