<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Invoice</title>
    <style>
        @page {
            margin: 20mm 10mm 25mm 10mm;
        }
        body {
            font-family: 'dejavusans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
        }
        
        .header-table { width: 100%; border: none; margin-bottom: 10px; }
        .header-table td { border: none; vertical-align: top; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        h1, h2, h3, h4 { margin: 0; padding: 0; }
        .invoice-title { font-size: 16px; font-weight: bold; text-align: center; margin: 10px 0 15px 0; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px 6px; }
        th { background: #f3f3f3; }

        .no-border td, .no-border th { border: none !important; padding: 2px 6px; }
        .summary-table td { border: none; padding: 4px 6px; }

        .footer {
            position: fixed;
            bottom: 15mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
        }

        .watermark {
            position: fixed;
            top: 45%;
            left: 20%;
            transform: rotate(-30deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.1);
            z-index: -1;
        }

        .bangla-text {
            font-family: dejavusans;
            font-size: 11px;
            line-height: 1.6;
            text-align: justify;
        }

        .totals td {
            border: none !important;
            padding: 2px 4px;
        }
    </style>
</head>
<body>
    {{-- Watermark --}}
    <div class="watermark">{{ strtoupper($salesOrder->paid_status) }}</div>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 15%;">
                <img src="{{ public_path($company_info->company_logo) }}" width="70">
            </td>
            <td class="text-center">
                <h2>{{ $company_info->company_name }}</h2>
                <p>{{ $company_info->company_bio }}</p>
                <p>Address : {{ $company_info->address }}</p>
                <p>Hotline : {{ $company_info->hotline }} | Mobile : {{ $company_info->mobile }}</p>
                <p>Email : {{ $company_info->email }} | Web : {{ $company_info->website }}</p>
            </td>
            <td style="width: 15%;"></td>
        </tr>
    </table>

    <div class="invoice-title">
        @if ($salesOrder->service_id)
            Service Sales Invoice Bill
        @elseif($salesOrder->sales_type == 'free_sales')
            Free Sales Invoice Bill
        @elseif($salesOrder->sales_type == 'partial_sales')
            Partial Sales Invoice Bill
        @else
            Sales Invoice
        @endif
    </div>

    {{-- Customer / Invoice Info --}}
    <table class="no-border">
        <tr>
            <td><strong>Invoice No :</strong> {{ $salesOrder->sales_order_id }}</td>
            <td><strong>Date :</strong> {{ $salesOrder->invoice_date }}</td>
        </tr>
        <tr>
            <td><strong>Name :</strong> {{ $salesOrder->customer->company_name }}</td>
            <td><strong>Time :</strong> {{ date('h:i A', strtotime($salesOrder->invoice_date)) }}</td>
        </tr>
        <tr>
            <td><strong>Address :</strong> {{ $salesOrder->customer->address }}</td>
            <td><strong>Sold By :</strong> {{ $salesOrder->createdBy->name }}</td>
        </tr>
        <tr>
            <td><strong>Phone :</strong> {{ $salesOrder->additional_phone }}</td>
            <td><strong>Print Date :</strong> {{ now()->format('d-M-Y h:i A') }}</td>
        </tr>
    </table>

    {{-- Products --}}
    <table style="margin-top: 10px;">
        <thead>
            <tr>
                <th style="width:5%;">SN</th>
                <th>Product Description</th>
                <th style="width:10%;">Qty.</th>
                <th style="width:15%;">Unit Price</th>
                <th style="width:15%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesOrder->salesOrderDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $detail->product->name }}
                        @if($detail->is_offers_product)
                            <span style="color:green;">Offer Product</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($detail->quantity) }}</td>
                    <td class="text-right">{{ number_format($detail->price) }}</td>
                    <td class="text-right">{{ number_format($detail->amount) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- In Words --}}
    <p style="margin-top:10px;"><strong>IN WORD :</strong>
        {{ convert_number($salesOrder->total_amount) }} Taka Only
    </p>

    {{-- Totals --}}
    <table class="totals text-right" style="width:40%; margin-left:auto;">
        <tr><td>Total :</td><td>{{ number_format($salesOrder->total_amount) }}</td></tr>
        <tr><td>Discount (-) :</td><td>{{ number_format($salesOrder->discount) }}</td></tr>
        <tr><td>VAT(5%) :</td><td>{{ number_format($salesOrder->vat) }}</td></tr>
        <tr><td><strong>Grand Total :</strong></td><td><strong>{{ number_format($salesOrder->total) }}</strong></td></tr>
    </table>

    {{-- Payment Summary --}}
    <table style="margin-top:15px;">
        <tr><th>Last Collection Date</th><td>:</td><td class="text-right">03-Aug-2025(15,000.00)</td></tr>
        <tr><th>Previous Due</th><td>:</td><td class="text-right">5,764,785.00</td></tr>
        <tr><th>Sales</th><td>:</td><td class="text-right">{{ number_format($salesOrder->total_amount) }}</td></tr>
        <tr><th>Paid</th><td>:</td><td class="text-right">0.00</td></tr>
        <tr><th>Total Due</th><td>:</td><td class="text-right">8,256,785.00</td></tr>
    </table>

    {{-- Bangla Notes --}}
    <div class="bangla-text" style="margin-top:10px;">
        <p>১. সুপ্রিয় গ্রাহক, লেন-দেনের সময় রশিদ বুঝিয়া নিবেন। রশিদ ছাড়া কোন রকম অভিযোগ গ্রহণযোগ্য হবে না।</p>
        <p>২. প্রতিটি বিল পাওয়ার পর প্রিভিয়াস ডিউ চেক করবেন। কোন সমস্যা থাকলে বিল পাওয়ার সাথে সাথে ফোন করে সমাধান নিবেন।৫ দিন অতিবাহিত হলে কোন অভিযোগ গ্রহণযোগ্য হবে না। আমাদের একমাত্র বিকাশ নং ০১৮৫২২৭৮২০০, ০১৪০৪০০৩৫০১ (বিকাশ পেমেন্ট)।</p>
        <p>৩. খুচরা রিএজেন্টের রেজাল্টের মান নিয়ে সকল অভিযোগ অগ্রহনযোগ্য ও উক্ত রিএজেন্ট অফেরতযোগ্য।</p>
        <p>৪.যে কোন প্রয়োজনে যোগাযোগ করুন +০৯৬৭৮০২০৫৫৫ অথবা, ০১৪০৪০০৩৫০০ নম্বরে। যেকোন প্রোডাক্ট অর্ডার করতে কল করুন- ০১৪০৪০০৩৫০১ নম্বরে, সার্ভিসিং এর জন্য যোগাযোগ করুন- ০১৪০৪০০৩৫৩৫ নম্বরে।</p>
        <p>৫. কুরিয়ারে বহনকালে প্রাকৃতিক দুর্যোগ, অগ্নিকান্ড, বা অনভিপ্রেত যেকোনো কারনে মালামালের ক্ষতি হইলে গ্লোবাল মেডিকেল ইঞ্জিনিয়ারিং (বিডি) লিঃ কোনো ভাবে দায়ী নয়।</p>
        <p>৬। কুরিয়ার থেকে দ্রুত পণ্য গ্রহণ করে সঠিক তাপমাত্রায় সংরক্ষণ করুন অন্যথায় রেজাল্টের তারতম্য হওয়ার সম্ভাবনা রয়েছে। তাপমাত্রা জনিত কারণে কোন অভিযোগ গ্রহণযোগ্য নয় ও এর দায়ভার একান্ত গ্রাহকের উপর বর্তায়।</p>
    </div>

    <div class="footer">
        <p>Received ___________________________ &nbsp;&nbsp;&nbsp; Authorized ___________________________</p>
    </div>
</body>
</html>
