{{-- Modules/HRMS/Resources/views/settings/approver-setup/approval-table.blade.php --}}
@if(isset($currentApprovers) && count($currentApprovers) > 0)
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="80" class="text-center">Level</th>
                    <th>EPF Number</th>
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
                            <i class="fas fa-level-up-alt me-1"></i>
                            Level {{ $approver->hierarchy_level }}
                        </span>
                    </td>
                    <td>
                        <span class="fw-bold">{{ $approver->approver->epf_number ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-2">
                                {{ strtoupper(substr($approver->approver->full_name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ $approver->approver->full_name ?? 'Unknown' }}</div>
                                @if($approver->approver->epf_number)
                                <small class="text-muted">{{ $approver->approver->epf_number }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $approver->approver->designation ?? 'N/A' }}</td>
                    <td class="text-center">
                        <i class="fas fa-trash-alt text-danger delete-approver" 
                           style="cursor: pointer; font-size: 18px; transition: all 0.2s;"
                           data-approver-id="{{ $approver->approver_id }}"
                           data-employee-id="{{ $approver->employee_id }}"
                           title="Remove Approver"></i>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Total:</strong> {{ count($currentApprovers) }} approver(s) assigned
            </div>
            <div class="text-muted small">
                <i class="fas fa-sort-numeric-up me-1"></i>
                <strong>Hierarchy Levels:</strong> 1 to {{ count($currentApprovers) }}
            </div>
        </div>
    </div>
@else
    <div class="text-center py-5">
        <div class="mb-4">
            <div class="position-relative d-inline-block">
                <i class="fas fa-users-slash fa-5x text-muted opacity-25"></i>
                @if(isset($hasEmployeeSelected) && $hasEmployeeSelected)
                <i class="fas fa-plus-circle text-primary position-absolute bottom-0 end-0" style="font-size: 30px;"></i>
                @endif
            </div>
        </div>
        <h5 class="text-muted mb-3">No Approvers Assigned</h5>
        <p class="text-muted mb-3">
            @if(isset($hasEmployeeSelected) && $hasEmployeeSelected)
                This employee doesn't have any approvers configured yet.
                <br>Use the search box above to find and add approvers.
            @else
                Select an employee from the dropdown above to view and manage their approvers.
            @endif
        </p>
        
        @if(isset($hasEmployeeSelected) && $hasEmployeeSelected)
        <div class="d-flex justify-content-center gap-2">
            <button class="btn btn-outline-primary" id="focusSearchBtn">
                <i class="fas fa-search me-2"></i>Search Approvers
            </button>
            <div class="text-muted small align-self-center">
                <i class="fas fa-arrow-up ms-2"></i>
            </div>
        </div>
        @else
        <div class="text-muted small">
            <i class="fas fa-arrow-up me-2"></i>
            Select an employee above to get started
        </div>
        @endif
    </div>
@endif