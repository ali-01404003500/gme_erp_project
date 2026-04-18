@section('title', 'Verification Requests')
@section('description', 'Verification Requests')
@extends('layout.app')
@section('content')
 

    <div class="container-fluid mt-3">

        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="text-success m-0">All Verification Tab</h5>
            <button class="btn btn-sm btn-primary">⟳</button>
        </div>
        <!-- Tabs -->
 

         <ul class="nav nav-tabs">

        <li class="nav-item">
            <a class="nav-link {{ request()->is('sms.templates') ? 'active' : '' }}" href="{{ route('sms.templates') }}">
                Profile
            </a>
        </li>
        </ul>
        <!-- Page Content -->
        <div class="mt-3">
            @yield('content')
        </div>


    </div>
    <style>
        .verification-tabs {
            white-space: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            border-bottom: 2px solid #eee;
        }

        /* optional better spacing */
        .verification-tabs .nav-item text-center {
            display: inline-block;
        }

    </style>
@endsection


@section('page_scripts')
    <script>
        $(document).ready(function () {

            // Load saved active tab
            let activeTab = localStorage.getItem("activeTab");

            if (activeTab) {
                $(".tab-link").removeClass("active");
                $(".tab-link[data-tab='" + activeTab + "']").addClass("active");
            }

            // Click event
            $(".tab-link").on("click", function () {

                $(".tab-link").removeClass("active");
                $(this).addClass("active");

                // save active tab
                localStorage.setItem("activeTab", $(this).data("tab"));
            });

        });
    </script>
@endsection