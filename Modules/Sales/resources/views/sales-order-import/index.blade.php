@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-upload me-2"></i>Sales Order Import
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Import Method Tabs -->
                    <ul class="nav nav-tabs mb-4" id="importTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="file-tab" data-bs-toggle="tab" data-bs-target="#file" type="button" role="tab">
                                <i class="fas fa-file-upload me-1"></i>File Upload
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bulk-tab" data-bs-toggle="tab" data-bs-target="#bulk" type="button" role="tab">
                                <i class="fas fa-edit me-1"></i>Bulk Input
                            </button>
                        </li>
                    </ul>

                    <!-- Import Tabs Content -->
                    <div class="tab-content" id="importTabsContent">
                        <!-- File Upload Tab -->
                        <div class="tab-pane fade show active" id="file" role="tabpanel">
                            <form id="fileImportForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="jsonFile" class="form-label">
                                                <i class="fas fa-file-json me-1"></i>JSON File
                                            </label>
                                            <input type="file" class="form-control" id="jsonFile" name="json_file" accept=".json" required>
                                            <div class="form-text">
                                                Upload a JSON file containing sales orders data
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="createDeliveriesCheck" name="create_deliveries" checked>
                                                <label class="form-check-label" for="createDeliveriesCheck">
                                                    Create Deliveries for Approved Orders
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info" id="fileValidateBtn">
                                        <i class="fas fa-check-circle me-2"></i>Validate Only
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="fileImportBtn">
                                        <i class="fas fa-upload me-2"></i>Import from File
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="downloadTemplateBtn">
                                        <i class="fas fa-download me-2"></i>Download Template
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Bulk Input Tab -->
                        <div class="tab-pane fade" id="bulk" role="tabpanel">
                            <form id="bulkImportForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="jsonTextarea" class="form-label">
                                        <i class="fas fa-code me-1"></i>JSON Data
                                    </label>
                                    <textarea class="form-control" id="jsonTextarea" name="json_data" rows="15" placeholder='{"sales_orders": []}' required></textarea>
                                    <div class="form-text">
                                        Paste your JSON data here with multiple sales orders
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="createDeliveriesBulkCheck" name="create_deliveries" checked>
                                        <label class="form-check-label" for="createDeliveriesBulkCheck">
                                            Create Deliveries for Approved Orders
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" id="bulkImportBtn">
                                        <i class="fas fa-upload me-2"></i>Import JSON Data
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="validateBtn">
                                        <i class="fas fa-check-circle me-2"></i>Validate JSON
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="loadTemplateBtn">
                                        <i class="fas fa-file-code me-2"></i>Load Template
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Progress & Results Section -->
                    <div id="importResults" class="mt-4" style="display: none;">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Import Results
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="progress">
                                        <div class="progress-bar" id="importProgress" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="row text-center" id="importStats">
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-primary" id="totalOrders">0</h4>
                                            <small class="text-muted">Total Orders</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-success" id="successOrders">0</h4>
                                            <small class="text-muted">Successful</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-danger" id="failedOrders">0</h4>
                                            <small class="text-muted">Failed</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-warning" id="warningsCount">0</h4>
                                            <small class="text-muted">Warnings</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Messages -->
                                <div id="messagesContainer" class="mt-4">
                                    <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                                    <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>
                                    <div id="validationMessage" class="alert alert-info" style="display: none;">
                                        <strong><i class="fas fa-info-circle me-2"></i>Validation Results:</strong><br>
                                        JSON structure appears valid, but some references may not exist in the database.
                                        Please review warnings and errors below.
                                    </div>
                                    <div id="warningsList" style="display: none;">
                                        <h6 class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Warnings:
                                        </h6>
                                        <ul class="list-group list-group-flush" id="warningsListItems"></ul>
                                    </div>
                                    <div id="errorsList" style="display: none;">
                                        <h6 class="text-danger">
                                            <i class="fas fa-times-circle me-1"></i>Errors:
                                        </h6>
                                        <ul class="list-group list-group-flush" id="errorsListItems"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template JSON Modal -->
