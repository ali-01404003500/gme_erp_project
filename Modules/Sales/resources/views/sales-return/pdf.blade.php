<!DOCTYPE html>
<html>

<head>
    <title>Sales Return List</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            text-align: center;
            width: 100%;
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
    </style>
</head>

<body>
    <!-- Header Section (Matching Delivery Challan) -->
    <header class="my-header">
        @include('partials._for_pdf_header_2nd')
    </header>

    <!-- Title Section -->
    <section class="title">
        <h4>Sales Return List</h4>
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
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Prepared By</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesReturns as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->invoice_no }}</td>
                    <td>{{ $item->return_date }}</td>
                    <td>{{ optional($item->customer)->company_name }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ optional($item->createdBy)->name }}</td>
                    <td>{{ $item->reference_invoice }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Total: {{ $salesReturns->count() }} records</div>
</body>

</html>