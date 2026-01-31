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
                                    <!-- Step 1: Phone Number Input -->
                                    <div id="phone-step" class="password-reset-step">
                                        <p class="mb-20">Enter your phone number to receive OTP</p>
                                        <div class="form-group mb-20">
                                            <label for="phone_number">Phone Number</label>
                                            <input type="text" class="form-control" id="phone_number" placeholder="Enter 11 digit phone number">
                                            <span class="text-danger" id="phone-error"></span>
                                        </div>
                                        <button class="btn btn-primary w-100" id="send-otp-btn" style="background-color: #0b2e33">
                                            Send OTP
                                        </button>
                                    </div>

                                    <!-- Step 2: OTP Verification -->
                                    <div id="otp-step" class="password-reset-step" style="display: none;">
                                        <p class="mb-20">Enter the 4-digit OTP sent to your phone</p>
                                        <div class="form-group mb-20">
                                            <label for="otp">OTP Code</label>
                                            <input type="text" class="form-control" id="otp" placeholder="Enter 4-digit OTP" maxlength="4">
                                            <span class="text-danger" id="otp-error"></span>
                                        </div>
                                        <button class="btn btn-primary w-100" id="verify-otp-btn" style="background-color: #0b2e33">
                                            Verify OTP
                                        </button>
                                        <button class="btn btn-secondary w-100 mt-2" id="resend-otp-btn">
                                            Resend OTP
                                        </button>
                                    </div>

                                    <!-- Step 3: Reset Password -->
                                    <div id="reset-step" class="password-reset-step" style="display: none;">
                                        <form id="reset-password-form" method="POST">
                                            @csrf
                                            <input type="hidden" id="user_id" name="user_id">
                                            <input type="hidden" id="verified_otp" name="otp">
                                            
                                            <div class="form-group mb-20">
                                                <label for="email">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                                            </div>
                                            
                                            <div class="form-group mb-20">
                                                <label for="password">New Password</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                                                    <span toggle="#password" class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-20">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
                                                    <span toggle="#password_confirmation" class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary w-100" style="background-color: #0b2e33">
                                                Reset Password
                                            </button>
                                        </form>
                                    </div>

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

    <div id="overlayer" style="display: none;">
        <div class="loader-overlay">
            <div class="dm-spin-dots spin-lg">
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
            </div>
        </div>
    </div>

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

            // Send OTP
            $('#send-otp-btn').click(function() {
                const phoneNumber = $('#phone_number').val();
                $('#phone-error').text('');

                if (!phoneNumber || phoneNumber.length !== 11) {
                    $('#phone-error').text('Please enter a valid 11-digit phone number');
                    return;
                }

                $('#overlayer').show();

                $.ajax({
                    url: '{{ route("password.send-otp") }}',
                    method: 'POST',
                    data: { phone_number: phoneNumber },
                    success: function(response) {
                        $('#overlayer').hide();
                        if (response.success) {
                            userId = response.user_id;
                            $('#phone-step').hide();
                            $('#otp-step').show();
                            alert(response.message);
                            console.log(response.message, response);
                            
                        }
                    },
                    error: function(xhr) {
                        $('#overlayer').hide();
                        const error = xhr.responseJSON;
                        $('#phone-error').text(error.message || 'An error occurred');
                    }
                });
            });

            // Resend OTP
            $('#resend-otp-btn').click(function() {
                $('#otp-step').hide();
                $('#phone-step').show();
                $('#otp').val('');
            });

            // Verify OTP
            $('#verify-otp-btn').click(function() {
                const otp = $('#otp').val();
                $('#otp-error').text('');

                if (!otp || otp.length !== 4) {
                    $('#otp-error').text('Please enter a valid 4-digit OTP');
                    return;
                }

                $('#overlayer').show();

                $.ajax({
                    url: '{{ route("password.verify-otp") }}',
                    method: 'POST',
                    data: { 
                        user_id: userId,
                        otp: otp 
                    },
                    success: function(response) {
                        $('#overlayer').hide();
                        if (response.success) {
                            $('#user_id').val(userId);
                            $('#verified_otp').val(otp);
                            $('#otp-step').hide();
                            $('#reset-step').show();
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        $('#overlayer').hide();
                        const error = xhr.responseJSON;
                        $('#otp-error').text(error.message || 'Invalid OTP');
                    }
                });
            });

            // Reset Password Form Submit
            $('#reset-password-form').submit(function(e) {
                e.preventDefault();
                
                $('#overlayer').show();
                
                $.ajax({
                    url: '{{ route("password.reset") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#overlayer').hide();
                        alert('Password reset successfully! Redirecting to login...');
                        window.location.href = '{{ route("login") }}';
                    },
                    error: function(xhr) {
                        $('#overlayer').hide();
                        const error = xhr.responseJSON;
                        alert(error.message || 'An error occurred. Please try again.');
                    }
                });
            });

            // Toggle password visibility
            $('.toggle-password2').click(function() {
                const target = $(this).attr('toggle');
                const input = $(target);
                
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).removeClass('uil-eye-slash').addClass('uil-eye');
                } else {
                    input.attr('type', 'password');
                    $(this).removeClass('uil-eye').addClass('uil-eye-slash');
                }
            });
        });
    </script>
</body>
</html>