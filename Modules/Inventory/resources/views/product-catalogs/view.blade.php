<style>
    .catalog-container {
        width: 100%;
        background-color: #ffffff;
        padding-left: 5px;
        padding-right: 5px;
        padding-bottom: 5px;
        padding-top: 0px;
    }

    .product-card {
        display: flex;
        flex-wrap: wrap;
        padding: 22px;
        padding-top: 0;
        border-radius: 10px;

    }

    .product-image {
        flex: 1;
        min-width: 100%;
        max-width: 100%;

    }

    .product-image img {
        width: 300px;
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
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        font-size: 12px;
    }

    @page {
        margin-top: 120px;
        margin-bottom: 80px;
        margin-left: 40px;
        margin-right: 40px;
    }

    header {
        position: fixed;
        top: -110px;
        left: -40px;
        right: -40px;
        height: 110px;
        background-color: #fff;
        text-align: center;
        line-height: 1.4;
    }

    footer {
        position: fixed;
        bottom: -80px;
        left: -40px;
        right: -40px;
        height: 80px;
        background-color: #fff;
        text-align: center;
        line-height: 1.3;
        border-top: 1px solid #ccc;
    }

    .content {
        margin-top: 0;
        /* Adjust based on header height */
        margin-bottom: 0px;
        /* Adjust based on footer height */
        line-height: 1.5;

    }
</style>
<div>

    {{-- <div class="card">
        <div class="card-header"> --}}
            <header>
                @include('partials._for_pdf_header')
            </header>

            <body>
                <h1 style="font-size:30px;text-align:center; padding-top:0; margin-top:0">Product Information</h1>
                <div class="catalog-container">
                    <div class="product-card">
                        <div class="row product-image">
                            <table class="table">
                                <tr>
                                    <td width="47%">
                                        @if($productCatalog->profile_image_upload)
                                            <img src="{{ s3FileToBase64($productCatalog->profile_image_upload) }}"
                                                alt="Product Image" style="width:100%;font-size:24pt;">
                                        @else
                                            {{-- <img src="https://placehold.co/600x400?text=No+Image"
                                                alt="No Image Available" style="width:100%;font-size:24pt;"> --}}
                                        @endif
                                        <p style="font-size:14pt;text-align:center;">
                                            <strong>{{ $productCatalog->name }}</strong></p>
                                    </td>
                                    <td width="53%">
                                        <table class="table" style="padding: 0;font-size:14px;">
                                            <tr style="padding: 0; margin:0;">
                                                <td style="width: 30%; font-size:22px!important;color:blue;">Model</td>
                                                <th style="width:70%;">{{ $productCatalog->model }}</th>
                                            </tr>
                                            <tr style="padding: 0; margin:0;">
                                                <td>Product Name</td>
                                                <td>{{ $productCatalog->name }}</td>
                                            </tr>
                                            <tr style="padding: 0; margin:0;">
                                                <td>Brand</td>
                                                <td>{{ optional($productCatalog->brand)->name }}</td>
                                            </tr>
                                            <tr style="padding: 0; margin:0;">
                                                <td>MRP</td>
                                                <td>{{ $productCatalog->mrp }}</td>
                                            </tr>
                                            <tr style="padding: 0; margin:0;">
                                                <td>Unit Type</td>
                                                <td>{{ $productCatalog->unit->name }}</td>
                                            </tr>
                                            <tr style="padding: 0; margin:0;">
                                                <td>Product Origin</td>
                                                <td>{{ $productCatalog->product_origin }}</td>
                                            </tr>
                                            <tr style="padding: 0; margin:0;">
                                                <td>Warranty Period</td>
                                                <td>{{$productCatalog->warranty_period_input}}
                                                    {{ $productCatalog->warranty_period }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="keyword-description">
                            <h2 class="mt-4 mb-4">Keyword & Descriptions</h2>
                            <p>{!! $productCatalog->description !!}</p>
                        </div>


                    </div>
                    <div class="uploaded-images">
                        <h3>Product Images</h3>
                        <table class="table table-bordered" style="border-color: white;">
                            <tbody>
                                @foreach (json_decode($productCatalog->image_uploads ?? "[]") as $index => $image)
                                    @if ($index % 2 == 0)
                                        <tr style="border-color: white;">
                                    @endif
                                        <td width="50%" style="border-color: white;">
                                            <img src="{{ s3FileToBase64($image) }}" width="auto"
                                                style="border: 2px solid #d1d1d1; border-radius: 5px; height:200px;">
                                        </td>
                                        @if ($index % 2 == 1 || $index == count(json_decode($productCatalog->image_uploads)) - 1)
                                            </tr>
                                        @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </body>
            {{--
        </div>

    </div> --}}


</div>