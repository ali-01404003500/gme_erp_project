<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('layout') == 'rtl' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

  {{-- <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('/assets/css/plugin' . Helper::rlt_ext() . '.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/style' . Helper::rlt_ext() . '.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app' . Helper::rlt_ext() . '.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('css')

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
  
    .card:hover {
        box-shadow: 0 0 11px rgba(33, 33, 33, .2);
    }
    .navbar a {
        color: white;
    }

    .job-post {
        margin-top: 20px;
        margin-bottom: 15px;
        background-color: #fff;
        padding: 15px 30px;
    }

    .job-content {
        margin-top: 20px;
    }

    .job-content p {
        margin-left: 20px;
    }
    body:after {
        display: none;
    }
</style>
</head>

<body class="no-skin">
    <!-- Sidebar -->
    @include('HRMS::recruitment.frontend.layout.sidebar')

    <div class="main-container ace-save-state" id="main-container" style="padding-top: 0 !important">
        <!-- Main Content -->
        <div class="main-content">
            <div class="main-content-inner">
                <div class="page-content" style="background-color: #efefef">
                    @yield('content', 'Default Content')
                </div>
            </div>
        </div>
    
        <!-- Footer -->
        @include('partials._footer')
    </div>
    
    <!-- Online JavaScript Links -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chosen.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    
    <!-- Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    
    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    @yield('page_scripts')

</body>
</html>
