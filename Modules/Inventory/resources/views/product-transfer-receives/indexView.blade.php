<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Product Transfer Receive List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-info {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Product Transfer Receive List</h2>
        @if(isset($company_info))
            <div class="company-info">
                <p>{{ $company_info->company_name ?? '' }}</p>
                <p>{{ $company_info->address ?? '' }}</p>
            </div>
        @endif
        <p>Generated: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sl</th>
                <th>Invoice No</th>
                <th>Source Branch</th>
                <th>Destination Branch</th>
                <th>Quantity</th>
                <th>Receive Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productTransferReceives as $key => $productTransferReceive)
                <tr>
                    <td>{{ ($productTransferReceives->currentPage() - 1) * $productTransferReceives->perPage() + $loop->iteration }}</td>
                    <td>{{ $productTransferReceive->invoice_no }}</td>
                    <td>{{ optional($productTransferReceive->sourceBranch)->name }}</td>
                    <td>{{ optional($productTransferReceive->destinationBranch)->name }}</td>
                    <td>{{ $productTransferReceive->productTransferReceiveDetails->sum('received_quantity') }}</td>
                    <td>{{ $productTransferReceive->receive_date }}</td>
                    <td>{{ ucfirst($productTransferReceive->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
