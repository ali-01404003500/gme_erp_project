<?php

namespace Modules\CRM\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Tag;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\BrokerCommission;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Modules\CRM\Services\Customer\CustomerService;
use Modules\CRM\Services\Customer\CustomerSettingService;
use Modules\LocationManager\Models\Area;
use PDF;

use function Termwind\render;


class CustomerController extends Controller
{

    /**
     * Service variable
     *
     * @var CustomerService
     */
    private $service; 
    private $customerSetting;
    function __construct(CustomerService $service, CustomerSettingService $customerSetting)
    {
        $this->middleware('permited')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('permitedSlug:dashboard')->only(['countCustomer']);

        $this->service = $service;
        $this->customerSetting = $customerSetting;
        // middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
        $data['customers'] = $this->service->getAll();

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
     * Get detailed financial information and balance for a specific customer.
     *
     * @param int $id Customer ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function balanceDetails($id)
    {
        try {
            $customer = Customer::select('id', 'company_name', 'phone', 'email', 'address', 'nid', 'remarks')
                ->findOrFail($id);

            // Total number of orders (approved/delivered/partial)
            $totalOrders = $customer->salesOrders()
                ->whereIn('status', ['delivered', 'partial', 'approved'])
                ->count();

            // Total sales and returns (all time)
            $totalSales = $customer->salesOrders()
                ->whereIn('status', ['delivered', 'partial', 'approved'])
                ->sum('net_amount');

            $totalReturns = $customer->salesReturns()
                ->sum('net_amount');

            // Total payments (collections)
            $account = $customer->getAccount();
            $totalPayments = 0;
            $transactions = collect([]);

            if ($account) {
                $totalPayments = Transaction::where('account_id', $account->id)
                    ->where('balance_type', 'credit')
                    ->sum('credit_amount');

                // Recent transactions for ledger
                $transactions = Transaction::where('account_id', $account->id)
                    ->select('id', 'created_at', 'balance_type', 'credit_amount', 'debit_amount', 'description')
                    ->latest('created_at')
                    ->latest('id')
                    ->limit(50)
                    ->get();
            }

            // Current due (overall receivable balance)
            $currentDue = $totalSales - $totalReturns - $totalPayments;

            // Advance balance
            $advanceAccount = $customer->getAdvanceAccount();
            $advanceBalance = 0;
            if ($advanceAccount) {
                $credits = Transaction::where('account_id', $advanceAccount->id)->sum('credit_amount');
                $debits = Transaction::where('account_id', $advanceAccount->id)->sum('debit_amount');
                $advanceBalance = $credits - $debits; // Normal credit balance = advance received
            }

            // Recent invoices (due contributors)
            $recentInvoices = $customer->salesOrders()
                ->whereIn('status', ['delivered', 'partial', 'approved'])
                ->where('paid_status', 'unpaid')
                ->latest('invoice_date')
                ->limit(20)
                ->get();

            // Recent returns
            $recentReturns = $customer->salesReturns()
                ->select('id', 'return_date', 'net_amount')
                ->latest('return_date')
                ->limit(20)
                ->get();

            $data = [
                'customer_info' => $customer,
                'advance_balance_amount' => $advanceBalance,
                'total_number_of_orders' => $totalOrders,
                'total_payments' => $totalPayments,
                'current_due' => $currentDue,
                'total_sales' => $totalSales,
                'total_returns' => $totalReturns,
                'due_payments_list' => [ // Recent invoices considered as outstanding/due items
                    'invoices' => $recentInvoices,
                  
                ],
            ];

            return response()->json([
                'data' => $data,
                'status' => true,
                'message' => 'Customer balance details retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customerTypes'] = CustomerType::pluck('name', 'id');
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::pluck('full_name','id');
        $data['areas'] = Area::get();
        return view('CRM::customer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * 'regex:/^(?:(?:\+|00)88)?01[3-9]\d{8}$/'
     */
    public function store(Request $request)
{
    // dd($request->all());
    $validate = $request->validate([
        'company_name' => 'required|string|max:255',
        'company_place_id' => 'required',
        'phone' => ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/','unique:customers,phone,NULL,id,deleted_at,NULL'],
        'email' => 'nullable|email|max:255|unique:customers,email,NULL,id,deleted_at,NULL',
        'contact_for_sms' => 'nullable|string|max:20',
        'user_ref_id'=> 'required|exists:employees,id',
        'customer_ref_id'=> 'nullable|exists:customers,id',
        'user_reference' => 'nullable|string|max:255',
        'customer_reference' => 'nullable|string|max:255',
        'customer_type' => 'required|integer',
        'address' => 'nullable|string',
        'nid' => 'required	|string|max:255|unique:customers,nid,NULL,id,deleted_at,NULL',
        'front_image' =>  'nullable|image',
        'back_image' =>  'nullable|image',
        'visiting_card_front' =>  'nullable|image',
        'visiting_card_back' =>  'nullable|image',
        'trade_license' => 'nullable|image',
        'signature' => 'nullable|image',
        'remarks' => 'nullable|string',   
        'logo' => 'nullable|image',                                
    ]);
    $customerShipping = $request->validate([
        'ship_to' => 'array',
        'ship_to.*' => 'nullable|string',
        'shipping_address' => 'array',
        'shipping_address.*' => 'nullable|string',
        'shipping_phone' => 'array',
        'shipping_phone.*' => 'nullable|string',
    ]);

    $customerOwner = $request->validate([
        'owner_name'=> 'array',
        'owner_name.*' => 'nullable|string|max:255',
        'owner_designation'=> 'array',
        'owner_designation.*' => 'nullable', 
        'owner_mobile'=> 'array',
        'owner_mobile.*'=> 'nullable',
        'owner_email'=> 'array',
        'owner_email.*'=> 'nullable',
        'owner_dob'=> 'array',
        'owner_dob.*'=> 'nullable',
    ]);
    
    try {
        // dd($validate);
        $result = $this->service->create($validate, $customerShipping , $customerOwner);

        return response()->json([
            'data' => $result,
            'status' => true, 'message' => 'Customer created successfully'
        ]);
    }
    catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
    }
    
}



    /**
     * Convert image to base64 string
     *
     * @param string $path
     * @return string|null
     */
    private function convertImageToBase64($path)
    {
        $fileContents = file_exists($path) ? file_get_contents($path) : null;

        if ($fileContents !== false) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($fileContents);
            return $base64;
        }

        return null;
    }

    public function customerSettings($id, Request $request){
        $data['customer'] = CustomerSetting::where('customer_id', $id)->first();
        if($data['customer'] == null){
             $data['customer'] = Customer::findOrFail($id);
        }
        $data['percentageTypes'] = Tag::all();
        $data['brokers'] = Broker::activeBrokers()->get();
        
        $data['products'] = ProductCatalog::with('tag')
        ->whereHas('tag', function ($query) use ($request) {
            $query->where('code','PDTG-001')->orWhere('code','PDTG-009');
        })
        ->get();      
        return view('CRM::customer.settings', $data);
    }
    public function getBrokerDetails(Request $request)
    {
        $broker = Broker::with(['brokerCommission', 'brokerCommission.PercentageType'])->find($request->id);
        return response()->json($broker);
    }
    public function customerSettingStore($id, Request $request){

        // try {
            $this->customerSetting->customerSettingStore($request);
            return redirect()->route('crm.customers.settings', $request->customer_id)->with('success', 'Customer Settings updated successfully.');
        // }
        // catch (\Exception $e) {
        //     return redirect()->back()->with('error', $e->getMessage());
        // }

    }

    public function editBrokerWithSettings($id, Request $request){
        $data['broker'] = Broker::find($id);
        $data['customers'] = Customer::activeCustomers()->get();
        $data['percentageTypes'] = Tag::all();
        return view("CRM::customer.broker", $data);
    }

    public function updateBrokerWithSettings(Request $request) {
        $brokerCommission = BrokerCommission::where("broker_id", $request->broker_id)->delete();
        $broker = Broker::find($request->broker_id);
    
        $broker->update([
            "commission_type" => $request->commission_type
        ]);
    
        if ($request->commission_type == 1 && $request->has('percentage_type')) {
            foreach ($request->percentage_type as $key => $percentageType) {
                if ($percentageType != null) {
                    BrokerCommission::create([
                        'commission_type' => $request->commission_type,
                        'broker_id' => $request->broker_id,
                        'percentage_type' => $percentageType,
                        'percentage' => $request->percentage[$key] ?? null,
                    ]);
                }
            }
        } elseif ($request->commission_type == 2) {
            if ($request->filled('fixed_type') && $request->filled('fixed')) {
                BrokerCommission::create([
                    'commission_type' => $request->commission_type,
                    'broker_id' => $request->broker_id,
                    'fixed_type' => $request->fixed_type,
                    'fixed' => $request->fixed,
                ]);
            }
        }
    
        return response()->json(['success' => true, 'message' => 'Broker Commission updated successfully.']);
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        try{
        $customer = $this->service->show($id);
        $customerTypes = CustomerType::pluck('name', 'id');
        $data = [
            'customer' => $customer,
            'customerTypes' => $customerTypes,
        ];

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
    public function edit(Customer $customer)
    {
        $data['customerTypes'] = CustomerType::pluck('name', 'id');
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::pluck('full_name','id');
        $data['areas'] = Area::get();
        $data['customer'] = $customer;
        return view("CRM::customer.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validate = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_place_id' => 'required',
            'phone' => ['required', 'regex:/^(?:\+?88|01)?01[3-9]\d{8}$/'],
            'email' => 'nullable|email|max:255',
            'contact_for_sms' => 'nullable|string|max:20',
            'user_ref_id'=> 'required|exists:employees,id',
            'customer_ref_id'=> 'nullable|exists:customers,id',
            'customer_type' => 'required|integer',
            'address' => 'nullable|string',
            'nid' => 'required|string|max:255',
            'front_image' => 'nullable|image',
            'back_image' => 'nullable|image',
            'visiting_card_front' => 'nullable|image',
            'visiting_card_back' => 'nullable|image',
            'trade_license' => 'nullable|image',
            'signature' => 'nullable|image',
            'remarks' => 'nullable|string',
            'logo'=> 'nullable|image',
        ]);

        $customerShipping = $request->validate([
            'ship_to' => 'array',
            'ship_to.*' => 'nullable|string',
            'shipping_address' => 'array',
            'shipping_address.*' => 'nullable|string',
            'shipping_phone' => 'array',
            'shipping_phone.*' => 'nullable|string',
        ]);
    
        $customerOwner = $request->validate([
            'owner_name'=> 'array',
            'owner_name.*' => 'nullable|string|max:255',
            'owner_designation'=> 'array',
            'owner_designation.*' => 'nullable', 
            'owner_mobile'=> 'array',
            'owner_mobile.*'=> 'nullable',
            'owner_email'=> 'array',
            'owner_email.*'=> 'nullable',
            'owner_dob'=> 'array',
            'owner_dob.*'=> 'nullable',
        ]);
        $result = $this->service->update($customer, $validate, $customerShipping, $customerOwner);

        return redirect()->route('crm.customers.edit', $customer->id )->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try{
        $this->service->delete($customer);
        return response()->json(['success' => true, 'message' => 'Customer deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

    public function countCustomer(){
        return response()->json(["count" => $this->service->countCustomer(),"current_month" =>$this->service->countCustomerCurrentMonth(), "previous_month" => $this->service->countCustomerPreviousMonth()]);
    }


    
    public function getCustomers(){
        try {
            $data = Customer::select('id', 'company_name', 'status', 'phone', 'email', 'address')->get();

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



    public function getCustomerTypes(){
        try {
            $data = CustomerType::select('id', 'name')->get();

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
}
