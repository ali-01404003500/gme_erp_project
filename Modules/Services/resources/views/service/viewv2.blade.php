@extends('layout.mpdf-view')
@section('title', 'Service Challan')
@section('content')
<div class="card-body">
    <!-- Document Title -->
    <div style="text-align: center;  padding-bottom: 15px;">
        <h2 style="font-size: 20px;  margin: 0; text-decoration: underline;">Service Challan</h2>
    </div>

    <!-- Service Information Section -->
    <div style="margin-bottom: 10px;">
        <table style="width: 100%; border: none; font-size: 11px;">
            <tr>
                <td style="width: 15%; border: none;  padding: 5px 0;">Service No</td>
                <td style="width: 2%; border: none; padding: 5px 0;">:</td>
                <td style="width: 33%; border: none; padding: 5px 0;">{{ $service->service_unique_id }}</td>
                
                <td style="width: 15%; border: none;  padding: 5px 0;">Date</td>
                <td style="width: 2%; border: none; padding: 5px 0;">:</td>
                <td style="width: 33%; border: none; padding: 5px 0;">{{ optional($service->serviceTokens[0] ?? null)->token_date }}</td>
            </tr>
            <tr>
                <td style="border: none;  padding: 5px 0;">Name</td>
                <td style="border: none; padding: 5px 0;">:</td>
                <td style="border: none; padding: 5px 0;">{{ optional($service->serviceTokens[0] ?? null)->customer->company_name }}</td>
                
                <td style="border: none;  padding: 5px 0;">Entry By</td>
                <td style="border: none; padding: 5px 0;">:</td>
                <td style="border: none; padding: 5px 0;">{{ $service->createdBy->name }}</td>
            </tr>
            <tr>
                <td style="border: none;  padding: 5px 0;">Address</td>
                <td style="border: none; padding: 5px 0;">:</td>
                <td style="border: none; padding: 5px 0;">{{ optional($service->serviceTokens[0] ?? null)->customer->address }}</td>
                
                <td style="border: none;  padding: 5px 0;">Print Date & Time</td>
                <td style="border: none; padding: 5px 0;">:</td>
                <td style="border: none; padding: 5px 0;">{{ date('d-M-Y H:i A') }}</td>
            </tr>
            <tr>
                <td style="border: none;  padding: 5px 0;">Phone</td>
                <td style="border: none; padding: 5px 0;">:</td>
                <td style="border: none; padding: 5px 0;">{{ optional($service->serviceTokens[0] ?? null)->customer->phone }}</td>
                <td colspan="3" style="border: none;"></td>
            </tr>
        </table>
    </div>

    <!-- Service Items Table -->
    <div style="margin-bottom: 30px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="border: 1px solid #000; padding: 10px; text-align: center;  width: 8%;">SN</th>
                    <th style="border: 1px solid #000; padding: 10px; text-align: center;  width: 50%;">Product Description</th>
                    <th style="border: 1px solid #000; padding: 10px; text-align: center;  width: 25%;">Serial No</th>
                    <th style="border: 1px solid #000; padding: 10px; text-align: center;  width: 17%;">Qty.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($service->serviceTokens as $key => $item)
                <tr>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $key + 1 }}</td>
                    <td style="border: 1px solid #000; padding: 8px;">
                        {{ $item->product->name }}
                    </td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $item->serial_number }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                        {{ number_format((float) $item->quantity, 0) == 1.0 ? '1' : number_format((float) $item->quantity, 0) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div style="margin-top: 20px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 70%; border: none;"></td>
                    <td style="width: 30%; border: none;">
                        <table style="width: 100%; border: none; font-size: 11px;">
                            <tr>
                                <td style="border: none; padding: 5px 0; ">Total Quantity</td>
                                <td style="border: none; padding: 5px 0; text-align: center;">:</td>
                                <td style="border: none; padding: 5px 0; text-align: right; ">
                                    {{ $service->serviceTokens->sum(function ($item) {
                                        return number_format((float) $item->quantity, 0) == 1.0 ? 1 : number_format((float) $item->quantity, 0);
                                    }) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Service Notes Section -->
    <div style="margin-bottom: 40px;">
        <h4 style="font-size: 14px; font-weight: bold; margin-bottom: 15px;">নোটসমূহ:</h4>
        <div style="font-size: 11px; line-height: 1; padding-left: 20px;">
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">১.</span> আপনার মেশিনটি এবং এর সাথে আসা সকল পার্টসের ভিডিও ধারণ করে আমাদের সার্ভারে সেভ করে রাখা হয়েছে। 
                পরবর্তীতে ভিডিও অনুসরণ করে মেশিনটি আপনাকে বুঝিয়ে দেওয়া হবে।
            </div>
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">২.</span> আপনার মেশিনের ওয়ারেন্টি সময়কাল অতিবাহিত না হলে ফ্রি সার্ভিস বুঝে নিন। (শর্ত প্রযোজ্য)
            </div>
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">৩.</span> সার্ভিস কাজ চলাকালীন, মেশিনের কোন পার্টস মেরামতের সময় তা আর ঠিক না হলে, তার দায়ভার গ্লোবাল 
                মেডিকেল ইঞ্জিনিয়ারিং (বিডি) লিঃ এর সার্ভিস সেন্টার বহন করবে না।
            </div>
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">৪.</span> আপনার অনুমতি প্রদানপূর্বক সার্ভিস কাজটি শুরু করার জন্য আমাদের বাধিত করবেন।
            </div>
        </div>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 60px;">
        <table style="width:100%; border:none;">
                            <tr>
                                <td style="width:50%; text-align:center; border:none;">
                                    ___________________________ <br>
                                    Received
                                </td>
                                <td style="width:50%; text-align:center; border:none;">
                                    ___________________________ <br>
                                    Authorized
                                </td>
                            </tr>
                        </table>
    </div>
</div>
@endsection