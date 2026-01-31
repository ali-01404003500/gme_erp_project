<style>
    .my-header {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .my-header img {
        max-width: 100px;
        margin-right: 20px;
    }
    .my-header h1 {
        margin: 0;
        font-size: 50px;
        font-weight: bold;
        color: rgb(0, 0, 187);
    }
    .my-header p {
        margin: 5px 0;
        font-size: 12px;
    }
    .title {
        text-align: center;
        margin-bottom: 20px;
    }
    .title h2 {
        margin: 0;
        font-size: 20px;
        text-decoration: underline;
    }
    table.table {
        width: 100%;
        border-collapse: collapse;
    }
    table.table th, table.table td {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
        border: 1px solid #000;
    }
</style>

<div style="font-size: 12px!important;">
    <header class="my-header">
        @include('partials._for_pdf_header_2nd')
    </header>

    <section class="title">
        <h2>Legal Report</h2>
    </section>

    <table class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <td colspan="6" style="font-family: 'Arial Black'; text-align: center; font-size: 36px">
                    {{ $company_info->company_name ?? 'All Branch' }}
                </td>
            </tr>
            <tr>
                <td colspan="6" style="font-family: 'Arial Black'; text-align: center; font-size: 24px">
                    Legal Report
                </td>
            </tr>
            <tr>
                <td colspan="6" style="font-family: 'Arial Black'; text-align: center">
                    {{ request('from') ? 'From: ' . request('from') : '' }} {{ request('to') ? 'To: ' . request('to') : '' }}
                </td>
            </tr>
            <tr>
                <th>SL</th>
                <th>Case Info</th>
                <th>Advocate Info</th>
                <th>Legal Status</th>
                <th>Document</th>
                <th>Last Hajira Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($legalEntrys as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        Case No: {{ $entry->case_no }}<br>
                        Customer: {{ optional($entry->convicts->first()->customer)->company_name ?? 'N/A' }}<br>
                        Convict: {{ $entry->convicts->pluck('convict_name')->implode(', ') }}<br>
                        Address: {{ $entry->convicts->pluck('convict_address')->implode(', ') }}
                    </td>
                    <td>
                        Name: {{ $entry->advocate_name }}<br>
                        Phone: {{ $entry->advocate_phone }}
                    </td>
                    <td>{{ $entry->status == 'running' ? 'Running' : 'Withdraw' }}</td>
                    <td>{{ $entry->attachment ? 'Available' : 'N/A' }}</td>
                    <td>
                        @if ($entry->hajiras->last())
                            Date: {{ $entry->hajiras->last()->hajira_date }}<br>
                            Remarks: {{ $entry->hajiras->last()->hajira_description }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer style="margin-top: 100px">
        @include('partials._for_pdf_footer')
    </footer>
</div>