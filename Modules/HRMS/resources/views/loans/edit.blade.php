@section('title', 'Edit Loan/Advance')
@section('description', 'Edit Loan/Advance')
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
                                <li class="breadcrumb-item active" aria-current="page">Edit Loan/Advance</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if (hasPermission('hrm.loans.index'))
                        <a href="{{ route('hrm.loans.index') }}"
                           class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                           <i class="fa fa-list"></i> List</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Heading -->
        <div class="row">
            <div class="col-md-12 m-2">
                <h4 class="text-capitalize breadcrumb-title">Edit Loan / Advance</h4>
                <x-error-alart />
            </div>

            <!-- Form Card -->
            <div class="card mb-50">
                <div class="row justify-content-center" id="justify-content-center">
                    <div class="col-sm-12">
                        <div class="mt-40 mb-50 p-30">
                            <form action="{{ route('hrm.loans.update', [$loan->id, app()->getLocale()]) }}" method="POST" id="loanForm">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <!-- Employee -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label for="employee_id" class="color-dark fs-14 fw-500 align-center">
                                                Employee Name <span class="text-danger">*</span>
                                            </label>
                                            <select name="employee_id" id="employee_id"
                                                class="form-control tom-select" required>
                                                <option value="">Select Employee</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ old('employee_id', $loan->employee_id) == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label for="amount" class="color-dark fs-14 fw-500 align-center">
                                                Loan/Advance Amount <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control" name="amount" id="amount"
                                                   value="{{ old('amount', $loan->amount) }}" required min="1">
                                            @error('amount')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Payment Date -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label for="payment_date" class="color-dark fs-14 fw-500 align-center">
                                                Payment Date <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control flatdate" name="payment_date"
                                                   id="payment_date" value="{{ old('payment_date', $loan->payment_date) }}" required>
                                            @error('payment_date')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Start Month -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label for="start_month" class="color-dark fs-14 fw-500 align-center">
                                                Reduction Start Month <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control flatmonth" name="start_month"
                                                   id="start_month" value="{{ old('start_month', $loan->start_month) }}" required>
                                            @error('start_month')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Duration -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label for="duration" class="color-dark fs-14 fw-500 align-center">
                                                Duration (Months) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="duration" id="duration"
                                                   class="form-control" required min="1"
                                                   value="{{ old('duration', $loan->duration) }}">
                                            @error('duration')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Monthly Reduction -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">
                                                Monthly Reduction
                                            </label>
                                            <input type="text" name="monthly_reduction" class="form-control" id="monthly_reduction"
                                                value="{{ old('monthly_reduction', $loan->monthly_reduction) }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Remaining Balance -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-25">
                                            <label class="color-dark fs-14 fw-500 align-center">
                                                Remaining Balance
                                            </label>
                                            <input type="text" name="remaining_balance" class="form-control"
                                                value="{{ old('remaining_balance', $loan->remaining_balance) }}" id="remaining_balance" readonly>
                                        </div>
                                    </div>

                                </div>

                                <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                    <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                            Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container -->
</div>
@endsection

@section('page_scripts')
<script>
    function calculateLoan() {
        let amount = parseFloat(document.getElementById('amount').value) || 0;
        let duration = parseInt(document.getElementById('duration').value) || 0;

        let monthly = duration > 0 ? (amount / duration).toFixed() : 0;
        document.getElementById('monthly_reduction').value = monthly;
        document.getElementById('remaining_balance').value = amount.toFixed();
    }

    document.getElementById('amount').addEventListener('input', calculateLoan);
    document.getElementById('duration').addEventListener('input', calculateLoan);
</script>
@endsection
