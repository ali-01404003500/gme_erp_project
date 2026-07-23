@extends('layout.app')    

@section('title', 'Leave Approver Setup')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-user-check me-2"></i>Leave Approver Setup
                        </h5>
                       
                    </div>

                    <div class="card-body">
                        {{-- 1. Main Employee Selection --}}
                        <div class="row mb-4">

                            <div class="col-md-12 justify-content-start">

                                <form action="{{ route('hrm.settings.leave-approvers.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- Step 1: Select Employee -->
                                            <div class="form-group">
                                                <label class="form-label fw-bold">Select Employee <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select tom-select" id="employeeSelect"
                                                    name="employee_id">
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach ($employees as $emp)
                                                        <option value="{{ $emp->id }}"
                                                            @if (request()->input('employee_id') == $emp->id) selected @endif>
                                                            {{ $emp->full_name }} 
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8"></div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">Select Approver From Here <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select tom-select" id="approver-select"
                                                    name="approver_id">
                                                    <option value="">-- Select Approver --</option>
                                                    @foreach ($employees as $emp)
                                                        <option value="{{ $emp->id }}" data-designation="{{ $emp->employementDetail->designation->name ?? '' }}">
                                                            {{ $emp->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8"></div>

                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-xs btn-primary mt-3" id="add-new-approver" type="button">Add
                                                New</button>
                                        </div>

                                    

                                        <div class="col-md-12 mt-5">
                                            <table class="table" id="approver-table">
                                                <thead>
                                                    <tr>
                                                        <th>Hiarachy</th>
                                                        {{-- <th>Approver Code</th> --}}
                                                        <th>Approver Name</th>
                                                        <th>Designation</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- JS diye data inject hobe --}}
                                                    {{-- @dd($approvers) --}}
                                                    @foreach ($approvers as $key => $approver)
                                                        <tr>
                                                            <td>
                                                                 <input type="hidden" class="approver_ids"
                                                                    name="approver_update_id[]"
                                                                    value="{{ $approver->id }}">
                                                                {{ $approver->hierarchy_level }}</td>

                                                            {{-- <td>{{ $approver->approver->epf_number }}</td> --}}
                                                            <td>
                                                                {{ $approver->approver->full_name }}
                                                                <input type="hidden" class="approver_ids"
                                                                    name="approver_ids[]"
                                                                    value="{{ $approver->approver_id }}">
                                                            </td>
                                                            <td>{{ $approver->approver->employementDetail->designation->name ?? 'N/A' }}</td>

                                                            <td><button class="btn btn-danger remove"
                                                                    type="button">Remove</button></td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>


                                        {{-- Step 2: Build Hierarchy Section --}}
                                    </div>


                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-success px-3">
                                                Submit
                                            </button>
                                        </div>
                                    </div>


                                </form>

                            </div>



                            {{-- 3. Current Status Table --}}



                        </div>
                    </div>
                </div>
            </div>

        </div>

        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endsection


    @section('page_scripts')

        <script>
            $(document).ready(function() {

                $("#add-new-approver").click(function() {
                    //handle add button
                    const approverSelect = $("#approver-select").val()
                    const employeeSelect = $("#employeeSelect").val()

                  if (!approverSelect || !employeeSelect) {
                        toastr.error('Please select both Approver and Employee');
                        
                        return
                    }

                    const approverOption = $("#approver-select").find('option:selected');
                    const approverId = approverSelect;
                    if ($('.approver_ids[value=\'' + approverId + '\']').length > 0) {
                        toastr.error('Approver already added');

                        return

                    }
                    const name = approverOption.text();
                    const designation = approverOption.data('designation');
                    // const epfNumber = approverOption.data('epf_number');
                    const approverTable = $("#approver-table");
                    approverTable.find('tbody').append($(` 
                                <tr>
                                <td>${ approverTable.find('tbody tr').length+1}</td>                                                    
                                <td>
                                    ${name}
                                    <input type="hidden" class="approver_ids" name="approver_ids[]" value="${approverId}">
                                    </td>
                                <td>${designation}</td>
                                <td><button class="btn btn-danger remove" type="button">Remove</button></td>
                            </tr>`
                        ));

                   // Reset Tom Select
                    const approverSelectElement = $("#approver-select")[0];

                    if (approverSelectElement && approverSelectElement.tomselect) {
                        approverSelectElement.tomselect.clear();
                    }
                




                })

                // Another function
                $('#employeeSelect').on("change", function() {
                    const employeeId = $(this).val()
                    if (employeeId) {
                        document.location = location.pathname + '?employee_id=' + employeeId
                    }
                })

                
                //Another Function
                $(document).on("click", ".remove", function() {
                    const row = $(this).closest('tr');
                    row.remove();
                })



            });
        </script>
    @endsection
