{{-- resources/views/layouts/app.blade.php --}}

@include('partials._header')

{{-- Flatpickr CSS --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

{{-- Flatpickr Month Plugin CSS --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<body class="layout-{{ request()->session()->get('dark_mode') ? 'dark' : 'light' }} side-menu">

    {{-- Mobile Search --}}
    <div class="mobile-search">

        <form action="/" class="search-form">

            <img src="{{ asset('assets/img/svg/search.svg') }}"
                 alt="search"
                 class="svg">

            <input class="form-control me-sm-2 box-shadow-none"
                   type="search"
                   placeholder="Search..."
                   aria-label="Search">

        </form>

    </div>

    <div class="mobile-author-actions"></div>

    {{-- Header --}}
    <header class="header-top">
        @include('partials._top_nav')
    </header>

    {{-- Main Content --}}
    <main class="main-content">

        {{-- Sidebar --}}
        <div class="sidebar-wrapper">

            <aside class="sidebar sidebar-collapse" id="sidebar">
                @include('partials._menu')
            </aside>

        </div>

        {{-- Page Content --}}
        <div class="contents">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="footer-wrapper">
            @include('partials._footer')
        </footer>

    </main>

    {{-- Loader --}}
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

    {{-- Overlays --}}
    <div class="overlay-dark-sidebar"></div>
    <div class="notification-wrapper bottom-right"></div>
    <div class="customizer-overlay"></div>

    {{-- Customizer --}}
    <div class="customizer-wrapper">
        @include('partials._customizer')
    </div>

    @stack('modals')

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Core JS --}}
    <script src="{{ asset('/assets/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/assets/js/script.min.js') }}"></script>

    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Flatpickr Month Plugin --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

    {{-- App JS --}}
    <script src="{{ asset('/js/app.min.js') }}"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>

    {{-- Environment Variables --}}
    <script>

        var env = {

            iconLoaderUrl: "{{ asset('assets/js/json/icons.json') }}",

            googleMarkerUrl: "{{ asset('assets/img/markar-icon.png') }}",

            editorIconUrl: "{{ asset('assets/img/ui/icons.svg') }}",

            mapClockIcon: "{{ asset('assets/img/svg/clock-ticket1.svg') }}"

        }

    </script>

    {{-- Plugins --}}
    <script src="{{ asset('/assets/plugins/tom-select/tom-select.complete.js') }}"></script>

    <script src="{{ asset('/assets/plugins/toastr/toastr.min.js') }}"></script>

    <script src="{{ asset('/assets/plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>

    <script src="{{ asset('/assets/plugins/datatable/datatables.js') }}"></script>

    <script src="{{ asset('/assets/plugins/file-preview/file-preview.js') }}"></script>

    {{-- Global Custom Script --}}
    <script>

        $(document).ready(function () {

            /*
            |--------------------------------------------------------------------------
            | Flatpickr Normal Date Picker
            |--------------------------------------------------------------------------
            */

            flatpickr(".flatdate", {

                dateFormat: "Y-m-d"

            });

            /*
            |--------------------------------------------------------------------------
            | Month Picker
            |--------------------------------------------------------------------------
            */

            flatpickr(".month-picker", {

                plugins: [

                    new monthSelectPlugin({

                        shorthand: true,
                        dateFormat: "Y-m",
                        altFormat: "F Y"

                    })

                ]

            });

            /*
            |--------------------------------------------------------------------------
            | Sidebar Toggle
            |--------------------------------------------------------------------------
            */

            $('#companyToggle').on('click', function (e) {

                e.preventDefault();

                $('#companyText').toggle();

                $('#companyLogo').toggle();

            });

        });

    </script>

    {{-- Page Specific Scripts --}}
    @yield('page_scripts')

    {{-- App Script --}}
    @include('partials.app_script_js')

    {{-- Custom CSS --}}
    <style>

        .logo-area {

            width: 100%;
            padding: 10px 20px;
            box-sizing: border-box;

        }

        .logo-area .company-title,
        .logo-area .company-logo {

            display: inline-block;
            white-space: nowrap;
            transition: opacity 0.3s ease, width 0.3s ease;

        }

        .sidebar-toggle {

            cursor: pointer;
            transition: transform 0.3s ease;

        }

        #companyLogo {

            transition: all 0.3s ease;

        }

    </style>

</body>

</html>