@section('title', 'TA/DA Detail')
@section('description', 'TA/DA Detail')
@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active">TA/DA Detail</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="row">
                            <a href="{{ route('hrm.bills.show', $billsAndAllowance->id) }}?export=pdf" target="_blank"
                                class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                <i class="las la-file-pdf"></i> PDF
                            </a>
                            @if (hasPermission('hrm.bills.index'))
                                <a href="{{ route('hrm.bills.index') }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">TA/DA Detail</h4>
                    </div>
                    <div class="card-body">
                        {{-- Basic Information --}}
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">Employee Name</th>
                                    <td>{{ $billsAndAllowance->employee->full_name }}</td>
                                    <th width="20%">Employee ID</th>
                                    <td>{{ $billsAndAllowance->employee->employementDetail->card_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Requisition Type</th>
                                    <td>
                                        @if($billsAndAllowance->transportExpenses->count() > 0 && $billsAndAllowance->generalExpenses->count() > 0)
                                            Transport & General Expense
                                        @elseif($billsAndAllowance->transportExpenses->count() > 0)
                                            Transport Expense
                                        @else
                                            General Expense
                                        @endif
                                    </td>
                                    <th>Request Date</th>
                                    <td>{{ date('d-M-y', strtotime($billsAndAllowance->date_of_bill_claim)) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td colspan="3">
                                        @if ($billsAndAllowance->status == 'pending')
                                            <span class="badge badge-round badge-warning">Pending</span>
                                        @elseif($billsAndAllowance->status == 'team_leader_check')
                                            <span class="badge badge-round badge-info">Team Leader Checked</span>
                                        @elseif($billsAndAllowance->status == 'accounts_check')
                                            <span class="badge badge-round badge-primary">Accounts Checked</span>
                                        @elseif($billsAndAllowance->status == 'approved')
                                            <span class="badge badge-round badge-success">Approved</span>
                                        @elseif($billsAndAllowance->status == 'rejected')
                                            <span class="badge badge-round badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        {{-- Transport Expenses --}}
                        @if($billsAndAllowance->transportExpenses->count() > 0)
                        <h5 class="mt-4 mb-3">Transport Expenses</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Expense Name</th>
                                        <th>Date</th>
                                        <th>From - To</th>
                                        <th>Remarks</th>
                                        <th>Amount</th>
                                        <th>Document</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billsAndAllowance->transportExpenses as $key => $transport)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>Transport Expense</td>
                                            <td>{{ date('d-M-y', strtotime($transport->date_of_expense)) }}</td>
                                            <td>{{ $transport->from_location }} - {{ $transport->to_location }}</td>
                                            <td>{{ $transport->expense_description }}</td>
                                            <td>
                                                <div><strong>Request Amt:</strong> {{ number_format($transport->amount) }}</div>
                                                @if($transport->team_leader_approved_amount)
                                                    <div><strong>Chk. Amt (Team Leader):</strong> {{ number_format($transport->team_leader_approved_amount) }}</div>
                                                @endif
                                                @if($transport->accounts_approved_amount)
                                                    <div><strong>Chk. Amt (HR/Accounts):</strong> {{ number_format($transport->accounts_approved_amount) }}</div>
                                                @endif
                                                @if($transport->final_approved_amount)
                                                    <div class="text-success"><strong>Final Approved:</strong> {{ number_format($transport->final_approved_amount) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($transport->receipts_invoices)
                                                    <a href="{{ $transport->receipts_invoices }}" target="_blank" 
                                                       class="btn btn-sm btn-outline-primary" title="View Receipt/Invoice">
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                @endif
                                                @if ($transport->supporting_documents)
                                                    <a href="{{ $transport->supporting_documents }}" target="_blank" 
                                                       class="btn btn-sm btn-outline-success" title="View Supporting Document">
                                                        <i class="fa fa-file-alt"></i>
                                                    </a>
                                                @endif
                                                @if (!$transport->receipts_invoices && !$transport->supporting_documents)
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        {{-- General Expenses --}}
                        @if($billsAndAllowance->generalExpenses->count() > 0)
                        <h5 class="mt-4 mb-3">General Expenses</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Expense Name</th>
                                        <th>Date</th>
                                        <th>Remarks</th>
                                        <th>Amount</th>
                                        <th>Document</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billsAndAllowance->generalExpenses as $key => $general)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $general->expenseType->name ?? 'General Expense' }}</td>
                                            <td>{{ date('d-M-y', strtotime($general->expense_date)) }}</td>
                                            <td>{{ $general->expense_description }}</td>
                                            <td>
                                                <div><strong>Request Amt:</strong> {{ number_format($general->amount) }}</div>
                                                @if($general->team_leader_approved_amount)
                                                    <div><strong>Chk. Amt (Team Leader):</strong> {{ number_format($general->team_leader_approved_amount) }}</div>
                                                @endif
                                                @if($general->accounts_approved_amount)
                                                    <div><strong>Chk. Amt (HR/Accounts):</strong> {{ number_format($general->accounts_approved_amount) }}</div>
                                                @endif
                                                @if($general->final_approved_amount)
                                                    <div class="text-success"><strong>Final Approved:</strong> {{ number_format($general->final_approved_amount) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($general->receipts_invoices)
                                                    <a href="{{ $general->receipts_invoices }}" target="_blank" 
                                                       class="btn btn-sm btn-outline-primary" title="View Receipt/Invoice">
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                @endif
                                                @if ($general->supporting_documents)
                                                    <a href="{{ $general->supporting_documents }}" target="_blank" 
                                                       class="btn btn-sm btn-outline-success" title="View Supporting Document">
                                                        <i class="fa fa-file-alt"></i>
                                                    </a>
                                                @endif
                                                @if (!$general->receipts_invoices && !$general->supporting_documents)
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        {{-- Grand Total --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                        
                                        <div class="col-md-6 p-4">
                                            <strong>IN WORD:</strong> <span style="white-space: nowrap;">{{ convert_number($billsAndAllowance->total_requested_amount) }} Taka Only</span>
                                        </div>
                                        <div class="col-md-6 text-end p-4">
                                            <strong>Grand Total:</strong> {{ number_format($billsAndAllowance->total_requested_amount) }}
                                        </div>
                                </div>
                            </div>
                        </div>

                        {{-- Verification Signatures --}}
                        <div class="mt-5 pt-4 border-top">
                            <h5 class="mb-4">Verification Details</h5>
                            <div class="row text-center">
                                <div class="col-md-2">
                                    <div class="signature-box">
                                         <div class="mt-2">{{ @$billsAndAllowance->createdBy->name ?? $billsAndAllowance->employee->full_name }}</div>
                                        <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->created_at)) }}</small>
                                        <div class="signature-line">________________</div>
                                        <strong>Prepared By</strong>
                                       
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="signature-box">
                                        @if($billsAndAllowance->checkedByTeamLeader)
                                            <div class="mt-2">{{ $billsAndAllowance->checkedByTeamLeader->name }}</div>
                                            <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->checked_by_team_leader_date)) }}</small>
                                        @endif
                                        <div class="signature-line">________________</div>
                                        <strong>Checked By (Team Leader)</strong>
                                        
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="signature-box">
                                         @if($billsAndAllowance->checkedByAccounts)
                                            <div class="mt-2">{{ $billsAndAllowance->checkedByAccounts->name }}</div>
                                            <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->checked_by_accounts_date)) }}</small>
                                        @endif
                                        <div class="signature-line">________________</div>
                                        <strong>Checked By (HR/Accounts)</strong>
                                       
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="signature-box">
                                         @if($billsAndAllowance->finalApprovedBy)
                                            <div class="mt-2">{{ $billsAndAllowance->finalApprovedBy->name }}</div>
                                            <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->final_approved_date)) }}</small>
                                        @endif
                                        <div class="signature-line">________________</div>
                                        <strong>Approved By</strong>
                                       
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="signature-box">
                                        @if($billsAndAllowance->paymentBy)
                                            <div class="mt-2">{{ $billsAndAllowance->paymentBy->name }}</div>
                                            <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->payment_date)) }}</small>
                                        @endif
                                        <div class="signature-line">________________</div>
                                        <strong>Payment By</strong>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Comments Section --}}
                        @if($billsAndAllowance->checked_by_team_leader_comments || $billsAndAllowance->checked_by_accounts_comments || $billsAndAllowance->final_approved_comments)
                        <div class="mt-4">
                            <h5>Remarks</h5>
                            @if($billsAndAllowance->checked_by_team_leader_comments)
                                <div class="alert alert-info">
                                    <strong>Team Leader:</strong> {{ $billsAndAllowance->checked_by_team_leader_comments }}
                                </div>
                            @endif
                            @if($billsAndAllowance->checked_by_accounts_comments)
                                <div class="alert alert-primary">
                                    <strong>HR/Accounts:</strong> {{ $billsAndAllowance->checked_by_accounts_comments }}
                                </div>
                            @endif
                            @if($billsAndAllowance->final_approved_comments)
                                <div class="alert alert-success">
                                    <strong>Final Approver:</strong> {{ $billsAndAllowance->final_approved_comments }}
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')

@endsection