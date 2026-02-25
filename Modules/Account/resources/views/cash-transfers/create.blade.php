
@extends('layout.app')

@section('title', 'Create Cash Transfer')
@section('description', 'Initiate a new cash transfer')
@section('page-header')
    <i class="fa fa-plus-circle"></i> Create Cash Transfer
@stop

@section('content')
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('account.cash-transfers.index') }}">Cash Transfers</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <x-error-alart />
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('account.cash-transfers.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="from_employee_id">From Employee (Sender)</label>
                                    @if(hasPermission('supper_admin'))
                                        <select name="from_employee_id" id="from_employee_id" class="form-control tom-select" required>
                                            <option value="">Select Sender</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ (old('from_employee_id') ?? ($currentEmployee->id ?? '')) == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" name="from_employee_id" id="from_employee_id" value="{{ $currentEmployee->id ?? '' }}">
                                        <input type="text" class="form-control" value="{{ $currentEmployee->full_name ?? Auth::user()->name }} (Current User)" readonly>
                                        <!-- @if(isset($currentEmployee) && $currentEmployee->getAccount())
                                            <small class="text-info">Current Balance: {{ number_format($currentEmployee->getAccount()->balance, 2) }}</small>
                                        @endif -->
                                    @endif
                                    @error('from_employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div id="current_balance_container" style="display: none;">
                                        <small class="text-info">Current Balance: <span id="from_employee_balance">0.00</span></small>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="to_employee_id">To Employee (Receiver)</label>
                                    <select name="to_employee_id" id="to_employee_id" class="form-control tom-select" required>
                                        <option value="">Select Receiver</option>
                                        @foreach($employees as $employee)
                                            @if(isset($currentEmployee) && $employee->id == $currentEmployee->id) @continue @endif
                                            <option value="{{ $employee->id }}" {{ old('to_employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="transfer_date">Transfer Date</label>
                                    <input type="text" name="transfer_date" id="transfer_date" class="form-control flatdate" value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                                    @error('transfer_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 text-right d-flex justify-content-end gap-1">
                                <a href="{{ route('account.cash-transfers.index') }}" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary">Initiate Transfer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            // Function to fetch balance
            function fetchBalance(employee_id) {
                if (employee_id) {
                    $.ajax({
                        url: "{{ route('account.get-ballance') }}",
                        type: 'GET',
                        data: { account_id: employee_id, type: 'employee' },
                        success: function(response) {
                            $('#from_employee_balance').text(response.balance);
                            $('#current_balance_container').show();
                        }
                    });
                } else {
                    $('#current_balance_container').hide();
                }
            }

            // Trigger on change
            $('#from_employee_id').on('change', function() {
                fetchBalance($(this).val());
            });

            // Trigger on load if value exists
            var initial_id = $('#from_employee_id').val();
            if (initial_id) {
                fetchBalance(initial_id);
            }
        });
    </script>
@endsection
