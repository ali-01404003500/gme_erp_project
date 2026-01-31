@section('title', 'Purchase Requisition Import')
@section('description', 'Purchase Requisition Import')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('purchase.requisitions.index') }}">Requisitions</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Import Requisitions</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('purchase.requisitions.index'))
                            <a href="{{ route('purchase.requisitions.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">Import Requisitions</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <!-- Import Form -->
                            <form action="{{ route('purchase.requisition.import.process') }}" 
                                  method="POST" 
                                  enctype="multipart/form-data" 
                                  id="importForm">
                                @csrf
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="json_file" class="color-dark fs-14 fw-500 align-center">JSON File <span class="text-danger">*</span></label>
                                        <input type="file" 
                                               class="form-control @error('json_file') is-invalid @enderror" 
                                               id="json_file" 
                                               name="json_file" 
                                               accept=".json"
                                               required>
                                        @error('json_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Upload a JSON file with requisition data. Maximum file size: 10MB
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="button-group d-flex justify-content-between">
                                            <div>
                                                <a href="{{ route('purchase.requisition.import.template') }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fa fa-download"></i> Download Template
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-secondary btn-sm" 
                                                        id="viewNamesBtn">
                                                    <i class="fa fa-list"></i> View Available Names
                                                </button>
                                            </div>
                                            <div>
                                                <button type="button" 
                                                        class="btn btn-warning btn-sm" 
                                                        id="validateBtn">
                                                    <i class="fa fa-check-circle"></i> Validate JSON
                                                </button>
                                                <button type="submit" 
                                                        class="btn btn-primary btn-sm" 
                                                        id="importBtn">
                                                    <i class="fa fa-upload"></i> Import Requisitions
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Validation Results -->
                            <div id="validationResults" class="row" style="display: none;">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Validation Results</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="validationContent"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            {{-- <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">JSON Format Instructions</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary">Required Fields:</h6>
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Field</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><code>requisition_no</code></td>
                                                                <td>Unique requisition number</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>branch_name</code></td>
                                                                <td>Branch name (must exist in system)</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>products</code></td>
                                                                <td>Array of products with name, quantity, etc.</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>status</code></td>
                                                                <td>0=pending, 1=approved, 2=rejected, 4=received</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-success">Optional Fields:</h6>
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Field</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><code>customer_name</code></td>
                                                                <td>Customer company name</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>supplier_name</code></td>
                                                                <td>Supplier company name</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>approved_by_name</code></td>
                                                                <td>User name who approved</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>products.price</code></td>
                                                                <td>Product purchase price</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <h6 class="text-info mt-3">Product Structure:</h6>
                                            <p>Each product must include:</p>
                                            <ul>
                                                <li><code>name</code>: Product name (must exist in system)</li>
                                                <li><code>quantity</code>: Quantity ordered</li>
                                                <li><code>serials</code> or <code>batches</code>: Array of serial or batch details (required for status=4)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Names Modal -->
    <div class="modal fade" id="availableNamesModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Available Names in System</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-primary">Customers</h6>
                            <div id="customersList" class="small" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-success">Suppliers</h6>
                            <div id="suppliersList" class="small" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-info">Branches</h6>
                            <div id="branchesList" class="small" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-warning">Products</h6>
                            <div id="productsList" class="small" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const validateBtn = document.getElementById('validateBtn');
    const importBtn = document.getElementById('importBtn');
    const jsonFileInput = document.getElementById('json_file');
    const validationResults = document.getElementById('validationResults');
    const validationContent = document.getElementById('validationContent');
    const viewNamesBtn = document.getElementById('viewNamesBtn');

    // Get CSRF token safely
    function getCsrfToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }
        const csrfInput = document.querySelector('input[name="_token"]');
        if (csrfInput) {
            return csrfInput.value;
        }
        return window.Laravel && window.Laravel.csrfToken ? window.Laravel.csrfToken : '{{ csrf_token() }}';
    }

    // Validate JSON
    validateBtn.addEventListener('click', function() {
        if (!jsonFileInput.files[0]) {
            showToast('error', 'Please select a JSON file first');
            return;
        }

        const formData = new FormData();
        formData.append('json_file', jsonFileInput.files[0]);
        formData.append('_token', getCsrfToken());

        validateBtn.disabled = true;
        validateBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Validating...';

        fetch('{{ route("purchase.requisition.import.validate") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showValidationResults(false, data.error, []);
            } else {
                showValidationResults(true, '', data);
            }
        })
        .catch(error => {
            showValidationResults(false, 'Validation failed: ' + error.message, []);
        })
        .finally(() => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="fa fa-check-circle"></i> Validate JSON';
        });
    });

    // View available names
    viewNamesBtn.addEventListener('click', function() {
        fetch('{{ route("purchase.requisition.available.names") }}', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            displayAvailableNames(data);
            const modal = new bootstrap.Modal(document.getElementById('availableNamesModal'));
            modal.show();
        })
        .catch(error => {
            showToast('error', 'Failed to load available names: ' + error.message);
        });
    });

    function showValidationResults(success, error, data) {
        validationResults.style.display = 'block';
        
        if (success) {
            const alertClass = data.error_count === 0 ? 'alert-success' : 'alert-warning';
            const icon = data.error_count === 0 ? 'fa-check-circle' : 'fa-exclamation-triangle';
            
            validationContent.innerHTML = `
                <div class="alert ${alertClass}">
                    <h6><i class="fa ${icon}"></i> Validation Results</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1">Valid records: <strong class="text-success">${data.valid_count}</strong></p>
                            <p class="mb-1">Invalid records: <strong class="text-danger">${data.error_count}</strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            ${data.error_count === 0 ? 
                                '<span class="badge bg-success">Ready to Import</span>' : 
                                '<span class="badge bg-warning">Has Errors</span>'
                            }
                        </div>
                    </div>
                </div>
                ${data.errors && data.errors.length > 0 ? `
                    <div class="alert alert-danger">
                        <h6><i class="fa fa-exclamation-circle"></i> Validation Errors:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr><th>Error</th></tr>
                                </thead>
                                <tbody>
                                    ${data.errors.map(error => `<tr><td>${error}</td></tr>`).join('')}
                                </tbody>
                            </table>
                        </div>
                        ${data.total_errors > 10 ? `<p class="mt-2 mb-0"><small class="text-muted">... and ${data.total_errors - 10} more errors</small></p>` : ''}
                    </div>
                ` : ''}
            `;
            
            if (data.error_count === 0) {
                importBtn.disabled = false;
                importBtn.classList.remove('btn-primary');
                importBtn.classList.add('btn-success');
                importBtn.innerHTML = '<i class="fa fa-upload"></i> Ready to Import';
            }
        } else {
            validationContent.innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="fa fa-exclamation-circle"></i> Validation Failed</h6>
                    <p>${error}</p>
                </div>
            `;
        }
    }

    function displayAvailableNames(data) {
        const customersList = document.getElementById('customersList');
        customersList.innerHTML = data.customers.map(customer => 
            `<div class="mb-1"><code>${customer.company_name}</code></div>`
        ).join('');

        const suppliersList = document.getElementById('suppliersList');
        suppliersList.innerHTML = data.suppliers.map(supplier => 
            `<div class="mb-1"><code>${supplier.company_name}</code></div>`
        ).join('');

        const branchesList = document.getElementById('branchesList');
        branchesList.innerHTML = data.branches.map(branch => 
            `<div class="mb-1"><code>${branch.name}</code></div>`
        ).join('');

        const productsList = document.getElementById('productsList');
        productsList.innerHTML = data.products.map(product => 
            `<div class="mb-1"><code>${product.name} (${product.type})</code></div>`
        ).join('');
            
    }

    // Handle form submission
    document.getElementById('importForm').addEventListener('submit', function(e) {
        const confirmed = confirm('Are you sure you want to import this data? This action cannot be undone.');
        if (!confirmed) {
            e.preventDefault();
            return false;
        }

        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Importing...';
    });

    // Reset import button when file changes
    jsonFileInput.addEventListener('change', function() {
        importBtn.disabled = false;
        importBtn.classList.remove('btn-success');
        importBtn.classList.add('btn-primary');
        importBtn.innerHTML = '<i class="fa fa-upload"></i> Import Requisitions';
        validationResults.style.display = 'none';
    });

    // Display toast messages
    function showToast(type, message) {
        if (typeof toastr !== 'undefined') {
            if (type === 'success') {
                toastr.success(message);
            } else if (type === 'error') {
                toastr.error(message);
            } else if (type === 'warning') {
                toastr.warning(message);
            } else {
                toastr.info(message);
            }
        } else {
            alert(message);
        }
    }
});
</script>
@endsection