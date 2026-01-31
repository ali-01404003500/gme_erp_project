@section('title', 'Purchase Requisition Detail')
@section('description', 'Purchase Requisition Detail')
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
                                        {{ trans('menu.requisition-view-menu-title') }}</li>
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

                /* footer p {
                        margin: 10px 0;
                        font-size: 14px;
                        width: 45%;
                        text-align: center;
                    } */
            </style>
            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">{{ trans('menu.requisition-view-menu-title') }}</h3>
                    <div class="row">
                        <a href="{{ route('purchase.requisitions.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"
                            style="margin-right: 5px;">
                            <i class="fa fa-list"></i> List</a>
                        <a href="{{ route('purchase.requisitions.show', $requisition->id) }}?export=pdf" target="_blank"
                            class="btn btn-primary ml-auto btn-sm">PDF</a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <div class="header">
                                <img class="header-img" src="{{ $company_info->company_logo }}" alt="GME Logo">

                                <div>
                                    <table class="content-table" style="border: none;">
                                        <tr>

                                            <td class="com-info" style="border: none; padding-top: 20px;">
                                                <div class="com">
                                                    <div style="margin-right: 160px;line-height: 0.8;">
                                                        <h1
                                                            style="color: rgb(13, 13, 92); font-size: 21px!important;margin-left: 45px;">
                                                            {{ $company_info->company_name }}</h1>
                                                        <p style="font-size: 8px!important; color:black; text-align:center">
                                                            {{ $company_info->company_bio }}</p>
                                                        <p style="font-size: 8px!important; color:black; text-align:center">
                                                            {{ $company_info->company_address }}</p>
                                                        <p style="font-size: 8px!important; color:black; text-align:center">
                                                            Hotline : {{ $company_info->company_phone }}</p>
                                                        <p style="font-size: 8px!important; color:black; text-align:center">
                                                            e-mail : {{ $company_info->company_email }} web:
                                                            {{ $company_info->website }}</p>
                                                    </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- <div class="header">
                                <img src="{{$company_info->company_logo}}" alt="GME Logo">
                                <div>
                                    <h1 width="200px">{{$company_info->company_name}}</h1>
                                    <p width="50px">{{$company_info->company_bio}}</p>
                                </div>
                            </div> --}}

                            <section class="title">
                                <h2>Purchase Requisition Invoice Bill</h2>
                            </section>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th style="width:150px;">Requisition No</th>
                                            <td>:</td>
                                            <th>{{ $requisition->requisition_no }}</th>
                                        </tr>
                                        <tr>
                                            <th style="width:150px;">Name</th>
                                            <td>:</td>
                                            <th>{{ @$requisition->supplier->company_name }}</th>
                                        </tr>
                                        <tr>
                                            <th style="width:150px;">Address</th>
                                            <td>:</td>
                                            <th>{{ @$requisition->supplier->address }}</th>
                                        </tr>
                                        <tr>
                                            <th style="width:150px;">Phone</th>
                                            <td>:</td>
                                            <th>{{ @$requisition->supplier->phone }}</th>
                                        </tr>
                                       

                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Date</th>
                                            <td>:</td>
                                            <td>{{ $requisition->created_at->format('d F, Y') ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Time</th>
                                            <td>:</td>
                                            <td>{{ $requisition->created_at->format('h:i A') ?? '' }}</td>
                                        </tr>
                                      
                                       
                                        <tr>
                                            <th>Requisition By</th>
                                            <td>:</td>
                                            <td>{{ $requisition->createdBy->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Product Name</th>
                                            <th>Product Description</th>
                                            <th>Price</th>
                                            <th>Sales Price</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach ($requisition->requisitionDetails as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->product->name }} <br>
                                                {{ $item->product->model }}-{{ $item->product->brand->name }}

                                            </td>
                                            <td>
                                                {{ $requisition->description }}
                                            </td>
                                            <td>
                                                {{ $item->price }}
                                            </td>
                                            <td>
                                                {{ $item->sales_price }}
                                            </td>
                                            <td>
                                                {{ $item->quantity }}
                                            </td>
                                            <td>
                                                {{ $item->amount }}
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
                                            <p>IN WORD : {{ convert_number($requisition->net_amount) }} Taka Only</p>
                                        </table>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr style="border: none!important;">
                                                <td style="border: none!important;">Total</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ $requisition->total_amount }}</strong>
                                                </td>
                                            </tr>
                                            <tr style="border: none!important;">
                                                <td style="border: none!important;">Discount</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ $requisition->discount }}</strong>
                                                </td>
                                            </tr>
                                            <tr style="border: none!important;">
                                                <th style="border: none!important;">Grand Total</th>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ $requisition->net_amount }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </section>
                                <div class="row">
                                    <p><strong>Description :</strong> {{ $requisition->description }}</p>
                                       
                                </div>

                            </section>

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
