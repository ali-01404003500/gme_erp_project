@section('title', 'Salary Sheet')
@section('description', 'Salary Sheet')
@extends('layout.app')
@section('content') 
    <!-- CONTENT AREA -->
    
    <div class="container-fluid">
        <div class="social-dash-wrap">
            


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-right d-flex justify-content-end mb-2 no-print">
                                <div class="btn-group">
                                    <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                                        class="btn btn-danger btn-sm">
                                        <i class="fa fa-file-pdf"></i> PDF
                                    </a>

                                    
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
 


                <div class="col-md-12" id="printableArea">
                    <div class="card mb-4 print-wrapper">
                        <div class="card-body">
                            <div class="row text-center">
                                <header style="line-height: 1.2; margin-bottom: 0;">
                                    <h1 style="margin:0; padding:0;">Global Medical Engineering (BD) Ltd.</h1>
                                    <p style="margin:0; padding:0;">Provider of Medical Equipment & Solutions for Hospitals, Clinics And HealthCare Institutes.</p>
                                    <p style="margin:0; padding:0;">Address : 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000</p>
                                    <p style="margin:0; padding:0;">Hotline : +88 09678 020555 Mobile : +8801404003500</p>
                                    <p style="margin:0; padding:0;">e-mail : <a href="mailto:info@gmebd.com">info@gmebd.com</a> web: <a href="http://www.gmebd.com">www.gmebd.com</a></p>
                                </header>

                                <section class="title py-3">
                                    <h2>Salary Sheet</h2>
                                </section>
                            </div>

                            
                            <div class="table-responsive salary-table" > 
                                <table id="zero-config" class="table table-bordered dt-table-hover" style="width: 100%; table-layout: auto;">

                                    <thead>  
                                        <tr>
                                            <th colspan="{{ $salaryBreakdowns->count()+22 }}" class="text-start fSize">Employee Salary {{ \Carbon\Carbon::parse($payrool->year_month)->format('F Y'); }}</th> 
                                        </tr>
 

                                        <tr> 
                                            <th class="text-center rotate-header bg-none fSize">Sl</th>
                                            <th class="text-center bg-none fSize">Employee</th> 
                                            <th class="text-center rotate-header bg-none fSize">Days of <br>Month</th>
                                            <th class="text-center rotate-header bg-none fSize">Weekend</th>
                                            <th class="text-center rotate-header bg-none fSize">Holiday</th>
                                            <th class="text-center rotate-header bg-none fSize">Absent</th>
                                            <th class="text-center rotate-header bg-none fSize">Late</th>
                                            <th class="text-center rotate-header bg-none fSize">Leave</th>
                                            <th class="text-center rotate-header bg-none fSize">Worked <br>Days</th> 

                                            @foreach($salaryBreakdowns as $item)
                                                <th class="text-center rotate-header bg-none fSize"> 
                                                    {{ $item->type }}
                                                </th>
                                            @endforeach 

                                            <th class="text-center rotate-header bg-none fSize">Increment <br>Amount</th> 
                                            <th class="text-center rotate-header bg-none fSize">Gross <br>Salary</th> 
                                            <th class="text-center rotate-header bg-none fSize">Approved <br>Salary in (%)</th> 
                                            <th class="text-center rotate-header bg-none fSize">Absent <br>Deduction</th>  
                                            <th class="text-center rotate-header bg-none fSize">Late <br>Deduction</th> 
                                            <th class="text-center rotate-header bg-none fSize">Advance+Loan <br>Deduction</th>  
                                            <th class="text-center rotate-header bg-none fSize">Tax <br>Deduction</th> 
                                            <th class="text-center rotate-header bg-none fSize">Total <br>Deduction</th> 
                                            <th class="text-center rotate-header bg-none fSize">Net <br>Payable</th> 
                                            <th class="text-center rotate-header bg-none fSize">Payment <br>Method</th>  
                                            <th class="text-center bg-none fSize">Remarks</th> 
                                        </tr>
                                        
                                    </thead>
                                    <tbody> 
                                        @php
                                            $total_basic = 0;
                                            $total_house_rent = 0;
                                            $total_medical = 0;
                                            $total_conveyance = 0;
                                            $total_entertainment = 0;
                                            $total_leave_fare = 0;
                                            $total_utility = 0;
                                            $total_unkeep = 0;
                                            $total_others = 0;
                                            $totalincrement = 0;
                                            $totalgross = 0;

                                            $totalabsent = 0;
                                            $totallate = 0;
                                            $totaladvance = 0;
                                            $totalloan = 0;
                                            $totaltax = 0;
                                            $totaldeduction = 0;
                                            $totalearning = 0;
                                            $department = '';
                                            
                                        @endphp 

                                        @php 
                                            $subtotalbasic = $subtotalhouse_rent = $subtotalmedical = $subtotalconveyance = $subtotalentertainment = $subtotalleave_fare = $subtotalutility = $subtotalunkeep = 0;
                                            $subtotalothers = $subtotalincrement = $subtotalgross = $subtotalabsent = $subtotallate = $subtotaladvance = $subtotalloan = $subtotaltax = $subtotaldeduction = $subtotalearning = 0;
                                        @endphp
                                        {{-- @dd($salaryGenerates) --}}
                                        @foreach ($salaryGenerates as $key => $item)
                                            @php
                                                $total_basic += $item->basic;
                                                $total_house_rent += $item->house_rent;
                                                $total_medical += $item->medical;
                                                $total_conveyance += $item->conveyance;
                                                $total_entertainment += $item->entertainment;
                                                $total_leave_fare += $item->leave_fare;
                                                $total_utility += $item->utility;
                                                $total_unkeep += $item->unkeep;
                                                $total_others += $item->others;
                                                $totalincrement += 0; 
                                                $totalgross += $item->gross;

                                                $totalabsent += $item->absent_deduction;
                                                $totallate += $item->late_deduction;
                                                $totaladvance += $item->advance;
                                                $totalloan += $item->loan;
                                                $totaltax += $item->tax;
                                                $totaldeduction += $item->total_deductions;
                                                $totalearning += $item->net_earning;
                                                
                                            @endphp

                                            

                                            {{-- deparment row show --}}
                                            @if ($department != $item->employee->employementDetail->department->name) 

                                                {{-- deparment wise summation show --}} 
                                                @if($key != 0) 
                                                <tr>
                                                    <th colspan="9" class="text-end fSize">Sub Total</th>

                                                    @foreach($salaryBreakdowns as $sb)
                                                        @php
                                                            $varName = 'subtotal' . $sb->value;
                                                        @endphp

                                                        <th class="text-center fSize">
                                                            {{ isset($$varName) ? number_format($$varName) : 0 }}
                                                        </th> 
                                                    @endforeach  

                                                

                                                    <th class="text-center fSize">{{ numberFormat($subtotalincrement) }}</th> 
                                                    <th class="text-center fSize">{{ numberFormat($subtotalgross) }}</th> 
                                                

                                                    <th class="text-center fSize">&nbsp;</th>

                                                    <th class="text-center fSize">{{ numberFormat($subtotalabsent) }}</th> 
                                                    <th class="text-center fSize">{{ numberFormat($subtotallate) }}</th>  
                                                    <th class="text-center fSize">{{ numberFormat($subtotaladvance+$subtotalloan) }}</th> 
                                                    <th class="text-center fSize">{{ numberFormat($subtotaltax) }}</th> 
                                                    <th class="text-center fSize">{{ numberFormat($subtotaldeduction) }}</th> 

                                                    <th class="text-center fSize">{{ numberFormat($subtotalearning) }}</th>  
                                                    <th colspan="3" class="text-center fSize">&nbsp;</th>
                                                </tr> 
                                                @php 
                                                    $subtotalbasic = $subtotalhouse_rent = $subtotalmedical = $subtotalconveyance = $subtotalentertainment = $subtotalleave_fare = $subtotalutility = $subtotalunkeep = 0;
                                                    $subtotalothers = $subtotalincrement = $subtotalgross = $subtotalabsent = $subtotallate = $subtotaladvance = $subtotalloan = $subtotaltax = $subtotaldeduction = $subtotalearning = 0;
                                                @endphp

                                                @endif 
                                            
                                                <tr>
                                                    <th colspan="34" class="text-start">{{ optional(optional(optional($item->employee)->employementDetail)->department)->name }}</th>
                                                </tr>
                                                @php
                                                    $department = $item->employee->employementDetail->department->name;

                                                    $subtotalbasic += $item->basic;
                                                    $subtotalhouse_rent += $item->house_rent;
                                                    $subtotalmedical += $item->medical;
                                                    $subtotalconveyance += $item->conveyance;
                                                    $subtotalentertainment += $item->entertainment;
                                                    $subtotalleave_fare += $item->leave_fare;
                                                    $subtotalutility += $item->utility;
                                                    $subtotalunkeep += $item->unkeep;
                                                    $subtotalothers += $item->others;
                                                    $subtotalincrement = 0; 
                                                    $subtotalgross += $item->gross;

                                                    $subtotalabsent += $item->absent_deduction;
                                                    $subtotallate += $item->late_deduction;
                                                    $subtotaladvance += $item->advance;
                                                    $subtotalloan += $item->loan;
                                                    $subtotaltax += $item->tax;
                                                    $subtotaldeduction += $item->total_deductions;
                                                    $subtotalearning += $item->net_earning;

                                                @endphp
                                            @endif
                                            
                                            <tr> 
                                                <td class="text-center fSize">{{ $key + 1 }}</td>
                                                <td class="text-start fSize">
                                                    <span class="text-muted">{{ optional(optional($item->employee)->employementDetail)->card_no }}</span> <br> 
                                                    @if ($item->status == 'UnPaid' || $item->status == 'Partially Paid')
                                                        <a href="{{ route('hrm.salary-generates.edit', $item->id) }}" target="_blank">{{ $item->employee->full_name }}</a>
                                                    @else
                                                        <a href="{{ route('hrm.salary-generates.show', $item->id) }}" target="_blank">{{ $item->employee->full_name }}</a>
                                                    @endif
                                                    <br> 
                                                    <span class="text-muted"> {{ optional(optional(optional($item->employee)->employementDetail)->designation)->name }}</span>

                                                </td>    
                                                <td class="text-center fSize">{{ $item->total_days }}</td>
                                                <td class="text-center fSize">{{ $item->weekend }}</td>
                                                <td class="text-center fSize">{{ $item->holidays }}</td>
                                                <td class="text-center fSize">{{ $item->absent_days }}</td>
                                                <td class="text-center fSize">{{ $item->late_days }}</td>
                                                <td class="text-center fSize">{{ $item->leave_days }}</td>
                                                <td class="text-center fSize">{{ $item->working_days }}</td>

                                                @foreach($salaryBreakdowns as $sb)
                                                    <td class="text-center fSize"> 
                                                        {{ $sb->value && optional($item)->{$sb->value} !== null ? number_format(optional($item)->{$sb->value}) : 0}}
                                                    </td>
                                                @endforeach 


                                                <td class="text-center fSize">{{ 0 }}</td>
                                                <td class="text-center fSize">{{ numberFormat($item->gross) }}</td>
                                                <td class="text-center fSize">{{ numberFormat($item->approved_salary_ratio) }}%</td>
                                                <td class="text-center fSize">{{ numberFormat($item->absent_deduction) }}</td>
                                                <td class="text-center fSize">{{ numberFormat($item->late_deduction) }}</td>
                                                <td class="text-center fSize">{{ numberFormat($item->advance + $item->loan) }}</td> 
                                                <td class="text-center fSize">{{ numberFormat($item->tax) }}</td>
                                                <td class="text-center fSize">{{ numberFormat($item->total_deductions) }}</td> 
                                                <td class="text-center highlight-col fSize">{{ numberFormat($item->net_earning) }}</td> 
                                                <td class="text-center fSize">
                                                    @php
                                                        $badgeClass = match(strtolower($item->payment_method)) {
                                                            'cash' => 'bg-success',
                                                            'bank' => 'bg-primary',
                                                            default => 'bg-info',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ ucfirst(strtolower($item->payment_method)) }}
                                                    </span>
                                                </td>   
                                                <td class="text-center fSize"> {{ $item->remarks }}  </td> 
                                            </tr>
                                            
                                        @endforeach  
                                        <tr>
                                            <th colspan="9" class="text-end fSize">Sub Total</th>

                                            @foreach($salaryBreakdowns as $sb)
                                                @php
                                                    $varName = 'subtotal' . $sb->value;
                                                @endphp

                                                <th class="text-center fSize">
                                                    {{ isset($$varName) ? number_format($$varName) : 0 }}
                                                </th> 
                                            @endforeach  

                                        

                                            <th class="text-center fSize">{{ numberFormat($subtotalincrement) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($subtotalgross) }}</th> 
                                        

                                            <th class="text-center fSize">&nbsp;</th>

                                            <th class="text-center fSize">{{ numberFormat($subtotalabsent) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($subtotallate) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($subtotaladvance + $subtotalloan) }}</th>  
                                            <th class="text-center fSize">{{ numberFormat($subtotaltax) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($subtotaldeduction) }}</th> 

                                            <th class="text-center fSize">{{ numberFormat($subtotalearning) }}</th>  
                                            <th colspan="3" class="text-center fSize">&nbsp;</th>
                                        </tr> 
                                    </tbody>
                                    <tfoot> 
                                            
                                        <tr>
                                            <th colspan="9" class="text-end fSize">Total</th>

                                            @foreach($salaryBreakdowns as $sb)
                                                @php
                                                    $varName = 'total_' . $sb->value;
                                                @endphp

                                                <th class="text-center fSize">
                                                    {{ isset($$varName) ? number_format($$varName) : 0 }}
                                                </th> 
                                            @endforeach  

                                        

                                            <th class="text-center fSize">{{ numberFormat($totalincrement) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($totalgross) }}</th> 
                                        

                                            <th class="text-center fSize">&nbsp;</th>

                                            <th class="text-center fSize">{{ numberFormat($totalabsent) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($totallate) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($totaladvance + $totalloan) }}</th>  
                                            <th class="text-center fSize">{{ numberFormat($totaltax) }}</th> 
                                            <th class="text-center fSize">{{ numberFormat($totaldeduction) }}</th> 

                                            <th class="text-center fSize">{{ numberFormat($totalearning) }}</th>  
                                            <th colspan="3" class="text-center fSize">&nbsp;</th>
                                        </tr> 
                                    </tfoot>
                                </table>
                            </div> 
                            <!-- Signature/Footer Section -->
                            <div class="salary-signature-footer">
                                <div class="row mt-5">
                                    @foreach($item->verifications as $verification)
                                        <div class="col text-center mb-3 fSize" style="flex: 0 0 {{ 100 / max(count($item->verifications), 1) }}%;">
                                            <strong>{{ $verification->role_name }}</strong><br><br><br> 
                                            @if(!empty($verification->employee?->signature))
                                                <img src="{{ url($verification->employee->signature) }}" 
                                                    alt=""  style="height:60px; object-fit:contain;">
                                            @endif
                                            {{ $verification->employee->full_name ?? '' }}<br>
                                            <small>{{ $verification->employee->employementDetail->designation->name }}</small><br>
                                            <small>{{ $verification->employee->employementDetail->department->name }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="card-footer"> 
                            
                        </div>
                    </div>

                </div> 
            </div>
 
        </div>
    </div>


@endsection
<!-- CONTENT AREA -->
@section('page_scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <script> 
      

    </script>
 

    <style>
 

    thead th {
        position: sticky;
        top: 0; 
    }
    tfoot td {
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 2;
    }

    .highlight-col {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .highlight-col {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    input[type="checkbox"] {
        transform: scale(1.2);
    }
    .rotate-header {
        writing-mode: vertical-rl; /* vertical */
        transform: rotate(225deg); /* optional for direction */
        text-align: center; 
    }
 
     
</style>
@endsection
