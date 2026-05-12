{{-- @dd($salaryGenerate) --}}
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
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.create-salary-setup-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.salary-generates.index'))
                            <a href="{{ route('hrm.salary-generates.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Salary Generate') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ route('hrm.salary-generates.update', $salaryGenerate->id)}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                         <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Employee Name
                                                    </span>
                                                    <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ $salaryGenerate->employee->full_name }}" readonly>
                                                    <input type="hidden" name="employee_id" id="employee_id" class="form-control" value="{{ $salaryGenerate->employee_id }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Employee Address
                                                    </span>
                                                    <input type="text" name="employee_address" id="employee_address" class="form-control" value="{{ $salaryGenerate->employee->present_address }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Employee Mobile
                                                    </span>
                                                    <input type="text" name="employee_mobile" id="employee_mobile" class="form-control" value="{{ $salaryGenerate->employee->personal_mobile }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-25">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                       Status
                                                    </span>
                                                    <select name="status" id="status" class="form-control tom-select">
                                                        <option value="Paid" {{ $salaryGenerate->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                                        <option value="UnPaid" {{ $salaryGenerate->status == 'UnPaid' ? 'selected' : '' }}>Unpaid</option>
                                                        <option value="Partially Paid" {{ $salaryGenerate->status == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-25">
                                            <div class="form-group mb-25">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Pay Date 
                                                    </span>
                                                    <input type="text" name="pay_date" id="pay_date" class="form-control flatdate" value="{{ optional($salaryGenerate)->pay_date }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mt-30">Gross Salary</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">

                                            <table class="table table-bordered">
                                                <thead >
                                                    <tr>
                                                        <th class="text-center">Basic</th>
                                                        <th class="text-center">House Rent</th>
                                                        <th class="text-center">Conveyance</th>
                                                        <th class="text-center">Medical</th>
                                                        <th class="text-center">Others</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="number" name="basic" id="basic" value="{{ old('basic', numberFormat($salaryGenerate->basic)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="house_rent" id="house_rent" value="{{ old('house_rent', numberFormat($salaryGenerate->house_rent)) }}"  class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="conveyance" id="conveyance" value="{{ old('conveyance', numberFormat($salaryGenerate->conveyance)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="medical" id="medical" value="{{ old('medical', numberFormat($salaryGenerate->medical)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="others" id="others" value="{{ old('others', numberFormat($salaryGenerate->others)) }}" class="form-control">
                                                        </td>
                                                       
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                    <h5 class="mt-30">(+)Other Earnings</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">

                                            <table class="table table-bordered">
                                                <thead >
                                                    <tr>
                                                        <th class="text-center">Over Time Pay</th>
                                                        <th class="text-center">Double Time Pay</th>
                                                        <th class="text-center">Commission</th>
                                                        <th class="text-center">Bonus</th>
                                                        <th class="text-center">Leave Encharshment</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="number" name="ot_pay" id="ot_pay" value="{{ old('ot_pay', numberFormat($salaryGenerate->ot_pay)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" name="double_time_pay" id="double_time_pay" value="{{ old('double_time_pay', numberFormat($salaryGenerate->double_time_pay)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="commission" id="commission" value="{{ old('commission', numberFormat($salaryGenerate->commission)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="bonus" id="bonus" value="{{ old('bonus', numberFormat($salaryGenerate->bonus)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="leave_encashment" id="leave_encashment" value="{{ old('leave_encashment', numberFormat($salaryGenerate->leave_encashment)) }}" class="form-control">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="mt-30">(-)Deduction</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">

                                            <table class="table table-bordered">
                                                <thead >
                                                    <tr>
                                                        <th class="text-center">Advance</th>
                                                        <th class="text-center">Loan</th>
                                                        <th class="text-center">No pay leave</th>
                                                        <th class="text-center">Advance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="text" name="advance" id="advance" value="{{ old('advance', numberFormat($salaryGenerate->advance)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="loan" id="loan" value="{{ old('loan', numberFormat($salaryGenerate->loan)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="no_pay_leave" id="no_pay_leave" value="{{ old('no_pay_leave', numberFormat($salaryGenerate->no_pay_leave
                                                            , 2)) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" name="absence" id="absence" value="{{ old('absence', numberFormat($salaryGenerate->absence)) }}" class="form-control">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="mt-30">(-)Deduction (Tax)</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">

                                            <table class="table table-bordered">
                                                <thead >
                                                    <tr>
                                                        <th class="text-center">Tax</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="text" name="tax" id="tax" value="{{ old('tax', numberFormat($salaryGenerate->tax)) }}" class="form-control">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mt-30">Total</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead >
                                                        <tr>
                                                            <th class="text-center">Gross Salary</th>
                                                            <th class="text-center">Total other earnings</th>
                                                            <th class="text-center">Total earning</th>
                                                            <th class="text-center">Total deduction</th>
                                                            <th class="text-center">Net Tax</th>
                                                            <th class="text-center">Net Earnings</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="text" name="gross" id="gross" value="{{ old('gross', numberFormat($salaryGenerate->gross)) }}" class="form-control" readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" name="total_other_earnings" id="total_other_earnings" value="{{ old('total_other_earnings', numberFormat($salaryGenerate->total_other_earnings
                                                                , 2)) }}" class="form-control" readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" name="total_earnings" id="total_earnings" value="{{ old('total_earnings', numberFormat($salaryGenerate->total_earnings
                                                                , 2)) }}" class="form-control" readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" name="total_deductions" id="total_deductions" value="{{ old('total_deductions', numberFormat($salaryGenerate->total_deductions
                                                                , 2)) }}" class="form-control" readonly>    
                                                            </td>   
                                                            <td class="text-center">
                                                                <input type="text" name="total_tax" id="total_tax" value="{{ old('total_tax', numberFormat($salaryGenerate->total_tax)) }}" class="form-control" readonly>
                                                            </td>   
                                                            <td class="text-center">
                                                                <input type="text" name="net_earning" id="net_earning" value="{{ old('net_earning', numberFormat($salaryGenerate->net_earning)) }}" class="form-control" readonly>
                                                            </td>                                          
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
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
        function calculateGross() {
            var basic = parseFloat($('#basic').val()) || 0;
            var houseRent = parseFloat($('#house_rent').val()) || 0;
            var conveyance = parseFloat($('#conveyance').val()) || 0;
            var medical = parseFloat($('#medical').val()) || 0;
            var others = parseFloat($('#others').val()) || 0;
            var gross = basic + houseRent + conveyance + medical + others;
            $('#gross').val(gross.toFixed());
            calculateNetEarning();
        }

        function calculateOtherEarnings() {
            var otPay = parseFloat($('#ot_pay').val()) || 0;
            var doubleTimePay = parseFloat($('#double_time_pay').val()) || 0;
            var commission = parseFloat($('#commission').val()) || 0;
            var bonus = parseFloat($('#bonus').val()) || 0;
            var leaveEncashment = parseFloat($('#leave_encashment').val()) || 0;
            var totalOtherEarnings = otPay + doubleTimePay + commission + bonus + leaveEncashment;
            $('#total_other_earnings').val(totalOtherEarnings.toFixed());
            calculateNetEarning();
        }

        function calculateDeductions() {
            var advance = parseFloat($('#advance').val()) || 0;
            var loan = parseFloat($('#loan').val()) || 0;
            var noPayLeave = parseFloat($('#no_pay_leave').val()) || 0;
            var absence = parseFloat($('#absence').val()) || 0;
            var totalDeductions = advance + loan + noPayLeave + absence;
            $('#total_deductions').val(totalDeductions.toFixed());
            calculateNetEarning();
        }

        function calculateTax() {
            var tax = parseFloat($('#tax').val()) || 0;
            $('#total_tax').val(tax.toFixed());
            calculateNetEarning();
        }

        function calculateNetEarning() {
            var gross = parseFloat($('#gross').val()) || 0;
            var totalOtherEarnings = parseFloat($('#total_other_earnings').val()) || 0;
            var totalDeductions = parseFloat($('#total_deductions').val()) || 0;
            var totalTax = parseFloat($('#total_tax').val()) || 0;
            var netEarning = gross + totalOtherEarnings - totalDeductions - totalTax;
            $('#net_earning').val(netEarning.toFixed());
            calculateNetEarning();
        }

        // Event listeners
        $('#basic, #house_rent, #conveyance, #medical, #others').on('keyup change', calculateGross);
        $('#ot_pay, #double_time_pay, #commission, #bonus, #leave_encashment').on('keyup change', calculateOtherEarnings);
        $('#advance, #loan, #no_pay_leave, #absence').on('keyup change', calculateDeductions);
        $('#tax').on('keyup change', calculateTax);

        
    });
</script>


@endSection
