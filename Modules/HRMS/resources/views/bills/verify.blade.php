@section('title',"TA/DA List")
@section('description',"TA/DA List")
@extends('layout.app')
@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.bills-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('hrm.bills.create'))
                            <a href="{{ route('hrm.bills.create') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.bills-list-menu-title') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="30%">
                                            <select name="employee_id" id="employee_id" class="tom-select input-sm"
                                                    data-placeholder="Select Employee">
                                                    <option value=""></option>
                                                    @foreach ($employees as $key => $value)
                                                        <option {{ request('employee_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->full_name }}</option>
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td width="40%">
                                            <div class="input-daterange input-group">
                                                <input type="text" class="form-control flatdate" name="from"
                                                    value="{{ request('from') }}" autocomplete="off"
                                                    placeholder="From" />
                                                <span class="input-group-text">
                                                    <i class="fa fa-exchange-alt"></i>
                                                </span>
                                                <input type="text" class="form-control flatdate" name="to"
                                                    value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                            </div>
                                        </td>
                                        <td width="30%" class="text-right">
                                            <div class="btn-group btn-corner">
                                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                    Search</button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                        class="fa fa-refresh"></i> Refresh</a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">SL</th>
                                        <th style="width: 30%;">Biller Name</th>
                                        <th style="width: 30%;">Amount</th>
                                        <th style="width: 25%;">Status</th>
                                        <th style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($billsAndAllowances); --}}
                                    @php  
                                        $i = 0; 
                                    @endphp

                                    @foreach ($billsAndAllowances as $employeeId => $id) 
                                        @foreach ($id as $key => $empidwisegroup)
                                        @php 
                                            $bill = $empidwisegroup->first();    
                                            $billIds = $empidwisegroup->pluck('id')->toArray();
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ ++$i }}</td>
                                            <td>{{ @$bill->employee->full_name }}</td> 
                                            <td>
                                                {{ $empidwisegroup->pluck('transportExpenses')->flatten()->sum('amount') 
                                                + 
                                                $empidwisegroup->pluck('generalExpenses')->flatten()->sum('amount')}}

                                            </td>
                                            <td>
                                             
                                                @if ($bill->status == 'pending')
                                                    <span class="badge badge-round badge-warning">Pending</span>
                                                @elseif($bill->status == 'team_leader_check')
                                                    <span class="badge badge-round badge-info">Team Leader Checked</span>
                                                @elseif($bill->status == 'accounts_check')
                                                    <span class="badge badge-round badge-primary">HR/Accounts Checked</span>
                                                @elseif($bill->status == 'approved')
                                                    <span class="badge badge-round badge-success">Approved</span>
                                                @elseif($bill->status == 'paid')
                                                    <span class="badge badge-round badge-success">Paid</span>
                                                @elseif($bill->status == 'rejected')
                                                    <span class="badge badge-round badge-danger">Rejected</span>
                                                @endif
                                                
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    

                                                    @if (hasPermission('hrm.bills.team_leader_verify') && $bill->status == 'pending')
                                                        <button type="button" 
                                                            class="btn btn-outline-info btn-verify"  
                                                            data-id='@json($billIds)'
                                                            data-status="team_leader"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#verifyModal" title="Team Leader Verify">
                                                            <i class="fas fa-check-circle"></i> 
                                                        </button>
                                                    @endif

                                                    @if (hasPermission('hrm.bills.accounts_verify') && $bill->status == 'team_leader_check')
                                                        <button type="button" 
                                                            class="btn btn-outline-primary btn-verify" 
                                                            data-id='@json($billIds)'
                                                            data-status="accounts"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#verifyModal" title="HR/Accounts Verify">
                                                            <i class="fas fa-check-circle"></i> 
                                                        </button>
                                                    @endif

                                                    @if (hasPermission('hrm.bills.final_approve') && $bill->status == 'accounts_check')
                                                        <button type="button" 
                                                            class="btn btn-outline-success btn-verify" 
                                                            data-id='@json($billIds)'
                                                            data-status="final"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#verifyModal" title="Final Approval">
                                                            <i class="fas fa-check-double"></i> 
                                                        </button>
                                                    @endif 

                                                </div>
                                            </td>
                                        </tr> 
                                        @endforeach
                                    @endforeach
                                   
                                </tbody>
                                 
                            </table>
                        </div>
                        <div class="d-none">
                            <form class="delete-form" action="" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Verification Modal --}}
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalTitle">Verification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="verifyForm">
                @csrf
                @method('put')
                <div class="modal-body" id="verifyModalBody">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="verifySubmitBtn">Verify & Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table td {
        vertical-align: top;
    }
    .table td ul {
        font-size: 13px;
        line-height: 1.8;
    }
    .table td ul li {
        margin-bottom: 3px;
    }
    .modal-custom {
        max-width: 70%;  /* width percentage, 70% of screen */
        width: 90%;
    }
