@extends('layout.app')

@section('title', 'Employee Approver Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-user-check me-2"></i>Employee Approver Management
                    </h5>
                </div>
                
                <div class="card-body">
                    <!-- Employee Info Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Employee Name *</label>
                            <select class="form-select" id="employeeSelect" name="employee_id">
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ ($employeeId ?? '') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->full_name }} ({{ $emp->epf_number ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        @if(isset($employee) && $employee)
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="p-3 bg-light rounded">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <small class="text-muted d-block">Name</small>
                                        <strong>{{ $employee->full_name }}</strong>
                                    </div>
                                    <div class="col-sm-4">
                                        <small class="text-muted d-block">EPF No</small>
                                        <strong>{{ $employee->epf_number ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-sm-4">
                                        <small class="text-muted d-block">Office</small>
                                        <strong>Head Office</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Approver Selection Section -->
                    <div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title fw-bold mb-3">Approve List - Set Approver *</h6>
        
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Select Employee by Code or Name</label>
                <div class="input-group">
                    <input type="text" 
                        class="form-control" 
                        id="approverSearch" 
                        placeholder="Type to search employee..."
                        {{ !isset($employee) || !$employee ? 'disabled' : '' }}
                        autocomplete="off">
                    <button class="btn btn-outline-secondary" 
                            type="button" 
                            id="searchBtn"
                            {{ !isset($employee) || !$employee ? 'disabled' : '' }}>
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div id="searchResults" class="list-group mt-2" style="max-height: 200px; overflow-y: auto; display: none;"></div>
                
                @if(!isset($employee) || !$employee)
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                      
                    </small>
                @endif
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100" 
                        id="addToListBtn"
                        {{ !isset($employee) || !$employee ? 'disabled' : '' }}>
                    <i class="fas fa-plus-circle me-2"></i>Add to List
                </button>
            </div>
        </div>
        
        <!-- Selected Approvers Preview -->
        <div id="selectedApproversPreview" class="mt-3">
            @if(!isset($employee) || !$employee)
                <div class="alert alert-info py-2 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Please select an employee first
                </div>
            @else
                <p class="text-muted mb-0 selected-preview-text">No approvers selected</p>
            @endif
        </div>
    </div>
</div>

<!-- Hidden token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

                    <!-- Current Approvers Table -->
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-3">Current Approvers List</h6>
                            
                            <div class="table-responsive" id="approversTableContainer">
                                @include('HRMS::settings.approver-setup.approval-table', ['currentApprovers' => $currentApprovers ?? collect()])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('styles')
<style>
    .selected-item {
        background-color: #e7f1ff;
        border: 1px solid #0d6efd;
        border-radius: 20px;
        padding: 5px 15px;
        margin: 5px;
        display: inline-flex;
        align-items: center;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .selected-item .remove-item {
        margin-left: 8px;
        cursor: pointer;
        color: #dc3545;
        font-size: 14px;
    }
    
    .selected-item .remove-item:hover {
        color: #bb2d3b;
    }
    
    #searchResults .list-group-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    #searchResults .list-group-item:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .delete-approver {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .delete-approver:hover {
        transform: scale(1.1);
    }
    
    /* Responsive fixes */
    @media (max-width: 768px) {
        .selected-item {
            width: 100%;
            justify-content: space-between;
        }
    }
    
    /* Notification styles */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // State management
    let selectedApprovers = [];
    let searchTimeout;
    
    // Setup CSRF for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Debug: Check if employee is selected
    console.log('Employee selected:', $('#employeeSelect').val());
    console.log('Search input disabled:', $('#approverSearch').is(':disabled'));

    // Employee select change handler - এইটা আপনার existing employee select এর সাথে সংযুক্ত করুন
    $(document).on('change', '#employeeSelect', function() {
        const employeeId = $(this).val();
        console.log('Employee changed to:', employeeId);
        
        if (employeeId) {
            // Enable search and add button
            $('#approverSearch').prop('disabled', false);
            $('#searchBtn').prop('disabled', false);
            $('#addToListBtn').prop('disabled', false);
            
            // Clear previous state
            selectedApprovers = [];
            updateSelectedPreview();
            $('#searchResults').hide().empty();
            
            // Show info
            showNotification('Employee selected. You can now search for approvers.', 'info');
        } else {
            // Disable search and add button
            $('#approverSearch').prop('disabled', true).val('');
            $('#searchBtn').prop('disabled', true);
            $('#addToListBtn').prop('disabled', true);
            
            // Clear all
            selectedApprovers = [];
            $('#searchResults').hide().empty();
            updateSelectedPreview();
        }
    });

    // Search functionality with Enter key
    $('#approverSearch').on('keyup', function(e) {
        clearTimeout(searchTimeout);
        
        if (e.keyCode === 13) { // Enter key
            performSearch();
        } else {
            searchTimeout = setTimeout(performSearch, 500);
        }
    });

    // Search button click
    $('#searchBtn').on('click', function() {
        performSearch();
    });

    // Perform search function
    function performSearch() {
        const searchTerm = $('#approverSearch').val().trim();
        const employeeId = $('#employeeSelect').val();

        console.log('Searching:', searchTerm, 'for employee:', employeeId);

        // Validation
        if (!employeeId) {
            showNotification('Please select an employee first', 'warning');
            return;
        }

        if (searchTerm.length < 1) {
            $('#searchResults').hide();
            return;
        }

        // Show loading
        $('#searchResults').html('<div class="list-group-item text-center"><i class="fas fa-spinner fa-spin me-2"></i>Searching...</div>').show();

        // AJAX call
        $.ajax({
            url: "{{ route('hrm.settings.employee-approvers.search') }}",
            method: 'POST',
            data: {
                employee_id: employeeId,
                search: searchTerm,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Search response:', response);
                if (response.success) {
                    displaySearchResults(response.data);
                } else {
                    showNotification(response.message || 'Search failed', 'error');
                    $('#searchResults').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', xhr.responseText);
                $('#searchResults').hide();
                
                let errorMessage = 'Search failed. ';
                if (xhr.status === 404) {
                    errorMessage += 'Route not found.';
                } else if (xhr.status === 500) {
                    errorMessage += 'Server error.';
                } else {
                    errorMessage += xhr.responseJSON?.message || 'Please try again.';
                }
                
                showNotification(errorMessage, 'error');
            }
        });
    }

    // Display search results
    function displaySearchResults(results) {
        const container = $('#searchResults');
        container.empty().show();

        if (!results || results.length === 0) {
            container.append('<div class="list-group-item text-muted">No employees found</div>');
            return;
        }

        // Filter out already selected approvers
        const filteredResults = results.filter(emp => 
            !selectedApprovers.some(selected => selected.id === emp.id)
        );

        if (filteredResults.length === 0) {
            container.append('<div class="list-group-item text-muted">All results are already selected</div>');
            return;
        }

        filteredResults.forEach(emp => {
            container.append(`
                <div class="list-group-item list-group-item-action search-result-item" 
                     data-id="${emp.id}" 
                     data-name="${emp.full_name}" 
                     data-epf="${emp.epf_number || 'N/A'}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${emp.full_name}</strong>
                            <br>
                            <small class="text-muted">${emp.epf_number || 'N/A'}</small>
                        </div>
                        <i class="fas fa-plus-circle text-primary"></i>
                    </div>
                </div>
            `);
        });

        // Add click handler for search results
        $('.search-result-item').off('click').on('click', function() {
            const emp = {
                id: $(this).data('id'),
                name: $(this).data('name'),
                epf: $(this).data('epf')
            };
            
            addToSelectedList(emp);
        });
    }

    // Add to selected list
    function addToSelectedList(emp) {
        // Check if already selected
        if (selectedApprovers.some(s => s.id === emp.id)) {
            showNotification(`${emp.name} is already selected`, 'warning');
            return;
        }

        selectedApprovers.push(emp);
        updateSelectedPreview();
        
        // Clear search
        $('#approverSearch').val('');
        $('#searchResults').hide();
        
        showNotification(`${emp.name} added to list`, 'success');
    }

    // Update selected preview
    function updateSelectedPreview() {
        const container = $('#selectedApproversPreview');
        container.empty();

        if (selectedApprovers.length === 0) {
            container.html('<p class="text-muted mb-0 selected-preview-text">No approvers selected</p>');
            return;
        }

        selectedApprovers.forEach((emp, index) => {
            container.append(`
                <span class="selected-item" data-id="${emp.id}">
                    <i class="fas fa-user me-2"></i>
                    ${emp.name} (${emp.epf})
                    <i class="fas fa-times-circle remove-item ms-2" data-id="${emp.id}" title="Remove" style="cursor: pointer;"></i>
                </span>
            `);
        });

        // Add remove handler
        $('.remove-item').on('click', function() {
            const id = $(this).data('id');
            removeFromSelectedList(id);
        });
    }

    // Remove from selected list
    function removeFromSelectedList(id) {
        const emp = selectedApprovers.find(s => s.id === id);
        selectedApprovers = selectedApprovers.filter(s => s.id !== id);
        updateSelectedPreview();
        
        if (emp) {
            showNotification(`${emp.name} removed from list`, 'info');
        }
    }

    // Add to list button click
    $('#addToListBtn').on('click', function() {
        const employeeId = $('#employeeSelect').val();

        if (!employeeId) {
            showNotification('Please select an employee first', 'warning');
            return;
        }

        if (selectedApprovers.length === 0) {
            showNotification('Please select at least one approver', 'warning');
            return;
        }

        // Disable button
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');

        $.ajax({
            url: "{{ route('hrm.settings.employee-approvers.store') }}",
            method: 'POST',
            data: {
                employee_id: employeeId,
                approver_ids: selectedApprovers.map(a => a.id),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Store response:', response);
                if (response.success) {
                    showNotification(response.message, 'success');
                    
                    // Update table
                    if (response.data) {
                        $('#approversTableContainer').html(response.data);
                    }
                    
                    // Clear selected list
                    selectedApprovers = [];
                    updateSelectedPreview();
                }
            },
            error: function(xhr) {
                console.error('Store error:', xhr);
                const message = xhr.responseJSON?.message || 'Failed to add approvers';
                showNotification(message, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Add to List');
            }
        });
    });

    // Click outside search results to hide
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#searchResults, #approverSearch, #searchBtn').length) {
            $('#searchResults').hide();
        }
    });

    // Notification function
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        $('.notification').remove();
        
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-circle' : 
                    type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        const notification = $(`
            <div class="alert ${alertClass} notification alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);

        $('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Initialize on page load
    function initialize() {
        console.log('Employee Approver Manager initialized');
        
        // Check initial state
        const employeeId = $('#employeeSelect').val();
        if (employeeId) {
            $('#approverSearch, #searchBtn, #addToListBtn').prop('disabled', false);
        }
    }

    initialize();
});
</script>

<!-- Add CSS for selected items -->
<style>
    .selected-item {
        background-color: #e7f1ff;
        border: 1px solid #0d6efd;
        border-radius: 20px;
        padding: 5px 15px;
        margin: 5px;
        display: inline-flex;
        align-items: center;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .selected-item .remove-item {
        margin-left: 8px;
        cursor: pointer;
        color: #dc3545;
        font-size: 14px;
    }
    
    .selected-item .remove-item:hover {
        color: #bb2d3b;
    }
    
    #searchResults .list-group-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    #searchResults .list-group-item:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    #searchResults .list-group-item:hover i {
        color: white !important;
    }
    
    .notification {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
@endpush