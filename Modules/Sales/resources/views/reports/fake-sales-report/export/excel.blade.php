<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table border="1">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 18px; font-weight: bold; background-color: #DC3545; color: white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 12px; background-color: #FFE5E5;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 12px; background-color: #FFE5E5;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #F2F2F2;">
                FAKE SALES REPORT
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 11px; background-color: #F8F9FA;">
                Generated on: {{ now()->format('d-M-Y h:i A') }}
            </th>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>

        <!-- Table Headers -->
        <tr style="background-color: #DC3545; color: white; font-weight: bold;">
            <th>SL No</th>
            @if(in_array('invoice-id', $selectedColumns))
            <th>Invoice ID</th>
            @endif
            @if(in_array('invoice-datetime', $selectedColumns))
            <th>Invoice Date &amp; Time</th>
            @endif
            @if(in_array('branch', $selectedColumns))
            <th>Branch/Center Name</th>
            @endif
            @if(in_array('customer', $selectedColumns))
            <th>Customer Name</th>
            @endif
            @if(in_array('status', $selectedColumns))
            <th>Invoice Status</th>
            @endif
            @if(in_array('remarks', $selectedColumns))
            <th>Remarks</th>
            @endif
            @if(in_array('username', $selectedColumns))
            <th>Username</th>
            @endif
            @if(in_array('reference', $selectedColumns))
            <th>Reference Invoice</th>
            @endif
            @if(in_array('creation', $selectedColumns))
            <th>Creation Date</th>
            @endif
            @if(in_array('type', $selectedColumns))
            <th>Invoice Type</th>
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
                
                // Get status text
                $statusText = ucfirst($data->status ?? 'N/A');
                
                // Get reference invoice info
                if($data->salesOrder) {
                    $referenceText = $data->salesOrder->sales_order_id . ' (' . $data->salesOrder->invoice_date . ')';
                } else {
                    $referenceText = 'N/A';
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                @if(in_array('invoice-id', $selectedColumns))
                <td>{{ $data->invoice_number }}</td>
                @endif
                @if(in_array('invoice-datetime', $selectedColumns))
                <td>{{ \Carbon\Carbon::parse($data->invoice_date)->format('d-M-Y') }} {{ $data->created_at->format('h:i A') }}</td>
                @endif
                @if(in_array('branch', $selectedColumns))
                <td>{{ optional($data->createdBy)->branch->name ?? 'N/A' }}</td>
                @endif
                @if(in_array('customer', $selectedColumns))
                <td>{{ optional($data->customer)->company_name ?? 'N/A' }}</td>
                @endif
                @if(in_array('status', $selectedColumns))
                <td>@if ($data->salesOrder->status == 'pending')
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
                                                @endif</td>
                @endif
                @if(in_array('remarks', $selectedColumns))
                <td>{{ $data->remarks ?? '' }}</td>
                @endif
                @if(in_array('username', $selectedColumns))
                <td>{{ optional($data->createdBy)->name ?? 'N/A' }}</td>
                @endif
                @if(in_array('reference', $selectedColumns))
                <td>{{ $referenceText }}</td>
                @endif
                @if(in_array('creation', $selectedColumns))
                <td>{{ $data->created_at->format('Y-m-d') }}</td>
                @endif
                @if(in_array('type', $selectedColumns))
                <td>Sales</td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; padding: 20px; font-style: italic;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Empty Row -->
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>

        <!-- Summary -->
        <tr style="background-color: #FFE5E5; font-weight: bold;">
            <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center;">
                TOTAL FAKE SALES RECORDS: {{ $totalRecords }}
            </td>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}" style="background-color: #F2F2F2; font-weight: bold; text-align: center;">
                SUMMARY INFORMATION
            </td>
        </tr>

        <!-- Additional Summary -->
        <tr style="background-color: #F8F9FA;">
            <td colspan="3" style="font-weight: bold;">Total Records:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ $reportData->count() }}</td>
        </tr>
        <tr style="background-color: #F8F9FA;">
            <td colspan="3" style="font-weight: bold;">Report Type:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">Fake Sales Report (Test/Audit Data)</td>
        </tr>
        <tr style="background-color: #F8F9FA;">
            <td colspan="3" style="font-weight: bold;">Generated By:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ auth()->user()->name ?? 'System' }}</td>
        </tr>
        <tr style="background-color: #F8F9FA;">
            <td colspan="3" style="font-weight: bold;">Generated Date:</td>
            <td colspan="{{ count($selectedColumns) - 2 }}">{{ now()->format('d-M-Y h:i A') }}</td>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>
        
        <tr style="background-color: #F2F2F2;">
            <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 10px; font-style: italic;">
                This is a computer-generated fake sales report for testing/audit purposes | 
                Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>