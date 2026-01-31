<table>
    <tr>
        <td colspan="3" style="font-weight: bold; font-size: 20px; text-align: center;">
            {{ $company_info->company_name ?? 'All Branch' }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">
            {{ $department ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-weight: bold; text-align: center;">Cash Flow</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">
            From: {{ request('from') ?? date('Y-m-d') }} To: {{ request('to') ?? date('Y-m-d') }}
        </td>
    </tr>
</table>

<table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%;">
    <tbody>
        <tr>
            <td>Sl.</td>
            <td><strong>Particular</strong></td>
            <td width="150px" class="text-center pr-1">Tk.</td>
        </tr>
        <tr>
            <td>1</td>
            <td><strong>Cash flows from operating activities:</strong></td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
        <tr>
            <td></td>
            <td>Net Profit/Loss</td>
            <td width="150px" class="text-right pr-1">{{ number_format($equity_balance, 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Adjustment to reconcile net profit to net cash:</td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
        <tr>
            <td></td>
            <td>Depreciation Expense</td>
            <td width="150px" class="text-right pr-1">{{ number_format($depreciations, 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Current Asset Increase/Decrease</td>
            <td width="150px" class="text-right pr-1">
                {{ $asset[0] >= 0 ? '(' . number_format($asset[0], 0) . ')' : number_format($asset[0], 0) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Current Liabilities Increase/Decrease</td>
            <td width="150px" class="text-right pr-1">
                {{ $liabilities[0] >= 0 ? number_format($liabilities[0], 0) : '(' . number_format(abs($liabilities[0]), 0) . ')' }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Net cash provided/used by Operating Activities</td>
            @php
                $operating_activities = $equity_balance + $depreciations - $asset[0] + $liabilities[0];
            @endphp
            <td width="150px" class="text-right pr-1">
                <strong>{{ $operating_activities >= 0 ? number_format($operating_activities, 0) : '(' . number_format(abs($operating_activities), 0) . ')' }}</strong>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td><strong>Cash flows from investing activities:</strong></td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
        <tr>
            <td></td>
            <td>Fixed Assets Increase/Decrease</td>
            <td width="150px" class="text-right pr-1">
                {{ $asset[1] >= 0 ? '(' . number_format($asset[1], 0) . ')' : number_format($asset[1], 0) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Net cash provided/used by Investing Activities</td>
            <td width="150px" class="text-right pr-1">
                <strong>{{ $asset[1] >= 0 ? '(' . number_format($asset[1], 0) . ')' : number_format($asset[1], 0) }}</strong>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td><strong>Cash flows from financing activities:</strong></td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
        <tr>
            <td></td>
            <td>Long-term Liabilities Increase/Decrease</td>
            <td width="150px" class="text-right pr-1">
                {{ $liabilities[1] >= 0 ? number_format($liabilities[1], 0) : '(' . number_format(abs($liabilities[1]), 0) . ')' }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Net cash provided/used by Financing Activities</td>
            <td width="150px" class="text-right pr-1">
                <strong>{{ $liabilities[1] >= 0 ? number_format($liabilities[1], 0) : '(' . number_format(abs($liabilities[1]), 0) . ')' }}</strong>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                Net Cash Change <br>
                Add Opening Balance <br>
                <strong>Closing Balance</strong>
            </td>
            @php
                $net_cash_change = $operating_activities + $asset[1] + $liabilities[1];
                $opening_balance = 0; // Example opening balance, replace with actual data
                $closing_balance = $net_cash_change + $opening_balance;
            @endphp
            <td width="150px" class="text-right pr-1">
                {{ number_format($net_cash_change, 0) }} <br>
                {{ number_format($opening_balance, 0) }} <br>
                <strong>{{ number_format($closing_balance, 0) }}</strong>
            </td>
        </tr>
    </tbody>
</table>