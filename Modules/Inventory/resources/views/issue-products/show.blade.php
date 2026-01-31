@section('title', 'Issue Product Information')
@section('description', 'Issue Product Information')
@extends('layout.app')
@section('content')
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
                                        {{ trans('menu.issue-product-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('inv.issue-products.index'))
                                    <a href="{{ route('inv.issue-products.index') }}"
                                        class="btn btn-sm btn-primary btn-add"><i class="las la-plus"></i>
                                        {{ trans('menu.issue-product-list-menu-title') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.issue-product-list-menu-title') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-3">
                                    <h2>{{ $issueProduct->warehouse_id  }}</h2>
                                    <img src="{{ $issueProduct->image_uploads }}" width="100%" alt="{{ $issueProduct->image_uploads }}" style="border: 2px solid #d1d1d1; border-radius: 5px">
                                    <div style="padding: 20px;">
                                        
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="card-body p-0">
                                        <div class="ap-product">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>Issue Date</th>
                                                            <td>{{ $issueProduct->issue_date }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Warehouse</th>
                                                            <td>{{ $issueProduct->warehouse_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Purpose</th>
                                                            <td>{{ $issueProduct->purpose_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Customer</th>
                                                            <td>{{ $issueProduct->company_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Machine</th>
                                                            <td>{{ $issueProduct->machine_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Unit Type</th>
                                                            <td>{{ $issueProduct->unit_type_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Order Number</th>
                                                            <td>{{ $issueProduct->order_number }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Remarks</th>
                                                            <td>{{ $issueProduct->remarks }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3>Products</h3>
                                            <table class="table table-bordered" id="product_info_table">
                                                <thead>
                                                    <tr>
                                                        <th>Product Catalog</th>
                                                        <th>Product Name</th>
                                                        <th>SKU (Stock Keeping Unit)</th>
                                                        <th>Unit Type</th>
                                                        <th>Quantity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($issueProduct->issueProductDetails as $key=> $issueProductDetail)
                                                        <tr>
                                                            <td>{{ $issueProductDetail->product_catalog_id }}</td>
                                                            <td>{{ $issueProductDetail->product_name }}</td>
                                                            <td>{{ $issueProductDetail->sku }}</td>
                                                            <td>{{ $issueProductDetail->unit_type_id }}</td>
                                                            <td>{{ $issueProductDetail->quantity }}</td>
                                                        </tr>
                                                        @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                

                                
                                
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <!-- Edit Modal -->

@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
    </script>
@endsection


