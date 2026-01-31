<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr><td>Employee Id</td><td>: {{ $loan->employee->employementDetail->card_no ?? '-' }}</td></tr>
                <tr><td>Employee Name</td><td>: {{ $loan->employee->full_name }}</td></tr>
                <tr><td>Department</td><td>: {{ $loan->employee->employementDetail->department->name ?? '-' }}</td></tr>
                <tr><td>Designation</td><td>: {{ $loan->employee->employementDetail->designation->name ?? '-' }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr><td>Loan Amount</td><td>: {{ number_format($loan->amount) }}</td></tr>
                <tr><td>Start Month</td><td>: {{ $loan->start_month }}</td></tr>
                <tr><td>Monthly Reduction</td><td>: {{ number_format($loan->monthly_reduction) }}</td></tr>
                <tr><td>Balance</td><td>: {{ number_format($loan->remaining_balance) }}</td></tr>
            </table>
        </div>
    </div>

    <div class="mb-3">
        <h6 class="font-weight-bold">Installment Month</h6>
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Sl.</th>
                    <th>Month</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loan->details as $index => $inst)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $inst->payment_month }}</td>
                        <td>{{ number_format($inst->amount) }}</td>
                        <td class="text-success">Paid</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($loan->attachment)
        <div>
            <strong>Emp./Worker Id:</strong> 
            <a href="{{ asset('storage/' . $loan->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary ml-2">
                <i class="fas fa-file-alt"></i> View Document
            </a>
        </div>
    @endif
</div>
