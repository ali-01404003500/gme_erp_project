@section('title', 'Salary Setup')
@section('description', 'Salary Setup')
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
                                        {{ trans('menu.create-salary-setup-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.employee-salarys.index'))
                            <a href="{{ route('hrm.employee-salarys.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-salary-setup-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ $employeeSalary->exists ? route('hrm.employee-salarys.update', $employeeSalary->id) : route('hrm.employee-salarys.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if ($employeeSalary->exists)
                                        @method('PUT')
                                    @endif
                                    <div class="row">
                                         <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Employee Name
                                                    </span>
                                                    <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ $employee->full_name }}" readonly>
                                                    <input type="hidden" name="employee_id" id="employee_id" class="form-control" value="{{ $employee->id }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Employee Address
                                                    </span>
                                                    <input type="text" name="employee_address" id="employee_address" class="form-control" value="{{ $employee->present_address }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Employee Mobile
                                                    </span>
                                                    <input type="text" name="employee_mobile" id="employee_mobile" class="form-control" value="{{ $employee->personal_mobile }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Effect From
                                                    </span>
                                                    <input type="text" name="effective_date" id="effective_date" class="form-control flatdate" value="{{ old('effective_date', date('Y-m-d')) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Gazette
                                                    </span>
                                                    <select name="salary_setup_id" id="salary_setup_id" class="form-control tom-select">
                                                        <option value="">Select Gazette</option>
                                                        @foreach ($salarySetups as $key => $value)
                                                            <option value="{{ $value->id }}" 
                                                                data-basic="{{ $value->basic }}"
                                                                data-house_rent="{{ $value->house_rent }}"
                                                                data-conveyance="{{ $value->conveyance }}"
                                                                data-is_conveyance_fixed="{{ $value->is_conveyance_fixed }}"
                                                                data-medical="{{ $value->medical }}"
                                                                data-is_medical_fixed="{{ $value->is_medical_fixed }}"
                                                                data-others="{{ $value->others }}"
                                                                data-is_others_fixed="{{ $value->is_others_fixed }}" {{ old('salary_setup_id', $employeeSalary->salary_setup_id) == $value->id ? 'selected' : ''}}>{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Tax 
                                                    </span>
                                                    <input type="text" name="tax" id="tax" class="form-control" value="{{ old('tax', number_format($employeeSalary->tax)) }}">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead >
                                                    <tr>
                                                        <th class="text-center">Basic</th>
                                                        <th class="text-center">House Rent</th>
                                                        <th class="text-center">Conveyance</th>
                                                        <th class="text-center">Medical</th>
                                                        <th class="text-center">Others</th>
                                                        <th class="text-center">Gross</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @dd( old('basic', number_format($employeeSalary->basic))) --}}
                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="number" name="basic" id="basic" value="{{ old('basic', numberFormat($employeeSalary->basic)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="house_rent" id="house_rent" value="{{ old('house_rent', numberFormat($employeeSalary->house_rent)) }}"  class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="conveyance" id="conveyance" value="{{ old('conveyance', numberFormat($employeeSalary->conveyance)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="medical" id="medical" value="{{ old('medical', numberFormat($employeeSalary->medical)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="others" id="others" value="{{ old('others', numberFormat($employeeSalary->others)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="gross" id="gross" value="{{ old('gross', numberFormat($employeeSalary->gross)) }}" class="form-control">
                                                        </td>
                                                    </tr>
                                                </tbody>


                                            </table>
                                        </div>
                                    </div>

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        @if($employeeSalary->exists)
                                        <a href="{{ route('hrm.employee-salarys.create', ['employee_id' => $employeeSalary->employee_id]) }}" 
                                            class="btn btn-warning btn-sm btn-squared shadow-sm">
                                            New Add
                                         </a>                                        @endif
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">{{ $employeeSalary->exists ? 'Update' : 'Submit' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <h2 class="mb-25">Salary List</h2>
                        <div class="col-md-12">
                            <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead >
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        <th class="text-center">Effect From</th>
                                        <th class="text-center">Basic</th>
                                        <th class="text-center">House Rent</th>
                                        <th class="text-center">Conveyance</th>
                                        <th class="text-center">Medical</th>
                                        <th class="text-center">Others</th>
                                        <th class="text-center">Gross</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employeeSalaries as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $value->effective_date }}</td>
                                            <td class="text-center">{{ number_format($value->basic) }}</td>
                                            <td class="text-center">{{ number_format($value->house_rent) }}</td>
                                            <td class="text-center">{{ number_format($value->conveyance) }}</td>
                                            <td class="text-center">{{ number_format($value->medical) }}</td>
                                            <td class="text-center">{{ number_format($value->others) }}</td>
                                            <td class="text-center">{{ number_format($value->gross) }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-round badge-{{ $value->status == 1 ? 'success' : 'danger' }}">{{ $value->status == 1 ? 'Active' : 'Inactive' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('hrm.employee-salarys.create', ['employee_id' => $employee->id, 'salary_id' => $value->id]) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            data-action="{{ route('hrm.employee-salarys.destroy', $value->id) }}"
                                                            class="btn btn-danger delete-confirm"
                                                            title="Delete"><i class="far fa-trash-alt"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        </div>
                        <div class="d-none">
                            <form class="delete-form" action="" method="POST">
                                @csrf
                                @method('DELETE')
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
   $(document).ready(function() {
    $('#basic, #house_rent, #conveyance, #medical, #others').on('keyup', function() {
        var basic = parseFloat($('#basic').val()) || 0;
        var houseRent = parseFloat($('#house_rent').val()) || 0;
        var conveyance = parseFloat($('#conveyance').val()) || 0;
        var medical = parseFloat($('#medical').val()) || 0;
        var others = parseFloat($('#others').val()) || 0;
        var gross = basic + houseRent + conveyance + medical + others;
        $('#gross').val(gross.toFixed());
    });
});
</script>
<script>
  
    $(document).ready(function() {
    $('#gross').on('input', calculateSalaries);
    $('#salary_setup_id').on('change', calculateSalaries);

    function calculateSalaries() {
        var gross = parseFloat($('#gross').val()) || 0;
        var selectedOption = $('#salary_setup_id option:selected');

        if (selectedOption.val()) {
            var basicPercentage = parseFloat(selectedOption.attr('data-basic')) || 0;
            var houseRentPercentage = parseFloat(selectedOption.attr('data-house_rent')) || 0;
            var conveyancePercentage = parseFloat(selectedOption.attr('data-conveyance')) || 0;
            var isConveyanceFixed = parseInt(selectedOption.attr('data-is_conveyance_fixed')) || 0;
            var medicalPercentage = parseFloat(selectedOption.attr('data-medical')) || 0;
            var isMedicalFixed = parseInt(selectedOption.attr('data-is_medical_fixed')) || 0;
            var othersPercentage = parseFloat(selectedOption.attr('data-others')) || 0;
            var isOthersFixed = parseInt(selectedOption.attr('data-is_others_fixed')) || 0;

            if (!gross) {
                $('#basic').val(0);
                $('#house_rent').val(0);
                $('#conveyance').val(0);
                $('#medical').val(0);
                $('#others').val(0);
                return;
            }
            var basic, houseRent, conveyance, medical, others, availablegross;

           if(isConveyanceFixed == 1 && isMedicalFixed == 1 && isOthersFixed == 1) {
                conveyance = conveyancePercentage;
                medical = medicalPercentage;
                others =  othersPercentage;
                availablegross = gross - conveyance - medical - others;
                basic = availablegross * (basicPercentage / 100);
                houseRent = availablegross * (houseRentPercentage / 100);
              
            } else if(isConveyanceFixed == 1 && isMedicalFixed == 0 && isOthersFixed == 0) {
                conveyance =  conveyancePercentage;
                availablegross = gross - conveyance;
                basic = availablegross * (basicPercentage / 100);
                houseRent = availablegross * (houseRentPercentage / 100);
                medical =  availablegross * (medicalPercentage / 100);
                others =  availablegross * (othersPercentage / 100);
            } else if(isMedicalFixed == 1 && isConveyanceFixed == 0 && isOthersFixed == 0) {
                medical =  medicalPercentage;
                availablegross = gross - medical;
                basic = availablegross * (basicPercentage / 100);
                houseRent = availablegross * (houseRentPercentage / 100);
                conveyance =  availablegross * (conveyancePercentage / 100);
                others =  availablegross * (othersPercentage / 100);
            } else if(isOthersFixed == 1 && isConveyanceFixed == 0 && isMedicalFixed == 0) {
                others =  othersPercentage;
                availablegross = gross - others;
                basic = availablegross * (basicPercentage / 100);
                houseRent = availablegross * (houseRentPercentage / 100);
                conveyance =  availablegross * (conveyancePercentage / 100);
                medical =  availablegross * (medicalPercentage / 100);
            } else if(isConveyanceFixed == 1 && isMedicalFixed == 1 && isOthersFixed == 0) {
                conveyance = conveyancePercentage;
                medical = medicalPercentage;
                availablegross = gross - conveyance - medical;
                basic = availablegross * (basicPercentage / 100);
                houseRent = availablegross * (houseRentPercentage / 100);
                others =  availablegross * (othersPercentage / 100);
            } else if(isConveyanceFixed == 1 && isMedicalFixed == 0 && isOthersFixed == 1) {
                conveyance = conveyancePercentage;
                others =  othersPercentage;
                availablegross = gross - conveyance - others;
                basic = availablegross * (basicPercentage / 100);
                houseRent = availablegross * (houseRentPercentage / 100);
                medical =  availablegross * (medicalPercentage / 100);
            } else if(isConveyanceFixed == 0 && isMedicalFixed == 1 && isOthersFixed == 1) {
                medical = medicalPercentage;
                others =  othersPercentage;
                availablegross = gross - medical - others;
                basic = availablegross * (basicPercentage / 100);
                houseRent = availablegross * (houseRentPercentage / 100);
                conveyance =  availablegross * (conveyancePercentage / 100);
            }else{
                conveyance =  gross * (conveyancePercentage / 100);
                medical =  gross * (medicalPercentage / 100);
                others =  gross * (othersPercentage / 100);
                basic = gross * (basicPercentage / 100);
                houseRent = gross * (houseRentPercentage / 100);
            }

            $('#basic').val(basic.toFixed());
            $('#house_rent').val(houseRent.toFixed());
            $('#conveyance').val(conveyance.toFixed());
            $('#medical').val(medical.toFixed());
            $('#others').val(others.toFixed());
        }
    }
});
</script>


@endSection
