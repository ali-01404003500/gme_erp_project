@section('title', 'Create Job ')
@section('description', 'Create Job ')
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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Create Job') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <div class="d-flex gap-2">
                                @if (hasPermission('hrm.jobs.index'))
                                    <a href="{{ route('hrm.jobs.index') }}"
                                        class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                            class="fa fa-list"></i> List</a>
                                @endif
                                @if (hasPermission('hrm.jobs.create'))
                                    <a href="{{ route('hrm.jobs.create', app()->getLocale()) }}"
                                        class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Job Create') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('hrm.jobs.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">
                                            Branch
                                        </label>
                                        <div class="col-sm-12">
                                            <select name="branch_id" class="form-control tom-select"
                                                data-selected="{{ old('branch_id') }}" data-placeholder="Select Branch">
                                                <option></option>
                                                @foreach ($branches as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ old('branch_id') == $id ? 'selected' : '' }}>{{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!--- Department -->
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Department <sup class="text-danger">*</sup></label>
                                        <div class="col-sm-12">
                                            <select name="department_id" class="form-control tom-select">
                                                <option value="">Select Department</option>

                                                @foreach ($departments as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ old('department_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <!--- Designation -->
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Designation <sup class="text-danger">*</sup></label>
                                        <div class="col-sm-12">
                                            <select name="designation_id" class="form-control tom-select">
                                                <option value="">Select Designation</option>
                                                @foreach ($designations as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ old('designation_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Title <sup class="text-danger">*</sup></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="title" value="{{ old('title') }}"
                                                class="form-control" placeholder="Type Title">

                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Job Type <sup class="text-danger">*</sup></label>
                                        <div class="col-sm-12">
                                            <select name="job_type" class="form-control tom-select">
                                                <option value="">Select Job Type</option>

                                                <option value="Full Time"
                                                    {{ old('job_type') == 'Full Time' ? 'selected' : '' }}>Full Time
                                                </option>
                                                <option value="Part Time"
                                                    {{ old('job_type') == 'Part Time' ? 'selected' : '' }}>Part Time
                                                </option>
                                                <option value="Internship"
                                                    {{ old('job_type') == 'Internship' ? 'selected' : '' }}>Internship
                                                </option>
                                                <option value="Temporary"
                                                    {{ old('job_type') == 'Temporary' ? 'selected' : '' }}>Temporary
                                                </option>
                                                <option value="Remote" {{ old('job_type') == 'Remote' ? 'selected' : '' }}>
                                                    Remote</option>
                                                <option value="Contractual"
                                                    {{ old('job_type') == 'Contractual' ? 'selected' : '' }}>Contractual
                                                </option>
                                                <option value="Others" {{ old('job_type') == 'Others' ? 'selected' : '' }}>
                                                    Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="Vacancy" class="col-sm-12">Gender <sup
                                                class="text-danger">*</sup></label>
                                        <div class="col-sm-12">
                                            <select name="gender" class="form-control tom-select">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="Both" {{ old('gender') == 'Both' ? 'selected' : '' }}>Both
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Office Hours </label>
                                        <div class="col-sm-12">
                                            <input type="text" name="office_hours" value="{{ old('office_hours') }}"
                                                class="form-control" placeholder="Office Hours">
                                        </div>
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Weekend </label>
                                        <div class="col-sm-12">
                                            <input type="text" name="weekend" value="{{ old('weekend') }}"
                                                class="form-control" placeholder="Weekend">
                                        </div>
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Application Start Date</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="start_at" value="{{ old('start_at') }}"
                                                class="form-control flatdate" placeholder="Application Start Date">
                                        </div>
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Application Deadline </label>
                                        <div class="col-sm-12">
                                            <input type="text" name="deadline_at" value="{{ old('deadline_at') }}"
                                                class="form-control flatdate" placeholder="Application Deadline">
                                        </div>
                                    </div>

                                    <!--- Salary -->
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Salary</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="salary" value="{{ old('salary') }}"
                                                class="form-control only-number" placeholder="Salary Range">
                                            <input type="checkbox" name="negotiable" id="negotiable" value="1">
                                            <label for="negotiable">Negotiable</label>
                                        </div>
                                    </div>
                                    <!--- Job Template Location -->
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Job Location</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="location" value="{{ old('location') }}"
                                                class="form-control" placeholder="Job Location">

                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Job Description <sup class="text-danger">*</sup></label>
                                        <div class="col-sm-12">
                                            <textarea class="summernotes form-control" name="description">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Company Overview</label>
                                        <div class="col-sm-12">
                                            <textarea class="summernotes form-control" name="company_overview">{{ old('company_overview') }}</textarea>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Skills & Experience</label>
                                        <div class="col-sm-12">
                                            <textarea class="summernotes form-control" name="experience">{{ old('experience') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-sm-12">Employee Centric Policies</label>
                                        <div class="col-sm-12">
                                            <textarea class="summernotes form-control" name="employee_centric_policy">{{ old('employee_centric_policy') }}</textarea>
                                        </div>
                                    </div>


                                    <div class="col-md-12 mb-2">
                                        <div class="form-group formElement-editor">
                                            <div class="col-md-12 mb-2 mt-4">
                                                <h4>Educational Requirements</h4>
                                            </div>
                                            <textarea class="form-control trumbowyg" id="educational_requirement" name="educational_requirement" rows="2">{{ old('educational_requirement') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-group formElement-editor">
                                            <div class="col-md-12 mb-2 mt-4">
                                                <h4>Key Responsibility </h4>
                                            </div>
                                            <textarea class="form-control trumbowyg" id="responsibility" name="responsibility" rows="2">{{ old('responsibility') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">

                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                            Create
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')

    <script>
        $('#negotiable').on('click', function() {
            // alert($(this).is(":checked"))
            $('input[name=salary]').val('');
            $('input[name=salary]').attr('readonly', $(this).is(":checked"))
        })
    </script>
   <script>
    $(document).ready(function() {
        // When branch, department, or designation is changed
        $('select[name="branch_id"], select[name="department_id"], select[name="designation_id"]').on('change', function() {
            let branchId = $('select[name="branch_id"]').val();
            let departmentId = $('select[name="department_id"]').val();
            let designationId = $('select[name="designation_id"]').val();

            if (branchId && departmentId && designationId) {
                // Send an AJAX request to get job template data
                $.ajax({
                    url: '{{ route('hrm.job-templates.fetch') }}', // Replace with your route
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                        department_id: departmentId,
                        designation_id: designationId
                    },
                    success: function(response) {
                        if (response && Object.keys(response).length > 0) {
                            console.log(response);
                            // Populate fields with response data
                            $('input[name="title"]').val(response.title);
                            $('input[name="salary"]').val(response.salary);
                            $('input[name="office_hours"]').val(response.office_hours);
                            $('input[name="weekend"]').val(response.weekend);
                            $('input[name="location"]').val(response.location);
                            $('textarea[name="company_overview"]').val(response.company_overview);
                            $('textarea[name="description"]').val(response.description);
                            $('textarea[name="experience"]').val(response.experience);
                            $('textarea[name="employee_centric_policy"]').val(response.employee_centric_policy);
                            $('textarea[name="educational_requirement"]').trumbowyg('html', response.educational_requirement);
                            $('textarea[name="responsibility"]').trumbowyg('html', response.responsibility);
                        } else {
                            // Clear all form fields when no response or empty response
                            clearFormFields();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching job template:', error);
                        clearFormFields(); // Clear form fields in case of error
                    }
                });
            }
        });

        // Function to clear all form fields
        function clearFormFields() {
            $('input[name="title"]').val('');
            $('input[name="salary"]').val('');
            $('input[name="office_hours"]').val('');
            $('input[name="weekend"]').val('');
            $('input[name="location"]').val('');
            $('textarea[name="company_overview"]').val('');
            $('textarea[name="description"]').val('');
            $('textarea[name="experience"]').val('');
            $('textarea[name="employee_centric_policy"]').val('');
            $('textarea[name="educational_requirement"]').trumbowyg('html', '');
            $('textarea[name="responsibility"]').trumbowyg('html', '');
        }
    });
</script>

    
@endSection
