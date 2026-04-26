@section('title', 'Product Catalog Information')
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
            width: 250mm;
            height: 297mm;
            margin: 5px auto;
            background: #fff;
            position: relative;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        /* --- Left Sidebar --- */
        .left-sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: var(--half-inch);
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
            font-size: 10px; /* Text ektu choto korle 0.5 inch sidebar-e bhalo dekhay */
            font-weight: 600;
            white-space: nowrap;
            
            transform: rotate(-90deg);
            transform-origin: center; /* Center theke rotate hobe */
            
            /* Exact centering adjustment */
            width: 200px; /* Ekta fixed width dile alignment easy hoy */
            text-align: center;
        }

        /* --- Header Area (0.5 Inch Top) --- */
        .header-wrapper {
            margin-left: 0.4in;
            padding-top:  0.2in; /* 0.5 inch from top */
            padding-right: 0.4in;
        }

        /* .logo-box {
            border: 1px solid #ccc;
            padding: 4px 15px;
            display: inline-block;
            border-radius: 0 0 35px 0;
            margin-bottom: 5px;
        } */

        .company-header-text {
            text-align: left;
            border-bottom: 1.5px solid var(--main-blue);
            padding-bottom: 3px;
        }

        .company-name {
            font-family: 'Times New Roman', Times, serif;
            color: #1a3a5f;
            font-weight: 900;
            font-size: 43px;
            margin: 0;
            line-height: 1;
        }

        .slogan {
            font-size: 15px;
            color: #ff8300;
            margin: 0;
        }

        /* --- Content Area --- */
        .main-content {
            margin-left: 0.6in;
            margin-right: 0.5in;
            margin-top: 0.4in;
            min-height: 5in;
        }

        /* --- Footer Area (0.5 Inch Bottom) --- */
        .footer-wrapper {
            position: absolute; 
            right: 0;
            padding: 10px 0.4in;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #333;
        }

        .footer-col { padding: 0 15px; border-right: 1px solid #ccc; }
        .footer-col:last-child { border-right: none; }

        @media print {
            body { background: none; margin: 0; }
            .a4-page { margin: 0; box-shadow: none; border: none; }
            .no-print { display: none; }
        }
    </style>

    <!-- CONTENT AREA -->
    <div class="container-fluid"> 
        <div class="a4-page">
            <div class="left-sidebar">
                <div class="sidebar-url">www.gmebd.com</div>
            </div>

            <div class="header-wrapper">
                <div class="d-flex justify-content-between align-items-end">
                    <div class="logo-box"> 
                        <img src="{{ asset('assets/img/gme-logo.png') }}"  alt="GME Logo"  style="height:85px;width:135px;">
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
                            <h4 style="font-size: 14px; margin-bottom: 5px;">Product Name: <span
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
