@extends('layout.app')
@section('content')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Settings</a></li>
                    <li class="breadcrumb-item active">Eligible Employee</li>
                </ol>
            </nav>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Leave Eligible Employee</h4>
                <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addEligibleModal">
                    <i class="fas fa-plus me-2"></i>Add New
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="">
                            <tr>
                                <th class="ps-4">Condition Type</th>
                                <th>Eligibility</th>
                                <th class="pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eligibilities as $item)
                                <tr>
                                    <td class="ps-4">{{ $item['condition_type'] }}</td>
                                    <td>{{ $item['eligibility'] }}</td>
                                    <td class="text-end pe-4 d-flex gap-2">
                                        <button class="btn border text-secondary"><i class="fas fa-edit"></i></button>
                                        <button class="btn border text-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addEligibleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">New Leave Eligible Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('hrm.settings.leave-eligible-employees.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Condition Type <span class="text-danger">*</span></label>
                                <select name="condition_type" class="form-select select2">
                                    <option value="">Select</option>
                                    <option value="Job Base">Job Base</option>
                                    <option value="Branch">Branch</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Eligibility <span class="text-danger">*</span></label>
                                <select name="eligibility" class="form-select select2">
                                    <option value="">Select</option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Contractual">Contractual</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light px-4 me-2"
                                data-bs-dismiss="modal text-primary">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
@endsection