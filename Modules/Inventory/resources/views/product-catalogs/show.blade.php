od@section('title', 'Product Catalog Information')
@section('description', 'Product Catalog Information')
@extends('layout.app')
@section('content')

    <style>
        :root {
            --half-inch: 0.5in;
            --main-blue: #3b5998;
        }

        body { background-color: #d1d4d7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
 
        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: none;
             position: relative;
        }

        /* --- Left Sidebar --- */
        .left-sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: .6in;
            height: 100%;
            background: var(--main-blue);
            z-index: 10;
            clip-path: polygon(0 0, 100% 0, 100% 25%, 70% 28%, 70% 65%, 100% 68%, 100% 100%, 0 100%);
            
            /* Content middle-e anar jonno */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar-url {
            color: white;
            font-size: 20px; /* Text ektu choto korle 0.5 inch sidebar-e bhalo dekhay */
            font-weight: 600;
            white-space: nowrap;
            padding-bottom: 20px;
            
            transform: rotate(-90deg);
            transform-origin: center; /* Center theke rotate hobe */
            
            /* Exact centering adjustment */
            width: 200px; /* Ekta fixed width dile alignment easy hoy */
            text-align: center;
        }

        /* --- Header Area (0.5 Inch Top) --- */
        .header-wrapper {
            margin-left: 0.5in;
            padding-top:  0.2in; /* 0.5 inch from top */
            padding-right: 0.4in;
        }

 
 
        .logo-box {
            position: relative;
            border-top: 3px solid var(--main-blue); 
        } 
        .logo-box::after{
            content:"";
            position:absolute;
            top:-3px;
            right:-2px;
            width:3px;
            height:106%;
            background:var(--main-blue);
            transform: rotate(-13deg);
            transform-origin: top center;
        }

        .company-header-text {
            text-align: left;
            border-bottom: 3px solid var(--main-blue);
            padding-bottom: 3px;
            padding-left: 3px;
        }

        .company-name {
            font-family: 'Times New Roman', Times, serif;
            color: #1a3a5f;
            font-weight: 900;
            font-size: 33px;
            margin: 0;
            line-height: 1;
        }

        .slogan {
            font-size: 12px;
            color: #ff8300;
            margin: 0;
        }

        /* --- Content Area --- */
        .main-content {
            margin-left: 0.6in;
            margin-right: 0.1in;
            margin-top: 0.1in; 
            background: #fff;
        }
        

        /* --- Footer Area (0.5 Inch Bottom) --- */
        .footer-wrapper {
            position: absolute; 
            right: 0;
            padding: 10px 0.6in;
            border-top: 2px solid #3b5998 !important;
            font-size: 10px;
            color: #333;
        }

        .footer-col { padding: 0 15px; border-right: 1px solid #ccc; }
        .footer-col:last-child { border-right: none; }

   
        @media print {

            @page {
                size: A4;
                margin: 0;
            }

            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            body * {
                visibility: hidden;
            }

            .printable-area, .printable-area * {
                visibility: visible;
            }

            .printable-area {
                position: fixed !important;
                top: 0;
                left: 0;
                width: 210mm;
                height: 297mm;
                margin: 0 !important;
                overflow: hidden; 
            }

            .no-print {
                display: none !important;
            }

            /* 🔥 FIX LEFT SIDEBAR PRINT */
            .left-sidebar {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background: #3b5998 !important;
                height: 297mm !important;
            }

            .logo-box::after {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            } 

      
                
        }

        

    </style>

    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('menu.product-catalog-view-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            <div class="row">
                            @if (hasPermission('inv.product-catalogs.show'))
                                <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #007bff; color: white; border: none; border-radius: 5px;">
                                    Print Catalog
                                </button>
                                
                                <a href="{{ route('inv.product-catalogs.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i>
                                    {{ trans('menu.product-catalog-list-menu-title') }}</a>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="a4-page printable-area">
            <div class="left-sidebar">
                <div class="sidebar-url">www.gmebd.com</div>
            </div>

            <div class="header-wrapper">
                <div class="d-flex justify-content-between align-items-end">
                    <div class="logo-box"> 
                        <img src="{{ asset('assets/img/gme-logo.png') }}"  alt="GME Logo"  style="height:65px;width:105px;">
                    </div>
                    <div class="company-header-text flex-grow-1 ms-3">
                        <h1 class="company-name">Global Medical Engineering (BD) Ltd.</h1>
                        <p class="slogan">Provider of Medical Equipment and Solutions for Hospitals, Clinics, Diagnostics and Healthcare Institutes.</p>
                    </div>
                </div>
            </div>

            <div class="main-content">  
                <div class="product-card">
                    <div class="row product-image">
                        <div class="col-md-4">
                            @if($productCatalog->profile_image_upload)
                                <img src="{{ $productCatalog->profile_image_upload }}"
                                    alt="Product Image">
                            @else
                                <img src="{{ asset('assets\img\No-image.jpg.jpg') }}" alt="No Image Available">
                            @endif
                        </div>
                        <div class="product-details col-md-8">
                            <h2 style="font-size: 30px; margin-bottom: 5px;">Model: <span
                                    style="font-size: 31px;">{{ $productCatalog->model }}</span>
                            </h2>
                            <h4 style="font-size: 14px; margin-bottom: 5px;">Name: <span
                                    style="font-size: 16px;">{{ $productCatalog->withoutModelSuffix()->name }}</span></h4>
                            <p style="font-size: 14px; margin-bottom: 5px;">Brand: <span
                                    style="font-size: 16px;">{{ optional($productCatalog->brand)->name }}</span>
                            </p>
                            <p style="font-size: 14px; margin-bottom: 5px;">MRP: <span
                                    style="font-size: 16px;">{{ $productCatalog->mrp }}</span></p>
                            <p style="font-size: 14px; margin-bottom: 5px;">Unit Type: <span
                                    style="font-size: 16px;">{{ $productCatalog->unit->name }}</span>
                            </p>
                            <p style="font-size: 14px; margin-bottom: 5px;">Product Origin: <span
                                    style="font-size: 16px;">{{ $productCatalog->product_origin }}</span>
                            </p>
                            <p style="font-size: 14px; margin-bottom: 5px;">Warranty Period: <span
                                style="font-size: 16px;">{{$productCatalog->warranty_period_input}} {{ $productCatalog->warranty_period }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="keyword-description">
                        <h2 class="mt-4 mb-4">Product Features</h2>
                        <p>{!! $productCatalog->description !!}</p>
                    </div> 

                </div> 
                 
            </div>

            <div class="footer-wrapper">
                <div class="row g-0">
                    <div class="col-7 footer-col">
                        <strong>Corporate Office :</strong><br>
                        House # 17/2, Topkhana Road (2nd Floor), Dhaka-1000, Bangladesh<br>
                        Hotline: +88 096780 20555, +88 01404 003500 | Order: +88 01404 003501<br>
                        Service: +88 01404 003535 | E-mail: info@gmebd.com, gmebd@hotmail.com
                    </div>
                    <div class="col-5 ps-3">
                        <strong>China Office :</strong><br>
                        190 # longping Road, Huate Industrial Zone<br>
                        Longgang District, Shenzhen<br>
                        GuangDong China 518116.
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
    <!-- CONTENT AREA -->
    @section('page_scripts')

        <!-- Initialize Slick Slider -->
        <script type="text/javascript">
            $(document).ready(function() {
                $('.slider').slick({
                    dots: true,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    adaptiveHeight: true
                });
            });
        </script>
    @endsection
