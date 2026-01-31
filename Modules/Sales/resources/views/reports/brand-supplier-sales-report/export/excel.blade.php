<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table border="1">
    <thead>
        <!-- Company Header -->
        <tr>
            <th colspan="7" style="text-align: center; font-size: 18px; font-weight: bold; background-color: #4472C4; color: white; height: 35px;">
                {{ $company_info->company_name ?? 'Company Name' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 12px; background-color: #D9E1F2; height: 25px;">
                {{ $company_info->company_address ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 12px; background-color: #D9E1F2; height: 25px;">
                Phone: {{ $company_info->company_phone ?? '' }} | Email: {{ $company_info->company_email ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #E7E6E6; height: 30px;">
                BRAND/SUPPLIER WISE SALES REPORT
            </th>
        </tr>
        
        <!-- Filter Information -->
        <tr>
            <th colspan="7" style="background-color: #F2F2F2; text-align: left; font-size: 11px; height: 25px;">
                <strong>Filters Used:</strong>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #FFF; text-align: right; font-weight: bold;">Brand Name:</th>
            <th colspan="5" style="background-color: #FFF; text-align: left;">{{ $brandName }}</th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #F8F9FA; text-align: right; font-weight: bold;">Product Tag:</th>
            <th colspan="5" style="background-color: #F8F9FA; text-align: left;">{{ $productTagName ?? 'All' }}</th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #FFF; text-align: right; font-weight: bold;">Top Range:</th>
            <th colspan="5" style="background-color: #FFF; text-align: left;">{{ $topRange }}</th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #F8F9FA; text-align: right; font-weight: bold;">Date Range:</th>
            <th colspan="5" style="background-color: #F8F9FA; text-align: left;">{{ $fromDate }} to {{ $toDate }}</th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #FFF; text-align: right; font-weight: bold;">Generated on:</th>
            <th colspan="5" style="background-color: #FFF; text-align: left;">{{ now()->format('d-M-Y h:i A') }}</th>
        </tr>
        
        <tr>
            <td colspan="7" style="height: 10px;">&nbsp;</td>
        </tr>

        <!-- Table Headers -->
        <tr style="background-color: #4472C4; color: white; font-weight: bold; height: 35px;">
            <th style="width: 50px;">SL</th>
            <th style="width: 250px;">Customer Name &amp; Address</th>
            <th style="width: 120px;">Phone</th>
            <th style="width: 400px;">Total Quantity</th>
            <th style="width: 400px;">Total Price</th>
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
            <tr style="{{ $index % 2 == 0 ? 'background-color: #F9F9F9;' : '' }}">
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $customer['customer_name'] }}</strong><br/>
                    {{ $customer['customer_address'] }}
                </td>
                <td>{{ $customer['customer_phone'] }}</td>
                <td>
                    {{-- Quantity Summary --}}
                    <strong style="color: #0066cc;">
                        @if($isDrawray)
                            Quantity: {{ number_format($totalQuantity) }} Test ⇔ {{ number_format($totalKits) }} Kit
                        @else
                            Quantity: {{ number_format($totalQuantity) }}
                        @endif
                    </strong>
                    <br/><br/>
                    
                    {{-- Product Quantity Details --}}
                    @foreach($customer['products'] as $pIndex => $product)
                        {{ $product['product_name'] }}: 
                        @if($isDrawray)
                            {{ number_format($product['quantity']) }} Test ⇔ {{ number_format($product['quantity'] / 20) }} Kit
                        @else
                            {{ number_format($product['quantity']) }} {{ $product['unit_type'] }}
                        @endif
                        @if($pIndex < count($customer['products']) - 1)
                            <br/>
                        @endif
                    @endforeach
                </td>
                <td>
                    {{-- Sales Summary --}}
                    <strong style="color: #28a745;">
                        Sales: {{ number_format($customer['total_amount']) }}
                    </strong>
                    <br/><br/>
                    
                    {{-- Product Sales Details --}}
                    @foreach($customer['products'] as $pIndex => $product)
                        {{ $product['product_name'] }}: {{ number_format($product['total_price']) }}
                        @if($pIndex < count($customer['products']) - 1)
                            <br/>
                        @endif
                    @endforeach
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; font-style: italic; color: #666;">
                    No records found matching the selected filters
                </td>
            </tr>
        @endforelse

        <!-- Empty Row -->
        <tr>
            <td colspan="5" style="height: 15px;">&nbsp;</td>
        </tr>

        <!-- Grand Total Summary -->
        @if(count($reportData) > 0)
        <tr style="background-color: #D9E1F2; font-weight: bold; height: 35px;">
            <td colspan="3" style="text-align: right; font-size: 14px;"><strong>GRAND TOTAL:</strong></td>
            <td style="text-align: center; font-size: 12px;">
                @if($isDrawray)
                    {{ number_format($grandTotalQuantity) }} Test ⇔ {{ number_format($grandTotalKits) }} Kit
                @else
                    {{ number_format($grandTotalQuantity) }}
                @endif
            </td>
            <td style="text-align: right; font-size: 12px; color: #28a745;">{{ number_format($grandTotalAmount) }}</td>
        </tr>
        @endif
        
        <tr>
            <td colspan="5" style="height: 15px;">&nbsp;</td>
        </tr>
        
        <tr>
            <td colspan="5" style="background-color: #E7E6E6; font-weight: bold; text-align: center; height: 30px;">
                SUMMARY INFORMATION
            </td>
        </tr>

        <!-- Additional Summary -->
        <tr style="background-color: #F2F2F2;">
            <td colspan="2" style="font-weight: bold; text-align: right;">Total Customers:</td>
            <td colspan="3">{{ count($reportData) }}</td>
        </tr>
        <tr style="background-color: #FFF;">
            <td colspan="2" style="font-weight: bold; text-align: right;">Brand Name:</td>
            <td colspan="3">{{ $brandName }}</td>
        </tr>
        <tr style="background-color: #F2F2F2;">
            <td colspan="2" style="font-weight: bold; text-align: right;">Product Tag:</td>
            <td colspan="3">{{ $productTagName ?? 'All' }}</td>
        </tr>
        <tr style="background-color: #FFF;">
            <td colspan="2" style="font-weight: bold; text-align: right;">Total Quantity:</td>
            <td colspan="3" style="font-weight: bold; color: #0066cc;">
                @if($isDrawray)
                    {{ number_format($grandTotalQuantity) }} Test ⇔ {{ number_format($grandTotalKits) }} Kit
                @else
                    {{ number_format($grandTotalQuantity) }}
                @endif
            </td>
        </tr>
        <tr style="background-color: #D4EDDA;">
            <td colspan="2" style="font-weight: bold; text-align: right;">Total Sales Amount:</td>
            <td colspan="3" style="font-weight: bold; color: #28a745;">{{ number_format($grandTotalAmount) }}</td>
        </tr>
        
        @if($isDrawray)
        <tr>
            <td colspan="5" style="height: 10px;">&nbsp;</td>
        </tr>
        <tr style="background-color: #FFF3CD;">
            <td colspan="5" style="text-align: center; font-weight: bold; color: #856404;">
                Note: For Drawray brand, quantities are converted using the formula: 20 Test = 1 Kit
            </td>
        </tr>
        @endif
        
        <tr>
            <td colspan="5" style="height: 15px;">&nbsp;</td>
        </tr>
        
        <tr style="background-color: #F2F2F2;">
            <td colspan="5" style="text-align: center; font-size: 10px; font-style: italic;">
                Report generated on {{ now()->format('d-M-Y h:i A') }} by {{ auth()->user()->name ?? 'System' }} | 
                Copyright {{ now()->year }} {{ $company_info->company_name ?? 'Company Name' }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>