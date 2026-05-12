 <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            position: relative;
        }

        @page {
            margin-top: 5px;
            margin-bottom: 5px;
            margin-left: 40px;
            margin-right: 40px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><text x="50%" y="50%" transform="rotate(-45, 50%, 50%)" font-size="120" font-family="Arial, sans-serif" fill="rgba(0,0,0,0.08)" text-anchor="middle" dominant-baseline="middle" font-weight="bold">DOCUMENT ENTRIES REPORT</text></svg>');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 80% auto;
        }

        @media print {
            .watermark {
                display: block !important;
            }

            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        header {
            position: fixed;
            top: -50px;
            left: -40px;
            right: -40px;
            height: 50px;
            background-color: #fff;
            text-align: center;
            line-height: 1.4;
            z-index: 1000;
        }

        footer {
            position: fixed;
            bottom: -80px;
            left: -40px;
            right: -40px;
            height: 80px;
            background-color: #fff;
            text-align: center;
            border-top: 1px solid #ccc;
            z-index: 1000;
        }

        .content {
            margin-top: 10px;
            margin-bottom: 20px;
            line-height: 1.5;
            position: relative;
            z-index: 1;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.08);
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
            opacity: 1;
            width: 100%;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Header Styles */
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
            text-transform: uppercase;
            text-align: center;
        }

        /* Table Styles - Matching First Format */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid black;
        }

        .custom-table th {
            background-color: #2a4054;
            font-weight: bold;
            text-align: left;
            border: 1px solid black;
            color: #ffffff !important;
            padding: 10px 5px;
        }

        .custom-table td {
            border: 1px solid black;
            padding: 10px 5px;
            word-wrap: break-word;
            vertical-align: top;
        }

        .custom-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        body {
            margin-top: 5px;
        }
    </style>
</head>

<body>

 

    <div class="content">
        <div class="catalog-container">
            <!-- Title Section -->
            <div class="title">
                <h2>Document Reports</h2>
            </div>

            <!-- Table Section -->
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="5%">Sl</th>
                        <th width="20%">Document Type</th>
                        <th width="25%">Document Head</th>
                        <th width="35%">Note</th>
                        <th width="15%">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documentEntries as $value)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $value->documentType->name ?? 'N/A' }}</td>
                            <td>{{ $value->documentHead->name ?? 'N/A' }}</td>
                            <td>{{ $value->remarks ?? 'N/A' }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($value->date)->format('d F, Y') ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No document entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Summary Section -->
            @if($documentEntries->count() > 0)
                <div style="text-align: right" class="mt-20">
                    <p>Total Records: <strong>{{ $documentEntries->count() }}</strong></p>
                    <p>Generated: <strong>{{ \Carbon\Carbon::now()->format('d F, Y') }}</strong></p>
                </div>
            @endif

            <!-- Signature Section -->
            <div class="signature mt-20">
                <p>_________________________</p>
                <p>Authorized Signature</p>
                <p>Date: {{ \Carbon\Carbon::now()->format('d F, Y') }}</p>
            </div>
        </div>
    </div>
</body>

</html>