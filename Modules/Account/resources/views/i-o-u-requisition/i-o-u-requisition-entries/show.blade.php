<!-- resources/views/i-o-u-requisition/i-o-u-requisition-entries/show.blade.php -->
{{-- @dd( $iOURequisitionEntry) --}}
@section('title', 'View IOU Requisition')
@section('description', 'View IOU requisition details')

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
                                <li class="breadcrumb-item"><a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.index') }}">{{ trans('menu.iou-requisition-list') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.view-iou-requisition') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center gap-2">
                            <a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="las la-arrow-left fs-16"></i> Back
                            </a>
                            <button onclick="printIOU()" class="btn btn-primary btn-sm">
                                <i class="las la-print fs-16"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.view-iou-requisition') }}</h4>
            </div>
            {{-- @dd( $iOURequisitionEntry) --}}
            <div class="col-md-12">
                <div class="card" id="printableArea">
                    <div class="card-body">
                        <div class="text-center mb-4 border-bottom">
                            <h3>IOU Requisition</h3>
                            <h5><strong>#{{ $iOURequisitionEntry->id }}</strong></h5>
                            <span class="badge bg-{{$iOURequisitionEntry->status}} text-white">{{ $iOURequisitionEntry->status }}</span>
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>Date</strong></td>
                                <td>{{ $iOURequisitionEntry->date->format('d M, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type</strong></td>
                                <td><span class="badge badge-round bg-primary">{{ $iOURequisitionEntry->type }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Employee Name</strong></td>
                                <td>{{ $iOURequisitionEntry->employee->full_name }}</td>
                            </tr> 
                            <tr>
                                <td><strong>Request Amount</strong></td>
                                <td>৳{{ number_format($iOURequisitionEntry->request_amount) }}</td>
                            </tr>
                             
                            <tr>
                                <td><strong>Verify Amount</strong></td>
                                <td>৳{{ number_format($iOURequisitionEntry->verify_amount) }}</td>
                            </tr>
                             
                            <tr>
                                <td><strong>Approved Amount</strong></td>
                                <td>৳{{ number_format($iOURequisitionEntry->approved_amount) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Remarks</strong></td>
                                <td>{{ $iOURequisitionEntry->remarks ?: '—' }}</td>
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