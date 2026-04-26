<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forget Password - GMEProject</title>
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
                                <img class="dark" src="{{ s3FileToBase64(App\Models\AccessControl\CompanyInfo::first()->company_logo??'assets/img/logo-dark.png') }}" alt="" style="height:100px!important; width: auto;">
                                <img class="light" src="{{ s3FileToBase64(App\Models\AccessControl\CompanyInfo::first()->company_logo??'assets/img/logo-white.png') }}" alt="" style="height:100px!important; width: auto;">
                            </div>
                            <div class="card border-0">
                                <div class="card-header">
                                    <div class="edit-profile__title">
                                        <h6>Reset Password</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Step 1: User Name Input -->
                                    <form action="{{ route('password.verify-email') }}" method="POST">
                                        @csrf
                                        <div id="email-step" class="password-reset-step">
                                            <p class="mb-20">Enter your user name</p>
                                            <div class="form-group mb-20">
                                                <label for="email">User Name</label>
                                                <input type="text" class="form-control" id="email" name="email" placeholder="User Name">
                                                <span class="text-danger" id="email-error"></span>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100" id="email-btn" style="background-color: #0b2e33">
                                                Submit
                                            </button>
                                        </div>
                                    </form> 
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('login') }}" class="color-primary">Back to Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

   

    <script src="{{ asset('assets/js/plugins.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let userId = null;

            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        
 
        });
    </script>
</body>
</html>