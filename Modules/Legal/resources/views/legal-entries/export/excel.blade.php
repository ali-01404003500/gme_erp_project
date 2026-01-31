<table class="table dt-table-hover" style="width:100%">
    <thead>
        <tr>
            <td colspan="{{ $tab == 'case' ? '5' : '8' }}" style="font-family: 'Arial Black'; text-align: center; font-size: 36px">
                {{ $company_info->company_name ?? 'All Branch' }}
            </td>
        </tr>
        <tr>
            <td colspan="{{ $tab == 'case' ? '5' : '8' }}" style="font-family: 'Arial Black'; text-align: center; font-size: 24px">
                {{ $tab == 'case' ? 'Case Report' : 'Notice Report' }}
            </td>
        </tr>
        {{-- @if($from || $to)
            <tr>
                <td colspan="{{ $tab == 'case' ? '5' : '8' }}" style="font-family: 'Arial Black'; text-align: center">
                    Date Range: {{ $from ?? 'Start' }} to {{ $to ?? 'End' }}
                </td>
            </tr>
        @endif --}}
        <tr></tr>
        
        @if($tab == 'case')
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Case Info</th>
                <th class="text-center">Advocate Info</th>
                <th class="text-center">Legal Status</th>
                <th class="text-center">Last Hajira Remarks</th>
            </tr>
        @else
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Customer Name</th>
                <th class="text-center">Convict Name</th>
                <th class="text-center">Phone</th>
                <th class="text-center">Address</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Start Date</th>
            </tr>
        @endif
    </thead>

    <tbody>
        @if($tab == 'case')
            @foreach ($caseReportEntrys as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        Case No: {{ $entry->case_no }}
                        Customer: {{ optional($entry->convicts->first()->customer)->company_name ?? 'N/A' }}
                        Convict: {{ $entry->convicts->pluck('convict_name')->implode(', ') }}
                        Address: {{ $entry->convicts->pluck('convict_address')->implode(', ') }}
                    </td>
                    <td>
                        Name: {{ $entry->advocate_name }}
                        Phone: {{ $entry->advocate_phone }}
                    </td>
                    <td>{{ $entry->status == 'running' ? 'Running' : 'Withdraw' }}</td>
                    <td>
                        @if ($entry->hajiras->last())
                            Date: {{ $entry->hajiras->last()->hajira_date }}
                            Remarks: {{ $entry->hajiras->last()->hajira_description }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            @foreach ($noticeReportEntrys as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($entry->convicts->first()->customer)->company_name ?? 'N/A' }}</td>
                    <td>{{ $entry->convicts->pluck('convict_name')->implode(', ') }}</td>
                    <td>{{ $entry->convicts->pluck('convict_phone')->implode(', ') }}</td>
                    <td>{{ $entry->convicts->pluck('convict_address')->implode(', ') }}</td>
                    <td>{{ $entry->amount }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->date)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>