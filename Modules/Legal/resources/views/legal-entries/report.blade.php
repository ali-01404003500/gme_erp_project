@section('title', 'Legal Report')
@section('description', 'View Case Report and Notice Report')
@extends('layout.app')
@section('content')
    <style>
        .dm-tab.tab-horizontal .nav-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            color: #3d3d3d;
            border-radius: 5px 5px 0 0;
        }

        .dm-tab.tab-horizontal .nav-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            color: #ffffff;
        }

        /* ============================================ */

        .vertical-table {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .vertical-table-row {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .vertical-table-row:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            /* transform: translateY(2px); */
        }

        .vertical-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            margin-bottom: 12px;
            /* border-bottom: 2px solid var(--color-primary); */
        }

        .sl-number {
            font-size: 18px;
            font-weight: bold;
            color: var(--color-primary);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-running {
            background: #d4edda;
            color: #155724;
        }

        .status-withdraw {
            background: #f8d7da;
            color: #721c24;
        }

        .vertical-table-body {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }

        .info-group {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            /* border-left: 3px solid var(--color-primary); */
        }

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            color: #6c757d;
            display: block;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 13px;
            color: #333;
            word-wrap: break-word;
            line-height: 1.5;
        }

        .documents-area {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .document-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.2s;
        }

        .document-link:hover {
            background: var(--color-primary);
            color: #fff;
            border-color: var(--color-primary);
        }

        .document-link i {
            font-size: 16px;
        }

        .details-btn {
            background: var(--color-primary);
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .details-btn:hover {
            opacity: 0.85;
            transform: scale(1.02);
        }

        /* Search and filter area */
        .search-area {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Loading state */
        .vertical-table.loading {
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }

        .vertical-table.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            margin: -20px 0 0 -20px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--color-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 1000;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Toast message */
        .toast-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: 8px;
            animation: slideIn 0.3s ease-out;
        }

        .toast-error {
            background: #dc3545;
            color: #fff;
        }

        .toast-success {
            background: #28a745;
            color: #fff;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 30px;
            text-align: center;
        }

        .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        /* No data */
        .no-data {
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
            border-radius: 10px;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .vertical-table-body {
                grid-template-columns: 1fr;
            }

            .vertical-table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        /* Modal improvements */
        .modal-remark-table {
            margin-top: 15px;
        }

        .modal-remark-table th {
            background: #f8f9fa;
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
            <div class="row">
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
                                            data-bs-toggle="tab" href="#case-report" role="tab" aria-controls="case-report"
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
                                            <!-- Search Area -->
                                            <div class="search-area">
                                                <form id="case-report-form">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-8">
                                                            <div class="form-group mb-0">
                                                                <input type="text" id="case-report-search" name="search"
                                                                    class="form-control"
                                                                    placeholder="Search by case number, customer, convict, address..."
                                                                    value="{{ request('search') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                                                <a href="{{ route('legal.reports') }}?export_type=pdf&tab=case&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                    target="_blank"
                                                                    class="btn btn-danger btn-sm export-pdf-case">
                                                                    <i class="las la-file-pdf"></i> PDF
                                                                </a>
                                                                <a href="{{ route('legal.reports') }}?export_type=excel&tab=case&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                    target="_blank"
                                                                    class="btn btn-success btn-sm export-excel-case">
                                                                    <i class="las la-file-excel"></i> Excel
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Vertical Table -->
                                            <div id="case-report-container">
                                                @if($caseReportEntrys->count() > 0)
                                                    <div class="vertical-table">
                                                        @foreach ($caseReportEntrys as $index => $entry)
                                                            <div class="vertical-table-row">
                                                                <!-- Header with SL and Status -->
                                                                <div class="vertical-table-header">
                                                                    <div class="sl-number">SL:
                                                                        {{ $caseReportEntrys->firstItem() + $index }}</div>
                                                                    <div>
                                                                        <span
                                                                            class="status-badge status-{{ $entry->status == 'running' ? 'running' : 'withdraw' }}">
                                                                            {{ $entry->status == 'running' ? '● Running' : '● Withdraw' }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <!-- Body with Grid Layout -->
                                                                <div class="vertical-table-body">
                                                                    <!-- Case Info Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-gavel"></i> CASE INFORMATION
                                                                        </div>
                                                                        <div class="info-value">
                                                                            <strong>Case No:</strong> {{ e($entry->case_no) }}<br>
                                                                            <strong>Customer:</strong>
                                                                            {{ e(optional($entry->convicts->first()->customer)->company_name ?? 'N/A') }}<br>
                                                                            <strong>Convict:</strong>
                                                                            {{ e($entry->convicts->pluck('convict_name')->implode(', ')) }}<br>
                                                                            <strong>Address:</strong>
                                                                            {{ e($entry->convicts->pluck('convict_address')->implode(', ')) }}
                                                                        </div>
                                                                    </div>

                                                                    <!-- Advocate Info Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-user"></i> ADVOCATE INFORMATION
                                                                        </div>
                                                                        <div class="info-value">
                                                                            <strong>Name:</strong>
                                                                            {{ e($entry->advocate_name) }}<br>
                                                                            <strong>Phone:</strong> {{ e($entry->advocate_phone) }}
                                                                        </div>
                                                                    </div>

                                                                    <!-- Last Hajira Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-calendar"></i> LAST HAJIRA
                                                                        </div>
                                                                        <div class="info-value">
                                                                            @if ($entry->hajiras->last())
                                                                                <strong>Date:</strong>
                                                                                {{ \Carbon\Carbon::parse($entry->hajiras->last()->hajira_date)->format('d-m-Y') }}<br>
                                                                                <strong>Remarks:</strong>
                                                                                {{ e($entry->hajiras->last()->hajira_description) }}
                                                                            @else
                                                                                N/A
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <!-- Documents & Action Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-file-alt"></i> DOCUMENTS & ACTION
                                                                        </div>
                                                                        <div class="info-value">
                                                                            <div class="documents-area mb-2">
                                                                                @php
                                                                                    $documents = is_string($entry->attachment)
                                                                                        ? json_decode($entry->attachment, true)
                                                                                        : $entry->attachment;
                                                                                    $documents = is_array($documents) ? array_filter($documents) : [];
                                                                                @endphp
                                                                                @if (!empty($documents))
                                                                                    @foreach ($documents as $doc)
                                                                                        <a href="{{ e($doc) }}" target="_blank"
                                                                                            class="document-link">
                                                                                            <i class="las la-file-pdf"></i> View
                                                                                        </a>
                                                                                    @endforeach
                                                                                @else
                                                                                    <span class="text-muted">No documents</span>
                                                                                @endif
                                                                            </div>
                                                                            <button class="details-btn" data-id="{{ $entry->id }}"
                                                                                onclick="showDetails({{ $entry->id }})">
                                                                                <i class="las la-info-circle"></i> View Details
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="no-data">
                                                        <i class="las la-folder-open la-3x mb-3"></i>
                                                        <p>No case reports found</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Pagination -->
                                            <div class="pagination-wrapper" id="case-report-pagination">
                                                @if ($caseReportEntrys instanceof \Illuminate\Pagination\LengthAwarePaginator && $caseReportEntrys->hasPages())
                                                    {{ $caseReportEntrys->appends(['search' => request('search'), 'tab' => 'case'])->links('pagination::bootstrap-5') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notice Report Tab -->
                                    <div class="tab-pane fade {{ $tab == 'notice' ? 'show active' : '' }}"
                                        id="notice-report" role="tabpanel" aria-labelledby="notice-report-tab">
                                        <div class="mt-4">
                                            <!-- Search Area -->
                                            <div class="search-area">
                                                <form id="notice-report-form">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-8">
                                                            <div class="form-group mb-0">
                                                                <input type="text" id="notice-report-search" name="search"
                                                                    class="form-control"
                                                                    placeholder="Search by customer, convict, address..."
                                                                    value="{{ request('search') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                                                <a href="{{ route('legal.reports') }}?export_type=pdf&tab=notice&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                    target="_blank"
                                                                    class="btn btn-danger btn-sm export-pdf-notice">
                                                                    <i class="las la-file-pdf"></i> PDF
                                                                </a>
                                                                <a href="{{ route('legal.reports') }}?export_type=excel&tab=notice&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                                    target="_blank"
                                                                    class="btn btn-success btn-sm export-excel-notice">
                                                                    <i class="las la-file-excel"></i> Excel
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Vertical Table -->
                                            <div id="notice-report-container">
                                                @if($noticeReportEntrys->count() > 0)
                                                    <div class="vertical-table">
                                                        @foreach ($noticeReportEntrys as $index => $entry)
                                                            <div class="vertical-table-row">
                                                                <!-- Header -->
                                                                <div class="vertical-table-header">
                                                                    <div class="sl-number">SL:
                                                                        {{ $noticeReportEntrys->firstItem() + $index }}</div>
                                                                </div>

                                                                <!-- Body -->
                                                                <div class="vertical-table-body">
                                                                    <!-- Customer Info Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-building"></i> CUSTOMER INFORMATION
                                                                        </div>
                                                                        <div class="info-value">
                                                                            <strong>Customer Name:</strong>
                                                                            {{ e(optional($entry->convicts->first()->customer)->company_name ?? 'N/A') }}<br>
                                                                            <strong>Convict Name:</strong>
                                                                            {{ e($entry->convicts->pluck('convict_name')->implode(', ')) }}<br>
                                                                            <strong>Phone:</strong>
                                                                            {{ e($entry->convicts->pluck('convict_phone')->implode(', ')) }}
                                                                        </div>
                                                                    </div>

                                                                    <!-- Address Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-map-marker"></i> ADDRESS
                                                                        </div>
                                                                        <div class="info-value">
                                                                            {{ e($entry->convicts->pluck('convict_address')->implode(', ')) }}
                                                                        </div>
                                                                    </div>

                                                                    <!-- Case Details Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-money-bill"></i> CASE DETAILS
                                                                        </div>
                                                                        <div class="info-value">
                                                                            <strong>Amount:</strong> Tk.
                                                                            {{ number_format($entry->amount, 2) }}<br>
                                                                            <strong>Start Date:</strong>
                                                                            {{ \Carbon\Carbon::parse($entry->date)->format('d-m-Y') }}
                                                                        </div>
                                                                    </div>

                                                                    <!-- Documents Group -->
                                                                    <div class="info-group">
                                                                        <div class="info-label">
                                                                            <i class="las la-file-alt"></i> DOCUMENTS
                                                                        </div>
                                                                        <div class="info-value">
                                                                            <div class="documents-area">
                                                                                @php
                                                                                    $documents = is_string($entry->attachment)
                                                                                        ? json_decode($entry->attachment, true)
                                                                                        : $entry->attachment;
                                                                                    $documents = is_array($documents) ? array_filter($documents) : [];
                                                                                @endphp
                                                                                @if (!empty($documents))
                                                                                    @foreach ($documents as $doc)
                                                                                        <a href="{{ e($doc) }}" target="_blank"
                                                                                            class="document-link">
                                                                                            <i class="las la-file-pdf"></i> View
                                                                                        </a>
                                                                                    @endforeach
                                                                                @else
                                                                                    <span class="text-muted">No documents</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="no-data">
                                                        <i class="las la-folder-open la-3x mb-3"></i>
                                                        <p>No notice reports found</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Pagination -->
                                            <div class="pagination-wrapper" id="notice-report-pagination">
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

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">
                        <i class="las la-calendar-check"></i> Legal Entry Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="modal-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="modal-content" style="display: none;">
                        <div class="alert alert-info">
                            <i class="las la-user"></i> <strong>Customer Name:</strong> <span
                                id="modal-customer-name"></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered modal-remark-table">
                                <thead>
                                    <tr>
                                        <th><i class="las la-calendar"></i> Hajira Date</th>
                                        <th><i class="las la-comment"></i> Remarks</th>
                                        <th><i class="las la-user"></i> Entry By</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-hajira-table"></tbody>
                            </table>
                        </div>
                    </div>
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
        // Escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Show toast
        function showToast(message, type = 'error') {
            const toast = $(`
                <div class="toast-message toast-${type}">
                    <i class="las la-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
                    ${escapeHtml(message)}
                </div>
            `);
            $('body').append(toast);
            setTimeout(() => {
                toast.fadeOut(300, () => toast.remove());
            }, 5000);
        }

        // Set loading
        function setLoading(container, isLoading) {
            if (isLoading) {
                $(container).addClass('loading');
            } else {
                $(container).removeClass('loading');
            }
        }

        $(document).ready(function () {
            // Form submissions
            $('#case-report-form').on('submit', function (e) {
                e.preventDefault();
                loadTabContent($(this).serialize() + '&tab=case');
            });

            $('#notice-report-form').on('submit', function (e) {
                e.preventDefault();
                loadTabContent($(this).serialize() + '&tab=notice');
            });

            // Search with debounce
            let caseTimeout, noticeTimeout;

            $('#case-report-search').on('input', function () {
                clearTimeout(caseTimeout);
                caseTimeout = setTimeout(() => $('#case-report-form').submit(), 500);
            });

            $('#notice-report-search').on('input', function () {
                clearTimeout(noticeTimeout);
                noticeTimeout = setTimeout(() => $('#notice-report-form').submit(), 500);
            });

            // Pagination clicks
            $(document).on('click', '#case-report-pagination a', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let search = $('#case-report-search').val();
                if (search) url += (url.includes('?') ? '&' : '?') + 'search=' + encodeURIComponent(search);
                if (!url.includes('tab=')) url += (url.includes('?') ? '&' : '?') + 'tab=case';
                loadTabContent(url);
            });

            $(document).on('click', '#notice-report-pagination a', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let search = $('#notice-report-search').val();
                if (search) url += (url.includes('?') ? '&' : '?') + 'search=' + encodeURIComponent(search);
                if (!url.includes('tab=')) url += (url.includes('?') ? '&' : '?') + 'tab=notice';
                loadTabContent(url);
            });

            // Load tab content
            function loadTabContent(dataOrUrl) {
                let url, data, activeTab;

                if (typeof dataOrUrl === 'string' && dataOrUrl.startsWith('http')) {
                    url = dataOrUrl;
                    data = {};
                    activeTab = url.includes('tab=notice') ? 'notice' : 'case';
                } else {
                    url = '{{ route('legal.reports') }}';
                    data = dataOrUrl;
                    activeTab = dataOrUrl.includes('tab=notice') ? 'notice' : 'case';
                }

                const container = activeTab === 'case' ? '#case-report-container' : '#notice-report-container';
                setLoading(container, true);

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data,
                    timeout: 30000,
                    success: function (response) {
                        const $response = $(response);

                        if (activeTab === 'case') {
                            $('#case-report-container').html($response.find('#case-report-container').html());
                            $('#case-report-pagination').html($response.find('#case-report-pagination').html());
                            updateExportLinks('case');
                        } else {
                            $('#notice-report-container').html($response.find('#notice-report-container').html());
                            $('#notice-report-pagination').html($response.find('#notice-report-pagination').html());
                            updateExportLinks('notice');
                        }
                    },
                    error: function (xhr) {
                        let msg = 'Error loading data';
                        if (xhr.status === 404) msg = 'Page not found';
                        else if (xhr.status === 500) msg = 'Server error';
                        else if (xhr.status === 0) msg = 'Network error';
                        showToast(msg, 'error');
                    },
                    complete: function () {
                        setLoading(container, false);
                    }
                });
            }

            // Update export links
            function updateExportLinks(tab) {
                const search = tab === 'case' ? $('#case-report-search').val() : $('#notice-report-search').val();
                const baseUrl = '{{ route('legal.reports') }}';

                let params = new URLSearchParams({ export_type: 'pdf', tab: tab });
                if (search) params.append('search', search);

                $(`.export-pdf-${tab}`).attr('href', baseUrl + '?' + params.toString());
                params.set('export_type', 'excel');
                $(`.export-excel-${tab}`).attr('href', baseUrl + '?' + params.toString());
            }

            // Tab switch
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                if ($(e.target).attr('href') === '#notice-report') {
                    if ($('#notice-report-container .vertical-table-row').length === 0) {
                        $('#notice-report-form').submit();
                    }
                }
            });
        });

        // Show details modal
        function showDetails(id) {
            $('#modal-loading').show();
            $('#modal-content').hide();
            $('#modal-hajira-table').html('');

            $('#scheduleModal').modal('show');

            const $btn = $(`button[onclick="showDetails(${id})"]`);
            const originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Loading...');

            $.ajax({
                url: `{{ url('legal/legal-schedule') }}/${id}`,
                method: 'GET',
                timeout: 15000,
                success: function (data) {
                    let customerName = 'N/A';
                    if (data.customer_name) customerName = escapeHtml(data.customer_name);
                    else if (data.convict_name && Array.isArray(data.convict_name)) customerName = escapeHtml(data.convict_name.join(', '));
                    else if (data.convict_name) customerName = escapeHtml(data.convict_name);

                    $('#modal-customer-name').html(customerName);

                    let tableBody = '';
                    if (data.remarks && data.remarks.length > 0) {
                        data.remarks.forEach(r => {
                            tableBody += `<tr><td>${escapeHtml(r.date || 'N/A')}</td><td>${escapeHtml(r.note || 'N/A')}</td><td>${escapeHtml(r.entry_by || 'N/A')}</td></tr>`;
                        });
                    } else {
                        tableBody = '<tr><td colspan="3" class="text-center">No remarks found</td></tr>';
                    }

                    $('#modal-hajira-table').html(tableBody);
                    $('#modal-loading').hide();
                    $('#modal-content').show();
                },
                error: function (xhr) {
                    let msg = 'Error loading details';
                    if (xhr.status === 404) msg = 'Record not found';
                    else if (xhr.status === 500) msg = 'Server error';
                    $('#modal-hajira-table').html(`<tr><td colspan="3" class="text-center text-danger">${msg}</td></tr>`);
                    $('#modal-loading').hide();
                    $('#modal-content').show();
                    showToast(msg, 'error');
                },
                complete: function () {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        }
    </script>
@endsection