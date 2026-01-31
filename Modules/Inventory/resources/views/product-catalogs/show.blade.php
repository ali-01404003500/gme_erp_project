@section('title', 'Product Catalog Information')
@section('description', 'Product Catalog Information')
@extends('layout.app')
@section('content')

    <style>
        .catalog-container {
            width: 100%;
            margin: 50px auto;
            background-color: #ffffff;
            padding-left: 40px;
            padding-right: 40px;
            padding-bottom: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 44px !important;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .product-card {
            display: flex;
            flex-wrap: wrap;
            margin-top: 28px;
            padding: 20px;
            border-radius: 10px;

        }

        .product-image {
            flex: 1;
            min-width: 100%;
            max-width: 100%;

        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .product-details {
            flex: 2;
            min-width: 250px;
            padding-left: 20px;
            max-height: 500px;
        }

        .product-details h2 {
            color: #007BFF;
            margin-top: 0;
            font-size: 24px;
        }

        .product-details p {
            margin: 10px 0;
            font-size: 16px;
        }

        .product-details span {
            font-weight: bold;
            color: #555;
        }

        .keyword-description {
            margin-top: 20px;
        }

        .keyword-description p {
            margin: 5px 0;
        }

        .uploaded-images {
            width: 100%;
            margin-top: 20px;
        }

        .uploaded-images h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .images-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .images-container img {
            width: 250px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>

    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
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
                                 <a href="{{ route('inv.product-catalogs.show', $productCatalog->id) }}?export=pdf" target="_blank"
                                    class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a>
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


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 1px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.product-catalog-view-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">

                            <body>
                                <div class="catalog-container">

                                    {{-- Company title Start --}}

                                    <div class="header" style="width: 100%; margin-bottom: 10px; position: relative; overflow: hidden;">
                                        <div style="display: flex; width:100%; transform:skewX(35deg); position: absolute;">
                                            <div
                                                style="height: 100px; width: 14%; border-top:4px solid rgb(0, 0, 179); border-right:4px solid rgb(0, 0, 179); ">
                                            </div>
                                            <div style="height: 100px; width: 86%; border-bottom:4px solid rgb(0, 0, 179); ">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 com-logo">
                                                <img src="{{ $company_info->company_logo }}"
                                                    alt="{{ $company_info->company_logo }}" style="max-width: 100px; margin-left: 40px;margin-top: 10px;">
                                            </div>
                                            <div class="com-info col-md-9" style="display: flex; align-items: left; justify-content: left; text-align: left;">
                                                <div class="com" style="display: flex; margin-left: -65px; flex-direction: column;">
                                                    <h1 width="200px" style="color: rgb(13, 13, 92);">{{ $company_info->company_name }}</h1>
                                                    <p width="50px" style="color:rgb(226, 35, 35); margin-left:5px;">
                                                        {{ $company_info->company_bio }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Company title End --}}

                                    <div class="product-card">
                                        <div class="row product-image">
                                            <div class="col-md-3">
                                               @if($productCatalog->profile_image_upload)
                                                    <img src="{{ $productCatalog->profile_image_upload }}"
                                                        alt="Product Image">
                                               @else
                                                    <img src="{{ asset('assets\img\No-image.jpg.jpg') }}" alt="No Image Available">
                                               @endif
                                            </div>
                                            <div class="product-details col-md-9">
                                                <h2 style="font-size: 30px; margin-bottom: 20px;">Model: <span
                                                        style="font-size: 31px;">{{ $productCatalog->model }}</span>
                                                </h2>
                                                <p style="font-size: 14px; margin-bottom: 20px;">Product Name: <span
                                                        style="font-size: 16px;">{{ $productCatalog->name }}</span></p>
                                                <p style="font-size: 14px; margin-bottom: 20px;">Brand: <span
                                                        style="font-size: 16px;">{{ optional($productCatalog->brand)->name }}</span>
                                                </p>
                                                <p style="font-size: 14px; margin-bottom: 20px;">MRP: <span
                                                        style="font-size: 16px;">{{ $productCatalog->mrp }}</span></p>
                                                <p style="font-size: 14px; margin-bottom: 20px;">Unit Type: <span
                                                        style="font-size: 16px;">{{ $productCatalog->unit->name }}</span>
                                                </p>
                                                <p style="font-size: 14px; margin-bottom: 20px;">Product Origin: <span
                                                        style="font-size: 16px;">{{ $productCatalog->product_origin }}</span>
                                                </p>
                                                <p style="font-size: 14px; margin-bottom: 20px;">Warranty Period: <span
                                                        style="font-size: 16px;">{{$productCatalog->warranty_period_input}} {{ $productCatalog->warranty_period }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="keyword-description">
                                            <h2 class="mt-4 mb-4">Keyword & Descriptions</h2>
                                            <p>{!! $productCatalog->description !!}</p>
                                        </div>


                                    </div>
                                    @if(!empty($productCatalog->image_uploads) && count($productCatalog->image_uploads) > 0)
                                    <div class="uploaded-images">
                                        <h3>Catalog Files</h3>
                                        <div class="images-container">
                                            @foreach ($productCatalog->image_uploads ?? [] as $image)
                                                @php
                                                    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                                    $icon = match ($ext) {
                                                        'pdf' => 'las la-file-pdf',
                                                        'doc', 'docx' => 'las la-file-word',
                                                        'xls', 'xlsx', 'csv' => 'las la-file-excel',
                                                        default => 'las la-file',
                                                    };
                                                @endphp
                                                <a href="{{ $image }}" target="_blank">
                                                    @if ($isImage)
                                                        <img src="{{ $image }}" alt="Product Image">
                                                    @else
                                                        <div class="file-placeholder" style="width: 250px; height: 180px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; border-radius: 10px; background: #f8f9fa;">
                                                            <i class="{{ $icon }}" style="font-size: 50px; color: #5f6368;"></i>
                                                        </div>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($productCatalog->catalog_file || $productCatalog->price_list_file)
                                    <div class="uploaded-files mt-3">
                                        <h3>Price List Files</h3>
                                        <div class="images-container">
                                            @if ($productCatalog->catalog_file)
                                                <p>Catalog File:
                                                    @php
                                                        $catalogFileInfo = new \SplFileInfo($productCatalog->catalog_file);
                                                        $catalogFileExtension = strtolower($catalogFileInfo->getExtension());
                                                    @endphp
                                                    @if (in_array($catalogFileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                                                        <a href="{{ $productCatalog->catalog_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            <img src="{{ $productCatalog->catalog_file }}" width="100px" style="border: 2px solid #d1d1d1; border-radius: 5px">
                                                        </a>
                                                    @elseif (in_array($catalogFileExtension, ['pdf', 'docx', 'doc', 'xlsx', 'xls', 'csv']))
                                                        @switch($catalogFileExtension)
                                                            @case('pdf')
                                                                <a href="{{ $productCatalog->catalog_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="las la-file-pdf fs-24" style="color: red"></i> Catalog File
                                                                </a>
                                                                @break

                                                            @case('docx')
                                                            @case('doc')
                                                                <a href="{{ $productCatalog->catalog_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="las la-file-word fs-24" style="color: blue"></i> Catalog File
                                                                </a>
                                                                @break

                                                            @case('xlsx')
                                                            @case('xls')
                                                            @case('csv')
                                                                <a href="{{ $productCatalog->catalog_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="las la-file-excel fs-24" style="color: green"></i> Catalog File
                                                                </a>
                                                                @break

                                                            @default
                                                                <a href="{{ $productCatalog->catalog_file }}" target="_blank" class="btn btn-outline-primary btn-sm">View Catalog File</a>
                                                        @endswitch
                                                    @else
                                                        <a href="{{ $productCatalog->catalog_file }}" target="_blank" class="btn btn-outline-primary btn-sm">View Catalog File</a>
                                                    @endif
                                                </p>
                                            @endif
                                        </div>

                                        <div class="images-container">
                                            @if ($productCatalog->price_list_file)
                                                <p>
                                                    @php
                                                        $fileInfo = new \SplFileInfo($productCatalog->price_list_file);
                                                        $fileExtension = strtolower($fileInfo->getExtension());
                                                    @endphp
                                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                                                        <a href="{{ $productCatalog->price_list_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            <img src="{{ $productCatalog->price_list_file }}" width="100px" style="border: 2px solid #d1d1d1; border-radius: 5px">
                                                        </a>
                                                    @elseif (in_array($fileExtension, ['pdf', 'docx', 'doc', 'xlsx', 'xls', 'csv']))
                                                        @switch($fileExtension)
                                                            @case('pdf')
                                                                <a href="{{ $productCatalog->price_list_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="las la-file-pdf fs-24" style="color: red"></i> Price List
                                                                </a>
                                                                @break

                                                            @case('docx')
                                                            @case('doc')
                                                                <a href="{{ $productCatalog->price_list_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="las la-file-word fs-24" style="color: blue"></i> Price List
                                                                </a>
                                                                @break

                                                            @case('xlsx')
                                                            @case('xls')
                                                            @case('csv')
                                                                <a href="{{ $productCatalog->price_list_file }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="las la-file-excel fs-24" style="color: green"></i> Price List
                                                                </a>
                                                                @break

                                                            @default
                                                                <a href="{{ $productCatalog->price_list_file }}" target="_blank" class="btn btn-outline-primary btn-sm">View Price List</a>
                                                        @endswitch
                                                    @else
                                                        <a href="{{ $productCatalog->price_list_file }}" target="_blank" class="btn btn-outline-primary btn-sm">View Price List</a>
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </body>
                        </div>

                    </div>


                </div>
            </div>
        </div>

        <!-- Edit Modal -->

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
