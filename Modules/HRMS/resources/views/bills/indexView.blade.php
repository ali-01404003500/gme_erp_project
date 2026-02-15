<style>
    @import url('https://fonts.maateen.me/kalpurush/font.css');

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

    footer {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    footer p {
        margin: 10px 0;
        font-size: 14px;
        width: 45%;
        text-align: center;
    }

    .custom-table,
    .custom-table td,
    .custom-table th,
    .custom-table tr {
        padding: 2px;
        margin: 2px;
        border: none;
        border-bottom: 1px solid #000000;
        border-right: none;
        border-left: none;

    }
</style>

<div class="row" style="font-size: 12px!important;">
    <div class="col-md-12 m-2">
        <x-error-alart />
    </div>
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">


                <header class="my-header">
                    @include('partials._for_pdf_header_2nd')
                </header>

                <section class="title">
                    <h2>TA/DA List</h2>
                </section>

                <table class="table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">SL</th>
                                        <th style="width: 30%;">Sender Info</th>
                                        <th style="width: 30%;">Particulars</th>
                                        <th style="width: 25%;">Entry Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach ($billsAndAllowances as $key => $bill)
                                        @php
                                            $grandTotal += $bill->total_requested_amount;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                <ul style="list-style: disc; padding-left: 20px; margin: 0;">
                                                    <li>
                                                        <strong>Request From:</strong> 
                                                        <a class="text-dark" href="{{ route('hrm.bills.show', $bill->id) }}">
                                                            {{ @$bill->employee->full_name }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <strong>Requisition Type:</strong>
                                                        @if($bill->transportExpenses->count() > 0 && $bill->generalExpenses->count() > 0)
                                                            Transport & General Expense
                                                        @elseif($bill->transportExpenses->count() > 0)
                                                            Transport Expense
                                                        @else
                                                            General Expense
                                                        @endif
                                                    </li>
                                                    <li>
                                                        <strong>Request Date:</strong> {{ date('d-M-y', strtotime($bill->date_of_bill_claim)) }}
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul style="list-style: disc; padding-left: 20px; margin: 0;">
                                                    <li>
                                                        <strong>Requested Amount:</strong> 
                                                        <span style="color: #dc3545;">{{ number_format($bill->total_requested_amount) }}</span>
                                                    </li>
                                                    @php
                                                        $teamLeaderTotal = $bill->transportExpenses->sum('team_leader_approved_amount') + 
                                                                          $bill->generalExpenses->sum('team_leader_approved_amount');
                                                        $accountsTotal = $bill->transportExpenses->sum('accounts_approved_amount') + 
                                                                        $bill->generalExpenses->sum('accounts_approved_amount');
                                                        $finalTotal = $bill->transportExpenses->sum('final_approved_amount') + 
                                                                     $bill->generalExpenses->sum('final_approved_amount');
                                                    @endphp
                                                    @if($teamLeaderTotal > 0)
                                                    <li>
                                                        <strong>Check Amount (TL):</strong> 
                                                        <span style="color: #17a2b8;">{{ number_format($teamLeaderTotal) }}</span>
                                                    </li>
                                                    @endif
                                                    @if($accountsTotal > 0)
                                                    <li>
                                                        <strong>Check Amount (HR/Accounts):</strong> 
                                                        <span style="color: #007bff;">{{ number_format($accountsTotal) }}</span>
                                                    </li>
                                                    @endif
                                                    @if($finalTotal > 0)
                                                    <li>
                                                        <strong>Final Approved Amount:</strong> 
                                                        <span style="color: #28a745;">{{ number_format($finalTotal) }}</span>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </td>
                                            <td>
                                                <ul style="list-style: disc; padding-left: 20px; margin: 0;">
                                                    <li>
                                                        <strong>Entry By:</strong> {{ @$bill->createdBy->name ?? @$bill->employee->full_name }}
                                                    </li>
                                                    <li>
                                                        <strong>Entry Date:</strong> {{ date('d-M-y', strtotime($bill->created_at)) }}
                                                    </li>
                                                    @if($bill->checked_by_team_leader)
                                                    <li>
                                                        <strong>Checked By (Team Leader):</strong> {{ @$bill->checkedByTeamLeader->name ?? 'N/A' }}
                                                    </li>
                                                    <li>
                                                        <strong>Date:</strong> {{ $bill->checked_by_team_leader_date ? date('d-M-y', strtotime($bill->checked_by_team_leader_date)) : 'N/A' }}
                                                    </li>
                                                    @endif
                                                    @if($bill->checked_by_accounts)
                                                    <li>
                                                        <strong>Checked By (HR/Accounts):</strong> {{ @$bill->checkedByAccounts->name ?? 'N/A' }}
                                                    </li>
                                                    <li>
                                                        <strong>Date:</strong> {{ $bill->checked_by_accounts_date ? date('d-M-y', strtotime($bill->checked_by_accounts_date)) : 'N/A' }}
                                                    </li>
                                                    @endif
                                                    @if($bill->final_approved_by)
                                                    <li>
                                                        <strong>Final Approved By:</strong> {{ @$bill->finalApprovedBy->name ?? 'N/A' }}
                                                    </li>
                                                    <li>
                                                        <strong>Date:</strong> {{ $bill->final_approved_date ? date('d-M-y', strtotime($bill->final_approved_date)) : 'N/A' }}
                                                    </li>
                                                    @endif
                                                    <li>
                                                        <strong>Status:</strong>
                                                        @if ($bill->status == 'pending')
                                                            <span class="badge badge-round badge-warning">Pending</span>
                                                        @elseif($bill->status == 'team_leader_check')
                                                            <span class="badge badge-round badge-info">Team Leader Checked</span>
                                                        @elseif($bill->status == 'accounts_check')
                                                            <span class="badge badge-round badge-primary">HR/Accounts Checked</span>
                                                        @elseif($bill->status == 'approved')
                                                            <span class="badge badge-round badge-success">Approved</span>
                                                        @elseif($bill->status == 'rejected')
                                                            <span class="badge badge-round badge-danger">Rejected</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                <footer style="margin-top: 100px">
                    @include('partials._for_pdf_footer')
                </footer>
            </div>
        </div>
    </div>
</div>
