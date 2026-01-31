<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Customer(Machine Code) Report</title>
</head>

<body>
    <table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <!-- Company Header -->
            <tr>
                <th colspan="5"
                    style="text-align:center;font-size:16px;font-weight:bold;background:#4472C4;color:#fff;">
                    {{ $company_info->company_name ?? 'Company Name' }}
                </th>
            </tr>
            <tr>
                <th colspan="5" style="text-align:center;font-size:12px;background:#D9E1F2;">
                    {{ $company_info->company_address ?? '' }}
                </th>
            </tr>
            <tr>
                <th colspan="5" style="text-align:center;font-size:12px;background:#D9E1F2;">
                    Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
                </th>
            </tr>

            <!-- Report Title -->
            <tr>
                <th colspan="5" style="text-align:center;font-size:14px;font-weight:bold;background:#E7E6E6;">
                    CUSTOMER LIST (MACHINE CODE) REPORT
                </th>
            </tr>
            <tr>
                <th colspan="5" style="text-align:center;font-size:10px;background:#F2F2F2;">
                    Generated on: {{ now()->format('d-M-Y h:i A') }}
                </th>
            </tr>

            <!-- Total Due Balance -->


            <!-- Spacer Row -->
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>

            <!-- Column Headers -->
            <tr style="background:#4472C4;color:#fff;font-weight:bold;">
                <th>SL</th>
                <th>Customer Name</th>
                <th>Phone No</th>
                <th>Address</th>
                <th>Due Balance</th>
            </tr>
        </thead>

        <tbody>
            @php
                $totalDue = 0;
            @endphp

            @forelse($reportData as $index => $customer)
                @php
                    $totalDue += $customer['due_balance'];
                @endphp
                <tr>
                    <td style="text-align:center; vertical-align: middle;">{{ $index + 1 }}</td>

                    <td style="vertical-align: middle;">
                        <strong>{{ $customer['customer_name'] }}</strong>
                    </td>

                    <td style="vertical-align: middle;">
                        {{ $customer['phone'] ?? 'N/A' }}
                    </td>

                    <td style="vertical-align: middle;">
                        {{ $customer['address'] ?? 'N/A' }}
                    </td>

                    <td style="text-align:right; vertical-align: middle;">
                        @if ($customer['receivable_balance'] > 0)
                            <div style="color:#dc3545;">
                                Receivable: ৳{{ number_format($customer['receivable_balance']) }} <br>
                            </div>
                        @endif

                        @if ($customer['advance_balance'] > 0)
                            <div style="color:#28a745;">
                                Advance: ৳{{ number_format($customer['advance_balance']) }} <br>
                            </div>
                        @endif

                        <div style="font-weight:bold;">
                            <strong style="color:{{ $customer['due_balance'] >= 0 ? '#dc3545' : '#28a745' }};">
                                Net Due: ৳{{ number_format($customer['due_balance']) }}
                            </strong>
                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:20px;font-style:italic;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse

            <!-- Spacer Row -->
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>

            <!-- Total Row -->
            @if ($reportData->count() > 0)
                <tr style="background:#D9E1F2;font-weight:bold;">
                    <td colspan="4" style="text-align:right;font-size:12px;">TOTAL DUE BALANCE:</td>
                    <td style="text-align:right;color:#dc3545;font-size:12px;">
                        <strong>৳{{ number_format($totalDue) }}</strong>
                    </td>
                </tr>
            @endif

            <!-- Spacer Row -->
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>

            <!-- Summary Section -->
            <tr>
                <th colspan="5"
                    style="text-align:center;font-size:14px;font-weight:bold;background:#D9E1F2;color:#dc3545;">
                    TOTAL DUE BALANCE: ৳{{ number_format($totalDueBalance) }}
                </th>
            </tr>

            <!-- Spacer Row -->
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>

            <!-- Footer -->
            <tr style="background:#F2F2F2;">
                <td colspan="5" style="text-align:center;font-size:10px;font-style:italic;">
                    Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} |
                    Copyright © {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }} |
                    All rights reserved
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
