@section('title', 'Salary Structure')
@section('description', 'Salary Structure')
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
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ $employeeSalary->exists ? route('hrm.employee-salarys.update', $employeeSalary->id) : route('hrm.employee-salarys.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if ($employeeSalary->exists)
                                        @method('PUT')
                                    @endif
                                    <div class="row g-3">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="card p-3">
                                                <!-- Employee Info -->
                                                <div class="mb-3">
                                                    <label class="form-label">Employee Name</label>
                                                    <input type="text" name="employee_name" class="form-control" value="{{ $employee->full_name }}" readonly>
                                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Salary Group</label>
                                                    <select name="salary_setup_id" id="salary_setup_id" class="form-control tom-select">
                                                        <option value="">Select Salary Group</option>
                                                        @foreach ($salarySetups as $key => $value)
                                                            <option value="{{ $value->id }}" 
                                                                data-basic="{{ $value->basic }}"
                                                                data-house_rent="{{ $value->house_rent }}"
                                                                data-conveyance="{{ $value->conveyance }}"
                                                                data-medical="{{ $value->medical }}"
                                                                data-entertainment="{{ $value->entertainment }}"
                                                                data-is_medical_fixed="{{ $value->is_medical_fixed }}"
                                                                data-leave_fare="{{ $value->leave_fare }}"
                                                                data-utility="{{ $value->utility }}"
                                                                data-unkeep="{{ $value->unkeep }}"
                                                                data-others="{{ $value->others }}"
                                                                data-is_house_rent_basic="{{ $value->is_house_rent_basic }}"
                                                                data-is_conveyance_basic="{{ $value->is_conveyance_basic }}"
                                                                data-is_medical_basic="{{ $value->is_medical_basic }}"
                                                                data-is_entertainment_basic="{{ $value->is_entertainment_basic }}"
                                                                data-is_leave_fare_basic="{{ $value->is_leave_fare_basic }}"
                                                                data-is_utility_basic="{{ $value->is_utility_basic }}"
                                                                data-is_unkeep_basic="{{ $value->is_unkeep_basic }}"
                                                                data-is_others_basics="{{ $value->is_others_basics }}" {{ old('salary_setup_id', $employeeSalary->salary_setup_id) == $value->id ? 'selected' : ''}}>{{ $value->title }}
                                                            </option>  
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Effect From</label>
                                                        <input type="text" name="effective_date" class="form-control flatdate" value="{{ old('effective_date', date('Y-m-d')) }}">
                                                            @error('effective_from')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                    </div> 

                                                    <div class="col-md-6">
                                                        <label class="form-label">Tax</label>
                                                        <input type="text" name="tax" class="form-control" value="{{ old('tax', number_format($employeeSalary->tax)) }}">
                                                    </div>
                                                </div>

                                                <div class="row g-3 mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Is Consolidate <span class="text-danger">*</span></label>
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="is_consolidate" id="consolidate_yes" value="1" 
                                                                    {{ old('is_consolidate', $employeeSalary->is_consolidate ?? 1) == 1 ? 'checked' : '' }} >
                                                                <label class="form-check-label">Yes</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="is_consolidate" id="consolidate_no" value="0"
                                                                    {{ old('is_consolidate', $employeeSalary->is_consolidate ?? 1) == 0 ? 'checked' : '' }}  >
                                                                <label class="form-check-label">No</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="payment_type" value="bank" 
                                                                    {{ old('payment_type', $employeeSalary->payment_type ?? "bank") == "bank" ? 'checked' : '' }}>
                                                                <label class="form-check-label">Bank</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="payment_type" value="cash"  
                                                                    {{ old('payment_type', $employeeSalary->payment_type ?? "bank") == "cash" ? 'checked' : '' }}>
                                                                <label class="form-check-label">Cash</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column -->
                                        <div class="col-md-6">
                                            <div class="card p-3">
                                                <label class="form-label">Add Salary Breakup <span class="text-danger">*</span></label>
                                                <select id="salaryBreakupSelect" class="form-select mb-3"> 
                                                    <option value="">Select Salary Breakup</option>
                                                    @foreach ($salaryBreakdown as $key => $sb)
                                                        <option value="{{ $sb->value}}">{{ $sb->type}}</option>
                                                    @endforeach 
                                                </select>

                                                <div class="table-responsive">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Breakup Name</th>
                                                                <th>Amount</th>
                                                                <th>Increase Amount</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="breakupList"></tbody>
                                                    </table>
                                                </div>

                                                <!-- Summary -->
                                                <div class="row g-2 mt-3">
                                                    <div class="col-md-3">
                                                        <label>Salary Amount</label>
                                                        <input type="text" id="salaryAmount" class="form-control">
                                                    </div>
                                                    <div class="col-md-1 d-flex justify-content-center align-items-end">
                                                        +
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Increased Amount</label>
                                                        <input type="text" id="totalIncrease" class="form-control">
                                                    </div>
                                                    <div class="col-md-1 d-flex justify-content-center align-items-end">
                                                        =
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Total Amount</label>
                                                        <input type="text" id="gross"  name="gross"  class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        @if($employeeSalary->exists)
                                            <a href="{{ route('hrm.employee-salarys.create', ['employee_id' => $employeeSalary->employee_id]) }}" 
                                                class="btn btn-warning btn-sm btn-squared shadow-sm">
                                                New Add
                                            </a>                                        
                                        @endif
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
                                        <th class="text-center">Is Consolidate</th>
                                        <th class="text-center">Payment Type</th>
                                        <th class="text-center">Breakup Details</th> 
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    @foreach ($employeeSalaries as $key => $value)
                                        <tr>  
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $value->effective_date }}</td>
                                            <td class="text-center">{{ $value->is_consolidate == 1 ? 'Yes' : 'No' }}</td>
                                            <td class="text-center">{{ ucfirst($value->payment_type) }}</td> 
                                            <td class="text-left">
                                                @php
                                                    $fields = [
                                                        'Basic' => ['amount' => $value->basic, 'increase' => $value->increase_basic],
                                                        'House Rent' => ['amount' => $value->house_rent, 'increase' => $value->increase_house_rent],
                                                        'Medical' => ['amount' => $value->medical, 'increase' => $value->increase_medical],
                                                        'Conveyance' => ['amount' => $value->conveyance, 'increase' => $value->increase_conveyance],
                                                        'Entertainment' => ['amount' => $value->entertainment, 'increase' => $value->increase_entertainment],
                                                        'Leave Fare' => ['amount' => $value->leave_fare, 'increase' => $value->increase_leave_fare],
                                                        'Utility' => ['amount' => $value->utility, 'increase' => $value->increase_utility],
                                                        'Unkeep' => ['amount' => $value->unkeep, 'increase' => $value->increase_unkeep],
                                                        'Others' => ['amount' => $value->others, 'increase' => $value->increase_others],
                                                    ];
                                                @endphp

                                                @foreach($fields as $label => $data)
                                                    @if($data['amount'] > 0 || $data['increase'] > 0)
                                                        <div class="d-flex border-bottom py-1">
                                                            <div style="width:40%">{{ $label }}</div>
                                                            <div style="width:30%" class="text-end">
                                                                {{ number_format($data['amount'], 2) }}
                                                            </div>
                                                            <div style="width:30%" class="text-end">
                                                                {{ number_format($data['increase'], 2) }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td> 
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
    let empSalary = @json($employeeSalary ?? null); 
    $(document).ready(function() { 
        const fields = {
            basic: 'Basic',
            house_rent: 'House Rent',
            conveyance: 'Conveyance',
            medical: 'Medical',
            entertainment: 'Entertainment',
            leave_fare: 'Leave Fare',
            utility: 'Utility',
            unkeep: 'Unkeep',
            others: 'Others'
        };
        var str ="";
        let salaryAmt = 0;
        let increaseAmt = 0;

        if(empSalary){ 
            $.each(fields, function(key, label) { 

                // মূল value & increase 
                let amount = parseFloat(empSalary[key] ?? 0);
                let increase = parseFloat(empSalary['increase_'+key] ?? 0); 

                // 0 হলে skip
                if(amount == 0 && increase == 0) return true; // continue to next
                
                salaryAmt += amount;
                increaseAmt += increase; 

                // 2 decimal format
                amount = parseFloat(amount).toFixed(2);
                increase = parseFloat(increase).toFixed(2);

                
                str += '<tr>';
                str += '<td width="30%" data-value="'+key+'">'+label+'</td>';
                str += '<td width="30%"><input type="number" name="'+key+'" id="'+key+'" value="'+amount+'" class="form-control" readonly></td>';
                str += '<td width="30%"><input type="number" name="increase_'+key+'" id="increase_'+key+'" value="'+increase+'" class="form-control" readonly></td>';
                str += '<td width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button></td>';
                str += '</tr>'; 
            });

            $('#salaryAmount').val(parseFloat(salaryAmt).toFixed(2));
            $('#totalIncrease').val(parseFloat(increaseAmt).toFixed(2));
            $('#gross').val(parseFloat(salaryAmt + increaseAmt).toFixed(2));

            $('#breakupList').append(str);

        }


        $('#salaryBreakupSelect').change(function() {
            var selectedText = $(this).find('option:selected').text();
            var selectedValue = $(this).val();
            var str = '';
            if(selectedValue) {
                // Check if already exists
                if($('#breakupList td[data-value="'+selectedValue+'"]').length == 0) {
                    str += '<tr>';
                    str += '<td  width="30%" data-value="'+selectedValue+'">'+selectedText+'</td>';
                    str += '<td  width="30%"><input type="number" name="'+selectedValue+'" id="'+selectedValue+'" value="0" class="form-control"></td>';
                    str += '<td  width="30%"><input type="number" name="increase_'+selectedValue+'" id="increase_'+selectedValue+'" value="0" class="form-control"></td>';
                    str += '<td  width="10%"> <button type="button" class="remove-salary-break-up"><i class="fa fa-trash"></i></button></td>';
                    str += '</tr>';
                    $('#breakupList').append(str);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops!',
                        text: 'Already added!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }
            // Reset select to default
            $(this).val('');
        });


        $(document).on("click", ".remove-salary-break-up", function() {
            $(this).closest("tr").remove();
        });

        function toggleSalaryBreakup() {
            // Get value of checked radio by name
            var val = $('input[name="is_consolidate"]:checked').val();
            if(val === '1') {  // yes
                $('#salaryBreakupSelect').prop('disabled', true);
                $(".remove-salary-break-up").addClass('d-none'); 
            } else {          // no
                $('#salaryBreakupSelect').prop('disabled', false);
                $(".remove-salary-break-up").removeClass('d-none');
            }
        }

        // Initial check
        toggleSalaryBreakup();

        // On radio change
        $('input[name="is_consolidate"]').change(function() {
            toggleSalaryBreakup();
        });

   
       $('#salaryAmount, #totalIncrease').on('keyup', function() {
            var selectedOption = $('#salary_setup_id option:selected');

            let salaryAmt = parseFloat($('#salaryAmount').val()) || 0;
            let increaseAmt =  parseFloat($('#totalIncrease').val()) || 0;
            let basic = houseRent = conveyance = medical = entertainment = leaveFare = utility = unkeep = others = 0;
            let increaseBasic = increaseHouseRent = increaseConveyance = increaseMedical = increaseEntertainment = increaseLeaveFare = increaseUtility = increaseUnkeep = increaseOthers = 0
            
            var basicPercentage = parseFloat(selectedOption.attr('data-basic')) || 0;
            var houseRentPercentage = parseFloat(selectedOption.attr('data-house_rent')) || 0;
            var conveyancePercentage = parseFloat(selectedOption.attr('data-conveyance')) || 0; 
            var medicalPercentage = parseFloat(selectedOption.attr('data-medical')) || 0;
            var entertainmentPercentage = parseInt(selectedOption.attr('data-entertainment')) || 0;
            var leave_farePercentage = parseFloat(selectedOption.attr('data-leave_fare')) || 0;
            var utilityPercentage = parseInt(selectedOption.attr('data-utility')) || 0;
            var unkeepPercentage = parseFloat(selectedOption.attr('data-unkeep')) || 0;
            var othersPercentage = parseInt(selectedOption.attr('data-others')) || 0;

            var isHouseRent = parseFloat(selectedOption.attr('data-is_house_rent_basic')) || 0;
            var isConveyance = parseFloat(selectedOption.attr('data-is_conveyance_basic')) || 0;
            var isMedical = parseFloat(selectedOption.attr('data-is_medical_basic')) || 0;
            var isEntertainment = parseInt(selectedOption.attr('data-is_entertainment_basic')) || 0;
            var isLeaveFare = parseFloat(selectedOption.attr('data-is_leave_fare_basic')) || 0;
            var isUtility = parseInt(selectedOption.attr('data-is_utility_basic')) || 0;
            var isUnkeep = parseFloat(selectedOption.attr('data-is_unkeep_basic')) || 0;
            var isothers = parseInt(selectedOption.attr('data-is_others_basics')) || 0;  
 

            
            if( basicPercentage != 0)
            {
                basic = (salaryAmt * basicPercentage)/100;
                increaseBasic = (increaseAmt * basicPercentage)/100; 
            }
            if( houseRentPercentage != 0)
            { 
                houseRent = (salaryAmt * houseRentPercentage)/100;
                increaseHouseRent = (increaseAmt * houseRentPercentage)/100; 
                 

            }
            
            if( conveyancePercentage != 0)
            { 
                conveyance = (salaryAmt * conveyancePercentage)/100;
                increaseConveyance = (increaseAmt * conveyancePercentage)/100; 


            }

            if( medicalPercentage != 0)
            {
                medical = (salaryAmt * medicalPercentage)/100;
                increaseMedical = (increaseAmt * medicalPercentage)/100; 



            }

            if( entertainmentPercentage != 0)
            { 
                entertainment = (salaryAmt * entertainmentPercentage)/100;
                increaseEntertainment = (increaseAmt * entertainmentPercentage)/100; 


            }

            if( leave_farePercentage != 0)
            { 
                leaveFare = (salaryAmt * leave_farePercentage)/100;
                increaseLeaveFare = (increaseAmt * leave_farePercentage)/100; 

                

            }

            if( utilityPercentage != 0)
            {  
                utility = (salaryAmt * utilityPercentage)/100;
                increaseUtility = (increaseAmt * utilityPercentage)/100; 
 
            }

            
            if( unkeepPercentage != 0)
            {  
                unkeep = (salaryAmt * unkeepPercentage)/100;
                increaseUnkeep = (increaseAmt * unkeepPercentage)/100; 


            }

            
            if( othersPercentage != 0)
            {  
                others = (salaryAmt * othersPercentage)/100;
                increaseOthers = (increaseAmt * othersPercentage)/100; 


            }
 
                    
                   

            
            $('#basic').val(basic.toFixed());
            $('#house_rent').val(houseRent.toFixed());
            $('#conveyance').val(conveyance.toFixed());
            $('#medical').val(medical.toFixed());
            $('#entertainment').val(entertainment.toFixed());
            $('#leave_fare').val(leaveFare.toFixed());
            $('#utility').val(utility.toFixed());
            $('#unkeep').val(unkeep.toFixed());
            $('#others').val(others.toFixed()); 

            $('#increase_basic').val(increaseBasic.toFixed());
            $('#increase_house_rent').val(increaseHouseRent.toFixed());
            $('#increase_conveyance').val(increaseConveyance.toFixed());
            $('#increase_medical').val(increaseMedical.toFixed());
            $('#increase_entertainment').val(increaseEntertainment.toFixed());
            $('#increase_leave_fare').val(increaseLeaveFare.toFixed());
            $('#increase_utility').val(increaseUtility.toFixed());
            $('#increase_unkeep').val(increaseUnkeep.toFixed());
            $('#increase_others').val(increaseOthers.toFixed()); 

            $('#gross').val((salaryAmt+increaseAmt).toFixed()); 
 
        });


        $('#salary_setup_id,#consolidate_yes').change(function() {
            var selectedOption = $('#salary_setup_id option:selected');

            var basicPercentage = parseFloat(selectedOption.attr('data-basic')) || 0;
            var houseRentPercentage = parseFloat(selectedOption.attr('data-house_rent')) || 0;
            var conveyancePercentage = parseFloat(selectedOption.attr('data-conveyance')) || 0; 
            var medicalPercentage = parseFloat(selectedOption.attr('data-medical')) || 0;
            var entertainmentPercentage = parseInt(selectedOption.attr('data-entertainment')) || 0;
            var leave_farePercentage = parseFloat(selectedOption.attr('data-leave_fare')) || 0;
            var utilityPercentage = parseInt(selectedOption.attr('data-utility')) || 0;
            var unkeepPercentage = parseFloat(selectedOption.attr('data-unkeep')) || 0;
            var othersPercentage = parseInt(selectedOption.attr('data-others')) || 0;
   
            str = "";
            $('#breakupList').html('');

            if(basicPercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="basic">Basic</td>';
                str += '<td  width="30%"><input type="number" name="basic" id="basic" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_basic" id="increase_basic" value="0" class="form-control" readonly ></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            }

            if(houseRentPercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="house_rent">House Rent</td>';
                str += '<td  width="30%"><input type="number" name="house_rent" id="house_rent" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_house_rent" id="increase_house_rent" value="0" class="form-control" readonly ></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            }
            if(conveyancePercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="conveyance">Conveyance</td>';
                str += '<td  width="30%"><input type="number" name="conveyance" id="conveyance" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_conveyance" id="increase_conveyance" value="0" class="form-control" readonly></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            } 
 
            if(medicalPercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="medical">Medical</td>';
                str += '<td  width="30%"><input type="number" name="medical" id="medical" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_medical" id="increase_medical" value="0" class="form-control" readonly></td>';
                str += '<td  width="10%"> <button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button></td>';
                str += '</tr>'; 
            }
            if(entertainmentPercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="entertainment">Entertainment</td>';
                str += '<td  width="30%"><input type="number" name="entertainment" id="entertainment" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_entertainment" id="increase_entertainment" value="0" class="form-control" readonly></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            }
 
            if(leave_farePercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="leave_fare">Leave Fare</td>';
                str += '<td  width="30%"><input type="number" name="leave_fare" id="leave_fare" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_leave_fare" id="increase_leave_fare" value="0" class="form-control" readonly></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            }
            if(utilityPercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="utility">>Utility</td>';
                str += '<td  width="30%"><input type="number" name="utility" id="utility" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_utility" id="increase_utility" value="0" class="form-control" readonly></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            }

            if(unkeepPercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="unkeep">Unkeep</td>';
                str += '<td  width="30%"><input type="number" name="unkeep" id="unkeep" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_unkeep" id="increase_unkeep" value="0" class="form-control" readonly></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            }
            if(othersPercentage != 0 ){
                str += '<tr>';
                str += '<td  width="30%" data-value="others">Others</td>';
                str += '<td  width="30%"><input type="number" name="others" id="others" value="0" class="form-control" readonly></td>';
                str += '<td  width="30%"><input type="number" name="increase_others" id="increase_others" value="0" class="form-control" readonly></td>';
                str += '<td  width="10%"><button type="button" class="remove-salary-break-up d-none"><i class="fa fa-trash"></i></button> </td>';
                str += '</tr>'; 
            } 

            $('#breakupList').append(str);
            
            


        });
        
   
    
});
</script>


@endSection
