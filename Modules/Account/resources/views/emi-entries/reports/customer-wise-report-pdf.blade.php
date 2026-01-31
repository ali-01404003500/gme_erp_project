<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EMI Customer-Wise Report</title>
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
    </style>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <header class="my-header">
                        @include('partials._for_pdf_header_2nd')
                    </header>

                    <section class="title">
                        <h2>EMI Customer Wise Report</h2>
                        <p style="font-size: 14px; margin-top: 10px;">
                            Month: {{ $month }}
                        </p>
                    </section>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>EMI No</th>
                                <th>Customer Info</th>
                                <th>Installment Date</th>
                                <th>Installment Amount</th>
                                <th>Payment Date</th>
                                <th>Payment Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($report_data as $entry)
                                <tr>
                                    <td>{{ $entry['sl'] }}</td>
                                    <td>{{ $entry['emi_no'] }}</td>
                                    <td style="text-align: left;">
                                        <strong>Customer:</strong> {{ $entry['customer_name'] }}<br>
                                        <strong>Phone:</strong> {{ $entry['phone'] }}<br>
                                        <strong>Address:</strong> {{ $entry['customer_address'] }}
                                    </td>
                                    <td>{{ $entry['emi_date'] }}</td>
                                    <td>{{ number_format($entry['installment_amount']) }}</td>
                                    <td>{{ $entry['pay_date'] ? $entry['pay_date'] : 'N/A' }}</td>
                                    <td>{{ number_format($entry['pay_amount']) }}</td>
                                    <td>{{ $entry['pay_status'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center;">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f2f2f2; font-weight: bold;">
                                <td colspan="4" style="text-align: right;">Total:</td>
                                <td>{{ number_format($total_installment_amount) }}</td>
                                <td></td>
                                <td>{{ number_format($total_payment_amount) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <footer style="margin-top: 100px">
                        @include('partials._for_pdf_footer')
                    </footer>
                </div>
            </div>
        </div>
    </div>
</body>
</html>