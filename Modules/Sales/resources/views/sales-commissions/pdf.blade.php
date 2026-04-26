<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Commissions List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            width: 100%;
            padding: 20px;
        }

        .my-header {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 30px;
        }

        .my-header img {
            max-width: 100px;
            margin-right: 20px;
        }

        .my-header h1 {
            margin: 0;
            font-size: 25px;
            font-weight: bold;
            color: rgb(0, 0, 187);
        }

        .my-header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h4 {
            margin: 0;
            font-size: 18px;
            text-decoration: underline;
        }

        .filter-info {
            font-size: 10px;
            margin-bottom: 15px;
            color: #666;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .commission-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .commission-table,
        .commission-table th,
        .commission-table td {
            border: 1px solid #000;
            font-size: 11px;
        }

        .commission-table th,
        .commission-table td {
            padding: 8px;
            text-align: left;
        }

        .commission-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px;
        }

        .clearfix {
            clear: both;
        }

        .my-5 {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .signature-section {
            width: 100%;
            margin-top: 50px;
            border: none;
        }

        .signature-section td {
            border: none;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="social-dash-wrap">

            <!-- Header Section (Matching Delivery Challan) -->
            <header class="my-header">
                @include('partials._for_pdf_header_2nd')
            </header>

            <!-- Title Section -->
            <section class="title">
                <h4>Sales Commissions List</h4>
                <p>Generated on: {{ date('d-m-Y H:i:s') }}</p>
            </section>

            <!-- Filter Information Section -->
            @if(request('broker_id') || request('from') || request('to') || request('type'))
                <div class="filter-info">
                    <strong>Filters Applied:</strong>
                    @if(request('broker_id'))
                        Broker: {{ optional($brokers->find(request('broker_id')))->broker_name }} |
                    @endif
                    @if(request('from'))
                        From: {{ request('from') }} |
                    @endif
                    @if(request('to'))
                        To: {{ request('to') }} |
                    @endif
                    @if(request('type'))
                        Type: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                    @endif
                </div>
            @endif

            <!-- Commission Table -->
            <section class="delivery-details">
                <table class="commission-table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Reference</th>
                            <th>Customer</th>
                            <th>Broker</th>
                            <th>Request By</th>
                            <th>Date</th>
                            <th>Invoice Amount</th>
                            <th>Commission Amount</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @foreach($salesCommissions as $commission)
                            <tr>
                                <td>{{ ($salesCommissions->currentPage() - 1) * $salesCommissions->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    @if($commission->sales_order_id)
                                        {{ $commission->salesOrder->sales_order_id ?? '-' }}
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $commission->type)) }}
                                    @endif
                                </td>
                                <td>{{ @$commission->salesOrder->customer->company_name ?? '-' }}</td>
                                <td>{{ optional($commission->broker)->broker_name ?? '-' }}</td>
                                <td>{{ optional($commission->createdBy)->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($commission->commission_date)->format('d-m-Y') }}</td>
                                <td>{{ number_format($commission->commissionable_amount ?? 0, 2) }}</td>
                                <td>{{ number_format($commission->amount, 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $commission->type)) }}</td>
                                <td>
                                    @if($commission->status == 'pending')
                                        <span style="color: orange;">Pending</span>
                                    @elseif($commission->status == 'verify')
                                        <span style="color: green;">Verified</span>
                                    @elseif($commission->status == 'deny')
                                        <span style="color: red;">Denied</span>
                                    @else
                                        {{ $commission->status }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- Summary Section -->
            <table style="width: 100%; margin-top: 20px; border: none;">
                <tbody>
                    <tr>
                        <td style="border: none; text-align: right;">
                            <strong>Total Commission Amount:</strong>
                            {{ number_format($salesCommissions->sum('amount'), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Signature Section (Matching Delivery Challan) -->
            <table class="signature-section">
                <tr>
                    <td style="width:50%; text-align:center;">
                        ___________________________ <br>
                        Prepared By
                    </td>
                    <td style="width:50%; text-align:center;">
                        ___________________________ <br>
                        Authorized Signature
                    </td>
                </tr>
            </table>

            <!-- Footer -->
            <div class="footer">
                Total Records: {{ $salesCommissions->total() }} |
                Page {{ $salesCommissions->currentPage() }} of {{ $salesCommissions->lastPage() }}
            </div>

        </div>
    </div>
</body>

</html>