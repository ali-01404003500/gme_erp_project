@section('title', 'Salary Generate Details')
@section('description', 'Salary Generate Details')
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
                                        {{ trans('Salary Generate Details') }}</li>
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Salary Generate Details') }}</h4>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <select name="employee_id" id="employee_id" class="form-select tom-select"
                                                    data-placeholder="Select Employee">
                                                    <option value=""></option>
                                                    @foreach ($employees as $key => $value)
                                                        <option {{ request('employee_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ optional($value)->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="department_id" id="department_id"
                                                    class="form-select tom-select" data-placeholder="Select Department">
                                                    <option value=""> </option>
                                                    @foreach ($departments as $key => $value)
                                                        <option
                                                            {{ request('department_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="year_month"
                                                    class="form-control input-sm flatmonth"
                                                    value="{{ request('year_month') }}">
                                            </td>
                                            <td colspan="5" class="text-right">
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
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salaryGenerates])' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
                                            <th class="text-center" style="width: 8%">Sl</th>
                                            <th class="text-center">Employee</th>
                                            <th class="text-center">Department</th>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">Net Salary</th>
                                            <th class="text-center">Deduction</th>
                                            <th class="text-center">Due Amount</th>
                                            <th class="text-center">Pay Date</th> 
                                            <th class="text-center no-content">Is paid</th>
                                            <th class="text-center no-content" >Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @csrf
                                        @foreach ($salaryGenerates as $key => $item)
                                            <tr>
                                                <td>
                                                    @if ($item->status == 'UnPaid' || $item->status == 'Partially Paid' && $item->net_earning - $item->salaryGeneratePayments->sum('amount') != 0 )
                                                        <input type="checkbox" name="id[]" value="{{ $item->id }}" class="checkBoxClass">
                                                    @else
                                                        <input type="checkbox" disabled>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $key + 1 }}</td>
                                                <td class="text-center">
                                                    @if ($item->status == 'UnPaid' || $item->status == 'Partially Paid')
                                                        <a href="{{ route('hrm.salary-generates.edit', $item->id) }}" target="_blank">{{ $item->employee->full_name }}</a>
                                                    @else
                                                        <a href="{{ route('hrm.salary-generates.show', $item->id) }}" target="_blank">{{ $item->employee->full_name }}</a>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ optional(optional(optional($item->employee)->employementDetail)->department)->name }}</td>
                                                <td class="text-center">{{ date('F', strtotime($item->year_month)) }}</td>
                                                <td class="text-center">{{ date('Y', strtotime($item->year_month)) }}</td>
                                                <td class="text-center">{{ numberFormat($item->net_earning) }}</td>
                                                <td class="text-center">{{ numberFormat($item->total_deductions) }}</td>
                                                <td class="text-center">
                                                    @if ($item->status == 'Partially Paid' && ($item->net_earning - $item->salaryGeneratePayments->sum('amount')) > 0)
                                                        {{ numberFormat($item->net_earning - $item->total_deductions - $item->salaryGeneratePayments->sum('amount')) ?? 0 }}
                                                    @elseif ($item->status == 'UnPaid')
                                                        {{ numberFormat($item->net_earning - $item->total_deductions) ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td>
                                                <td class="text-center">{{ $item->pay_date }}</td>
                                                <td class="text-center">
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
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                        @if ($item->status == 'UnPaid')
                                                            @if (hasPermission('hrm.salary-generates.update'))
                                                                <a href="{{ route('hrm.salary-generates.edit', $item->id) }}" class="btn btn-edit btn-outline-warning" title="Edit">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                            @endif
                                                            {{-- @if (hasPermission('hrm.salary-generates.destroy'))
                                                                <button type="button" data-action="{{ route('hrm.salary-generates.destroy', $item->id) }}" class="btn btn-outline-danger delete-confirm" title="Delete">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </button>
                                                            @endif --}}
                                                        @endif
                                                        @if (hasPermission('hrm.salary-generates.show'))
                                                            <a href="{{ route('hrm.salary-generates.show', $item->id) }}" class="btn btn-outline-primary" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            
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
                        <div class="card-footer">
                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <button type="submit" id="paidAll" name="status" value="Paid"
                                    class="btn btn-primary btn-sm paid-all" data-bs-toggle="modal" data-bs-target="#paidAllModal"
                                    formaction="{{ route('hrm.salary-generates.paid-all') }}">Paid All</button>
                                <button type="submit" id="partiallyPaidAll" name="status" value="Partially Paid"
                                    class="btn btn-warning btn-sm partially-paid" data-bs-toggle="modal" data-bs-target="#partiallyPaidModal"
                                    formaction="{{ route('hrm.salary-generates.partially-paid-all') }}">Partially Paid
                                    All</button>
                            </div>
                        </div>
                    </div>

                </div>
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
                                                id="paidAllCreditAccountId" class="form-control tom-select required" <!-- Updated ID -->
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
@endsection
