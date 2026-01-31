<style>
    @import url('https://fonts.maateen.me/kalpurush/font.css');

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
    font-size: 16px;
    text-decoration: underline;
}

.sales-order-info {
    justify-content: space-between;
    margin-bottom: 20px;
    font-size: 12px;
}

.sales-order-info .left,
.sales-order-info .right {
    width: 100%;
    /* Adjusted width */
}

.sales-order-info table {
    width: 100%;
    border-collapse: collapse;
    border: none;
    /* Removed border color */
}

.sales-order-info th,
.sales-order-info td {
    padding: 5px;
    text-align: left;
    font-size: 14px;
}

.invoice-details {
    margin-bottom: 20px;
}

.invoice-details table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}

.invoice-details table,
.invoice-details th,
.invoice-details td {
    border: 1px solid #000;
}

.invoice-details th,
.invoice-details td {
    padding: 8px;
    text-align: left;
    font-size: 14px;
}

.invoice-details p {
    margin: 5px 0;
    font-size: 14px;
}

.invoice-details .totals {
    text-align: right;
}

.invoice-details .totals p {
    margin: 5px 0;
    font-size: 14px;
}

.footer {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.footer p {
    margin: 10px 0;
    font-size: 14px;
    width: 45%;
    text-align: center;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                
                <header class="my-header">
                    @include('partials._for_pdf_header_2nd')
                </header>

                <section class="title">
                    <h2>Sales Requisition Invoice Bill</h2>
                </section>


                <section class="sales-order-info">
                    <div class="left">
                        <table style="border: 1px solid white;">
                            <tr style="border: 1px solid white;">
                                <th style="border: 1px solid white; ; font-size: 10px;">Requisition No</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;">{{$salesRequisition->invoice_id }}</th>
                            
                                <th style="border: 1px solid white; ; font-size: 10px;">Date</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;">{{$salesRequisition->invoice_date}}</th>
                            </tr>
                            <tr style="border: 1px solid white;">
                                <th style="border: 1px solid white; ; font-size: 10px;">Name</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;"> {{$salesRequisition->customer->company_name }}</th>
                            
                                <th style="border: 1px solid white; ; font-size: 10px;">Time</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;">demo data</th>
                            </tr>
                        
                            <tr style="border: 1px solid white;">
                                <th style="border: 1px solid white; ; font-size: 10px;">Address</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;">{{$salesRequisition->customer->address}}</th>
                            
                                <th style="border: 1px solid white; ; font-size: 10px;">Requisition By</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;">{{$salesRequisition->requisitionBy->name }}</th>
                            </tr>
                            <tr style="border: 1px solid white;">
                                <th style="border: 1px solid white; ; font-size: 10px;">Phone</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;">{{$salesRequisition->additional_phone}}</th>
                            
                                <th style="border: 1px solid white; ; font-size: 10px;">Print Date</th>
                                <td style="border: 1px solid white; ; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; ; font-size: 10px;">{{ now()->format('d-M-Y h:i A') }}</th>
                            </tr>
                        </table>
                    </div>
                </section>


                <section class="invoice-details">
                    <table>
                        <tr>
                            <th style="font-size: 11px;">SN</th>
                            <th style="font-size: 11px;">Product Description</th>
                            <th style="font-size: 11px;">Qty</th>
                            <th style="font-size: 11px;">Unit Price</th>
                            <th style="font-size: 11px;">Discount</th>
                            <th style="font-size: 11px;">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesRequisition->salesRequisitionDetails as $salesRequisitionsDetail)
                                {{-- @dd($salesRequisitionsDetail) --}}
                                <tr>
                                    <td style="font-size: 10px;">
                                    {{ $loop->index + 1 }}
                                    <td style="font-size: 10px;">
                                        {{ $salesRequisitionsDetail->product->name }}<br>
                                        {{ $salesRequisitionsDetail->product->model }}
                                    </td>
                                    <td style="font-size: 10px;">{{ numberFormat($salesRequisitionsDetail->quantity) }}
                                    </td>
                                    <td style="font-size: 10px;">{{ numberFormat($salesRequisitionsDetail->price) }}</td>
                                    <td style="font-size: 10px;">{{ numberFormat($salesRequisitionsDetail->unit_discount) }}</td>
                                    <td style="font-size: 10px;">{{ numberFormat($salesRequisitionsDetail->amount) }} </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <section>
                        <table style="margin-top:0!important;">
                            <tr>
                                <td width="60%" rowspan="4" style="margin-top:0!important; border: 1px solid white!important;">
                                    <p style="font-size: 10px;">IN WORD : {{ convert_number($salesRequisition->total_amount) }} Taka Only</p>
                                </td>
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important;font-size: 10px;">
                                    Total
                                </td>
                                <td style="border: 1px solid white!important; text-align: left; width: 2%; margin-top:0!important; font-size: 10px;">
                                    :
                                </td>
                                <td style="border: 1px solid white!important; text-align: right; margin-top:0!importantfont-size: 10px;">
                                    <strong>{{ number_format($salesRequisition->total_amount, 2, '.', '') }}</strong>
                                </td>
                            </tr>
                            <tr style="border: 1px solid white!important; margin-top:0!important;">
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important;font-size: 10px;">
                                    Discount
                                </td>
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important;font-size: 10px;">
                                    :
                                </td>
                                <td style="border: 1px solid white!important; text-align: right; margin-top:0!importantfont-size: 10px;">
                                    <strong>{{ number_format($salesRequisition->discount, 2, '.', '') }}</strong>
                                </td>
                            </tr>
                            <tr style="border: 1px solid white!important; margin-top:0!important;">
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important;font-size: 10px;">
                                    Percentage
                                </td>
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important;font-size: 10px;">
                                    :
                                </td>
                                <td style="border: 1px solid white!important; text-align: right; margin-top:0!importantfont-size: 10px;">
                                    <strong>{{ number_format($salesRequisition->percentage, 2, '.', '') }}</strong>
                                </td>
                            </tr>
                            <tr style="border: 1px solid white!important; margin-top:0!important;">
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important;font-size: 10px;">
                                    Net Amount
                                </td>
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important;font-size: 10px;">
                                    :
                                </td>
                                <td style="border: 1px solid white!important; text-align: right; margin-top:0!importantfont-size: 10px;">
                                    <strong>{{ number_format($salesRequisition->net_amount, 2, '.', '') }}</strong>
                                </td>
                            </tr>
                        </table>
                    </section>

                    

                </section>
                <p style="text-align: center;font-size: 13px;">Previous Due Adjust with Conditional amount = 100</p>
                    <div  style="margin-top: 50px; font-family: Arial">
                        <section>
                            <p>১. সুপ্রিয় গ্রাহক, লেন-দেনের সময় রশিদ বুঝিয়া নিবেন। রশিদ ছাড়া কোন রকম অভিযোগ গ্রহণযোগ্য হবে না।</p>
                            <p>২. প্রতিটি বিল পাওয়ার পর প্রিভিয়াস ডিউ চেক করবেন। কোন সমস্যা থাকলে বিল পাওয়ার সাথে সাথে ফোন করে সমাধান নিবেন।৫ দিন অতিবাহিত হলে কোন অভিযোগ গ্রহণযোগ্য হবে না। আমাদের একমাত্র বিকাশ নং ০১৮৫২২৭৮২০০, ৪০৪০০৩৫০১ (বিকাশ পেমেন্ট)।</p>
                            <p><strong>৩. খুচরা রিএজেন্টের রেজাল্টের মান নিয়ে সকল অভিযোগ অগ্রহনযোগ্য ও উক্ত রিএজেন্ট অফেরতযোগ্য।</strong></p>
                            <p>৪.যে কোন প্রয়োজনে যোগাযোগ করুন +০৯৬৭৮০২০৫৫৫ অথবা, ০১৪০৪০০৩৫০০ নম্বরে। যেকোন প্রোডাক্ট অর্ডার করতে কল করুন- ০১৪০৪০০৩৫০১ নম্বরে, সার্ভিসিং এর জন্য যোগাযোগ করুন- ০১৪০৪০০৩৫৩৫ নম্বরে।</p>
                            <p>৫. কুরিয়ারে বহনকালে প্রাকৃতিক দুর্যোগ, অগ্নিকান্ড, বা অনভিপ্রেত যেকোনো কারনে মালামালের ক্ষতি হইলে গ্লোবাল মেডিকেল ইঞ্জিনিয়ারিং (বিডি) লিঃ কোনো ভাবে দায়ী নয়।</p>
                            <p><strong>৬। কুরিয়ার থেকে দ্রুত পণ্য গ্রহণ করে সঠিক তাপমাত্রায় সংরক্ষণ করুন অন্যথায় রেজাল্টের তারতম্য হওয়ার সম্ভাবনা রয়েছে। তাপমাত্রা জনিত কারণে কোন অভিযোগ গ্রহণযোগ্য নয় ও এর দায়ভার একান্ত গ্রাহকের উপর বর্তায়।</strong></p>
                        </section>
                    </div>
                    <footer style="margin-top: 100px">
                        <p>Received ___________________________</p>
                        <p>Authorized ___________________________</p>
                    </footer>
            </div>
        </div>
    </div>
</div>