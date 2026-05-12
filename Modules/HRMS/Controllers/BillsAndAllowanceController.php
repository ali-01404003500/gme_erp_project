<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\ExpenseType;
use Modules\HRMS\Models\Settings\TransportType;
use Modules\HRMS\Services\BillsAndAllowanceService;
use App\Services\Notifications\GeneralNotificationService;
use Modules\Account\Services\Payments\PettyCashPaymentService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillsAndAllowanceController extends Controller
{
    private $service;
    private $generalNotificationService;
    private $pettyCashPaymentService;

    function __construct(
        BillsAndAllowanceService $service, 
        GeneralNotificationService $generalNotificationService,
        PettyCashPaymentService $pettyCashPaymentService
    )
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        $this->pettyCashPaymentService = $pettyCashPaymentService;
    }
    
    public function index(Request $request)
    {
        $data['billsAndAllowances'] = $this->service->getAll();
        $data['employees'] = Employee::all();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('HRMS::bills.indexView', $data)->render();

            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('bills_and_allowance_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("HRMS::bills.index", $data);
    }

    public function verify(Request $request)
    {
        $data['billsAndAllowances'] = $this->service->verify();
        $data['employees'] = Employee::all();
        $data['company_info'] = CompanyInfo::first();
 
        return view("HRMS::bills.verify", $data);
    }
    public function create()
    {
        $employeeId = Auth::user()->employee->id; 
        $data['employee'] = Employee::where('id', $employeeId)->first(); 
        $data['transport_types'] = TransportType::all();
        $data['expense_types'] = ExpenseType::all(); 
        return view('HRMS::bills.create', $data);
    }

    public function store(Request $request)
    {
        $result = $this->service->create($request);

        $this->generalNotificationService->store([
            'title' => 'New Bills & Allowance',
            'description' => 'New Bills & Allowance Added - needs team leader verification',
            'action' => $this->generalNotificationService->actionBuilder(BillsAndAllowanceController::class, 'verifyDetails', [$result['bills']->id]),
        ], $this->generalNotificationService->getPermittedUsers('hrm.bills.team_leader_verify'));

        return redirect()->route('hrm.bills.index')->with('success', 'Bills And Allowance created successfully.');
    }

    public function verifyDetails(Request $request)
    {
        
        $data['billsAndAllowance'] = $this->service->multipleShow(json_decode($request->ids));
        return response()->json([
            'success' => true,
            'data' => $data['billsAndAllowance']
        ]);
    }

    public function teamLeaderVerify(Request $request)
    {
        DB::beginTransaction();
        try {
            //dd($request->all()); 
            
            $ids = json_decode($request->id);
            foreach($ids as $id)
            {
                $bill = BillsAndAllowance::findOrFail($id);
            
                // Update bill status
                $bill->update([
                    'checked_by_team_leader' => auth()->user()->id,
                    'checked_by_team_leader_date' => now(),
                    'checked_by_team_leader_comments' => $request->comments,
                    'status' => 'team_leader_check',
                ]);

                // Update approved amounts for transport expenses
                if ($request->has('transport_approved')) {
                    foreach ($request->transport_approved as $expenseId => $amount) {
                        $bill->transportExpenses()->where('id', $expenseId)->update([
                            'team_leader_approved_amount' => $amount
                        ]);
                    }
                }

                // Update approved amounts for general expenses
                if ($request->has('general_approved')) {
                    foreach ($request->general_approved as $expenseId => $amount) {
                        $bill->generalExpenses()->where('id', $expenseId)->update([
                            'team_leader_approved_amount' => $amount
                        ]);
                    }
                }    
            } 
            DB::commit();

            // Notify HR/Accounts
            $this->generalNotificationService->store([
                'title' => 'Bills & Allowance - Team Leader Verified',
                'description' => 'Bills & Allowance verified by team leader - needs accounts verification',
                'action' => $this->generalNotificationService->actionBuilder(BillsAndAllowanceController::class, 'verifyDetails', [$bill->id]),
            ], $this->generalNotificationService->getPermittedUsers('hrm.bills.accounts_verify'));

            return redirect()->route('hrm.bills.verify')->with('success', 'Bills & Allowance verified by team leader successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    public function accountsVerify(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $ids = json_decode($request->id);
            foreach($ids as $id)
            {
                $bill = BillsAndAllowance::findOrFail($id);
                
                $bill->update([
                    'checked_by_accounts' => auth()->user()->id,
                    'checked_by_accounts_date' => now(),
                    'checked_by_accounts_comments' => $request->comments,
                    'status' => 'accounts_check',
                ]);

                // Update approved amounts for transport expenses
                if ($request->has('transport_approved')) {
                    foreach ($request->transport_approved as $expenseId => $amount) {
                        $bill->transportExpenses()->where('id', $expenseId)->update([
                            'accounts_approved_amount' => $amount
                        ]);
                    }
                }

                // Update approved amounts for general expenses
                if ($request->has('general_approved')) {
                    foreach ($request->general_approved as $expenseId => $amount) {
                        $bill->generalExpenses()->where('id', $expenseId)->update([
                            'accounts_approved_amount' => $amount
                        ]);
                    }
                }

                
            }
            DB::commit();
            // Notify final approver
            $this->generalNotificationService->store([
                'title' => 'Bills & Allowance - Accounts Verified',
                'description' => 'Bills & Allowance verified by accounts - needs final approval',
                'action' => $this->generalNotificationService->actionBuilder(BillsAndAllowanceController::class, 'verifyDetails', [$bill->id]),
            ], $this->generalNotificationService->getPermittedUsers('hrm.bills.final_approve'));

            return redirect()->route('hrm.bills.verify')->with('success', 'Bills & Allowance verified by accounts successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    public function finalApprove(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = json_decode($request->id);
            foreach($ids as $id)
            {
                $bill = BillsAndAllowance::findOrFail($id);
                
                $bill->update([
                    'final_approved_by' => auth()->user()->id,
                    'final_approved_date' => now(),
                    'final_approved_comments' => $request->comments,
                    'status' => 'approved',
                ]);

                // Update final approved amounts for transport expenses
                if ($request->has('transport_approved')) {
                    foreach ($request->transport_approved as $expenseId => $amount) {
                        $bill->transportExpenses()->where('id', $expenseId)->update([
                            'final_approved_amount' => $amount
                        ]);
                    }
                }

                // Update final approved amounts for general expenses
                if ($request->has('general_approved')) {
                    foreach ($request->general_approved as $expenseId => $amount) {
                        $bill->generalExpenses()->where('id', $expenseId)->update([
                            'final_approved_amount' => $amount
                        ]);
                    }
                }

                $journalResult = $this->pettyCashPaymentService->createStep1JournalEntry($bill->id);
                    
                //    dd($journalResult);
            }
            DB::commit();

            // Notify accounts team for payment
            $this->generalNotificationService->store([
                'title' => 'Bills & Allowance - Ready for Payment',
                'description' => 'Bills & Allowance finally approved - ready for payment processing',
                'action' => $this->generalNotificationService->actionBuilder(
                    'Modules\Account\Controllers\Payments\PettyCashPaymentController', 
                    'details', 
                    [$bill->id]
                ),
            ], $this->generalNotificationService->getPermittedUsers('account.payments.petty_cash.pay'));

            return redirect()->route('hrm.bills.verify')
                ->with('success', 'Bills & Allowance finally approved successfully. Step 1 journal entry created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Final approval failed: ' . $e->getMessage());
        }
    }

    public function show($id, Request $request)
    {
        $data['billsAndAllowance'] = $this->service->show($id);
        
        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('HRMS::bills.view', $data)->render();
            
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('bills_and_allowance_' . $data['billsAndAllowance']->employee->full_name . '.pdf', ['Attachment' => false]);
        }

        return view("HRMS::bills.show", $data);
    }

    public function edit($id)
    {
        $data['employees'] = Employee::all();
        $data['transport_types'] = TransportType::all();
        $data['expense_types'] = ExpenseType::all();
        $data['billsAndAllowance'] = BillsAndAllowance::with(['employee', 'transportExpenses', 'generalExpenses'])->findOrFail($id);
        return view("HRMS::bills.edit", $data);
    }

    public function update(Request $request, $id)
    {
        $billsAndAllowance = BillsAndAllowance::with(['employee', 'transportExpenses', 'generalExpenses'])->findOrFail($id);
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_of_bill_claim' => 'required|date',
        ]);

        $this->service->update($billsAndAllowance, $validate, $request);

        return redirect()->route('hrm.bills.index')->with('success', 'Bills And Allowance updated successfully.');
    }

    public function destroy($id)
    {
        $billsAndAllowance = BillsAndAllowance::findOrFail($id);
        $this->service->delete($billsAndAllowance);
        return redirect()->route('hrm.bills.index')->with('success', 'Bills And Allowance deleted successfully.');
    }
}