 {{-- resources/views/layouts/app.blade.php --}}

@include('partials._header')

<body class="layout-{{request()->session()->get('dark_mode') ? "dark" : "light"}} side-menu">
    <div class="mobile-search">
        <form action="/" class="search-form">
            <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
            <input class="form-control me-sm-2 box-shadow-none" type="search" placeholder="Search..."
                aria-label="Search">
        </form>
    </div>
    <div class="mobile-author-actions"></div>
    <header class="header-top">
        @include('partials._top_nav')
    </header>
    <main class="main-content">
        <div class="sidebar-wrapper">
            <aside class="sidebar sidebar-collapse" id="sidebar">
                @include('partials._menu')
            </aside>
        </div>
        <div class="contents">
            @yield('content')
        </div>
        <footer class="footer-wrapper">
            @include('partials._footer')
        </footer>
    </main>

    {{-- Overlays / Loader --}}
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
    <div class="overlay-dark-sidebar"></div>
    <div class="notification-wrapper bottom-right"></div>
    <div class="customizer-overlay"></div>
    <div class="customizer-wrapper">
        @include('partials._customizer')
    </div>

    @stack('modals')
    
    {{-- ===== jQuery FIRST ===== --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Plugins --}}
    <script src="{{ asset('/assets/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/assets/js/script.min.js') }}"></script>
    <script src="{{ asset('/js/app.min.js') }}"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>


     {{-- Custom JS --}}
    <script> 

        $(document).ready(function() {
            $('#companyToggle').on('click', function(e) {
                e.preventDefault(); // link click prevent

                $('#companyText').toggle(); // text hide/show
                $('#companyLogo').toggle(); // logo hide/show
            });
        });
        
    </script>

    <script>
        var env = {
            iconLoaderUrl: "{{ asset('assets/js/json/icons.json') }}",
            googleMarkerUrl: "{{ asset('assets/img/markar-icon.png') }}",
            editorIconUrl: "{{ asset('assets/img/ui/icons.svg') }}",
            mapClockIcon: "{{ asset('assets/img/svg/clock-ticket1.sv') }}g"
        }
    </script>
  
    {{-- Other plugins --}}
    <script src="{{ asset('/assets/plugins/tom-select/tom-select.complete.js') }}"></script>
    <script src="{{ asset('/assets/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatable/datatables.js') }}"></script>
    <script src="{{ asset('/assets/plugins/file-preview/file-preview.js') }}"></script>

    @yield('page_scripts')

    @include('partials.app_script_js')


 


    {{-- Sidebar Toggle Button JS & CSS --}}


    <style>

       .logo-area {
            width: 100%;
            padding: 10px 20px;
            box-sizing: border-box;
        }

        /* Flex layout ensures everything inline */
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

        /* Optional smooth show/hide */
        #companyLogo {
            transition: all 0.3s ease;
        }

    </style>

</body>

</html>