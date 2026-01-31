@section('title', 'Fake Invoice')
@section('description', 'Fake Invoice for ' . $fakeInvoice->customer->company_name)
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Fake Invoice Details') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <button onclick="printWholePage()" class="btn btn-primary btn-sm"
                                style="margin-right: 5px;">PDF</button>
                            {{-- <a href="{{ route('sales.fake-invoices.show', $fakeInvoice->id) }}?export=pdf" target="_blank"
                                class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a> --}}
                            @if (hasPermission('sales.fake-invoices.index'))
                                <a href="{{ route('sales.fake-invoices.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif



                        </div>
                    </div>
                </div>
            </div>
            <style>
                .my-header img {
                    max-width: 130px;
                }

                .my-header h1 {
                    margin: 0;
                    font-size: 35px;
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
                    font-size: 20px;
                    text-decoration: underline;
                }

                .sales-order-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .sales-order-info .left,
                .sales-order-info .right {
                    width: 70%;
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

               

                .bangla-text {
                    margin-top: 50px;
                }
            </style>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Fake Invoice Details') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12 mb-4 d-flex flex-wrap justify-content-start">
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
                                        <label class="custom-control-label" for="customCheck1">Title</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2" checked>
                                        <label class="custom-control-label" for="customCheck2">Payment Status</label>
                                    </div>

                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3">
                                        <label class="custom-control-label" for="customCheck3">VAT Status</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck4">
                                        <label class="custom-control-label" for="customCheck4">AIT Status</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck5">
                                        <label class="custom-control-label" for="customCheck5">CW</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck6" checked>
                                        <label class="custom-control-label" for="customCheck6">Remarks</label>
                                    </div>
                                  
                                    <div class="custom-control custom-checkbox mr-3" style="margin-right: 25px;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck8" checked>
                                        <label class="custom-control-label" for="customCheck8">Discount</label>
                                    </div>
                                   
                                </div>
                            </div>
                            <div id="print-content">
                                <header class="my-header">
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <div style="width: 10%;">
                                            <img class="header-img" src="{{ $company_info->company_logo }}" alt="GME Logo">
                                        </div>
                                        <div style="width: 80%">
                                            <div style="text-align: center;">
                                                <h1>{{ $company_info->company_name }}</h1>
                                                <p>{{ $company_info->company_bio }}</p>
                                                <p>Address : 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000</p>
                                                <p>Hotline : +88 09678 020555 Mobile : +8801404003500</p>
                                                <p>e-mail : <a href="mailto:info@gmebd.com">info@gmebd.com</a> web: <a
                                                        href="http://www.gmebd.com">www.gmebd.com</a></p>
                                            </div>
                                        </div>
                                        <div style="width: 10%"></div>
                                    </div>
                                </header>

                                <section class="title">
                                    <h2>Sales Invoice Bill</h2>
                                </section>


                                <section class="sales-order-info">
                                    <div class="left">
                                        <table>
                                            <tr>
                                                <th>Invoice No</th>
                                                <td>:</td>
                                                <th>{{ $fakeInvoice->invoice_number }}</th>
                                            </tr>
                                            <tr>
                                                <th>Customer name</th>
                                                <td>:</td>
                                                <th>{{ $fakeInvoice->customer->company_name }}</th>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td>:</td>
                                                <td>{{ $fakeInvoice->customer->address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td>:</td>
                                                <td> {{ $fakeInvoice->customer->phone }}</td>
                                            </tr>
                                          
                                        </table>
                                    </div>
                                    <div class="right">
                                        <table>

                                            <tr>
                                                <th>Date</th>
                                                <td>:</td>
                                                <th>{{ $fakeInvoice->invoice_date }}</th>
                                            </tr>
                                            <tr>
                                                <th>Time</th>
                                                <td>:</td>
                                                <th>{{ date('h:i A', strtotime($fakeInvoice->created_at)) }}</th>
                                            </tr>
                                            <tr>
                                                <th>Sold By</th>
                                                <td>:</td>
                                                <th>{{ $fakeInvoice->createdBy->name }}</th>
                                            </tr>
                                            <tr>
                                                <th>Print Date</th>
                                                <td>:</td>
                                                <th>{{ now()->format('d-M-Y') }}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </section>


                                <section class="invoice-details">
                                    <table>
                                        <tr>
                                            <th style="width: 15px;">SL</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th id="discount">Discount</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fakeInvoice->details as $detail)
                                                <tr>
                                                    <td style="width: 15px;">{{ $loop->iteration }}</td>
                                                    <td>{{ $detail->product->name }}</td>
                                                    <td>{{ numberFormat($detail->quantity) }}</td>
                                                    <td>{{ numberFormat($detail->price) }}</td>
                                                    <td id="discount1">{{ numberFormat($detail->total_discount) }}</td>
                                                    <td>{{ numberFormat($detail->amount) }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <section class="requisition-info"
                                        style="display: flex; justify-content: space-between;">
                                        <div class="left" style="width: 70%;">
                                            <table>
                                                <p>IN WORD : {{ convert_number($fakeInvoice->total_amount) }} Taka Only</p>
                                            </table>
                                        </div>
                                        <div class="right" style="width: 30%;">
                                            <table style="border: none!important;">
                                                <tr style="border: none!important;">
                                                    <td style="border: none!important;">Total Amount</td>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;">
                                                        <strong>{{ $fakeInvoice->total_amount }}</strong>
                                                    </td>
                                                </tr>
                                                <tr style="border: none!important;">
                                                    <td style="border: none!important;">Discount</td>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;">
                                                        <strong>{{ $fakeInvoice->discount }}</strong>
                                                    </td>
                                                </tr>

                                                <tr id="vat-and-net-amount" style="border: none!important;">
                                                    <th style="border: none!important;">VAT(5)%</th>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;">
                                                        <strong>{{ $fakeInvoice->vat }}</strong>
                                                    </td>
                                                </tr>
                                                <tr id="vat-and-net-amount" style="border: none!important;">
                                                    <th style="border: none!important;">Net Amount</th>
                                                    <td style="border: none!important;">:</td>
                                                    <td style="border: none!important; text-align: end;" id="net-amount">
                                                        <strong>{{ $fakeInvoice->net_amount }}</strong>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </section>
                                
                                    <section>
                                        <div id="remarks">
                                            <p style="font-size: 18px; font-weight: bold;">Remarks</p>
                                            <p style="text-align: justify;">{{ $fakeInvoice->remarks }}</p>
                                        </div>
                                    </section>
                                
                                        
                                </section>
                                <section class="payment-info"
                                    style="width: 100%; display: flex; ">
                                    <table style="border: 1px solid black!important; padding: 5px; width: 30%;">
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                                            <th
                                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px; text-align: left;">
                                                Previous Due</th>
                                            <td
                                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                                :</td>
                                            <td
                                                style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                                <strong>0.00</strong>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                                            <th
                                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px; text-align: left;">
                                                Sales Returns</th>
                                            <td
                                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                                :</td>
                                            <td
                                                style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                                <strong>{{$fakeInvoice->total_amount }}</strong>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                                            <th
                                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px; text-align: left;">
                                                Paid</th>
                                            <td
                                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                                :</td>
                                            <td
                                                style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                                <strong>{{$fakeInvoice->net_amount }}</strong>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black!important; width: 100%; padding: 5px;">
                                            <th
                                                style="width: 50%; border: 1px solid black!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px; text-align: left;">
                                                Total Due</th>
                                            <td
                                                style="width: 50%; border-left: 1px solid rgb(255, 255, 255)!important; border-right: 1px solid rgb(255, 255, 255)!important; padding: 5px;">
                                                :</td>
                                            <td
                                                style="width: 50%; border: 1px solid black!important; text-align: end; padding: 5px;">
                                                <strong>0.00 </strong>
                                            </td>
                                        </tr>
                                    </table>
                                </section>

                                 <div style="font-family: Arial; font-size: 10px; margin-top: 10px;" class="bangla-text">
                                    <section>
                                        <p style="margin-bottom: 0rem !important;">১. সুপ্রিয় গ্রাহক, লেন-দেনের সময় রশিদ বুঝিয়া নিবেন। রশিদ ছাড়া কোন রকম অভিযোগ
                                            গ্রহণযোগ্য হবে না।</p>
                                        <p style="margin-bottom: 0rem !important;">২. প্রতিটি বিল পাওয়ার পর প্রিভিয়াস ডিউ চেক করবেন। কোন সমস্যা থাকলে বিল পাওয়ার
                                            সাথে সাথে ফোন করে সমাধান নিবেন।৫ দিন অতিবাহিত হলে কোন অভিযোগ গ্রহণযোগ্য হবে না।
                                            আমাদের একমাত্র বিকাশ নং ০১৮৫২২৭৮২০০, ৪০৪০০৩৫০১ (বিকাশ পেমেন্ট)।</p>
                                        <p style="margin-bottom: 0rem !important;"><strong>৩. খুচরা রিএজেন্টের রেজাল্টের মান নিয়ে সকল অভিযোগ অগ্রহনযোগ্য ও উক্ত
                                                রিএজেন্ট অফেরতযোগ্য।</strong></p>
                                        <p style="margin-bottom: 0rem !important;">৪.যে কোন প্রয়োজনে যোগাযোগ করুন +০৯৬৭৮০২০৫৫৫ অথবা, ০১৪০৪০০৩৫০০ নম্বরে। যেকোন
                                            প্রোডাক্ট অর্ডার করতে কল করুন- ০১৪০৪০০৩৫০১ নম্বরে, সার্ভিসিং এর জন্য যোগাযোগ
                                            করুন- ০১৪০৪০০৩৫৩৫ নম্বরে।</p>
                                        <p style="margin-bottom: 0rem !important;">৫. কুরিয়ারে বহনকালে প্রাকৃতিক দুর্যোগ, অগ্নিকান্ড, বা অনভিপ্রেত যেকোনো কারনে
                                            মালামালের ক্ষতি হইলে গ্লোবাল মেডিকেল ইঞ্জিনিয়ারিং (বিডি) লিঃ কোনো ভাবে দায়ী নয়।
                                        </p>
                                        <p style="margin-bottom: 0rem !important;"><strong>৬। কুরিয়ার থেকে দ্রুত পণ্য গ্রহণ করে সঠিক তাপমাত্রায় সংরক্ষণ করুন অন্যথায়
                                                রেজাল্টের তারতম্য হওয়ার সম্ভাবনা রয়েছে। তাপমাত্রা জনিত কারণে কোন অভিযোগ
                                                গ্রহণযোগ্য নয় ও এর দায়ভার একান্ত গ্রাহকের উপর বর্তায়।</strong></p>
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
            </div>
        @endsection

        @section('page_scripts')
            <script>
                $('#customCheck6').change(function() {
                    if ($(this).is(':checked')) {
                        $('#remarks').show();
                    } else {
                        $('#remarks').hide();
                    }
                });
                if (!$('#customCheck6').is(':checked')) {
                    $('#remarks').hide();
                }
            </script>

            <script>
                $('#customCheck8').change(function() {
                    if ($(this).is(':checked')) {
                        $('#discount').show();
                        $('#discount1').show();
                    } else {
                        $('#discount').hide();
                        $('#discount1').hide();
                    }
                });
                if (!$('#customCheck8').is(':checked')) {
                    $('#discount').hide();
                    $('#discount1').hide();
                }

            </script>
            <script>
                $(document).ready(function() {
                    $('.bangla-text').hide();
                    
                    $('#customCheck5').change(function() {
                        if ($(this).is(':checked')) {
                            $('.bangla-text').show();
                        } else {
                            $('.bangla-text').hide();
                        }
                        
                    });
                })
            </script>
            <script>
                $('#customCheck3').change(function() {
                    if ($(this).is(':checked')) {
                        $('#vat-and-net-amount').show();
                    } else {
                        $('#vat-and-net-amount').hide();
                    }
                });
            </script>
            <script>
                $(document).ready(function() {
                    if ($('#customCheck3').is(':checked')) {
                        $('#vat-and-net-amount').show();
                    } else {
                        $('#vat-and-net-amount').hide();
                    }
                });
            </script>
            <script>
                $('#customCheck3').change(function() {
                    if ($(this).is(':checked')) {
                        $('#total-amount').hide();
                        $('#net-amount').show();
                    } else {
                        $('#total-amount').show();
                        $('#net-amount').hide();
                    }
                });
            </script>
            <script>
                $(document).ready(function() {
                    $('#customCheck2').click(function() {
                        if ($(this).is(':checked')) {
                            $('.payment-info').show();
                        } else {
                            $('.payment-info').hide();
                        }
                    });
                });
            </script>
            <script>
                $(document).ready(function() {
                    $('#customCheck1').click(function() {
                        if ($(this).is(':checked')) {
                            $('.my-header').show();
                            $('.title').show();
                        } else {
                            $('.my-header').hide();
                            $('.title').hide();
                        }
                    });
                });
            </script>
            <script>
                function printWholePage() {
                    var printContents = document.getElementById('print-content').innerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;

                    // add style by scripting
                    var style = document.createElement('style');
                    style.textContent = `
                    .my-header img {
                        max-width: 80px;
                    }

                    .my-header h1 {
                        margin: 0;
                        font-size: 23px;
                        font-weight: bold;
                        color: rgb(0, 0, 187);
                    }

                    .my-header p {
                        margin: 5px 0;
                        font-size: 9px;
                    }

                    .title {
                        margin-top: 15px;
                        text-align: center;
                        margin-bottom: 5px;
                    }

                    .title h2 {
                        margin: 0;
                        font-size: 16px;
                        text-decoration: underline;
                    }

                    .sales-order-info {
                        justify-content: space-between;
                        display: flex;
                        margin-bottom: 20px;
                        font-size: 9px;
                    }

                    .sales-order-info .left,
                    .sales-order-info .right {
                        width: 50%;
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
                        padding: 1px;
                        text-align: left;
                        font-size: 12px;
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
                        padding: 4px;
                        text-align: left;
                        font-size: 12px;
                    }

                    .invoice-details p {
                        margin: 5px 0;
                        font-size: 14px;
                    }

                    .invoice-details .totals {
                        text-align: right;
                    }

                    .invoice-details .totals p {
                        
                        font-size: 12px;
                    }

                    footer {
                        display: flex;
                        justify-content: space-between;
                        margin-top: 20px;
                    }

                    footer p {
                        margin: 6px 0;
                        font-size: 12px;
                        width: 45%;
                        text-align: center;
                    }
                    .payment-info{
                        font-size: 12px;
                    }
                    .bangla-text{
                        margin-top: 10px;
                        font-size: 10px;
                    }
                    
                `;
                    document.head.appendChild(style);

                    window.print();

                    document.body.innerHTML = originalContents;
                }
            </script>
            <script>
                $(document).ready(function() {
                    $('#approve').click(function() {
                        $("#status").val("approved");
                        return true;
                    });
                });
            </script>
            
            <script>
                $(document).ready(function() {
                    const customerSelect = $('#customer_id');
                    const shipmentConfirmCheckbox = $('#shipmentConfirm');
                    const courierConfirmCheckbox = $('#courierConfirm');
                    const conditionCheckbox = $('#condition');

                    const shipmentFields = [
                        $('#area_id'),
                        $('#address'),
                        $('#contact_person_name'),
                        $('#contact_person_phone')
                    ];

                    const courierFields = [
                        $('#courier_id'),
                        $('#condition')
                    ];

                    const conditionFields = [
                        $('#additional_amount'),
                        $('#condition_remarks')
                    ];

                    function toggleFields(fields, enable) {
                        fields.forEach(field => {
                            if (enable) {
                                field.removeAttr('disabled');
                                if (field.prop('tomselect')) {
                                    field.prop('tomselect').enable();
                                }
                            } else {
                                field.attr('disabled', true);
                                if (field.prop('tomselect')) {
                                    field.prop('tomselect').disable();
                                }
                            }

                        });
                    }

                    function handleCustomerSelection() {
                        const customerSelected = customerSelect.val() !== "";
                        shipmentConfirmCheckbox.prop('disabled', !customerSelected);
                        courierConfirmCheckbox.prop('disabled', !customerSelected);

                        if (!customerSelected) {
                            shipmentConfirmCheckbox.prop('checked', false);
                            courierConfirmCheckbox.prop('checked', false);
                            conditionCheckbox.prop('checked', false);
                            toggleFields(shipmentFields, false);
                            toggleFields(courierFields, false);
                            toggleFields(conditionFields, false);
                        }
                    }

                    function handleShipmentConfirm() {
                        toggleFields(shipmentFields, shipmentConfirmCheckbox.is(':checked'));
                    }

                    function handleCourierConfirm() {
                        toggleFields(courierFields, courierConfirmCheckbox.is(':checked'));
                        if (!courierConfirmCheckbox.is(':checked')) {
                            conditionCheckbox.prop('checked', false);
                            toggleFields(conditionFields, false);
                        }
                    }

                    function handleCondition() {
                        toggleFields(conditionFields, conditionCheckbox.is(':checked'));
                    }

                    customerSelect.on('change', handleCustomerSelection);
                    shipmentConfirmCheckbox.on('change', handleShipmentConfirm);
                    courierConfirmCheckbox.on('change', handleCourierConfirm);
                    conditionCheckbox.on('change', handleCondition);

                    // Initial state
                    handleCustomerSelection();
                    handleShipmentConfirm();
                    handleCourierConfirm();
                    handleCondition();
                });
            </script>




            <script>
                $(document).ready(function() {
                    $('#customer_id').change(getCustomerSettings);

                    // Add event listener for TomSelect change event
                    $('#area_id')[0].tomselect.on('change', function(value) {
                        if (value === 'address') {
                            clearFields();
                        }
                    });
                });

                function getCustomerSettings() {
                    var id = $("#customer_id option:selected").val();
                    if (id) {
                        $.ajax({
                            url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                            success: function(data) {
                                console.log(data);

                                if (data && data.customers && data.customers.customer) {
                                    var area = data.customers.customer.area;
                                    var area_id = area ? area.id : "address";
                                    var area_name = area ? area.area : "New Address";

                                    // Update the area_id select element with the new option
                                    $("#area_id").html(`<option value="${area_id}">${area_name}</option>`);
                                    $("#area_id")[0].tomselect.clear();
                                    $("#area_id")[0].tomselect.addOption({
                                        value: area_id,
                                        text: area_name
                                    });
                                    $("#area_id")[0].tomselect.setValue(area_id);

                                    // Update the fields if the area is not "New Address"
                                    if (area_id !== 'address') {
                                        $("#address").val(area_name);
                                        $("#contact_person_name").val(data.customers.customer.company_name);
                                        $("#contact_person_phone").val(data.customers.customer.phone);
                                    } else {
                                        clearFields();
                                    }

                                    if (data.customers.vat_status == 1) {
                                        $('#vat_percentage').val(.05);
                                    } else {
                                        $('#vat_percentage').val(0);
                                    }
                                }
                            }
                        });
                    }
                }

                function clearFields() {
                    $("#address").val("");
                    $("#contact_person_name").val("");
                    $("#contact_person_phone").val("");
                }
            </script>



            <script type="text/javascript">
                $(document).ready(function() {
                    const rowTemplate = $("#product_info_table tbody tr:first-child").clone();
                    rowTemplate.find('input').val('');
                    rowTemplate.find('.to-select option:selected').removeAttr('selected');
                    rowTemplate.find('#remove_row').removeClass('disabled').removeAttr('disabled');


                    $("#product_info_table tbody tr:first-child").find('.to-select').each(function() {
                        new TomSelect(this, {});
                    });


                    function calculateRow(row) {
                        const qty = parseFloat(row.find("#quantity").val()) || 0;
                        const price = parseFloat(row.find("#price").val()) || 0;
                        const unitDiscount = parseFloat(row.find("#unit_discount").val()) || 0;

                        const amount = qty * price;
                        const totalDiscount = qty * unitDiscount;

                        row.find("#amount").val(amount);
                        row.find("#total_discount").val(totalDiscount);

                        return {
                            amount,
                            totalDiscount
                        };
                    }

                    function calculateTotals() {
                        let totalAmount = 0;
                        let totalDiscount = 0;
                        let totalVat = 0;
                        let vat = $('#vat_percentage').val();

                        $("#product_info_table tbody tr").each(function() {
                            const {
                                amount,
                                totalDiscount: rowDiscount
                            } = calculateRow($(this));
                            totalAmount += amount;
                            totalDiscount += rowDiscount;
                        });

                        $("#total_amount").val(totalAmount);
                        $("#discount").val(totalDiscount);
                        $("#total").val(totalAmount - totalDiscount);
                        $("#vat").val((totalAmount - totalDiscount) * vat);
                        $("#net_amount").val(totalAmount - totalDiscount + (totalAmount - totalDiscount) * vat);
                    }

                    $("#add_row").click(function() {
                        const newRow = rowTemplate.clone();
                        newRow.find('.to-select').each(function() {
                            new TomSelect(this, {});
                        });
                        $("#product_info_table tbody").append(newRow);
                    });

                    $("#product_info_table tbody").on("keyup change", "#quantity, #price, #unit_discount", function() {
                        calculateTotals();
                    });

                    $("#product_info_table").on("click", "#remove_row", function() {
                        $(this).closest('tr').remove();
                        calculateTotals();
                    });

                    // Initial calculation for existing rows
                    // calculateTotals();


                    $(document).on('change', '.product_ids', async function() {
                        await getProductPrice(this);
                        const customer_id = $('#customer_id').val();
                        const product_id = $(this).val();
                        console.log({
                            customer_id,
                            product_id
                        });
                        await getSalesDiscount(customer_id, product_id, this);
                    })

                    $(document).on('keyup', '.discount_range', function() {
                        const discount_range = $(this).data('discount_range');
                        const discount = $(this).val();
                        if (discount < Number(discount_range.min) || discount > Number(discount_range.max)) {
                            $(this).addClass('is-invalid');
                        } else {
                            $(this).removeClass('is-invalid');
                        }

                    })
                });
            </script>

            <script>
                var selectedProductIds = []; // Array to store selected product IDs

                async function getProductPrice(selectElement) {
                    var productId = selectElement.value;
                    var priceInput = selectElement.closest('tr').querySelector('input[name="price[]"]');
                    if (productId.trim() !== '') {
                        if (selectedProductIds.includes(productId)) {
                            // Same product selected again
                            showToast('warning', 'You have already selected this product.');
                            return;
                        }
                        try {
                            const response = await $.get('{{ route('purchase.get.product.list') }}?id=' + productId);
                            //  $.ajax({
                            //     url: '{{ route('purchase.get.product.list') }}',
                            //     method: 'GET',
                            //     data: {
                            //         id: productId
                            //     }
                            // });
                            var product = response[0];
                            if (!product) {
                                // Product not found
                                showToast('error', 'Price not found.');
                                priceInput.value = '';
                                salespriceInput.value = '';
                                return;
                            }
                            priceInput.value = product.mrp;
                            selectedProductIds.push(productId); // Add the selected product ID to the array
                        } catch (error) {
                            console.error(error);
                            // Show error message
                            showToast('error', 'An error occurred while fetching product details.');
                        }
                    } else {
                        // Clear inputs if no product is selected
                        priceInput.value = '';
                        salespriceInput.value = '';
                    }
                }

                function showToast(type, message) {
                    // Display toast message
                    if (type === 'warning') {
                        toastr.warning(message);
                    } else if (type === 'error') {
                        toastr.error(message);
                    }
                }


                async function getSalesDiscount(customerId, productId, element = null) {
                    try {
                        const discounts = await $.get(
                            `{{ route('sales.get-sales-discount') }}?customer_id=${customerId}&product_id=${productId}`);
                        console.log({
                            discount: discounts.discount
                        });
                        $(element).closest('tr').find("#unit_discount").val(0);
                        $(element).closest('tr').find("#unit_discount").removeClass('discount_range');
                        $(element).closest('tr').find("#unit_discount").data('discount_range', null);
                        $(element).closest('tr').find("#unit_discount").removeAttr('readonly');
                        if (discounts.discount) {
                            if (discounts.discount.percentage) {
                                console.log(discounts.discount.percentage);
                                if (discounts.discount.percentage.percentage > 0) {
                                    if (element) {
                                        const percentage = discounts.discount.percentage.percentage;
                                        const price = $(element).closest('tr').find("#price").val();
                                        console.log(element);
                                        $(element).closest('tr').find("#unit_discount").val((percentage * price) / 100);
                                        $(element).closest('tr').find("#unit_discount").attr('readonly', 'readonly');
                                    }
                                }
                            } else if (discounts.discount.productPrice) {
                                console.log(discounts.discount.productPrice);
                                const discountPrice = discounts.discount.productPrice.sales_amounts;
                                const price = $(element).closest('tr').find("#price").val();
                                if (discountPrice < price) {
                                    $(element).closest('tr').find("#unit_discount").val(price - discountPrice);
                                    $(element).closest('tr').find("#unit_discount").attr('readonly', 'readonly');
                                }
                            } else if (discounts.discount.discountRange) {
                                $(element).closest('tr').find("#unit_discount").data('discount_range', discounts.discount
                                    .discountRange);
                                $(element).closest('tr').find("#unit_discount").val(0);
                                $(element).closest('tr').find("#unit_discount").addClass('discount_range');
                            }
                        }
                    } catch (error) {
                        console.error(error);
                        // Show error message
                        showToast('error', 'An error occurred while fetching sales discount.');
                    }
                }
            </script>


        @endsection
