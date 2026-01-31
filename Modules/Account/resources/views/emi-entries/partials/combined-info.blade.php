<div class="row">
    <div class="col-md-4">
        <p><strong>Customer Name</strong> : {{ $emiEntry->customer->company_name }}</p>
        <p><strong>Invoice ID</strong> : {{ @$emiEntry->salesOrder->sales_order_id }}</p>
        <p><strong>Principal Amount</strong> : {{ number_format($emiEntry->emiDetails->sum('principal_amount')) }}</p>
        <p><strong>Interest Rate</strong> : {{ $emiEntry->interest_rate }}%</p>
    </div>
    <div class="col-md-4">
        <p><strong>Customer Phone</strong> : {{ $emiEntry->customer->phone }}</p>
        <p><strong>EMI Date</strong> : {{ \Carbon\Carbon::parse($emiEntry->emi_date)->format('d-M-Y') }}</p>
        <p><strong>Interest Amount</strong> : {{ number_format($emiEntry->emiDetails->sum('interest_amount')) }}</p>
        <p><strong>Paid Tenure</strong> : <span
                class="text-success">{{ $emiEntry->emiDetails->where('status', 'paid')->count() ?? 0 }}</span></p>
    </div>
    <div class="col-md-4">
        <p><strong>Company Address</strong> : {{ $emiEntry->customer->address }}</p>
        <p><strong>Tenure No</strong> : {{ $emiEntry->tenure_no }} {{ $emiEntry->tenure_type }}</p>
        <p><strong>Paid Amount</strong> : <span
                class="text-success">{{ number_format($emiEntry->emiDetails->where('status', 'paid')->sum('emi_amount'), 2) }}</span>
        </p>
        <p><strong>Due Amount</strong> : <span
                class="text-danger">{{ number_format($emiEntry->emiDetails->where('status', 'due')->sum('emi_amount'), 2) }}</span>
        </p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-4">
        <p><strong>Due Tenure</strong> : <span
                class="text-danger">{{ $emiEntry->emiDetails->where('status', 'due')->count() ?? 0 }}</span></p>
    </div>
    <div class="col-md-4">
        <p><strong>Disbursement By</strong> : {{ $emiEntry->createdBy->name ?? 'N/A' }}</p>
    </div>
    <div class="col-md-4">
        <p><strong>Disbursement Date</strong> : {{ \Carbon\Carbon::parse($emiEntry->created_at)->format('d-M-Y') }}</p>
    </div>
</div>
