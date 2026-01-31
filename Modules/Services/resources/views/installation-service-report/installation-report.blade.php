<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Installation</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding-top: 20px !important;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        .company-details {
            font-size: 9px;
            color: #666;
        }
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }
        .report-info {
            margin: 15px 0;
            font-size: 10px;
        }
        .customer-info {
            margin: 20px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 3px solid #007bff;
        }
        .certification-text {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            font-size: 11px;
            line-height: 1.6;
            font-style: italic;
        }
        .engineer-info {
            margin: 20px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        
        .basic-info {
            margin: 20px 0;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 5px;
        }
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            border: 1px solid #333;
            min-height: 80px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #333;
            font-size: 9px;
            color: #666;
        }
        .download-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #007bff;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 10px;
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
    <header class="my-header">
                  @include('partials._for_pdf_header_2nd')
              </header>

    <!-- Report Title -->
    <div class="report-title">
        CERTIFICATE OF INSTALLATION OF MEDICAL EQUIPMENT
    </div>

    <!-- Report Info -->
    <div class="report-info">
        <div><strong>Report No:</strong> {{ $reportNo ?? 'N/A' }}</div>
        <div><strong>Date:</strong> {{ $installationDate }}</div>
    </div>

    <!-- Customer Information -->
    <div class="customer-info">
        <div><strong>Organization Name:</strong> {{ $token->customer->company_name ?? 'N/A' }}</div>
        <div><strong>Address:</strong> {{ $token->customer->address ?? 'N/A' }}</div>
    </div>

    <!-- Certification Statement -->
    <div class="certification-text">
        We the above named organization certify that he has been trained in the installation of medical equipment 
        and is hereby certified as competent to install the named equipment.
    </div>

    <!-- Engineer Information -->
    <div class="engineer-info">
        <div><span class="info-label">Installation Done By:</span> {{ $engineerName }}</div>
        <div><span class="info-label">Designation:</span> {{ $engineerDesignation }}</div>
    </div>

    <!-- Instrument Details Table -->
    <h4 style="margin-top: 20px;">Instrument Details</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">SL</th>
                <th style="width: 90%;">Instrument Details</th>
            </tr>
        </thead>
        <tbody>
           
                <tr>
                    <td class="text-center">{{  1 }}</td>
                    <td>
                            <strong>Name:</strong> {{ $token->product->withoutModelSuffix()->name ?? 'N/A' }}<br>
                            <strong>Model:</strong> {{ $token->product->model ?? 'N/A' }}<br>
                            <strong>Serial No:</strong> {{ $token->serial_number ?? 'N/A' }}
                        </td>
                </tr>
                
        </tbody>
    </table>

    <!-- Basic Information -->
    <h4 style="margin-top: 20px;">Basic Information</h4>
    <div class="basic-info">
        
        <div class="info-row">
            <div class="info-cell"><strong>Supply Voltage:</strong> {{ $token->serviceMyTask->basic_info_supply_voltage ?? 'N/A' }}</div>
            <div class="info-cell"><strong>Generator Backup:</strong> {{ $token->serviceMyTask->basic_info_generator_backup ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><strong>Ground Voltage:</strong> {{ $token->serviceMyTask->basic_info_ground_voltage ?? 'N/A' }}</div>
            <div class="info-cell"><strong>UPS Backup:</strong> {{  $token->serviceMyTask->basic_info_ups_backup ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Handover Information -->
    <h4 style="margin-top: 20px;">Instrument Handover Information</h4>
    <div class="basic-info">
        
        <div class="info-row">
            <div class="info-cell"><strong>Name:</strong> {{ $token->serviceMyTask->handover_info_name ?? 'N/A' }}</div>
            <div class="info-cell"><strong>Department:</strong> {{ $token->serviceMyTask->handover_info_department ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><strong>Designation:</strong> {{ $token->serviceMyTask->handover_info_designation ?? 'N/A' }}</div>
            <div class="info-cell"><strong>Contact Number:</strong> {{ $token->serviceMyTask->handover_info_contact_no ?? 'N/A' }}</div>
        </div>
    </div>

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
            <strong>This is a software generated report and this installation is verified through OTP with this number 01910501060.</strong><br>
            No signature required.
        </p>
    </div>
</body>
</html>