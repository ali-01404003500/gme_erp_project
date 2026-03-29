
@section('title', 'Leave Eligible')
@section('description', 'Leave Eligible')
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
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm"> 
                                            @if (hasPermission('hrm.leave-eligible-employees.update'))
                                                <button type="button"
                                                    data-action="{{ route('hrm.leave-eligible-employees.update', $item->id) }}"
                                                    data-data="{{ json_encode($item) }}"
                                                    class="btn btn-outline-primary btn-edit" data-bs-toggle="modal"
                                                    data-bs-target="#editModal">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                            @endif
                                            @if (hasPermission('hrm.leave-eligible-employees.destroy'))
                                                <button type="button"
                                                    data-action="{{ route('hrm.leave-eligible-employees.destroy', $item->id) }}"
                                                    class="btn btn-outline-danger delete-confirm">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- Create Modal --}}
        <div class="modal fade" id="addEligibleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">New Leave Eligible Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('hrm.leave-eligible-employees.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Condition Type <span class="text-danger">*</span></label>
                                <select  name="condition_type" class="form-select select2">
                                    <option value="">Select</option>
                                    <option value="Job Base">Job Base</option>
                                    <option value="Branch">Branch</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Eligibility <span class="text-danger">*</span></label>
                                <select  name="eligibility" class="form-select select2">
                                    <option value="">Select</option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Contractual">Contractual</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light px-4 me-2"  data-bs-dismiss="modal">Cancel</button>  
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Edit Modal --}}
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Leave Eligible Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post" id="editForm">
                        @csrf
                        <input type="hidden" id="id" name="id" >
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Condition Type <span class="text-danger">*</span></label>
                                <select id="condition_type" name="condition_type" class="form-select select2">
                                    <option value="">Select</option>
                                    <option value="Job Base">Job Base</option>
                                    <option value="Branch">Branch</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Eligibility <span class="text-danger">*</span></label>
                                <select id="eligibility" name="eligibility" class="form-select select2">
                                    <option value="">Select</option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Contractual">Contractual</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select id="status" name="status" class="form-select"> 
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light px-4 me-2"  data-bs-dismiss="modal">Cancel</button>  
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('page_scripts')

    <script>
        $(document).ready(function () { 

         
            
        });

        $(document).on('click', '.btn-edit', function () { 
            const data = $(this).data('data');
            const action = $(this).data('action');

            let form = $('#editForm');
            form.attr('action', action);

 
            form.find('select[name="condition_type"]').val(data.condition_type).trigger('change');
            form.find('select[name="eligibility"]').val(data.eligibility).trigger('change');
            form.find('select[name="status"]').val(data.status).trigger('change');
            form.find('select[name="id"]').val(data.id);

        });
    </script>

    

@endsection

 