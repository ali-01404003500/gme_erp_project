<style>
    @import url('https://fonts.maateen.me/kalpurush/font.css');
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@100..900&display=swap');

    body {
        font-family: Arial, sans-serif;
        /* margin: 20px; */
        font-size: 12px;
    }

    .my-header {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-bottom: 20px;
    }

    .my-header img {
        max-width: 100px;
        margin-right: 20px;
    }

    .my-header h1 {
        margin: 0;
        font-size: 25px;
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

    .received-items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .received-items-table,
    .received-items-table th,
    .received-items-table td {
        border: 1px solid #000;
    }

    .received-items-table th,
    .received-items-table td {
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }

    .received-items-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .total-section {
        margin-top: 20px;
        text-align: right;
    }

    .total-section table {
        width: 300px;
        margin-left: auto;
        border-collapse: collapse;
    }

    .total-section table,
    .total-section table th,
    .total-section table td {
        border: 1px solid #000;
    }

    .total-section th,
    .total-section td {
        padding: 8px;
        text-align: right;
    }

    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
    }

    footer p {
        margin: 10px 0;
        font-size: 14px;
        width: 45%;
        text-align: center;
    }
</style>

<div class="my-header">
    @include('partials._for_pdf_header_2nd')
</div>

<section class="title">
    <h2>Received Courier Details</h2>
</section>

<div class="table-responsive">
    <table class="received-items-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Invoice ID</th>
                <th>Courier</th>
                <th>Invoice Amount</th>
                <th>Payment Amount</th>
                <th>Discount</th>
                <th>Conditional Amount</th>
                <th>Receipt No</th>
                <th>Service Charge</th>
                <th>Delivery Charge</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                @php
                    $salesOrder = $item->salesOrder;
                    $shipmentVerify = $item->shipmentVerify;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $salesOrder->sales_order_id }}</td>
                    <td>{{ $item->courier->courier_name ?? '' }}</td>
                    <td>{{ number_format($item->invoice_amount, 2) }}</td>
                    <td>{{ number_format($salesOrder->net_amount ?? 0, 2) }}</td>
                    <td>{{ number_format($salesOrder->discount ?? 0, 2) }}</td>
                    <td>{{ number_format($item->condition_amount, 2) }}</td>
                    <td>{{ $shipmentVerify->receipt_no ?? '' }}</td>
                    <td>{{ number_format($shipmentVerify->service_charge ?? 0, 2) }}</td>
                    <td>{{ number_format($shipmentVerify->delivery_charge ?? 0, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No received items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>