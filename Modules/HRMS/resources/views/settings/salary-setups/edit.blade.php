@section('title', 'Salary Setup Update')
@section('description', 'Salary Setup Update')
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
                                        {{ trans('menu.edit-salary-setup-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.settings.salary-setups.index'))
                            <a href="{{ route('hrm.settings.salary-setups.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-salary-setup-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ route('hrm.settings.salary-setups.update', $salarySetup->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="title" class="color-dark fs-14 fw-500 align-center">Title<span class="text-danger">*</span></label>
                                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $salarySetup->title) }}" required>
                                                @if ($errors->has('title'))
                                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-25">
                                                <label for="effective_date" class="color-dark fs-14 fw-500 align-center">Effective Date<span class="text-danger">*</span></label>
                                                <input type="text" name="effective_date" id="effective_date" class="form-control flatdate" value="{{ old('effective_date', $salarySetup->effective_date) }}" required>
                                                @if ($errors->has('effective_date'))
                                                    <p class="text-danger">{{ $errors->first('effective_date') }}</p>
                                                @endif
                                        
                                            </div>
                                        </div>

                                        <div class="form-group row mb-25">
                                            <label for="basic" class="col-md-3 col-form-label color-dark fs-14 fw-500">Basic (%)<span class="text-danger">*</span></label>
                                            <div class="col-md-6">
                                                 <input type="text" name="basic" id="basic" class="form-control" value="{{ old('basic', $salarySetup->basic) }}" required>
                                                @if ($errors->has('basic'))
                                                    <p class="text-danger">{{ $errors->first('basic') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row mb-25">
                                            <label for="house_rent" class="col-md-3 col-form-label color-dark fs-14 fw-500">House Rent (%)<span class="text-danger">*</span></label>
                                            <div class="col-md-6">
                                                 <input type="text" name="house_rent" id="house_rent" class="form-control" value="{{ old('house_rent', $salarySetup->house_rent) }}" >
                                                @if ($errors->has('house_rent'))
                                                    <p class="text-danger">{{ $errors->first('house_rent') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row mb-25">
                                            <label for="conveyance" class="col-md-3 col-form-label color-dark fs-14 fw-500">Conveyance Allowance (% or Tk.)</label>
                                            <div class="col-md-6">
                                                 <input type="text" name="conveyance" id="conveyance" class="form-control" value="{{ old('conveyance', $salarySetup->conveyance) }}">
                                                @if ($errors->has('conveyance'))
                                                    <p class="text-danger">{{ $errors->first('conveyance') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox-theme-default custom-checkbox m-2">
                                                    <input class="checkbox" name="is_conveyance_fixed" value="1" @if (old('is_conveyance_fixed') == 1 || $salarySetup->is_conveyance_fixed == 1) checked  @endif
                                                        type="checkbox" id="is_conveyance_fixed">
                                                    <label for="is_conveyance_fixed">
                                                        <span class="checkbox-text">
                                                            Fixed
                                                        </span>
                                                    </label>
                                                </div>                                            
                                            </div>
                                        </div>

                                        <div class="form-group row mb-25">
                                            <label for="medical" class="col-md-3 col-form-label color-dark fs-14 fw-500">Medical Allowance (% or Tk.)</label>
                                            <div class="col-md-6">
                                                 <input type="text" name="medical" id="medical" class="form-control" value="{{ old('medical', $salarySetup->medical) }}">
                                                @if ($errors->has('medical'))
                                                    <p class="text-danger">{{ $errors->first('medical') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox-theme-default custom-checkbox m-2">
                                                    <input class="checkbox" name="is_medical_fixed" value="1" @if (old('is_medical_fixed', $salarySetup->is_medical_fixed) == 1) checked  @endif
                                                        type="checkbox" id="is_medical_fixed">
                                                    <label for="is_medical_fixed">
                                                        <span class="checkbox-text">
                                                            Fixed
                                                        </span>
                                                    </label>
                                                </div>                                            
                                            </div>
                                        </div>

                                        <div class="form-group row mb-25">
                                            <label for="others" class="col-md-3 col-form-label color-dark fs-14 fw-500">Others Allowance (% or Tk.)</label>
                                            <div class="col-md-6">
                                                 <input type="text" name="others" id="others" class="form-control" value="{{ old('others', $salarySetup->others) }}">
                                                @if ($errors->has('others'))
                                                    <p class="text-danger">{{ $errors->first('others') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox-theme-default custom-checkbox m-2">
                                                    <input class="checkbox" name="is_others_fixed" value="1" @if (old('is_others_fixed', $salarySetup->is_others_fixed) == 1) checked  @endif
                                                        type="checkbox" id="is_others_fixed">
                                                    <label for="is_others_fixed">
                                                        <span class="checkbox-text">
                                                            Fixed
                                                        </span>
                                                    </label>
                                                </div>
                                        </div>

                                    </div>
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
   $(document).ready(function() {
    function calculateTotalPercentage() {
        // Get the input values
        let basic = parseFloat($('#basic').val()) || 0;
        let houseRent = parseFloat($('#house_rent').val()) || 0;
        let conveyance = parseFloat($('#conveyance').val()) || 0;
        let medical = parseFloat($('#medical').val()) || 0;
        let others = parseFloat($('#others').val()) || 0;

        // Check if fixed checkboxes are checked
        let isConveyanceFixed = $('#is_conveyance_fixed').is(':checked');
        let isMedicalFixed = $('#is_medical_fixed').is(':checked');
        let isOthersFixed = $('#is_others_fixed').is(':checked');

        let total = houseRent + basic;

        // Only add if not fixed
        if (!isConveyanceFixed) {
            total += conveyance;
        }
        if (!isMedicalFixed) {
            total += medical;
        }
        if (!isOthersFixed) {
            total += others;
        }

        return total;
    }

    function validateTotalPercentage() {
        let total = calculateTotalPercentage();
        if (total !== 100) {
            toastr.error('The total percentage must equal 100.');
            return false;
        }
        return true;
    }

    // Recalculate total percentage on input change
    $('input[type="text"], input[type="checkbox"]').on('change', function() {
        validateTotalPercentage();
    });

    // Handle form submission
    $('form').on('submit', function(e) {
        // Prevent form submission if validation fails
        if (!validateTotalPercentage()) {
            e.preventDefault();
        }
    });

    // Initial validation on page load
    validateTotalPercentage();
});

</script>

@endSection
