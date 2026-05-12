 

<style>
    @import url('https://fonts.maateen.me/kalpurush/font.css');

    @media print {
        .no-print {
            display: none !important;
        }
    }

    .my-header {
      
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .my-footer {
      
        align-items: center;
        justify-content: center;
        text-align: center;
    }


    .my-header img {
        max-width: 100px;
        margin-right: 20px;
    }

    .my-header h1 {
        margin: 0;
        font-size: 50px;
        font-weight: bold;
        color: rgb(0, 0, 187);
    }

    .my-header p {
        margin: 5px 0;
        font-size: 12px;
    }

    .title {
        text-align: center;
        margin-bottom: 20px;
    }

    .title h2 {
        margin: 0;
        font-size: 20px;
        text-decoration: underline;
    }

    footer {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    footer p {
        margin: 10px 0;
        font-size: 14px;
        width: 45%;
        text-align: center;
    }
    .custom-table, .custom-table td, .custom-table th, .custom-table tr {
        padding: 2px 4px;          /* tight padding */
        text-align: left;
        border: 1px solid #000;    /* optional, for printing */
        white-space: nowrap;       /* prevent wrapping */
    } 


    
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
        transform: rotate(180deg); /* optional for direction */
        text-align: center; 
    }
    /* Container flex right */
    .print-btn-container {
        display: flex !important;
        justify-content: flex-end !important; /* right side */
        align-items: center;
        padding: 10px 15px; /* optional padding */
    }

    /* Print button styling */
    .print-btn {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 5px;
        background-color: #007bff;  /* bootstrap primary color */
        color: #fff;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;  /* icon + text spacing */
        transition: background 0.3s;
    }

    .print-btn:hover {
        background-color: #0056b3;
        cursor: pointer;
    }

</style>

<title>Salary Sheet {{ \Carbon\Carbon::parse($payrool->year_month)->format('F Y'); }}</title>

<div class="row" style="font-size: 12px!important;"> 
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header print-btn-container no-print">
                
                <button onclick="window.print()" class="btn btn-primary print-btn">
                    <i class="fa fa-print"></i> Print Salary Sheet
                </button>
                 
            </div>
            
            <div class="card-body">
                 
                <header class="my-header ">
                    <h1 style="margin:0; padding:0;">Global Medical Engineering (BD) Ltd.</h1>
                    <p style="margin:0; padding:0;">Provider of Medical Equipment & Solutions for Hospitals, Clinics And HealthCare Institutes.</p>
                    <p style="margin:0; padding:0;">Address : 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000</p>
                    <p style="margin:0; padding:0;">Hotline : +88 09678 020555 Mobile : +8801404003500</p>
                    <p style="margin:0; padding:0;">e-mail :info@gmebd.com web: www.gmebd.com</p>
                </header>

                <section class="title">
                    <h2 style="padding-top: 20px;">Salary Sheet</h2>
                </section>

                <table style="width:100%; border-collapse: collapse;" class="custom-table  table-bordered "> 
                    <thead>  
                        <tr>
                            <th colspan="{{ $salaryBreakdowns->count()+24 }}" class="text-start ">Employee Salary {{ \Carbon\Carbon::parse($payrool->year_month)->format('F Y'); }}</th> 
                        </tr>


                        <tr> 
                            <th class="text-center rotate-header bg-none ">Sl</th>
                            <th class="text-center bg-none ">Employee</th> 
                            <th class="text-center rotate-header bg-none ">Designation</th>
                            <th class="text-center rotate-header bg-none ">ID No</th>
                            <th class="text-center rotate-header bg-none ">Days of <br>Month</th>
                            <th class="text-center rotate-header bg-none ">Weekend</th>
                            <th class="text-center rotate-header bg-none ">Holiday</th>
                            <th class="text-center rotate-header bg-none ">Absent</th>
                            <th class="text-center rotate-header bg-none ">Late</th>
                            <th class="text-center rotate-header bg-none ">Leave</th>
                            <th class="text-center rotate-header bg-none ">Worked <br>Days</th> 

                            @foreach($salaryBreakdowns as $item)
                                <th class="text-center rotate-header bg-none "> 
                                    {{ $item->type }}
                                </th>
                            @endforeach 

                            <th class="text-center rotate-header bg-none ">Increment <br>Amount</th> 
                            <th class="text-center rotate-header bg-none ">Gross <br>Salary</th> 
                            <th class="text-center rotate-header bg-none ">Approved <br>Salary in (%)</th> 
                            <th class="text-center rotate-header bg-none ">Absent <br>Deduction</th>  
                            <th class="text-center rotate-header bg-none ">Late <br>Deduction</th> 
                            <th class="text-center rotate-header bg-none ">Advance+Loan <br>Deduction</th>  
                            <th class="text-center rotate-header bg-none ">Tax <br>Deduction</th> 
                            <th class="text-center rotate-header bg-none ">Total <br>Deduction</th> 
                            <th class="text-center rotate-header bg-none ">Net <br>Payable</th> 
                            <th class="text-center rotate-header bg-none ">Payment <br>Method</th>  
                            <th class="text-center bg-none ">Remarks</th> 
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
                                    <th colspan="11" class="text-end ">Sub Total</th>

                                    @foreach($salaryBreakdowns as $sb)
                                        @php
                                            $varName = 'subtotal' . $sb->value;
                                        @endphp

                                        <th class="text-center ">
                                            {{ isset($$varName) ? number_format($$varName) : 0 }}
                                        </th> 
                                    @endforeach  

                                

                                    <th class="text-center ">{{ numberFormat($subtotalincrement) }}</th> 
                                    <th class="text-center ">{{ numberFormat($subtotalgross) }}</th> 
                                

                                    <th class="text-center ">&nbsp;</th>

                                    <th class="text-center ">{{ numberFormat($subtotalabsent) }}</th> 
                                    <th class="text-center ">{{ numberFormat($subtotallate) }}</th>  
                                    <th class="text-center ">{{ numberFormat($subtotaladvance+$subtotalloan) }}</th> 
                                    <th class="text-center ">{{ numberFormat($subtotaltax) }}</th> 
                                    <th class="text-center ">{{ numberFormat($subtotaldeduction) }}</th> 

                                    <th class="text-center ">{{ numberFormat($subtotalearning) }}</th>  
                                    <th colspan="3" class="text-center ">&nbsp;</th>
                                </tr> 
                                @php 
                                    $subtotalbasic = $subtotalhouse_rent = $subtotalmedical = $subtotalconveyance = $subtotalentertainment = $subtotalleave_fare = $subtotalutility = $subtotalunkeep = 0;
                                    $subtotalothers = $subtotalincrement = $subtotalgross = $subtotalabsent = $subtotallate = $subtotaladvance = $subtotalloan = $subtotaltax = $subtotaldeduction = $subtotalearning = 0;
                                @endphp

                                @endif 
                            
                                <tr>
                                    <th colspan="36" style="text-align: left !important;">{{ optional(optional(optional($item->employee)->employementDetail)->department)->name }}</th>
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
                                <td class="text-center ">{{ $key + 1 }}</td>
                                <td class="text-start ">{{ $item->employee->full_name }}</td>    
                                <td class="text-center "> {{ optional(optional(optional($item->employee)->employementDetail)->designation)->name }}</td>
                                <td class="text-center ">{{ optional(optional($item->employee)->employementDetail)->card_no }}</td>

                                <td class="text-center ">{{ $item->total_days }}</td>
                                <td class="text-center ">{{ $item->weekend }}</td>
                                <td class="text-center ">{{ $item->holidays }}</td>
                                <td class="text-center ">{{ $item->absent_days }}</td>
                                <td class="text-center ">{{ $item->late_days }}</td>
                                <td class="text-center ">{{ $item->leave_days }}</td>
                                <td class="text-center ">{{ $item->working_days }}</td>

                                @foreach($salaryBreakdowns as $sb)
                                    <td class="text-center "> 
                                        {{ $sb->value && optional($item)->{$sb->value} !== null ? number_format(optional($item)->{$sb->value}) : 0}}
                                    </td>
                                @endforeach 


                                <td class="text-center ">{{ 0 }}</td>
                                <td class="text-center ">{{ numberFormat($item->gross) }}</td>
                                <td class="text-center ">{{ numberFormat($item->approved_salary_ratio) }}%</td>
                                <td class="text-center ">{{ numberFormat($item->absent_deduction) }}</td>
                                <td class="text-center ">{{ numberFormat($item->late_deduction) }}</td>
                                <td class="text-center ">{{ numberFormat($item->advance + $item->loan) }}</td> 
                                <td class="text-center ">{{ numberFormat($item->tax) }}</td>
                                <td class="text-center ">{{ numberFormat($item->total_deductions) }}</td> 
                                <td class="text-center highlight-col ">{{ numberFormat($item->net_earning) }}</td> 
                                <td class="text-center ">
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
                                <td class="text-center "> {{ $item->remarks }}  </td> 
                            </tr>
                            
                        @endforeach  
                        <tr>
                            <th colspan="11" class="text-end ">Sub Total</th>

                            @foreach($salaryBreakdowns as $sb)
                                @php
                                    $varName = 'subtotal' . $sb->value;
                                @endphp

                                <th class="text-center ">
                                    {{ isset($$varName) ? number_format($$varName) : 0 }}
                                </th> 
                            @endforeach  

                        

                            <th class="text-center ">{{ numberFormat($subtotalincrement) }}</th> 
                            <th class="text-center ">{{ numberFormat($subtotalgross) }}</th> 
                        

                            <th class="text-center ">&nbsp;</th>

                            <th class="text-center ">{{ numberFormat($subtotalabsent) }}</th> 
                            <th class="text-center ">{{ numberFormat($subtotallate) }}</th> 
                            <th class="text-center ">{{ numberFormat($subtotaladvance + $subtotalloan) }}</th>  
                            <th class="text-center ">{{ numberFormat($subtotaltax) }}</th> 
                            <th class="text-center ">{{ numberFormat($subtotaldeduction) }}</th> 

                            <th class="text-center ">{{ numberFormat($subtotalearning) }}</th>  
                            <th colspan="3" class="text-center ">&nbsp;</th>
                        </tr> 
                        <tr>
                            <th colspan="11" class="text-end ">Total</th>

                            @foreach($salaryBreakdowns as $sb)
                                @php
                                    $varName = 'total_' . $sb->value;
                                @endphp

                                <th class="text-center ">
                                    {{ isset($$varName) ? number_format($$varName) : 0 }}
                                </th> 
                            @endforeach  

                        

                            <th class="text-center ">{{ numberFormat($totalincrement) }}</th> 
                            <th class="text-center ">{{ numberFormat($totalgross) }}</th> 
                        

                            <th class="text-center ">&nbsp;</th>

                            <th class="text-center ">{{ numberFormat($totalabsent) }}</th> 
                            <th class="text-center ">{{ numberFormat($totallate) }}</th> 
                            <th class="text-center ">{{ numberFormat($totaladvance + $totalloan) }}</th>  
                            <th class="text-center ">{{ numberFormat($totaltax) }}</th> 
                            <th class="text-center ">{{ numberFormat($totaldeduction) }}</th> 

                            <th class="text-center ">{{ numberFormat($totalearning) }}</th>  
                            <th colspan="3" class="text-center ">&nbsp;</th>
                        </tr> 
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="36" style="padding-top: 50px;border:none;">
                                <table style="width:100%; border:none; border-collapse: collapse;">
                                    <tr>
                                        @foreach($item->verifications as $verification)
                                            <td style="text-align:center; width: {{ 100 / max(count($item->verifications),1) }}%; vertical-align: top; border:none;">
                                                <strong>{{ $verification->role_name }}</strong><br><br><br>
                                                @if(!empty($verification->employee?->signature))
                                                    <img src="{{ url($verification->employee->signature) }}" 
                                                        alt="" style="height:50px; object-fit:contain; display:block; margin:auto;">

                                                   
                                                @endif
                                                 <br><small style="display: inline-block; transform: rotate(-15deg); ">{{ \Carbon\Carbon::parse($verification->approved_at)->format('d-m-Y'); }} </small><br><br>

                                                {{ $verification->employee->full_name ?? '' }}<br>
                                                <small>{{ $verification->employee->employementDetail->designation->name }}</small><br>
                                                <small>{{ $verification->employee->employementDetail->department->name }}</small>
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>

               
            </div>
        </div>
    </div>
</div>
