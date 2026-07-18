<?php

namespace Modules\CRM\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\AccessControl\CompanyInfo;
use App\Services\AutocompleteService;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Tag;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\BrokerCommission;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\Settings\CustomerRating;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Modules\CRM\Services\Customer\CustomerService;
use Modules\CRM\Services\Customer\CustomerSettingService;
use Modules\LocationManager\Models\Area;
use Modules\Sales\Models\Quotation;
use Modules\Sales\Models\SalesOrder;
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
        $data['customers'] = $this->service->getAll();
        $data['customerTypes'] = CustomerType::get();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('CRM::customer.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('customer_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("CRM::customer.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customerTypes'] = CustomerType::pluck('name', 'id');
        $data['employees'] = Employee::where('status', 1)->pluck('full_name', 'id');
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
            'phone' => ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/', 'unique:customers,phone,NULL,id,deleted_at,NULL'],
            'email' => 'nullable|email|max:255|unique:customers,email,NULL,id,deleted_at,NULL',
            'contact_for_sms' => 'required|string|max:20',
            'user_ref_id' => 'required|exists:employees,id',
            'customer_ref_id' => 'nullable|exists:customers,id',
            'customer_type' => 'required|integer',
            'address' => 'required|string',
            'nid' => 'nullable|string|max:255|unique:customers,nid,NULL,id,deleted_at,NULL',
            'front_image' => 'nullable',
            'back_image' => 'nullable',
            'visiting_card_front' => 'nullable',
            'visiting_card_back' => 'nullable',
            'trade_license' => 'nullable',
            'signature' => 'nullable',
            'remarks' => 'nullable|string',
            'logo' => 'nullable',
            'customer_id' => 'nullable',
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
            'owner_name' => 'array',
            'owner_name.*' => 'nullable|string|max:255',
            'owner_designation' => 'array',
            'owner_designation.*' => 'nullable',
            'owner_mobile' => 'array',
            'owner_mobile.*' => 'nullable',
            'owner_email' => 'array',
            'owner_email.*' => 'nullable',
            'owner_dob' => 'array',
            'owner_dob.*' => 'nullable',
        ]);

        try {
            // dd($validate);
            $result = $this->service->create($validate, $customerShipping, $customerOwner);

            return redirect()->route('crm.customers.edit', $result['customers']->id)->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function approve($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->status = 2; // Assuming 1 is for approved

        $customer->createUser();
        $customer->save();
        $this->service->dummyTransactionForOpeningBalance($customer);

        return redirect()->route('crm.customers.index')->with('success', 'Customer approved successfully.');
    }

    public function deny($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->status = 3; // Assuming 2 is for denied
        $customer->save();

        return redirect()->route('crm.customers.index')->with('warning', 'Customer denied successfully.');
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

    public function customerSettings($id, Request $request)
    {
        $customer = CustomerSetting::where('customer_id', $id)->first();
        // dd($data['customer']);
        if ($customer == null) {
            $data['customer'] = Customer::findOrFail($id);
        } else {
            $data['customer'] = $customer;
        }
        // dd($data['customer']);
        $data['customerRatings'] = CustomerRating::all();
        $data['percentageTypes'] = Tag::all();
       
 
        return view('CRM::customer.settings', $data);
    }
    public function getBrokerDetails(Request $request)
    {
        $broker = Broker::with(['brokerCommission', 'brokerCommission.PercentageType', 'brokerCommission.product'])->find($request->id);
        return response()->json($broker);
    }
    public function customerSettingStore($id, Request $request)
    {

        // try {
        $this->customerSetting->customerSettingStore($request);
        return redirect()->route('crm.customers.settings', $request->customer_id)->with('success', 'Customer Settings updated successfully.');
        // }
        // catch (\Exception $e) {
        //     return redirect()->back()->with('error', $e->getMessage());
        // }

    }

    public function editBrokerWithSettings($id, Request $request)
    {
        $data['broker'] = Broker::find($id);
        $data['customers'] = Customer::all();
        $data['percentageTypes'] = Tag::all();
        return view("CRM::customer.broker", $data);
    }

    public function updateBrokerWithSettings(Request $request)
    {

        $broker = Broker::find($request->broker_id);
        $commissionType = in_array(1, $request->commission_type ?? []) || in_array(2, $request->commission_type ?? []) ? 1 : 0;
        $broker->update([
            "commission_type" => $commissionType
        ]);

        // Update or add commission details for the broker
        if (in_array(1, $request->commission_type ?? []) && $request->has('percentage_type')) {
            $brokerCommission = BrokerCommission::where("broker_id", $request->broker_id)
                ->where('commission_type', 1)
                ->delete();

            foreach ($request->percentage_type as $key => $percentageType) {
                if ($percentageType != null) {
                    BrokerCommission::create([
                        'commission_type' => '1',
                        'broker_id' => $request->broker_id,
                        'percentage_type' => $percentageType,
                        'percentage' => $request->percentage[$key] ?? null,
                    ]);
                }
            }
        }

        if (in_array(2, $request->commission_type ?? [])) {
            $brokerCommission = BrokerCommission::where("broker_id", $request->broker_id)
                ->whereIn('commission_type', ['2', '3'])
                ->delete();

            foreach ($request->fixed_type as $key => $fixedType) {

                if ($key == 0) {
                    if ($fixedType != null && $request->fixed[$key] != 0) {
                        BrokerCommission::create([
                            'commission_type' => '3',
                            'broker_id' => $request->broker_id,
                            'fixed_type' => $request->fixed_type[$key],
                            'fixed' => $request->fixed[$key] ?? 0,
                        ]);
                    }
                } else {
                    if ($fixedType != null && $request->fixed[$key] != 0) {
                        BrokerCommission::create([
                            'commission_type' => '2',
                            'broker_id' => $request->broker_id,
                            'fixed_type' => $request->fixed_type[$key],
                            'fixed' => $request->fixed[$key] ?? 0,
                        ]);
                    }
                }
            }
        }


        return response()->json(['success' => true, 'message' => 'Broker Commission updated successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $customer = $this->service->show($id);
        $customerTypes = CustomerType::pluck('name', 'id');
        $data = [
            'customer' => $customer,
            'customerTypes' => $customerTypes,
        ];
        $data['salesOrders'] = SalesOrder::where('customer_id', $customer->id)
            ->latest()
            ->paginate(10, ['*'], 'sales_page');

        $data['quotations'] = Quotation::where('customer_name', $customer->company_name)
            ->latest()
            ->paginate(10, ['*'], 'quotation_page');



        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('CRM::customer.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('customer_' . $data['customer']->company_name . '.pdf', ['Attachment' => false]);
        }
        // dd($data);

        return view("CRM::customer.show", $data);
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $data['customerTypes'] = CustomerType::pluck('name', 'id');
        $data['employees'] = Employee::pluck('full_name', 'id');
        $data['customer'] = $customer;
        return view("CRM::customer.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // dd($request->all());
        $validate = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_place_id' => 'required',
            'phone' => ['required', 'regex:/^(?:\+?88|01)?01[3-9]\d{8}$/'],
            'email' => 'nullable|email|max:255',
            'contact_for_sms' => 'required|string|max:20',
            'user_ref_id' => 'required|exists:employees,id',
            'customer_ref_id' => 'nullable|exists:customers,id',
            'customer_type' => 'required|integer',
            'address' => 'required|string',
            'nid' => 'nullable|string|max:255',
            'front_image' => 'nullable',
            'back_image' => 'nullable',
            'visiting_card_front' => 'nullable',
            'visiting_card_back' => 'nullable',
            'trade_license' => 'nullable',
            'signature' => 'nullable',
            'remarks' => 'nullable|string',
            'logo' => 'nullable',
            'customer_id' => 'nullable',
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
            'owner_name' => 'array',
            'owner_name.*' => 'nullable|string|max:255',
            'owner_designation' => 'array',
            'owner_designation.*' => 'nullable',
            'owner_mobile' => 'array',
            'owner_mobile.*' => 'nullable',
            'owner_email' => 'array',
            'owner_email.*' => 'nullable',
            'owner_dob' => 'array',
            'owner_dob.*' => 'nullable',
        ]);
        $result = $this->service->update($customer, $validate, $customerShipping, $customerOwner);

        return redirect()->route('crm.customers.edit', $customer->id)->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $this->service->delete($customer);

        return redirect()->route('crm.customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function countCustomer()
    {
        return response()->json(["count" => $this->service->countCustomer(), "current_month" => $this->service->countCustomerCurrentMonth(), "previous_month" => $this->service->countCustomerPreviousMonth()]);
    }

    /**
     * Get customers data with id and name
     */
    public function getCustomers()
    {
        $customers = $this->service->getCustomers();

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    public function downloadSampleCSV()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_customers.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = [
            'customer_id',
            'company_name',
            'company_place',
            'phone',
            'email',
            'contact_for_sms',
            'user_ref',
            'customer_ref',
            'customer_type',
            'address',
            'nid',
            'remarks',
            'ship_to',
            'shipping_address',
            'shipping_phone',
            'owner_name',
            'owner_designation',
            'owner_mobile',
            'owner_email',
            'owner_dob',
            'customer_rating',
            'customer_status',
            'credit_limit',
            'additional_credit_limit',
            'opening_balance',
            'is_condition_bill',
            'minimum_condition_bill',
            'vat_status',
            'is_document_return',
            'service_applicable',
            'discount_type',
            'percentage_type_names', // Multiple percentage type names, pipe-separated
            'percentages',          // Multiple percentages, pipe-separated
            'product_names',        // Multiple product names, pipe-separated
            'sales_amounts',        // Multiple sales amounts, pipe-separated
            'broker_names',         // Multiple broker names, pipe-separated
            'broker_statuses'       // Multiple broker statuses, pipe-separated
        ];

        try {
            $callback = function () use ($columns) {
                $file = fopen('php://output', 'w');
                // Output header row.
                fputcsv($file, $columns);
                // Add a sample row with example data.
                fputcsv($file, [
                    'CUST-005',            // customer_id
                    'Nayan',      // company_name
                    'Sagor Dhigi',             // company_place
                    "\t+8801712345678",     // phone
                    'example@company.com',  // email
                    "\t01712345678",        // contact_for_sms
                    'Shahbuddin Nayan',     // user_ref
                    'Lab Aid Ltd.', // customer_ref
                    'Diagnostic',           // customer_type
                    '123 Example Street, City', // address
                    '1234567890',           // nid
                    'Sample remarks',       // remarks
                    'Main Office',          // ship_to
                    '123 Main St',          // shipping_address
                    "\t+8801712345678",     // shipping_phone
                    'John Doe',             // owner_name
                    'Director',             // owner_designation
                    "\t+8801712345678",     // owner_mobile
                    'john@example.com',     // owner_email
                    '1990-01-01',           // owner_dob
                    '1',                    // customer_rating
                    '1',                    // customer_status
                    '10000',                // credit_limit
                    '5000',                 // additional_credit_limit
                    '0',                    // opening_balance
                    '0',                    // is_condition_bill
                    '1',                    // minimum_condition_bill
                    '0',                    // vat_status
                    '0',                    // is_document_return
                    '0',                    // service_applicable
                    '3',                    // discount_type
                    'IC|BC',     // percentage_type_names
                    '10|15',               // percentages
                    'Test Product Two|Test Product One',  // product_names
                    '100|200',             // sales_amounts
                    'MD Amin Mia|Shabuddin', // broker_names
                    '1|0'                  // broker_statuses
                ]);
                fclose($file);
            };
        } catch (\Exception $e) {
            $callback = function () use ($e) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Error', $e->getMessage()]);
                fclose($file);
                return response()->json(['error' => $e->getMessage()], 422);
            };
        }

        return response()->streamDownload($callback, 'sample_customers.csv', $headers);
    }

    public function insertFromCSV(Request $request)
    {
        $file = $request->file('csv_file');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('public', $filename);
        $this->service->insertFromCSV($filename);
        return redirect()->route('crm.customers.index')->with('success', 'Customer imported successfully.');
    }
 
    
    public function customerAutocomplete(Request $request, AutocompleteService $autocompleteService)
    { 
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
  
        $data = $autocompleteService->customerSearch(
            Customer::class,
            ['company_name','address','phone'],
            $request->search,
            ['id', 'company_name','company_place_id', 'phone', 'customer_type', 'address'],
            30
        ); 

        
        return response()->json($data);
    }

    

    public function areaAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->search(
            Area::class,
            ['area'],

            $request->search,
            ['id', 'area'],
            20
        ); 
        return response()->json($data);
    }
    public function brokerAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->search(
            Broker::class,
            ['broker_name'],

            $request->search,
            ['id', 'broker_name'],
            20
        ); 
        return response()->json($data);
    }
    
}
