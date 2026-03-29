@extends('layout.app')
@section('title', 'Edit Leave Eligible Employee')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm mt-5">
                    <div class="card-header bg-white border-0 py-3">
                        <h4 class="mb-0 fw-bold text-primary">Edit Leave Eligible Employee</h4>
                    </div>
                    <hr class="m-0 text-muted opacity-10">
                    <div class="card-body p-4">
                        <form action="{{ route('hrm.leave-eligible-employees.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label fw-bold">Condition Type *</label>
                                <select name="condition_type" class="form-select" required>
                                    <option value="Job Base" {{ $item->condition_type == 'Job Base' ? 'selected' : '' }}>Job
                                        Base</option>
                                    <option value="Branch" {{ $item->condition_type == 'Branch' ? 'selected' : '' }}>Branch
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Eligibility *</label>
                                <select name="eligibility" class="form-select" required>
                                    <option value="Permanent" {{ $item->eligibility == 'Permanent' ? 'selected' : '' }}>
                                        Permanent</option>
                                    <option value="Contractual" {{ $item->eligibility == 'Contractual' ? 'selected' : '' }}>
                                        Contractual</option>
                                    <option value="Probation" {{ $item->eligibility == 'Probation' ? 'selected' : '' }}>
                                        Probation</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('hrm.leave-eligible-employees.index') }}"
                                    class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">Update Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection