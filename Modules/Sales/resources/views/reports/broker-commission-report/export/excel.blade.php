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
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 18px; font-weight: bold; background-color: #007bff; color: white;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 12px; background-color: #E5F2FF;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 12px; background-color: #E5F2FF;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #F2F2F2;">
                BROKER COMMISSION REPORT
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
        <tr style="background-color: #007bff; color: white; font-weight: bold;">
            <th>SL No</th>
            @if(in_array('broker', $selectedColumns))
            <th>Broker Name</th>
            @endif
            @if(in_array('customer', $selectedColumns))
            <th>Customer Name</th>
            @endif
            @if(in_array('bank', $selectedColumns))
            <th>Bank Info</th>
            @endif
            @if(in_array('commission', $selectedColumns))
            <th>Commission Amount</th>
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
                
                // Broker info
                $brokerInfo = optional($commission->broker)->broker_name ?? 'N/A';
                if ($commission->broker && $commission->broker->broker_phone) {
                    $brokerInfo .= ' (' . $commission->broker->broker_phone . ')';
                }
                
                // Customer info
                $customerInfo = 'N/A';
                if ($commission->salesOrder && $commission->salesOrder->customer) {
                    $customerInfo = $commission->salesOrder->customer->company_name;
                    if ($commission->salesOrder->customer->address) {
                        $customerInfo .= ' - ' . $commission->salesOrder->customer->address;
                    }
                }
                
                // Bank info - Handle multiple banks
                $bankInfo = 'N/A';
                if ($commission->broker && $commission->broker->brokerBank && $commission->broker->brokerBank->count() > 0) {
                    $bankDetails = [];
                    foreach ($commission->broker->brokerBank as $bankDetail) {
                        $bankDetails[] = ($bankDetail->bank_name ?? 'N/A') . 
                                       ' | A/C: ' . ($bankDetail->account_nos ?? '') . 
                                       ' | Branch: ' . ($bankDetail->branch_name ?? '');
                    }
                    $bankInfo = implode(' || ', $bankDetails);
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                @if(in_array('broker', $selectedColumns))
                <td>{{ $brokerInfo }}</td>
                @endif
                @if(in_array('customer', $selectedColumns))
                <td>{{ $customerInfo }}</td>
                @endif
                @if(in_array('bank', $selectedColumns))
                <td>{{ $bankInfo }}</td>
                @endif
                @if(in_array('commission', $selectedColumns))
                <td style="text-align: right;">{{ numberFormat($commission->amount) }}</td>
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
        <tr style="background-color: #E5F2FF; font-weight: bold;">
            <td colspan="{{ count($selectedColumns) }}" style="text-align: right;">
                TOTAL COMMISSION AMOUNT:
            </td>
            @if(in_array('commission', $selectedColumns))
            <td style="text-align: right;">{{ numberFormat($totalCommission) }}</td>
            @else
            <td>{{ numberFormat($totalCommission) }}</td>
            @endif
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
            <td colspan="2" style="font-weight: bold;">Total Records:</td>
            <td colspan="{{ count($selectedColumns) - 1 }}">{{ $reportData->count() }}</td>
        </tr>
        <tr style="background-color: #F8F9FA;">
            <td colspan="2" style="font-weight: bold;">Total Commission:</td>
            <td colspan="{{ count($selectedColumns) - 1 }}">{{ numberFormat($totalCommission) }}</td>
        </tr>
        <tr style="background-color: #F8F9FA;">
            <td colspan="2" style="font-weight: bold;">Report Type:</td>
            <td colspan="{{ count($selectedColumns) - 1 }}">Broker Commission Report</td>
        </tr>
        <tr style="background-color: #F8F9FA;">
            <td colspan="2" style="font-weight: bold;">Generated By:</td>
            <td colspan="{{ count($selectedColumns) - 1 }}">{{ auth()->user()->name ?? 'System' }}</td>
        </tr>
        <tr style="background-color: #F8F9FA;">
            <td colspan="2" style="font-weight: bold;">Generated Date:</td>
            <td colspan="{{ count($selectedColumns) - 1 }}">{{ now()->format('d-M-Y h:i A') }}</td>
        </tr>
        
        <tr>
            <td colspan="{{ count($selectedColumns) + 1 }}">&nbsp;</td>
        </tr>
        
        <tr style="background-color: #F2F2F2;">
            <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; font-size: 10px; font-style: italic;">
                This is a computer-generated broker commission report | 
                Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>