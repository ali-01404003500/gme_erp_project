<table class="table" style="width:100%; border: 1px solid #ccc;">
    <thead>
        <tr>
            <td colspan="6" style="font-family: 'Arial Black'; text-align: center; font-size: 36px">
                {{ $company_info->company_name ?? 'All Branch' }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="font-family: 'Arial Black'; text-align: center; font-size: 28px">
                {{ $department ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="font-family: 'Arial Black'; text-align: center; font-size: 24px">Trial Balance</td>
        </tr>
        <tr>
            <td colspan="6" style="font-family: 'Arial Black'; text-align: center; font-size: 14px">
                Period: {{ $from ?? 'N/A' }} to {{ $to ?? 'N/A' }}
            </td>
        </tr>
        <tr style="font-size: 12px; border-bottom: 1px solid #ccc;">
            <th class="text-center" style="background-color: #4CAF50; color: white;">Group</th>
            <th class="text-center" style="background-color: #4CAF50; color: white;">Control</th>
            <th class="text-center" style="background-color: #4CAF50; color: white;">Subsidiary</th>
            <th class="text-center" style="background-color: #4CAF50; color: white;">Account</th>
            <th class="text-center" style="background-color: #4CAF50; color: white;">Debit</th>
            <th class="text-center" style="background-color: #4CAF50; color: white;">Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalTrialAmountDebit = 0;
            $totalTrialAmountCredit = 0;
            $showZero = $show_zero ?? false;
        @endphp

        @foreach ($accountGroups as $accountGroup)
            @php
                $debitAccountGroup = $accountGroup->accountControls->sum(fn($control) =>
                    $control->accountSubsidiaries->sum(fn($item) =>
                        $item->accounts->sum('debit')
                    )
                );
                $creditAccountGroup = $accountGroup->accountControls->sum(fn($control) =>
                    $control->accountSubsidiaries->sum(fn($item) =>
                        $item->accounts->sum('credit')
                    )
                );
            @endphp

            @if ($showZero || $debitAccountGroup != 0 || $creditAccountGroup != 0)
                @php
                    $totalTrialAmountDebit += $debitAccountGroup;
                    $totalTrialAmountCredit += $creditAccountGroup;
                @endphp

                <!-- Group Row -->
                <tr style="background-color: #e8f5e9;">
                    <td style="font-weight: bold;">{{ $accountGroup->name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-end" style="font-weight: bold;">{{ $debitAccountGroup }}</td>
                    <td class="text-end" style="font-weight: bold;">{{ $creditAccountGroup }}</td>
                </tr>

                <!-- Control Level -->
                @foreach ($accountGroup->accountControls as $accountControl)
                    @php
                        $debitAccountControl = $accountControl->accountSubsidiaries->sum(fn($item) => $item->accounts->sum('debit'));
                        $creditAccountControl = $accountControl->accountSubsidiaries->sum(fn($item) => $item->accounts->sum('credit'));
                    @endphp

                    @if ($showZero || $debitAccountControl != 0 || $creditAccountControl != 0)
                        <tr style="background-color: #f1f8e9;">
                            <td></td>
                            <td style="font-weight: bold;">{{ $accountControl->name }}</td>
                            <td></td>
                            <td></td>
                            <td class="text-end" style="font-weight: bold;">{{ $debitAccountControl }}</td>
                            <td class="text-end" style="font-weight: bold;">{{ $creditAccountControl }}</td>
                        </tr>

                        <!-- Subsidiary Level -->
                        @foreach ($accountControl->accountSubsidiaries as $accountSubsidiary)
                            @php
                                $debitSubsidiary = $accountSubsidiary->accounts->sum('debit');
                                $creditSubsidiary = $accountSubsidiary->accounts->sum('credit');
                            @endphp

                            @if ($showZero || $debitSubsidiary != 0 || $creditSubsidiary != 0)
                                <tr style="background-color: #f9fbe7;">
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight: bold;">{{ ucfirst($accountSubsidiary->name) }}</td>
                                    <td></td>
                                    <td class="text-end" style="font-weight: bold;">{{ $debitSubsidiary }}</td>
                                    <td class="text-end" style="font-weight: bold;">{{ $creditSubsidiary }}</td>
                                </tr>

                                <!-- Accounts Level -->
                                @foreach ($accountSubsidiary->accounts as $account)
                                    @if ($showZero || $account->debit != 0 || $account->credit != 0)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $account->name }}</td>
                                            <td class="text-end">{{ $account->debit }}</td>
                                            <td class="text-end">{{ $account->credit }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($accountGroups->count() == 0)
            <tr>
                <td colspan="6" class="text-center text-danger" style="font-size: 16px;">NO RECORDS FOUND!</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr style="font-size: 15px; font-weight: bold; border-top: 2px solid #000; background-color: #c8e6c9;">
            <td colspan="4" class="text-start" style="font-size: 18px; color: #1ba74d; font-weight: bold;">Total</td>
            <td class="text-end" style="font-size: 18px; color: #1ba74d; font-weight: bold;">{{ $totalTrialAmountDebit }}</td>
            <td class="text-end" style="font-size: 18px; color: #1ba74d; font-weight: bold;">{{ $totalTrialAmountCredit }}</td>
        </tr>
    </tfoot>
</table>