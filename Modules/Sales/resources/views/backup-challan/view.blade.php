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

    footer {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    footer p {
        margin: 10px 0;
        font-size: 14px;
        width: 45%;
        text-align: center;
    }
</style>

<div class="row" style="font-size: 12px!important;">
    <div class="col-md-12 m-2">
        <x-error-alart />
    </div>
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">

                <header class="my-header">
                    @include('partials._for_pdf_header_2nd')
                </header>

                <section class="title">
                    <h2>{{ $backupChallan->type }} Invoice</h2>
                </section>


                <section class="sales-order-info">
                    <div class="left">
                        <table style="border: 1px solid white;">
                            <tr style="border: 1px solid white;">
                                <th style="border: 1px solid white; font-size: 10px;">Invoice No</th>
                                <td style="border: 1px solid white; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; font-size: 10px;"> {{ $backupChallan->invoice_no }}</th>

                                <th style="border: 1px solid white; font-size: 10px;">Invoice Date</th>
                                <td style="border: 1px solid white; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; font-size: 10px;"> {{ $backupChallan->invoice_date }}</th>
                            </tr>
                            <tr style="border: 1px solid white; font-size: 10px;">

                                <th style="border: 1px solid white; font-size: 10px;">Customer Name</th>
                                <td style="border: 1px solid white; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; font-size: 10px;">{{ $backupChallan->customer->company_name }}</th>

                                <th style="border: 1px solid white; font-size: 10px;">Remaining Date</th>
                                <td style="border: 1px solid white; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; font-size: 10px;">{{ $backupChallan->remaining_date }}</th>
                            </tr>
                            <tr>
                                
                                <th style="border: 1px solid white; font-size: 10px;">Type</th>
                                <td style="border: 1px solid white; font-size: 10px;">:</td>
                                <th style="border: 1px solid white; font-size: 10px;"> {{ $backupChallan->type }}</th>

                            </tr>
                        </table>
                    </div>
                </section>


                <section class="invoice-details">
                    <table>
                        <tr>
                            <th style="font-size: 11px;">Product Name</th>
                            <th style="font-size: 11px;">Quantity</th>
                            <th style="font-size: 11px;">Price</th>
                            <th style="font-size: 11px;">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($backupChallan->backupChallanDetails as $key => $backupChallanDetail)
                                {{-- @dd($backupChallanDetail) --}}
                                <tr>
                                    <td style="font-size: 10px;">
                                        {{ $backupChallanDetail->product->name }}
                                        {{-- <select name="product_ids[]"
                                            class="form-control product_ids to-select">
                                            <option value="">Choose Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" @if ($backupChallanDetail->product_id == $product->id) selected @endif>
                                                    {{ $product->name }}</option>
                                            @endforeach

                                        </select>
                                        <input type="hidden" name="sales_order_detail_id[]" value="{{ $backupChallanDetail->id }}"> --}}

                                    </td>
                                    <td style="font-size: 10px;">
                                        {{ numberFormat($backupChallanDetail->quantity) }}
                                    </td>
                                    <td style="font-size: 10px;">{{ numberFormat($backupChallanDetail->price) }}
                                    </td>
                                    <td style="font-size: 10px;">{{ numberFormat($backupChallanDetail->amount) }} </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- <p><strong>IN WORD : Taka Twenty Eight Lac Only</strong></p>
                                    <table>
                                        <div class="totals">
                                            <p>Total : <strong>2,800,000.00</strong></p>
                                            <p>Discount : <strong>0.00</strong></p>
                                            <p><strong>Grand Total : 2,800,000.00</strong></p>
                                        </div>
                                    </table> -->


                    <section>
                        <table style="margin-top:0!important border: 1px solid white!important;">
                            <tr style="border: 1px solid white!important;">
                                <td width="60%" rowspan="5" style="margin-top:0!important; border: 1px solid white!important;">
                                    <p style="margin-top:0!important; font-size: 12px; font-size: 10px;">IN WORD : {{ convert_number($backupChallan->total_amount) }}</p>
                                </td>
                                <td style="border: 1px solid white!important; text-align: left; margin-top:0!important; font-size: 10px;">
                                    Total Amount
                                </td>
                                <td style="border: 1px solid white!important; text-align: left; width: 2%; margin-top:0!important; font-size: 10px;">
                                    :
                                </td>
                                <td style="border: 1px solid white!important; text-align: right; margin-top:0!important; font-size: 10px;">
                                    <strong>{{ number_format($backupChallan->total_amount, 2, '.', '') }}</strong>
                                </td>
                            </tr>
                        </table>
                    </section>

                    <section>
                        <p style="font-size: 10px;"><strong>Remarks: </strong>{{ $backupChallan->remarks }}</p>
                    </section>

                </section>
                <section class="payment-info" style="width: 60%; display: flex; justify-content: flex-start;">
                    <h2 style="margin-top: 20px;">Shipment Details</h2>
                    @foreach ($backupChallan->backupChallanShipments as $key => $backupChallanShipment)
                    <table style="border: 1px solid black!important; padding: 5px;">
                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                            <th
                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                Courier</th>
                            <td
                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                :</td>
                            <td style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                <strong>{{ @$backupChallanShipment->courier->courier_name }}</strong>
                            </td>
                        </tr>
                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                            <th
                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                Area</th>
                            <td
                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                :</td>
                            <td style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                <strong>{{ @$backupChallanShipment->area->area }}</strong>
                            </td>
                        </tr>
                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                            <th
                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                Address</th>
                            <td
                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                :</td>
                            <td style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                <strong>{{ $backupChallanShipment->address }}</strong>
                            </td>
                        </tr>
                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                            <th
                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                Contact Person Name</th>
                            <td
                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                :</td>
                            <td style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                <strong>{{ $backupChallanShipment->contact_person_name }}</strong>
                            </td>
                        </tr>
                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                            <th
                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                Contact Person Number</th>
                            <td
                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                :</td>
                            <td style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                <strong>{{ $backupChallanShipment->contact_person_number }}</strong>
                            </td>
                        </tr>
                    </table>
                    @endforeach
                </section>

                <div style="margin-top: 50px;  font-family: 'Kalpurush', Arial, sans-serif !important;">
                    <section>
                        <p>১. সুপ্রিয় গ্রাহক, লেন-দেনের সময় রশিদ বুঝিয়া নিবেন। রশিদ ছাড়া কোন রকম অভিযোগ গ্রহণযোগ্য হবে
                            না।</p>
                        <p>২. প্রতিটি বিল পাওয়ার পর প্রিভিয়াস ডিউ চেক করবেন। কোন সমস্যা থাকলে বিল পাওয়ার সাথে সাথে ফোন
                            করে সমাধান নিবেন।৫ দিন অতিবাহিত হলে কোন অভিযোগ গ্রহণযোগ্য হবে না। আমাদের একমাত্র বিকাশ নং
                            ০১৮৫২২৭৮২০০, ৪০৪০০৩৫০১ (বিকাশ পেমেন্ট)।</p>
                        <p><strong>৩. খুচরা রিএজেন্টের রেজাল্টের মান নিয়ে সকল অভিযোগ অগ্রহনযোগ্য ও উক্ত রিএজেন্ট
                                অফেরতযোগ্য।</strong></p>
                        <p>৪.যে কোন প্রয়োজনে যোগাযোগ করুন +০৯৬৭৮০২০৫৫৫ অথবা, ০১৪০৪০০৩৫০০ নম্বরে। যেকোন প্রোডাক্ট অর্ডার
                            করতে কল করুন- ০১৪০৪০০৩৫০১ নম্বরে, সার্ভিসিং এর জন্য যোগাযোগ করুন- ০১৪০৪০০৩৫৩৫ নম্বরে।</p>
                        <p>৫. কুরিয়ারে বহনকালে প্রাকৃতিক দুর্যোগ, অগ্নিকান্ড, বা অনভিপ্রেত যেকোনো কারনে মালামালের ক্ষতি
                            হইলে গ্লোবাল মেডিকেল ইঞ্জিনিয়ারিং (বিডি) লিঃ কোনো ভাবে দায়ী নয়।</p>
                        <p><strong>৬। কুরিয়ার থেকে দ্রুত পণ্য গ্রহণ করে সঠিক তাপমাত্রায় সংরক্ষণ করুন অন্যথায় রেজাল্টের
                                তারতম্য হওয়ার সম্ভাবনা রয়েছে। তাপমাত্রা জনিত কারণে কোন অভিযোগ গ্রহণযোগ্য নয় ও এর দায়ভার
                                একান্ত গ্রাহকের উপর বর্তায়।</strong></p>
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
