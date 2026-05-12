<div class="row">
    <div class="col-md-12">
        Available Stocks : {{ $total_stock }}
    </div>
    <div class="col-md-12 table-responsive">
        @if ($product)
            @if ($product->is_serial_product)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">Sl</th>
                        <th>Product Name</th>
                        <th>Serial NO</th>
                        <th class="d-none">Dongle ID</th>
                        <th>Manufacturer Date</th>
                        {{-- <th>Stock</th> --}}
                        {{-- <th>available Stock</th> --}}
                        <th class="text-center" style="width: 20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <form id="stockForm">
                        {{-- @dd($stocks) --}}
                        @foreach ($stocks as $key => $stock)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>
                                    {{ $stock->product->name }}
                                    <input type="hidden" name="product_id[]" value="{{ $stock->product_id }}">
                                </td>
                                <td class="text-center">
                                    {{ $stock->serial_no }}<br>
                                    @if(optional($stock->source)->dongle_no)
                                        Dongle: {{ optional($stock->source)->dongle_no }}
                                    @endif
                                    <input type="hidden" name="serial_no[{{ $stock->product_id }}][]" class="serial_no" value="{{ $stock->serial_no }}">
                                </td>
                                <td class="d-none">{{ optional($stock->source)->dongle_no }}</td>
                                <td>{{ optional($stock->source)->manufacture_date }}</td> 
                                <td class="text-center">
                                    <div class="checkbox-theme-default custom-checkbox m-2">
                                        <input class="checkbox" name="stock_id" value="{{ $stock->product_id }}"
                                            type="checkbox" id="check_id{{ $key }}">
                                        <label for="check_id{{ $key }}">
                                            <span class="checkbox-text">
                                                Select
                                            </span>
                                        </label>
                                    </div> 
                                </td>
                            </tr>
                        @endforeach
                    </form>
                </tbody>
            </table>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">Sl</th>
                            <th>Product Name</th>
                            <th>Lots</th>
                            <th class="d-none">Batch No</th>
                            <th class="d-none">Manufacture No</th>
                            <th>Expiry Date</th>
                            <th>Available Stock</th>
                           
                            {{-- <th>available Stock</th> --}}
                            <th class="text-center" style="width: 30%">Delivary Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form id="stockForm">
                            {{-- @dd($stocks) --}}
                            @foreach ($stocks as $key => $stock)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        {{ $stock->product->name }}
                                        <input type="hidden" class="product_id" name="product_id[]" value="{{ $stock->product_id }}">
                                    </td>
                                    <td>{{ $stock->lot_no }}
                                        <input type="hidden" name="lot_no[{{ $stock->product_id }}][]" class="lot_no" value="{{ $stock->lot_no }}">
                                    </td>
                                    <td class="text-center d-none">{{ optional($stock->source)->batch_no }}</td>
                                    <td class="text-center d-none">{{ optional($stock->source)->manufacture_no }}</td>
                                    <td class="text-center"> {{ date('Y-m-d', strtotime(optional($stock->source)->expired_date)) }}</td>
                                    <td class="text-center">{{ $stock->stock }}
                                        <input type="hidden" name="available_stock" value="{{ $stock->stock }}">
                                    </td>
                                    {{-- <td class="text-center">{{ $stock->stock }}</td> --}}
                                    <td class="text-center">
                                        <input type="number" class="form-control" name="quantity"  onkeyup="$('#lots_quantity{{ $stock->product_id }}').val(this.value)">
                                        <input type="hidden" id="lots_quantity{{ $stock->product_id }}"  name="lots_quantity[{{ $stock->product_id }}][]"  >
                                    </td>
                                </tr>
                            @endforeach
                        </form>
                    </tbody>
                </table>
            @endif
        @endif
    </div>
</div>
