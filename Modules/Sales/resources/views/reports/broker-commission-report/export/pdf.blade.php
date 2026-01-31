<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Broker Commission Report</title>
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
            background-color: #007bff;
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
            background-color: #007bff;
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
            background-color: #E5F2FF !important;
            font-weight: bold;
            font-size: 8px;
        }

        .bank-detail {
            margin-bottom: 4px;
            padding-bottom: 3px;
        }

        .bank-detail:not(:last-child) {
            border-bottom: 1px solid #ccc;
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
    <div class="report-title">BROKER COMMISSION REPORT</div>
    
    <div style="text-align: center; font-size: 8px; color: #666; margin: 5px 0;">
        Generated on: {{ now()->format('d-M-Y h:i A') }}
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">SL No</th>
                @if(in_array('broker', $selectedColumns))
                <th style="width: 18%;">Broker Name</th>
                @endif
                @if(in_array('customer', $selectedColumns))
                <th style="width: 22%;">Customer Name</th>
                @endif
                @if(in_array('bank', $selectedColumns))
                <th style="width: 30%;">Bank Info</th>
                @endif
                @if(in_array('commission', $selectedColumns))
                <th style="width: 12%;">Commission Amount</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $totalCommission = 0;
            @endphp

            @forelse($reportData as $index => $item)
                @php
                    $commission = $item['data'];
                    $totalCommission += $commission->amount;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    @if(in_array('broker', $selectedColumns))
                    <td>
                        <strong>{{ optional($commission->broker)->broker_name ?? 'N/A' }}</strong><br>
                        <span class="small-text">{{ optional($commission->broker)->broker_phone ?? '' }}</span>
                    </td>
                    @endif
                    
                    @if(in_array('customer', $selectedColumns))
                    <td>
                        @if($commission->salesOrder && $commission->salesOrder->customer)
                            <strong>{{ $commission->salesOrder->customer->company_name ?? 'N/A' }}</strong><br>
                            <span class="small-text">{{ $commission->salesOrder->customer->address ?? '' }}</span>
                        @else
                            N/A
                        @endif
                    </td>
                    @endif
                    
                    @if(in_array('bank', $selectedColumns))
                    <td>
                        @if($commission->broker && $commission->broker->brokerBank && $commission->broker->brokerBank->count() > 0)
                            @foreach($commission->broker->brokerBank as $bankDetail)
                                <div class="bank-detail">
                                    <strong>{{ $bankDetail->bank_name ?? 'N/A' }}</strong><br>
                                    <span class="small-text">A/C: {{ $bankDetail->account_nos ?? '' }}</span><br>
                                    <span class="small-text">Branch: {{ $bankDetail->branch_name ?? '' }}</span>
                                </div>
                            @endforeach
                        @else
                            No bank info
                        @endif
                    </td>
                    @endif
                    
                    @if(in_array('commission', $selectedColumns))
                    <td class="text-right">
                        <strong>{{ numberFormat($commission->amount) }}</strong>
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
                <td colspan="{{ count($selectedColumns) }}" class="text-right">
                    <strong>TOTAL COMMISSION AMOUNT:</strong>
                </td>
                @if(in_array('commission', $selectedColumns))
                <td class="text-right">
                    <strong>{{ numberFormat($totalCommission) }}</strong>
                </td>
                @endif
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Records: {{ $reportData->count() }}</strong></p>
        <p>This is a computer-generated broker commission report.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. | 
        Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>