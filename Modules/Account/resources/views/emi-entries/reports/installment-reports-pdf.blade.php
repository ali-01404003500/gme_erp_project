<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EMI Installment Report</title>
    <style>
        .my-header {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .my-header img {
            max-width: 100px;
            margin-right: 20px;
        }
        .my-header h1 {
            margin: 0;
            font-size: 50px;
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
        .title h2 {
            margin: 0;
            font-size: 20px;
            text-decoration: underline;
        }
        table.table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        table.table th, table.table td {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
            border: 1px solid #ddd;
        }
        table.table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        footer {
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <header class="my-header">
        @include('partials._for_pdf_header_2nd')
    </header>

    <section class="title">
        <h2>EMI Installment Report</h2>
        <p style="font-size: 14px; margin-top: 10px;">
            Month: {{ $month }}
        </p>
    </section>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SL</th>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Phone No</th>
                <th>Balance</th>
                <th>Installment Amount</th>
                <th>Cheque No</th>
                <th>Pay Status</th>
                <th>Pay Date</th>
                <th>Pay Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report_data as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry['customer_name'] }}</td>
                    <td>{{ $entry['address'] }}</td>
                    <td>{{ $entry['phone'] }}</td>
                    <td>{{ number_format($entry['balance']) }}</td>
                    <td>{{ number_format($entry['installment_amount']) }}</td>
                    <td>{{ $entry['cheque_no'] }}</td>
                    <td>{{ $entry['pay_status'] }}</td>
                    <td>{{ $entry['pay_date'] ? \Carbon\Carbon::parse($entry['pay_date'])->format('d-m-Y') : 'N/A' }}</td>
                    <td>{{ number_format($entry['pay_amount']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        @include('partials._for_pdf_footer')
    </footer>
</body>
</html>