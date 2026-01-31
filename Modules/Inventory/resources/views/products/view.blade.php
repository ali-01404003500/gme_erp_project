<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $product->name }}</title>
</head>
<style>
    @page {
        header: page-header;
        footer: page-footer;
        sheet-size: A4;
        margin: 50px;
    }

    table,
    td,
    th {
        font-size: 11px;
        font-family: Arial, sans-serif;
    }

    table {
        border-top: none;
        border-left: none;
        border-right: none;
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        padding-left: 2px !important;
    }

    th.head {
        background-color: rgba(143, 175, 170, 0.35);
        height: 40px;
        font-size: 12px;
    }

    td.loop_td {
        height: 58px;
        font-size: 12px;
    }

    .text-center {
        text-align: center;
        color: rgb(101, 101, 101)
    }

    .heading-style th {
        background-color: rgb(240, 236, 236);
        padding: 7px 0;
        border: 1px solid rgb(240, 236, 236);
        color: rgb(101, 101, 101)
    }

    .heading-style2 th {
        color: rgb(101, 101, 101)
    }

    .body-style td {
        padding: 5px 4px;
        border: 1px solid rgb(240, 236, 236);
        color: rgb(101, 101, 101)
    }

    .basic-style th,
    .basic-style td {
        color: rgb(67, 67, 67)
    }
</style>

<body>
    <table class="table" style="font-size: 10px !important">
        <tr class="heading-style2">
            <th style="border: none; text-align: left;">
                <h3>{{ $product->name }}</h3>
                <h3>{{ $product->productType->name }}</h3>
                <p>{{ $product->description }}</p>
            </th>
            {{-- <td style="border: none; text-align: right;">
    @if (file_exists($product->photograph))
        <img src="{{ public_path($product->photograph) }}"
            style="width: 90px; height: 90px; display: block; border: 2px solid gray; padding: 5px;"
            alt="{{ $product->photograph }}">
    @elseif (file_exists('default-user.jpg'))
        <img src="{{ public_path('default-user.jpg') }}" style="width: 90px; height: 90px; display: block;"
            alt="{{ $product->photograph }}">
    @endif
</td> --}}

        </tr>

        <td colspan="2">
            <hr style="height: 2px; color: #a0a0a0; background: #a0a0a0; " />
        </td>

        <tr>
            <td>
                <table class="table basic-style" style="font-size: 10px !important">
                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                            Product Type</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $product->productType->name }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                            Product Catalog</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $product->name }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                            Name</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $product->name }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">Email
                            Model</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $product->productCatalog->model }}</td>
                    </tr>

                </table>
            </td>

            <td>
                <table class="table basic-style" style="font-size: 10px !important">

                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                            MRP</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $product->mrp }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">User
                            Product Tag</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $product->tag->name }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; border: none; font-weight: bold;" width="47%">
                            Remainder Quantity</td>
                        <td style="text-align: left" width="2%">:</td>
                        <td style="text-align: left; text-align: left" width="47%">
                            {{ $product->remainder_quantity }}</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

    <h4 class="text-center" style="margin-bottom: 8;">Product Pricing Details</h4>
    <table class="table" border="1">
        <thead class="heading-style">
            <tr>
                <th>MRP</th>
                <th>Landed Price</th>
                <th>Transportation Cost Per Unit</th>
                <th>VAT</th>
                <th>TAX</th>
                <th>Miscellaneous amount</th>
                <th>Total Price</th>
            </tr>
        </thead>

        <tbody class="body-style">
            <tr>
                <td>{{ $product->mrp }}</td>
                <td>{{ $product->landed_price }}</td>
                <td>{{ $product->transportation_cost }}</td>
                <td>{{ $product->vat }}</td>
                <td>{{ $product->tax }}</td>
                <td>{{ $product->misc }}</td>
                <td>{{ $product->total_price }}</td>
            <tr>
        </tbody>
    </table>


    <h4 class="text-center" style="margin-bottom: 8;">Settings for Sales</h4>
    <table class="table" border="1">
        <thead class="heading-style">
            <tr>
                <th>Maximum Sales Qty (Invoice Wise)</th>
                <th>Total Sales Qty:</th>
                <th>Applied Type:</th>
                <th>Inv No:</th>
                <th>Stock</th>
                <th>Rule Status</th>
                <th>Start Date</th>
                <th>Stop Date</th>
            </tr>
        </thead>

        <tbody class="body-style">
            <tr>
                <td>{{ $product->max_sales_qty }}</td>
                <td>{{ $product->total_sales_qty }}</td>
                <td>{{ $product->applied_type }}</td>
                <td>{{ $product->inv_no }}</td>
                <td>
                    @if ($product->stock == 'stock_out')
                        Stock Out
                    @else
                        Available
                    @endif
                </td>
                <td>{{ $product->rule_status }}</td>
                <td>{{ $product->start_date }}</td>
                <td>{{ $product->stop_date }}</td>
            </tr>
        </tbody>
    </table>


    <h4 class="text-center" style="margin-bottom: 8;">Settings for Purchase</h4>
    <table class="table" border="1">
        <thead class="heading-style">
            <tr>
                <th>Maximum Purchase Qty (Invoice Wise)</th>
                <th>Total Purchase Qty</th>
                <th>Last Purchase Price</th>
                <th>Stock Status</th>
                <th>Remarks</th>
            </tr>
        </thead>

        <tbody class="body-style">
                <tr>
                    <td>{{ $product->max_purchase_qty }}</td>
                    <td>{{ $product->total_purchase_qty }}</td>
                    <td>{{ $product->last_purchase_price }}</td>
                    <td>
                        @if ($product->stock_status == 0)
                            Stock Out
                        @else
                            Available
                        @endif
                    </td>
                    <td>{{ $product->remarks }}</td>
                </tr>
        </tbody>
    </table>

    <h4 class="text-center" style="margin-bottom: 8;">Settings for Sales Discount</h4>
    <table class="table" border="1">
        <thead class="heading-style">
            <tr>
                <th>Discount Type</th>
                <th>Product Tag</th>
            </tr>
        </thead>

        <tbody class="body-style">
                <tr>
                    <td>
                        @if ($product->discount_type == 'N/A')
                            N/A
                        @elseif($product->discount_type == 'Percentage')
                            Percentage
                        @else
                            Fixed
                        @endif
                    </td>
                    <td>
                        {{-- @foreach ($tags as $productTag)
                            @if ($productTag->id == $product->product_tag_id)
                                {{ $productTag->name }}
                            @endif
                        @endforeach --}}
                    </td>
                    
                </tr>
        </tbody>
    </table>
</body>