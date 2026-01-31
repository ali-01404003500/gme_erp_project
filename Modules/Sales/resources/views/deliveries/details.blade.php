@php
    $product = $deliveryDetails->first()?->productCatalog;
@endphp

@if($product)
    <h4 class="mb-3">
        Product Name: {{ $product->name }}, Model No: {{ $product->model }}
    </h4>
    <h5 class="mb-3">Stock Details {{ $product->is_serial_product ? ' (Serial Wise)' : ' (Lot Wise)' }}</h5>

    @if($product->is_serial_product)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Serial No</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryDetails as $detail)
                    @foreach ($detail->deliveryStocks as $stock)
                        <tr>
                            <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                            <td>{{ $stock->serial_no }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Lot No</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryDetails as $detail)
                    @foreach ($detail->deliveryStocks as $stock)
                        <tr>
                            <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                            <td>{{ $stock->lot_no }}</td>
                            <td>{{ $stock->quantity }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
@endif
