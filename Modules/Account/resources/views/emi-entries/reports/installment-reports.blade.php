@extends('layout.app')
@section('title', 'EMI Installment Report')
@section('description', 'EMI Installment Report')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('EMI Installment Report') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                        @if (hasPermission('account.emi-installment-report'))
                        <button id="export-pdf-btn" class="btn btn-danger btn-sm" style="margin-left: 5px;" disabled>
                            <i class="las la-file-pdf fs-16"></i> PDF
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('EMI Installment Report') }}</h4>
                <x-error-alart />
            </div>

            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form id="report-form">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatmonth" name="month"
                                                        value="{{ request('month') }}" autocomplete="off"
                                                        placeholder="Select Month" id="month_picker" />
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button type="button" class="btn btn-xs btn-primary" id="generate-report">
                                                        <i class="fa fa-cog"></i> Generate
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-warning" id="refresh-report">
                                                        <i class="fa fa-refresh"></i> Refresh
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Date Buttons -->
                        <div class="mb-3">
                            <div id="dateTabs" class="btn-group mb-3" role="group"></div>
                        </div>

                        <!-- Report Table -->
                        <div class="tab-content" id="dateTabContent">
                            <div class="table-responsive">
                                <table id="emi-report-table" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Phone No</th>
                                            <th>Balance</th>
                                            <th>Installment Amount</th>
                                            <th>Ins Date</th>
                                            <th>Cheque No</th>
                                            <th>Pay Status</th>
                                            <th>Pay Date</th>
                                            <th>Pay Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="report-body"></tbody>
                                </table>
                            </div>
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
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#emi-report-table').DataTable({
        searching: true,
        paging: false,
        info: false,
        language: {
            searchPlaceholder: "Search records...",
            emptyTable: "No data available. Please select a month and generate the report."
        }
    });

    // Store data
    let allData = [];
    let selectedFilterDate = null;
    let currentMonth = null;

    // Generate Report
    $('#generate-report').on('click', function() {
        const month = $('#month_picker').val();

        if (!month) {
            toastr.error('Please select a month.');
            return;
        }

        currentMonth = month;
        selectedFilterDate = null;

        $.ajax({
            url: '{{ route('account.emi-reports.emi-report-data') }}',
            type: 'GET',
            data: {
                month: month
            },
            success: function(response) {
                // Clear existing buttons and table
                $('#dateTabs').empty();
                table.clear().draw();
                allData = response.data;

                // Enable PDF export button
                $('#export-pdf-btn').prop('disabled', false);

                // Generate date buttons
                response.dates.forEach((date, index) => {
                    const day = new Date(date).getDate();
                    const activeClass = index === 0 ? 'active' : '';
                    $('#dateTabs').append(`
                        <button type="button" class="btn btn-outline-primary date-filter-btn ${activeClass}" data-date="${date}">
                            ${day}
                        </button>
                    `);
                });

                // Populate table with data for the first date
                if (response.dates.length > 0) {
                    selectedFilterDate = response.dates[0];
                    filterTableByDate(response.dates[0]);
                    $('#clear-date-filter').show();
                } else {
                    table.clear().draw();
                    $('#clear-date-filter').hide();
                }

                toastr.success('Report generated successfully!');
            },
            error: function(xhr) {
                toastr.error('Failed to fetch report data: ' + xhr.responseJSON?.message || 'Unknown error');
                $('#export-pdf-btn').prop('disabled', true);
            }
        });
    });

    // Filter table by selected date
    $(document).on('click', '#dateTabs .date-filter-btn', function() {
        $('#dateTabs .date-filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const selectedDate = $(this).data('date');
        selectedFilterDate = selectedDate;
        filterTableByDate(selectedDate);
    });

    // Clear date filter - show all data
    $('#clear-date-filter').on('click', function() {
        $('#dateTabs .date-filter-btn').removeClass('active');
        selectedFilterDate = null;
        table.clear().draw();
        
        allData.forEach((item, index) => {
            table.row.add([
                index + 1,
                item.customer_name,
                item.address,
                item.phone,
                item.balance ? parseFloat(item.balance).toFixed() : '0.00',
                item.installment_amount ? parseFloat(item.installment_amount).toFixed() : '0.00',
                item.emi_date || 'N/A',
                item.cheque_no || 'N/A',
                item.pay_status || 'Due',
                item.pay_date || 'N/A',
                item.pay_amount ? parseFloat(item.pay_amount).toFixed() : '0.00'
            ]).draw();
        });

        toastr.info('Date filter cleared!');
    });

    // Function to filter table data by date
    function filterTableByDate(date) {
        table.clear().draw();
        const filteredData = allData.filter(item => item.emi_date === date);
        filteredData.forEach((item, index) => {
            table.row.add([
                index + 1,
                item.customer_name,
                item.address,
                item.phone,
                item.balance ? parseFloat(item.balance).toFixed() : '0.00',
                item.installment_amount ? parseFloat(item.installment_amount).toFixed() : '0.00',
                item.emi_date || 'N/A',
                item.cheque_no || 'N/A',
                item.pay_status || 'Due',
                item.pay_date || 'N/A',
                item.pay_amount ? parseFloat(item.pay_amount).toFixed() : '0.00'
            ]).draw();
        });
    }

    // PDF Export handler
    $('#export-pdf-btn').on('click', function() {
        const month = $('#month_picker').val();
        
        if (!month) {
            toastr.error('Please select a month and generate the report first.');
            return;
        }

        let pdfUrl = '{{ route('account.emi-reports.emi-report-data') }}?month=' + encodeURIComponent(month) + '&export=pdf';
        
        // Add filter date if selected
        if (selectedFilterDate) {
            pdfUrl += '&filter_date=' + encodeURIComponent(selectedFilterDate);
        }

        window.open(pdfUrl, '_blank');
    });

    // Refresh Report
    $('#refresh-report').on('click', function() {
        $('#report-form')[0].reset();
        $('#dateTabs').empty();
        $('#emi-report-table tbody').empty();
        table.clear().draw();
        allData = [];
        selectedFilterDate = null;
        currentMonth = null;
        $('#export-pdf-btn').prop('disabled', true);
        $('#clear-date-filter').hide();
        toastr.info('Report cleared!');
    });
});
</script>
@endsection

<style>
    .my-header {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .my-header img {
        max-width: 100px;
        margin-right: 20px;
    }
    .my-header h1 {
        margin: 0;
        font-size: 50px;
        font-weight: bold;
        color: rgb(0, 0, 187);
    }
    .my-header p {
        margin: 5px 0;
        font-size: 12px;
    }
</style>