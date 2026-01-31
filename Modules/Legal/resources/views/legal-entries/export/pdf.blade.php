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
        font-size: 12px;
    }
    table.table th, table.table td {
        text-align: center;
        vertical-align: middle;
        padding: 8px;
        border: 1px solid #ddd;
    }
    table.table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <header class="my-header">
                    @include('partials._for_pdf_header_2nd')
                </header>

                <section class="title">
                    <h2>{{ $tab == 'case' ? 'Case Report' : 'Notice Report' }}</h2>
                    {{-- @if($from || $to)
                        <p style="font-size: 14px; margin-top: 10px;">
                            Date Range: {{ $from ?? 'Start' }} to {{ $to ?? 'End' }}
                        </p>
                    @endif --}}
                </section>

                @if($tab == 'case')
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Case Info</th>
                                <th>Advocate Info</th>
                                <th>Legal Status</th>
                                <th>Last Hajira Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($caseReportEntrys as $index => $entry)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="text-align: left;">
                                        <strong>Case No:</strong> {{ $entry->case_no }}<br>
                                        <strong>Customer:</strong> {{ optional($entry->convicts->first()->customer)->company_name ?? 'N/A' }}<br>
                                        <strong>Convict:</strong> {{ $entry->convicts->pluck('convict_name')->implode(', ') }}<br>
                                        <strong>Address:</strong> {{ $entry->convicts->pluck('convict_address')->implode(', ') }}
                                    </td>
                                    <td style="text-align: left;">
                                        <strong>Name:</strong> {{ $entry->advocate_name }}<br>
                                        <strong>Phone:</strong> {{ $entry->advocate_phone }}
                                    </td>
                                    <td>{{ $entry->status == 'running' ? 'Running' : 'Withdraw' }}</td>
                                    <td style="text-align: left;">
                                        @if ($entry->hajiras->last())
                                            <strong>Date:</strong> {{ $entry->hajiras->last()->hajira_date }}<br>
                                            <strong>Remarks:</strong> {{ $entry->hajiras->last()->hajira_description }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Customer Name</th>
                                <th>Convict Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                            </tr>
                        </thead>
                        <tbody>
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
                        </tbody>
                    </table>
                @endif

                <footer style="margin-top: 100px">
                    @include('partials._for_pdf_footer')
                </footer>
            </div>
        </div>
    </div>
</div>