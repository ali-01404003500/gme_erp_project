<table  class="table dt-table-hover" style="width:100%">
    <thead>
        <tr>
            <td colspan="5" style="font-family: 'Arial Black';text-align: center; font-size: 36px">
                {{ $company_info->company_name ?? 'All Branch' }}
            </td>
        </tr>
        <tr>
            <td colspan="5" style="font-family: 'Arial Black';text-align: center; font-size: 28px">
                {{ $department ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="5" style="font-family: 'Arial Black';text-align: center; font-size: 24px">Chart Of Accounts
            </td>
        </tr>
        <tr>
            <td colspan="5" style="font-family: 'Arial Black';text-align: center">{{ request('date', now())}}
            </td>
        </tr>

        <tr class="heading-style">
            <th class="text-center" style="font-family: 'Arial Black';text-align: center; font-size: 16px">Sl</th>
            <th class="text-center" style="font-family: 'Arial Black';text-align: center; font-size: 16px">Account Group</th>
            <th class="text-center" style="font-family: 'Arial Black';text-align: center; font-size: 16px">Account Control</th>
            <th class="text-center" style="font-family: 'Arial Black';text-align: center; font-size: 16px">Account Subsidiary</th>
            <th class="text-center" style="font-family: 'Arial Black';text-align: center; font-size: 16px">Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $key => $accountSubsidiary)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $accountSubsidiary->accountGroup->name ?? '' }}</td>
                <td class="text-center">{{ $accountSubsidiary->accountControl->name ?? '' }}</td>
                <td class="text-center">{{ $accountSubsidiary->accountSubsidiary->name ?? '' }}</td>
                <td class="text-center">{{ $accountSubsidiary->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>