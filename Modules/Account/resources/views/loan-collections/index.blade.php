@extends('layout.app')

@section('title', 'Loan Collection')
@section('description', 'Loan Collection')

@section('page-head')

<style>

    .summary-card {
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 5px;
        background: #fff;
    }

    .summary-title {
        font-size: 13px;
        color: #777;
    }

    .summary-value {
        font-size: 20px;
        font-weight: 600;
    }

    .amount-link {
        cursor: pointer;
        font-weight: 600;
    }

</style>

@endsection


@section('content')

<div class="container-fluid">

    <div class="social-dash-wrap">


        {{-- Breadcrumb --}}

        <div class="row">

            <div class="col-lg-12">

                <div class="breadcrumb-main">

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">
                                <a href="/">
                                    <i class="las la-home"></i>
                                    Home
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Loan Collection
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- Title --}}

        <div class="row">

            <div class="col-md-12"
                 style="padding-bottom:20px">

                <h4 class="breadcrumb-title">
                    Loan Collection
                </h4>

            </div>

        </div>


        {{-- Summary --}}

        <div class="row">

            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Pending Collection
                    </div>

                    <div class="summary-value">

                        {{ number_format($pendingAmount, 2) }}

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Submitted
                    </div>

                    <div class="summary-value">

                        {{ number_format($submittedAmount, 2) }}

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Checked
                    </div>

                    <div class="summary-value">

                        {{ number_format($checkedAmount, 2) }}

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card">

                    <div class="summary-title">
                        Approved
                    </div>

                    <div class="summary-value">

                        {{ number_format($approvedAmount, 2) }}

                    </div>

                </div>

            </div>

        </div>


        <br>


        {{-- Filter --}}

        <div class="card">

            <div class="card-body">

                <form method="GET">

                    <div class="row">

                        <div class="col-md-4">

                            <label>
                                Employee
                            </label>

                            <select name="employee_id"
                                    class="form-control tom-select">

                                <option value="">
                                    All Employees
                                </option>

                                @foreach($employees as $employee)

                                    <option value="{{ $employee->id }}"
                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>

                                        {{ $employee->full_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>
                        <div class="col-md-2">

                            <label>Month</label>

                            <input type="month"
                                name="month"
                                class="form-control"
                                value="{{ request('month', $month) }}">

                        </div>


                        <div class="col-md-2">

                            <label>
                                Status
                            </label>

                            <select name="status" class="form-control">
                                <option value=""> All </option>
                                <option value="pending"  {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>
                                    Submitted
                                </option>
                                <option value="checked" {{ request('status') == 'checked' ? 'selected' : '' }}>
                                    Checked
                                </option>
                                <option value="approved"  {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    Approved
                                </option>
                            </select>

                        </div>


                        <div class="col-md-2" style="padding-top:30px">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>

                            <a href="{{ request()->url() }}"  class="btn btn-warning">
                                <i class="fa fa-refresh"></i>Refresh
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>


        {{-- Collection Table --}}

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped">

                        <thead>

                            <tr>

                                <th class="text-center">
                                    SL
                                </th>

                                <th>
                                    Employee
                                </th>

                                <th>
                                    Installment
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th class="text-right">
                                    Amount
                                </th>

                                <th class="text-right">
                                    Paid
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($payments as $payment)

                                <tr>

                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>
                                        {{ $payment->employee->full_name ?? '' }}
                                    </td>


                                    <td>
                                        #{{ $payment->installment_no }}
                                    </td>


                                    <td>
                                        {{ $payment->due_date?->format('Y-m-d') }}
                                    </td>


                                    <td class="text-right">

                                        {{ number_format($payment->amount, 2) }}

                                    </td>


                                    <td class="text-right">

                                        {{ number_format($payment->paid_amount, 2) }}

                                    </td>


                                    <td>

                                        @if($payment->status == 'pending')

                                            <span class="badge badge-warning">
                                                Pending
                                            </span>

                                        @elseif($payment->status == 'submitted')

                                            <span class="badge badge-info">
                                                Submitted
                                            </span>

                                        @elseif($payment->status == 'checked')

                                            <span class="badge badge-primary">
                                                Checked
                                            </span>

                                        @elseif($payment->status == 'approved')

                                            <span class="badge badge-success">
                                                Approved
                                            </span>

                                        @elseif($payment->status == 'paid')

                                            <span class="badge badge-success">
                                                Paid
                                            </span>

                                        @elseif($payment->status == 'rejected')

                                            <span class="badge badge-danger">
                                                Rejected
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center">


                                        {{-- Pending --}}

                                        @if($payment->status == 'pending')

                                            <button type="button"
                                                    class="btn btn-xs btn-primary collect-btn"

                                                    data-id="{{ $payment->id }}"

                                                    data-employee="{{ $payment->employee->full_name ?? '' }}"

                                                    data-installment="{{ $payment->installment_no }}"

                                                    data-due-date="{{ $payment->due_date?->format('Y-m-d') }}"

                                                    data-amount="{{ $payment->amount }}">

                                                <i class="fa fa-money"></i>
                                                Collect

                                            </button>


                                        {{-- Submitted --}}

                                        @elseif($payment->status == 'submitted')  

                                            <form method="POST"  action="{{ route('account.loan-collections.check',['loanPayment' => $payment->id]) }}"  style="display:inline">
                                                @csrf
                                                <button type="submit"class="btn btn-xs btn-info">
                                                    Check
                                                </button>
                                            </form>


                                        {{-- Checked --}}

                                        @elseif($payment->status == 'checked')
                                            <form method="POST"  action="{{ route('account.loan-collections.approve', ['loanPayment' => $payment->id]) }}"  style="display:inline">
                                                @csrf
                                                <button type="submit"class="btn btn-xs btn-success">
                                                    Approve
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        No loan collection found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Collection Modal --}}

<div class="modal fade"
     id="collectionModal"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog"
         role="document">

        <div class="modal-content">


            <div class="modal-header">

                <h4 class="modal-title">
                    Loan Collection
                </h4>

                <button type="button"
                        class="close"
                        data-dismiss="modal" id="closeCollectionModal">

                    <span>
                        &times;
                    </span>

                </button>

            </div>


            <form method="POST"
                  id="collectionForm">

                @csrf

                <div class="modal-body">


                    <div class="row">

                        <div class="col-md-6">

                            <label>
                                Employee
                            </label>

                            <input type="text"
                                   id="modal_employee"
                                   class="form-control"
                                   readonly>

                        </div>


                        <div class="col-md-6">

                            <label>
                                Installment
                            </label>

                            <input type="text"
                                   id="modal_installment"
                                   class="form-control"
                                   readonly>

                        </div>

                    </div>


                    <br>


                    <div class="row">

                        <div class="col-md-6">

                            <label>
                                Due Date
                            </label>

                            <input type="text"
                                   id="modal_due_date"
                                   class="form-control"
                                   readonly>

                        </div>


                        <div class="col-md-6">

                            <label>
                                Due Amount
                            </label>

                            <input type="text"
                                   id="modal_due_amount"
                                   class="form-control"
                                   readonly>

                        </div>

                    </div>


                    <br>


                    <div class="row">

                        <div class="col-md-6">

                            <label>
                                Paid Amount
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="paid_amount"
                                   id="modal_paid_amount"
                                   step="0.01"
                                   min="0.01"
                                   class="form-control"
                                   required>

                        </div>


                        <div class="col-md-6">

                            <label>
                                Payment Date
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   name="payment_date"
                                   value="{{ date('Y-m-d') }}"
                                   class="form-control flatdate"
                                   required>

                        </div>

                    </div>


                    <br>


                    <div class="row">

                        <div class="col-md-6">

                            <label>
                                Payment Method
                                <span class="text-danger">*</span>
                            </label>

                            <select name="payment_method"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Select
                                </option>

                                <option value="cash">
                                    Cash
                                </option>

                                <option value="bank">
                                    Bank
                                </option>

                                <option value="mobile_banking">
                                    Mobile Banking
                                </option>

                                <option value="cheque">
                                    Cheque
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label>
                                Reference No
                            </label>

                            <input type="text"
                                   name="reference_no"
                                   class="form-control">

                        </div>

                    </div>


                    <br>


                    <div>

                        <label>
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  class="form-control"
                                  rows="3"></textarea>

                    </div>


                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-default"
                            data-dismiss="modal" id="closeCollectionModalFooter">

                        Close

                    </button>


                    <button type="submit"
                            class="btn btn-primary">

                        Submit Collection

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection



@section('page_scripts')

<script>
$(document).on('click', '#closeCollectionModal, #closeCollectionModalFooter', function () {
    $('#collectionModal').modal('hide');
});

$(document).on('click', '.collect-btn', function () {
    let id = $(this).data('id');
    let employee = $(this).data('employee');
    let installment = $(this).data('installment');
    let dueDate = $(this).data('due-date');
    let amount = parseFloat( $(this).data('amount') ) || 0;

    /*
    |--------------------------------------------------------------------------
    | Fill Modal
    |--------------------------------------------------------------------------
    */

    $('#modal_employee').val(employee);
    $('#modal_installment').val('#' + installment);
    $('#modal_due_date').val(dueDate);
    $('#modal_due_amount').val(amount.toFixed(2)  );
    $('#modal_paid_amount').val(amount);

    /*
    |--------------------------------------------------------------------------
    | Set Form Action
    |--------------------------------------------------------------------------
    */

    let url = "{{ route('account.loan-collections.collect', ':id') }}";
    url = url.replace(':id', id);

    $('#collectionForm').attr('action', url);

    /*
    |--------------------------------------------------------------------------
    | Open Modal
    |--------------------------------------------------------------------------
    */

    $('#collectionModal').modal('show');

});

</script>

@endsection