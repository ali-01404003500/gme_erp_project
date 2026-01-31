{{-- @dd($stocks) --}}
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Sl</th>
            <th>Date</th>
            <th>Source Type</th>
            <th>In Quantity</th>
            <th>Out Quantity</th>
            <th>Remaining Quantity</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_in_qty = 0;
            $total_out_qty = 0;
        @endphp
        @foreach ($stocks as $key => $ledger)
            @php
                $total_in_qty += $ledger->sum('in_qty');
                $total_out_qty += $ledger->sum('out_qty');
                $remaining_qty = $total_in_qty - $total_out_qty;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ledger->first()->created_at->format('d-m-Y') }}</td>
                <td>{{ $ledger->first()->source_name }}</td>
                <td>{{ $ledger->sum('in_qty') }}</td>
                <td>{{ $ledger->sum('out_qty') }}</td>
                <td>{{ $remaining_qty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</table>

