@section('title', 'Create Job Template ')
@section('description', 'Create Job Template ')
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
                                        {{ trans('Create Job Template') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            <div class="d-flex gap-2">
                                @if (hasPermission('hrm.job-templates.index'))
                                    <a href="{{ route('hrm.job-templates.index') }}"
                                        class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                            class="fa fa-list"></i> List</a>
                                @endif
                                @if (hasPermission('hrm.job-templates.create'))
                                    <a href="{{ route('hrm.job-templates.create', app()->getLocale()) }}"
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Job Template Create') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('hrm.job-templates.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <!-- LEFT SIDE -->
                                        <!--- Job Template Title -->
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Title <sup class="text-danger">*</sup></label>
                                            <div class="col-sm-9">
                                                <input type="text" name="title" value="{{ old('title') }}"
                                                    class="form-control" placeholder="Type Title">

                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">
                                                Branch <sup class="text-danger">*</sup>
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="branch_id" class="form-control tom-select"
                                                    data-selected="{{ old('branch_id') }}"
                                                    data-placeholder="Select Branch">
                                                    <option></option>
                                                    @foreach ($branches as $id => $name)
                                                        <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : ''}}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!--- Department -->
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Department <sup class="text-danger">*</sup></label>
                                            <div class="col-sm-9">
                                                <select name="department_id" class="form-control tom-select">
                                                    <option value="">Select Department</option>

                                                    @foreach ($departments as $id => $name)
                                                        <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : ''}}>{{ $name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        <!--- Designation -->
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Designation <sup class="text-danger">*</sup></label>
                                            <div class="col-sm-9">
                                                <select name="designation_id" class="form-control tom-select">
                                                    <option value="">Select Designation</option>
                                                    @foreach ($designations as $id => $name)
                                                        <option value="{{ $id }}" {{ old('designation_id') == $id ? 'selected' : ''}}>{{ $name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        <!--- Salary -->
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Salary</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="salary" value="{{ old('salary') }}"
                                                    class="form-control only-number" placeholder="Salary Range">
                                                <input type="checkbox" name="negotiable" id="negotiable" value="1">
                                                <label for="negotiable">Negotiable</label>
                                            </div>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Office Hours </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="office_hours" value="{{ old('office_hours') }}"
                                                    class="form-control" placeholder="Office Hours">
                                            </div>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Weekend </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="weekend" value="{{ old('weekend') }}"
                                                    class="form-control" placeholder="Weekend">
                                            </div>
                                        </div>


                                        <!--- Job Template Location -->
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Job Location</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="location" value="{{ old('location') }}"
                                                    class="form-control" placeholder="Job Location">

                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Company Overview</label>
                                            <div class="col-sm-9">
                                                <textarea class="summernotes form-control" name="company_overview">{{ old('company_overview') }}</textarea>
                                            </div>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3">Job Description</label>
                                            <div class="col-sm-9">
                                                <textarea class="summernotes form-control" name="description">{{ old('description') }}</textarea>
                                            </div>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="col-sm-12">Skills & Experience</label>
                                            <div class="col-sm-9">
                                                <textarea class="summernotes form-control" name="experience">{{ old('experience') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-12">Employee Centric Policies</label>
                                            <div class="col-sm-9">
                                                <textarea class="summernotes form-control" name="employee_centric_policy">{{ old('employee_centric_policy') }}</textarea>
                                            </div>
                                        </div>
                                       
                                       
                                        <div class="col-md-11 mb-2">
                                            <div class="form-group formElement-editor">
                                                    <div class="col-md-12 mb-2 mt-4">
                                                        <h4>Educational Requirements</h4>
                                                    </div>
                                                <textarea class="form-control trumbowyg" id="educational_requirement" name="educational_requirement" rows="2">{{ old('educational_requirement') }}</textarea>
                                            </div>
                                        </div>

                                    <div class="col-md-11 mb-2">
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

@endSection
