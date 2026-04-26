@section('title', 'Condition Amount Collection')
@section('description', 'Condition Amount Collection List')
@extends('layout.app')

@section('content')
    <div class="container-fluid">
        {{-- Responsive Header & Metrics --}}
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-main flex-column flex-md-row align-items-center justify-content-between">
                    <div class="breadcrumb-action">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2 mb-md-0">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Condition Amount Collection</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="breadcrumb-main__wrapper mt-3 mt-md-0">
                        <div class="action-btn d-flex flex-wrap justify-content-center align-items-center gap-3">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold me-2">Received Courier No:</span>
                                <span class="badge badge-round badge-primary fs-14 fs-lg-16 pointer px-3"
                                    data-bs-toggle="modal" data-bs-target="#receivedCourierDetailsModal">
                                    {{ $metrics['received_count'] ?? 0 }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="fw-bold me-2">Received Amount:</span>
                                <span class="badge badge-round badge-success fs-14 fs-lg-16 px-3">
                                    {{ number_format($metrics['received_amount'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                            <h5 class="mb-0 text-dark fw-bold">Condition Amount Collections</h5>
                            <button type="button" class="btn btn-success d-flex align-items-center justify-content-center"
                                id="receiveTogetherBtn" disabled>
                                <i class="las la-check-circle me-2 fs-18"></i> Receive Together
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0 p-sm-3">
                        <div class="table-responsive">
                            <style>
                                .condition-table-custom {
                                    width: 100% !important;
                                    margin-bottom: 0 !important;
                                }

                                .condition-table-custom th,
                                .condition-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    padding: 10px 15px !important;
                                    vertical-align: middle !important;
                                    font-size: 0.875rem;
                                    /* Better for laptop density */
                                }

                                .condition-table-custom thead th {
                                    background-color: #f8f9fa;
                                    white-space: nowrap;
                                    font-weight: 700;
                                }

                                .text-wrap-column {
                                    min-width: 150px;
                                    max-width: 250px;
                                    white-space: normal !important;
                                    word-break: break-word;
                                }

                                .table-responsive::-webkit-scrollbar {
                                    height: 8px;
                                }

                                .table-responsive::-webkit-scrollbar-thumb {
                                    background: #ccc;
                                    border-radius: 4px;
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

                            <table id="zero-config" class="table condition-table-custom dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $conditionAmountCollects])'>
                                <thead>
                                    <tr>
                                        <th class="text-center no-content">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Customer Name</th>
                                        <th>Courier</th>
                                        <th>Inv Date</th>
                                        <th>Inv Amt</th>
                                        <th>Cond Amt</th>
                                        <th>Courier Info</th>
                                        <th class="text-center">Image</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conditionAmountCollects as $item)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input row-checkbox"
                                                        name="selected_items[]" value="{{ $item->id }}"
                                                        id="checkbox_{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td class="text-wrap-column">
                                                <a href="{{ route('sales.sales-orders.show', $item->sales_order_id) }}"
                                                    target="_blank" class="fw-bold text-primary">
                                                    {{ $item->customer->company_name }}
                                                </a>
                                            </td>
                                            <td class="text-wrap-column">{{ $item->courier->courier_name }}</td>
                                            <td class="text-nowrap">{{ $item->salesOrder->invoice_date ?? '' }}</td>
                                            <td class="fw-bold">{{ number_format($item->invoice_amount) }}</td>
                                            <td class="fw-bold text-success">{{ number_format($item->condition_amount) }}</td>
                                            <td class="text-wrap-column" style="font-size: 0.8rem;">
                                                @php
                                                    $sv = $item->shipmentVerify;
                                                    $info = [];
                                                    if ($sv->service_charge > 0)
                                                        $info[] = "<b>Service:</b> {$sv->service_charge}";
                                                    if ($sv->delivery_charge > 0)
                                                        $info[] = "<b>Delivery:</b> {$sv->delivery_charge}";
                                                    if ($sv->cartoon_no)
                                                        $info[] = "<b>Carton:</b> {$sv->cartoon_no}";
                                                @endphp
                                                {!! implode('<br>', $info) !!}
                                            </td>
                                            <td class="text-center">
                                                @if($item->shipmentVerify && $item->shipmentVerify->files)
                                                    <button class="btn btn-outline-light btn-sm view-images border"
                                                        data-files="{{ json_encode($item->shipmentVerify->files) }}">
                                                        <i class="las la-image text-primary fs-18"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <form action="{{ route('sales.condition-amount-collects.store') }}"
                                                        method="POST" class="received-form">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <button type="button"
                                                            class="btn btn-success btn-sm btn-received">Recv</button>
                                                    </form>
                                                    <a href="{{ asset('/assets/pdf/Claim_Page.pdf') }}"
                                                        class="btn btn-info btn-sm" target="_blank">Claim</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light fw-bold">
                                    <tr>
                                        <td colspan="4" class="text-end">Grand Total:</td>
                                        <td>{{ number_format($grandTotalInvAmt ?? 0) }}</td>
                                        <td class="text-success">{{ number_format($grandTotalCondAmt ?? 0) }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals (Full width on mobile, optimized for Laptop) --}}
    <div class="modal fade" id="receivedCourierDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Received Courier Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="receivedCourierModalBody">
                    <div class="d-flex justify-content-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fileViewerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" id="fileViewerModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // UI Interaction Handlers
            $('#selectAll').on('change', function () {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                updateReceiveTogetherButton();
            });

            $(document).on('change', '.row-checkbox', function () {
                $('#selectAll').prop('checked', $('.row-checkbox:checked').length === $('.row-checkbox').length);
                updateReceiveTogetherButton();
            });

            function updateReceiveTogetherButton() {
                $('#receiveTogetherBtn').prop('disabled', $('.row-checkbox:checked').length === 0);
            }

            // Action Handlers
            $('.btn-received').on('click', function () {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Confirm Receipt?',
                    text: "Move this to the Received list.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Yes, Received!'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });

            // Image Viewer Logic
            $('.view-images').on('click', function (e) {
                e.preventDefault();
                const files = $(this).data('files');
                if (!files || files.length === 0) return Swal.fire('Info', 'No files attached.', 'info');

                const getUrl = (f) => f.startsWith('http') ? f : '{{ asset("") }}' + (f.startsWith('/') ? f.substring(1) : f);
                const isImg = (f) => /\.(jpg|jpeg|png|gif|webp)$/i.test(f);

                let html = '<div class="row g-3">';
                files.forEach(file => {
                    const url = getUrl(file);
                    if (isImg(file)) {
                        html += `<div class="col-sm-6"><img src="${url}" class="img-fluid rounded border shadow-sm" style="max-height:400px; width:100%; object-fit:contain;"></div>`;
                    } else {
                        html += `<div class="col-12"><a href="${url}" target="_blank" class="btn btn-outline-primary w-100">Open Document</a></div>`;
                    }
                });
                html += '</div>';
                $('#fileViewerModalBody').html(html);
                $('#fileViewerModal').modal('show');
            });

            // Load Received Details
            $('#receivedCourierDetailsModal').on('show.bs.modal', function () {
                $('#receivedCourierModalBody').load('{{ route("sales.condition-amount-collects.received-details") }}');
            });
        });
    </script>
@endsection