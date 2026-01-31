<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Brand/Supplier Wise Sales Report</title>
    <style>
        @page {
            margin: 10mm 8mm;
            size: landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }
        
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }
        
        .company-details {
            font-size: 9px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            background-color: #4472C4;
            color: white;
            padding: 8px;
            text-align: center;
        }
        
        .filter-info {
            font-size: 8px;
            background-color: #f8f9fa;
            padding: 6px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }
        
        .filter-info strong {
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 7px;
        }
        
        th {
            background-color: #4472C4;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
        }
        
        td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        .customer-name {
            font-weight: bold;
            color: #333;
            font-size: 8px;
        }
        
        .customer-address {
            color: #666;
            font-size: 6px;
            margin-top: 2px;
        }
        
        .product-item {
            margin-bottom: 3px;
            line-height: 1.4;
        }
        
        .product-name {
            color: #333;
        }
        
        .product-quantity {
            font-weight: bold;
            color: #0066cc;
        }
        
        .product-price {
            font-weight: bold;
            color: #28a745;
        }
        
        .total-quantity {
            font-weight: bold;
            color: #0066cc;
            text-align: center;
        }
        
        .total-price {
            font-weight: bold;
            color: #28a745;
            font-size: 8px;
        }
        
        .grand-total-row {
            background-color: #D9E1F2 !important;
            font-weight: bold;
            font-size: 8px;
        }
        
        .grand-total-row td {
            padding: 8px 4px;
            border: 2px solid #4472C4;
        }
        
        .footer {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid #333;
            font-size: 7px;
            text-align: center;
            color: #666;
        }
        
        .conversion-label {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $company_info->company_name ?? 'Company Name' }}</div>
        <div class="company-details">{{ $company_info->company_address ?? '' }}</div>
        <div class="company-details">
            Phone: {{ $company_info->company_phone ?? '' }} | 
            Email: {{ $company_info->company_email ?? '' }}
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">BRAND/SUPPLIER WISE SALES REPORT</div>
    
    <!-- Filter Information -->
    <div class="filter-info">
        <strong>Filters Used:</strong> 
        Brand Name: <strong>{{ $brandName }}</strong> | 
        Product Tag: <strong>{{ $productTagName ?? 'All' }}</strong> | 
        Top Range: <strong>{{ $topRange }}</strong> | 
        Date Range: <strong>{{ $fromDate }} to {{ $toDate }}</strong> | 
        Generated on: <strong>{{ now()->format('d-M-Y h:i A') }}</strong>
    </div>

    <!-- Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">SL</th>
                <th style="width: 22%;">Customer Name & Address</th>
                <th style="width: 8%;">Phone</th>
                <th style="width: 35%;">Total Quantity</th>
                <th style="width: 32%;">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalQuantity = 0;
                $grandTotalKits = 0;
                $grandTotalAmount = 0;
                $isDrawray = strtolower($brandName) === 'drawray';
            @endphp

            @forelse($reportData as $index => $customer)
                @php
                    $totalQuantity = 0;
                    $totalKits = 0;
                    
                    foreach($customer['products'] as $product) {
                        $totalQuantity += $product['quantity'];
                        if($isDrawray) {
                            $totalKits += $product['quantity'] / 20;
                        }
                    }
                    
                    $grandTotalQuantity += $totalQuantity;
                    if($isDrawray) {
                        $grandTotalKits += $totalKits;
                    }
                    $grandTotalAmount += $customer['total_amount'];
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="customer-name">{{ $customer['customer_name'] }}</div>
                        <div class="customer-address">{{ $customer['customer_address'] }}</div>
                    </td>
                    <td>{{ $customer['customer_phone'] }}</td>
                    <td>
                        {{-- Quantity Summary --}}
                        <div style="text-align: right; font-weight: bold; margin-bottom: 4px;">
                            @if($isDrawray)
                                Quantity: {{ number_format($totalQuantity) }} Test ⇔ {{ number_format($totalKits) }} Kit
                            @else
                                Quantity: {{ number_format($totalQuantity) }}
                            @endif
                        </div>
                        
                        {{-- Product Quantity Details --}}
                        @foreach($customer['products'] as $product)
                            <div class="product-item">
                                <span class="product-name">{{ $product['product_name'] }}:</span>
                                @if($isDrawray)
                                    <span class="product-quantity">{{ number_format($product['quantity']) }} Test ⇔ {{ number_format($product['quantity'] / 20) }} Kit</span>
                                @else
                                    <span class="product-quantity">{{ number_format($product['quantity']) }} {{ $product['unit_type'] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </td>
                    <td>
                        {{-- Sales Summary --}}
                        <div style="text-align: right; font-weight: bold; margin-bottom: 4px; color: #28a745;">
                            Sales: {{ number_format($customer['total_amount']) }}
                        </div>
                        
                        {{-- Product Sales Details --}}
                        @foreach($customer['products'] as $product)
                            <div class="product-item">
                                <span class="product-name">{{ $product['product_name'] }}:</span>
                                <span class="product-price">{{ number_format($product['total_price']) }}</span>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">
                        No records found matching the selected filters
                    </td>
                </tr>
            @endforelse
            
            @if(count($reportData) > 0)
            <tr class="grand-total-row">
                <td colspan="3" class="text-right"><strong>GRAND TOTAL:</strong></td>
                <td class="text-center">
                    @if($isDrawray)
                        <strong>{{ number_format($grandTotalQuantity) }} Test ⇔ {{ number_format($grandTotalKits) }} Kit</strong>
                    @else
                        <strong>{{ number_format($grandTotalQuantity) }}</strong>
                    @endif
                </td>
                <td class="text-right"><strong>{{ number_format($grandTotalAmount) }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Total Customers: {{ count($reportData) }}</strong></p>
        @if($isDrawray)
            <p><strong>Note:</strong> For Drawray brand, quantities are converted: <span class="conversion-label">20 Test = 1 Kit</span></p>
        @endif
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}. All rights reserved. | 
        Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>