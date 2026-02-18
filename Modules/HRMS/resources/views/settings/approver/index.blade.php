@extends('layout.app')
@section('content')
<style>
    /* Global Styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f8f9fa;
    }
    
    .container-fluid {
        padding: 15px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Employee Header Card */
    .employee-card {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .employee-card .card-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 2px solid #dee2e6;
        border-radius: 8px 8px 0 0;
    }
    
    .employee-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #495057;
        font-size: 1.1rem;
    }
    
    .employee-card .card-body {
        padding: 25px;
    }
    
    /* Employee Input Section */
    .employee-input-wrapper {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    
    .input-group-custom {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }
    
    .input-field-group {
        flex: 1;
        min-width: 250px;
    }
    
    .input-field-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }
    
    .input-field-group .required::after {
        content: " *";
        color: #dc3545;
    }
    
    .employee-search-container {
        position: relative;
        width: 100%;
    }
    
    .employee-input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: white;
    }
    
    .employee-input:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    
    .employee-input.error {
        border-color: #dc3545;
    }
    
    .search-icon-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 5px 10px;
    }
    
    .search-icon-btn:hover {
        color: #007bff;
    }
    
    /* Employee Search Results */
    .employee-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 6px 6px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .employee-search-results.show {
        display: block;
    }
    
    .employee-result-item {
        padding: 12px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f1f1f1;
        transition: background-color 0.2s;
    }
    
    .employee-result-item:hover {
        background-color: #f8f9fa;
    }
    
    .employee-result-item:last-child {
        border-bottom: none;
    }
    
    .employee-result-item .employee-code {
        font-weight: 600;
        color: #007bff;
        margin-right: 10px;
    }
    
    .employee-result-item .employee-name {
        color: #495057;
    }
    
    .employee-result-item .employee-designation {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }
    
    .no-results {
        padding: 15px;
        text-align: center;
        color: #6c757d;
        font-style: italic;
    }
    
    /* Main Card */
    .main-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-top: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .main-card .card-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 2px solid #dee2e6;
        border-radius: 8px 8px 0 0;
    }
    
    .main-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .main-card .card-body {
        padding: 25px;
    }
    
    /* Set Approver Section */
    .approver-section {
        background-color: #f8f9fa;
        padding: 25px;
        border-radius: 6px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }
    
    .approver-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 10px;
        font-size: 1rem;
    }
    
    .approver-label .required {
        color: #dc3545;
        margin-left: 3px;
    }
    
    .approver-search-wrapper {
        position: relative;
        width: 100%;
    }
    
    .approver-search-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .approver-search-input:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    
    .approver-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 6px 6px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .approver-search-results.show {
        display: block;
    }
    
    .approver-result-item {
        padding: 12px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .approver-result-item:hover {
        background-color: #f8f9fa;
    }
    
    .approver-result-item .approver-code {
        font-weight: 600;
        color: #28a745;
        margin-right: 10px;
    }
    
    .approver-result-item .approver-name {
        color: #495057;
    }
    
    .approver-result-item .approver-designation {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }
    
    /* Buttons */
    .btn-add-approver {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-add-approver:hover {
        background-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40,167,69,0.2);
    }
    
    .btn-add-approver:active {
        transform: translateY(0);
    }
    
    .btn-add-approver i {
        font-size: 1.1rem;
    }
    
    .btn-add-approver:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    /* Table Styles */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        overflow: hidden;
    }
    
    .table {
        width: 100%;
        margin-bottom: 0;
        background-color: white;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 15px;
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
        white-space: nowrap;
    }
    
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.95rem;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Badge */
    .office-badge {
        background-color: #17a2b8;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        align-items: center;
    }
    
    .action-link {
        font-size: 1.2rem;
        cursor: default;
        color: #6c757d;
    }
    
    .action-edit {
        color: #007bff;
        cursor: pointer;
        transition: color 0.2s;
        font-size: 1.1rem;
    }
    
    .action-edit:hover {
        color: #0056b3;
    }
    
    .action-delete {
        color: #dc3545;
        cursor: pointer;
        transition: color 0.2s;
        font-size: 1.1rem;
    }
    
    .action-delete:hover {
        color: #c82333;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #dee2e6;
    }
    
    .empty-state p {
        margin: 0;
        font-size: 1rem;
    }
    
    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
    }
    
    .modal-header .modal-title {
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .modal-body .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    
    .modal-body .form-control {
        border: 2px solid #dee2e6;
        padding: 10px 15px;
        font-size: 0.95rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .modal-body .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-radius: 0 0 8px 8px;
    }
    
    .modal-footer .btn {
        padding: 10px 25px;
        font-size: 0.95rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
    
    .modal-footer .btn-secondary:hover {
        background-color: #5a6268;
    }
    
    .modal-footer .btn-primary {
        background-color: #007bff;
        border: none;
    }
    
    .modal-footer .btn-primary:hover {
        background-color: #0069d9;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 10px;
        }
        
        .employee-card .card-body,
        .main-card .card-body {
            padding: 15px;
        }
        
        .input-field-group {
            min-width: 100%;
        }
        
        .input-group-custom {
            flex-direction: column;
            gap: 15px;
        }
        
        .btn-add-approver {
            padding: 10px 20px;
        }
        
        .approver-section {
            padding: 15px;
        }
        
        .table thead th {
            padding: 12px 10px;
            font-size: 0.85rem;
        }
        
        .table tbody td {
            padding: 12px 10px;
            font-size: 0.85rem;
        }
        
        .action-group {
            gap: 8px;
        }
        
        .office-badge {
            padding: 4px 10px;
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .employee-card .card-header h5,
        .main-card .card-header h5 {
            font-size: 1rem;
        }
        
        .approver-label {
            font-size: 0.9rem;
        }
        
        .employee-input,
        .approver-search-input {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            min-width: 600px;
        }
        
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-body {
            padding: 15px;
        }
        
        .modal-footer .btn {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
    }
    
    /* Loading Spinner */
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 300px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .toast-content {
        padding: 15px 20px;
        border-radius: 6px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .toast-content.success {
        background-color: #28a745;
    }
    
    .toast-content.error {
        background-color: #dc3545;
    }
    
    .toast-content.warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .toast-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0 5px;
    }
</style>

<div class="container-fluid">
    <!-- Employee Information Card -->
    <div class="employee-card">
        <div class="card-header">
            <h5>
                <i class="fas fa-user-circle me-2"></i>
                Approver Setup For Employee
            </h5>
        </div>
        <div class="card-body">
            <div class="employee-input-wrapper">
                <div class="input-group-custom">
                    <div class="input-field-group">
                        <label class="required">Employee Name</label>
                        <div class="employee-search-container" style="max-width: 350px;">
                            <input type="text" 
                                   class="employee-input" 
                                   id="employeeNameInput"
                                   placeholder="Type employee name or code..."
                                   autocomplete="off">
                            <button class="search-icon-btn" type="button" id="searchEmployeeBtn">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="employee-search-results" id="employeeSearchResults"></div>
                        </div>
                    </div>
                    <div class="input-field-group">
                        <label>Office Location</label>
                        <input type="text" class="employee-input" id="officeLocation" value="Head Office" readonly>
                    </div>
                    <div class="input-field-group">
                        <label>Employee Code</label>
                        <input type="text" class="employee-input" id="employeeCode" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card - Approve List -->
    <div class="main-card">
        <div class="card-header">
            <h5>
                <i class="fas fa-list-alt me-2"></i>
                Approve List
                <span class="office-badge ms-auto">
                    <i class="fas fa-building me-1"></i>Head Office
                </span>
            </h5>
        </div>
        <div class="card-body">
            <!-- Set Approver Section -->
            <div class="approver-section">
                <div class="row">
                    <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
                        <div class="approver-label">
                            Set Approver
                            <span class="required">*</span>
                        </div>
                        <div class="approver-search-wrapper">
                            <input type="text" 
                                   class="approver-search-input" 
                                   id="approverSearch"
                                   placeholder="Select Employee by Code or Name"
                                   autocomplete="off">
                            <div class="approver-search-results" id="approverSearchResults"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <div class="approver-label">&nbsp;</div>
                        <button class="btn-add-approver" id="addToListBtn" disabled>
                            <i class="fas fa-plus-circle"></i>
                            Add to List
                        </button>
                    </div>
                </div>
            </div>

            <!-- Approvers Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="10%">Hierarchy</th>
                            <th width="20%">Approver Code</th>
                            <th width="30%">Approver Name</th>
                            <th width="30%">Designation</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="approversTableBody">
                        @forelse($supervisors as $supervisor)
                        <tr data-id="{{ $supervisor->id }}" 
                            data-code="{{ $supervisor->employee_code }}"
                            data-name="{{ $supervisor->employee_name }}"
                            data-designation="{{ $supervisor->designation }}">
                            <td>{{ $supervisor->hierarchy_level }}</td>
                            <td><strong>{{ $supervisor->employee_code }}</strong></td>
                            <td>{{ $supervisor->employee_name }}</td>
                            <td>{{ $supervisor->designation }}</td>
                            <td>
                                <div class="action-group">
                                    <span class="action-link">🔗</span>
                                    <i class="fas fa-edit action-edit" title="Edit"></i>
                                    <i class="fas fa-trash-alt action-delete" title="Delete"></i>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>No approvers found. Add your first approver above.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="approverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-plus-circle"></i>
                    Add Approver
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approverForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="approverId">
                    
                    <div class="mb-3">
                        <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_employee_code" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_employee_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_designation" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" class="form-control" id="modal_department">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Office Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_office_location" value="Head Office" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Hierarchy Level <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="modal_hierarchy_level" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // State Management
        let state = {
            selectedEmployee: null,
            selectedApprover: null,
            isLoading: false,
            employeeSearchTimeout: null,
            approverSearchTimeout: null
        };

        // Mock Employee Data (Replace with actual API call)
        const mockEmployees = [
            { code: 'GME746', name: 'Mahamud Hosen', designation: 'Manager', department: 'IT', office: 'Head Office' },
            { code: 'GME760', name: 'Nafis Ul Haque', designation: 'Senior Manager', department: 'Sales', office: 'Head Office' },
            { code: 'GME755', name: 'Rafiq Ahmed', designation: 'Deputy Manager', department: 'HR', office: 'Head Office' },
            { code: 'GME770', name: 'Shahinur Rahman', designation: 'Assistant Manager', department: 'Marketing', office: 'Head Office' },
            { code: 'GME780', name: 'Farhana Islam', designation: 'Executive', department: 'Finance', office: 'Head Office' }
        ];

        // ==================== EMPLOYEE SEARCH ====================
        function searchEmployees(searchTerm) {
            if (searchTerm.length < 2) {
                $('#employeeSearchResults').removeClass('show').empty();
                return;
            }

            state.isLoading = true;
            
            // Simulate API call - Replace with actual AJAX call
            setTimeout(() => {
                const filtered = mockEmployees.filter(emp => 
                    emp.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    emp.code.toLowerCase().includes(searchTerm.toLowerCase())
                );
                
                displayEmployeeResults(filtered);
                state.isLoading = false;
            }, 300);
        }

        function displayEmployeeResults(employees) {
            const resultsDiv = $('#employeeSearchResults');
            resultsDiv.empty();

            if (employees.length === 0) {
                resultsDiv.append('<div class="no-results">No employees found</div>');
            } else {
                employees.forEach(emp => {
                    resultsDiv.append(`
                        <div class="employee-result-item" 
                             data-code="${emp.code}"
                             data-name="${emp.name}"
                             data-designation="${emp.designation}"
                             data-department="${emp.department}"
                             data-office="${emp.office}">
                            <span class="employee-code">${emp.code}</span>
                            <span class="employee-name">${emp.name}</span>
                            <div class="employee-designation">${emp.designation}</div>
                        </div>
                    `);
                });
            }

            resultsDiv.addClass('show');
        }

        // Employee Search Event Handlers
        $('#employeeNameInput').on('input', function() {
            const searchTerm = $(this).val();
            
            if (state.employeeSearchTimeout) {
                clearTimeout(state.employeeSearchTimeout);
            }
            
            state.employeeSearchTimeout = setTimeout(() => {
                searchEmployees(searchTerm);
            }, 300);
        });

        $('#searchEmployeeBtn').click(function() {
            const searchTerm = $('#employeeNameInput').val();
            if (searchTerm.length >= 2) {
                searchEmployees(searchTerm);
            }
        });

        // Select Employee from results
        $(document).on('click', '.employee-result-item', function(e) {
            e.preventDefault();
            
            state.selectedEmployee = {
                code: $(this).data('code'),
                name: $(this).data('name'),
                designation: $(this).data('designation'),
                department: $(this).data('department'),
                office: $(this).data('office')
            };
            
            $('#employeeNameInput').val(state.selectedEmployee.name);
            $('#employeeCode').val(state.selectedEmployee.code);
            $('#officeLocation').val(state.selectedEmployee.office);
            
            $('#employeeSearchResults').removeClass('show');
            
            showToast('success', `Employee ${state.selectedEmployee.name} selected`);
        });

        // ==================== APPROVER SEARCH ====================
        function searchApprovers(searchTerm) {
            if (searchTerm.length < 2) {
                $('#approverSearchResults').removeClass('show').empty();
                return;
            }

            state.isLoading = true;
            
            // Simulate API call - Replace with actual AJAX call
            $.ajax({
                url: '{{ route("hrm.settings.supervisors.search.employees") }}',
                type: 'GET',
                data: { q: searchTerm },
                success: function(data) {
                    displayApproverResults(data);
                    state.isLoading = false;
                },
                error: function() {
                    // Fallback to mock data if API fails
                    const filtered = mockEmployees.filter(emp => 
                        emp.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        emp.code.toLowerCase().includes(searchTerm.toLowerCase())
                    );
                    displayApproverResults(filtered);
                    state.isLoading = false;
                }
            });
        }

        function displayApproverResults(approvers) {
            const resultsDiv = $('#approverSearchResults');
            resultsDiv.empty();

            if (approvers.length === 0) {
                resultsDiv.append('<div class="no-results">No approvers found</div>');
            } else {
                approvers.forEach(app => {
                    resultsDiv.append(`
                        <div class="approver-result-item" 
                             data-code="${app.code}"
                             data-name="${app.name}"
                             data-designation="${app.designation}">
                            <span class="approver-code">${app.code}</span>
                            <span class="approver-name">${app.name}</span>
                            <div class="approver-designation">${app.designation}</div>
                        </div>
                    `);
                });
            }

            resultsDiv.addClass('show');
        }

        // Approver Search Event Handlers
        $('#approverSearch').on('input', function() {
            const searchTerm = $(this).val();
            
            if (state.approverSearchTimeout) {
                clearTimeout(state.approverSearchTimeout);
            }
            
            state.approverSearchTimeout = setTimeout(() => {
                searchApprovers(searchTerm);
            }, 300);
        });

        // Select Approver from results
        $(document).on('click', '.approver-result-item', function(e) {
            e.preventDefault();
            
            state.selectedApprover = {
                code: $(this).data('code'),
                name: $(this).data('name'),
                designation: $(this).data('designation')
            };
            
            $('#approverSearch').val(`${state.selectedApprover.name} (${state.selectedApprover.code})`);
            $('#addToListBtn').prop('disabled', false);
            
            $('#approverSearchResults').removeClass('show');
        });

        // ==================== ADD TO LIST ====================
        $('#addToListBtn').click(function() {
            if (!state.selectedApprover) {
                showToast('warning', 'Please select an approver first');
                return;
            }

            if (!state.selectedEmployee) {
                showToast('warning', 'Please select an employee first');
                return;
            }

            // Get next hierarchy level
            const nextLevel = $('#approversTableBody tr').length + 1;
            
            // Populate modal
            $('#modal_employee_code').val(state.selectedApprover.code);
            $('#modal_employee_name').val(state.selectedApprover.name);
            $('#modal_designation').val(state.selectedApprover.designation);
            $('#modal_hierarchy_level').val(nextLevel);
            $('#modal_office_location').val(state.selectedEmployee.office || 'Head Office');
            
            $('#modalTitle').html('<i class="fas fa-plus-circle"></i> Add Approver');
            $('#approverId').val('');
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('approverModal'));
            modal.show();
            
            // Reset selected approver
            state.selectedApprover = null;
            $('#approverSearch').val('');
            $('#addToListBtn').prop('disabled', true);
        });

        // ==================== FORM SUBMIT ====================
        $('#approverForm').submit(function(e) {
            e.preventDefault();
            
            const id = $('#approverId').val();
            const url = id ? `/supervisors/${id}` : '{{ route("hrm.settings.supervisors.store") }}';
            const method = id ? 'PUT' : 'POST';
            
            const formData = {
                employee_code: $('#modal_employee_code').val(),
                employee_name: $('#modal_employee_name').val(),
                designation: $('#modal_designation').val(),
                department: $('#modal_department').val(),
                office_location: $('#modal_office_location').val(),
                hierarchy_level: $('#modal_hierarchy_level').val()
            };
            
            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<span class="spinner"></span> Saving...').prop('disabled', true);
            
            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#approverModal').modal('hide');
                        showToast('success', 'Approver saved successfully');
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Something went wrong';
                    showToast('error', message);
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });

        // ==================== EDIT APPROVER ====================
        $(document).on('click', '.action-edit', function() {
            const row = $(this).closest('tr');
            
            $('#modal_employee_code').val(row.data('code'));
            $('#modal_employee_name').val(row.data('name'));
            $('#modal_designation').val(row.data('designation'));
            $('#modal_hierarchy_level').val(row.find('td:first').text());
            $('#modal_office_location').val('Head Office');
            $('#approverId').val(row.data('id'));
            
            $('#modalTitle').html('<i class="fas fa-edit"></i> Edit Approver');
            
            const modal = new bootstrap.Modal(document.getElementById('approverModal'));
            modal.show();
        });

        // ==================== DELETE APPROVER ====================
        $(document).on('click', '.action-delete', function() {
            if (!confirm('Are you sure you want to delete this approver?')) return;
            
            const row = $(this).closest('tr');
            const id = row.data('id');
            
            $.ajax({
                url: `/supervisors/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        row.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Update hierarchy levels
                            $('#approversTableBody tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });
                            
                            showToast('success', 'Approver deleted successfully');
                        });
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Something went wrong';
                    showToast('error', message);
                }
            });
        });

        // ==================== UTILITY FUNCTIONS ====================
        function showToast(type, message) {
            const toastId = 'toast-' + Date.now();
            const toast = `
                <div id="${toastId}" class="toast-notification">
                    <div class="toast-content ${type}">
                        <span>
                            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle'} me-2"></i>
                            ${message}
                        </span>
                        <button class="toast-close" onclick="document.getElementById('${toastId}').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#toastContainer').append(toast);
            
            setTimeout(() => {
                $(`#${toastId}`).fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // Close search results when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#employeeNameInput, #searchEmployeeBtn, .employee-search-results').length) {
                $('#employeeSearchResults').removeClass('show');
            }
            if (!$(e.target).closest('#approverSearch, .approver-search-results').length) {
                $('#approverSearchResults').removeClass('show');
            }
        });

        // Handle keyboard events
        $(document).keydown(function(e) {
            if (e.key === 'Escape') {
                $('#employeeSearchResults').removeClass('show');
                $('#approverSearchResults').removeClass('show');
            }
        });
    });
</script>
@endsection