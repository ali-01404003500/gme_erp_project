<table class="table">


    <!-- table header -->
    <thead>
        <tr style="color: black !important; font-weight: bolder; font-size: 15px">
            <th style="width: 10%">SL</th>
            <th style="width: 20%">Account</th>
            <th style="width:20%">Description</th>
            <th style="width: 25%">Dr.</th>
            <th style="width: 25%">Cr.</th>
        </tr>
    </thead>


    <!-- body -->
    <tbody>

        @php
            $totalDebit = 0;
            $totalCredit = 0;
            $sl = 1;
        @endphp


        @forelse ($transactions->groupBy('invoice_no') as $items)

            @foreach ($items as $item)
                @php
                    $totalDebit += $item->debit_amount;
                    
                    $totalCredit += $item->credit_amount;
                @endphp

                <tr>
                    <td style="width: 10%">
                        @if ($loop->first)
                            {{ $sl }}
                        @endif
                    </td>
                    <td style="width: 20%">{{ optional($item->account)->name }}</td>
                    <td style="width: 20%">{{ $item->getDescription() }}</td>
                    <td style="width: 25%">
                        <strong>{{ number_format($item->debit_amount ?? 0) }}</strong>
                    </td>
                    <td style="width: 25%">
                        <strong>{{ number_format($item->credit_amount ?? 0) }}</strong>
                    </td>
                </tr>
            @endforeach

            @php
                $sl++;
            @endphp

        @empty
            
        @endforelse
    </tbody>




    <!-- table footer -->
    @if (count($transactions) > 0)
        <tfoot>
            <tr>
                <th></th>
                <th class="text"></th>
                <th class="text-right h4"><strong style="font-size: 16px">Total</strong></th>
                <th class="text-right h4"><strong
                        style="font-size: 16px">{{ number_format($totalDebit) }}</strong></th>
                <th class="text-right h4"><strong
                        style="font-size: 16px">{{ number_format($totalCredit) }}</strong>
                </th>
            </tr>
        </tfoot>
    @endif
</table>