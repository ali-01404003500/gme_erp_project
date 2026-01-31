@section('title', 'Legal Report')
@section('description', 'View Case Report and Notice Report')
@extends('layout.app')
@section('content')
    <style>
        /* Style for horizontal tabs */
        .dm-tab.tab-horizontal .nav-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            color: #3d3d3d;
            border-radius: 5px 5px 0 0;
        }

        .dm-tab.tab-horizontal .nav-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            color: #ffffff;
        }

        /* Table styling */
        .table th,
        .table td {
            vertical-align: middle;
            padding: 10px;
        }

        .document-icon {
            cursor: pointer;
            color: var(--color-primary);
        }

        .details-btn {
            cursor: pointer;
            color: #ffffff;
            background-color: var(--color-primary);
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .table-responsive {
            overflow-x: auto;
        }

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

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h2 {
            margin: 0;
            font-size: 20px;
            text-decoration: underline;
        }
    </style>

    <div class="container-fluid">
        <div class="social-dash-wrap">
            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Legal Report') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="row" style="font-size: 12px!important;">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Legal Report') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dm-tab tab-horizontal">
                                <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $tab == 'case' ? 'active' : '' }}" id="case-report-tab"
                                            data-bs-toggle="tab" href="#case-report" role="tab"
                                            aria-controls="case-report"
                                            aria-selected="{{ $tab == 'case' ? 'true' : 'false' }}">Case Report</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $tab == 'notice' ? 'active' : '' }}" id="notice-report-tab"
                                            data-bs-toggle="tab" href="#notice-report" role="tab"
                                            aria-controls="notice-report"
                                            aria-selected="{{ $tab == 'notice' ? 'true' : 'false' }}">Notice Report</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Case Report Tab -->
                                    <div class="tab-pane fade {{ $tab == 'case' ? 'show active' : '' }}" id="case-report"
                                        role="tabpanel" aria-labelledby="case-report-tab">
                                        <div class="mt-4">
                                            <form id="case-report-form">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" id="case-report-search"
                                                                        name="search" class="form-control px-15"
                                                                        placeholder="Search..."
                                                                        value="{{ request('search') }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="mb-2 text-right">
                                                                    <a href="{{ route('legal.reports') }}?export_type=pdf&tab=case&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                        target="_blank"
                                                                        class="btn btn-danger btn-sm d-inline-block mr-2 export-pdf-case"
                                                                        style="margin-left: 5px;">
                                                                        <i class="las la-file-pdf fs-16"></i> PDF
                                                                    </a>
                                                                    <a href="{{ route('legal.reports') }}?export_type=excel&tab=case&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                        target="_blank"
                                                                        class="btn btn-success btn-sm d-inline-block export-excel-case"
                                                                        style="margin-left: 5px;">
                                                                        <i class="las la-file-excel fs-16"></i> Excel
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </form>

                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="case-report-table"
                                                            class="table table-bordered dt-table-hover" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>SL</th>
                                                                    <th>Case Info</th>
                                                                    <th>Advocate Info</th>
                                                                    <th>Legal Status</th>
                                                                    <th>Document</th>
                                                                    <th>Last Hajira Remarks</th>
                                                                    <th>Details</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($caseReportEntrys as $index => $entry)
                                                                    <tr>
                                                                        <td>{{ $caseReportEntrys->firstItem() + $index }}</td>
                                                                        <td style="max-width: 300px; word-wrap: break-word; white-space: normal;">
                                                                            Case No: {{ $entry->case_no }}<br>
                                                                            Customer: {{ optional($entry->convicts->first()->customer)->company_name ?? 'N/A' }}<br>
                                                                            Convict: {{ $entry->convicts->pluck('convict_name')->implode(', ') }}<br>
                                                                            Address: {{ $entry->convicts->pluck('convict_address')->implode(', ') }}
                                                                        </td>
                                                                        <td>
                                                                            Name: {{ $entry->advocate_name }}<br>
                                                                            Phone: {{ $entry->advocate_phone }}
                                                                        </td>
                                                                        <td>{{ $entry->status == 'running' ? 'Running' : 'Withdraw' }}</td>
                                                                        <td>
                                                                           

                                                                            @php
                                                                                $documents = is_string($entry->attachment)
                                                                                    ? json_decode($entry->attachment, true)
                                                                                    : $entry->attachment;

                                                                                // Ensure it's an array and remove null/empty values
                                                                                $documents = is_array($documents) ? array_filter($documents) : [];
                                                                            @endphp

                                                                            @if (!empty($documents))
                                                                                @foreach ($documents as $doc)
                                                                                    <a href="{{ $doc }}" target="_blank"><i class="fa fa-file" style="font-size: 24px;"></i></a>
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($entry->hajiras->last())
                                                                                Date: {{ $entry->hajiras->last()->hajira_date }}<br>
                                                                                Remarks: {{ $entry->hajiras->last()->hajira_description }}
                                                                            @else
                                                                                N/A
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <button class="details-btn"
                                                                                data-id="{{ $entry->id }}"
                                                                                onclick="showDetails({{ $entry->id }})">Details</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- Pagination Links -->
                                                    <div class="mt-3" id="case-report-pagination">
                                                        @if ($caseReportEntrys instanceof \Illuminate\Pagination\LengthAwarePaginator && $caseReportEntrys->hasPages())
                                                            {{ $caseReportEntrys->appends(['search' => request('search'), 'tab' => 'case'])->links('pagination::bootstrap-5') }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notice Report Tab -->
                                    <div class="tab-pane fade {{ $tab == 'notice' ? 'show active' : '' }}" id="notice-report"
                                        role="tabpanel" aria-labelledby="notice-report-tab">
                                        <div class="mt-4">
                                            <form id="notice-report-form">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" id="notice-report-search"
                                                                        name="search" class="form-control px-15"
                                                                        placeholder="Search..."
                                                                        value="{{ request('search') }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="mb-2 text-right">
                                                                    <a href="{{ route('legal.reports') }}?export_type=pdf&tab=notice&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                        target="_blank"
                                                                        class="btn btn-danger btn-sm d-inline-block mr-2 export-pdf-notice"
                                                                        style="margin-left: 5px;">
                                                                        <i class="las la-file-pdf fs-16"></i> PDF
                                                                    </a>
                                                                    <a href="{{ route('legal.reports') }}?export_type=excel&tab=notice&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                        target="_blank"
                                                                        class="btn btn-success btn-sm d-inline-block export-excel-notice"
                                                                        style="margin-left: 5px;">
                                                                        <i class="las la-file-excel fs-16"></i> Excel
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </form>

                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="notice-report-table"
                                                            class="table table-bordered dt-table-hover" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>SL</th>
                                                                    <th>Customer Name</th>
                                                                    <th>Convict Name</th>
                                                                    <th>Phone</th>
                                                                    <th>Address</th>
                                                                    <th>Amount</th>
                                                                    <th>Start Date</th>
                                                                    <th>Document</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($noticeReportEntrys as $index => $entry)
                                                                    <tr>
                                                                        <td>{{ $noticeReportEntrys->firstItem() + $index }}</td>
                                                                        <td>{{ optional($entry->convicts->first()->customer)->company_name ?? 'N/A' }}</td>
                                                                        <td>{{ $entry->convicts->pluck('convict_name')->implode(', ') }}</td>
                                                                        <td>{{ $entry->convicts->pluck('convict_phone')->implode(', ') }}</td>
                                                                        <td>{{ $entry->convicts->pluck('convict_address')->implode(', ') }}</td>
                                                                        <td>{{ $entry->amount }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($entry->date)->format('d-m-Y') }}</td>
                                                                        <td>
                                                                           @php
                                                                                $documents = is_string($entry->attachment)
                                                                                    ? json_decode($entry->attachment, true)
                                                                                    : $entry->attachment;

                                                                                // Ensure it's an array and remove null/empty values
                                                                                $documents = is_array($documents) ? array_filter($documents) : [];
                                                                            @endphp

                                                                            @if (!empty($documents))
                                                                                @foreach ($documents as $doc)
                                                                                    <a href="{{ $doc }}" target="_blank"><i class="fa fa-file" style="font-size: 24px;"></i></a>
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- Pagination Links -->
                                                    <div class="mt-3" id="notice-report-pagination">
                                                        @if ($noticeReportEntrys instanceof \Illuminate\Pagination\LengthAwarePaginator && $noticeReportEntrys->hasPages())
                                                            {{ $noticeReportEntrys->appends(['search' => request('search'), 'tab' => 'notice'])->links('pagination::bootstrap-5') }}
                                                        @endif
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
            </div>
        </div>
    </div>

    <!-- Schedule Update Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Legal Entry Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Customer Name:</strong> <span id="modal-customer-name"></span></p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hajira Date</th>
                                <th>Remarks</th>
                                <th>Entry By</th>
                            </tr>
                        </thead>
                        <tbody id="modal-hajira-table"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
