@extends('layout.app')

@section('title', 'Edit Cash Transfer')
@section('description', 'Edit pending cash transfer')
@section('page-header')
<i class="fa fa-edit"></i> Edit Cash Transfer
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
                                <li class="breadcrumb-item"><a href="{{ route('account.cash-transfers.index') }}">Cash
                                        Transfers</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('account.cash-transfers.update', $cashTransfer->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" id="transfer_status" value="{{ $cashTransfer->status }}">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>From Employee (Sender)</label>
                                    <input type="text" class="form-control"
                                        value="{{ $cashTransfer->fromEmployee->full_name ?? '' }}" readonly disabled>
                                    <div id="current_balance_container" style="display: none;">
                                        <small class="text-info">Current Balance: <span
                                                id="from_employee_balance">0.00</span></small>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>To Employee (Receiver)</label>
                                    <input type="text" class="form-control"
                                        value="{{ $cashTransfer->toEmployee->full_name ?? '' }}" readonly disabled>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                                        min="0.01" value="{{ old('amount', $cashTransfer->amount) }}" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="transfer_date">Transfer Date</label>
                                    <input type="date" name="transfer_date" id="transfer_date" class="form-control"
                                        value="{{ old('transfer_date', $cashTransfer->transfer_date) }}" required>
                                    @error('transfer_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control"
                                        rows="3">{{ old('remarks', $cashTransfer->remarks) }}</textarea>
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-end gap-1">
                                <a href="{{ route('account.cash-transfers.index') }}" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Transfer</button>
                                @if(request()->get('for') == 'confirm')
                                    <button type="button" id="confirm_save_btn" class="btn btn-success">Confirm and
                                        Save</button>
                                @endif
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
        $(document).ready(function () {
            var employee_id = "{{ $cashTransfer->from_employee_id }}";
            if (employee_id) {
                $.ajax({
                    url: "{{ route('account.get-ballance') }}",
                    type: 'GET',
                    data: { account_id: employee_id, type: 'employee' },
                    success: function (response) {
                        $('#from_employee_balance').text(response.balance);
                        $('#current_balance_container').show();
                    }
                });
            }

            // Confirm and Save button logic
            $('#confirm_save_btn').on('click', function () {
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to confirm and save this transfer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, confirm it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#transfer_status').val('confirmed');
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection