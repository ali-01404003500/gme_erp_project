<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Petty Cash Bill - {{ $billsAndAllowance->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .invoice-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        .title {
            text-align: center;
            margin: 15px 0;
        }

        .title h4 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
        }

        .requisition-info {
            width: 100%;
            margin-bottom: 15px;
        }

        .requisition-info table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .requisition-info th,
        .requisition-info td {
            vertical-align: top;
            padding: 3px;
            text-align: left;
            font-size: 10px;
            border: none !important;
        }

        .requisition-info .left {
            width: 60%;
            float: left;
        }

        .requisition-info .right {
            width: 40%;
            float: right;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .invoice-details {
            margin-bottom: 15px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .invoice-details table,
        .invoice-details th,
        .invoice-details td {
            border: 1px solid #000;
        }

        .invoice-details th,
        .invoice-details td {
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        .invoice-details th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin: 15px 0 5px 0;
            padding: 3px 0;
            border-bottom: 1px solid #000;
        }

        .amount-breakdown {
            font-size: 9px;
            line-height: 1.4;
        }

        .amount-breakdown div {
            margin: 2px 0;
        }

        .final-amount {
            color: #006400;
            font-weight: bold;
        }

        .total-section {
            width: 100%;
            margin-top: 10px;
        }

        .total-section .left {
            width: 60%;
            float: left;
            font-size: 9px;
        }

        .total-section .right {
            width: 40%;
            float: right;
        }

        .total-section table {
            width: 100%;
            border: none;
        }

        .total-section td,
        .total-section th {
            border: none;
            padding: 3px;
            font-size: 10px;
        }

        .signature-section {
            margin-top: 60px;
            width: 100%;
        }

        .signature-container {
            width: 100%;
            display: table;
        }

        .signature-box {
            width: 17%;
            text-align: center;
            display: inline-block;
            vertical-align: bottom;
        }

        .signature-display {
             height: 30px;
            margin-bottom: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .signature-display .name {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .signature-timestamp {
            font-size: 7px;
            color: #666;
        }

        .signature-placeholder {
            color: #999;
            font-style: italic;
            font-size: 8px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 5px auto;
        }

        .signature-label {
            margin-top: 5px;
            font-size: 9px;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .remarks-section {
            margin: 15px 0;
            font-size: 10px;
        }

        .remarks-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 8px;
            margin: 5px 0;
        }

        .remarks-box strong {
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header with QR Code -->
        <header class="my-header">
            @include('partials._for_pdf_header_with_qr', ['qrCode' => true, 'qrCodeUrl' => route('hrm.bills.show', $billsAndAllowance->id)])
        </header>

        <!-- Title -->
        <section class="title">
            <h4>Petty Cash Bill</h4>
        </section>

        <!-- Bill Information -->
        <section class="requisition-info clearfix">
            <div class="left">
                <table>
                    <tr>
                        <th style="width: 35%;">Bill No</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 60%;">{{ str_pad($billsAndAllowance->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Employee Name</th>
                        <td>:</td>
                        <td>{{ $billsAndAllowance->employee->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Employee ID</th>
                        <td>:</td>
                        <td>{{ $billsAndAllowance->employee->employementDetail->card_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Requisition Type</th>
                        <td>:</td>
                        <td>
                            @if($billsAndAllowance->transportExpenses->count() > 0 && $billsAndAllowance->generalExpenses->count() > 0)
                                Transport & General Expense
                            @elseif($billsAndAllowance->transportExpenses->count() > 0)
                                Transport Expense
                            @else
                                General Expense
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="right">
                <table>
                    <tr>
                        <th style="width: 40%;">Request Date</th>
                        <td style="width: 5%;">:</td>
                        <td style="width: 55%;">{{ \Carbon\Carbon::parse($billsAndAllowance->date_of_bill_claim)->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:</td>
                        <td>
                            @if ($billsAndAllowance->status == 'pending')
                                <span class="status-badge status-pending">Pending</span>
                            @elseif($billsAndAllowance->status == 'team_leader_check')
                                <span class="status-badge status-pending">Team Leader Checked</span>
                            @elseif($billsAndAllowance->status == 'accounts_check')
                                <span class="status-badge status-pending">HR/Accounts Checked</span>
                            @elseif($billsAndAllowance->status == 'approved')
                                <span class="status-badge status-approved">Approved</span>
                            @elseif($billsAndAllowance->status == 'paid')
                                <span class="status-badge status-approved">Paid</span>
                            @elseif($billsAndAllowance->status == 'rejected')
                                <span class="status-badge status-rejected">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Prepared By</th>
                        <td>:</td>
                        <td>{{ $billsAndAllowance->createdBy->name ?? $billsAndAllowance->employee->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Print Date</th>
                        <td>:</td>
                        <td>{{ now()->format('d-M-Y') }}</td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Transport Expenses -->
        @if($billsAndAllowance->transportExpenses->count() > 0)
        <div class="section-title">TRANSPORT EXPENSES</div>
        <section class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center">SN</th>
                        <th style="width: 10%;">Date</th>
                        <th style="width: 15%;">From - To</th>
                        <th style="width: 12%;">Transport By</th>
                        <th style="width: 25%;">Description</th>
                        <th style="width: 8%;">Distance</th>
                        <th style="width: 13%;" class="text-right">Amount</th>
                        <th style="width: 13%;" class="text-right">Final Approved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($billsAndAllowance->transportExpenses as $key => $transport)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($transport->date_of_expense)->format('d-M-y') }}</td>
                            <td>{{ $transport->from_location }} - {{ $transport->to_location }}</td>
                            <td>{{ $transport->transportType->name ?? 'N/A' }}</td>
                            <td>{{ $transport->expense_description }}</td>
                            <td class="text-center">{{ $transport->distance }} km</td>
                            <td class="text-right">
                                <div class="amount-breakdown">
                                    <div><strong>Req:</strong> {{ number_format($transport->amount) }}</div>
                                    @if($transport->team_leader_approved_amount)
                                        <div><strong>TL:</strong> {{ number_format($transport->team_leader_approved_amount) }}</div>
                                    @endif
                                    @if($transport->accounts_approved_amount)
                                        <div><strong>Acc:</strong> {{ number_format($transport->accounts_approved_amount) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">
                                @if($transport->final_approved_amount)
                                    <strong class="final-amount">{{ number_format($transport->final_approved_amount) }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-right"><strong>Transport Sub Total:</strong></td>
                        <td class="text-right">
                            <strong>{{ number_format($billsAndAllowance->transportExpenses->sum('final_approved_amount')) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        @endif

        <!-- General Expenses -->
        @if($billsAndAllowance->generalExpenses->count() > 0)
        <div class="section-title">GENERAL EXPENSES</div>
        <section class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center">SN</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 18%;">Expense Type</th>
                        <th style="width: 32%;">Description</th>
                        <th style="width: 17%;" class="text-right">Amount</th>
                        <th style="width: 17%;" class="text-right">Final Approved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($billsAndAllowance->generalExpenses as $key => $general)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($general->expense_date)->format('d-M-y') }}</td>
                            <td>{{ $general->expenseType->name ?? 'General Expense' }}</td>
                            <td>{{ $general->expense_description }}</td>
                            <td class="text-right">
                                <div class="amount-breakdown">
                                    <div><strong>Req:</strong> {{ number_format($general->amount) }}</div>
                                    @if($general->team_leader_approved_amount)
                                        <div><strong>TL:</strong> {{ number_format($general->team_leader_approved_amount) }}</div>
                                    @endif
                                    @if($general->accounts_approved_amount)
                                        <div><strong>Acc:</strong> {{ number_format($general->accounts_approved_amount) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">
                                @if($general->final_approved_amount)
                                    <strong class="final-amount">{{ number_format($general->final_approved_amount) }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-right"><strong>General Sub Total:</strong></td>
                        <td class="text-right">
                            <strong>{{ number_format($billsAndAllowance->generalExpenses->sum('final_approved_amount')) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        @endif

        <!-- Total Section -->
        <section class="total-section clearfix">
            <div class="left">
                <p><strong>IN WORD:</strong> {{ convert_number($billsAndAllowance->transportExpenses->sum('final_approved_amount') + $billsAndAllowance->generalExpenses->sum('final_approved_amount')) }} Taka Only</p>
            </div>
            <div class="right">
                <table style="float: right; width: 70%;">
                    <tr>
                        <th style="text-align: left;">Grand Total</th>
                        <td style="text-align: center;">:</td>
                        <td class="text-right">
                            <strong>{{ number_format($billsAndAllowance->transportExpenses->sum('final_approved_amount') + $billsAndAllowance->generalExpenses->sum('final_approved_amount')) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Remarks Section -->
        @if($billsAndAllowance->checked_by_team_leader_comments || $billsAndAllowance->checked_by_accounts_comments || $billsAndAllowance->final_approved_comments)
        <section class="remarks-section clearfix">
            <div class="section-title">REMARKS</div>
            @if($billsAndAllowance->checked_by_team_leader_comments)
                <div class="remarks-box">
                    <strong>Team Leader:</strong> {{ $billsAndAllowance->checked_by_team_leader_comments }}
                </div>
            @endif
            @if($billsAndAllowance->checked_by_accounts_comments)
                <div class="remarks-box">
                    <strong>HR/Accounts:</strong> {{ $billsAndAllowance->checked_by_accounts_comments }}
                </div>
            @endif
            @if($billsAndAllowance->final_approved_comments)
                <div class="remarks-box">
                    <strong>Final Approver:</strong> {{ $billsAndAllowance->final_approved_comments }}
                </div>
            @endif
        </section>
        @endif

        <!-- Signature Section -->
        <section class="signature-section clearfix">
            <div class="signature-container">
                <!-- Prepared By -->
                <div class="signature-box">
                    <div class="signature-display">
                        @if($billsAndAllowance->createdBy)
                            <div class="name">{{ $billsAndAllowance->createdBy->name }}</div>
                            <div class="signature-timestamp">{{ \Carbon\Carbon::parse($billsAndAllowance->created_at)->format('d-M-y') }}</div>
                        @else
                            <div class="signature-placeholder">Not signed</div>
                        @endif
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-label">Prepared By</div>
                </div>

                <!-- Checked By Team Leader -->
                <div class="signature-box">
                    <div class="signature-display">
                        @if($billsAndAllowance->checkedByTeamLeader)
                            <div class="name">{{ $billsAndAllowance->checkedByTeamLeader->name }}</div>
                            <div class="signature-timestamp">{{ \Carbon\Carbon::parse($billsAndAllowance->checked_by_team_leader_date)->format('d-M-y') }}</div>
                        @else
                            <div class="signature-placeholder">Pending</div>
                        @endif
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-label">Team Leader</div>
                </div>

                <!-- Checked By Accounts -->
                <div class="signature-box">
                    <div class="signature-display">
                        @if($billsAndAllowance->checkedByAccounts)
                            <div class="name">{{ $billsAndAllowance->checkedByAccounts->name }}</div>
                            <div class="signature-timestamp">{{ \Carbon\Carbon::parse($billsAndAllowance->checked_by_accounts_date)->format('d-M-y') }}</div>
                        @else
                            <div class="signature-placeholder">Pending</div>
                        @endif
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-label">HR/Accounts</div>
                </div>

                <!-- Approved By -->
                <div class="signature-box">
                    <div class="signature-display">
                        @if($billsAndAllowance->finalApprovedBy)
                            <div class="name">{{ $billsAndAllowance->finalApprovedBy->name }}</div>
                            <div class="signature-timestamp">{{ \Carbon\Carbon::parse($billsAndAllowance->final_approved_date)->format('d-M-y') }}</div>
                        @else
                            <div class="signature-placeholder">Pending</div>
                        @endif
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-label">Approved By</div>
                </div>

                <!-- Payment By -->
                <div class="signature-box">
                    <div class="signature-display">
                        @if($billsAndAllowance->paymentBy)
                            <div class="name">{{ $billsAndAllowance->paymentBy->name }}</div>
                            <div class="signature-timestamp">{{ \Carbon\Carbon::parse($billsAndAllowance->payment_date)->format('d-M-y') }}</div>
                        @else
                            <div class="signature-placeholder">Pending</div>
                        @endif
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-label">Payment By</div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>