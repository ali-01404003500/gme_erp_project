
<table class="table table-sm table-bordered detail-table">
    <thead>
        <tr style="border: none;">
            <th class="text-center pb-0" style="font-size: 28px; border: none;" colspan="3" >Income Statement</th>
        </tr>
        <tr style="border: none;">
            <th class="text-center " style="font-size: 18px; border: none;" colspan="3" > As on {{ request()->input('date_range')??date('Y-m') }}</th>
        </tr>
        <tr>
            <th colspan="2">Particular</th>
            <th class="text-end">{{ $search }}</th>
        </tr>
    </thead>
    

    <tbody>
        @php 
            $sale_amount = $revenues->accountControls->sum(function($accountControl) {
                return $accountControl->accounts->sum('balance');
            })
        @endphp
        <tr>
            <td colspan="2"><strong style="font-size: 15px">Sales</strong></td>
            <td class="text-end">
                {{ number_format($sale_amount, 0) }}
            </td>
        </tr>


        @php
            $less_amount = $purchases->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance');
        @endphp
        <tr>
            <td colspan="2">Less: Cost of Goods Sold</td>
            <td class="text-end">
                {{ number_format($less_amount, 0) }}</td>
        </tr>


        <tr>
            <td colspan="2" class="text-right">
                <strong>Gross Profit:</strong>
            </td>
            <td class="text-end">
                <strong>{{ number_format($gross_profit = $sale_amount - $less_amount, 0) }}</strong>
            </td>
        </tr>



        <tr>
            <td colspan="2">&nbsp;</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Operating Expenses:</strong>
            </td>
            <td></td>
        </tr>



        @php 
            // dd($expenses);
            $expense = $expenses->accountControls->sum(function($accountControl) {
                return $accountControl->accounts->sum('balance');
            })
        @endphp
        <tr>
            <td colspan="2">
                Administrative Expenses
            </td>
            <td class="text-end">
                {{ number_format($expense, 0) }}
            </td>
        </tr>



        @php 
            // $depreciation = $depreciations->accountControls->sum(function($accountControl) {
            //     return $accountControl->accounts->sum('balance');
            // })
            $depreciation = 0;
        @endphp
        <tr>
            <td colspan="2">
                Depreciation
            </td>
            <td class="text-end">
                {{ number_format($depreciation, 0) }}
            </td>
        </tr>



        <tr>
            <td colspan="2" class="text-right">
                <strong>Operating Profit:</strong>
            </td>
            <td class="text-end">
                <strong>{{ number_format($operative_profit = $gross_profit - $expense /* - $depreciation */, 0) }}</strong>
            </td>
        </tr>


        {{-- @php
            $none_operating_income = $equity->accountControls->sum(function($accountControl) {
                return $accountControl->accounts->sum('balance');
            });
        @endphp
        <tr>
            <td colspan="2">
                Add: Non-operating Income
            </td>
            <td class="text-end">{{ number_format($none_operating_income, 0) }}</td>
        </tr> --}}



        <tr>
            <td colspan="2" class="text-right">
                <strong>Earnings Before Interest & Tax:</strong>
            </td>
            <td class="text-end">
                <strong>{{ number_format($earning_interest_and_tax = $operative_profit , 0) }}</strong>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                Less: Financial Expenses
            </td>
            <td class="text-end">{{ $financial_expendex = 0 }}</td>
        </tr>



        <tr>
            <td colspan="2" class="text-right">
                <strong>Earnings Before Tax:</strong>
            </td>
            <td class="text-end">
                <strong>{{ number_format($earning_before_tax = $earning_interest_and_tax + $financial_expendex, 0) }}</strong>
            </td>
        </tr>
        @php
        // dd();

            $income_tax = $tax->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance');
        @endphp
        <tr>
            <td colspan="2">
                Less: Provision for Income Tax
            </td>
            <td class="text-end">{{ number_format($income_tax) }}</td>
        </tr>



        <tr>
            <td colspan="2">
                <strong>Net Profit/(Loss) after Tax:</strong>
            </td>
            <td class="text-end">
                <strong>{{ number_format($net_profit_and_loss = $earning_before_tax - $income_tax, 0) }}</strong>
            </td>
        </tr>
    </tbody>
</table>

