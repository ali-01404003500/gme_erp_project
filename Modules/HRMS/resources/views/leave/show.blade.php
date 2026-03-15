 
    <div class="container-fluid">
        <div class="social-dash-wrap">
            
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-leaves-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <table class="table table-bordered mb-4">
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4">Employee Information</td>
                                </tr>
                                 
                                <tr>
                                    <td>Employee Code</td>
                                    <td>{{ $leave->employee->employementDetail->card_no ?? '-' }}</td>
                                    <td>Employee Name</td>
                                    <td>{{ $leave->employee->full_name ?? '-' }}</td>
                                </tr> 
                                <tr>
                                    <td>Job Status</td>
                                    <td> 
                                        @php
                                            $employmentTypes = [
                                                1 => 'Casual',
                                                2 => 'Contractual',
                                                3 => 'Not Defined',
                                                4 => 'Permanent',
                                                5 => 'Probationary',
                                                6 => 'Suspended',
                                                7 => 'Trainee',
                                            ];

                                            $currentStatus = $leave->employee->status ?? null;
                                        @endphp

                                        <span>
                                            {{ $employmentTypes[$currentStatus] ?? '' }}
                                        </span>
                                        
                                    </td>
                                    <td>Designation</td>
                                    <td>{{ $leave->employee->employementDetail->designation->name ?? '-' }}</td>
                                </tr> 
                                <tr>
                                    <td>Branch</td>
                                    <td>{{ $leave->employee->employementDetail->branch->name ?? '-' }}</td>
                                    <td>Department</td>
                                    <td>{{ $leave->employee->employementDetail->department->name ?? '-' }}</td>
                                </tr>

                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4">Application Details</td>
                                </tr>
                                <tr>
                                    <td>Leave Type</td>
                                    <td>{{ $leave->leaveType->leave_type_name ?? '-' }}</td>
                                    <td>Leave Applied On</td>
                                    <td>{{ $leave->created_at->format('d-m-Y') }}</td>
                                </tr>
                                 
                                <tr>
                                    <td>From Date</td>
                                    <td>{{ $leave->from_date }}</td>
                                    <td>To Date</td>
                                    <td>{{ $leave->to_date }}</td>
                                </tr>
                                <tr>
                                    <td>Day(s)</td>
                                    <td>{{ $leave->total_days ?? 1 }}</td>
                                    <td>Status</td>
                                    <td>
                                        @if($leave->status == 'approved')
                                            <span class="text-success">Approved</span>
                                        @elseif($leave->status == 'rejected')
                                            <span class="text-danger">Rejected</span>
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                </tr> 
                                <tr>
                                    <td>Extra Working Days</td>
                                    <td>{{ $leave->extra_days ?? 'N/A' }}</td>
                                    <td></td>
                                    <td></td>
                                </tr> 
                                <tr>
                                    <td>Remarks</td>
                                    <td colspan="3">{{ $leave->remarks }}</td>
                                </tr>

                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4">Approver Actions</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        @foreach($leave->approvals as $approval)  
                                            Approver {{ $approval->level }}: 
                                                {{ $approval->approver->full_name ?? 'N/A' }} - 
                                                <strong>{{ ucfirst($approval->status) }}</strong><br>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 