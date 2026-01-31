@extends('layout.app')
@section('title', 'Customer Ledger')
@section('page-header')
    <i class="fa fa-list"></i> Customer Ledger
@stop

@push('style')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .page-break {
            page-break-before: always;
        }
    }

    .text-center {
        text-align: center !important;
    }

    .info-section {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #f9f9f9;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding: 5px 0;
        border-bottom: 1px dotted #ddd;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: bold;
        color: #333;
    }

    .info-value {
        color: #666;
    }

    .clickable-link {
        color: #007bff;
        cursor: pointer;
        text-decoration: underline;
    }

    .deed-section {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .deed-section a {
        margin-left: 10px;
        font-size: 18px;
    }

    .header-section {
        position: relative;
        margin-bottom: 20px;
    }

    .customer-details-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    @media print {
        .customer-details-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .modal-body table {
        font-size: 14px;
    }

    .cheque-modal-print {
        margin-bottom: 15px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-sm-12">
        <x-error-alart />

        <!-- Print Button -->
        <div class="row px-1 pt-2 pb-2 text-right no-print" style="width: 100%; margin: 0 !important;">
            <div class="btn-group btn-corner">
                <button type="button" class="btn btn-danger btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Print
                </button>
                @if(request('account_id'))
                <button type="button" class="btn btn-success btn-sm" onclick="exportLedger()">
                    <i class="fa fa-file-excel"></i> Export
                </button>
                @endif
            </div>
        </div>

        <!-- Company Header -->
        @if(isset($company_info))
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-1 text-center">
                <h3 style="margin: 0; font-weight: bold;">{{ $company_info->company_name }}</h3>
                <p style="margin: 0;">{{ $company_info->address }}</p>
                <p style="margin: 0;">Phone: {{ $company_info->phone }} | Email: {{ $company_info->email }}</p>
            </div>
        </div>
        @endif

        <!-- Report Header -->
        <div class="row pb-1" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-1" style="width: 100%">
                <h4 style="background-color: #eee; padding: 12px; text-align: center; margin-top: 15px;">
                    Customer Ledger Report
                </h4>
                @if(isset($selectedCustomer))
                <h5 style="text-align: center; margin: 10px 0;">
                    {{ $selectedCustomer->company_name }}
                </h5>
                @endif
                <h6 style="text-align: center; margin: 10px 0;">
                    Date From {{ request('from') }} To {{ request('to') }}
                </h6>
            </div>
        </div>

        <!-- Customer Details Section -->
        @if(isset($selectedCustomer))
        <div class="header-section no-print" style="width: 100%; margin: 0 !important;">
            <!-- Deed Icons (Top Right) -->
            @if(isset($deed_document) && $deed_document)
            <div class="deed-section">
                <a href="{{ asset($deed_document) }}" target="_blank" title="View Deed">
                    <i class="fa fa-eye text-primary"></i>
                </a>
                <a href="{{ asset($deed_document) }}" download title="Download Deed">
                    <i class="fa fa-download text-success"></i>
                </a>
            </div>
            @endif

            <div class="info-section">
                <h5 style="margin-bottom: 15px; color: #333;">Customer Details</h5>
                <div class="customer-details-grid">
                    <!-- Column 1 -->
                    <div>
                        <div class="info-row">
                            <span class="info-label">Company Name:</span>
                            <span class="info-value">{{ $selectedCustomer->company_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">{{ $selectedCustomer->mobile ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Area:</span>
                            <span class="info-value">{{ $selectedCustomer->area?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Address:</span>
                            <span class="info-value">{{ $selectedCustomer->address ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div>
                        <div class="info-row">
                            <span class="info-label">Customer Type:</span>
                            <span class="info-value">{{ $selectedCustomer->customerInfo?->customer_type ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">User Reference:</span>
                            <span class="info-value">{{ $selectedCustomer->customerInfo?->user_reference ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Credit Limit:</span>
                            <span class="info-value">{{ number_format($selectedCustomer->credit_limit ?? 0) }}</span>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div>
                        <div class="info-row">
                            <span class="info-label">Commission Amount:</span>
                            <span class="info-value">{{ number_format($commission_amount ?? 0) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Legal Expense:</span>
                            <span class="info-value">{{ number_format($legal_expense ?? 0) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Advance Cheque:</span>
                            <span class="info-value">
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
        </div>
        @endif

        <!-- Ledger Table -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-1" style="width: 100%">
                <table class="table table-bordered table-striped" style="margin-bottom: 0; width: 100%">
                    <thead>
                        <tr class="table-header-bg">
                            <th class="text-center" style="width: 50px;">Sl</th>
                            <th class="text-center" style="width: 100px;">Date</th>
                            <th class="text-left" style="width: 200px;">Particulars</th>
                            <th class="text-center" style="width: 120px;">Reference</th>
                            <th class="text-center" style="width: 100px;">User</th>
                            <th class="text-center" style="width: 100px;">Verify</th>
                            <th class="text-right pr-1" style="width: 100px;">Debit</th>
                            <th class="text-right pr-1" style="width: 100px;">Credit</th>
                            <th class="text-right pr-1" style="width: 100px;">Balance</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(request('account_id'))
                            <tr style="background-color: #f0f0f0; font-weight: bold;">
                                <td class="text-left pl-3" colspan="8">Opening Balance</td>
                                <td class="text-right pr-1">{{ number_format($balance ?? 0) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="9" style="font-size: 16px" class="text-center text-danger">
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
                                $particulars = '';
                                $reference = '';
                                $debitAmount = $transaction->debit_amount;
                                $creditAmount = $transaction->credit_amount;
                                
                                // Determine particulars based on transaction type
                                $transactionType = class_basename($transaction->transactionable_type ?? '');
                                
                                switch($transactionType) {
                                    case 'Collection':
                                    case 'MoneyReceipt':
                                        $particulars = 'Collection (' . ($transaction->transactionable?->payment_mode ?? 'Cash') . ')';
                                        $reference = $transaction->transactionable?->receipt_no ?? '';
                                        break;
                                    case 'SalesInvoice':
                                        $condition = $transaction->transactionable?->condition ?? 'Regular';
                                        $particulars = 'Sales (' . $condition . ')';
                                        $reference = $transaction->transactionable?->bill_id ?? '';
                                        break;
                                    case 'ServiceBill':
                                        $particulars = 'Service Bill';
                                        $reference = $transaction->transactionable?->bill_no ?? '';
                                        break;
                                    case 'SalesReturn':
                                        $particulars = 'Sales Return';
                                        $reference = $transaction->transactionable?->return_no ?? '';
                                        break;
                                    case 'Backup':
                                    case 'Challan':
                                        $particulars = 'Backup/Challan';
                                        $reference = $transaction->transactionable?->challan_no ?? '';
                                        break;
                                    default:
                                        $particulars = $transactionType ?: $transaction->description ?? 'Transaction';
                                        $reference = $transaction->voucher_no ?? '';
                                }
                                
                                $runningBalance += ($debitAmount - $creditAmount);
                                $totalDebit += $debitAmount;
                                $totalCredit += $creditAmount;
                            @endphp

                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $transaction->transaction_date ?? $transaction->created_at }}</td>
                                <td class="text-left pl-2">{{ $particulars }}</td>
                                <td class="text-center">
                                    @if($reference)
                                        <span class="no-print">
                                            <a href="#" class="text-primary">{{ $reference }}</a>
                                        </span>
                                        <span class="print-only">{{ $reference }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">{{ $transaction->created_by_user?->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $transaction->verified_by_user?->name ?? '-' }}</td>
                                <td class="text-right pr-1">{{ $debitAmount > 0 ? number_format($debitAmount) : '-' }}</td>
                                <td class="text-right pr-1">{{ $creditAmount > 0 ? number_format($creditAmount) : '-' }}</td>
                                <td class="text-right pr-1">{{ number_format($runningBalance) }}</td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>

                    <tfoot>
                        @if(request('account_id'))
                        <tr style="background-color: #e9ecef; font-weight: bold;">
                            <th colspan="6" class="text-right pr-3">Total:</th>
                            <th class="text-right pr-1">{{ number_format($totalDebit) }}</th>
                            <th class="text-right pr-1">{{ number_format($totalCredit) }}</th>
                            <th class="text-right pr-1">{{ number_format($runningBalance) }}</th>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Signature Section (for print) -->
        <div class="row" style="width: 100%; margin: 30px 0 0 0 !important; display: none;" id="signature-section">
            <div class="col-sm-12">
                <div style="display: flex; justify-content: space-between; margin-top: 50px;">
                    <div style="text-align: center;">
                        <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px;">
                            Prepared By
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px;">
                            Checked By
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px;">
                            Approved By
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Collected Cheque Modal -->
<div class="modal fade" id="collectedChequeModal" tabindex="-1" aria-labelledby="collectedChequeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="collectedChequeModalLabel">Advance Cheque History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-end cheque-modal-print">
                    <button class="btn btn-sm btn-primary" onclick="printChequeHistory('collectedChequeModal')">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Sl. No.</th>
                            <th>Cheque Date</th>
                            <th>Cheque No.</th>
                            <th class="text-right">Cheque Amount</th>
                            <th>Cheque Type</th>
                            <th class="text-center">Attachment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($advance_cheques) && count($advance_cheques) > 0)
                        @foreach($advance_cheques as $index => $cheque)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $cheque['cheque_date'] }}</td>
                            <td>{{ $cheque['cheque_no'] }}</td>
                            <td class="text-right">{{ number_format($cheque['amount']) }}</td>
                            <td>{{ ucfirst($cheque['cheque_type']) }}</td>
                            <td class="text-center">
                                @if(isset($cheque['attachment']) && $cheque['attachment'])
                                <a href="{{ asset($cheque['attachment']) }}" target="_blank" title="View Attachment">
                                    <i class="fa fa-file-image text-primary"></i>
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">No cheques found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Refunded Cheque Modal -->
<div class="modal fade" id="refundedChequeModal" tabindex="-1" aria-labelledby="refundedChequeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="refundedChequeModalLabel">Refund Cheque History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Sl. No.</th>
                            <th>Cheque Date</th>
                            <th>Refund Date</th>
                            <th>Cheque No.</th>
                            <th class="text-right">Cheque Amount</th>
                            <th>Cheque Type</th>
                            <th class="text-center">Attachment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($refunded_cheques) && count($refunded_cheques) > 0)
                        @foreach($refunded_cheques as $index => $cheque)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ isset($cheque['cheque_date']) ? $cheque['cheque_date'] : 'N/A' }}</td>
                            <td>{{ $cheque['refund_date'] }}</td>
                            <td>{{ $cheque['cheque_no'] ?? 'N/A' }}</td>
                            <td class="text-right">{{ number_format($cheque['amount'] ?? 0) }}</td>
                            <td>{{ ucfirst($cheque['cheque_type'] ?? 'N/A') }}</td>
                            <td class="text-center">
                                @if(isset($cheque['attachment']) && $cheque['attachment'])
                                <a href="{{ asset($cheque['attachment']) }}" target="_blank" title="View Attachment">
                                    <i class="fa fa-file-image text-primary"></i>
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center text-muted">No refunded cheques found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show signature section when printing
window.onbeforeprint = function() {
    document.getElementById('signature-section').style.display = 'block';
};

window.onafterprint = function() {
    document.getElementById('signature-section').style.display = 'none';
};

// Print cheque history
function printChequeHistory(modalId) {
    const modalContent = document.querySelector('#' + modalId + ' .modal-body').cloneNode(true);
    
    // Remove print button from cloned content
    const printBtn = modalContent.querySelector('.cheque-modal-print');
    if (printBtn) {
        printBtn.remove();
    }
    
    const printWindow = window.open('', '', 'height=600,width=800');
    
    printWindow.document.write('<html><head><title>Cheque History</title>');
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">');
    printWindow.document.write('<style>');
    printWindow.document.write('body { padding: 20px; font-family: Arial, sans-serif; }');
    printWindow.document.write('table { width: 100%; margin-top: 20px; }');
    printWindow.document.write('th, td { padding: 8px; }');
    printWindow.document.write('.table-bordered { border: 1px solid #dee2e6; }');
    printWindow.document.write('.table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h3 style="text-align: center; margin-bottom: 20px;">' + 
        document.querySelector('#' + modalId + ' .modal-title').textContent + 
        '</h3>');
    printWindow.document.write(modalContent.innerHTML);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}

// Export to Excel
function exportLedger() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;
    
    // Add all current query parameters
    const params = new URLSearchParams(window.location.search);
    params.append('export_type', 'excel');
    
    for (const [key, value] of params) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endpush