<div class="details">
    {{-- @dd($returnApproveDetail) --}}
    <h4 class="mb-3">Product Name: {{ $returnApproveDetail->product->name }}, Model No: {{ $returnApproveDetail->product->model }}</h4>
    <h5 class="mb-3">Stock Details {{$returnApproveDetail->product->isSerial}}</h5>
    @if($returnApproveDetail->product->is_serial_product)
    <table class="table table-bordered">
        <thead>
            
            <tr>
                <td>SL</td>
                <td>Serial No</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($returnApproveDetail->purchaseReturnApproveStocks as $stock)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{ $stock->serial_no }}</td>
                </tr>
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
            @foreach ($returnApproveDetail->purchaseReturnApproveStocks as $stock)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{ $stock->lot_no }}</td>
                    <td>{{ $stock->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>