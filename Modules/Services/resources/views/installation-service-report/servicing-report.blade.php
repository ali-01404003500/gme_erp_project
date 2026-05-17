<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate of Servicing</title>

    <style>
        @page {
            margin: 10mm;
            size: A4;
        }

        html, body {
            margin-left: 10mm;
            margin-right: 10mm;
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5px;
            line-height: 1.2;
            color: #333;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
        }

        h4 {
            margin: 6px 0 4px 0;
            font-size: 11px;
        }

        /* HEADER (IMPORTANT: no margin push) */
        .header {
            margin: 0;
            padding: 0;
        }

        /* TITLE */
        .report-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 6px 0;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 3px;
            font-size: 9px;
            vertical-align: top;
        }

        table th {
            background: #f2f2f2;
        }

        /* CUSTOMER */
        .customer-info {
            margin: 5px 0;
            padding: 5px;
            background: #f5f5f5;
        }

        /* CERTIFICATION */
        .certification-text {
            text-align: center;
            margin: 5px 0;
            padding: 5px;
            font-size: 9px;
            font-style: italic;
        }

        /* ENGINEER */
        .engineer-info {
            margin: 5px 0;
        }

        /* BASIC INFO */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }

        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 2px;
        }

        /* SIGNATURE */
        .signature-section {
            margin-top: 8px;
        }

        .signature-box {
            border: 1px solid #333;
            height: 55px;
            margin-top: 3px;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 8px;
            font-size: 8px;
            border-top: 1px solid #333;
            padding-top: 4px;
        }

        /* IMPORTANT FIX: prevent unwanted spacing from includes */
        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body>

@php
    $reportNo = 'GME-CER-' . $token->serviceMyTask->updated_at->format('Ym') . '-' . str_pad($token->id, 4, '0', STR_PAD_LEFT);
    $servicingDate = @$token->serviceMyTask->updated_at;

    $engineerName = 'N/A';
    $engineerDesignation = 'N/A';

    if ($token->engineerAssign && $token->engineerAssign->engineers->count() > 0) {
        $engineer = $token->engineerAssign->engineers->first();
        $engineerName = $engineer->full_name;
        $engineerDesignation = $engineer->employementDetail->designation->name ?? 'N/A';
    }
@endphp

<!-- HEADER -->
<div class="header">
    @include('partials._for_pdf_header_2nd')
</div>

<!-- TITLE -->
<div class="report-title">
    CERTIFICATE OF SERVICING OF MEDICAL EQUIPMENT
</div>

<!-- REPORT INFO -->
<table style="margin-top:20px;">
    <tr>
        <td width="50%">
            <strong>Report No:</strong> {{ $reportNo ?? 'N/A' }}
        </td>
        <td width="50%" style="text-align:right;">
            <strong>Date:</strong> {{ $servicingDate->format('d-m-Y') }}
        </td>
    </tr>
</table>

<!-- CUSTOMER -->
<table style="margin-top:20px;">
    <tr>
        <td width="30%">
            <strong>Organization Name</strong> 
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $token->customer->company_name ?? 'N/A' }}
        </td>
    </tr>
    <tr>
        <td width="30%">
            <strong>Address</strong>
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $token->customer->address ?? 'N/A' }}
        </td>
    </tr>
</table>

<!-- CERTIFICATION -->
<div class="certification-text" style="font-size:16px;">
    We the above name organization certifies that he has been trained in the Servicing of medical equipment and is hereby certified as competent to service the named equipment.
</div>

<!-- ENGINEER -->  
<table style="margin-top:20px;">
    <tr>
        <td width="30%">
            <strong>Service Done By</strong> 
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $engineerName }}
        </td>
    </tr>
    <tr>
        <td width="30%">
            <strong>Designation</strong>
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $engineerDesignation }}
        </td>
    </tr>
</table>

