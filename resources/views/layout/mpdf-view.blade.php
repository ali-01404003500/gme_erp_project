<!DOCTYPE html>
<html>
<head>
   
    <title>  @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <style>
        /* General Styles */
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        /* Header Styles */
        .header {
            position: relative;
            width: 100%;
        }

        .logo-container {
            float: left;
            width: 75px;
            max-height: 75px;
            margin-right: 15px;
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .company-info {
            float: right;
            width: calc(100% - 145px);
            text-align: center;
            margin-bottom: 16px;
        }

        .company-name {
            color: rgb(13, 13, 92);
            font-size: 29px;
            font-weight: bold;
            margin: 0;
        }

        .company-details {
            font-size: 10px;
            line-height: 1.3;
            margin: 5px 0 0 5px;
        }

        .clearfix {
            clear: both;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        /* Info Table (borderless) */
        .info-table {
            border: none !important;
        }

        .info-table td,
        .info-table th {
            border: none !important;
            padding: 4px 8px;
            font-size: 11px;
        }

        .info-table .label {
            font-weight: bold;
            color: #444;
            width: 15%;
        }

        .info-table .colon {
            width: 2%;
        }

        .info-table .value {
            color: #333;
        }

        /* Data Table */
        .data-table {
            border: 1px solid #333;
        }

        .data-table th {
            background-color: #f8f9fa;
            border: 1px solid #333;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            color: #333;
        }

        .data-table td {
            border: 1px solid #333;
            padding: 8px;
            font-size: 11px;
            vertical-align: top;
        }

        .data-table .center {
            text-align: center;
        }

        .data-table .right {
            text-align: right;
        }

        /* Product description styling */
        .product-name {
            font-weight: bold;
            color: #333;
        }

        .product-model {
            font-size: 10px;
            color: #666;
            font-style: italic;
        }

        /* Total section */
        .total-section {
            margin-top: 20px;
            font-size: 11px;
        }

        .total-table {
            border: none !important;
            width: 300px;
            float: right;
        }

        .total-table td {
            border: none !important;
            padding: 5px 8px;
        }

        .total-label {
            font-weight: bold;
            color: #444;
        }

        .total-value {
            text-align: right;
            font-weight: bold;
            color: #333;
        }

        /* Notes Section */
        .notes-section {
            margin: 30px 0;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .notes-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .notes-list {
            font-size: 10px;
            line-height: 1.5;
            color: #555;
            padding-left: 20px;
        }

        .notes-list li {
            margin-bottom: 8px;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 80px;
            page-break-inside: avoid;
        }

        .signature-table {
            border: none !important;
            width: 100%;
        }

        .signature-table td {
            border: none !important;
            text-align: center;
            padding: 20px 0;
            width: 50%;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 0 auto 10px auto;
            height: 1px;
        }

        .signature-label {
            font-weight: bold;
            font-size: 12px;
            color: #333;
        }

        /* Document title */
        .document-title {
            text-align: center;
            margin: 20px 0 30px 0;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .document-title h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .font-small { font-size: 10px; }
        .color-primary { color: #007bff; }
        .color-secondary { color: #666; }
        
        /* Page break utilities */
        .page-break-before { page-break-before: always; }
        .page-break-after { page-break-after: always; }
        .page-break-inside-avoid { page-break-inside: avoid; }
    </style>
    @yield('html_head')
</head>

<body>
    <!-- Header Section -->
    <div class="header">
        <!-- Logo Container -->
        <div class="logo-container">
            <img src="{{ s3FileToBase64($company_info->company_logo) }}" 
                 alt="Company Logo" 
                 width="120" 
                 height="auto">
        </div>
        
        <!-- Company Info -->
        <div class="company-info">
            <h1 class="company-name">{{ $company_info->company_name ?? 'Global Medical Engineering (BD) Ltd.' }}</h1>
            <p class="company-details">{{ $company_info->company_bio }}</p>
            <p class="company-details">{{ $company_info->company_address }}</p>
            <p class="company-details">Hotline: {{ $company_info->company_phone }}</p>
            <p class="company-details">e-mail: {{ $company_info->company_email }} web: {{ $company_info->website }}</p>
        </div>
        
        <!-- Clear Floats -->
        <div class="clearfix"></div>
    </div>

    <!-- Main Content -->
    @yield('content')
</body>
</html>