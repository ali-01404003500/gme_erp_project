@section('title', 'Service Detail')
@section('description', 'Service Detail')
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
                                        {{ trans('menu.service-view-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }

                .invoice-container {
                    width: 80%;
                    margin: 20px auto;
                    padding: 100px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                }
                .header img {
                    max-width: 100px;
                    margin-right: 20px;
                    margin-top: -73px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 50px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .header p {
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

                .requisition-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .requisition-info .left,
                .requisition-info .right {
                    width: 70%;
                    /* Adjusted width */
                }

                .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
                }

                .requisition-info th,
                .requisition-info td {
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
            </style>
            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">{{ trans('menu.service-view-menu-title') }}</h3>
                    <div class="row">
                        <a href="{{ route('services.service.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm" style="margin-right: 5px;">
                        <i class="fa fa-list"></i> List</a>
                        <a href="{{ route('services.service.show', $service->id) }}?export=pdf" target="_blank"
                        class="btn btn-primary ml-auto btn-sm">PDF</a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <div class="header">
                                <img class="header-img" src="{{ $company_info->company_logo }}" alt="GME Logo">

                                <div>
                                    <h1>{{ $company_info->company_name }}</h1>
                                    <p>{{ $company_info->company_bio }}</p>
                                    <p>Address: {{ $company_info->company_address }}</p>
                                    <p>Hotline: +88 09678 020555  Mobile: {{ $company_info->company_phone }}</p>
                                    <p>e-mail: {{ $company_info->company_email }} web: {{ $company_info->website }}</p>
                                </div>
                            </div>

                            {{-- <div class="header">
                                <img src="{{$company_info->company_logo}}" alt="GME Logo">
                                <div>
                                    <h1 width="200px">{{$company_info->company_name}}</h1>
                                    <p width="50px">{{$company_info->company_bio}}</p>
                                </div>
                            </div> --}}

                            <section class="title mt-5">
                                <h2>Service Challan</h2>
                            </section>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Service No</th>
                                            <td>:</td>
                                            <th>{{ $service->service_unique_id }}</th>
                                        </tr>
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>:</td>
                                            <th>{{optional($service->serviceTokens[0] ?? null)->customer->company_name }}</th>
                                        </tr>
                                        <tr>
                                            <th>Customer Phone</th>
                                            <td>:</td>
                                            <th>{{optional($service->serviceTokens[0] ?? null)->customer->phone }}</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Date</th>
                                            <td>:</td>
                                            <th>{{optional($service->serviceTokens[0] ?? null)->token_date }}</th>
                                        </tr>
                                        <tr>
                                            <th>Customer Address</th>
                                            <td>:</td>
                                            <th>{{optional($service->serviceTokens[0] ?? null)->customer->address }}</th>                                        
                                        </tr>
                                        <tr>
                                            <th>Entry By</th>
                                            <td>:</td>
                                            <th>{{ $service->createdBy->name }}</th>                                        
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Product Description</th>
                                            <th>Serial No</th>
                                            <th>Qty.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach ($service->serviceTokens as  $key => $item) 
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                   {{ $item->product->name }} <br>
                                                </td>
                                                <td>
                                                   {{ $item->serial_number}}
                                                </td>
                                                <td>
                                                    {{ number_format((float) $item->quantity, 0) == 1.0000 ? '1' : number_format((float) $item->quantity, 0) }}
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tr>
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

                                <section class="requisition-info" style="display: flex; justify-content: space-between;">
                                    <div class="left" style="width: 70%;">
                                        <table>
                                            {{-- @dd($requisition->net_amount) --}}
                                            {{-- <p>IN WORD : {{convert_number($requisition->net_amount)}} Taka Only</p> --}}
                                        </table>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr style="border: none!important;">
                                                <td style="border: none!important;">Total Quantity</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ $service->serviceTokens->sum(function($item) {
                                                        return number_format((float) $item->quantity, 0) == 1.0000 ? 1 : number_format((float) $item->quantity, 0);
                                                    }) }}</strong></td>
                                            </tr>
                                            {{-- <tr style="border: none!important;">
                                                <td style="border: none!important;">Discount</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;"><strong>{{ $requisition->discount }}</strong>
                                                </td>
                                            </tr>
                                            <tr style="border: none!important;">
                                                <th style="border: none!important;">Grand Total</th>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;" >
                                                    <strong>{{ $requisition->net_amount }}</strong></td>
                                            </tr> --}}
                                        </table>
                                    </div>
                                </section>
                            </section>
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


                            <footer>
                                <p>Received ___________________________</p>
                                <p>Authorized ___________________________</p>
                            </footer>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@section('page_scripts')

    <script type="text/javascript">
        const row = $("#product_info_table tbody tr:first-child").clone();
        row.find('input').val('');
        row.find('tom-select option:selected').removeAttr('selected');
        row.find('#remove_row').removeClass('disabled');
        row.find('#remove_row').removeAttr('disabled');

        $("#add_row").click(function() {
            const newRow = row.clone();
            newRow.find('.tom-select').each(function() {
                new TomSelect(this, {})
            });
            $("#product_info_table tbody").append(newRow);

        });
        $("#product_info_table tbody").on("keyup", "#quantity", function() {
            calculateTotalPrice($(this).closest('tr'));
        });

        function removeRow(row) {
            $(row).closest('tr').remove();
            calculateTotalAmount();
            calculateNetAmount();

        }


        function calculateTotalPrice(row) {
            var qty = $(row).find("#quantity").val() ? $(row).find("#quantity").val() : 0;
            var price = $(row).find("#price").val() ? $(row).find("#price").val() : 0;

            var total = parseFloat(qty) * parseFloat(price);
            $(row).find("#amount").val(total);
            console.log(total);
        }

        // Initial calculation for existing rows
        $("#product_info_table tbody tr").each(function() {
            calculateTotalPrice($(this));
            calculateTotalAmount();
            calculateNetAmount();

        });
    </script>

    <script type="text/javascript">
        function calculateTotalAmount() {
            var totalAmount = 0;
            $("#product_info_table tbody tr").each(function() {
                var amount = parseFloat($(this).find("#amount").val()) || 0;
                totalAmount += amount;
            });
            $("#total_amount").val(totalAmount);
        }

        $(document).ready(function() {
            calculateTotalAmount();

            $("#product_info_table tbody").on("keyup", "#quantity", function() {
                calculateTotalPrice($(this).closest('tr'));
                calculateTotalAmount();
                calculateNetAmount();
            });
        });
    </script>
    <script type="text/javascript">
        function calculateNetAmount() {
            var totalAmount = parseFloat($("#total_amount").val()) || 0;
            var discount = parseFloat($("#discount").val()) || 0;
            var netAmount = totalAmount - discount;
            $("#net_amount").val(netAmount);
        }
        $(document).ready(function() {
            calculateNetAmount();

            $("#discount").on("keyup", function() {
                calculateNetAmount();
            });
            $("#product_info_table tbody").on("keyup", "#quantity", function() {
                calculateTotalPrice($(this).closest('tr'));
                calculateTotalAmount();
                calculateNetAmount();
            });
        });
    </script>

    



@endsection
