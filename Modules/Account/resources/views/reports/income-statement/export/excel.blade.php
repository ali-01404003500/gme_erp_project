<table class="table table-sm table-bordered detail-table">
    <thead>
        <tr style="border: none;">
            <th class="text-center pb-0" style="font-size: 28px; border: none;" colspan="3">Income Statement</th>
        </tr>
        <tr style="border: none;">
            <th class="text-center" style="font-size: 18px; border: none;" colspan="3">As on {{ request()->input('date_range') ?? date('Y-m') }}</th>
        </tr>
        <tr>
            <th>Particular</th>
            <th>{{ $search }}</th>
        </tr>
    </thead>

    <tbody>
        <!-- Sales Section -->
        <tr>
            <td><strong style="font-size: 15px">Sales</strong></td>
            <td>
                {{ numberFormat($sale_amount = $revenues->accountControls->sum(function($accountControl) {
                    return $accountControl->accounts->sum('balance');
                }), 0) }}
            </td>
        </tr>

        <!-- Sales Account Details -->
        <tr style="border:none !important" class="detail-rows">
            <td colspan="3">
                <div class="col-sm-8 col-sm-offset-1" style="width: 90% !important">
                    <table class="table table-borderless" style="border:none !important; margin-bottom: 0;">
                        @foreach ($revenues->accountControls as $accountControl)
                            @foreach ($accountControl->accounts as $account)
                                <tr style="border:none !important">
                                    <td style="border:none !important; padding-left: 20px;">{{ $account->name }}</td>
                                    <td style="border:none !important">
                                        {{ numberFormat($account->balance) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>

        <!-- Cost of Goods Sold Section -->
        <tr>
            <td>Less: Cost of Goods Sold</td>
            <td>
                {{ numberFormat($less_amount = $purchases->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance'), 0) }}
            </td>
        </tr>

        <!-- COGS Account Details -->
        <tr style="border:none !important" class="detail-rows">
            <td colspan="3">
                <div class="col-sm-8 col-sm-offset-1" style="width: 90% !important">
                    <table class="table table-borderless" style="border:none !important; margin-bottom: 0;">
                        @foreach ($purchases->accountControls as $accountControl)
                            @foreach ($accountControl->accountSubsidiaries as $accountSubsidiary)
                                @foreach ($accountSubsidiary->accounts as $account)
                                    <tr style="border:none !important">
                                        <td style="border:none !important; padding-left: 20px;">{{ $account->name }}</td>
                                        <td style="border:none !important">
                                            {{ numberFormat($account->balance) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>

        <!-- Gross Profit -->
        <tr>
            <td class="text-right">
                <strong>Gross Profit:</strong>
            </td>
            <td>
                <strong>{{ numberFormat($gross_profit = $sale_amount - $less_amount, 0) }}</strong>
            </td>
        </tr>

        <!-- Operating Expenses Section -->
        <tr>
            <td>
                <strong>Operating Expenses:</strong>
            </td>
            <td></td>
        </tr>

        <!-- Administrative Expenses -->
        <tr>
            <td>Administrative Expenses</td>
            <td>
                {{ numberFormat($expense = $expenses->accountControls->sum(function($accountControl) {
                    return $accountControl->accounts->sum('balance');
                }), 0) }}
            </td>
        </tr>

        <!-- Expense Account Details -->
        <tr style="border:none !important" class="detail-rows">
            <td colspan="3">
                <div class="col-sm-8 col-sm-offset-1" style="width: 90% !important">
                    <table class="table table-borderless" style="border:none !important; margin-bottom: 0;">
                        @foreach ($expenses->accountControls as $accountControl)
                            @foreach ($accountControl->accounts as $account)
                                <tr style="border:none !important">
                                    <td style="border:none !important; padding-left: 20px;">{{ $account->name }}</td>
                                    <td style="border:none !important">
                                        {{ numberFormat($account->balance) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>

        <!-- Operating Profit -->
        <tr>
            <td class="text-right">
                <strong>Operating Profit:</strong>
            </td>
            <td>
                <strong>{{ numberFormat($operative_profit = $gross_profit - $expense, 0) }}</strong>
            </td>
        </tr>

        <!-- Earnings Before Interest & Tax -->
        <tr>
            <td class="text-right">
                <strong>Earnings Before Interest and Tax:</strong>
            </td>
            <td>
                <strong>{{ numberFormat($earning_interest_and_tax = $operative_profit , 0) }}</strong>
            </td>
        </tr>

        <!-- Financial Expenses -->
        <tr>
            <td>Less: Financial Expenses</td>
            <td>{{ $financial_expendex = 0 }}</td>
        </tr>

        <!-- Earnings Before Tax -->
        <tr>
            <td class="text-right">
                <strong>Earnings Before Tax:</strong>
            </td>
            <td>
                <strong>{{ numberFormat($earning_before_tax = $financial_expendex, 0) }}</strong>
            </td>
        </tr>

        <!-- Provision for Income Tax -->
        @php
            $income_tax = $tax->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance');
        @endphp
        <tr>
            <td>Less: Provision for Income Tax</td>
            <td>{{ numberFormat($income_tax) }}</td>
        </tr>

        <!-- Net Profit/(Loss) after Tax -->
        <tr>
            <td>
                <strong>Net Profit/(Loss) after Tax:</strong>
            </td>
            <td>
                <strong>{{ numberFormat($net_profit_and_loss = $earning_before_tax - $income_tax, 0) }}</strong>
            </td>
        </tr>
    </tbody>
</table>