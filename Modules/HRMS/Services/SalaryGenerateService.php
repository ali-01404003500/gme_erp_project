<?php

namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Services\AccountTransactionService;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Loan;
use Modules\HRMS\Models\Payroll;
use Modules\HRMS\Models\SalaryGenerate;
use Modules\HRMS\Models\SalaryGenerationPolicy;

class SalaryGenerateService
{
    public $transactionService;
    public function __construct(AccountTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getAll(int $limit = 20)
    {
        return SalaryGenerate::query()
            ->searchByFields(['payroll_id', 'employee_id', 'department_id', 'year_month'])
            ->paginate($limit);
    }

   public function store($data, $request)
    {
        DB::beginTransaction();

        try {
            $invoiceId = 'INV-' . now()->format('Ymd') . '-' . rand(1000, 9999);

            // Step 1: Create main Payroll entry
            $payroll = Payroll::create([
                'invoice_id'        => $invoiceId,
                'department_id'     => $request->department_id,
                'year_month'        => $request->year_month,
                'total_net_earning' => array_sum($data['net_earning']),
            ]);

            foreach ($data['employee_id'] as $key => $employeeId) {
                $employee     = Employee::with('employementDetail.department')->findOrFail($employeeId);
                $departmentId = $data['department_id'][$key] ?? $employee->employementDetail?->department->id;
                $yearMonth    = $data['year_month'][$key];

                // Step 2: Find active loan for this month
                $loan = Loan::query()
                    ->where('employee_id', $employeeId)
                    ->where('status', 'approved')
                    ->where('remaining_balance', '>', 0)
                    ->where('start_month', '<=', $yearMonth)
                    ->get()
                    ->first(function ($loan) use ($yearMonth) {
                        $endMonth = date('Y-m', strtotime("+".($loan->duration - 1)." months", strtotime($loan->start_month)));
                        return $yearMonth >= $loan->start_month && $yearMonth <= $endMonth;
                    });

                $loanDeduction = 0;

                if ($loan) {
                    $loanDeduction = min($loan->monthly_reduction, $loan->remaining_balance);

                    $loan->decrement('remaining_balance', $loanDeduction);

                    $loan->details()->create([
                        'payment_month' => $yearMonth,
                        'amount'        => $loanDeduction,
                    ]);
                }

                // Step 3: Create salary record
                SalaryGenerate::create([
                    'payroll_id'    => $payroll->id,
                    'employee_id'   => $employeeId,
                    'basic'         => $data['basic'][$key],
                    'house_rent'    => $data['house_rent'][$key],
                    'medical'       => $data['medical'][$key],
                    'conveyance'    => $data['conveyance'][$key],
                    'others'        => $data['others'][$key],
                    'gross'         => $data['gross'][$key],
                    'tax'           => $data['tax'][$key],
                    'net_earning'   => $data['net_earning'][$key],
                    'year_month'    => $yearMonth,
                    'department_id' => $departmentId,
                    'status'        => $data['status'][$key],
                    'loan'          => $loanDeduction,
                    'total_deductions' => $loanDeduction,
                ]);
            }

            // Step 4: Final transaction actions
            $this->makeTransaction($payroll);

            DB::commit();

            return ['success' => true, 'message' => 'Payroll created successfully.'];

        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::error("Payroll store failed: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }



    public function makeTransaction(Payroll $payroll)
    {
        $transactionable_type = Payroll::class;
        $transactionable_id = $payroll->id;
        $invoice_no = $payroll->invoice_id;
        $expense_account = Account::where('name', 'Salaries and Allowance Expense')->first();

        $invoice_link = '#'. $invoice_no ;
        $description = $invoice_link .' Salary Generated';

        // 1️⃣ Debit salary expense
        $this->transactionService->storeTransaction(
            $transactionable_type,
            $transactionable_id,
            $invoice_no,
            $expense_account->id,
            -$payroll->total_net_earning,
            $payroll->total_net_earning,
            0,
            'debit',
            $description
        );

        // 2️⃣ Credit employee liabilities, and loan receivables if applicable
        foreach ($payroll->salaryGenerates as $salaryGenerate) {
            $employee = $salaryGenerate->employee;
            $liabilityAccount = $employee->getSalaryLiabilitieAccount();

            // Full gross salary liability before deductions
            $creditAmount = $salaryGenerate->net_earning;

            // If loan was deducted, create separate credit to Loan Receivable
            if ($salaryGenerate->loan > 0) {
                $loanAccount = $employee->getLoanReceivableAccount();

                // 🔹 Credit: Loan Receivable
                $this->transactionService->storeTransaction(
                    $transactionable_type,
                    $transactionable_id,
                    $invoice_no,
                    $loanAccount->id,
                    $salaryGenerate->loan,
                    0,
                    $salaryGenerate->loan,
                    'credit',
                    $description . ' (Loan Recovery)'
                );

                // 🔹 Remaining credit goes to Salary Payable
                $creditAmount = $salaryGenerate->net_earning - $salaryGenerate->loan;
            }

            // 🔹 Credit: Salary Payable
            $this->transactionService->storeTransaction(
                $transactionable_type,
                $transactionable_id,
                $invoice_no,
                $liabilityAccount->id,
                $creditAmount,
                0,
                $creditAmount,
                'credit',
                $description
            );
        }
    }

    public function update(SalaryGenerate $salaryGenerate, array $data)
    {
        $salaryGenerate->update($data);
        return $salaryGenerate;
    }

    public function delete(SalaryGenerate $salaryGenerate)
    {
        $salaryGenerate->delete();
    }

    public function show($id)
    {
        return SalaryGenerate::findOrFail($id);
    }

      /**
     * Calculate salary for single employee
     */
    public function calculateSalary($empId, $month, $totalDays, $weekendDays, $holidays, $workingDays)
    { 
        DB::select("
            CALL calculate_salary_gennerate_details(
                $empId, '$month', $totalDays, $weekendDays, $holidays, $workingDays,
                @gross, @basic, @house, @medical, @conv, 
                @ent, @leave_fare, @utility, @unkeep, @others,
                @absent, @late, @leave, @loan, @advance, @tax, @net
            )

        ");
  
        $result = DB::select("
            SELECT 
                @gross as gross_salary,
                @basic as basic_salary,
                @house as house_rent,
                @medical as medical,
                @conv as conveyance,
                @ent as entertainment,
                @leave_fare as leave_fare,
                @utility as utility,
                @unkeep as unkeep,
                @others as others,
                @absent as absent_deduction,
                @late as late_deduction,
                @leave as leave_deduction,
                @loan as loan_deduction,
                @advance as advance_deduction,
                @tax as tax_deduction,
                @net as salary_payment 
        ");
        

        return (array) $result[0];
    }

      /**
     * Generate salary for all active employees and save
     */
    public function salaryGenerateAndSaveAllActiveEmployee($month, $request)
    { 

        try {
            $invoiceId = 'SALARY-' . now()->format('Ymd') . '-' . rand(1000, 9999);
           
            // Step 1: Create main Payroll entry
            $payroll = Payroll::firstOrCreate(
                ['year_month'        => $month],
                ['invoice_id'        => $invoiceId]
            );
            $employeesQuery = Employee::with('employementDetail.department')
                ->where('status', 1);

            if($request->employee_id != "") {
                $employeesQuery->where('id', $request->employee_id);
            }
            
            if($request->department_id != "") {
                $employeesQuery->whereHas('employementDetail', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            $employees = $employeesQuery->get();
           
            $allSalaries = [];
            $totalSalary = 0;

            // Get working days, holidays, weekend
  
s            $policy = SalaryGenerationPolicy::first(); 
            $result = DB::select("CALL GetMonthSummary('$month')"); 
            $data = $result[0];
  
            if($policy->calculation_type == "fixed_days") {
                $totalDays = $policy->fixed_days; 
            } else {
                $totalDays = $data->total_days;
            }

            foreach ($employees as $employee) {

                $salaryData = $this->calculateSalary($employee->id, $month, $totalDays, $data->weekends,$data->holidays,$data->working_days);
 
                $loan = Loan::query()
                    ->where('employee_id', $employee->id)
                    ->where('status', 'paid')
                    ->where('remaining_balance', '>', 0)
                    ->where('start_month', '<=', $month)
                    ->get()
                    ->first(function ($loan) use ($month) {
                        $endMonth = date('Y-m', strtotime("+".($loan->duration - 1)." months", strtotime($loan->start_month)));
                        return $month >= $loan->start_month && $month <= $endMonth;
                    });

                $loanDeduction = 0; 
                $loanDeduction = min($loan->monthly_reduction ?? 0 , $loan->remaining_balance ?? 0);

                if ($loan && $salaryData['loan_deduction'] != 0) {
                    $loan->decrement('remaining_balance', $loanDeduction);

                    $loan->details()->create([
                        'payment_month' => $month,
                        'amount'        => $loanDeduction,
                    ]);

                }


                // Create salary record
                $netEarning = $salaryData['gross_salary']-$salaryData['tax_deduction'];
                $totalDeduction = $salaryData['absent_deduction']-$salaryData['late_deduction']-$salaryData['loan_deduction']-$salaryData['advance_deduction']-$salaryData['tax_deduction'];
                $totalSalary += $salaryData['gross_salary'];
  
                SalaryGenerate::updateOrInsert(
                    [ 
                        'employee_id' => $employee->id,
                        'year_month'       => $month
                    ],
                    [ 
                        'payroll_id' => $payroll->id,
                        'basic' => $salaryData['basic_salary'],
                        'house_rent'   => $salaryData['house_rent'],
                        'medical'      => $salaryData['medical'],
                        'conveyance'   => $salaryData['conveyance'],
                        'entertainment'=> $salaryData['entertainment'],
                        'leave_fare'   => $salaryData['leave_fare'],
                        'utility'      => $salaryData['utility'],
                        'unkeep'       => $salaryData['unkeep'],
                        'others'       => $salaryData['others'],

                        'absence' => $salaryData['absent_deduction'],  
                        'late_deduction' => $salaryData['late_deduction'],  
                        'loan'   => $salaryData['loan_deduction'],
                        'advance'=> $salaryData['advance_deduction'],
                        'tax'    => $salaryData['tax_deduction'], 
                        'gross'    => $salaryData['gross_salary'],

                        'status'        => "Create",
                        'net_earning'        => $netEarning,
                        'total_deductions'        => $totalDeduction, 
                        
                        'total_tax'    => $salaryData['tax_deduction'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ] 
 
                );

 
                $allSalaries[] = [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'salary' => $salaryData
                ]; 
                 
            }

            return $allSalaries;

            $payroll->update([
                'total_net_earning' => $totalSalary,
            ]); 

        
        } catch (\Throwable $e) {
            DB::rollBack(); 
            throw $e;
        }
 

        
    }

}
