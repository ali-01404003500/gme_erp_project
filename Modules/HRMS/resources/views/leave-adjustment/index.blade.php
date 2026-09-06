@extends('layout.app')
@section('content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Leave Adjustment List</li>
                </ol>
                <ul><br></ul>
            </nav>
            <div class="page-title-box">
                <h4 class="page-title">Leave Adjustment List</h4>
            </div>
        </div>
        
    </div>
    <ul><br></ul>

    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <select class="form-select" style="min-width: 200px;">
                                <option value="">Select Employee</option>
                                <option value="1">John Doe</option>
                                <option value="2">Jane Smith</option>
                                <option value="3">Mike Johnson</option>
                                <option value="4">Sarah Williams</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <input type="date" class="form-control" placeholder="From" style="min-width: 150px;">
                        </div>
                        <div class="col-auto">
                            <input type="date" class="form-control" placeholder="To" style="min-width: 150px;">
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>

                       <a href="{{ route('hrm.leave-adjustments.create') }}" class="btn btn-success">  

                                <i class="fas fa-plus me-1"></i> Add New
                        </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Leave Status Alert -->
    <div class="row mb-3">
        <div class="col-12">
            {{-- <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>New adjustments found</strong> - 3 pending adjustments require review
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> --}}
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">SI</th>
                                    <th>Employee</th>
                                    <th>Leave Year</th>
                                    <th>Leave Type</th>
                                    <th>Previous Balance</th>
                                    <th>Adjustment</th>
                                    <th>New Balance</th>
                                    <th>Remarks</th>
                                    <th>Adjusted By</th>
                                    <th>Adjustment Date</th>
                                    <th>Status</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Data Row 1 -->
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div>
                                            <strong>John Doe</strong>
                                            <br>
                                            <small class="text-muted">EMP001</small>
                                        </div>
                                    </td>
                                    <td>2025</td>
                                    <td>Annual Leave</td>
                                    <td>15</td>
                                    <td>
                                        <span class="badge bg-success">+5</span>
                                    </td>
                                    <td><strong>20</strong></td>
                                    <td>Yearly performance bonus</td>
                                    <td>Admin User</td>
                                    <td>2025-02-15</td>
                                    <td>
                                        <span class="badge bg-success">Approved</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-danger" title="Delete" 
                                               onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                              
                               
                             
                            </tbody>
                        </table>
                        
                        <!-- No Data Available Row (hidden by default) -->
                        <!--
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No data available in table</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        -->
                    </div>
                    
                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-0">Showing 1 to 4 of 4 entries</p>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation" class="float-end">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Adjustment Modal -->


@push('styles')
<!-- Font Awesome -->

<style>
    .breadcrumb {
        background-color: transparent;
        padding: 0.75rem 0;
    }
    .page-title-box {
        padding-bottom: 1rem;
    }
    .table > :not(caption) > * > * {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    .modal-md {
        max-width: 500px;
    }
</style>
@endpush
@endsection

