<table>
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 20px; text-align: center;">
            {{ $company_info->company_name ?? 'All Branch' }}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            {{ $department ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold; text-align: center;">Balance Sheet</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            From: {{ request('from') ?? date('Y-m-d') }} To: {{ request('to') ?? date('Y-m-d') }}
        </td>
    </tr>
</table>

@foreach($accountGroups->where('id', 1) as $accountGroup)
<table>
    <tr>
        <td colspan="2" style="font-weight: bold;">{{ $accountGroup->name }}</td>
    </tr>
    @php $totalBalance = 0; @endphp
    @foreach($accountGroup->accountControls as $control)
        @php
            $balance = $control->accounts->sum('debit_balance') - $control->accounts->sum('credit_balance');
            $totalBalance += $balance;
        @endphp
        <tr>
            <td style="font-weight: bold;">{{ $control->name }}</td>
            <td>{{ numberFormat($balance) }}</td>
        </tr>
        @foreach($control->accounts as $account)
        <tr>
            <td>  - {{ $account->name }}</td>
            <td>{{ numberFormat($account->debit_balance - $account->credit_balance) }}</td>
        </tr>
        @endforeach
    @endforeach
    <tr>
        <td><strong>Total {{ $accountGroup->name }}</strong></td>
        <td><strong>{{ numberFormat($totalBalance) }}</strong></td>
    </tr>
</table>
@endforeach

{{-- Owners Equity --}}
<table>
    <tr>
        <td><strong>Owners Equity</strong></td>
        <td><strong></strong></td>
    </tr>
    <tr>
        <td>Total Equity Balance</td>
        <td>{{ numberFormat($equity_balance) }}</td>
    </tr>
</table>

{{-- Liabilities --}}
@php $liabilityBalance = 0; @endphp
@foreach($accountGroups->whereIn('id', [2, 10]) as $accountGroup)
<table>
    <tr>
        <td colspan="2" style="font-weight: bold;">{{ $accountGroup->name }}</td>
    </tr>
    @foreach($accountGroup->accountControls as $control)
        @php
            $balance = $control->accounts->sum('credit_balance') - $control->accounts->sum('debit_balance');
            $liabilityBalance += $balance;
        @endphp
        <tr>
            <td style="font-weight: bold;">{{ $control->name == 'None' && $accountGroup->id == 10 ? 'Accumulated Depreciation' : $control->name }}</td>
            <td>{{ numberFormat($balance) }}</td>
        </tr>
        @foreach($control->accounts as $account)
        <tr>
            <td>  - {{ $account->name }}</td>
            <td>{{ numberFormat($account->credit_balance - $account->debit_balance) }}</td>
        </tr>
        @endforeach
    @endforeach
</table>
@endforeach

<table>
    <tr>
        <td><strong>Total Liabilities</strong></td>
        <td><strong>{{ numberFormat($liabilityBalance) }}</strong></td>
    </tr>
    <tr>
        <td><strong>Total Liabilities and Owners Equity</strong></td>
        <td><strong>{{ numberFormat($liabilityBalance + $equity_balance) }}</strong></td>
    </tr>
</table>
