<table>
    <tr>
        <td colspan="4" style="font-weight: bold; font-size: 20px; text-align: center;">
            {{ $company_info->company_name ?? 'All Branch' }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center;">
            {{ $department ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="font-weight: bold; text-align: center;">Equity Statement</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center;">
            From: {{ request('from') ?? date('Y-m-d') }} To: {{ request('to') ?? date('Y-m-d') }}
        </td>
    </tr>
</table>

@php 
    $previous_year_share_capital = 0;
    $previous_year_retained_earnings = 0;

    $profit_loss_share_capital = 0;
    $profit_los_retained_earnings = $profit_and_loss;

    $addition_share_capital = 0;
    $addition_retained_earnings = $equity > 0 ? $equity : 0;

    $adjustment_share_capital = 0;
    $adjusement_retained_earnings = $equity < 0 ? $equity : 0;

    $closing_share_capital = $previous_year_share_capital + $profit_loss_share_capital + $addition_share_capital - $adjustment_share_capital;
    $closing_retained_earnings = $previous_year_retained_earnings + $profit_los_retained_earnings + $addition_retained_earnings - $adjusement_retained_earnings;
@endphp

<table border="1">
    <thead>
        <tr>
            <th>Particular</th>
            <th>Share Capital</th>
            <th>Retained Earnings</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Opening Balance</td>
            <td>{{ number_format($previous_year_share_capital) }}</td>
            <td>{{ number_format($previous_year_retained_earnings) }}</td>
            <td>{{ number_format($previous_year_share_capital + $previous_year_retained_earnings) }}</td>
        </tr>

        <tr>
            <td>Add: Profit/Loss during the year</td>
            <td>{{ number_format($profit_loss_share_capital) }}</td>
            <td>{{ number_format($profit_los_retained_earnings) }}</td>
            <td>{{ number_format($profit_loss_share_capital + $profit_los_retained_earnings) }}</td>
        </tr>

        <tr>
            <td>Add: Addition during the year</td>
            <td>{{ number_format($addition_share_capital) }}</td>
            <td>{{ number_format($addition_retained_earnings) }}</td>
            <td>{{ number_format($addition_share_capital + $addition_retained_earnings) }}</td>
        </tr>

        <tr>
            <td>Less: Adjustment during the year</td>
            <td>{{ number_format($adjustment_share_capital) }}</td>
            <td>{{ number_format($adjusement_retained_earnings) }}</td>
            <td>{{ number_format($adjustment_share_capital + $adjusement_retained_earnings) }}</td>
        </tr>

        <tr>
            <td><strong>Closing Balance</strong></td>
            <td><strong>{{ number_format($closing_share_capital) }}</strong></td>
            <td><strong>{{ number_format($closing_retained_earnings) }}</strong></td>
            <td><strong>{{ number_format($closing_share_capital + $closing_retained_earnings) }}</strong></td>
        </tr>

        <tr>
            <td>Previous Year Balance</td>
            <td>{{ number_format($previous_year_share_capital) }}</td>
            <td>{{ number_format($previous_year_retained_earnings) }}</td>
            <td>{{ number_format($previous_year_share_capital + $previous_year_retained_earnings) }}</td>
        </tr>
    </tbody>
</table>
