{{-- Modules/HRMS/resources/views/salary-approvals/show.blade.php --}}
@extends('HRMS::layout.app')

@section('title', 'Salary Approval Details')
@section('description', 'Salary Approval Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Salary Approval Request Details</h3>
                    <a href="{{ route('hrm.salary-approvals.verification') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Verification
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Request ID:</th>
                                    <td>#{{ $salaryApprovalRequest->id }}</td>
                                </tr>
                                <tr>
                                    <th>Employee Name:</th>
                                    <td>
                                        @if($salaryApprovalRequest->salaryGenerate && $salaryApprovalRequest->salaryGenerate->employee)
                                            {{ $salaryApprovalRequest->salaryGenerate->employee->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Employee ID:</th>
                                    <td>
                                        @if($salaryApprovalRequest->salaryGenerate && $salaryApprovalRequest->salaryGenerate->employee)
                                            {{ $salaryApprovalRequest->salaryGenerate->employee->employee_id ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>
                                        @if($salaryApprovalRequest->salaryGenerate && $salaryApprovalRequest->salaryGenerate->employee && $salaryApprovalRequest->salaryGenerate->employee->department)
                                            {{ $salaryApprovalRequest->salaryGenerate->employee->department->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Designation:</th>
                                    <td>
                                        @if($salaryApprovalRequest->salaryGenerate && $salaryApprovalRequest->salaryGenerate->employee && $salaryApprovalRequest->salaryGenerate->employee->designation)
                                            {{ $salaryApprovalRequest->salaryGenerate->employee->designation->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Salary Month:</th>
                                    <td>
                                        @if($salaryApprovalRequest->salaryGenerate)
                                            {{ $salaryApprovalRequest->salaryGenerate->month ?? 'N/A' }} / {{ $salaryApprovalRequest->salaryGenerate->year ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Submitted By:</th>
                                    <td>{{ $salaryApprovalRequest->creator->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Submitted Date:</th>
                                    <td>{{ $salaryApprovalRequest->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Current Status:</th>
                                    <td>
                                        @if($salaryApprovalRequest->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($salaryApprovalRequest->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Denied</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Current Level:</th>
                                    <td>
                                        <span class="badge badge-info">Level {{ $salaryApprovalRequest->current_level }}</span>
                                    </td>
                                </tr>
                                @if($salaryApprovalRequest->remarks)
                                <tr>
                                    <th>Remarks:</th>
                                    <td>{{ $salaryApprovalRequest->remarks }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Salary Details</h5>
                                </div>
                                <div class="card-body">
                                    @if($salaryApprovalRequest->salaryGenerate)
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Basic Salary:</th>
                                                <td>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->basic_salary ?? 0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>House Rent:</th>
                                                <td>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->house_rent ?? 0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Medical Allowance:</th>
                                                <td>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->medical_allowance ?? 0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Transport Allowance:</th>
                                                <td>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->transport_allowance ?? 0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Other Allowance:</th>
                                                <td>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->other_allowance ?? 0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gross Salary:</th>
                                                <td><strong>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->gross_salary ?? 0, 2) }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Deductions:</th>
                                                <td>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->deductions ?? 0, 2) }}</td>
                                            </tr>
                                            <tr class="table-primary">
                                                <th>Net Salary:</th>
                                                <td><strong>৳ {{ number_format($salaryApprovalRequest->salaryGenerate->net_salary ?? 0, 2) }}</strong></td>
                                            </tr>
                                        </table>
                                    @else
                                        <div class="alert alert-warning">Salary details not available</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($salaryApprovalRequest->status == 'pending')
                    <div class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Your Decision</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{{ route('hrm.salary-approvals.approve', $salaryApprovalRequest) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="approve_remarks">Remarks (Optional):</label>
                                                <textarea name="remarks" id="approve_remarks" class="form-control" rows="3" placeholder="Add your approval remarks..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                                <i class="fas fa-check-circle"></i> Approve Salary
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <form action="{{ route('hrm.salary-approvals.deny', $salaryApprovalRequest) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="deny_remarks">Remarks <span class="text-danger">*</span>:</label>
                                                <textarea name="remarks" id="deny_remarks" class="form-control" rows="3" required placeholder="Please provide reason for denial..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-lg btn-block">
                                                <i class="fas fa-times-circle"></i> Deny Salary
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Approval History</h4>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if($history->isEmpty())
                        <p class="text-muted text-center">No approval history yet.</p>
                    @else
                        <div class="timeline">
                            @foreach($history as $item)
                                <div class="timeline-item mb-3">
                                    <div class="timeline-badge {{ $item['status'] == 'approved' ? 'bg-success' : ($item['status'] == 'denied' ? 'bg-danger' : 'bg-warning') }}">
                                        <i class="fas fa-{{ $item['status'] == 'approved' ? 'check' : ($item['status'] == 'denied' ? 'times' : 'clock') }}"></i>
                                    </div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h5 class="timeline-title">
                                                Level {{ $item['level'] }}: {{ ucfirst($item['status']) }}
                                            </h5>
                                            <p><strong>{{ $item['signatory_name'] }}</strong> ({{ $item['signatory_tag'] }})</p>
                                            @if($item['actioned_at'])
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    {{ \Carbon\Carbon::parse($item['actioned_at'])->format('d M Y, h:i A') }}
                                                </small>
                                            @endif
                                        </div>
                                        @if($item['remarks'])
                                            <div class="timeline-body mt-2">
                                                <strong>Remarks:</strong>
                                                <p class="mb-0">{{ $item['remarks'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-badge {
    position: absolute;
    left: -30px;
    top: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    text-align: center;
    line-height: 25px;
    color: white;
}
.timeline-badge i {
    font-size: 12px;
    line-height: 25px;
}
.timeline-panel {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}
.timeline-title {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: bold;
}
</style>
@endpush