<div class="modal fade" id="templateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">JSON Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre><code id="templateJson"></code></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="copyTemplateBtn">
                    <i class="fas fa-copy me-1"></i>Copy to Bulk Input
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_scripts')
<script>
    $(document).ready(function() {
        // File Validate Button
        $('#fileValidateBtn').on('click', function() {
            validateFile(false); // false means validate only, don't import
        });
    
        // File Import Form
        $('#fileImportForm').on('submit', function(e) {
            e.preventDefault();
            submitImport('import');
        });

        // Bulk Import Form
        $('#bulkImportForm').on('submit', function(e) {
            e.preventDefault();
            submitImport('bulk-import');
        });

        // Validate JSON
        $('#validateBtn').on('click', function() {
            validateJson();
        });

        // Download Template
        $('#downloadTemplateBtn').on('click', function() {
            downloadTemplate();
        });

        // Load Template
        $('#loadTemplateBtn').on('click', function() {
            loadTemplate();
        });

        // Copy Template
        $('#copyTemplateBtn').on('click', function() {
            copyTemplate();
        });

        // Auto-format JSON in textarea
            $('#jsonTextarea').on('blur', function() {
                try {
                    const json = JSON.parse($(this).val());
                    $(this).val(JSON.stringify(json, null, 2));
                } catch (e) {
                    // Invalid JSON, leave as is
                }
            });
        
            // Validate file function
            function validateFile(importAfterValidation = false) {
                const file = $('#jsonFile')[0].files[0];
                if (!file) {
                    alert('Please select a JSON file first');
                    return;
                }
        
                const formData = new FormData();
                formData.append('json_file', file);
                formData.append('_token', '{{ csrf_token() }}');
        
                // Show results section
                $('#importResults').show();
                $('#importProgress').css('width', '0%');
                $('#successMessage').hide();
                $('#errorMessage').hide();
                $('#warningsList').hide();
                $('#errorsList').hide();
        
                // Disable buttons
                $('#fileValidateBtn, #fileImportBtn').prop('disabled', true);
                $('#fileValidateBtn, #fileImportBtn').each(function() {
                    if (!importAfterValidation) {
                        $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Validating...');
                    } else {
                        $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
                    }
                });
        
                $.ajax({
                    url: importAfterValidation ? '{{ route("sales.sales-order-import.import") }}' : '{{ route("sales.sales-order-import.validate-file") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#importProgress').css('width', '100%');
        
                        if (response.valid === undefined) {
                            // This is an import response
                            if (response.success) {
                                $('#successMessage').html('<i class="fas fa-check-circle me-2"></i>' + response.message).show();
                                $('#errorMessage').hide();
                            } else {
                                $('#errorMessage').html('<i class="fas fa-exclamation-triangle me-2"></i>' + response.message).show();
                                $('#successMessage').hide();
                            }
                            displayStats(response.stats);
                        } else {
                            // This is a validation response
                            if (response.valid && (response.warnings || []).length === 0) {
                                $('#successMessage').html('<i class="fas fa-check-circle me-2"></i>' + response.message).show();
                                $('#errorMessage').hide();
                            } else {
                                // Show validation status - success with warnings or failed validation
                                let messagePrefix = response.valid ? '✅ Validation completed' : '❌ Validation failed';
                
                                if ((response.warnings || []).length > 0) {
                                    messagePrefix += ' (with ' + response.warnings.length + ' warning(s))';
                                    $('#successMessage').html('<i class="fas fa-check-circle me-2"></i>' + messagePrefix + ': ' + response.message).show();
                                    $('#errorMessage').hide();
                                } else if ((response.errors || []).length > 0) {
                                    $('#errorMessage').html('<i class="fas fa-exclamation-triangle me-2"></i>' + messagePrefix + ': ' + response.message).show();
                                    $('#successMessage').hide();
                                } else {
                                    // Default message for valid with no warnings
                                    $('#successMessage').html('<i class="fas fa-check-circle me-2"></i>' + response.message).show();
                                    $('#errorMessage').hide();
                                }
                            }

                            // Show import stats section for validation
                            $('#importStats').show();

                            if (response.stats) {
                                displayValidationStats(response.stats);
                            }

                            // Always show warnings and errors if they exist, regardless of validation status
                            displayValidationWarnings(response.warnings || []);
                            displayValidationErrors(response.errors || []);

                            // Show validation message if there are warnings or errors
                            if ((response.warnings && response.warnings.length > 0) || (response.errors && response.errors.length > 0)) {
                                $('#validationMessage').show();
                            } else {
                                $('#validationMessage').hide();
                            }

                            console.log('Validation Response:', response); // Debug logging
                        }
                    },
                    error: function(xhr) {
                        $('#importProgress').css('width', '0%');
                        $('#errorMessage').html('<i class="fas fa-times-circle me-2"></i>Validation failed: ' + (xhr.responseJSON?.message || 'Unknown error')).show();
                        $('#successMessage').hide();
                    },
                    complete: function() {
                        // Re-enable buttons
                        $('#fileValidateBtn, #fileImportBtn').prop('disabled', false);
                        $('#fileValidateBtn').html('<i class="fas fa-check-circle me-2"></i>Validate Only');
                        $('#fileImportBtn').html('<i class="fas fa-upload me-2"></i>Import from File');
                    }
                });
            }
    });

    function submitImport(type) {
        const formData = type === 'import' ? new FormData($('#fileImportForm')[0]) : new FormData($('#bulkImportForm')[0]);
        const url = type === 'import' ? '{{ route("sales.sales-order-import.import") }}' : '{{ route("sales.sales-order-import.bulk-import") }}';

        // Show results section
        $('#importResults').show();
        $('#importProgress').css('width', '0%');
        $('#importStats').hide();

        // Disable buttons
        $('#fileImportBtn, #bulkImportBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Importing...');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        $('#importProgress').css('width', percentComplete + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                $('#importProgress').css('width', '100%');
                displayStats(response.stats);

                if (response.success) {
                    $('#successMessage').html('<i class="fas fa-check-circle me-2"></i>' + response.message).show();
                    $('#errorMessage').hide();
                } else {
                    $('#errorMessage').html('<i class="fas fa-exclamation-triangle me-2"></i>' + response.message).show();
                    $('#successMessage').hide();
                }

                // Show stats after animation
                setTimeout(() => {
                    $('#importStats').show();
                }, 500);
            },
            error: function(xhr) {
                $('#importProgress').css('width', '0%');
                $('#errorMessage').html('<i class="fas fa-times-circle me-2"></i>Import failed: ' + (xhr.responseJSON?.message || 'Unknown error')).show();
                $('#successMessage').hide();
            },
            complete: function() {
                // Re-enable buttons
                $('#fileImportBtn, #bulkImportBtn').prop('disabled', false).html(function() {
                    return $(this).attr('id') === 'fileImportBtn' ?
                        '<i class="fas fa-upload me-2"></i>Import from File' :
                        '<i class="fas fa-upload me-2"></i>Import JSON Data';
                });
            }
        });
    }

    function validateJson() {
        const jsonData = $('#jsonTextarea').val();
        if (!jsonData.trim()) {
            alert('Please enter JSON data first');
            return;
        }

        $.ajax({
            url: '{{ route("sales.sales-order-import.validate-json") }}',
            method: 'POST',
            data: {
                json_data: jsonData,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.valid) {
                    alert('JSON is valid! Found ' + response.order_count + ' order(s).');
                } else {
                    alert('JSON validation failed:\n' + response.errors.join('\n'));
                }
            },
            error: function(xhr) {
                alert('Validation error: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    }

    function downloadTemplate() {
        // Direct download from the download-template route
        const url = '{{ route("sales.sales-order-import.download-template") }}';
        window.open(url, '_blank');
    }

    function loadTemplate() {
        $.ajax({
            url: '{{ route("sales.sales-order-import.template") }}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#jsonTextarea').val(JSON.stringify(response, null, 2));
            },
            error: function(xhr) {
                alert('Error loading template: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    }

    function copyTemplate() {
        const templateJson = $('#templateJson').text();
        $('#jsonTextarea').val(templateJson);
        $('#templateModal').modal('hide');
        $('#bulk-tab').tab('show');
    }

    function displayStats(stats) {
        $('#totalOrders').text(stats.processed);
        $('#successOrders').text(stats.successful);
        $('#failedOrders').text(stats.failed);
        $('#warningsCount').text(stats.warnings.length);
    
        // Display warnings
        if (stats.warnings.length > 0) {
            $('#warningsListItems').empty();
            stats.warnings.forEach(function(warning) {
                $('#warningsListItems').append('<li class="list-group-item list-group-item-warning">' + warning + '</li>');
            });
            $('#warningsList').show();
        } else {
            $('#warningsList').hide();
        }
    
        // Display errors
        if (stats.errors.length > 0) {
            $('#errorsListItems').empty();
            stats.errors.forEach(function(error) {
                $('#errorsListItems').append('<li class="list-group-item list-group-item-danger">' + error + '</li>');
            });
            $('#errorsList').show();
        } else {
            $('#errorsList').hide();
        }
    }
    
    function displayValidationStats(stats) {
        console.log('Displaying validation stats:', stats);
    
        // Show import stats section for validation results
        $('#importStats').show();
    
        $('#totalOrders').text(stats.total_orders || 0);
    
        // Set appropriate values for validation
        if (stats.total_orders > 0) {
            $('#successOrders').text('✓');
            $('#failedOrders').text('0');
            $('#warningsCount').text(stats.total_orders - (stats.orders_to_approve || 0));
    
            // Add some visual indication of validation success
            $('#successOrders').parent().addClass('bg-light border-success');
        } else {
            $('#successOrders').text('0');
            $('#failedOrders').text('0');
            $('#warningsCount').text('0');
        }
    }
    
    function displayValidationWarnings(warnings) {
        console.log('Displaying warnings:', warnings);
    
        if (warnings && warnings.length > 0) {
            $('#warningsListItems').empty();
            warnings.forEach(function(warning) {
                $('#warningsListItems').append('<li class="list-group-item list-group-item-warning"><i class="fas fa-exclamation-triangle me-2"></i>' + warning + '</li>');
            });
            $('#warningsList').show();
            $('#warningsCount').text(warnings.length);
            console.log('Warnings displayed, count:', warnings.length);
        } else {
            $('#warningsList').hide();
            $('#warningsCount').text('0');
            console.log('No warnings to display');
        }
    }
    
    function displayValidationErrors(errors) {
        console.log('Displaying errors:', errors);
    
        if (errors && errors.length > 0) {
            $('#errorsListItems').empty();
            errors.forEach(function(error) {
                $('#errorsListItems').append('<li class="list-group-item list-group-item-danger"><i class="fas fa-times-circle me-2"></i>' + error + '</li>');
            });
            $('#errorsList').show();
            $('#failedOrders').text(errors.length);
            console.log('Errors displayed, count:', errors.length);
        } else {
            $('#errorsList').hide();
            $('#failedOrders').text('0');
            console.log('No errors to display');
        }
    }
</script>
@endsection

{{-- @push('styles')
<style>
    .progress {
        height: 25px;
    }
    .progress-bar {
        transition: width 0.3s ease;
    }
    .list-group-flush .list-group-item {
        border-radius: 5px;
        margin-bottom: 5px;
    }
    pre {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        overflow-x: auto;
    }
</style>
@endpush --}}