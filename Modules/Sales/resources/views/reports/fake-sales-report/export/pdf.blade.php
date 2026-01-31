<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fake Sales Report</title>
    <style>
        @page {
            margin: 10mm 5mm;
            size: landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }
        
        .company-details {
            font-size: 9px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
            background-color: #DC3545;
            color: white;
            padding: 8px;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 7px;
        }
        
        th {
            background-color: #DC3545;
            color: white;
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
            font-size: 7px;
        }
        
        td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 7px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6px;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .badge-primary { background-color: #007bff; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .badge-dark { background-color: #343a40; color: white; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #333;
            font-size: 7px;
            text-align: center;
            color: #666;
        }
        
        .small-text {
            font-size: 6px;
            color: #666;
        }

        .summary-row {
            background-color: #FFE5E5 !important;
            font-weight: bold;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $company_info->company_name ?? 'Company Name' }}</div>
        <div class="company-details">{{ $company_info->company_address ?? '' }}</div>
        <div class="company-details">
            Phone: {{ $company_info->company_phone ?? '' }} | 
            Email: {{ $company_info->company_email ?? '' }}
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">FAKE SALES REPORT</div>
    
    <div style="text-align: center; font-size: 8px; color: #666; margin: 5px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">SL No</th>
                @if(in_array('invoice-id', $selectedColumns))
                <th style="width: 12%;">Invoice ID</th>
                @endif
                @if(in_array('invoice-datetime', $selectedColumns))
                <th style="width: 10%;">Invoice Date & Time</th>
                @endif
                @if(in_array('branch', $selectedColumns))
                <th style="width: 10%;">Branch/Center Name</th>
                @endif
                @if(in_array('customer', $selectedColumns))
                <th style="width: 12%;">Customer Name</th>
                @endif
                @if(in_array('status', $selectedColumns))
                <th style="width: 8%;">Invoice Status</th>
                @endif
                @if(in_array('remarks', $selectedColumns))
                <th style="width: 15%;">Remarks</th>
                @endif
                @if(in_array('username', $selectedColumns))
                <th style="width: 10%;">Username</th>
                @endif
                @if(in_array('reference', $selectedColumns))
                <th style="width: 10%;">Reference Invoice</th>
                @endif
                @if(in_array('creation', $selectedColumns))
                <th style="width: 8%;">Creation Date</th>
                @endif
                @if(in_array('type', $selectedColumns))
                <th style="width: 8%;">Invoice Type</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $totalRecords = 0;
            @endphp

            @forelse($reportData as $index => $item)
                @php
                    $data = $item['data'];
                    $totalRecords++;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @if(in_array('invoice-id', $selectedColumns))
                    <td>{{ $data->invoice_number }}</td>
                    @endif
                    @if(in_array('invoice-datetime', $selectedColumns))
                    <td>
                        {{ \Carbon\Carbon::parse($data->invoice_date)->format('d-M-Y') }}<br>
                        <span class="small-text">{{ $data->created_at->format('h:i A') }}</span>
                    </td>
                    @endif
                    @if(in_array('branch', $selectedColumns))
                    <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                    @endif
                    @if(in_array('customer', $selectedColumns))
                    <td>{{ optional($data->customer)->company_name ?? 'N/A' }}</td>
                    @endif
                    @if(in_array('status', $selectedColumns))
                    <td>
                       @if ($data->salesOrder->status == 'pending')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $data->salesOrder->status }}</span>
                                                @elseif($data->salesOrder->status == 'approved')
                                                    <span
                                                        class="badge badge-round badge-success text-capitalize">Undeliver</span>
                                                @elseif($data->salesOrder->status == 'delivered')
                                                    <span
                                                        class="badge badge-round badge-info text-capitalize">{{ $data->salesOrder->status }}</span>
                                                @elseif($data->salesOrder->status == 'partial')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $data->salesOrder->status }}</span>
                                                @endif
                    </td>
                    @endif
                    @if(in_array('remarks', $selectedColumns))
                    <td>{{ $data->remarks ?? '' }}</td>
                    @endif
                    @if(in_array('username', $selectedColumns))
                    <td>{{ optional($data->createdBy)->name ?? 'N/A' }}</td>
                    @endif
                    @if(in_array('reference', $selectedColumns))
                    <td>
                        @if($data->salesOrder)
                            {{ $data->salesOrder->sales_order_id }}<br>
                            <span class="small-text">({{ $data->salesOrder->invoice_date }})</span>
                        @else
                            N/A
                        @endif
                    </td>
                    @endif
                    @if(in_array('creation', $selectedColumns))
                    <td>{{ $data->created_at->format('Y-m-d') }}</td>
                    @endif
                    @if(in_array('type', $selectedColumns))
                    <td>
                        <span class="badge badge-primary">Sales</span>
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($selectedColumns) + 1 }}" class="text-center" style="padding: 20px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse
            
            @if($reportData->count() > 0)
            <tr class="summary-row">
                <td colspan="{{ count($selectedColumns) + 1 }}" class="text-center">
                    <strong>TOTAL RECORDS: {{ $totalRecords }}</strong>
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Fake Sales Invoices: {{ $totalRecords }}</strong></p>
        <p>This is a computer-generated fake sales report for testing/audit purposes.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. | 
        Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>