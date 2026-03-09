<?php

namespace Modules\CRM\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Services\AutocompleteService;
use Modules\Inventory\Models\Settings\Tag;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\BrokerBank;
use Modules\CRM\Models\Customer\BrokerCommission;
use Modules\CRM\Models\Customer\BrokerCustomerAttached;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Services\Customer\BrokerService;
use Modules\Inventory\Models\ProductCatalog;

class BrokerController extends Controller
{

    /**
     * Service variable
     *
     * @var BrokerService
     */
    private $service; 

    function __construct()
    {
        $this->service = new BrokerService();     
        $this->middleware('permited');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['customers'] = Customer::activeCustomers()->get();
        $data['brokers'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('CRM::broker.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('broker_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("CRM::broker.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['percentageTypes'] = Tag::all();

        $data['customers'] = Customer::activeCustomers()->get();
        return view('CRM::broker.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   

   


        // dd(request()->all());
        $validatedData = $request->validate([
            'broker_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:brokers,email,NULL,id,deleted_at,NULL',
            'mobile' =>  ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/','unique:brokers,mobile,NULL,id,deleted_at,NULL'],
            'alternative_phone' => 'nullable|string|max:20',
            'dob' => 'required|string', // You might need to adjust this based on your date format
            'gender' => 'required', 
            'commission_type' => 'required|array',
            'commission_type.*' => 'integer',
            'division_id' => 'required',
            'district_id' => 'required',
            'thana_id' => 'required',
            'nid' => 'required|string|max:20|unique:brokers,nid,NULL,id,deleted_at,NULL',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'photograph' => 'nullable',
            'front_image' => 'nullable',
            'back_image' => 'nullable',
            'broker_id' => 'nullable',
            // Validation for Broker Commission Table
            'broker_commission.*.commission_type' => 'required|array',
            'broker_commission.*.commission_type' => 'required|integer',
            'broker_commission.*.percentage_type' => 'nullable|string',
            'broker_commission.*.fixed_type' => 'nullable|string',
            'broker_commission.*.percentage' => 'nullable|numeric|min:0',
            'broker_commission.*.fixed' => 'nullable|numeric|min:0',
            // Validation for Broker Customer Attached Table
            'broker_customers.*.customer_id' => 'required|exists:customers,id',
            'broker_customers.*.status' => 'required|integer',
            // Validation for Broker Bank Table
            'broker_banks.*.bank_type' => 'nullable|string',
            'broker_banks.*.bank_name' => 'nullable|string',
            'broker_banks.*.branch_name' => 'nullable|string',
            'broker_banks.*.account_nos' => 'nullable|string',
            'broker_banks.*.e_tin_no' => 'nullable|string',
            'broker_banks.*.routing_name' => 'nullable|string',
        ]);

        // if(isset($request->customer_id[0])) {
        //     $request->validate([
        //         'customer_id.*' => 'required|exists:customers,id',
        //         'status.*' => 'required:customer_id.0|integer',
        //     ]);
        // }

        // if(isset($request->status[0])) {
        //     $request->validate([
        //         'status.*' => 'required|exists:customers,id',
        //         'customer_id.*' => 'required:status.0|integer',
        //     ]);
        // }

        // if(isset($request->percentage_type[0])) {
        //     $request->validate([
        //         'percentage_type.*' => 'required|exists:customers,id',
        //         'percentage.*' => 'required:percentage_type.0|integer',
        //     ]);
        // }

        // if(isset($request->percentage[0])) {
        //     $request->validate([
        //         'percentage.*' => 'required|exists:customers,id',
        //         'percentage_type.*' => 'required:percentage.0|integer',
        //     ]);
        // }

               
                $broker = $this->service->create($request);

                return redirect()->route('crm.brokers.edit',$broker->id)->with('success', 'Broker created successfully.');
          
    
        
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
    /**
     * Display the specified resource.
     */
    // public function show( $id)
    // {
    //     $data['broker'] = $this->service->show($id);
    //     $data['customers'] = Customer::all();

    //     return view("CRM::broker.show", $data);
    // }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
{
    $broker = $this->service->show($id);

    $data = [
        'broker' => $broker];

    if ($request->export == "pdf") {
        set_time_limit(1000);
        $html = view('CRM::broker.view', $data)->render();

        // Set Dompdf options
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('broker_' . $data['broker']->company_name . '.pdf', ['Attachment' => false]);
    }

    return view("CRM::broker.show", $data);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Broker $broker)
    {
        $data['broker'] = $broker;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['percentageTypes'] = Tag::all();  
      //  dd($data);
        return view("CRM::broker.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'broker_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'dob' => 'required|string', // You might need to adjust this based on your date format
            'gender' => 'required', 
            'commission_type' => 'required|array',
            'commission_type.*' => 'integer',
            'division_id' => 'required',
            'district_id' => 'required',
            'thana_id' => 'required',
            'nid' => 'required|string|max:20',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'photograph' => 'nullable',
            'front_image' => 'nullable',
            'back_image' => 'nullable',
            'broker_id' => 'nullable',

            // Validation for Broker Commission Table
            'broker_commission.*.commission_type' => 'required|array',
            'broker_commission.*.commission_type' => 'required|integer',
            'broker_commission.*.percentage_type' => 'nullable|string',
            'broker_commission.*.fixed_type' => 'nullable|string',
            'broker_commission.*.percentage' => 'nullable|numeric|min:0',
            'broker_commission.*.fixed' => 'nullable|numeric|min:0', 
            // Validation for Broker Customer Attached Table
            'broker_customers.*.customer_id' => 'required|exists:customers,id',
            'broker_customers.*.status' => 'required|integer',

            // Validation for Broker Bank Table
            'broker_banks.*.bank_type' => 'nullable|string',
            'broker_banks.*.bank_name' => 'nullable|string',
            'broker_banks.*.branch_name' => 'nullable|string',
            'broker_banks.*.account_nos' => 'nullable|string',
            'broker_banks.*.e_tin_no' => 'nullable|string',
            'broker_banks.*.routing_name' => 'nullable|string',
        ]);

        // if(isset($request->customer_id[0])) {
        //     $request->validate([
        //         'customer_id.*' => 'required|exists:customers,id',
        //         'status.*' => 'required:customer_id.0|integer',
        //     ]);
        // }

        // if(isset($request->status[0])) {
        //     $request->validate([
        //         'status.*' => 'required|exists:customers,id',
        //         'customer_id.*' => 'required:status.0|integer',
        //     ]);
        // }

        // if(isset($request->percentage_type[0])) {
        //     $request->validate([
        //         'percentage_type.*' => 'required|exists:customers,id',
        //         'percentage.*' => 'required:percentage_type.0|integer',
        //     ]);
        // }
        

        // if(isset($request->percentage[0])) {
        //     $request->validate([
        //         'percentage.*' => 'required|exists:customers,id',
        //         'percentage_type.*' => 'required:percentage.0|integer',
        //     ]);
        // }

        $broker = $this->service->update($request, $id);

        return redirect()->route('crm.brokers.edit',$broker->id)->with('success', 'Broker Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $broker = Broker::findOrFail($id);

        $bankBrokers = BrokerBank::where('broker_id', $broker->id)->get();

        foreach ($bankBrokers as $bankBroker) {
            $bankBroker->delete();
        }

        $customerAttached = BrokerCustomerAttached::where('broker_id', $broker->id)->get();
         
        foreach( $customerAttached as $customer) {
            $customer->delete();
        }

        $brokerCommission = BrokerCommission::where('broker_id', $broker->id)->get();

        foreach( $brokerCommission as $commission) {  
            $commission->delete();
        }

        $broker->delete();

        return redirect()->route('crm.brokers.index')->with('success',' Broker deleted successfully.');
    }


    public function downloadSampleCSV() {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_brokers.csv"',
        ];
    
        $columns = [
            'broker_id',
            'broker_name', 'mobile', 'alternative_phone', 'email', 'nid', 'dob', 'gender',
            'present_address', 'permanent_address', 'division', 'district', 'thana',
            'commission_type',
            'percentage_type_1', 'percentage_1', 'fixed_type_1', 'fixed_1',
            'percentage_type_2', 'percentage_2', 'fixed_type_2', 'fixed_2',
            'customer_name', 'customer_status', 
            'bank_type', 'bank_name', 'branch_name', 'account_nos', 'e_tin_no', 'routing_name'
        ];
    
        $sampleData = [
            [
                'B001',
                'Shabuddin', '01711234567', '', 'john@example.com', '123456789', '1980-01-01', 'male',
                'Dhaka', 'Chittagong', 'Chittagong', "Cox's Bazar", 'Ramu',
                'Percentage', 
                'IC', '10', '', '',
                'BC', '10', '', '',
                'Lab Aid Ltd.', '1',
                'Bank', 'ABC Bank', 'Gulshan Branch', '123456789', '', ''
            ],
            [
                'B002',
                'Shadhin', '01711234568', '', 'shadhin@example.com', '987654321', '1985-05-15', 'male',
                'Dhaka', 'Dhaka', 'Chittagong', "Cox's Bazar", 'Ramu',
                'Fixed', 
                '', '', 'Monthly', '200',
                '', '', '', '',
                'Lab Aid Ltd.', '1',
                'Bank', 'XYZ Bank', 'Motijheel Branch', '987654321', '', ''
            ]
        ];
    
        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

    public function insertFromCSV(Request $request){
        $file = $request->file('csv_file');
        // if(!$file){
        //     return redirect()->route('inv.product-catalogs.index')->with('error', 'File cannot be uploaded.');
        // }
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('public', $filename);
        $this->service->insertFromCSV($filename);
        return redirect()->route('crm.brokers.index')->with('success', 'Supplier imported successfully.');
    }

    public function approve($id)
    {
        $broker = Broker::findOrFail($id);
        $broker->status = 2; // Assuming 1 is for approved
        $broker->save();

        return redirect()->route('crm.brokers.index')->with('success', 'Broker approved successfully.');
    }

    public function deny($id)
    {
        $broker = Broker::findOrFail($id);
        $broker->status = 3; // Assuming 2 is for denied
        $broker->save();

        return redirect()->route('crm.brokers.index')->with('warning', 'Broker denied successfully.');
    }


    public function productAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->productSearch(
            ProductCatalog::class,
            ['name','model'],
            $request->search,
            ['id', 'name','model','product_brand_id'],
            30
        ); 
        return response()->json($data);
    }

}
