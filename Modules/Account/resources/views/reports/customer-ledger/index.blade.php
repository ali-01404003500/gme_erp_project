@extends('layout.app')

@section('title', 'Customer Ledger')
@section('description', 'Customer Ledger Report')

@section('page-head')
    <style type="text/css">
        .bg-qty { background: #5759604a; }
        .bg-value { background: #33712e45; }
        .info-card { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .info-label { font-weight: bold; }
        .clickable-link { color: #007bff; cursor: pointer; text-decoration: underline; }
        .deed-icons { position: absolute; top: 130px; right: 10px; }
        .deed-icons a { margin-left: 10px; }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .company-logo {
            flex-shrink: 0;
        }
        
        .company-logo img {
            height: 60px;
            width: auto;
            max-width: 100px;
            object-fit: contain;
        }
        
        .company-info-text {
            text-align: center;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 12px;
            margin-bottom: 3px;
            color: #555;
        }

        /* Table Styles */
        .cheque-detail-container .table td,
        .cheque-detail-container .table th,
        .refund-cheque-container .table td,
        .refund-cheque-container .table th {
            padding: 10px;
            vertical-align: middle;
        }
        
        .badge {
            font-size: 13px;
        }
        
        .bg-primary {
            background-color: #4472C4 !important;
        }
        
        .bg-danger {
            background-color: #dc3545 !important;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active">Customer Ledger</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                <h4 class="text-capitalize breadcrumb-title">Customer Ledger Report</h4>
                <div class="btn-group">
                    <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                        class="btn btn-danger btn-sm">
                        <i class="fa fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}" target="_blank"
                        class="btn btn-primary btn-sm">
                        <i class="fa fa-file-excel"></i> Excel
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%">
                                        <select id="account_id" name="account_id" class="form-control tom-select" data-placeholder="- Select Customer -" required>
                                            <option value=""></option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->getAccount()->id }}"
                                                    {{ request('account_id') == $customer->getAccount()->id ? 'selected' : '' }}>
                                                    {{ $customer->company_name }} - {{ $customer->address}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                   value="{{ request('from') ?? date('Y-m-d') }}" placeholder="Date From" autocomplete="off">
                                            <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                   value="{{ request('to') ?? date('Y-m-d') }}" placeholder="Date To" autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="btn-group">
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                <!-- Customer Details Section -->
                @if(isset($selectedCustomer))
                <div class="card mb-4 position-relative">
                    <!-- Deed Icons (Top Right) -->


                    <div class="card-body">
                        <h5 class="mb-3">Customer Details</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-row">
                                    <span class="info-label">Company Name:</span>
                                    <span>{{ $selectedCustomer->company_name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Phone:</span>
                                    <span>{{ $selectedCustomer->phone }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Area:</span>
                                    <span>{{ $selectedCustomer->area?->area }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Address:</span>
                                    <span>{{ $selectedCustomer->address }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-row">
                                    <span class="info-label">Customer Type:</span>
                                    <span>{{ $selectedCustomer->customerType?->name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">User Reference:</span>
                                    <span>{{ $selectedCustomer->userRef?->full_name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Credit Limit:</span>
                                    <span>{{ $selectedCustomer->customerSetting->first()->credit_limit ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-row">
                                    <span class="info-label">Commission Amount:</span>
                                    <span>{{ number_format($commission_amount ?? 0) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Legal Expense:</span>
                                    <span>{{ number_format($legal_expense ?? 0) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Advance Cheque:</span>
                                    <span>
                                        <span class="clickable-link" data-bs-toggle="modal" data-bs-target="#collectedChequeModal">
                                            Collected: {{ number_format($collected_cheque_amount ?? 0) }} ({{ $collected_cheque_count ?? 0 }})
                                        </span>
                                        @if(isset($refunded_cheque_count) && $refunded_cheque_count > 0)
                                        | 
                                        <span class="clickable-link" data-bs-toggle="modal" data-bs-target="#refundedChequeModal">
                                            Refund: {{ $refunded_cheque_count }}
                                        </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    @if(isset($deed_document))
                    <div class="deed-icons"> Deed :
                        <a href="{{ url($deed_document) }}" target="_blank" title="View Deed">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ url($deed_document) }}" download title="Download Deed">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Ledger Table -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="table-header-bg">
                                        <th class="text-center">Sl</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-left">Particulars</th>
                                        <th class="text-center">Reference</th>
                                        <th class="text-right pr-1">Debit</th>
                                        <th class="text-right pr-1">Credit</th>
                                        <th class="text-right pr-1">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(request('account_id'))
                                        <tr>
                                            <td colspan="6" class="text-left pl-3"><strong>Opening Balance</strong></td>
                                            <td class="text-right pr-1"><strong>{{ number_format($balance ?? 0) }}</strong></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-danger" style="font-size:16px">
                                                NO RECORDS FOUND!
                                            </td>
                                        </tr>
                                    @endif

                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        $runningBalance = $balance ?? 0;
                                    @endphp

                                    @if(isset($transactions))
                                    @foreach($transactions as $transaction)
                                        @php
                                            $debitAmount = $transaction->debit_amount;
                                            $creditAmount = $transaction->credit_amount;
                                            
                                            $runningBalance += ($debitAmount - $creditAmount);
                                            $totalDebit += $debitAmount;
                                            $totalCredit += $creditAmount;

                                            $particulars = 'N/A';
                                            if ($transaction->transactionable) {
                                                $particulars = class_basename($transaction->transactionable_type);
                                            }

                                            if($transaction->transactionable_type=='Modules\Account\Models\MFSVerification' && $transaction->balance_type=='debit')
                                                $particulars = 'MFS Charge';
                                            else if($transaction->transactionable_type=='Modules\Account\Models\MFSVerification' && $transaction->balance_type=='credit')
                                                $particulars = 'Collection';
                                            else if($transaction->transactionable_type=='Modules\Sales\Models\ShipmentVerify' && $transaction->balance_type=='debit')
                                                $particulars = 'Courier Charge'; 
                                            else if($transaction->transactionable_type==' SalesOrder Modules\Sales\Models\SalesOrder' && $transaction->balance_type=='debit')
                                                $particulars = 'Sales';
 
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                                            <td class="text-left">{{ $particulars }}</td>
                                            <td class="text-center">{!! $transaction->getClickableVoucherNo() !!}</td>
                                            <td class="text-right pr-1">{{ number_format($debitAmount) }}</td>
                                            <td class="text-right pr-1">{{ number_format($creditAmount) }}</td>
                                            <td class="text-right pr-1">{{ number_format($runningBalance) }}</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if(request('account_id'))
                                        <tr>
                                            <th colspan="4" class="text-right">Total:</th>
                                            <th class="text-right pr-1">{{ number_format($totalDebit) }}</th>
                                            <th class="text-right pr-1">{{ number_format($totalCredit) }}</th>
                                            <th class="text-right pr-1">{{ number_format($runningBalance) }}</th>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                        @if(isset($transactions))
                        @include('partials._paginate', ['data' => $transactions])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Collected Cheque Modal -->
<div class="modal fade" id="collectedChequeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advance Cheque History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="cheque-detail-container">
                    <!-- Company Header -->
                    <div class="header">
                        <div class="header-content">
                            @if(!empty($company_info->company_logo))
                            <div class="company-logo">
                                <img src="{{ url($company_info->company_logo) }}" alt="Company Logo">
                            </div>
                            @endif
                            <div class="company-info-text">
                                <div class="company-name">{{ $company_info->company_name ?? 'Company Name' }}</div>
                                <div class="company-details">{{ $company_info->company_address ?? '' }}</div>
                                <div class="company-details">
                                    Phone: {{ $company_info->company_phone ?? '' }} | 
                                    Email: {{ $company_info->company_email ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    @if(isset($selectedCustomer))
                    <div class="mb-3">
                        <h6 class="mb-2"><strong>Customer:</strong> {{ $selectedCustomer->company_name }}</h6>
                        <p class="mb-1"><strong>Phone:</strong> {{ $selectedCustomer->phone ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $selectedCustomer->address ?? 'N/A' }}</p>
                    </div>
                    @endif

                    <div class="mb-3 d-flex justify-content-end">
                        <button onclick="printCollectedCheque()" class="btn btn-info btn-sm">
                            <i class="las la-print"></i> Print
                        </button>
                    </div>

                    <div class="table-responsive" id="collectedChequeTable">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 8%;">Sl. No.</th>
                                    <th style="width: 15%;">Cheque Date</th>
                                    <th style="width: 17%;">Cheque No.</th>
                                    <th class="text-right" style="width: 15%;">Cheque Amount</th>
                                    <th style="width: 15%;">Cheque Type</th>
                                    <th class="text-center" style="width: 10%;">Attachment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($advance_cheques))
                                @foreach($advance_cheques as $index => $cheque)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($cheque['cheque_date'])->format('d-m-Y') }}</td>
                                    <td>{{ $cheque['cheque_no'] }}</td>
                                    <td class="text-right">
                                        <span class="badge badge-round badge-success p-2">{{ number_format($cheque['amount']) }}</span>
                                    </td>
                                    <td>{{ $cheque['cheque_type'] }}</td>
                                    <td class="text-center">
                                        @php
                                            $documents = is_string($cheque['document']) ? json_decode($cheque['document'], true) : $cheque['document'];
                                        @endphp
                                        @if (!empty($documents) && is_array($documents))
                                            @foreach ($documents as $doc)  
                                                <a href="{{ url($doc) }}" target="_blank">  <i class="fa fa-image"></i>  </a>
                                             
                                            @endforeach
                                        @endif
                                        {{-- @if($cheque['attachment'])
                                        <a href="{{ $cheque['attachment'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-file-image"></i>
                                        </a>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif --}}
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="text-center">No cheque data available</td>
                                </tr>
                                @endif
                            </tbody>
                            @if(isset($advance_cheques) && count($advance_cheques) > 0)
                            <tfoot class="font-weight-bold">
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total Collected Amount:</strong></td>
                                    <td class="text-right">
                                        <span class="badge badge-primary p-2" style="font-size: 14px;">
                                            {{ number_format($collected_cheque_amount ?? 0) }}
                                        </span>
                                    </td>
                                    <td colspan="2" class="text-center">
                                        <strong>Total Count: {{ $collected_cheque_count ?? 0 }}</strong>
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

<!-- Refunded Cheque Modal -->
<div class="modal fade" id="refundedChequeModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refund Cheque History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="refund-cheque-container">
                    <!-- Company Header -->
                    <div class="header">
                        <div class="header-content">
                            @if(!empty($company_info->company_logo))
                            <div class="company-logo">
                                <img src="{{ asset($company_info->company_logo) }}" alt="Company Logo">
                            </div>
                            @endif
                            <div class="company-info-text">
                                <div class="company-name">{{ $company_info->company_name ?? 'Company Name' }}</div>
                                <div class="company-details">{{ $company_info->company_address ?? '' }}</div>
                                <div class="company-details">
                                    Phone: {{ $company_info->company_phone ?? '' }} | 
                                    Email: {{ $company_info->company_email ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    @if(isset($selectedCustomer))
                    <div class="mb-3">
                        <h6 class="mb-2"><strong>Customer:</strong> {{ $selectedCustomer->company_name }}</h6>
                        <p class="mb-1"><strong>Phone:</strong> {{ $selectedCustomer->phone ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $selectedCustomer->address ?? 'N/A' }}</p>
                    </div>
                    @endif

                    <div class="mb-3 d-flex justify-content-end">
                        <button onclick="printRefundedCheque()" class="btn btn-info btn-sm">
                            <i class="las la-print"></i> Print
                        </button>
                    </div>

                    <div class="table-responsive" id="refundedChequeTable">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-danger text-white">
                                <tr>
                                    <th style="width: 8%;">Sl. No.</th>
                                    <th style="width: 12%;">Cheque Date</th>
                                    <th style="width: 12%;">Refund Date</th>
                                    <th style="width: 15%;">Cheque No.</th>
                                    <th class="text-right" style="width: 13%;">Cheque Amount</th>
                                    <th style="width: 12%;">Cheque Type</th>
                                    <th class="text-center" style="width: 10%;">Attachment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($refunded_cheques))
                                @php
                                    $totalRefundAmount = 0;
                                @endphp
                                @foreach($refunded_cheques as $index => $cheque)
                                @php
                                    $totalRefundAmount += $cheque['amount'];
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        {{ $cheque['cheque_date'] ? \Carbon\Carbon::parse($cheque['cheque_date'])->format('d-m-Y') : 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($cheque['refund_date'])->format('d-m-Y') }}</td>
                                    <td>{{ $cheque['cheque_no'] }}</td>
                                    <td class="text-right">
                                        <span class="badge badge-round badge-danger p-2">{{ number_format($cheque['amount']) }}</span>
                                    </td>
                                    <td>{{ $cheque['cheque_type'] }}</td>
                                    <td class="text-center">
                                                    @php
                                                        $documents = is_string($cheque['document'])
                                                            ? json_decode($cheque['document'], true)
                                                            : $cheque['document'];
                                                    @endphp
                                                    @if (!empty($documents) && is_array($documents))
                                                        @foreach ($documents as $doc)
                                                            <a href="{{ $doc }}" target="_blank"><i
                                                                    class="fa fa-image"></i></a>
                                                        @endforeach
                                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="text-center">No refund data available</td>
                                </tr>
                                @endif
                            </tbody>
                            @if(isset($refunded_cheques) && count($refunded_cheques) > 0)
                            <tfoot class="font-weight-bold">
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Refunded Amount:</strong></td>
                                    <td class="text-right">
                                        <span class="badge badge-danger p-2" style="font-size: 14px;">
                                            {{ number_format($totalRefundAmount ?? 0) }}
                                        </span>
                                    </td>
                                    <td colspan="2" class="text-center">
                                        <strong>Total Count: {{ $refunded_cheque_count ?? 0 }}</strong>
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


<script type="text/javascript">
// Pass PHP data to JavaScript
const companyInfo = {
    name: "{{ $company_info->company_name ?? 'Company Name' }}",
    address: "{{ $company_info->company_address ?? '' }}",
    phone: "{{ $company_info->company_phone ?? '' }}",
    email: "{{ $company_info->company_email ?? '' }}",
    logo: "{{ !empty($company_info->company_logo) ? asset($company_info->company_logo) : '' }}"
};

const selectedCustomer = {
    exists: {{ isset($selectedCustomer) ? 'true' : 'false' }},
    company_name: "{{ $selectedCustomer->company_name ?? '' }}",
    phone: "{{ $selectedCustomer->phone ?? 'N/A' }}",
    address: "{{ $selectedCustomer->address ?? 'N/A' }}"
};

function printCollectedCheque() {
    const tableContent = document.getElementById('collectedChequeTable').innerHTML;

    const printWindow = window.open('', '_blank', 'height=700,width=900');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Advance Cheque History</title>
            <meta charset="utf-8">
            <style>
                body { 
                    font-family: Arial, Helvetica, sans-serif; 
                    margin: 20px; 
                    line-height: 1.4; 
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 20px; 
                    border-bottom: 2px solid #333; 
                    padding-bottom: 15px; 
                }
                .header-content { 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    gap: 20px; 
                }
                .company-logo img { 
                    height: 70px; 
                    max-width: 120px; 
                    object-fit: contain; 
                }
                .company-info-text { 
                    text-align: center; 
                }
                .company-name { 
                    font-size: 22px; 
                    font-weight: bold; 
                    margin-bottom: 8px; 
                }
                .company-details { 
                    font-size: 13px; 
                    color: #555; 
                    margin: 4px 0; 
                }
                h2 { 
                    text-align: center; 
                    margin: 20px 0; 
                }
                .customer-info { 
                    margin: 15px 0; 
                    font-size: 14px; 
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-top: 10px; 
                }
                th, td { 
                    border: 1px solid #999; 
                    padding: 10px; 
                    vertical-align: middle;
                }
                th { 
                    background-color: #4472C4 !important; 
                    color: white !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .text-right { 
                    text-align: right; 
                }
                .text-center { 
                    text-align: center; 
                }
                .badge { 
                    padding: 5px 10px; 
                    border-radius: 4px; 
                    color: white;
                    display: inline-block;
                    font-size: 13px;
                }
                .badge-success { 
                    background-color: #28a745 !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .badge-primary { 
                    background-color: #007bff !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                tfoot td { 
                    font-weight: bold; 
                    background-color: #f8f9fa !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .btn {
                    display: none;
                }
                @media print {
                    body { 
                        margin: 10mm; 
                    }
                }
            </style>
        </head>
        <body>
    `);

    // Company Header
    printWindow.document.write(`
        <div class="header">
            <div class="header-content">
                ${companyInfo.logo ? `<div class="company-logo"><img src="${companyInfo.logo}" alt="Logo"></div>` : ''}
                <div class="company-info-text">
                    <div class="company-name">${companyInfo.name}</div>
                    <div class="company-details">${companyInfo.address}</div>
                    <div class="company-details">Phone: ${companyInfo.phone} | Email: ${companyInfo.email}</div>
                </div>
            </div>
        </div>
        <h2>Advance Cheque History</h2>
    `);

    // Customer Info
    if (selectedCustomer.exists) {
        printWindow.document.write(`
            <div class="customer-info">
                <strong>Customer:</strong> ${selectedCustomer.company_name}<br>
                <strong>Phone:</strong> ${selectedCustomer.phone}<br>
                <strong>Address:</strong> ${selectedCustomer.address}
            </div>
        `);
    }

    // Table
    printWindow.document.write(tableContent);

    printWindow.document.write(`
        </body>
        </html>
    `);

    printWindow.document.close();

    // Wait for content and images to load
    setTimeout(function() {
        printWindow.focus();
        printWindow.print();
    }, 1000);
}

function printRefundedCheque() {
    const tableContent = document.getElementById('refundedChequeTable').innerHTML;

    const printWindow = window.open('', '_blank', 'height=700,width=900');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Refund Cheque History</title>
            <meta charset="utf-8">
            <style>
                body { 
                    font-family: Arial, Helvetica, sans-serif; 
                    margin: 20px; 
                    line-height: 1.4; 
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 20px; 
                    border-bottom: 2px solid #333; 
                    padding-bottom: 15px; 
                }
                .header-content { 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    gap: 20px; 
                }
                .company-logo img { 
                    height: 70px; 
                    max-width: 120px; 
                    object-fit: contain; 
                }
                .company-info-text { 
                    text-align: center; 
                }
                .company-name { 
                    font-size: 22px; 
                    font-weight: bold; 
                    margin-bottom: 8px; 
                }
                .company-details { 
                    font-size: 13px; 
                    color: #555; 
                    margin: 4px 0; 
                }
                h2 { 
                    text-align: center; 
                    margin: 20px 0; 
                }
                .customer-info { 
                    margin: 15px 0; 
                    font-size: 14px; 
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-top: 10px; 
                }
                th, td { 
                    border: 1px solid #999; 
                    padding: 10px; 
                    vertical-align: middle;
                }
                th { 
                    background-color: #dc3545 !important; 
                    color: white !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .text-right { 
                    text-align: right; 
                }
                .text-center { 
                    text-align: center; 
                }
                .badge { 
                    padding: 5px 10px; 
                    border-radius: 4px; 
                    color: white;
                    display: inline-block;
                    font-size: 13px;
                }
                .badge-danger { 
                    background-color: #dc3545 !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                tfoot td { 
                    font-weight: bold; 
                    background-color: #f8f9fa !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .btn {
                    display: none;
                }
                @media print {
                    body { 
                        margin: 10mm; 
                    }
                }
            </style>
        </head>
        <body>
    `);

    // Company Header
    printWindow.document.write(`
        <div class="header">
            <div class="header-content">
                ${companyInfo.logo ? `<div class="company-logo"><img src="${companyInfo.logo}" alt="Logo"></div>` : ''}
                <div class="company-info-text">
                    <div class="company-name">${companyInfo.name}</div>
                    <div class="company-details">${companyInfo.address}</div>
                    <div class="company-details">Phone: ${companyInfo.phone} | Email: ${companyInfo.email}</div>
                </div>
            </div>
        </div>
        <h2>Refund Cheque History</h2>
    `);

    // Customer Info
    if (selectedCustomer.exists) {
        printWindow.document.write(`
            <div class="customer-info">
                <strong>Customer:</strong> ${selectedCustomer.company_name}<br>
                <strong>Phone:</strong> ${selectedCustomer.phone}<br>
                <strong>Address:</strong> ${selectedCustomer.address}
            </div>
        `);
    }

    // Table
    printWindow.document.write(tableContent);

    printWindow.document.write(`
        </body>
        </html>
    `);

    printWindow.document.close();

    // Wait for content and images to load
    setTimeout(function() {
        printWindow.focus();
        printWindow.print();
    }, 1000);
}
</script>
@endsection