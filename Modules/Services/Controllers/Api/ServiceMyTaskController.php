<?php

namespace Modules\Services\Controllers\Api;


use App\Http\Controllers\Controller;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceMyTask;
use Modules\Services\Models\ServicePendingToken;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Services\ServiceMyTaskService;
use Illuminate\Http\Request;
use Modules\Account\Models\Customer;
use Modules\Account\Models\Setup\Bank;

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
        try{
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
        
        
        
        $data['myTasks']->transform(function ($token) {
            $engineerAssign = $token->engineerAssign;
            $assignBy = null;

            if ($engineerAssign) {
                if ($engineerAssign->createdBy) {
                    $assignBy = $engineerAssign->createdBy->name;
                } elseif ($engineerAssign->engineers->first()) {
                    $assignBy = $engineerAssign->engineers->first()->full_name;
                }
            }

            $token->service_date   = $engineerAssign->service_date ?? null;
            $token->assign_by      = $assignBy;
            $token->service_status = $token->action;
            $token->priority       = $engineerAssign->service_priority ?? null;

            return $token;
        });



        return response()->json([
            'message' => 'Success',
            'success' => true,
            'data' => $data,
        ]);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }


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
        // dd ($request);
        try {
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

            $pendingServiceTokenData = null;
            if($validate['status'] != 'cancelled') {
                $pendingServiceTokenData = $request->validate([
                    'pending_tokens' => 'required|array',
                    'pending_tokens.*.id' => 'required|integer|exists:service_tokens,id',
                    'pending_tokens.*.description' => 'required|string|min:100',
                ],[
                    'pending_tokens.*.description.min' => 'The pending description must be at least 100 characters.',
                ]);
            }

            $serviceBillsData = $request->validate([
                'service_bills' => 'nullable|array',
                'service_bills.*.product_id' => 'nullable|integer|exists:product_catalogs,id',
                'service_bills.*.quantity' => 'nullable|integer|min:0',
                'service_bills.*.price' => 'nullable|numeric|min:0',
                'service_bills.*.unit_discount' => 'nullable|numeric|min:0',
                'service_bills.*.total_discount' => 'nullable|numeric|min:0',
                'service_bills.*.amount' => 'nullable|numeric|min:0',
            ]);

            $serviceReturnBillsData =  $request->validate([
                'service_return_bills' => 'nullable|array',
                'service_return_bills.*.product_id' => 'nullable|integer|exists:product_catalogs,id',
                'service_return_bills.*.quantity' => 'nullable|integer|min:0',
                'service_return_bills.*.price' => 'nullable|numeric|min:0',
                'service_return_bills.*.unit_discount' => 'nullable|numeric|min:0',
                'service_return_bills.*.total_discount' => 'nullable|numeric|min:0',
                'service_return_bills.*.amount' => 'nullable|numeric|min:0',
                'return_bill_net_amount' => 'nullable|numeric|min:0',
            ]);

            $paymentsData = $request->validate([
                'payments' => 'nullable|array',
                'payments.*.pay_mode' => 'required|in:Cash,Cheque,Online Deposit,Bkash,Card',
                'payments.*.bank_id' => 'nullable|integer|exists:banks,id',
                'payments.*.branch_id' => 'nullable|integer|exists:bank_branches,id',
                'payments.*.transaction_id' => 'nullable|string',
                'payments.*.date' => 'required|date',
                'payments.*.amount' => 'nullable|numeric|min:0',
                'payments.*.attachments' => 'nullable|string',
                'payments.*.verified' => 'nullable|in:0,1',
            ]);

            // Transform pending_tokens data
            $pendingServiceToken = [
                'pending_token_ids' => [],
                'pending_descriptions' => [],
            ];
            if (isset($pendingServiceTokenData['pending_tokens'])) {
                foreach ($pendingServiceTokenData['pending_tokens'] as $token) {
                    $pendingServiceToken['pending_token_ids'][] = $token['id'];
                    $pendingServiceToken['pending_descriptions'][$token['id']] = $token['description'];
                }
            }

            // Transform service_bills data
            $serviceBills = [
                'bill_product_ids' => [],
                'bill_quantity' => [],
                'bill_price' => [],
                'bill_unit_discount' => [],
                'bill_total_discount' => [],
                'bill_amount' => [],
            ];
            if (isset($serviceBillsData['service_bills'])) {
                foreach ($serviceBillsData['service_bills'] as $bill) {
                    $serviceBills['bill_product_ids'][] = $bill['product_id'];
                    $serviceBills['bill_quantity'][] = $bill['quantity'];
                    $serviceBills['bill_price'][] = $bill['price'];
                    $serviceBills['bill_unit_discount'][] = $bill['unit_discount'];
                    $serviceBills['bill_total_discount'][] = $bill['total_discount'];
                    $serviceBills['bill_amount'][] = $bill['amount'];
                }
            }

            // Transform service_return_bills data
            $serviceReturnBills = [
                'return_bill_product_ids' => [],
                'return_bill_quantity' => [],
                'return_bill_price' => [],
                'return_bill_unit_discount' => [],
                'return_bill_total_discount' => [],
                'return_bill_amount' => [],
                'return_bill_net_amount' => $serviceReturnBillsData['return_bill_net_amount'] ?? null,
            ];
            if (isset($serviceReturnBillsData['service_return_bills'])) {
                foreach ($serviceReturnBillsData['service_return_bills'] as $returnBill) {
                    $serviceReturnBills['return_bill_product_ids'][] = $returnBill['product_id'];
                    $serviceReturnBills['return_bill_quantity'][] = $returnBill['quantity'];
                    $serviceReturnBills['return_bill_price'][] = $returnBill['price'];
                    $serviceReturnBills['return_bill_unit_discount'][] = $returnBill['unit_discount'];
                    $serviceReturnBills['return_bill_total_discount'][] = $returnBill['total_discount'];
                    $serviceReturnBills['return_bill_amount'][] = $returnBill['amount'];
                }
            }

            // Transform payments data
            $payments = [
                'payments_pay_mode' => [],
                'payments_bank_id' => [],
                'payments_branch_id' => [],
                'payments_transaction_id' => [],
                'payments_date' => [],
                'payments_amount' => [],
                'payments_attachments' => [],
                'payments_verified' => [],
            ];
            if (isset($paymentsData['payments'])) {
                foreach ($paymentsData['payments'] as $payment) {
                    $payments['payments_pay_mode'][] = $payment['pay_mode'];
                    $payments['payments_bank_id'][] = $payment['bank_id']??null;
                    $payments['payments_branch_id'][] = $payment['branch_id']??null;
                    $payments['payments_transaction_id'][] = $payment['transaction_id']??null;
                    $payments['payments_date'][] = $payment['date'];
                    $payments['payments_amount'][] = $payment['amount'];
                    $payments['payments_attachments'][] = $payment['attachments']??null;
                }
            }

            // dd($validate, $pendingServiceToken, $payments, $serviceBills, $serviceReturnBills);

            $result = $this->service->store($validate, $pendingServiceToken, $payments, $serviceBills, $serviceReturnBills);

            return response()->json([
                'message' => 'ServiceMyTask created successfully.',
                'success' => true,
                'data' => $result
            ], 201); // 201 Created status code

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422); // 422 Unprocessable Entity for validation errors
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500); // 500 Internal Server Error for other exceptions
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($service_token_id)
        {
            try {
                $serviceMyTask = $this->service->show($service_token_id);
    
                return response()->json([
                    'success' => true,
                    'data' => $serviceMyTask
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
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
        try {
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

            $pendingServiceTokenData = null;
            if($validate['status'] != 'cancelled') {
                $pendingServiceTokenData = $request->validate([
                    'pending_tokens' => 'required|array',
                    'pending_tokens.*.id' => 'required|integer|exists:service_tokens,id',
                    'pending_tokens.*.description' => 'required|string|min:100',
                ],[
                    'pending_tokens.*.description.min' => 'The pending description must be at least 100 characters.',
                ]);
            }

            $serviceBillsData = $request->validate([
                'service_bills' => 'nullable|array',
                'service_bills.*.product_id' => 'nullable|integer|exists:product_catalogs,id',
                'service_bills.*.quantity' => 'nullable|integer|min:0',
                'service_bills.*.price' => 'nullable|numeric|min:0',
                'service_bills.*.unit_discount' => 'nullable|numeric|min:0',
                'service_bills.*.total_discount' => 'nullable|numeric|min:0',
                'service_bills.*.amount' => 'nullable|numeric|min:0',
            ]);

            $serviceReturnBillsData = $request->validate([
                'service_return_bills' => 'nullable|array',
                'service_return_bills.*.product_id' => 'nullable|integer|exists:product_catalogs,id',
                'service_return_bills.*.quantity' => 'nullable|integer|min:0',
                'service_return_bills.*.price' => 'nullable|numeric|min:0',
                'service_return_bills.*.unit_discount' => 'nullable|numeric|min:0',
                'service_return_bills.*.total_discount' => 'nullable|numeric|min:0',
                'service_return_bills.*.amount' => 'nullable|numeric|min:0',
                'return_bill_net_amount' => 'nullable|numeric|min:0',
            ]);

            $paymentsData = $request->validate([
                'payments' => 'nullable|array',
                'payments.*.pay_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
                'payments.*.bank_id' => 'nullable|integer|exists:banks,id',
                'payments.*.branch_id' => 'nullable|integer|exists:bank_branches,id',
                'payments.*.transaction_id' => 'nullable|string',
                'payments.*.date' => 'required|date',
                'payments.*.amount' => 'nullable|numeric|min:0',
                'payments.*.attachments' => 'nullable|string',
                'payments.*.verified' => 'nullable|in:0,1',
            ]);

            // Transform pending_tokens data
            $pendingServiceToken = [
                'pending_token_ids' => [],
                'pending_descriptions' => [],
            ];
            if (isset($pendingServiceTokenData['pending_tokens'])) {
                foreach ($pendingServiceTokenData['pending_tokens'] as $token) {
                    $pendingServiceToken['pending_token_ids'][] = $token['id'];
                    $pendingServiceToken['pending_descriptions'][] = $token['description'];
                }
            }

            // Transform service_bills data
            $serviceBills = [
                'bill_product_ids' => [],
                'bill_quantity' => [],
                'bill_price' => [],
                'bill_unit_discount' => [],
                'bill_total_discount' => [],
                'bill_amount' => [],
            ];
            if (isset($serviceBillsData['service_bills'])) {
                foreach ($serviceBillsData['service_bills'] as $bill) {
                    $serviceBills['bill_product_ids'][] = $bill['product_id'];
                    $serviceBills['bill_quantity'][] = $bill['quantity'];
                    $serviceBills['bill_price'][] = $bill['price'];
                    $serviceBills['bill_unit_discount'][] = $bill['unit_discount'];
                    $serviceBills['bill_total_discount'][] = $bill['total_discount'];
                    $serviceBills['bill_amount'][] = $bill['amount'];
                }
            }

            // Transform service_return_bills data
            $serviceReturnBills = [
                'return_bill_product_ids' => [],
                'return_bill_quantity' => [],
                'return_bill_price' => [],
                'return_bill_unit_discount' => [],
                'return_bill_total_discount' => [],
                'return_bill_amount' => [],
                'return_bill_net_amount' => $serviceReturnBillsData['return_bill_net_amount'] ?? null,
            ];
            if (isset($serviceReturnBillsData['service_return_bills'])) {
                foreach ($serviceReturnBillsData['service_return_bills'] as $returnBill) {
                    $serviceReturnBills['return_bill_product_ids'][] = $returnBill['product_id'];
                    $serviceReturnBills['return_bill_quantity'][] = $returnBill['quantity'];
                    $serviceReturnBills['return_bill_price'][] = $returnBill['price'];
                    $serviceReturnBills['return_bill_unit_discount'][] = $returnBill['unit_discount'];
                    $serviceReturnBills['return_bill_total_discount'][] = $returnBill['total_discount'];
                    $serviceReturnBills['return_bill_amount'][] = $returnBill['amount'];
                }
            }

            // Transform payments data
            $payments = [
                'payments_pay_mode' => [],
                'payments_bank_id' => [],
                'payments_branch_id' => [],
                'payments_transaction_id' => [],
                'payments_date' => [],
                'payments_amount' => [],
                'payments_attachments' => [],
                'payments_verified' => [],
            ];
            if (isset($paymentsData['payments'])) {
                foreach ($paymentsData['payments'] as $payment) {
                    $payments['payments_pay_mode'][] = $payment['pay_mode'];
                    $payments['payments_bank_id'][] = $payment['bank_id'] ?? null;
                    $payments['payments_branch_id'][] = $payment['branch_id'] ?? null;
                    $payments['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
                    $payments['payments_date'][] = $payment['date'];
                    $payments['payments_amount'][] = $payment['amount'];
                    $payments['payments_attachments'][] = $payment['attachments'] ?? null;
                }
            }

            $result = $this->service->update($serviceMyTask, $validate, $pendingServiceToken, $payments, $serviceBills, $serviceReturnBills);

            return response()->json([
                'message' => 'ServiceMyTask updated successfully.',
                'success' => true,
                'data' => $result
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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
        $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
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

    
    public function serviceMyTask(Request $request)
    { 
        $request->validate([
            'token_id' => 'required',
        ]);
        $data['service'] = Service::first();
        $data['serviceToken'] = ServiceToken::with(['customer'])->find($request->token_id);
        if ($data['serviceToken']->service_type == 'ON SPOT') {
            $data['product'] = ProductCatalog::where('name', 'Service Charge With (TA) (DA)')->first();
        } else if($data['serviceToken']->service_type == 'ON CALL') {
            $data['product'] = ProductCatalog::where('name', 'Service Charge On Call')->first();
        } else {
            $data['product'] = ProductCatalog::where('name', 'Service Charge (IN HOUSE)')->first();
            
        }
                $data['return_product'] = ProductCatalog::where('name', 'Checking Charge')->first();

        // $data['customers'] = Customer::activeCustomers()->get();

        // $data['serviceMyTasks'] = $this->service->getAll();
        // $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        // $data['productCatalogs'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        // $data['BillingProducts'] = ProductCatalog::whereIn('name', ['Service Charge (IN HOUSE)', 'Service Charge With (TA) (DA)', 'Service Charge On Call'])->get();
        // $data['banks'] = Bank::all();
        $data['pendingServiceTokens'] = ServiceToken::with("engineerAssign.engineers")->whereIn('action', ['Pending', 'Live'])->where('customer_id', $data['serviceToken']->customer_id)->get();
        // dd($data['pendingServiceTokens']);

        $data['serviceMyTask'] = ServiceMyTask::where('service_token_id', $request->token_id)->first();

        // dd(ServiceMyTask::with('payments')->where('service_token_id', $request->token_id)->first());

        // return view('Services::service-my-task.bill', $data);
        return response()->json([
            'message' => 'Success',
            'status' => 200,
            'data' => $data,
        ]);
    }
}
