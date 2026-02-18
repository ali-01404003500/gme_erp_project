@extends('layout.app')

@section('title', 'Cash Transfer Details')
@section('description', 'View cash transfer details')
@section('page-header')
<i class="fa fa-eye"></i> Cash Transfer Details
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
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Transfer Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Status</th>
                                <td>
                                    @if($cashTransfer->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($cashTransfer->status == 'confirmed')
                                        <span class="badge badge-success">Confirmed</span>
                                    @else
                                        <span class="badge badge-danger">{{ ucfirst($cashTransfer->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>From Employee</th>
                                <td>{{ $cashTransfer->fromEmployee->full_name ?? '' }}
                                    ({{ $cashTransfer->fromEmployee->employee_id ?? '' }})</td>
                            </tr>
                            <tr>
                                <th>To Employee</th>
                                <td>{{ $cashTransfer->toEmployee->full_name ?? '' }}
                                    ({{ $cashTransfer->toEmployee->employee_id ?? '' }})</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ number_format($cashTransfer->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Transfer Date</th>
                                <td>{{ $cashTransfer->transfer_date }}</td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>{{ $cashTransfer->remarks }}</td>
                            </tr>
                            @if($cashTransfer->status == 'confirmed')
                                <tr>
                                    <th>Received Amount</th>
                                    <td>{{ number_format($cashTransfer->received_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Cash Matched</th>
                                    <td>{{ $cashTransfer->is_cash_count_matched ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                @if($cashTransfer->status == 'pending')
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Confirm Receipt</h5>
                                </div>
                                <?php 
                                            $currentUserEmployee = \Modules\HRMS\Models\Employee::where('user_id', auth()->id())->first();
                    $isReceiver = $currentUserEmployee && $currentUserEmployee->id == $cashTransfer->to_employee_id;
                                        ?>
                                <div class="card-body">

                                    @if($isReceiver)
                                        <form action="{{ route('account.cash-transfers.confirm', $cashTransfer->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="received_amount">Received Amount</label>
                                                <input type="number" name="received_amount" id="received_amount" class="form-control"
                                                    step="0.01" required value="{{ old('received_amount', $cashTransfer->amount) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Cash Count Matched?</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_cash_count_matched" id="match_yes"
                                                        value="1" checked>
                                                    <label class="form-check-label" for="match_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_cash_count_matched" id="match_no"
                                                        value="0">
                                                    <label class="form-check-label" for="match_no">No</label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-success btn-block w-100 mt-3">Confirm Receipt</button>
                                        </form>
                                    @else
                                        <div class="alert alert-info">
                                            Only the receiver ({{ $cashTransfer->toEmployee->full_name ?? 'Employee' }}) can confirm this
                                            transfer.
                                        </div>
                                    @endif
                                </div>
                            </div>
                @endif
            </div>
        </div>
    </div>
@endsection