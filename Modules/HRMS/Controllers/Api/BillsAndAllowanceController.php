<?php

namespace Modules\HRMS\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\ExpenseType;
use Modules\HRMS\Models\Settings\TransportType;
use Modules\HRMS\Services\BillsAndAllowanceService;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
class BillsAndAllowanceController extends Controller
{

    /**
     * Service variable
     *
     * @var BillsAndAllowanceService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(BillsAndAllowanceService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        $this->middleware('permited')->only('index');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        try{
        $data['billsAndAllowances'] = $this->service->getAll();
        $data['employees'] = Employee::all();
        $data['company_info'] = CompanyInfo::first();

        return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    
    }

    public function expenseType(){
        $data['expense_types'] = ExpenseType::all();
        return response()->json([
            'data' => $data,
            'status' => true,
        ]);
    }

    public function transportType(){
        $data['transport_types'] = TransportType::all();
        return response()->json([
            'data' => $data,
            'status' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()

    {
        $data['employees'] = Employee::all();
        $data['transport_types'] = TransportType::all();
        $data['expense_types'] = ExpenseType::all();
        return view('HRMS::bills.create', $data);
    }

    public function recommended(Request $request, $id){
        BillsAndAllowance::find($id)->update([
         'recommended_by' => auth()->user()->id,
         'recommended_comments' => $request->recommended_comments,
         'status' => 'recommended',
     ]);
 
     return redirect()->route('hrm.bills.index')->with('success', 'Bills & Allowance updated successfully.');
     }
 
     public function approved(Request $request, $id){
         BillsAndAllowance::find($id)->update([
             'approved_by' => auth()->user()->id,
             'approved_comments' => $request->approved_comments,
             'status' => 'approved',
         ]);
  
         return redirect()->route('hrm.bills.index')->with('success', 'Bills & Allowance updated successfully.');
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $employee_id = auth()->user()->employee->id;
        // dd($request->all());
        $validate = $request->validate([
            'date_of_bill_claim' => 'required|date',
        ]);

        $transportExpense = $request->input('transport_expense', []);
        $generalExpense = $request->input('general_expense', []);
        if(!empty($transportExpense)){
            //validate transport expense
            $request->validate([
                'transport_expense.*.date_of_expense' => 'required|date',
                'transport_expense.*.from_location' => 'required|string',
                'transport_expense.*.to_location' => 'required|string',
                'transport_expense.*.transport_by' => 'required|string',
                'transport_expense.*.distance' => 'required|integer',
                'transport_expense.*.expense_description' => 'required|string',
                'transport_expense.*.amount' => 'required|numeric|min:1',
                'transport_expense.*.settlement_amount' => 'required|numeric|min:1',
                'transport_expense.*.receipts_invoices' => 'nullable|string',
                'transport_expense.*.supporting_documents' => 'nullable|string',
            ]);
        }
        if(!empty($generalExpense)){
            //validate general expense
            $request->validate([
                'general_expense.*.expense_date' => 'nullable|date',
                'general_expense.*.expense_type' => 'required|string',
                'general_expense.*.expense_description' => 'required|string',
                'general_expense.*.amount' => 'required|numeric|min:0.01',
                'general_expense.*.settlement_amount' => 'required|numeric|min:0.01',
                'general_expense.*.receipts_invoices' => 'nullable|string',
                'general_expense.*.supporting_documents' => 'nullable|string',
            ]);
        }

        $validate['employee_id'] = $employee_id;

        // dd($validate, $transportExpense,   $generalExpense);

        $result = $this->service->createApi($validate, $transportExpense, $generalExpense);
        // dd($result);
        $this->generalNotificationService->store([
            'title' => 'New Bills & Allowance',
            'description' => 'New Bills & Allowance Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(BillsAndAllowanceController::class, 'approve', [$result['bill']->id]),
         ],$this->generalNotificationService->getPermittedUsers('hrm.bills.approve'));

        return response()->json([
            'data' => $result,
            'status' => true,
            'message' => 'Bills And Allowance created successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee_id = auth()->user()->employee->id;
    
        $validate = $request->validate([
            'date_of_bill_claim' => 'required|date',
        ]);
    
        $transportExpense = $request->input('transport_expense', []);
        $generalExpense = $request->input('general_expense', []);
    
        if (!empty($transportExpense)) {
            // Validate transport expense
            $request->validate([
                'transport_expense.*.date_of_expense' => 'required|date',
                'transport_expense.*.from_location' => 'required|string',
                'transport_expense.*.to_location' => 'required|string',
                'transport_expense.*.transport_by' => 'required|string',
                'transport_expense.*.distance' => 'required|integer',
                'transport_expense.*.expense_description' => 'required|string',
                'transport_expense.*.amount' => 'required|numeric|min:1',
                'transport_expense.*.settlement_amount' => 'required|numeric|min:1',
                'transport_expense.*.receipts_invoices' => 'nullable|string',
                'transport_expense.*.supporting_documents' => 'nullable|string',
            ]);
        }
    
        if (!empty($generalExpense)) {
            // Validate general expense
            $request->validate([
                'general_expense.*.expense_date' => 'nullable|date',
                'general_expense.*.expense_type' => 'required|string',
                'general_expense.*.expense_description' => 'required|string',
                'general_expense.*.amount' => 'required|numeric|min:0.01',
                'general_expense.*.settlement_amount' => 'required|numeric|min:0.01',
                'general_expense.*.receipts_invoices' => 'nullable|string',
                'general_expense.*.supporting_documents' => 'nullable|string',
            ]);
        }
    
        $validate['employee_id'] = $employee_id;
    
        $result = $this->service->updateApi($id, $validate, $transportExpense, $generalExpense);
    
        return response()->json([
            'data' => $result,
            'status' => true,
            'message' => 'Bills And Allowance updated successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try{
            $data['billsAndAllowance'] = $this->service->show($id);

            return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['employees'] = Employee::all();
        $data['transport_types'] = TransportType::all();
        $data['expense_types'] = ExpenseType::all();
        $data['billsAndAllowance'] = $this->service->show($id);
        //
        return view("HRMS::bills.edit", $data);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillsAndAllowance $billsAndAllowance)
    {
        $this->service->delete($billsAndAllowance);
        return response()->json(['message' => 'BillsAndAllowance deleted successfully.'], 200);
    }
}
