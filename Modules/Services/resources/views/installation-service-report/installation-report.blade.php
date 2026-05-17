<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Installation</title>
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
        $installationDate = @$token->serviceMyTask->updated_at;
        $reportNo = 'GME-CER-' . $token->serviceMyTask->updated_at->format('Ym') . '-' . str_pad($token->id, 4, '0', STR_PAD_LEFT);

        $engineerName = 'N/A';
        $engineerDesignation = 'N/A';
        if ($token->engineerAssign && $token->engineerAssign->engineers->count() > 0) {
            $engineer = $token->engineerAssign->engineers->first();
            $engineerName = $engineer->full_name;
            $engineerDesignation = $engineer->designation->name ?? 'N/A';
        }
    @endphp

    <!-- Header -->
    <div class="header">
        @include('partials._for_pdf_header_2nd')
    </div>

    <!-- Report Title -->
    <div class="report-title">
        CERTIFICATE OF INSTALLATION OF MEDICAL EQUIPMENT
    </div>

    <!-- Report Info -->  
    <table style="margin-top:20px;">
        <tr>
            <td width="50%">
                <strong>Report No:</strong> {{ $reportNo ?? 'N/A' }}
            </td>
            <td width="50%" style="text-align:right;">
                <strong>Date:</strong> {{ $installationDate->format('d-m-Y') }}
            </td>
        </tr>
    </table>

    <!-- Customer Information -->  
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

    <!-- Certification Statement -->
    <div class="certification-text" style="font-size:16px;">
        We the above named organization certify that he has been trained in the installation of medical equipment 
        and is hereby certified as competent to install the named equipment.
    </div>

    <!-- Engineer Information -->
    <table style="margin-top:20px;">
        <tr>
            <td width="30%">
                <strong>Installation Done By</strong> 
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

    <!-- Instrument Details Table -->
    <h4 style="margin-top:20px;">INSTALL INSTRUMENT DETAILS </h4>
    <table width="100%" >
        <thead>
            <tr>
                <th width="5" >SL</th>
                <th width="95%" >Instrument Details</th> 
            </tr>
        </thead>
        <tbody>
            @php
                $pendingTokens = $token->serviceMyTask->pendingServiceTokens ?? collect();
            @endphp

            @forelse($pendingTokens as $index => $pendingToken)
                <tr>
                    <td width="5%" >{{ $index + 1 }}</td>
                    <td width="95%" >
                        {{ $pendingToken->serviceToken->product->withoutModelSuffix()->name ?? 'N/A' }}<br>
                        Model:{{ $pendingToken->serviceToken->product->model ?? '' }}<br>
                        Serial: {{ $pendingToken->serviceToken->serial_number ?? '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td width="5%" >1</td>
                    <td width="95%" >
                        {{ $token->product->withoutModelSuffix()->name ?? 'N/A' }}<br>
                        Model: {{ $token->product->model ?? '' }}<br>
                        Serial: {{ $token->serial_number ?? '' }}
                    </td>
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
    
 
    <!-- Operator Training -->
    <h4 style="margin-top:20px;">OPERATOR TRAINING: </h4> 
    <table>
        <tr>
            <td width="30%">
                <strong>Operator Training Complete</strong> 
            </td>
            <td width="5%">
                <strong>:</strong>
            </td>
            <td width="65%" style="text-align:left;">
               {{ $token->serviceMyTask->operator_info_training_status === 1 ? 'Yes' : ($token->serviceMyTask->basic_info_generator_backup === 0 ? 'No' : 'No') }}
            </td>
        </tr> 
        <tr>
            <td width="30%">
                <strong>Operator Name</strong> 
            </td>
            <td width="5%">
                <strong>:</strong>
            </td>
            <td width="65%" style="text-align:left;">
                {{ $token->serviceMyTask->operator_info_name ?? 'N/A' }}
            </td>
        </tr>       
        <tr> 
            <td width="30%">
                <strong>Operator Designation</strong>
            </td>
            <td width="5%">
                <strong>:</strong>
            </td>
            <td width="65%" style="text-align:left;">
                {{ $token->serviceMyTask->operator_info_designation ?? 'N/A' }}
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
                {{ $token->serviceMyTask->operator_info_contact_no ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong>Operator Comments</strong>
            </td>
            <td width="5%">
                <strong>:</strong>
            </td>
            <td width="65%" style="text-align:left;">
                {{ $token->serviceMyTask->operator_comments ?? 'N/A' }}
            </td>
        </tr>
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

    <!-- Remarks -->
    @if($token->serviceMyTask && $token->serviceMyTask->remarks)
        <h4 style="margin-top: 20px;">Remarks</h4>
        <div style="padding: 10px; background-color: #f9f9f9; border-left: 3px solid #007bff;">
            {{ $token->serviceMyTask->remarks }}
        </div>
    @endif

    <!-- Seal & Signature Section -->
    <div class="signature-section">
        <h4>Seal & Signature</h4>
        <div class="signature-box"></div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>This is a software generated report and this installation is verified through OTP.</strong><br>
            No signature required.
        </p>
    </div>
</body>
</html>