<?php

namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Services\AccountTransactionService;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Loan;
use Modules\HRMS\Models\Payroll;
use Modules\HRMS\Models\SalaryGenerate;

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
}
