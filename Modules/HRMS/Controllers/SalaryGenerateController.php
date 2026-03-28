<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\HRMS\Models\SalaryGenerate;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Services\SalaryGenerateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Services\AccountTransactionService;
use Modules\HRMS\Models\Payroll;
use PDO;

class SalaryGenerateController extends Controller
{

    /**
     * Service variable
     *
     * @var SalaryGenerateService
     */
    private $service;
    private $transactionService;
    function __construct(SalaryGenerateService $service, AccountTransactionService $transactionService)
    {
        $this->service = $service;
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */

    public function payrolls()
    {
        $data['payrolls'] = Payroll::latest()
            ->searchByFields(['department_id', 'year_month'])
            ->paginate(20);
        $data['departments'] = Department::where('status', 1)->get();
        $data['employees'] = Employee::where('status', 1)->get();


        return view("HRMS::payroll.salary-generates.payrolls", $data);
    }
    public function index()
    {
        $data['salaryGenerates'] = $this->service->getAll();
        $data['departments'] = Department::where('status', 1)->get();
        $data['employees'] = Employee::all();
        $data['accounts'] = Account::orderBy('account_group_id', 'asc')->get();
        return view("HRMS::payroll.salary-generates.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('salaryGenerates.create');
    }
 

    /**
     * Store salary for single employee
     */
    
    /*public function store(Request $request)
    {
         
        $salaries = EmployeeSalary::whereRaw(
            DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql'
                ? "to_char(effective_date, 'YYYY-MM') <= ?"
                : "DATE_FORMAT(effective_date, '%Y-%m') <= ?",
            $request->year_month
        )
            ->latest('effective_date')
            ->whereHas('employee', function ($q) use ($request) {
                $q->whereDoesntHave('salaryGenerates', function ($q) use ($request) {
                    $q->where('year_month', $request->year_month);
                });

                if ($request->department_id != null) {
                    $q->whereHas('employementDetails', function ($q) use ($request) {
                        $q->where('department_id', $request->department_id);
                    });
                }
            })
            ->get()
            ->where('status', 1)
            ->unique('employee_id');

        $data = [];
        if ($salaries->count() == 0) {
            return redirect()->route('hrm.payrolls')->with('error', 'No employee found or salary already generated for this month.');
        }
        foreach ($salaries as $salary) {
            $data['employee_id'][] = $salary->employee_id;
            $data['basic'][] = $salary->basic;
            $data['house_rent'][] = $salary->house_rent;
            $data['medical'][] = $salary->medical;
            $data['conveyance'][] = $salary->conveyance;
            $data['others'][] = $salary->others;
            $data['gross'][] = $salary->gross;
            $data['tax'][] = $salary->tax;
            $data['net_earning'][] = $salary->gross - $salary->tax;
            $data['year_month'][] = $request->year_month;
            $data['department_id'][] = $request->department_id ?? null;
            $data['status'][] = 'UnPaid';
        }


        $this->service->store($data, $request);
        return redirect()->route('hrm.payrolls')->with('success', 'SalaryGenerate created successfully.');
    } */

        
    public function store(Request $request, SalaryGenerate $service)
    {
        // Validate input
        $request->validate([
            'year_month' => 'required|date_format:Y-m',
        ]);

        $month = $request->year_month . '-01'; // convert to full date for SP

        // Call Service function that handles all active employee salary calculation & save
        $allSalaries = $this->service->salaryGenerateAndSaveAllActiveEmployee($month,$request);

        // Return JSON response
        /*return response()->json([
            'success' => true,
            'month' => $month,
            'salaries' => $allSalaries,
            'message' => 'Payroll created successfully.',
        ]);*/
        return redirect()->route('hrm.payrolls')->with('success', 'SalaryGenerate created successfully.');
    }
 


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['salaryGenerate'] = $this->service->show($id);

        return view("HRMS::payroll.salary-generates.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryGenerate $salaryGenerate)
    {
        $data['salaryGenerate'] = $salaryGenerate;
        //
        return view("HRMS::payroll.salary-generates.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryGenerate $salaryGenerate)
    {
        $validate = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'basic' => 'required|numeric',
            'house_rent' => 'required|numeric',
            'medical' => 'nullable|numeric',
            'conveyance' => 'nullable|numeric',
            'others' => 'nullable|numeric',
            'ot_pay' => 'nullable|numeric',
            'double_time_pay' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'leave_encashment' => 'nullable|numeric',
            'advance' => 'nullable|numeric',
            'loan' => 'nullable|numeric',
            'no_pay_leave' => 'nullable|numeric',
            'absence' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'gross' => 'nullable|numeric',
            'total_other_earnings' => 'nullable|numeric',
            'total_earnings' => 'nullable|numeric',
            'total_deductions' => 'nullable|numeric',
            'total_tax' => 'nullable|numeric',
            'net_earning' => 'nullable|numeric',
            'status' => 'required|string',
            'pay_date' => 'nullable|date',

        ]);
        $this->service->update($salaryGenerate, $validate);

        return redirect()->route('hrm.salary-generates.index')->with('success', 'SalaryGenerate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryGenerate $salaryGenerate)
    {
        $this->service->delete($salaryGenerate);
        return redirect()->route('hrm.salary-generates.index')->with('success', 'SalaryGenerate deleted successfully.');
    }

    public function paid(Request $request, $id)
    {
        DB::beginTransaction();

        $salaryGenerate = SalaryGenerate::find($id);
        $salaryGenerate->status = 'Paid';
        $salaryGenerate->pay_date = date('Y-m-d');

        $salaryGenerate->salaryGeneratePayments()->create([
            'amount' => $salaryGenerate->net_earning,
            'pay_date' => date('Y-m-d'),
        ]);
        $salaryGenerate->save();

        $creditAccountId = $request->credit_account_id;
        $this->makeTransaction($salaryGenerate, $creditAccountId, $salaryGenerate->net_earning);

        DB::commit();
        return redirect()->back()->with('success', 'Salary paid successfully');
    }



    public function partiallyPaid(Request $request, $id)
    {
        DB::beginTransaction();

        $salaryGenerate = SalaryGenerate::findOrFail($id);
        $salaryGenerate->status = 'Partially Paid';
        $salaryGenerate->pay_date = date('Y-m-d');

        $salaryGenerate->salaryGeneratePayments()->create([
            'amount' => $request->amount,
            'pay_date' => date('Y-m-d'),
        ]);

        $salaryGenerate->save();

        $creditAccountId = $request->credit_account_id;
        $this->makeTransaction($salaryGenerate, $creditAccountId, $request->amount);

        DB::commit();
        return redirect()->back()->with('success', 'Salary partially paid successfully.');
    }




    public function paidAll(Request $request)
    {
        DB::beginTransaction();

        $ids = $request->input('id');
        $status = $request->input('status');
        $creditAccountId = $request->credit_account_id;

        SalaryGenerate::whereIn('id', $ids)->update([
            'status' => $status,
            'pay_date' => date('Y-m-d')
        ]);

        foreach ($ids as $id) {
            $salaryGenerate = SalaryGenerate::find($id);

            $totalPaidAmount = $salaryGenerate->salaryGeneratePayments()->sum('amount') ?? 0;
            $remainingAmount = $salaryGenerate->net_earning - $totalPaidAmount;

            if ($remainingAmount > 0) {
                $salaryGenerate->salaryGeneratePayments()->create([
                    'amount' => $remainingAmount,
                    'pay_date' => date('Y-m-d'),
                ]);

                // Call transaction function inside the loop with correct amount
                $this->makeTransaction($salaryGenerate, $creditAccountId, $remainingAmount);
            }
        }

        DB::commit();
        return response()->json(['message' => 'Salaries updated successfully!']);
    }

    public function partiallyPaidAll(Request $request)
    {
        DB::beginTransaction();

        $ids = $request->input('id');
        $status = $request->input('status');
        $amount = $request->input('amount');
        $creditAccountId = $request->credit_account_id;

        SalaryGenerate::whereIn('id', $ids)->update([
            'status' => $status,
            'pay_date' => date('Y-m-d'),
        ]);

        foreach ($ids as $id) {
            $salaryGenerate = SalaryGenerate::find($id);

            if ($amount > 0) {
                $salaryGenerate->salaryGeneratePayments()->create([
                    'amount' => $amount,
                    'pay_date' => date('Y-m-d'),
                ]);

                // Call transaction function inside the loop with correct amount
                $this->makeTransaction($salaryGenerate, $creditAccountId, $amount);
            }
        }

        DB::commit();
        return response()->json(['message' => 'Salaries updated successfully!']);
    }

    public function makeTransaction(SalaryGenerate $salaryGenerate, $creditAccountId, $amount)
    {
        $transactionable_type = SalaryGenerate::class;
        $transactionable_id = $salaryGenerate->id;
        $invoice_no = $salaryGenerate->id;

        $invoice_link = $invoice_no;
        $description = 'Salary Payment';

        $this->transactionService->storeTransaction(
            $transactionable_type,
            $transactionable_id,
            $invoice_no,
            $salaryGenerate->employee->getSalaryLiabilitieAccount()->id,
            -$amount,
            $amount,
            0,
            'debit',
            $description
        );

        $this->transactionService->storeTransaction(
            $transactionable_type,
            $transactionable_id,
            $invoice_no,
            $creditAccountId,
            $amount,
            0,
            $amount,
            'credit',
            $description
        );
    }
 


 
}
