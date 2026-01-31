@extends('layout.app')

@section('title', 'Monthly KPI Appraisal Details')
@section('description', 'View Monthly KPI Appraisal Details')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hrm.kpis.monthly-kpi-appraisals.index') }}">Monthly KPI Appraisals</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Appraisal Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                            <i class="fa fa-list"></i> Back to List
                        </a>
                        @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.edit') && ($appraisal->status == 'Draft' || $appraisal->status == 'Submitted'))
                            <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.edit', $appraisal->id) }}"
                                class="btn btn-info btn-default btn-squared radius-md shadow2 btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 m-2">
            <h4 class="text-capitalize breadcrumb-title">Monthly KPI Appraisal Details</h4>
            <x-error-alart />
        </div>

        <div class="card mb-50">
            <div class="card-body p-30">
                <!-- Employee Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3 border-bottom pb-2">Employee Information</h5>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Employee Name:</th>
                                <td>{{ $appraisal->employee->full_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Employee ID:</th>
                                <td>{{ $appraisal->employee->employementDetail->card_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Branch:</th>
                                <td>{{ $appraisal->employee->employementDetail->branch->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Department:</th>
                                <td>{{ $appraisal->employee->employementDetail->department->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Designation:</th>
                                <td>{{ $appraisal->employee->employementDetail->designation->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Assessment Month:</th>
                                <td>{{ \Carbon\Carbon::parse($appraisal->assessment_month)->format('F Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                

                <!-- Status Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3 border-bottom pb-2">Appraisal Status</h5>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Status:</th>
                                <td>
                                    @if($appraisal->status == 'Draft')
                                        <span class="badge badge-round bg-secondary">Draft</span>
                                    @elseif($appraisal->status == 'Submitted')
                                        <span class="badge badge-round bg-warning">Submitted</span>
                                    @elseif($appraisal->status == 'Approved')
                                        <span class="badge badge-round bg-success">Approved</span>
                                    @elseif($appraisal->status == 'Rejected')
                                        <span class="badge badge-round bg-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $appraisal->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($appraisal->submitted_at)
                            <tr>
                                <th width="40%">Submitted At:</th>
                                <td>{{ \Carbon\Carbon::parse($appraisal->submitted_at)->format('d M Y, h:i A') }}</td>
                            </tr>
                            @endif
                            @if($appraisal->approved_at)
                            <tr>
                                <th>Approved At:</th>
                                <td>{{ \Carbon\Carbon::parse($appraisal->approved_at)->format('d M Y, h:i A') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- KPI Appraisal Details -->
                <div class="table-responsive mt-4">
                    <h5 class="mb-3 border-bottom pb-2">KPI Appraisal Details</h5>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">SL</th>
                                <th width="30%">Key Responsibilities</th>
                                <th width="10%">Target Days (T)</th>
                                <th width="10%">Actual Days (A)</th>
                                <th width="15%">KPI Score (%)</th>
                                                                <th width="10%">Weight</th>

                                <th width="15%">Performance Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalPerformanceScore = 0; @endphp
                            @foreach ($appraisal->details as $index => $detail)
                                @php $totalPerformanceScore += $detail->performance_score ?? 0; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="max-width: 200px; word-wrap: break-word; white-space: normal;">{{ $detail->responsibility->description ?? 'N/A' }}</td>
                                    <td class="text-center">{{ number_format($detail->target_days) }}</td>
                                    <td class="text-center">{{ number_format($detail->actual_days ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($detail->kpi_score ?? 0) }}</td>
                                                                        <td class="text-center">{{ number_format($detail->weight) }}</td>

                                    <td class="text-center">{{ number_format($detail->performance_score ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="6" class="text-end">Total Performance Score:</th>
                                <th class="text-center">{{ number_format($totalPerformanceScore) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Score Management -->
                <div class="table-responsive mt-4">
                    <h5 class="mb-3 border-bottom pb-2">Score Management on Performance Appraisal</h5>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Score On</th>
                                <th width="15%">Weight</th>
                                <th width="15%">Achieved Score</th>
                                <th width="45%">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Performance Score</td>
                                <td class="text-center">70</td>
                                <td class="text-center"><strong>{{ number_format($appraisal->achieved_performance_score) }}</strong></td>
                                <td>{{ $appraisal->performance_score_note ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Succession Management</td>
                                <td class="text-center">20</td>
                                <td class="text-center"><strong>{{ number_format($appraisal->succession_management_score ?? 0) }}</strong></td>
                                <td>{{ $appraisal->succession_management_note ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Behavioral Performance</td>
                                <td class="text-center">10</td>
                                <td class="text-center"><strong>{{ number_format($appraisal->behavioral_performance_score ?? 0) }}</strong></td>
                                <td>{{ $appraisal->behavioral_performance_note ?? 'N/A' }}</td>
                            </tr>
                            <tr class="table-secondary">
                                <td><strong>Aggregate Score</strong></td>
                                <td class="text-center"><strong>100</strong></td>
                                <td class="text-center">
                                    <strong class="fs-16">
                                        @php
                                            $aggregateScore = ($appraisal->achieved_performance_score ?? 0) + 
                                                            ($appraisal->succession_management_score ?? 0) + 
                                                            ($appraisal->behavioral_performance_score ?? 0);
                                        @endphp
                                        {{ number_format($aggregateScore) }}
                                    </strong>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Remarks -->
                    @php
                        $score = $aggregateScore;
                        $suggestion = \Modules\HRMS\Models\Kpi\ScoreWiseSuggestion::getSuggestionByScore($score);
                        $remarks = $suggestion ? $suggestion->getFormattedRemarks() : 'No remarks available';
                    @endphp
                    <div class="alert alert-info mt-3">
                        <strong>Remarks:</strong> {{ $remarks }}
                    </div>
                </div>

                <!-- Notes -->
                @if($appraisal->notes)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="mb-3 border-bottom pb-2">Additional Notes</h5>
                        <div class="alert alert-secondary">
                            {{ $appraisal->notes }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.approve') && $appraisal->status == 'Submitted')
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="{{ route('hrm.kpis.monthly-kpi-appraisals.approve', $appraisal->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-squared btn-sm"
                                    onclick="return confirm('Are you sure you want to approve this appraisal?')">
                                    <i class="fa fa-check"></i> Approve
                                </button>
                            </form>

                            <form action="{{ route('hrm.kpis.monthly-kpi-appraisals.reject', $appraisal->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-squared btn-sm"
                                    onclick="return confirm('Are you sure you want to reject this appraisal?')">
                                    <i class="fa fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection