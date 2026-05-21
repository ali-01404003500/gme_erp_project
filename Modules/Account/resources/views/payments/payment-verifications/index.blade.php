@section('title', 'Payment Verification List')
@section('description', 'Payment Verification List')
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Payments Verification List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Payments Verification List') }}</h4>
                </div>
                <div class="col-md-12">  
                            {{-- @dd($makePayments); --}} 
                            
                            <div class="row mb-4">

                                <div class="d-none">
                                    @foreach ($paymentVerifications as $payment)

                                        <div class="col-12 mb-3">
                                            <div class="card shadow-sm border-0">
                                                <div class="card-body">

                                                    {{-- Top Row --}}
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div>
                                                            <strong>
                                                                #{{ ($paymentVerifications->currentPage() - 1) * $paymentVerifications->perPage() + $loop->iteration }}
                                                            </strong>
                                                            <span class="ms-2 text-muted">{{ $payment->date->format('d-M-Y') }}</span>
                                                        </div>

                                                        {{-- Status --}}
                                                        @switch($payment->verified)
                                                            @case('0')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @break
                                                            @case('1')
                                                                <span class="badge bg-info">Verified</span>
                                                            @break
                                                            @case('2')
                                                                <span class="badge bg-success">Approved</span>
                                                            @break
                                                            @case('-1')
                                                                <span class="badge bg-danger">Verify Rejected</span>
                                                            @break
                                                            @case('-2')
                                                                <span class="badge bg-danger">Approval Rejected</span>
                                                            @break
                                                        @endswitch
                                                    </div>

                                                    <hr class="my-2">

                                                    {{-- Main Info Grid --}}
                                                    <div class="row">

                                                        {{-- Left Side --}}
                                                        <div class="col-md-8">

                                                            {{-- Type --}}
                                                            <p class="mb-1">
                                                                <strong>Type:</strong>
                                                                @if($payment->paymentable->paymentTo->company_name??null)
                                                                    Supplier
                                                                @elseif($payment->paymentable->customer->company_name??null)
                                                                    Customer
                                                                @elseif($payment->paymentable->paymentTo->account_name??null)
                                                                    Petty Cash
                                                                @elseif($payment->paymentable->salesCommission->broker->broker_name??null)
                                                                    Broker
                                                                @elseif($payment->paymentable??null)
                                                                    Employee
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </p>

                                                            {{-- Payment To --}}
                                                            <p class="mb-1">
                                                                <strong>Payment To:</strong> 
                                                                {{ $payment->paymentable->paymentTo->company_name ??
                                                                $payment->paymentable->paymentTo->broker_name ??
                                                                $payment->paymentable->paymentTo->name ??
                                                                $payment->paymentable->customer->company_name ??
                                                                $payment->paymentable->salesCommission->broker->broker_name ??
                                                                $payment->paymentable->employee->full_name ??
                                                                $payment->paymentable->paymentTo->account_name ??
                                                                'N/A' }}
                                                            </p>

                                                            {{-- Approver Info --}}
                                                            <p class="mb-1">
                                                                <strong>Entry:</strong> {{ $payment->paymentable->createdBy?->name ?? 'N/A' }}<br>

                                                                @if($payment->verifiedBy)
                                                                    <strong>Verify:</strong> {{ $payment->verifiedBy->name }}<br>
                                                                @endif

                                                                @if($payment->approvedBy)
                                                                    <strong>Approve:</strong> {{ $payment->approvedBy->name }}
                                                                @endif
                                                            </p>

                                                            {{-- Remarks --}}
                                                            @if($payment->remark)
                                                                <p class="text-muted small mt-2 mb-0">
                                                                    Note: {!! nl2br(wordwrap($payment->remark, 100)) !!}
                                                                </p>
                                                            @endif

                                                        </div>

                                                        {{-- Right Side --}}
                                                        <div class="col-md-4 text-md-end mt-2 mt-md-0">

                                                            <p class="mb-1"><strong>Pay Mode:</strong> {{ $payment->pay_mode }}</p>
                                                            <p class="mb-2"><strong>Amount:</strong> {{ number_format($payment->amount) }}</p>

                                                            {{-- Attachment --}} 
                                                            @if($payment->attachments)
                                                                <div class="d-flex justify-content-md-end">
                                                                    <a href="{{ asset($payment->attachments) }}" target="_blank"
                                                                    class="btn btn-sm btn-outline-info mb-2">
                                                                        <i class="fa fa-eye"></i>  
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            {{-- Actions --}}
                                                            <div class="d-flex justify-content-md-end gap-2 flex-wrap">

                                                                @if ($payment->verified == '0' && hasPermission('account.payments.make-payments.verify'))
                                                                    <button class="btn btn-sm btn-outline-info"  title="Verify" 
                                                                            onclick="verifyPayment({{ $payment->id }})">
                                                                        Verify
                                                                    </button>

                                                                    <button class="btn btn-sm btn-outline-danger"  title="Verify Rejected"
                                                                            onclick="denyPayment({{ $payment->id }}, -1)">
                                                                        Reject
                                                                    </button>
                                                                @endif

                                                                @if ($payment->verified == '1' && hasPermission('account.payments.make-payments.approve'))
                                                                    <button class="btn btn-sm btn-outline-success" title="Approve"
                                                                            onclick="approvePayment({{ $payment->id }})">
                                                                        Approve
                                                                    </button>

                                                                    <button class="btn btn-sm btn-outline-danger"  title="Approval Rejected"
                                                                            onclick="denyPayment({{ $payment->id }}, -2)">
                                                                        Reject
                                                                    </button>
                                                                @endif

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>


                                <!-- Vertical Table -->
                                <div id="case-report-container  ">
                                    @if($paymentVerifications->count() > 0)
                                        <div class="vertical-table">
                                            @foreach ($paymentVerifications as $payment)
                                                <div class="vertical-table-row">
                                                    <!-- Header with SL and Status -->
                                                    <div class="vertical-table-header">
                                                        <div class="sl-number">SL:
                                                             #{{ ($paymentVerifications->currentPage() - 1) * $paymentVerifications->perPage() + $loop->iteration }} 
                                                        </div>
                                                        
                                                        <div> 
                                                            {{-- Status --}}
                                                            @switch($payment->verified)
                                                                @case('0')
                                                                    <span class="badge bg-warning status-badge">Pending</span>
                                                                @break
                                                                @case('1')
                                                                    <span class="badge bg-info status-badge">Verified</span>
                                                                @break
                                                                @case('2')
                                                                    <span class="badge bg-success status-badge">Approved</span>
                                                                @break
                                                                @case('-1')
                                                                    <span class="badge bg-danger status-badge">Verify Rejected</span>
                                                                @break
                                                                @case('-2')
                                                                    <span class="badge bg-danger status-badge">Approval Rejected</span>
                                                                @break
                                                            @endswitch
                                                            
                                                        </div>
                                                    </div>

                                                    <!-- Body with Grid Layout -->
                                                    <div class="vertical-table-body">
                                                        <!-- Case Info Group -->
                                                        <div class="info-group">
                                                            <div class="info-label">
                                                                <i class="las la-gavel"></i> PAYMENT INFORMATION
                                                            </div>
                                                            <div class="info-value">
                                                                <strong>{{ $payment->date->format('d-M-Y') }}</strong> 
                                                                <br> 
                                                                <strong>Type:</strong>
                                                                @if($payment->paymentable->paymentTo->company_name??null)
                                                                    Supplier
                                                                @elseif($payment->paymentable->customer->company_name??null)
                                                                    Customer
                                                                @elseif($payment->paymentable->paymentTo->account_name??null)
                                                                    Petty Cash
                                                                @elseif($payment->paymentable->salesCommission->broker->broker_name??null)
                                                                    Broker
                                                                @elseif($payment->paymentable??null)
                                                                    Employee
                                                                @else
                                                                    N/A
                                                                @endif
                                                                <br> 

                                                                {{-- Payment To --}}
                                                                <strong>Payment To:</strong> 
                                                                {{ $payment->paymentable->paymentTo->company_name ??
                                                                $payment->paymentable->paymentTo->broker_name ??
                                                                $payment->paymentable->paymentTo->name ??
                                                                $payment->paymentable->customer->company_name ??
                                                                $payment->paymentable->salesCommission->broker->broker_name ??
                                                                $payment->paymentable->employee->full_name ??
                                                                $payment->paymentable->paymentTo->account_name ??
                                                                'N/A' }} 
                                                                <br> 
                                                                <strong>Pay Mode:</strong> {{ $payment->pay_mode }} <br> 
                                                                <strong>Amount:</strong> {{ number_format($payment->amount) }}

                                                            </div>
                                                        </div>


                                                       
                                                     
                                                        <!-- Remarks Group -->
                                                        <div class="info-group">
                                                            <div class="info-label">
                                                                <i class="las la-calendar"></i> DOCUMENTS & REMARKS
                                                            </div>
                                                            <div class="info-value">
                                                                <div class="documents-area mb-2">
                                                                    {{-- Attachment --}} 
                                                                    @if($payment->attachments)
                                                                        <div class="d-flex justify-content-md-end">
                                                                            <a href="{{ asset($payment->attachments) }}" target="_blank"
                                                                            class="btn btn-sm btn-outline-info mb-2">
                                                                                <i class="fa fa-eye"></i>  
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                @if($payment->remark)
                                                                    {!! nl2br(wordwrap($payment->remark, 100)) !!}
                                                                @endif

                                                            </div>
                                                        </div>

                                                         <!-- Approver Info Group -->
                                                        <div class="info-group">
                                                            <div class="info-label">
                                                                <i class="las la-user"></i> APPROVER INFORMATION
                                                            </div>
                                                            <div class="info-value">
                                                                <strong>Entry:</strong> {{ $payment->paymentable->createdBy?->name ?? 'N/A' }}<br>
                                                                @if($payment->verifiedBy)
                                                                    <strong>Verify:</strong> {{ $payment->verifiedBy->name }}<br>
                                                                @endif

                                                                @if($payment->approvedBy)
                                                                    <strong>Approve:</strong> {{ $payment->approvedBy->name }}
                                                                @endif
                                                            </div>
                                                        </div> 

                                                        <!-- Action Group -->
                                                        <div class="info-group">
                                                            <div class="info-label">
                                                                <i class="las la-file-alt"></i> ACTION
                                                            </div>
                                                            <div class="info-value">
                                                              
                                                                {{-- Actions --}}
                                                                <div class="d-flex justify-content-md-end gap-2 flex-wrap">

                                                                    @if ($payment->verified == '0' && hasPermission('account.payments.make-payments.verify'))
                                                                        <button class="btn btn-sm btn-outline-info"  title="Verify" 
                                                                                onclick="verifyPayment({{ $payment->id }})">
                                                                            Verify
                                                                        </button>

                                                                        <button class="btn btn-sm btn-outline-danger"  title="Verify Rejected"
                                                                                onclick="denyPayment({{ $payment->id }}, -1)">
                                                                            Reject
                                                                        </button>
                                                                    @endif

                                                                    @if ($payment->verified == '1' && hasPermission('account.payments.make-payments.approve'))
                                                                        <button class="btn btn-sm btn-outline-success" title="Approve"
                                                                                onclick="approvePayment({{ $payment->id }})">
                                                                            Approve
                                                                        </button>

                                                                        <button class="btn btn-sm btn-outline-danger"  title="Approval Rejected"
                                                                                onclick="denyPayment({{ $payment->id }}, -2)">
                                                                            Reject
                                                                        </button>
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
                                            <p>No payments found</p>
                                        </div>
                                    @endif
                                </div> 
                              
                                <!-- Pagination -->
                                <div class="pagination-wrapper" id="case-report-pagination">
                                    @if ($paymentVerifications instanceof \Illuminate\Pagination\LengthAwarePaginator && $paymentVerifications->hasPages())
                                        {{ $paymentVerifications->appends(['search' => request('search'), 'tab' => 'case'])->links('pagination::bootstrap-5') }}
                                    @endif
                                </div>
                                
                           
                            </div> 
 
                            <div class="d-none">
                                <form class="updateForm" action="" method="POST">
                                    @csrf 
                                    @method('POST')
                                    <input type="hidden" name="verified" value="0">
                                </form>
                            </div>
                        
                   
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
        
 
        });

        function verifyPayment(id) {
            Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to verify this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('.updateForm').attr('action','{{ route("account.payments.payment-verifications.update", ":id") }}'.replace(':id', id));
                $('.updateForm input[name="verified"]').val('1');
                $('.updateForm').submit();
            }
            });
        }

        function approvePayment(id) {
            Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to approve this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('.updateForm').attr('action','{{ route("account.payments.payment-verifications.update", ":id") }}'.replace(':id', id));
                $('.updateForm input[name="verified"]').val('2');
                $('.updateForm').submit();
            }
            });
        }

        function denyPayment(id,v) {
            Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to deny this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deny it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('.updateForm').attr('action','{{ route("account.payments.payment-verifications.update", ":id") }}'.replace(':id', id));
                $('.updateForm input[name="verified"]').val(v);
                $('.updateForm').submit();
            }
            });
        }
    </script>
@endsection