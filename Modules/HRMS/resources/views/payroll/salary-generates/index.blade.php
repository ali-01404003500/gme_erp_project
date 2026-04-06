@section('title', 'Salary Verification')
@section('description', 'Salary Verification')
@extends('layout.app')
@section('content')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Salary Verification') }}</li>
                                </ol>
                            </nav>
                        </div>
                        {{-- <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.salary-generates.create'))
                                    <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#createModal">
                                        Add New
                                    </button>
                                @endif
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Salary Verification') }}</h4>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <div class="btn-group btn-corner"> 
                                        <a  href="{{ route('hrm.salary-generates.index', request()->query()) }}" class="btn btn-xs btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="zero-config"class="table table-bordered  table-bordered  dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salaryGenerates])' style="width:100%">  
                                        <thead>  
                                            <tr>
                                                <th colspan="2" class="text-center ">Employee</th>
                                                <th colspan="2" class="text-center ">Attendance</th>
                                                <th colspan="5" class="text-center d-none">Attendance</th>
                                                <th colspan="{{ $salaryBreakdowns->count()+2 }}" class="text-center ">Salary Breakdown</th>
                                                <th class="text-center ">&nbsp;</th>
                                                <th colspan="6" class="text-center ">Deductions</th>
                                                <th colspan="7" class="text-center ">&nbsp;</th>
                                            </tr>

                                            <tr> 
                                                <th class="text-center rotate-header bg-none">Sl</th>
                                                <th class="text-center bg-none">Employee</th> 
                                                <th class="text-center rotate-header bg-none  d-none">Days of <br>Month</th>
                                                <th class="text-center rotate-header bg-none d-none">Weekend</th>
                                                <th class="text-center rotate-header bg-none d-none">Holiday</th>
                                                <th class="text-center rotate-header bg-none">Absent</th>
                                                <th class="text-center rotate-header bg-none">Late</th>
                                                <th class="text-center rotate-header bg-none d-none">Leave</th>
                                                <th class="text-center rotate-header bg-none d-none">Worked <br>Days</th> 

                                                @foreach($salaryBreakdowns as $item)
                                                    <th class="text-center rotate-header bg-none"> 
                                                        {{ $item->type }}
                                                    </th>
                                                @endforeach 

                                                <th class="text-center rotate-header bg-none">Increment <br>Amount</th> 
                                                <th class="text-center rotate-header bg-none">Gross <br>Salary</th> 
                                                <th class="text-center rotate-header bg-none">Approved <br>Salary in (%)</th> 
                                                <th class="text-center rotate-header bg-none">Absent <br>Deduction</th>  
                                                <th class="text-center rotate-header bg-none">Late <br>Deduction</th> 
                                                <th class="text-center rotate-header bg-none">Advance <br>Deduction</th> 
                                                <th class="text-center rotate-header bg-none">Loan <br>Deduction</th> 
                                                <th class="text-center rotate-header bg-none">Tax <br>Deduction</th> 
                                                <th class="text-center rotate-header bg-none">Total <br>Deduction</th> 
                                                <th class="text-center rotate-header bg-none">Net <br>Payable</th> 
                                                <th class="text-center rotate-header bg-none">Payment <br>Method</th>  
                                                <th class="text-center bg-none">Status</th>
                                                <th class="text-center bg-none">Remarks</th> 
                                                <th class="text-center bg-none no-content" >Action</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody> 
                                            @php
                                                $total_basic = 0;
                                                $total_house_rent = 0;
                                                $total_medical = 0;
                                                $total_conveyance = 0;
                                                $total_entertainment = 0;
                                                $total_leave_fare = 0;
                                                $total_utility = 0;
                                                $total_unkeep = 0;
                                                $total_others = 0;
                                                $totalincrement = 0;
                                                $totalgross = 0;

                                                $totalabsent = 0;
                                                $totallate = 0;
                                                $totaladvance = 0;
                                                $totalloan = 0;
                                                $totaltax = 0;
                                                $totaldeduction = 0;
                                                $totalearning = 0;
                                                $department = '';
                                                
                                            @endphp 
                                            {{-- @dd($salaryGenerates) --}}
                                            @foreach ($salaryGenerates as $key => $item)
                                                @php
                                                    $total_basic += $item->basic;
                                                    $total_house_rent += $item->house_rent;
                                                    $total_medical += $item->medical;
                                                    $total_conveyance += $item->conveyance;
                                                    $total_entertainment += $item->entertainment;
                                                    $total_leave_fare += $item->leave_fare;
                                                    $total_utility += $item->utility;
                                                    $total_unkeep += $item->unkeep;
                                                    $total_others += $item->others;
                                                    $totalincrement += 0; 
                                                    $totalgross += $item->gross;

                                                    $totalabsent += $item->absent_deduction;
                                                    $totallate += $item->late_deduction;
                                                    $totaladvance += $item->advance;
                                                    $totalloan += $item->loan;
                                                    $totaltax += $item->tax;
                                                    $totaldeduction += $item->total_deductions;
                                                    $totalearning += $item->net_earning;
                                                    
                                                @endphp
                                                @if ($department != $item->employee->employementDetail->department->name)
                                                <tr>
                                                    <th colspan="34" class="text-start">{{ optional(optional(optional($item->employee)->employementDetail)->department)->name }}</th>
                                                </tr>
                                                @php
                                                    $department = $item->employee->employementDetail->department->name;
                                                @endphp
                                                @endif
                                                
                                                <tr> 
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td class="text-start">
                                                        @if ($item->status == 'UnPaid' || $item->status == 'Partially Paid')
                                                            <a href="{{ route('hrm.salary-generates.edit', $item->id) }}" target="_blank">{{ $item->employee->full_name }}</a>
                                                        @else
                                                            <a href="{{ route('hrm.salary-generates.show', $item->id) }}" target="_blank">{{ $item->employee->full_name }}</a>
                                                        @endif
                                                        <span class="text-muted d-none">({{ optional(optional($item->employee)->employementDetail)->card_no }})</span> <br>  
                                                        <span class="text-muted"> {{ optional(optional(optional($item->employee)->employementDetail)->designation)->name }}</span>

                                                    </td>    
                                                    <td class="text-center d-none">{{ $item->total_days }}</td>
                                                    <td class="text-center d-none">{{ $item->weekend }}</td>
                                                    <td class="text-center d-none">{{ $item->holidays }}</td>
                                                    <td class="text-center">{{ $item->absent_days }}</td>
                                                    <td class="text-center">{{ $item->late_days }}</td>
                                                    <td class="text-center d-none">{{ $item->leave_days }}</td>
                                                    <td class="text-center d-none">{{ $item->working_days }}</td>

                                                    @foreach($salaryBreakdowns as $sb)
                                                        <td class="text-center"> 
                                                            {{ $sb->value && optional($item)->{$sb->value} !== null ? number_format(optional($item)->{$sb->value}) : 0}}
                                                        </td>
                                                    @endforeach 


                                                    <td class="text-center">{{ 0 }}</td>
                                                    <td class="text-center">{{ numberFormat($item->gross) }}</td>
                                                    <td class="text-center">{{ numberFormat($item->approved_salary_ratio) }}%</td>
                                                    <td class="text-center">{{ numberFormat($item->absent_deduction) }}</td>
                                                    <td class="text-center">{{ numberFormat($item->late_deduction) }}</td>
                                                    <td class="text-center">{{ numberFormat($item->advance) }}</td>
                                                    <td class="text-center">{{ numberFormat($item->loan) }}</td>
                                                    <td class="text-center">{{ numberFormat($item->tax) }}</td>
                                                    <td class="text-center">{{ numberFormat($item->total_deductions) }}</td> 
                                                    <td class="text-center highlight-col">{{ numberFormat($item->net_earning) }}</td> 
                                                    <td class="text-center">
                                                        @php
                                                            $badgeClass = match(strtolower($item->payment_method)) {
                                                                'cash' => 'bg-success',
                                                                'bank' => 'bg-primary',
                                                                default => 'bg-info',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">
                                                            {{ ucfirst(strtolower($item->payment_method)) }}
                                                        </span>
                                                    </td>   
                                                    <td class="text-center">{{ ucwords(str_replace('_', ' ', $item->status)) }} - {{ $item->current_approval_level }}</td>

                                                    <form action="{{ route('hrm.salary-generates.update', request()->payroll_id)}}" method="POST" enctype="multipart/form-data">
                                                        <td class="text-center">
                                                            <input type="text" name="remarks" class="form-control form-control-sm" placeholder="Note" value="{{ $item->remarks }}">
                                                        </td>
                                                        <td class="text-center">  
                                                            @csrf
                                                            @method('PUT')
                                                        
                                                            <input type="hidden" name="approver_status" id="approver_status{{ $item->id }}" value="">
                                                            <input type="hidden" name="payroll_id"  value="{{ request()->payroll_id }}"> 
                                                            <input type="hidden" name="id" value="{{ $item->id }}" >
                                                            
                                                            @foreach($item->verifications as $verification) 
                                                            
                                                                @if($item->current_approval_level == $verification->approver_level && $verification->approver_id == optional(auth()->user()->employee)->id)  
                                                            
                                                                    <!-- Approve / Reject buttons --> 
                                                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                                        <button type="submit" class="btn btn-sm btn-primary status-btn" data-status="approved"  data-id="{{ $item->id }}"
                                                                            data-level="{{ $item->current_approval_level }}" onclick="$('#approver_status{{ $item->id }}').val('approved')">
                                                                            <i class="fas fa-check-circle"></i>
                                                                        </button>

                                                                        <button type="submit" class="btn btn-sm btn-danger status-btn" data-status="rejected" data-id="{{ $item->id }}"
                                                                            data-level="{{ $item->current_approval_level }}" onclick="$('#approver_status{{ $item->id }}').val('rejected')">
                                                                            <i class="fas fa-times-circle"></i>
                                                                        </button>
                                                                    </div> 
                                                                @endif
                                                                
                                                            @endforeach
                                                        
                                                        </td>
                                                    </form>
                                                    {{-- <td class="text-center">
                                                        @if ($item->status == 'UnPaid')
                                                            <div class="btn-group btn-group-xs text-center">
                                                                @if (hasPermission('hrm.salary-generates.show'))
                                                                    <a href={{ $item->id }} data-amount="{{ $item->net_earning - $item->total_deductions ?? 0 }}"
                                                                        data-action="{{ route('hrm.salary-generates.paid', $item->id) }}"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        data-bs-toggle="modal" data-bs-target="#paidModal" class="btn btn-paid btn-xs btn-outline-primary" title="Paid">
                                                                        Paid
                                                                    </a>
                                                                    <a href={{ $item->id }} class="btn btn-edit btn-xs btn-outline-warning"
                                                                        data-paymentable_amount="{{ $item->net_earning- $item->total_deductions - $item->salaryGeneratePayments->sum('amount') ?? 0 }}"
                                                                        data-action="{{ route('hrm.salary-generates.partially-paid', $item->id) }}"
                                                                        data-toggle="tooltip" data-placement="top" title="Edit"
                                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                                        Partial Paid
                                                                    </a>
                                                                @endif
                                                            </div>
                                                            
                                                        @elseif($item->status == 'Paid' || $item->net_earning - $item->salaryGeneratePayments->sum('amount') == 0)
                                                            <span class="badge badge-round badge-success">Paid</span>
                                                        @elseif($item->status == 'Partially Paid' && $item->net_earning - $item->salaryGeneratePayments->sum('amount') > 0)
                                                        <div class="btn-group btn-group-xs text-center" >

                                                            <a href={{ $item->id }} class="btn btn-edit btn-xs btn-outline-warning"
                                                                data-paymentable_amount="{{ $item->net_earning - $item->salaryGeneratePayments->sum('amount')??0 }}"
                                                                data-action="{{ route('hrm.salary-generates.partially-paid', $item->id) }}"
                                                                data-toggle="tooltip" data-placement="top" title="Edit"
                                                                data-bs-toggle="modal" data-bs-target="#editModal">
                                                                Partial Paid
                                                            </a>
                                                        </div>
                                                        @endif
                                                    </td> --}}
                                                    
                                                </tr>
                                                
                                            @endforeach 
                                            
                                        </tbody>
                                        <tfoot> 
                                                
                                            <tr>
                                                <th colspan="4" class="text-end d-none">Total</th>
                                                <th colspan="5" class="text-end">Total</th>

                                                @foreach($salaryBreakdowns as $sb)
                                                    @php
                                                        $varName = 'total_' . $sb->value;
                                                    @endphp

                                                    <th class="text-center">
                                                        {{ isset($$varName) ? number_format($$varName) : 0 }}
                                                    </th> 
                                                @endforeach  

                                            

                                                <th class="text-center">{{ numberFormat($totalincrement) }}</th> 
                                                <th class="text-center">{{ numberFormat($totalgross) }}</th> 
                                            

                                                <th class="text-center ">&nbsp;</th>

                                                <th class="text-center">{{ numberFormat($totalabsent) }}</th> 
                                                <th class="text-center">{{ numberFormat($totallate) }}</th> 
                                                <th class="text-center">{{ numberFormat($totaladvance) }}</th> 
                                                <th class="text-center">{{ numberFormat($totalloan) }}</th> 
                                                <th class="text-center">{{ numberFormat($totaltax) }}</th> 
                                                <th class="text-center">{{ numberFormat($totaldeduction) }}</th> 

                                                <th class="text-center">{{ numberFormat($totalearning) }}</th>  
                                                <th colspan="3" class="text-center ">&nbsp;</th>
                                                <th class="text-center ">&nbsp;</th>
                                            </tr> 
                                        </tfoot>
                                    </table>
                                </div> 
                            </div>
                            <div class="card-footer">
 
                                {{-- <input type="hidden" name="approver_status" id="approver_status" value="">
                                <input type="hidden" name="payroll_id"  value="{{ request()->payroll_id }}"> 
                                 
                                
                                @foreach($item->verifications as $verification)

                                    @if($item->current_approval_level == $verification->approver_level && ($item->status == 'Pending' || $item->status == 'recomended')  && $verification->approver_id == auth()->user()->employee->id)  
                                   
                                        <!-- Approve / Reject buttons --> 
                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-sm btn-primary status-btn" data-status="approved" 
                                                data-level="{{ $item->current_approval_level + 1 }}" onclick="$('#approver_status').val('approved')">
                                                <i class="fas fa-check-circle"></i> Check
                                            </button>

                                            <button type="submit" class="btn btn-sm btn-danger status-btn" data-status="rejected"
                                                data-level="{{ $item->current_approval_level + 1 }}" onclick="$('#approver_status').val('rejected')">
                                                <i class="fas fa-times-circle"></i> Deny
                                            </button>
                                        </div> 
                                    @endif
                                    
                                @endforeach --}}
                                


                                
                                {{-- <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                    <button type="submit" id="paidAll" name="status" value="Paid"
                                        class="btn btn-primary btn-sm paid-all" data-bs-toggle="modal" data-bs-target="#paidAllModal"
                                        formaction="{{ route('hrm.salary-generates.paid-all') }}">Paid All</button>
                                    <button type="submit" id="partiallyPaidAll" name="status" value="Partially Paid"
                                        class="btn btn-warning btn-sm partially-paid" data-bs-toggle="modal" data-bs-target="#partiallyPaidModal"
                                        formaction="{{ route('hrm.salary-generates.partially-paid-all') }}">Partially Paid
                                        All</button>
                                </div> --}} 
                                
                                {{-- @if ($item->status == "Create" && hasPermission('hrm.salary-generates.check-by-department-head'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-sm btn-primary status-btn" 
                                            data-status="department_head_checked" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-check-circle"></i> Department Head Check
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger status-btn" 
                                            data-status="department_head_deny" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-times-circle"></i> Department Head Deny
                                        </button>
                                    </div> 
                                @elseif ($item->status == "department_head_checked" && hasPermission('hrm.salary-generates.check-by-hr-head'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-sm btn-primary status-btn" 
                                            data-status="hr_head_checked" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-check-circle"></i> HR Check
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger status-btn" 
                                            data-status="hr_head_deny" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-times-circle"></i> HR Deny
                                        </button>
                                    </div> 
                                @elseif ($item->status == "hr_head_checked" && hasPermission('hrm.salary-generates.check-by-admin-head'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-sm btn-primary status-btn" 
                                            data-status="admin_head_checked" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-check-circle"></i> Admin Check
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger status-btn" 
                                            data-status="admin_head_deny" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-times-circle"></i> Admin Deny
                                        </button>
                                    </div>
                                @elseif ($item->status == "admin_head_checked" && hasPermission('hrm.salary-generates.check-by-account-head'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-sm btn-primary status-btn" 
                                            data-status="account_head_checked" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-check-circle"></i> A/C Check
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger status-btn" 
                                            data-status="account_head_deny" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-times-circle"></i> A/C Deny
                                        </button>
                                    </div>
                                @elseif ($item->status == "account_head_checked" && hasPermission('hrm.salary-generates.check-by-ceo'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-sm btn-primary status-btn" 
                                            data-status="ceo_checked" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-check-circle"></i> CEO Check
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger status-btn" 
                                            data-status="ceo_deny" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-times-circle"></i> CEO Deny
                                        </button>
                                    </div>
                                @elseif ($item->status == "ceo_checked" && hasPermission('hrm.salary-generates.check-by-md'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-sm btn-primary status-btn" 
                                            data-status="md_checked" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-check-circle"></i> Managing Director Check
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger status-btn" 
                                            data-status="md_deny" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-times-circle"></i> Managing Director Deny
                                        </button>
                                    </div>
                                @elseif ($item->status == "md_checked" && hasPermission('hrm.salary-generates.check-by-chairman'))
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-sm btn-primary status-btn" 
                                            data-status="chairman_checked" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-check-circle"></i> Chairman Check
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger status-btn" 
                                            data-status="chairman_deny" onclick="$('#status').val(this.dataset.status)">
                                            <i class="fas fa-times-circle"></i> Chairman Deny
                                        </button>
                                    </div>
                                @endif --}}
                                    
                            </div>
                        </div>

                    </div> 
            </div>

            <div class="d-none">
                <form class="delete-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            

            <!-- Create Modal -->
            <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">

                        <div class="modal-header" id="createModalLabel">
                            <h5 class="modal-title">Salary Generate</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-hidden="true"></button>
                        </div>
                        <form action="{{ route('hrm.salary-generates.store') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <label class="col-sm-12 col-form-label">Department</label>
                                    <div class="col-sm-12">
                                        <select class="form-select tom-select" name="department_id">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-sm-12 col-form-label">Year Month</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="year_month" class="form-control flatmonth"
                                            value="{{ date('Y-m') }}" required>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">
                                    </span>&nbsp;<span class="nav-icon fa fa-cog"></span>Generate
                                    </span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
            aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">

                        <div class="modal-header" id="editModalLabel">
                            <h5 class="modal-title">Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form action="" method="post" id="editFrom">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <label for="credit_account_id" class="col-sm-12 col-form-label">Paymentable Account <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select required="required" name="credit_account_id"
                                            id="credit_account_id" class="form-control tom-select required"
                                            data-placeholder="- Select Account -">
                                            <option></option>
                                            @foreach ($accounts as $id => $value)
                                                <option value="{{ $value->id }}">
                                                    {{ $value->account_with_group}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="paymentable_amount" class="col-sm-12 col-form-label">Paymentable Amount</label>
                                    <div class="col-sm-12">
                                        <input name="paymentable_amount" id="paymentable_amount" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="amount" class="col-sm-12 col-form-label">Paid amount</label>
                                    <div class="col-sm-12">
                                        <input name="amount" id="amount" class="form-control" type="number" required>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Paid</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade inputForm-modal" id="paidModal" tabindex="-1" role="dialog"
            aria-labelledby="paidModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">

                        <div class="modal-header" id="paidModalLabel">
                            <h5 class="modal-title">Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form action="" method="post" id="paidFrom">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <label for="credit_account_id" class="col-sm-12 col-form-label">Paymentable Account <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select required="required" name="credit_account_id"
                                            id="credit_account_id" class="form-control tom-select required"
                                            data-placeholder="- Select Account -">
                                            <option></option>
                                            @foreach ($accounts as $id => $value)
                                                <option value="{{ $value->id }}">
                                                    {{ $value->account_with_group}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="amount" class="col-sm-12 col-form-label">Paid amount</label>
                                    <div class="col-sm-12">
                                        <input name="amount" id="amount" class="form-control" type="number" readonly>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Paid</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade inputForm-modal" id="partiallyPaidModal" tabindex="-1" role="dialog" aria-labelledby="partiallyPaidModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="partiallyPaidModalLabel">Partially Paid</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>

                        </div>
                        <form id="partiallyPaidForm">
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <label for="credit_account_id" class="col-sm-12 col-form-label">Paymentable Account <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select required="required" name="credit_account_id"
                                            id="credit_account_id" class="form-control tom-select required"
                                            data-placeholder="- Select Account -" required>
                                            <option></option>
                                            @foreach ($accounts as $id => $value)
                                                <option value="{{ $value->id }}">
                                                    {{ $value->account_with_group}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                <input type="hidden" id="partiallyPaidIds" name="ids">
                                <div class="form-group">
                                    <label for="partiallyPaidAmount">Amount:</label>
                                    <input type="number" class="form-control" id="partiallyPaidAmount" name="amount" required>
                                </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Paid</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add hidden input for Paid All IDs in the Paid All Modal -->
            <div class="modal fade inputForm-modal" id="paidAllModal" tabindex="-1" role="dialog" aria-labelledby="paidAllModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paidAllModalLabel">Paid All</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form id="paidAllForm">
                            <div class="modal-body">
                                <input type="hidden" id="paidAllIds" name="ids"> <!-- Added hidden input for IDs -->
                                <div class="row mb-4">
                                    <label for="paidAllCreditAccountId" class="col-sm-12 col-form-label"> <!-- Updated ID -->
                                        Paymentable Account <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <select required="required" name="credit_account_id" 
                                                id="paidAllCreditAccountId" class="form-control tom-select required"
                                                data-placeholder="- Select Account -" required>
                                            <option></option>
                                            @foreach ($accounts as $id => $value)
                                                <option value="{{ $value->id }}">
                                                    {{ $value->account_with_group}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Paid</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection
<!-- CONTENT AREA -->
@section('page_scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <script>
        $(document).ready(function(e) {
           $(document).on('click', '.btn-edit', function() {
               $('#editModal #paymentable_amount').val($(this).data('paymentable_amount'));
               $("#editModal #editFrom").attr("action", $(this).data('action'));

           });
           $('#editModal #editFrom').submit(function(e) {
            var paymentableAmount = parseFloat($('#editModal #paymentable_amount').val());
            var amount = parseFloat($('#editModal #amount').val());
            var credit_account_id = $('#editModal #credit_account_id').val();
            if (credit_account_id == '') {
                toastr.error('Please select account');
                e.preventDefault();
            }
            if (amount > paymentableAmount) {
                toastr.error('Paid amount cannot be greater than paymentable amount');
                $('#editModal #amount').val(paymentableAmount);
                e.preventDefault();
            }
        });
       });
    </script>
    <script>
        $(document).ready(function(e) {
           $(document).on('click', '.btn-paid', function() {  
                      
               $('#paidModal #amount').val($(this).data('amount'));
               $("#paidModal #paidFrom").attr("action", $(this).data('action'));

           });
           $('#paidModal #paidFrom').submit(function(e) {
            var amount = parseFloat($('#paidModal #amount').val());
            var credit_account_id = $('#paidModal #credit_account_id').val();
            if (credit_account_id == '') {
                toastr.error('Please select account');
                e.preventDefault();
            }
            if (amount == '') {
                toastr.error('Please enter amount');
                e.preventDefault();
            }
        });
       });
    </script>
    <script>
        $(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Toggle check/uncheck all checkboxes
    $('#checkAll').click(function() {
        $('.checkBoxClass').prop('checked', $(this).is(':checked'));
    });

    // Handle Paid and Partially Paid buttons
    $('#paidAll').click(function(event) {
            event.preventDefault();
            
            var ids = [];
            $('.checkBoxClass:checked').each(function() {
                ids.push($(this).val());
            });
            
            if (ids.length === 0) {
                toastr.error('Please select at least one salary to update.');
                return;
            }

            $('#paidAllIds').val(ids.join(','));
            $('#paidAllModal').modal('show');
        });
    $('#paidAllForm').submit(function(event) {
            event.preventDefault();

            var ids = $('#paidAllIds').val().split(',');
            var creditAccountId = $('#paidAllCreditAccountId').val();
            var url = $('#paidAll').attr('formaction');

            if (!creditAccountId) {
                toastr.error('Please select a payment account.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    id: ids,
                    credit_account_id: creditAccountId,
                    status: 'Paid'
                },
                success: function(response) {
                    $('#paidAllModal').modal('hide');
                    localStorage.setItem('successMessage', 'Salaries paid successfully!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    toastr.error('Error: ' + error);
                }
            });
        });
        $('#partiallyPaidAll').click(function(event) {
            event.preventDefault();
            
            var ids = [];
            $('.checkBoxClass:checked').each(function() {
                ids.push($(this).val());
            });
            
            if (ids.length === 0) {
                toastr.error('Please select at least one salary to update.');
                return;
            }

            $('#partiallyPaidIds').val(ids.join(','));
            $('#partiallyPaidModal').modal('show');
        });

    // Handle partially paid modal form submission
    $('#partiallyPaidForm').submit(function(event) {
        event.preventDefault();

        var ids = $('#partiallyPaidIds').val().split(',');
        var amount = $('#partiallyPaidAmount').val();
        var credit_account_id = $('#partiallyPaidForm #credit_account_id').val();
        var url = $('#partiallyPaidAll').attr('formaction');

        if (!credit_account_id) {
            toastr.error('Please select a payment account.');
            return;
        }

        if (!amount || amount <= 0) {
            toastr.error('Please enter a valid amount.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                id: ids,
                status: 'Partially Paid',
                amount: amount,
                credit_account_id: credit_account_id
            },
            success: function(response) {
                localStorage.setItem('successMessage', 'Salaries updated successfully!');
                location.reload();
            },
            error: function(xhr, status, error) {
                toastr.error('Error: ' + error);
            }
        });
    });

    // Show success message if it exists in localStorage
    if (localStorage.getItem('successMessage')) {
        toastr.success(localStorage.getItem('successMessage'));
        localStorage.removeItem('successMessage');
    }
});

    $(document).ready(function(){

       $(document).on('click', '.status-btn', function () {

            let status = $(this).data('status');
            let id = $(this).data('id');
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to " + status + " this request?",
                icon: status === 'approved' ? 'success' : 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'approved' ? '#3085d6' : '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, ' + status + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    // hidden input set
                    $('#approver_status' + id).val(status);

                    // form submit
                    form.submit();
                }
            });
        });

    });



    </script>

{{-- <script>
    $(document).ready(function() {
    // Get CSRF token from meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Toggle check/uncheck all checkboxes
    $('#checkAll').click(function() {
        $('.checkBoxClass').prop('checked', $(this).is(':checked'));
    });

    // Handle Paid and Partially Paid buttons
    $('#paidAll, #partiallyPaidAll').click(function(event) {
        event.preventDefault(); // Prevent form submission

        var ids = [];
        // Collect all checked checkboxes
        $('.checkBoxClass:checked').each(function() {
            ids.push($(this).val());
        });
        var credit_account_id = $('#credit_account_id').val();

        if (ids.length > 0) {
            if ($(this).attr('id') === 'partiallyPaidAll') {
                // Open modal for partially paid
                $('#partiallyPaidModal').modal('show');
                $('#partiallyPaidIds').val(ids.join(','));

            } else {
                var url = $(this).attr('formaction');
                var status = $(this).val();

                // AJAX request to update salaries
                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header
                    },
                    data: {
                        id: ids,
                        credit_account_id: credit_account_id,
                        status: status
                    },
                    success: function(response) {
                        // Store success message in localStorage
                        localStorage.setItem('successMessage',
                            'Salaries updated successfully!');

                        // Reload the page after success
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error: ' + error);
                    }
                });
            }
        } else {
            toastr.error('Please select at least one salary to update.');
        }
    });

    // Handle partially paid modal form submission
    $('#partiallyPaidForm').submit(function(event) {
        event.preventDefault();

        var ids = $('#partiallyPaidIds').val().split(',');
        var amount = $('#partiallyPaidAmount').val();
        var url = $('#partiallyPaidAll').attr('formaction');
        var credit_account_id = $('#partiallyPaidForm #credit_account_id').val();

        // AJAX request to update salaries
        $.ajax({
            type: 'POST',
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header
            },
            data: {
                id: ids,
                status: 'Partially Paid',
                amount: amount,
                credit_account_id: credit_account_id
            },
            success: function(response) {
                // Store success message in localStorage
                localStorage.setItem('successMessage',
                    'Salaries updated successfully!');

                // Reload the page after success
                location.reload();
            },
            error: function(xhr, status, error) {
                toastr.error('Error: ' + error);
            }
        });
    });

    // Show success message if it exists in localStorage
    if (localStorage.getItem('successMessage')) {
        toastr.success(localStorage.getItem('successMessage'));
        localStorage.removeItem('successMessage'); // Remove it after showing
    }
});
</script> --}}

<style>
    thead th {
        position: sticky;
        top: 0; 
    }
    tfoot td {
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 2;
    }

    .highlight-col {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .highlight-col {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    input[type="checkbox"] {
        transform: scale(1.2);
    }
    .rotate-header {
        writing-mode: vertical-rl; /* vertical */
        transform: rotate(225deg); /* optional for direction */
        text-align: center; 
    }
</style>
@endsection
