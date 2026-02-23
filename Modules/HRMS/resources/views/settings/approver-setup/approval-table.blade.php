@if(isset($currentApprovers) && count($currentApprovers) > 0)
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="80" class="text-center">Hierarchy</th>
                    <th>Approver Code</th>
                    <th>Approver Name</th>
                    <th>Designation</th>
                    <th width="100" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($currentApprovers as $approver)
                <tr>
                    <td class="text-center">
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            Level {{ $approver->hierarchy_level }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $approver->approver->epf_number ?? 'N/A' }}</strong>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-light-primary me-2">
                                {{ strtoupper(substr($approver->approver->full_name, 0, 1)) }}
                            </div>
                            {{ $approver->approver->full_name }}
                        </div>
                    </td>
                    <td>{{ $approver->approver->designation ?? 'N/A' }}</td>
                    <td class="text-center">
                        <i class="fas fa-trash-alt text-danger delete-approver" 
                           style="cursor: pointer; font-size: 18px; transition: all 0.2s;"
                           data-approver-id="{{ $approver->approver_id }}"
                           data-employee-id="{{ $approver->employee_id }}"
                           title="Remove Approver"
                           onmouseover="this.style.transform='scale(1.1)'"
                           onmouseout="this.style.transform='scale(1)'"></i>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="mt-2 text-muted small">
            <i class="fas fa-info-circle me-1"></i>
            Total {{ count($currentApprovers) }} approver(s) assigned
        </div>
    </div>
@else
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="fas fa-users-slash fa-4x text-muted opacity-50"></i>
        </div>
        <h6 class="text-muted">No Approvers Assigned</h6>
        <p class="text-muted small mb-0">Select an employee and add approvers from above</p>
    </div>
@endif

@push('styles')
<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    background-color: #e7f1ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    color: #0d6efd;
}

.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-weight: 500;
    font-size: 12px;
}

@media (max-width: 768px) {
    .table {
        font-size: 14px;
    }
    
    .avatar-circle {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
}
</style>
@endpush
