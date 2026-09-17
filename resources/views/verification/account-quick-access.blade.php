@section('title', 'Quick Access')
@section('description', 'Quick Access')
@extends(request()->boolean('embed')? 'layout.embed': 'layout.app')

@section('content')

<style>

    /* ==========================================================
       Quick Access
    ========================================================== */

    .verification-center {
        width: 100%;
        padding: 0;
    }


    /* ==========================================================
       PAGE HEADER
    ========================================================== */

    .verification-page-header {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 18px 22px;
        box-sizing: border-box;
    }

    .verification-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .verification-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .verification-header-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: rgba(67, 97, 238, 0.10);
        color: #4361ee;

        font-size: 24px;
    }

    .verification-page-title {
        margin: 0;

        font-size: 22px;
        line-height: 1.3;
        font-weight: 600;

        color: #1f2937;
    }

    .verification-page-subtitle {
        margin: 5px 0 0;

        font-size: 13px;
        line-height: 1.5;

        color: #6b7280;
    }

    .verification-header-actions {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    #refreshVerification {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;

        min-width: 95px;
    }

    #refreshVerification i {
        font-size: 16px;
    }

    #refreshVerification.loading i {
        animation: verification-spin 0.8s linear infinite;
    }


    /* ==========================================================
       VERIFICATION NAV
    ========================================================== */

    .verification-nav {
        width: 100%;

        background: #ffffff;

        border: 1px solid #e5e7eb;
        border-radius: 8px;

        margin-bottom: 15px;

        overflow: hidden;
        box-sizing: border-box;
    }


    /*
    |--------------------------------------------------------------------------
    | Horizontal Scroll Container
    |--------------------------------------------------------------------------
    */

    .verification-nav-inner {
        display: flex;
        align-items: stretch;

        width: 100%;

        /*
        IMPORTANT:
        Tabs will NEVER go to a second line.
        */

        flex-wrap: nowrap;

        /*
        Horizontal scrolling
        */

        overflow-x: auto;
        overflow-y: hidden;

        scroll-behavior: smooth;

        /*
        Prevent scrollbar from taking too much space
        */

        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }


    /* ==========================================================
       CHROME / EDGE / SAFARI SCROLLBAR
    ========================================================== */

    .verification-nav-inner::-webkit-scrollbar {
        height: 6px;
    }

    .verification-nav-inner::-webkit-scrollbar-track {
        background: #f5f6f8;
    }

    .verification-nav-inner::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .verification-nav-inner::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }


    /* ==========================================================
       TAB
    ========================================================== */

    .verification-tab {
        position: relative;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        /*
        IMPORTANT:
        Each tab keeps its own width.
        */

        flex: 0 0 auto;

        min-width: max-content;

        min-height: 52px;

        padding: 0 18px;

        color: #6b7280;
        background: #ffffff;

        border-right: 1px solid #eef0f3;

        text-decoration: none !important;

        /*
        Prevent title from wrapping
        */

        white-space: nowrap;

        font-size: 13px;
        font-weight: 500;

        transition: all 0.2s ease;

        cursor: pointer;

        box-sizing: border-box;
    }


    .verification-tab:hover {
        color: #4361ee;
        background: #f8faff;
    }


    .verification-tab.active {
        color: #4361ee;
        background: #f8faff;

        font-weight: 600;
    }


    .verification-tab.active::after {
        content: "";

        position: absolute;

        left: 12px;
        right: 12px;
        bottom: 0;

        height: 3px;

        background: #4361ee;

        border-radius: 3px 3px 0 0;
    }


    /* ==========================================================
       COUNT BADGE
    ========================================================== */

    .verification-count {
        flex: 0 0 auto;

        min-width: 22px;
        height: 22px;

        padding: 0 6px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 20px;

        background: #4361ee;
        color: #ffffff;

        font-size: 11px;
        line-height: 1;

        font-weight: 600;

        box-sizing: border-box;
    }


    .verification-count.zero {
        background: #e5e7eb;
        color: #6b7280;
    }


    .verification-count.pending {
        background: #ff9f43;
        color: #ffffff;
    }


    /* ==========================================================
       IFRAME WRAPPER
    ========================================================== */

    .verification-frame-wrapper {
        position: relative;

        width: 100%;

        min-height: 650px;

        background: #ffffff;

        border: 1px solid #e5e7eb;
        border-radius: 8px;

        overflow: hidden;

        box-sizing: border-box;
    }


    #verificationFrame {
        display: block;

        width: 100%;
        height: 750px;

        border: 0;
        margin: 0;
        padding: 0;

        background: #ffffff;
    }


    /* ==========================================================
       IFRAME LOADER
    ========================================================== */

    .verification-frame-loader {
        position: absolute;

        top: 0;
        left: 0;
        right: 0;
        bottom: 0;

        z-index: 20;

        display: none;

        align-items: center;
        justify-content: center;

        background: rgba(255, 255, 255, 0.88);
    }


    .verification-frame-loader.active {
        display: flex;
    }


    .verification-loader-content {
        text-align: center;

        color: #6b7280;
    }


    .verification-loader-spinner {
        width: 35px;
        height: 35px;

        margin: 0 auto 10px;

        border: 3px solid #e5e7eb;
        border-top-color: #4361ee;

        border-radius: 50%;

        animation: verification-spin 0.8s linear infinite;
    }


    .verification-loader-text {
        font-size: 13px;
        font-weight: 500;
    }


    /* ==========================================================
       ANIMATION
    ========================================================== */

    @keyframes verification-spin {

        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }

    }


    /* ==========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 991px) {

        .verification-page-header {
            padding: 15px;
        }

        .verification-page-title {
            font-size: 19px;
        }

        .verification-header-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;

            font-size: 21px;
        }

        .verification-tab {
            min-height: 48px;
            padding: 0 14px;

            font-size: 12px;
        }

        #verificationFrame {
            height: 700px;
        }

    }


    @media (max-width: 575px) {

        .verification-header-content {
            align-items: flex-start;
        }

        .verification-header-left {
            align-items: flex-start;
        }

        .verification-header-icon {
            display: none;
        }

        .verification-page-title {
            font-size: 18px;
        }

        .verification-page-subtitle {
            font-size: 12px;
        }

        .verification-header-actions {
            flex-shrink: 0;
        }

        #refreshVerification {
            min-width: 40px;
            width: 40px;
            padding: 0;
        }

        #refreshVerification span {
            display: none;
        }

        #verificationFrame {
            height: 650px;
        }

    }

</style>


<div class="verification-center">


    {{-- ==========================================================
         PAGE HEADER
    =========================================================== --}}

    <div class="verification-page-header">
        <div class="verification-header-content">
            <div class="verification-header-left">
                <div class="verification-header-icon">
                    <i class="uil uil-shield-check"></i>
                </div>
                <div>
                    <h3 class="verification-page-title">
                        Quick Access
                    </h3>
                    <p class="verification-page-subtitle">
                        Quick Access to Essential Accounting Operations.
                    </p>
                </div>

            </div>


            <div class="verification-header-actions">
                <buttontype="button" class="btn btn-outline-primary" id="refreshVerification" >
                    <i class="uil uil-refresh"></i>
                    <span>Refresh </span>
                </button>
            </div>
        </div>
    </div>



    {{-- ==========================================================
         VERIFICATION TABS
    =========================================================== --}}

    <div class="verification-nav">
        <div class="verification-nav-inner">
            @foreach($verificationTabs as $tab)
                @if(hasPermission($tab['permission']))
                    <a href="javascript:void(0)"   class="verification-tab" data-key="{{ $tab['key'] }}"  data-url="{{ $tab['route'] }}"   >
                        <span class="verification-title"> {{ $tab['title'] }}  </span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>



    {{-- ==========================================================
         IFRAME
    =========================================================== --}}

    <div class="verification-frame-wrapper">


        {{-- IFRAME LOADER --}}

        <div class="verification-frame-loader" id="verificationFrameLoader"  >
            <div class="verification-loader-content">
                <div class="verification-loader-spinner"></div>
                <div class="verification-loader-text">
                    Loading verification page...
                </div>
            </div>
        </div>

        {{-- EXISTING VERIFICATION PAGE --}}
        <iframe id="verificationFrame" name="verificationFrame"  frameborder="0" scrolling="yes" ></iframe>
    </div>
</div>

@endsection

@section('page_scripts')
<script>
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    let activeVerificationTab = null;

    const frame = $('#verificationFrame');

    const loader = $('#verificationFrameLoader');

    const refreshButton = $('#refreshVerification');



    /*
    |--------------------------------------------------------------------------
    | Load Verification Tab
    |--------------------------------------------------------------------------
    */

    function loadVerificationTab(tab, forceReload = false) {

        if (!tab || !tab.length) {
            return;
        }


        const key = tab.data('key');

        let url = tab.data('url');


        if (!url) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Active Tab
        |--------------------------------------------------------------------------
        */

        $('.verification-tab').removeClass('active');

        tab.addClass('active');

        activeVerificationTab = key;


        /*
        |--------------------------------------------------------------------------
        | Add embed parameter
        |--------------------------------------------------------------------------
        */

        if (url.indexOf('?') !== -1) {

            url += '&embed=1';

        } else {

            url += '?embed=1';

        }


        /*
        |--------------------------------------------------------------------------
        | Force iframe refresh
        |--------------------------------------------------------------------------
        */

        if (forceReload) {

            url += '&_refresh=' + Date.now();

        }


        /*
        |--------------------------------------------------------------------------
        | Show Loader
        |--------------------------------------------------------------------------
        */

        loader.addClass('active');


        /*
        |--------------------------------------------------------------------------
        | Load iframe
        |--------------------------------------------------------------------------
        */

        frame.attr('src', url);

    }


    /*
    |--------------------------------------------------------------------------
    | Iframe Loaded
    |--------------------------------------------------------------------------
    */

    frame.on('load', function () {

        loader.removeClass('active');

    });


    /*
    |--------------------------------------------------------------------------
    | Tab Click
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.verification-tab',
        function (e) {

            e.preventDefault();

            const tab = $(this);

            loadVerificationTab(tab);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Load First Tab Automatically
    |--------------------------------------------------------------------------
    */

    const firstTab = $('.verification-tab').first();

    if (firstTab.length) {

        loadVerificationTab(firstTab);

    }





    /*
    |--------------------------------------------------------------------------
    | Manual Refresh
    |--------------------------------------------------------------------------
    */

    refreshButton.on(
        'click',
        function () {


            if (
                refreshButton.hasClass('loading')
            ) {

                return;

            }


            refreshButton.addClass('loading');


            /*
            |--------------------------------------------------------------------------
            | Refresh Current Tab
            |--------------------------------------------------------------------------
            */

            const activeTab =
                $('.verification-tab.active');


            if (activeTab.length) {

                loadVerificationTab(
                    activeTab,
                    true
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Remove Loading
            |--------------------------------------------------------------------------
            */

            setTimeout(
                function () {

                    refreshButton.removeClass('loading');

                },
                700
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Keep Active Tab Visible
    |--------------------------------------------------------------------------
    */

    function scrollActiveTabIntoView() {

        const activeTab =
            $('.verification-tab.active');


        if (!activeTab.length) {
            return;
        }


        const nav =
            $('.verification-nav-inner');


        const navElement =
            nav.get(0);


        const tabElement =
            activeTab.get(0);


        if (!navElement || !tabElement) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Scroll active tab into visible area
        |--------------------------------------------------------------------------
        */

        const navRect =
            navElement.getBoundingClientRect();

        const tabRect =
            tabElement.getBoundingClientRect();


        if (tabRect.left < navRect.left) {

            navElement.scrollLeft -=
                (navRect.left - tabRect.left + 20);

        }


        if (tabRect.right > navRect.right) {

            navElement.scrollLeft +=
                (tabRect.right - navRect.right + 20);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Scroll Active Tab After Click
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.verification-tab',
        function () {

            setTimeout(
                function () {

                    scrollActiveTabIntoView();

                },
                100
            );

        }
    );


});

</script>

@endsection