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
        <h2>Legal List</h2>
    </section>

    <table class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <td colspan="9" style="font-family: 'Arial Black'; text-align: center; font-size: 36px">
                    {{ $company_info->company_name ?? 'All Branch' }}
                </td>
            </tr>
            <tr>
                <td colspan="9" style="font-family: 'Arial Black'; text-align: center; font-size: 24px">
                    Legal List
                </td>
            </tr>
            <tr>
                <td colspan="9" style="font-family: 'Arial Black'; text-align: center">
                    {{ request('from') ? 'From: ' . request('from') : '' }} {{ request('to') ? 'To: ' . request('to') : '' }}
                    {{ request('legal_type') && request('legal_type') != 'all' ? ' | Type: ' . ucfirst(request('legal_type')) : '' }}
                </td>
            </tr>
            <tr>
                <th>SL</th>
                <th>Customer Name</th>
                <th>Convict Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Amount</th>
                <th>Case No</th>
                <th>Type</th>
                <th>Start Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($legalEntrys as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($entry->convicts->first()->customer)->company_name ?? 'N/A' }}</td>
                    <td>{{ $entry->convicts->pluck('convict_name')->implode(', ') }}</td>
                    <td>{{ $entry->convicts->pluck('convict_phone')->implode(', ') }}</td>
                    <td>{{ $entry->convicts->pluck('convict_address')->implode(', ') }}</td>
                    <td>{{ $entry->amount }}</td>
                    <td>{{ $entry->case_no }}</td>
                    <td>{{ ucfirst($entry->legal_type) }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->date)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer style="margin-top: 100px">
        @include('partials._for_pdf_footer')
    </footer>
</div>