$(document).ready(function() {
    // Initialize datepicker
    $('.datePicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });

    // Handle form submission for Case Report tab
    $('#case-report-form').on('submit', function(e) {
        e.preventDefault();
        let data = $(this).serialize() + '&tab=case';
        loadTabContent(data);
    });

    // Handle form submission for Notice Report tab
    $('#notice-report-form').on('submit', function(e) {
        e.preventDefault();
        let data = $(this).serialize() + '&tab=notice';
        loadTabContent(data);
    });

    // Client-side search with server update for Case Report
    let caseSearchTimeout;
    $('#case-report-search').on('input', function() {
        clearTimeout(caseSearchTimeout);
        caseSearchTimeout = setTimeout(function() {
            $('#case-report-form').submit();
        }, 500);
    });

    // Client-side search with server update for Notice Report
    let noticeSearchTimeout;
    $('#notice-report-search').on('input', function() {
        clearTimeout(noticeSearchTimeout);
        noticeSearchTimeout = setTimeout(function() {
            $('#notice-report-form').submit();
        }, 500);
    });

    // Handle pagination clicks for Case Report
    $(document).on('click', '#case-report-pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        
        let currentSearch = $('#case-report-search').val();
        if (currentSearch) {
            url += (url.includes('?') ? '&' : '?') + 'search=' + encodeURIComponent(currentSearch);
        }
        
        if (!url.includes('tab=')) {
            url += (url.includes('?') ? '&' : '?') + 'tab=case';
        }
        
        loadTabContent(url);
    });

    // Handle pagination clicks for Notice Report
    $(document).on('click', '#notice-report-pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        
        let currentSearch = $('#notice-report-search').val();
        if (currentSearch) {
            url += (url.includes('?') ? '&' : '?') + 'search=' + encodeURIComponent(currentSearch);
        }
        
        if (!url.includes('tab=')) {
            url += (url.includes('?') ? '&' : '?') + 'tab=notice';
        }
        
        loadTabContent(url);
    });

    // Function to load tab content
    function loadTabContent(dataOrUrl) {
        let url, data;
        
        if (typeof dataOrUrl === 'string' && dataOrUrl.startsWith('http')) {
            url = dataOrUrl;
            data = {};
        } else {
            url = '{{ route('legal.reports') }}';
            data = dataOrUrl;
        }

        $.ajax({
            url: url,
            method: 'GET',
            data: typeof dataOrUrl === 'string' && dataOrUrl.startsWith('http') ? {} : dataOrUrl,
            success: function(response) {
                let $response = $(response);
                
                let activeTab = url.includes('tab=notice') || (typeof dataOrUrl === 'string' && dataOrUrl.includes('tab=notice')) ? 'notice' : 'case';
                
                if (activeTab === 'case') {
                    let tableContent = $response.find('#case-report-table tbody').html();
                    let paginationContent = $response.find('#case-report-pagination').html();
                    
                    if (tableContent) {
                        $('#case-report-table tbody').html(tableContent);
                    }
                    if (paginationContent) {
                        $('#case-report-pagination').html(paginationContent);
                    }
                    updateExportLinks('case');
                } else {
                    let tableContent = $response.find('#notice-report-table tbody').html();
                    let paginationContent = $response.find('#notice-report-pagination').html();
                    
                    if (tableContent) {
                        $('#notice-report-table tbody').html(tableContent);
                    }
                    if (paginationContent) {
                        $('#notice-report-pagination').html(paginationContent);
                    }
                    updateExportLinks('notice');
                }
            },
            error: function(xhr) {
                console.error('AJAX error:', xhr);
                alert('An error occurred while updating the content. Please try again.');
            }
        });
    }

    // Function to update export links dynamically
    function updateExportLinks(tab) {
        let searchInput = tab === 'case' ? '#case-report-search' : '#notice-report-search';
        let search = $(searchInput).val();
        let baseUrl = '{{ route('legal.reports') }}';

        let pdfClass = '.export-pdf-' + tab;
        let excelClass = '.export-excel-' + tab;

        let pdfParams = new URLSearchParams();
        pdfParams.append('export_type', 'pdf');
        pdfParams.append('tab', tab);
        if (search) pdfParams.append('search', search);

        let pdfHref = baseUrl + '?' + pdfParams.toString();
        $(pdfClass).attr('href', pdfHref);

        let excelParams = new URLSearchParams();
        excelParams.append('export_type', 'excel');
        excelParams.append('tab', tab);
        if (search) excelParams.append('search', search);

        let excelHref = baseUrl + '?' + excelParams.toString();
        $(excelClass).attr('href', excelHref);
    }

    // Handle tab switching
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        let targetTab = $(e.target).attr('href');
        
        if (targetTab === '#notice-report') {
            let rowCount = $('#notice-report-table tbody tr').length;
            if (rowCount === 0) {
                let data = $('#notice-report-form').serialize() + '&tab=notice';
                loadTabContent(data);
            }
        }
    });
});

// Show Details Modal
function showDetails(id) {
    $.ajax({
        url: '{{ url('legal/legal-schedule') }}/' + id,
        method: 'GET',
        success: function(data) {
            console.log(data);
            
            $('#modal-customer-name').text(data.customer_name || data.convict_name.join(', '));
            let tableBody = '';
            data.remarks.forEach(function(remark) {
                tableBody += `
                    <tr>
                        <td>${remark.date}</td>
                        <td>${remark.note}</td>
                        <td>${remark.entry_by || 'N/A'}</td>
                    </tr>`;
            });
            $('#modal-hajira-table').html(tableBody);
            $('#scheduleModal').modal('show');
        },
        error: function(xhr) {
            console.error('Modal error:', xhr);
            alert('An error occurred while loading the details. Please try again.');
        }
    });
}
</script>
@endsection