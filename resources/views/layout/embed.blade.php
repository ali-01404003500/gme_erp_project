 {{-- resources/views/layouts/embed.blade.php --}}

<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ Session::get('layout') == 'rtl' ? 'rtl' : 'ltr' }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0"
    >

    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge"
    >

    <meta
        name="description"
        content="@yield('description')"
    >

    <title>
        @yield('title') | GME
    </title>


    {{-- ==========================================================
         PRECONNECT
    =========================================================== --}}

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
        crossorigin
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        rel="preconnect"
        href="https://unicons.iconscout.com"
        crossorigin
    >


    {{-- ==========================================================
         GOOGLE FONT
    =========================================================== --}}

    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    {{-- ==========================================================
         MAIN STYLESHEETS
         
         EXACT SAME CSS AS layouts/app.blade.php
    =========================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('/assets/css/plugin' . Helper::rlt_ext() . '.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/css/style' . Helper::rlt_ext() . '.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/css/variables.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/app' . Helper::rlt_ext() . '.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/custom.css') }}?v=2"
    >


    {{-- ==========================================================
         PLUGIN STYLESHEETS
         
         EXACT SAME AS layouts/app.blade.php
    =========================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('/assets/plugins/tom-select/custom-tomSelect.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/plugins/tom-select/tom-select.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/plugins/tom-select/tom-select.default.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/plugins/toastr/toastr.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/plugins/sweetalerts2/sweetalerts2.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/plugins/datatable/datatables.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('/assets/plugins/datatable/dt-global_style.css') }}"
    >


    {{-- ==========================================================
         FLATPICKR CSS
    =========================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css"
    >


    {{-- ==========================================================
         FAVICON
    =========================================================== --}}

    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('/assets/img/favicon1.png') }}"
    >


    {{-- ==========================================================
         UNICONS
    =========================================================== --}}

    <link
        rel="stylesheet"
        href="https://unicons.iconscout.com/release/v3.0.0/css/line.css"
    >


    {{-- ==========================================================
         PAGE HEAD
    =========================================================== --}}

    @yield('page-head')


    {{-- ==========================================================
         PAGE STACK STYLES
    =========================================================== --}}

    @stack('styles')


    {{-- ==========================================================
         CUSTOM SIDEBAR CSS
         
         Keeping because some existing pages may depend on it.
    =========================================================== --}}

    <style>

        .has-child .has-subchild ul li a {
            padding-left: 48px !important;
        }

        .has-child .has-subchild .has-subsubchild ul li a {
            padding-left: 64px !important;
        }

        .has-child .has-subchild .has-subsubsubchild ul li a {
            padding-left: 80px !important;
        }

        .sidebar__menu-group ul.sidebar_nav li ul {
            padding-left: 4px;
            padding-right: 4px;
        }

        .sidebar__menu-group ul.sidebar_nav li.has-subchild.open>a .toggle-icon:before,
        .sidebar__menu-group ul.sidebar_nav li.has-subsubchild.open>a .toggle-icon:before,
        .sidebar__menu-group ul.sidebar_nav li.has-subsubsubchild.open>a .toggle-icon:before {
            content: "\f107";
        }

        @media print {

            .no-print {
                display: none !important;
            }

        }

    </style>

</head>


<body class="layout-{{ request()->session()->get('dark_mode') ? 'dark' : 'light' }} side-menu">


    {{-- ==========================================================
         CONTENT ONLY
         
         No:
         - top navigation
         - sidebar
         - footer
         - customizer
    =========================================================== --}}

    <main class="main-content">

        <div class="contents">

            @yield('content')

        </div>

    </main>


    {{-- ==========================================================
         LOADER
    =========================================================== --}}

    <div id="overlayer">

        <span class="loader-overlay">

            <div class="dm-spin-dots spin-lg">

                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>

            </div>

        </span>

    </div>


    {{-- ==========================================================
         OVERLAYS
    =========================================================== --}}

    <div class="overlay-dark-sidebar"></div>

    <div class="notification-wrapper bottom-right"></div>

    <div class="customizer-overlay"></div>


    {{-- ==========================================================
         MODALS
    =========================================================== --}}

    @stack('modals')


    {{-- ==========================================================
         JQUERY
    =========================================================== --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    {{-- ==========================================================
         CORE JS
    =========================================================== --}}

    <script src="{{ asset('/assets/js/plugins.min.js') }}"></script>

    <script src="{{ asset('/assets/js/script.min.js') }}"></script>


    {{-- ==========================================================
         FLATPICKR
    =========================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>


    {{-- ==========================================================
         APP JS
    =========================================================== --}}

    <script src="{{ asset('/js/app.min.js') }}"></script>

    <script src="{{ asset('/js/custom.js') }}"></script>


    {{-- ==========================================================
         ENVIRONMENT VARIABLES
    =========================================================== --}}

    <script>

        var env = {

            iconLoaderUrl:
                "{{ asset('assets/js/json/icons.json') }}",

            googleMarkerUrl:
                "{{ asset('assets/img/markar-icon.png') }}",

            editorIconUrl:
                "{{ asset('assets/img/ui/icons.svg') }}",

            mapClockIcon:
                "{{ asset('assets/img/svg/clock-ticket1.svg') }}"

        };

    </script>


    {{-- ==========================================================
         TOM SELECT
    =========================================================== --}}

    <script src="{{ asset('/assets/plugins/tom-select/tom-select.complete.js') }}"></script>


    {{-- ==========================================================
         TOASTR
    =========================================================== --}}

    <script src="{{ asset('/assets/plugins/toastr/toastr.min.js') }}"></script>


    {{-- ==========================================================
         SWEETALERT
    =========================================================== --}}

    <script src="{{ asset('/assets/plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>


    {{-- ==========================================================
         DATATABLE
    =========================================================== --}}

    <script src="{{ asset('/assets/plugins/datatable/datatables.js') }}"></script>


    {{-- ==========================================================
         FILE PREVIEW
    =========================================================== --}}

    <script src="{{ asset('/assets/plugins/file-preview/file-preview.js') }}"></script>


    {{-- ==========================================================
         GLOBAL SCRIPT
    =========================================================== --}}

    <script>

        $(document).ready(function () {


            /*
            |--------------------------------------------------------------------------
            | Flatpickr
            |--------------------------------------------------------------------------
            */

            if (typeof flatpickr !== 'undefined') {

                flatpickr(".flatdate", {

                    dateFormat: "Y-m-d"

                });


                /*
                |--------------------------------------------------------------------------
                | Month Picker
                |--------------------------------------------------------------------------
                */

                if (
                    typeof monthSelectPlugin !== 'undefined'
                ) {

                    flatpickr(".month-picker", {

                        plugins: [

                            new monthSelectPlugin({

                                shorthand: true,

                                dateFormat: "Y-m",

                                altFormat: "F Y"

                            })

                        ]

                    });

                }

            }

        });

    </script>


    {{-- ==========================================================
         PAGE SPECIFIC SCRIPTS
    =========================================================== --}}

    @yield('page_scripts')


    {{-- ==========================================================
         EXISTING APP SCRIPT
    =========================================================== --}}

    @include('partials.app_script_js')


    {{-- ==========================================================
         STACK SCRIPTS
    =========================================================== --}}

    @stack('scripts')


    {{-- ==========================================================
         EMBED LAYOUT CSS
    =========================================================== --}}

    <style>

        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        html,
        body {

            width: 100%;

            min-height: 100%;

            margin: 0;

            padding: 0;

        }


        body {

            overflow-x: hidden;

        }


        /*
        |--------------------------------------------------------------------------
        | Main content full width
        |--------------------------------------------------------------------------
        */

        .main-content {

            width: 100% !important;

            min-height: 100vh;

            margin: 0 !important;

            padding: 0 !important;

        }


        .contents {

            width: 100% !important;

            margin: 0 !important;

            padding: 0 !important;

        }


        /*
        |--------------------------------------------------------------------------
        | No application navigation inside iframe
        |--------------------------------------------------------------------------
        */

        .header-top,
        .sidebar-wrapper,
        #sidebar,
        .footer-wrapper,
        .customizer-wrapper {

            display: none !important;

        }


        /*
        |--------------------------------------------------------------------------
        | Remove possible sidebar offset
        |--------------------------------------------------------------------------
        */

        .main-content .contents {

            margin-left: 0 !important;

            margin-right: 0 !important;

        }

    </style>


</body>

</html>