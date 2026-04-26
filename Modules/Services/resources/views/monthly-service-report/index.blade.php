@section('title', 'Monthly Service Report')
@section('description', 'Engineer-wise Service Sales Report')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Monthly Service Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn d-flex align-items-center">
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-2">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}"
                                    class="btn btn-success btn-sm">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">
                        Monthly Service Report
                    </h4>
                </div>

                <!-- Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="las la-filter"></i> Filter Options</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('services.reports.monthly-service-reports') }}" id="monthlyReportForm">
                                <div class="row">
                                    <!-- Date Range -->
                                    <div class="col-md-8 mb-3">
                                        <label class="font-weight-bold">Date Range</label>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                value="{{ $from }}" autocomplete="off" placeholder="Start Date" required />
                                            <span class="input-group-text">
                                                <i class="fa fa-exchange-alt"></i>
                                            </span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                value="{{ $to }}" autocomplete="off" placeholder="End Date" required />
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-md-4 mb-3">
                                        <label class="font-weight-bold">&nbsp;</label>
                                        <div class="button-group d-flex">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <a href="{{ route('services.reports.monthly-service-reports') }}" class="btn btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <h6 class="mb-0"><i class="las la-table"></i> Monthly Service Report</h6>
                                <span class="badge badge-round badge-primary badge-lg">Total Engineers: {{ $totals['total_engineers'] }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm" id="monthlyServiceTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center" style="width: 5%;">SL</th>
                                            <th style="width: 35%;">Engineer Name</th>
                                            <th class="text-right" style="width: 20%;">Service Sales</th>
                                            <th class="text-right" style="width: 20%;">Spare Sales</th>
                                            <th class="text-right" style="width: 20%;">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($engineerReports as $index => $report)
                                            <!-- Engineer Summary Row -->
                                            <tr class="engineer-row" data-engineer-id="{{ $report['engineer']->id }}" style="cursor: pointer;">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="text-primary font-weight-bold toggle-details">
                                                        <i class="las la-plus-circle"></i> {{ $report['engineer']->full_name }}
                                                    </a>
                                                    <br>
                                                </td>
                                                <td class="text-right">
                                                    <strong class="text-success">৳{{ number_format($report['service_sales']) }}</strong>
                                                </td>
                                                <td class="text-right">
                                                    <strong class="text-info">৳{{ number_format($report['spare_sales']) }}</strong>
                                                </td>
                                                <td class="text-right">
                                                    <strong class="text-danger">৳{{ number_format($report['total_amount']) }}</strong>
                                                </td>
                                            </tr>
                                            
                                            <!-- Engineer Details Row (Hidden by default) -->
                                            <tr class="engineer-details" id="details-{{ $report['engineer']->id }}" style="display: none;">
                                                <td colspan="5" style="padding: 0;">
                                                    <div style="background-color: #f8f9fa; padding: 15px;">
                                                        <h6 class="mb-3">
                                                            <i class="las la-list"></i> Detailed Service Records for {{ $report['engineer']->full_name }}
                                                        </h6>
                                                        
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-sm mb-0" style="font-size: 10px;">
                                                                <thead class="bg-secondary text-white">
                                                                    <tr>
                                                                        <th class="text-center" style="width: 5%;">SL</th>
                                                                        <th style="width: 10%;">Date</th>
                                                                        <th style="width: 35%;">Customer Name</th>
                                                                        <th class="text-right" style="width: 15%;">Service Sales</th>
                                                                        <th class="text-right" style="width: 15%;">Spare Parts Sales</th>
                                                                        <th class="text-right" style="width: 15%;">Invoice Amount</th>
                                                                        <th class="text-center" style="width: 5%;">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="details-content" data-engineer-id="{{ $report['engineer']->id }}">
                                                                    <tr>
                                                                        <td colspan="7" class="text-center py-3">
                                                                            <i class="las la-spinner la-spin"></i> Loading details...
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0 mt-2">No service records found for the selected period</p>
                                                    <small class="text-muted">Try selecting a different date range</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if(count($engineerReports) > 0)
                                        <tfoot>
                                            <tr class="font-weight-bold" style="font-size: 13px;">
                                                <td colspan="2" class="text-right">
                                                    <strong class="text-primary">TOTAL SUMMARY:</strong>
                                                </td>
                                                <td class="text-right bg-success text-white">
                                                    <strong>৳{{ number_format($totals['total_service_sales']) }}</strong>
                                                </td>
                                                <td class="text-right bg-info text-white">
                                                    <strong>৳{{ number_format($totals['total_spare_sales']) }}</strong>
                                                </td>
                                                <td class="text-right bg-danger text-white">
                                                    <strong>৳{{ number_format($totals['grand_total']) }}</strong>
                                                </td>
                                            </tr>
                                            <tr class="font-weight-bold" style="font-size: 14px;">
                                                <td colspan="2" class="text-right">
                                                    <strong>GRAND TOTAL:</strong>
                                                </td>
                                                <td colspan="3" class="text-right">
                                                    <strong>৳{{ number_format($totals['grand_total']) }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        function toNumber(value) {
    return parseFloat(value.replace(/,/g, '')) || 0;
}

        $(document).ready(function() {
            // Initialize flatpickr for date inputs
            $('.flatdate').flatpickr({
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            // Form validation
            $('#monthlyReportForm').on('submit', function(e) {
                const from = $('input[name="from"]').val();
                const to = $('input[name="to"]').val();

                if (!from || !to) {
                    e.preventDefault();
                    toastr.error('Please select both start and end dates');
                    return false;
                }

                if (from > to) {
                    e.preventDefault();
                    toastr.error('Start date cannot be later than end date');
                    return false;
                }
            });

            // Toggle engineer details
            $('.engineer-row').on('click', function(e) {
                e.preventDefault();
                
                const engineerId = $(this).data('engineer-id');
                const detailsRow = $('#details-' + engineerId);
                const icon = $(this).find('.toggle-details i');
                const detailsContent = $(this).next('.engineer-details').find('.details-content');
                
                // Toggle visibility
                if (detailsRow.is(':visible')) {
                    // Hide details
                    detailsRow.hide();
                    icon.removeClass('la-minus-circle').addClass('la-plus-circle');
                } else {
                    // Show details
                    detailsRow.show();
                    icon.removeClass('la-plus-circle').addClass('la-minus-circle');
                    
                    // Load details if not already loaded
                    if (detailsContent.data('loaded') !== 'true') {
                        loadEngineerDetails(engineerId, detailsContent);
                    }
                }
            });

            // Load engineer details via AJAX
            function loadEngineerDetails(engineerId, container) {
                const from = $('input[name="from"]').val();
                const to = $('input[name="to"]').val();
                
                $.ajax({
                    url: '{{ route("services.reports.monthly-service-reports.details") }}',
                    type: 'GET',
                    data: {
                        engineer_id: engineerId,
                        from: from,
                        to: to
                    },
                    success: function(response) {
                        console.log(response);
                        
                        if (response.success) {
                            let html = '';
                            
                            if (response.data.length === 0) {
                                html = '<tr><td colspan="7" class="text-center py-3">No service records found</td></tr>';
                            } else {
                                response.data.forEach((detail, index) => {
                                    const serviceLink = '{{ route("services.service.show", ":id") }}'.replace(':id', detail.service_id);
                                    const serviceSales = toNumber(detail.service_sales);
                                    const spareSales   = toNumber(detail.spare_sales);
                                    const invoiceAmt   = toNumber(detail.invoice_amount);

                                    
                                    html += `
                                        <tr>
    <td class="text-center">${index + 1}</td>
    <td><strong>${detail.date}</strong></td>

    <td>
        <strong class="text-primary">${detail.customer_name}</strong><br>
        <small class="text-muted">Token: ${detail.token_id}</small>
    </td>

    <td class="text-right">
        ${serviceSales > 0
            ? `<a href="${serviceLink}" target="_blank" class="text-success font-weight-bold">৳${detail.service_sales}</a>`
            : '<span class="text-muted">৳0.00</span>'}
    </td>

    <td class="text-right">
        ${spareSales > 0
            ? `<a href="${serviceLink}" target="_blank" class="text-info font-weight-bold">৳${detail.spare_sales}</a>`
            : '<span class="text-muted">৳0.00</span>'}
    </td>

    <td class="text-right">
        ${invoiceAmt > 0
            ? `<a href="${serviceLink}" target="_blank" class="text-danger font-weight-bold">৳${detail.invoice_amount}</a>`
            : '<span class="text-muted">৳0.00</span>'}
    </td>

    <td class="text-center">
        <a href="${serviceLink}" target="_blank" >
            <i class="las la-file-invoice" style="font-size: 25px;" title="Service Challan"></i> 
        </a>
    </td>
</tr>

                                    `;
                                    console.log({html, detail});
                                    
                                });
                                
                                // Add total row
                                html += `
                                    <tr class="font-weight-bold" style="font-size: 11px; background-color: #e9ecef;">
                                        <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                        <td class="text-right"><strong>৳${response.totals.service_sales}</strong></td>
                                        <td class="text-right"><strong>৳${response.totals.spare_sales}</strong></td>
                                        <td class="text-right"><strong>৳${response.totals.grand_total}</strong></td>
                                        <td></td>
                                    </tr>
                                `;
                            }
                            
                            container.html(html);
                            container.data('loaded', 'true');
                        } else {
                            container.html('<tr><td colspan="7" class="text-center text-danger py-3">Error loading details</td></tr>');
                        }
                    },
                    error: function() {
                        container.html('<tr><td colspan="7" class="text-center text-danger py-3">Error loading details</td></tr>');
                    }
                });
            }
        });
    </script>
@endsection