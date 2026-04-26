@extends('layout.app')
@section('title', "Stock Report")
@section('description', "Stock Report for Stock in hand")
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
                                        {{ trans('menu.stock-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                {{-- @if (hasPermission('inv.settings.units.create'))
                                <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                    data-bs-target="#createModal">
                                    Add New
                                </button>
                                @endif --}}
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <button id="customExport" class="btn btn-info btn-sm d-inline-block mr-2">
                                    <i class="las la-file-download fs-16"></i> Custom Export
                                </button>

                                <a href="{{ request()->url() . '/export' }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.stock-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <style>
                                .unit-table-custom,
                                .unit-table-custom th,
                                .unit-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    border-collapse: collapse !important;
                                }

                                .unit-table-custom th,
                                .unit-table-custom td {
                                    padding: 12px;
                                    vertical-align: middle;
                                }

                                .unit-table-custom thead th {
                                    background-color: #f8f9fa;
                                    border-bottom-width: 2px !important;
                                }

                                .table thead th {
                                    background-color: #35526e !important;
                                    color: #ffffff !important;
                                    font-weight: 600 !important;
                                    text-transform: uppercase;
                                    font-size: 0.85rem !important;
                                    letter-spacing: 0.08em;
                                    border-bottom: 2px solid #2a4054 !important;
                                    padding: 14px 16px !important;
                                    vertical-align: middle;
                                    text-align: center;
                                }
                            </style>
                            <table class="table unit-table-custom dt-table-hover" style="width:100%" id="stock-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>

                                        <th>Product Name</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th class="text-center">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($stocks) --}}
                                    @foreach ($stocks as $key => $stock)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td style="word-wrap: break-word; white-space: normal; min-width: 200px;">
                                                <a href="#" class="show-product" data-bs-toggle="modal"
                                                    data-url="{{ route('inv.stocks.product-ledger', $stock->product_id) }}"
                                                    data-bs-target="#product-ledger-modal">{{ optional($stock->product)->name }}</a>
                                            </td>
                                            <td class="text-center">{{ $stock->total_in }}</td>
                                            <td class="text-center">{{ $stock->total_out }}</td>
                                            <td class="text-center">{{ $stock->stock }}</td>
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

    <!-- EDIT MODAL -->
    <div class="modal fade inputForm-modal" id="product-ledger-modal" tabindex="-1" role="dialog"
        aria-labelledby="product-ledger-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Product Ledger </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center mt-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')

    <script>
        $(document).ready(function () {

            $(document).on('click', '.show-product', function () {
                var ledgerUrl = $(this).data('url');
                $('#product-ledger-modal').find('.modal-body').loadWithSpinner(ledgerUrl);
            });

            // Custom Export Button
            initCustomPdf('customExport', 'stock-table', {
                title: 'Stock Report',
                subtitle: 'Stock in hand',
                exportExcel: true,
            });
        });
    </script>
@endsection