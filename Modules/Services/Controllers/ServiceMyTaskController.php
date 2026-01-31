<?php

namespace Modules\Services\Controllers;


use App\Http\Controllers\Controller;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceMyTask;
use Modules\Services\Models\ServicePendingToken;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Services\ServiceMyTaskService;
use Illuminate\Http\Request;

class ServiceMyTaskController extends Controller
{

    /**
     * Service variable
     *
     * @var ServiceMyTaskService
     */
    private $service; 
    function __construct(ServiceMyTaskService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $dateRange = request()->query('from_to_date');
        $status = request()->query('status');
        $query = ServiceToken::whereIn('action', ['Live', 'Started', 'Done', 'Failed']);
        if ($dateRange) {
            $dates = explode(' to ', $dateRange);

            $query->whereBetween('updated_at', [$dates[0].' 00:00:00', $dates[1].' 23:59:59']);
        }
        if ($status) {
            $query->where('action', $status);
        }
        if (!auth()->user()->id == 1) {
            $query->where('engineerAssign', function ($query) {
                $query->where('engineers', function ($query) {
                    $query->where('engineer_id', auth()->user()->employee->id);
                });
            });
        }
        $data['myTasks'] = $query->get();

        return view("Services::service-my-task.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('serviceMyTasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
         $validate = $request->validate([
            'service_token_id' => 'required|integer|exists:service_tokens,id',
            'bill_type' => 'required|in:service_bill,service_return_bill', 
            'net_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string', 
            'basic_info_supply_voltage' => 'nullable|string',
            'basic_info_generator_backup' => 'nullable|in:0,1',
            'basic_info_ground_voltage' => 'nullable|string',
            'basic_info_ups_backup' => 'nullable|in:online,offline,no',
            'handover_info_name' => 'nullable|string',
            'handover_info_department' => 'nullable|string',
            'handover_info_designation' => 'nullable|string',
            'handover_info_contact_no' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,live,cancelled',
            'attachments' => 'nullable|array',
            'bill_description' => 'nullable|string',
            'return_bill_description' => 'nullable|string',
            'tips_amount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);


        // dd($validate);
        // Validate pending service tokens
        $pendingServiceToken = null;

        if($validate['status'] != 'cancelled') {
            // Validate pending service tokens
            $pendingServiceToken = $request->validate([
                'pending_token_ids' => 'required|array',
                'pending_token_ids.*' => 'required|integer|exists:service_tokens,id',
                'pending_descriptions' => 'nullable|array',
                'pending_descriptions.*' => 'required|string|min:100',
            ],[
                'pending_descriptions.*.min' => 'The pending description must be at least 100 characters.',
                'pending_descriptions.*.required' => 'The pending description is required.'
            ]);
        }


        

        // Validate service bills
        $serviceBills = $request->validate([
            'bill_product_ids' => 'nullable|array',
            'bill_product_ids.*' => 'nullable|integer|exists:product_catalogs,id',
            'bill_quantity' => 'nullable|array',
            'bill_quantity.*' => 'nullable|integer|min:0',
            'bill_price' => 'nullable|array',
            'bill_price.*' => 'nullable|numeric|min:0',
            'bill_unit_discount' => 'nullable|array',
            'bill_unit_discount.*' => 'nullable|numeric|min:0',
            'bill_total_discount' => 'nullable|array',
            'bill_total_discount.*' => 'nullable|numeric|min:0',
            'bill_amount' => 'nullable|array',
            'bill_amount.*' => 'nullable|numeric|min:0',
        ]);


        $serviceReturnBills =  $request->validate([
            'return_bill_product_ids' => 'nullable|array',
            'return_bill_product_ids.*' => 'nullable|integer|exists:product_catalogs,id',
            'return_bill_quantity' => 'nullable|array',
            'return_bill_quantity.*' => 'nullable|integer|min:0',
            'return_bill_price' => 'nullable|array',
            'return_bill_price.*' => 'nullable|numeric|min:0',
            'return_bill_unit_discount' => 'nullable|array',
            'return_bill_unit_discount.*' => 'nullable|numeric|min:0',
            'return_bill_total_discount' => 'nullable|array',
            'return_bill_total_discount.*' => 'nullable|numeric|min:0',
            'return_bill_amount' => 'nullable|array',
            'return_bill_amount.*' => 'nullable|numeric|min:0',
            'return_bill_net_amount' => 'nullable|numeric|min:0',
            'return_bill_description' => 'nullable|string',
        ]);


        // Validate payments
        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
            'payments_bank_id' => 'nullable|array',
            'payments_bank_id.*' => 'nullable|integer',
            'payments_branch_id' => 'nullable|array',
            'payments_branch_id.*' => 'nullable|integer|exists:bank_branches,id',
            'payments_emi_id' => 'nullable|array',
            'payments_emi_id.*' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments_transaction_id' => 'nullable|array',
            'payments_transaction_id.*' => 'nullable|string',
            'payments_date' => 'nullable|array',
            'payments_date.*' => 'required|date',
            'payments_amount' => 'nullable|array',
            'payments_amount.*' => 'nullable|numeric|min:0',
            'payments_attachments' => 'nullable|array',
            'payments_attachments.*' => 'nullable|string',
            'payments_verified' => 'nullable|array',
            'payments_verified.*' => 'nullable|in:0,1',
            'payments_remark' => 'nullable|array',
            'payments_remark.*' => 'nullable|string',
            'payments_total_amount' => 'required|numeric',
            'payments_payable_amount' => 'required|numeric',
            'payments_due_amount' => 'nullable|numeric',
            'payments_advance_amount' => 'nullable|numeric'
        ]);

        // dd($request->all(), $payments);


        $salesOrderShipments = [];
        if ($request->has('is_shipment') ?? false) {
            $salesOrderShipments = $request->validate([
                'courier_id' => ($validate['is_courier'] ?? false) ? 'required|exists:couriers,id' : 'nullable|exists:couriers,id',
                'area_id' => 'required',
                'address' => 'required|string',
                'contact_person_name' => 'required|string',
                'contact_person_number' => 'required|string',
                'condition' => 'nullable|in:on,off',
                'additional_amount' => 'nullable|numeric',
                'condition_remarks' => ($request->has('additional_amount') && $request->input('additional_amount') > 0) ? 'required|string' : 'nullable|string',
            ]);
        }

    //    dd(  $salesOrderShipments );

        // dd($validate, $payments, $serviceBills,  $pendingServiceToken);
    
        $this->service->store($validate, $pendingServiceToken, $payments, $serviceBills, $serviceReturnBills, $salesOrderShipments);
        return redirect()->route('services.service-my-task.index')->with('success', 'Service My Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['serviceMyTask'] = $this->service->show($id);

        return view("serviceMyTasks.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceMyTask $serviceMyTask)
    {
        $data['serviceMyTask'] = $serviceMyTask;
        //
        return view("serviceMyTasks.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceMyTask $serviceMyTask)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($serviceMyTask, $validate);

        return redirect()->route('serviceMyTasks.index')->with('success', 'ServiceMyTask updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceMyTask $serviceMyTask)
    {
        $this->service->delete($serviceMyTask);
        return redirect()->route('serviceMyTasks.index')->with('success', 'ServiceMyTask deleted successfully.');
    }

    public function solutionVerification()
    {
        $data['serviceMyTasks'] = ServicePendingToken::where('status', 'pending')->whereHas('serviceMyTask', function ($query) {
            $query->where('status', 'approved');
        })->get();
        $data['products'] = ProductCatalog::all();
        return view('Services::service-my-task.solution-verification', $data);
    }

    public function solutionVerificationStore(Request $request,$id)
    {
        // dd($request->all());
        $serviceMyTask = ServicePendingToken::findOrFail($id);
        $validate = $request->validate([
            'status' => 'required|in:Verified,Unchanged',
            'description' => 'nullable|string',
        ]);
        if ($validate['status'] == 'Unchanged') {
            $serviceMyTask->update([
                'status' =>  $validate['status'],
            ]);        
        }
        else {
            $serviceMyTask->update([
                'status' =>  $validate['status'],
                'description' =>  $validate['description'],
            ]);
        }
       
        return redirect()->back()->with('success', 'Solution Verified successfully.');
    }
}
