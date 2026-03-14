{{-- resources/views/Account/payments/petty-cash-payments/details-modal.blade.php --}}
<form method="POST" action="{{ route('account.payments.petty-cash-payments.process') }}" id="paymentForm">
    @csrf
    <input type="hidden" name="ids" value="{{ request()->ids }}" >
    <div class="row mb-3">
        <div class="col-md-6"><strong>Employee:</strong> {{ $billsAndAllowance->first()->employee->full_name }}</div>
        <div class="col-md-6"><strong>Type:</strong>
            @if($billsAndAllowance->pluck('transportExpenses')->flatten()->count()  && $billsAndAllowance->pluck('generalExpenses')->flatten()->count())
            
                Transport & General (PCB)
            @elseif($billsAndAllowance->pluck('transportExpenses')->flatten()->count() )
                Transport (PCB)
            @else
                General (PCB)
            @endif
        </div>
    </div>

   

    <!-- Transport Expenses -->
    @if($billsAndAllowance->pluck('transportExpenses')->flatten()->count())
         <div class="row mb-4"> 
            <div class="col-9 "></div>
            <div class="col-3 "> 
                <label><strong>Apply to All Transport Expense Account Head</strong></label>
                <select class="tom-select form-select global-transport-account-head w-100" data-placeholder="Apply same account head to all transport expense rows">
                    <option value="">-- Apply to All --</option> 
                    @foreach(\Modules\Account\Models\Account::where('account_group_id', 5)->orderBy('name')->get() as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->account_number }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <h5>Transport Expenses</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr><th>SL</th><th>Transport Name</th><th>Remarks</th><th>Doc</th><th>Request Amount</th><th>Approved Amount</th><th>Account Head</th></tr>
                </thead>
                <tbody>
                    @php
                        $tReqAmt = $tApvAmt = 0;
                    @endphp
                    @foreach($billsAndAllowance->pluck('transportExpenses')->flatten() as $i => $e)
                    @php
                        $tReqAmt += $e->amount;
                        $tApvAmt += $e->final_approved_amount;
                    @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $e->transportType->name ?? 'Transport' }}</td>
                            <td>{{ $e->expense_description }}</td>
                            <td class="text-center">
                                @if($e->receipts_invoices)<a href="{{ $e->receipts_invoices }}" target="_blank"><i class="fa fa-file text-primary"></i></a>@endif
                                @if($e->supporting_documents)<a href="{{ $e->supporting_documents }}" target="_blank"><i class="fa fa-file-alt text-success"></i></a>@endif
                            </td>
                            <td>{{ number_format($e->amount) }}</td>
                            <td class="text-success fw-bold">{{ number_format($e->final_approved_amount ?? 0) }}</td>
                            <td width="250">
                                <select name="account_heads[transport_{{ $e->id }}]" class="transport-tom-select form-select individual-transport-account-head" required>
                                    <option value="">Select Account</option>
                                    @foreach(\Modules\Account\Models\Account::where('account_group_id', 5)->orderBy('name')->get() as $acc)
                                        <option value="{{ $acc->id }}" {{ $e->account_head_id == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->name }} ({{ $acc->account_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfooter>
                    <tr>
                        <td colspan="4">Total</td>
                        <td>{{$tReqAmt}}</td>
                        <td>{{$tApvAmt}}</td>
                    </tr>
                </tfooter>
            </table>
        </div>
    @endif

    <!-- General Expenses -->
    @if($billsAndAllowance->pluck('generalExpenses')->flatten()->count())
        <div class="row mb-4"> 
            <div class="col-9 "></div>
            <div class="col-3 ">
                <label><strong>Apply to All General Expense Account Head</strong></label>
                <select class="tom-select form-select global-general-account-head w-100" data-placeholder="Apply same account head to all general expense rows">
                    <option value="">-- Apply to All --</option>
                    @foreach(\Modules\Account\Models\Account::where('account_group_id', 5)->orderBy('name')->get() as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->account_number }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <h5>General Expenses</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr><th>SL</th><th>Expense Name</th><th>Remarks</th><th>Doc</th><th>Request Amount</th><th>Approved Amount</th><th>Account Head</th></tr>
                </thead>
                <tbody>
                    @php
                        $gReqAmt = $gApvAmt = 0;
                    @endphp
                    @foreach($billsAndAllowance->pluck('generalExpenses')->flatten() as $i => $e)
                    @php
                        $gReqAmt += $e->amount;
                        $gApvAmt += $e->final_approved_amount;
                    @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $e->expenseType->name ?? 'General' }}</td>
                            <td>{{ $e->expense_description }}</td>
                            <td class="text-center">
                                @if($e->receipts_invoices)<a href="{{ $e->receipts_invoices }}" target="_blank"><i class="fa fa-file text-primary"></i></a>@endif
                                @if($e->supporting_documents)<a href="{{ $e->supporting_documents }}" target="_blank"><i class="fa fa-file-alt text-success"></i></a>@endif
                            </td>
                            <td>{{ number_format($e->amount) }}</td>
                            <td class="text-success fw-bold">{{ number_format($e->final_approved_amount ?? 0) }}</td>
                            <td width="250">
                                <select name="account_heads[general_{{ $e->id }}]" class="general-tom-select  form-select individual-general-account-head" required>
                                    <option value="">Select Account</option>
                                    @foreach(\Modules\Account\Models\Account::where('account_group_id', 5)->orderBy('name')->get() as $acc)
                                        <option value="{{ $acc->id }}" {{ $e->account_head_id == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->name }} ({{ $acc->account_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfooter>
                    <tr>
                        <td colspan="4">Total</td>
                        <td>{{$gReqAmt}}</td>
                        <td>{{$gApvAmt}}</td>
                    </tr>
                </tfooter>
            </table>
        </div>
    @endif

    @php
        $total = $billsAndAllowance->pluck('transportExpenses')->flatten()->sum('final_approved_amount') +
                $billsAndAllowance->pluck('generalExpenses')->flatten()->sum('final_approved_amount');
    @endphp

    <div class="alert alert-info">
        <strong>Total Amount: {{ number_format($total) }} <br> In Words: {{ convert_number($total) }} Taka Only</strong>
    </div>

    <div class="mb-3">
        <label>Remarks (Optional)</label>
        <textarea name="remarks" class="form-control" rows="3" placeholder="Add any payment note..."></textarea>
    </div>
                            <div class="mt-5 pt-4 border-top">
                            <h5 class="mb-4">Verification Details</h5>
                            <div class="row text-center">
                                <div class="col-md-2">
                                    <div class="signature-box">
                                         <div class="mt-2">{{ @$billsAndAllowance->first()->createdBy->name ?? $billsAndAllowance->first()->employee->full_name }}</div>
                                        <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->first()->created_at)) }}</small>
                                        <div class="signature-line">________________</div>
                                        <strong>Prepared By</strong>
                                       
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="signature-box">
                                        @if($billsAndAllowance->first()->checkedByTeamLeader)
                                            <div class="mt-2">{{ $billsAndAllowance->first()->checkedByTeamLeader->name }}</div>
                                            <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->first()->checked_by_team_leader_date)) }}</small>
                                        @endif
                                        <div class="signature-line">________________</div>
                                        <strong>Checked By (Team Leader)</strong>
                                        
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="signature-box">
                                         @if($billsAndAllowance->first()->checkedByAccounts)
                                            <div class="mt-2">{{ $billsAndAllowance->first()->checkedByAccounts->name }}</div>
                                            <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->first()->checked_by_accounts_date)) }}</small>
                                        @endif
                                        <div class="signature-line">________________</div>
                                        <strong>Checked By (HR/Accounts)</strong>
                                       
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="signature-box">
                                         @if($billsAndAllowance->first()->finalApprovedBy)
                                            <div class="mt-2">{{ $billsAndAllowance->first()->finalApprovedBy->name }}</div>
                                            <small class="text-muted">{{ date('d-M-y', strtotime($billsAndAllowance->first()->final_approved_date)) }}</small>
                                        @endif
                                        <div class="signature-line">________________</div>
                                        <strong>Approved By</strong>
                                       
                                    </div>
                                </div>

                                
                            </div>
                        </div>

    <div class="modal-footer mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-check"></i> Confirm Payment
        </button>
    </div>
</form>