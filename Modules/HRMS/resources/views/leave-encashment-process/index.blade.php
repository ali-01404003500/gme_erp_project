@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="text-danger mb-4" style="text-decoration: underline; font-style: italic; font-weight: bold;">
                    Leave Encashment Process:
                </h4>

                <ul class="nav nav-tabs mb-4" id="encashmentTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Process New</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-muted" href="#">Already Processed</a>
                    </li>
                </ul>

                <form action="{{ route('hrm.settings.LeaveEncashmentProcess.store') }}" method="POST">
                    @csrf

                    <div class="d-flex mb-4">
                        <div class="form-check me-4">
                            <input class="form-check-input" type="radio" name="process_type" id="empWise"
                                value="employee_wise" checked>
                            <label class="form-check-label fw-bold" for="empWise">Employee Wise</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="process_type" id="groupWise"
                                value="group_wise">
                            <label class="form-check-label fw-bold" for="groupWise">Leave Group Wise</label>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-7" id="employeeSelectContainer">
                            <label class="form-label fw-bold">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select select2" id="employee_id">
                                <option value="">Select Employee by Code or Name</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        [{{ $employee->employee_code }}] {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn text-white w-100 py-2"
                                style="background-color: #6a49e3; border-radius: 8px;">
                                Process Encashment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Initialize Select2 if you have it
                $('.select2').select2();

                // Optional: Toggle visibility if Group Wise is selected
                $('input[name="process_type"]').on('change', function () {
                    if ($(this).val() === 'group_wise') {
                        $('#employeeSelectContainer').css('opacity', '0.5'); // Or hide it
                    } else {
                        $('#employeeSelectContainer').css('opacity', '1');
                    }
                });
            });
        </script>
    @endpush
@endsection