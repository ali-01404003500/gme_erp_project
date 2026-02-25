<!-- resources/views/funs-transfers/show.blade.php --> 
@section('title', 'View Fund Transfer')
@section('description', 'View Fund Transfer')

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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('account.fund-transfers.index') }}">{{ trans('menu.fund-transfer-list') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.edit-fund-transfers') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center gap-2">
                            <a href="{{ route('account.fund-transfers.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="las la-arrow-left fs-16"></i> Back
                            </a>
                            <button onclick="printFundTransfer()" class="btn btn-primary btn-sm">
                                <i class="las la-print fs-16"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.view-fund-transfer') }}</h4>
            </div>
            {{-- @dd( $fundTransfer) --}}
            <div class="col-md-12">
                <div class="card" id="printableArea">
                    <div class="card-body">
                        <div class="text-center mb-4 border-bottom">
                            <h3>Fund Transfer</h3>
                            <h5><strong>#{{ $fundTransfer->id }}</strong></h5> 
                        </div> 

                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>Transfer Type</strong></td> 
                                <td> 
                                    @php
                                        if($fundTransfer->transfer_type=="bank_to_bank")
                                            $transferType = "Bank to Bank";
                                        else if($fundTransfer->transfer_type=="bank_to_cash")
                                            $transferType = "Bank to Cash";
                                        else if($fundTransfer->transfer_type=="cash_to_bank")
                                            $transferType = "Cash to Bank";
                                        else if($fundTransfer->transfer_type=="bkash_to_bank")
                                            $transferType = "Bkash to Bank";
                                        else
                                            $transferType = "";
                                    @endphp
                                    
                                    {{ $transferType }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Transfer Date</strong></td>
                                <td>{{ $fundTransfer->transfer_date; }}</td>
                            </tr>
                            <tr>
                                <td><strong>Sender</strong></td>
                                <td>{{ $fundTransfer->transferFromBankAccount->account_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Receiver</strong></td>
                                <td>{{ $fundTransfer->transferToBankAccount->account_name }}</td>
                            </tr> 
                            <tr>
                                <td><strong>Amount</strong></td>
                                <td>৳{{ number_format($fundTransfer->amount) }}</td>
                            </tr>
                              
                            <tr>
                                <td><strong>Cheque Date</strong></td>
                                <td>{{ $fundTransfer->cheque_date }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cheque No</strong></td>
                                <td>{{ $fundTransfer->cheque_no }}</td>
                            </tr> 

                            <tr>
                                <td><strong>Remarks</strong></td>
                                <td>{{ $fundTransfer->remarks ?: '—' }}</td>
                            </tr> 

                            <tr>
                                <td><strong>Create By</strong></td>
                                <td>{{ $fundTransfer->createdBy->name  ?: '—' }}</td>
                            </tr>
                              
                            <tr>
                                <td><strong>Verify By</strong></td>
                                <td>{{ $fundTransfer->verifyBy?->name  ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Approve By</strong></td>
                                <td>{{ $fundTransfer->approveBy?->name  ?: '—' }}</td>
                            </tr>  
 
                        </table>

                        <div class="mt-5 text-center text-muted small">
                            <p>Generated on: {{ now()->format('d M, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printableArea, #printableArea * { visibility: visible; }
    #printableArea { position: absolute; left: 0; top: 0; width: 100%; padding: 20px; }
    .breadcrumb-main, .action-btn, footer, .btn { display: none !important; }
}
</style>

@endsection

@section('page_scripts')
<script>
function printIOU() { window.print(); }
</script>
@endsection