<!-- INSTRUMENT TABLE -->
<h4 style="margin-top:20px;">SERVICING INSTRUMENT DETAILS </h4>
<table width="100%" >
    <thead>
        <tr>
            <th width="2%" >SL</th>
            <th width="28%" >Instrument</th>
            <th width="10%" >Problem</th>
            <th width="60%" >Description</th>
        </tr>
    </thead>
    <tbody>
        @php
            $pendingTokens = $token->serviceMyTask->pendingServiceTokens ?? collect();
        @endphp

        @forelse($pendingTokens as $index => $pendingToken)
            <tr>
                <td width="2%" >{{ $index + 1 }}</td>
                <td width="28%" >
                    {{ $pendingToken->serviceToken->product->withoutModelSuffix()->name ?? 'N/A' }}<br>
                    Model:{{ $pendingToken->serviceToken->product->model ?? '' }}<br>
                    Serial: {{ $pendingToken->serviceToken->serial_number ?? '' }}
                </td>
                <td width="10%">  {{ $token->problem_details ?? 'N/A' }}</td>
                <td width="60%">  {{ $pendingToken->description ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td width="2%" >1</td>
                <td width="28%" >
                    {{ $token->product->withoutModelSuffix()->name ?? 'N/A' }}<br>
                    Model: {{ $token->product->model ?? '' }}<br>
                    Serial: {{ $token->serial_number ?? '' }}
                </td>
                <td width="10%" >{{ $token->problem_details ?? 'N/A' }}</td>
                <td width="60%" >{{ $token->serviceMyTask->description ?? 'N/A' }}</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- BASIC INFO -->
<h4 style="margin-top:20px;">BASIC INFORMATION </h4> 
<table>
    <tr>
        <td width="20%">
            <strong>Supply Voltage</strong> 
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="25%" style="text-align:left;">
            {{ $token->serviceMyTask->basic_info_supply_voltage ?? 'N/A' }}
        </td>
        <td width="20%">
            <strong>Generator</strong>
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="25%" style="text-align:left;">
            {{ $token->serviceMyTask->basic_info_generator_backup === 1 ? 'Yes' : ($token->serviceMyTask->basic_info_generator_backup === 0 ? 'No' : 'N/A') }}
        </td>
    </tr>
    <tr>
        <td width="20%">
            <strong>Ground</strong> 
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="25%" style="text-align:left;">
            {{ $token->serviceMyTask->basic_info_ground_voltage ?? 'N/A' }}
        </td>
        <td width="20%">
            <strong>UPS</strong>
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="25%" style="text-align:left;">
            {{ $token->serviceMyTask->basic_info_ups_backup ?? 'N/A' }}
        </td>
    </tr>
</table>
 

<!-- SPARE PARTS -->
<h4 style="margin-top:20px;">SPARE PARTS USED</h4>
<table>
    <thead>
        <tr>
            <th>SL</th>
            <th>Spare Name</th>
            <th>Qty</th>
        </tr>
    </thead>
    <tbody>
        @php
            $spareParts = collect();
            foreach ($token->serviceMyTask->bills ?? [] as $bill) {
                if ($bill->product && $bill->product->tag && stripos($bill->product->tag->name, 'service') === false) {
                    $spareParts->push([
                        'name' => $bill->product->withoutModelSuffix()->name,
                        'model' => $bill->product->model,
                        'quantity' => $bill->quantity ?? 1,
                    ]);
                }
            }
        @endphp

        @forelse($spareParts as $i => $part)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $part['name'] }} {{ $part['model'] ? '(' . $part['model'] . ')' : '' }}</td>
                <td>{{ $part['quantity'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="text-align:center;">No spare parts used</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- HANDOVER -->
<h4 style="margin-top:20px;">INSTRUMENTS HANDOVER TAKEN BY AFTER SERVICING:</h4> 
<table>
    <tr>
        <td width="30%">
            <strong>Name</strong> 
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $token->serviceMyTask->handover_info_name ?? 'N/A' }}
        </td>
    </tr>
    <tr>
        <td width="30%">
            <strong>Department</strong> 
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $token->serviceMyTask->handover_info_department ?? 'N/A' }}
        </td>
    </tr>
    <tr> 
        <td width="30%">
            <strong>Designation</strong>
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $token->serviceMyTask->handover_info_designation ?? 'N/A' }}
        </td>
    </tr> 
    <tr>
        <td width="30%">
            <strong>Contact Number</strong>
        </td>
        <td width="5%">
            <strong>:</strong>
        </td>
        <td width="65%" style="text-align:left;">
            {{ $token->serviceMyTask->handover_info_contact_no ?? 'N/A' }}
        </td>
    </tr>
</table>


<!-- REMARKS -->
@if($token->serviceMyTask && $token->serviceMyTask->remarks)
<h4>Remarks</h4>
<div>{{ $token->serviceMyTask->remarks }}</div>
@endif

<!-- SIGNATURE -->
<div class="signature-section">
    <h4>Seal & Signature</h4>
    <div class="signature-box"></div>
</div>

<!-- FOOTER -->
<div class="footer">
    <strong>This is System generated report and This service is verified through OTP. No signature required.</strong>
</div>

</body>
</html>