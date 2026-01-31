<style>
    @import url('https://fonts.maateen.me/kalpurush/font.css');
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@100..900&display=swap');

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
        font-size: 20px;
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
                    @include('partials._for_pdf_header')
                </header>

                <section class="title">
                    <h2>Product Transfer Invoice</h2>
                </section>


                <section class="sales-order-info">
                    <div class="left">
                        <table style="border: 1px solid white;">
                            <tr style="border: 1px solid white;">
                                <th style="border: 1px solid white;">Request date</th>
                                <td style="border: 1px solid white;">:</td>
                                <th style="border: 1px solid white;">{{$productTransferRequest->request_date}}</th>

                                <th style="border: 1px solid white;">Source Branch</th>
                                <td style="border: 1px solid white;">:</td>
                                <th style="border: 1px solid white;">{{ $productTransferRequest->sourceBranch->name }}</th>
                            </tr>
                            <tr style="border: 1px solid white;">
                                <th style="border: 1px solid white;">Destination Branch</th>
                                <td style="border: 1px solid white;">:</td>
                                <th style="border: 1px solid white;"> {{ $productTransferRequest->destinationBranch->name }}</th>

                                <th style="border: 1px solid white;">Remarks</th>
                                <td style="border: 1px solid white;">:</td>
                                <th style="border: 1px solid white;"> {{ $productTransferRequest->remarks }}</th>
                            </tr>
                        </table>
                    </div>
                </section>


                <section class="invoice-details">
                    <table>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($productTransferRequest->productTransferRequestDetails as $productTransferRequestDetail)
                            <tr>
                                <td>{{ $productTransferRequestDetail->productCatalog->name }}</td>
                                <td>{{ $productTransferRequestDetail->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

                
                <footer>
                        <table style="margin: 0 auto; margin-bottom: 100px; border: 1px solid white;">
                            <tbody style="text-align: center;">
                                <tr>
                                    <td style="margin-bottom: 20px; border: 1px solid white;">Received ___________________________</td>
                                
                                    <td style="margin-bottom: 20px; border: 1px solid white;">Authorized ___________________________</td>
                                </tr>
                            </tbody>
                        </table>
                </footer>
            </div>
        </div>
    </div>
</div>