</style>

@endsection

@section('page_scripts')
<script>
    $(".datePicker").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $(document).ready(function() {
        $(document).on('click', '.btn-verify', function() {
            let billIds = $(this).data('id');
            billIds = JSON.stringify(billIds);
            //console.log(billIds);  
            const status = $(this).data('status');
            
            let actionUrl = '';
            let titleText = '';
            let buttonText = '';
            
            if (status === 'team_leader') {
                actionUrl = "{{ route('hrm.bills.team-leader-verify') }}";
                titleText = 'Team Leader Verification';
                buttonText = 'Verify & Submit';
            } else if (status === 'accounts') {
                actionUrl = "{{ route('hrm.bills.accounts-verify') }}";
                titleText = 'HR/Accounts Verification';
                buttonText = 'Verify & Submit';
            } else if (status === 'final') {
                actionUrl = "{{ route('hrm.bills.final-approve') }}";
                titleText = 'Final Approval';
                buttonText = 'Approve';
            }
            
           
            $('#verifyForm').attr('action', actionUrl+'?id='+billIds);
            $('#verifyModalTitle').text(titleText);
            $('#verifySubmitBtn').text(buttonText);
            
            $.ajax({
                url: "{{ route('hrm.bills.verify-details') }}?ids="+billIds,
                method: 'GET',
                success: function(response) {
                    renderVerificationDetails(response.data, status);
                },
                error: function() {
                    alert('Failed to load details');
                }
            });
        });

        function renderVerificationDetails(bill, status) {
            console.log(bill);  
            let transportTotal = 0;
            let generalTotal = 0;
            let transportExpenseCount = 0;
            let generalExpenseCount = 0;

            let html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Request From:</strong> ${bill[0].employee.full_name}
                    </div> 
                </div>
                
                <h5 class="mt-4">Transport Expenses</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Request Date</th>
                                <th>Expense Date</th>
                                <th>Transport By</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Remarks</th>
                                <th>Request Amt</th>`;
            
                                if (status === 'accounts' || status === 'final') {
                                    html += `<th>Chk. Amt <br>(Team Leader)</th>`;
                                }
                                if (status === 'final') {
                                    html += `<th>Chk. Amt <br>(HR/Accounts)</th>`;
                                }
            
                                html += `<th>Approved Amount</th>
                                <th>Documents</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        
                        bill.forEach(element => {
                            if (element.transport_expenses && element.transport_expenses.length > 0) {
                                element.transport_expenses.forEach((expense, index) => {
                                    transportExpenseCount++;

                                    transportTotal += parseFloat(expense.amount);
                                    html += `<tr>
                                        <td>${transportExpenseCount}</td>
                                        <td>${new Date(element.date_of_bill_claim).toLocaleDateString()}</td>
                                        <td>${new Date(expense.date_of_expense).toLocaleDateString()}</td>
                                        <td>${ expense.transport_type?.name ?? ''  }</td>
                                        <td>${expense.from_location}</td>
                                        <td>${expense.to_location}</td>
                                        <td>${expense.expense_description}</td>
                                        <td>${parseFloat(expense.amount).toFixed()}</td>`;
                                    let amt = expense.amount; 
                                    if (status === 'accounts' || status === 'final') {
                                        html += `<td>${expense.team_leader_approved_amount ? parseFloat(expense.team_leader_approved_amount).toFixed() : '-'}</td>`;
                                        amt = expense.team_leader_approved_amount;
                                    
                                    }
                                    if (status === 'final') {
                                        html += `<td>${expense.accounts_approved_amount ? parseFloat(expense.accounts_approved_amount).toFixed() : '-'}</td>`;
                                        amt = expense.accounts_approved_amount; 
                                    }
                                    
                                

                                    


                                    html += `<td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                            name="transport_approved[${expense.id}]" 
                                            value="${amt}" required>
                                    </td>
                                    <td class="text-center">
                                        ${expense.receipts_invoices ? `<a href="${expense.receipts_invoices}" target="_blank" title="Receipt/Invoice"><i class="fa fa-file text-primary"></i></a>` : ''}
                                        ${expense.supporting_documents ? `<a href="${expense.supporting_documents}" target="_blank" title="Supporting Document" class="ms-2"><i class="fa fa-file-alt text-success"></i></a>` : ''}
                                    </td>
                                    </tr>`;
                                });
                                

                            } 
                            else {
                                
                            }
                            
                        });
                        if( transportExpenseCount == 0)
                            html += `<tr><td colspan="10" class="text-center">No transport expenses</td></tr>`;
                        else
                            html += `<tr><td colspan="7" class="text-end">Total</td><td colspan="">${transportTotal}</td></tr>`;
                            
                        html += `</tbody></table></div>
                            
                            <h5 class="mt-4">General Expenses</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Request Date</th>
                                            <th>Expense Date</th>
                                            <th>Expense Type</th>
                                            <th>Remarks</th>
                                            <th>Request Amt</th>`;
                        
                                            if (status === 'accounts' || status === 'final') {
                                                html += `<th>Chk. Amt <br>(Team Leader)</th>`;
                                            }
                                            if (status === 'final') {
                                                html += `<th>Chk. Amt <br>(HR/Accounts)</th>`;
                                            }
                                            
                                            html += `<th>Approved Amount</th>
                                            <th>Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                                    bill.forEach(element => {
                                        if (element.general_expenses && element.general_expenses.length > 0) {
                                            element.general_expenses.forEach((expense, index) => {
                                                generalExpenseCount++
                                                generalTotal += parseFloat(expense.amount);
                                                html += `<tr>
                                                    <td>${generalExpenseCount}</td>
                                                    <td>${new Date(element.date_of_bill_claim).toLocaleDateString()}</td>
                                                    <td>${new Date(expense.expense_date).toLocaleDateString()}</td> 
                                                    <td>${ expense.expenseType?.name ?? ''  }</td>
                                                    <td>${expense.expense_description}</td>
                                                    <td>${parseFloat(expense.amount).toFixed()}</td>`;

                                                let amt = expense.amount; 
                                                if (status === 'accounts' || status === 'final') {
                                                    html += `<td>${expense.team_leader_approved_amount ? parseFloat(expense.team_leader_approved_amount).toFixed() : '-'}</td>`;
                                                    amt = expense.team_leader_approved_amount;
                                                }
                                                if (status === 'final') {
                                                    html += `<td>${expense.accounts_approved_amount ? parseFloat(expense.accounts_approved_amount).toFixed() : '-'}</td>`;
                                                    amt = expense.accounts_approved_amount;
                                                }
                                                
                                                html += `<td>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" 
                                                        name="general_approved[${expense.id}]" 
                                                        value="${amt}" required>
                                                </td>
                                                <td class="text-center">
                                                    ${expense.receipts_invoices ? `<a href="${expense.receipts_invoices}" target="_blank" title="Receipt/Invoice"><i class="fa fa-file text-primary"></i></a>` : ''}
                                                    ${expense.supporting_documents ? `<a href="${expense.supporting_documents}" target="_blank" title="Supporting Document" class="ms-2"><i class="fa fa-file-alt text-success"></i></a>` : ''}
                                                </td>
                                                </tr>`;
                                            });
                                            
                                        } 
                                    });
                                    if( generalExpenseCount == 0)
                                        html += `<tr><td colspan="8" class="text-center">No general expenses</td></tr>`;
                                    else
                                        html += `<tr><td colspan="6" class="text-end">Total</td><td colspan="">${generalTotal}</td></tr>`;
                                        
                                    let grandTotal = transportTotal + generalTotal;
                                    
                                    html += `</tbody></table></div>

                                        <div class="mt-4 p-3">
                                            <div class="row">
                                                <div class="col-md-12 text-end">
                                                    <h5><strong>Grand Total Amount: ${grandTotal.toFixed()}</strong></h5>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <label class="form-label">Remarks</label>
                                                <textarea name="comments" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>`;
                                    
                                    $('#verifyModalBody').html(html);
        }
    });
</script>
@endSection