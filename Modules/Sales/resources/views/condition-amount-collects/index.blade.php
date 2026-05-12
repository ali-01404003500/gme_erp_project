@section('title', 'Condition Amount Collection')
@section('description', 'Condition Amount Collection List')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Condition Amount Collection</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <div class="d-flex align-items-center me-4">
                                <span class="fw-bold me-2">Received Courier No:</span>
                                <span class="badge badge-round badge-primary fs-16 pointer" data-bs-toggle="modal"
                                    data-bs-target="#receivedCourierDetailsModal">
                                    {{ $metrics['received_count'] ?? 0 }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="fw-bold me-2">Received Amount:</span>
                                <span class="badge badge-round badge-success fs-16">
                                    {{ number_format($metrics['received_amount'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h5>Condition Amount Collections</h5>
                            <button type="button" class="btn btn-success" id="receiveTogetherBtn" disabled>
                                <i class="las la-check-circle"></i> Receive Together
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="zero-config" class="table table-bordered dt-table-hover" style="width:100%" data-page='@include('utils.table_paginate', ['data' => $conditionAmountCollects])'>
                                <thead>
                                    <tr>
                                        <th class="text-center no-content" style="width: 3%">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                                <label class="form-check-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 5%">SL</th>
                                        <th>Customer Name</th> 
                                        <th>Courier</th>
                                        <th class="d-none">Inv Date</th>
                                        <th>Inv Amt</th>
                                        <th>Cond Amt</th>
                                        <th class="d-none">Courier Info (Service, Delivery, Other, Carton No)</th>
                                        <th>Rcpt No</th>
                                        <th>Rcpt Date</th>
                                        <th>Image</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conditionAmountCollects as $key => $item)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input row-checkbox" name="selected_items[]" value="{{ $item->id }}" id="checkbox_{{ $item->id }}">
                                                    <label class="form-check-label" for="checkbox_{{ $item->id }}"></label>
                                                </div>
                                            </td> 
                                            <td class="text-center">{{ ($conditionAmountCollects->currentPage() - 1) * $conditionAmountCollects->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                <a href="{{ route('sales.sales-orders.show', $item->sales_order_id) }}"
                                                    target="_blank">
                                                    {{ $item->customer->company_name }}
                                                </a><br>
                                                <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ $item->customer->area->area }}</small> 
                                            </td> 
                                            <td>{{ $item->courier->courier_name }}</td>
                                            <td class="d-none">{{ $item->salesOrder->invoice_date ?? '' }}</td>
                                            <td class="text-end">{{ number_format($item->invoice_amount) }}</td>
                                            <td class="text-end">{{ number_format($item->condition_amount) }}</td>
                                            <td class="d-none">
                                                <!-- Logic to display Service/Delivery/Other charges and Carton No from Shipment Verify -->
                                                @php
                                                    $sv = $item->shipmentVerify;
                                                    $info_parts = [];
                                                    if ($sv->service_charge > 0)
                                                        $info_parts[] = 'Service Charge:' . $sv->service_charge;
                                                    if ($sv->delivery_charge > 0)
                                                        $info_parts[] = 'Delivery Charge:' . $sv->delivery_charge;
                                                    if ($sv->other_charge > 0)
                                                        $info_parts[] = 'Other Charge:' . $sv->other_charge;
                                                    if ($sv->cartoon_no)
                                                        $info_parts[] = 'Carton No:' . $sv->cartoon_no;
                                                @endphp
                                                {!! implode('<br>', $info_parts) !!}
                                            </td>
                                            <td> 
                                                @if(!empty($item->courier->web_link))
                                                    <a  href="{{ $item->courier->web_link  ?? '#' }}" target="_blank">
                                                        {{ $item->shipmentVerify->receipt_no ?? '' }}
                                                    </a> 
                                                @else
                                                    {{ $item->shipmentVerify->receipt_no ?? '' }}
                                                @endif

                                            </td>

                                            <td>{{ $item->shipmentVerify->receive_date ?? '' }}</td>
                                            <td>
                                                @if($item->shipmentVerify && $item->shipmentVerify->files)
                                                    <!-- Assuming files is an array of paths -->
                                                    <a href="#" class="view-images"
                                                        data-files="{{ json_encode($item->shipmentVerify->files) }}">
                                                        <i class="las la-image fs-20"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <form action="{{ route('sales.condition-amount-collects.store') }}"
                                                        method="POST" class="received-form">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <button type="button" class="btn btn-success btn-sm btn-received">
                                                            Received
                                                        </button>
                                                    </form>
                                                    <a href="{{ "/assets/pdf/Claim_Page.pdf" }}" class="btn btn-info btn-sm" target="_blank">Claim</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tfoot>
                                        <tr class="fw-bold" style="background-color: #f8f9fa;">
                                            <td colspan="4" class="text-end">Grand Total:</td>
                                            <td>{{ number_format($grandTotalInvAmt ?? 0) }}</td>
                                            <td>{{ number_format($grandTotalCondAmt ?? 0) }}</td>
                                            <td colspan="5"></td>
                                        </tr>
                                    </tfoot>
                                </tbody>
                            </table>
                            {{-- <div class="d-flex justify-content-end mt-3">
                                {{ $conditionAmountCollects->links() }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Received Courier Details Modal -->
    <div class="modal fade" id="receivedCourierDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Received Courier Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receivedCourierModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Viewer Modal -->
    <div class="modal fade" id="fileViewerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fileViewerModalBody">
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Select All checkbox functionality
            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Individual checkbox change - update select all checkbox state
            $(document).on('change', '.row-checkbox', function() {
                const totalCheckboxes = $('.row-checkbox').length;
                const checkedCheckboxes = $('.row-checkbox:checked').length;

                $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);

                // Enable/disable receive together button based on selection
                updateReceiveTogetherButton();
            });

            // Update receive together button state
            function updateReceiveTogetherButton() {
                const selectedCount = $('.row-checkbox:checked').length;
                $('#receiveTogetherBtn').prop('disabled', selectedCount === 0);
            }

            // Receive Together button click handler
            $('#receiveTogetherBtn').on('click', function() {
                const selectedItems = [];
                $('.row-checkbox:checked').each(function() {
                    selectedItems.push($(this).val());
                });

                if (selectedItems.length === 0) {
                    Swal.fire('No Selection', 'Please select at least one item to mark as received.', 'warning');
                    return;
                }

                // Show confirmation modal
                Swal.fire({
                    title: 'Mark Selected as Received',
                    html: `
                        <div class="text-start">
                            <p>Selected items: ${selectedItems.length}</p>
                            <p>Are you sure you want to mark these condition amount collections as received?</p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, mark as received!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mark selected items as received
                        markSelectedAsReceived(selectedItems);
                    }
                });
            });

            // Function to mark selected items as received
            function markSelectedAsReceived(selectedItems) {
                $.ajax({
                    url: '{{ route("sales.condition-amount-collects.bulk-receive") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: selectedItems
                    },
                    beforeSend: function() {
                        Swal.showLoading();
                    },
                    success: function(response) {
                        Swal.fire('Success', response.message || 'Items marked as received successfully!', 'success');
                        location.reload(); // Reload to update the table
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to mark items as received.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            }

            $('.btn-received').on('click', function () {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to mark this as Received. This will move it to the Received list.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Received it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Load Received Details into Modal
            $('#receivedCourierDetailsModal').on('show.bs.modal', function () {
                $('#receivedCourierModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');

                $.ajax({
                    url: '{{ route("sales.condition-amount-collects.received-details") }}',
                    method: 'GET',
                    success: function (response) {
                        $('#receivedCourierModalBody').html(response);
                    },
                    error: function () {
                        $('#receivedCourierModalBody').html('<div class="alert alert-danger">Failed to load details.</div>');
                    }
                });
            });

            // Received Back Action
            $(document).on('click', '.btn-received-back', function () {
                let id = $(this).data('id');
                let row = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to move this back to the collection list?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Revert it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("sales.condition-amount-collects.received-back") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function (response) {
                                if (response.success) {
                                    row.fadeOut(300, function () {
                                        $(this).remove();
                                        if ($('#received-courier-table tbody tr').length === 0) {
                                            $('#received-courier-table tbody').html('<tr><td colspan="11" class="text-center">No received items found.</td></tr>');
                                        }
                                    });
                                    Swal.fire('Success', response.message, 'success').then(() => {
                                        location.reload(); // Reload to update counters/metrics
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // File Viewing Logic
            $('.view-images').on('click', function (e) {
                e.preventDefault();
                let files = $(this).data('files');

                if (!files || files.length === 0) {
                    Swal.fire('No Files', 'No files attached to this record.', 'info');
                    return;
                }

                // Helper to check if file is image
                const isImage = (file) => {
                    return /\.(jpg|jpeg|png|gif|webp|bmp)$/i.test(file);
                };

                // Helper to get full URL (assuming files are paths relative to asset root or need specific prefix)
                const getUrl = (file) => {
                    if (file.startsWith('http')) {
                        return file;
                    }
                    // Remove leading slash if present to avoid double slashes
                    let cleanFile = file.startsWith('/') ? file.substring(1) : file;
                    return '{{ asset('') }}' + cleanFile;
                };

                if (files.length === 1) {
                    let file = files[0];
                    let url = getUrl(file);

                    if (isImage(file)) {
                        // Open single image in modal
                        let html = `<img src="${url}" class="img-fluid" alt="View Image">`;
                        $('#fileViewerModalBody').html(html);
                        $('#fileViewerModal').modal('show');
                    } else {
                        // Open document in new tab
                        window.open(url, '_blank');
                    }
                } else {
                    // Multiple files
                    let html = '<div class="row g-3">';
                    files.forEach(file => {
                        let url = getUrl(file);
                        if (isImage(file)) {
                            html += `
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border">
                                        <div class="card-body p-2 text-center">
                                            <a href="${url}" target="_blank">
                                                <img src="${url}" class="img-fluid" style="max-height: 200px; object-fit: contain;" alt="Image">
                                            </a>
                                        </div>
                                    </div>
                                </div>`;
                        } else {
                            html += `
                                <div class="col-12 mb-2">
                                    <a href="${url}" target="_blank" class="btn btn-outline-primary w-100">
                                        <i class="las la-file-alt"></i> View File (${file.split('/').pop()})
                                    </a>
                                </div>`;
                        }
                    });
                    html += '</div>';

                    $('#fileViewerModalBody').html(html);
                    $('#fileViewerModal').modal('show');
                }
            });
        });

       // Add this to your HTML head:
//



    </script>
@endsection