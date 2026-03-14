@extends('layout.app')
@section('title', 'Home')
@section('description', 'Dashboard')

@section('content')
    <style>
        :root {
            /* Aesthetic Palette - Clean White Version */
            --primary-color: #6366f1;
            --primary-dark: #4338ca;
            --success-color: #10b981;
            --danger-color: #f43f5e;
            --info-color: #0ea5e9;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .social-dash-wrap {
            padding: 30px 0;
            background-color: #ffffff;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        /* Specific Header Spacing (Left and Right) */
        .header-container {
            padding-left: 45px;
            padding-right: 45px;
        }

        /* Modern Card Design - Clean White Version */
        .overview-card-modern {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--card-shadow);
            height: 100%;
        }

        .overview-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-label {
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 10px 0;
        }

        .pre-card-value {
            font-size: 11px;
            font-weight: 400;
            color: var(--text-muted);
            margin: 5px 0;
        }

        .icon-box {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .bg-primary-grad {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        }

        .bg-secondary-grad {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        }

        .bg-success-grad {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-info-grad {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        .glass-panel {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            border: 1px solid var(--border-light);
        }

        .activity-item {
            padding: 14px 0;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cal-header {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 20px -5px rgba(67, 56, 202, 0.3);
        }

        .cal-day {
            font-size: 42px;
            font-weight: 900;
        }

        .stat-badge {
            font-weight: 700;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-danger {
            color: #f43f5e !important;
        }

        /* Summary Card Styles */
        .summary-stat-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .summary-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow);
        }

        .summary-label {
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .summary-trend {
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 30px;
            background: white;
            margin-top: 10px;
        }

        .mini-progress {
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 15px 0 5px;
        }

        .mini-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #6366f1, #4338ca);
            border-radius: 10px;
        }

        .top-performer {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: white;
            border-radius: 12px;
            margin-top: 15px;
            border: 1px solid var(--border-light);
        }

        .performer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .performer-info {
            flex: 1;
        }

        .performer-name {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .performer-stats {
            font-size: 12px;
            color: var(--text-muted);
        }

        .achievement-ring {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .ring-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .btn-group button.active {
            background: #0d6efd;   /* Blue background */
            color: #080808;           /* White text */
            border-color: #0d6efd; /* Optional: border match */
        }
    </style>

    <div class="container-fluid">
        <div class="social-dash-wrap">

           
            @if(hasPermission('dashboard.admin'))
            <!-- Summary -->
            <div class="row px-4">
                <!-- Summary Section -->
                <div class="col-lg-12 mb-25">
                    <div class="glass-panel h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0">Summary</h5>
                            <div class="btn-group shadow-sm radius-md">
                                <button id="btnDaily" class="btn btn-sm btn-white active border">Daily</button>
                                <button id="btnMonthly" class="btn btn-sm btn-white border">Monthly</button>
                                <button id="btnYearly" class="btn btn-sm btn-white border">Yearly</button>
                            </div>
                        </div> 
 
                        <!-- Summary Stats Row -->
                        <div class="row g-3 mb-4">
                             <div class="col-xl-3 col-sm-6 mb-25">
                                <div class="overview-card-modern">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="card-label" id="sales-title">Daily Sales</p>
                                            <h1 class="card-value" id="total-sales">0</h1> 
                                            <h6 class="pre-card-value" id="pre-total-sales">PRV:0</h6> 
                                        </div>
                                        <div class="icon-box bg-success-grad"><i class="uil uil-shopping-cart-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-25">
                                <div class="overview-card-modern">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="card-label" id="purchase-title">Daily Purchase</p>
                                            <h1 class="card-value" id="total-purchases">0</h1> 
                                            <h6 class="pre-card-value" id="pre-total-purchases">PRV:0</h6>
                                        </div>
                                        <div class="icon-box bg-success-grad"><i class="uil uil-shopping-cart-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-25">
                                <div class="overview-card-modern">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="card-label" id="collection-title">Daily Collection</p>
                                            <h1 class="card-value" id="total-collections">0</h1>
                                            <h6 class="pre-card-value" id="pre-total-collections">PRV:0</h6>
                                             
                                        </div>
                                        <div class="icon-box bg-primary-grad"><i class="uil uil-money-bill"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-25">
                                <div class="overview-card-modern">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="card-label" id="payment-title">Daily Payment</p>
                                            <h1 class="card-value" id="total-payments">0</h1> 
                                            <h6 class="pre-card-value" id="pre-total-payments">PRV:0</h6>
                                        </div>
                                        <div class="icon-box bg-success-grad"><i class="uil uil-money-bill"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div> 
            </div>
            @endif
           
            @if(hasPermission('dashboard.user') && !hasPermission('dashboard.admin') )
                @include('user-dashboard');
            @endif
            
        </div>
    </div>

    <footer class="footer-wrapper p-4 text-center mt-auto" style="background: #ffffff; border-top: 1px solid #e2e8f0;">
        <p>@php date_default_timezone_set('Asia/Dhaka') @endphp Copyright © {{ date('Y') }}<a href="https://gmebd.com" target="__blank"> Global Medical Engineering (BD) Ltd</a>. All rights reserved</p>
    </footer>
@endsection

@section('page_scripts')
    @stack('script')
    <script> 
        $(document).ready(function() {
  
            function loadSummary(btnId,type) {

                $.ajax({
                    url: "{{ route('dashboard.summary') }}",
                    type: "GET",
                    data: { type: type },
                    success: function(response) {
                        //console.log(response);

                        // Safely convert to Number
                        var currentSales = Number(response.currentSales) || 0;
                        var previousSales = Number(response.previousSales) || 0;

                        var currentPurchase = Number(response.currentPurchase) || 0;
                        var previousPurchase = Number(response.previousPurchase) || 0;

                        var currentCollection = Number(response.currentCollection) || 0;
                        var previousCollection = Number(response.previousCollection) || 0;

                        var currentPayment = Number(response.currentPayment) || 0;
                        var previousPayment = Number(response.previousPayment) || 0;

                        // Update DOM
                        $('#sales-title').text(type.toUpperCase()+' Sales');
                        $('#total-sales').text(currentSales.toFixed(2));
                        $('#pre-total-sales').text('PRV: '+previousSales.toFixed(2));

                        $('#purchase-title').text(type.toUpperCase()+' Purchase');
                        $('#total-purchases').text(currentPurchase.toFixed(2));
                        $('#pre-total-purchases').text('PRV: '+previousPurchase.toFixed(2));

                        $('#collection-title').text(type.toUpperCase()+' Collection');
                        $('#total-collections').text(currentCollection.toFixed(2));
                        $('#pre-total-collections').text('PRV: '+previousCollection.toFixed(2));

                        $('#payment-title').text(type.toUpperCase()+' Payment');
                        $('#total-payments').text(currentPayment.toFixed(2));
                        $('#pre-total-payments').text('PRV: '+previousPayment.toFixed(2));

                        // Active button
                        $('.btn-group button').removeClass('active');
                        $('#btn' + type.charAt(0).toUpperCase() + type.slice(1)).addClass('active');
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax Error:', error);
                    }
                }); 
               
            }

            // Default load (Daily)
            loadSummary('btnDaily','daily');

            $('#btnDaily').click(function() {
                loadSummary('btnDaily','daily');
            });

            $('#btnMonthly').click(function() {
                loadSummary('btnMonthly','monthly');
            });

            $('#btnYearly').click(function() {
                loadSummary('btnYearly','yearly');
            });
 

        });

        
 
    </script>
@endsection

 