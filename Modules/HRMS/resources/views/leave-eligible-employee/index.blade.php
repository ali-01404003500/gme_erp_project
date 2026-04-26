@extends('layout.app')
@section('title', 'Leave Eligible Employee')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Leave Eligible Employee</h4>
                <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addEligibleModal">
                    <i class="fas fa-plus me-2"></i>Add New
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <style>
                        .eligible-table-custom,
                        .eligible-table-custom th,
                        .eligible-table-custom td {
                            border: 1px solid #dee2e6 !important;
                            border-collapse: collapse !important;
                        }

                        .eligible-table-custom th,
                        .eligible-table-custom td {
                            padding: 12px;
                            vertical-align: middle;
                        }

                        .eligible-table-custom thead th {
                            background-color: #f8f9fa;
                            border-bottom-width: 2px !important;
                        }

                        .table thead th {
                            background-color: #35526e !important;
                            color: #ffffff !important;
                            font-weight: 600 !important;
                            text-transform: uppercase;
                            font-size: 0.85rem !important;
                            letter-spacing: 0.08em;
                            border-bottom: 2px solid #2a4054 !important;
                            padding: 14px 16px !important;
                            vertical-align: middle;
                            text-align: center;
                        }
                    </style>

                    <table class="table eligible-table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Condition Type</th>
                                <th>Eligibility</th>
                                <th class="pe-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eligibilities as $item)
                                <tr>
                                    <td class="ps-4">{{ $item->condition_type }}</td>
                                    <td>{{ $item->eligibility }}</td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('hrm.leave-eligible-employees.edit', $item->id) }}"
                                                class="btn btn-sm border text-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('hrm.leave-eligible-employees.destroy', $item->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm border text-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addEligibleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">New Leave Eligible Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('hrm.leave-eligible-employees.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Condition Type *</label>
                            <select name="condition_type" class="form-select select2" required>
                                <option value="">Select</option>
                                <option value="Job Base">Job Base</option>
                                <option value="Branch">Branch</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Eligibility *</label>
                            <select name="eligibility" class="form-select select2" required>
                                <option value="">Select</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Contractual">Contractual</option>
                                <option value="Probation">Probation</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                dropdownParent: $('#addEligibleModal'),
                width: '100%'
            });
        });
    </script>
@endpush