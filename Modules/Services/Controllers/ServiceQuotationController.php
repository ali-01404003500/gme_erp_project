<?php

namespace Modules\Services\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Services\AutocompleteService;
use App\Services\Notifications\GeneralNotificationService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\Delivery;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use Modules\Services\Models\ServiceQuotation;
use Modules\Services\Models\Service;
use Modules\Services\Services\ServiceQuotationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Services\Models\ServiceToken;

class ServiceQuotationController extends Controller
{

    /**
     * Service variable
     *
     * @var ServiceQuotationService
     */
    private $service;
    private $generalNotificationService;

    function __construct(ServiceQuotationService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['quotations'] = $this->service->getAll();
        $data['customer'] = Customer::find($request->customer_id);
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Services::quotation.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('quotation_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Services::quotation.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
       
        $data['selected_service_id'] = $request->service_id; // pass selected service id from query parameter
        return view('Services::quotation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $quotation_no = $this->getQuotationNumber();

        $validate = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'customer_id' => 'required|string',
            'date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'percentage' => 'nullable|numeric',
            'total' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);
        $validate['percentage'] = 0; // Allow percentage to be nullable


        $quotationDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'nullable|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);

        $validate['quotation_no'] = $quotation_no;

        $result = $this->service->store($validate, $quotationDetails);
        $this->generalNotificationService->store([
            'title' => 'New ServiceQuotation Added',
            'description' => 'New ServiceQuotation Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(ServiceQuotationController::class, 'approve', [$result['quotation']->id]),
        ], $this->generalNotificationService->getPermittedUsers('services.quotations.approve'));
        return redirect()->route('services.quotations.index')->with('success', 'ServiceQuotation created successfully.');
    }

    public function getQuotationNumber()
    {
        $count_purchase_number = ServiceQuotation::count();
        if ($count_purchase_number == 0) {
            $user_id = auth()->user()->id;
            $serial_number = str_pad(1, 3, '0', STR_PAD_LEFT);

            return 'QUO-'
                . date('Ymd')
                . '-'
                .'USR-'
                . str_pad($user_id, 6, '0', STR_PAD_LEFT)
                . '-'
                . $serial_number;
        } else {
            $last_job_id = ServiceQuotation::orderBy('id', 'desc')->pluck('id')->first();
            $user_id = ServiceQuotation::find($last_job_id)->created_by;
            $serial_number = str_pad(ServiceQuotation::where('created_by', $user_id)->count() + 1, 3, '0', STR_PAD_LEFT);

            return 'QUO-'
                . date('Ymd', strtotime(ServiceQuotation::find($last_job_id)->created_at))
                . '-'
                .'USR-'
                . str_pad($user_id, 6, '0', STR_PAD_LEFT)
                . '-'
                . $serial_number;
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['quotation'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return view("Services::quotation.show", $data);
    }

    /**
     * Show the form for printing the specified resource.
     */
    public function print($id)
    {
        $data['quotation'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return view("Services::quotation.print", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceQuotation $quotation)
    {
        $data['quotation'] = $quotation;

        return view("Services::quotation.edit", $data);
    }

    public function approval($id)
    {
        $quotation = $this->service->show($id);
        $data['quotation'] = $quotation;
        $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['customers'] = Customer::activeCustomers()->get();
        ;

        return view("Services::quotation.approval", $data);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceQuotation $quotation)
    {
        $validate = $request->validate([
            "service_id" => "string|nullable|exists:services,id",
            'customer_id' => 'required|string',
            'date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'percentage' => 'nullable|numeric',
            'total' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);
        $validate['percentage'] = 0; // Allow percentage to be nullable


        $quotationDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'nullable|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);

        $this->service->update($quotation, $validate, $quotationDetails);

        return redirect()->route('services.quotations.index')->with('success', 'ServiceQuotation updated successfully.');
    }
    // public function approveStore(Request $request, $id)
    // {

    //     $quotation = ServiceQuotation::find($id);

    //     $validate = $request->validate([
    //         'customer_name' => 'required|string',
    //         'area' => 'required|string',
    //         'address' => 'nullable|string',
    //         'phone' => ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/'],
    //         'customer_type' => 'required|integer|exists:customer_types,id',
    //         'date' => 'required|date',
    //         'total_amount' => 'required|numeric',
    //         'discount' => 'required|numeric',
    //         'percentage' => 'required|numeric',
    //         'total' => 'required|numeric',
    //         'net_amount' => 'required|numeric',
    //         'remarks' => 'nullable|string',
    //         'status' => 'required|integer',
    //     ]);

    //     $validate['approved_by'] = auth()->user()->id;

    //     $quotationDetails = $request->validate([
    //         'product_ids.*' => 'required|integer|exists:product_catalogs,id',
    //         'quantity.*' => 'required|numeric',
    //         'price.*' => 'required|numeric',
    //         'unit_discount.*' => 'nullable|numeric',
    //         'total_discount.*' => 'required|numeric',
    //         'amount.*' => 'required|numeric',
    //     ]);
    //     $quotationTerms = $request->validate([
    //         'quotation_to' => 'nullable|string',
    //         'email' => 'nullable|string',
    //         'attn' => 'nullable|string',
    //         'attn_cell' => 'nullable|string',
    //         'payment' => 'nullable|string',
    //         'payment_method' => 'nullable|string',
    //         'tax_vat' => 'nullable|string',
    //         'installation' => 'nullable|string',
    //         'training' => 'nullable|string',
    //         'warranty' => 'nullable|string',
    //         'buyers_responsibility' => 'nullable|string',
    //         'validity' => 'nullable|string',
    //         'delivery_info' => 'nullable|string',
    //     ]);
    //     $this->service->update($quotation, $validate, $quotationDetails);

    //     return redirect()->route('services.quotations.index')->with('success', 'ServiceQuotation Approved successfully.');
    // }

    public function getSalesOrderId($supplier_id)
    {
        $today = date('Y-m-d');

        $customer_count = SalesOrder::whereDate(DB::raw('DATE(created_at)'), $today)->count();

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        $SalesOrderToday = SalesOrder::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        // Generate Sales Order number with the appropriate format
        $SalesOrderNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-SL-%06d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser,
            $SalesOrderToday + 1
        );

        return $SalesOrderNumber;
    }

    public function salesOrder(Request $request)
    {
        $quotation = ServiceQuotation::find($request->id);
        $sales_order_id = $this->getSalesOrderId($quotation->customer_id);


        $salesOrder = SalesOrder::create([
            'customer_id' => $quotation->customer_id,
            'sales_order_id' => $sales_order_id,
            'invoice_date' => today()->format('Y-m-d'),
            'total_amount' => $quotation->total_amount,
            'discount' => $quotation->discount,
            'commission' => 0,
            'total' => $quotation->total,
            'vat' => 0,
            'net_amount' => $quotation->total,
            'remarks' => $quotation->remarks,
            'status' => 'pending',
            'source_type' => ServiceQuotation::class,
            'source_id' => $quotation->id,
        ]);


        foreach ($quotation->quotationDetails as $item) {
            SalesOrderDetails::create([
                'sales_order_id' => $salesOrder->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'unit_discount' => $item->unit_discount??0,
                'total_discount' => $item->total_discount??0,
                'amount' => $item->amount,
                'is_offers_product' => false, // Default value for non-offer products
            ]);
        }
        Delivery::updateOrCreate([
            'source_id' => $salesOrder->id,
            'source_type' => SalesOrder::class,
        ], [
            'delivery_date' => today()->format('Y-m-d'),
        ]);

        $quotation->update([
            'status' => 2
        ]);

        return redirect()->route('sales.sales-orders.index')->with('success', 'SalesOrder created successfully for ServiceQuotation.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceQuotation $quotation)
    {
        $this->service->delete($quotation);
        return redirect()->route('services.quotations.index')->with('success', 'ServiceQuotation deleted successfully.');
    }

    public function serviceAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->search(
            Service::class,
            ['service_unique_id'],

            $request->search,
            ['id', 'service_unique_id'],
            20
        ); 
        return response()->json($data);
    }

    public function getCustomerByService(Request $request)
    {     

        $service = ServiceToken::with('customer')
            ->where('service_id', $request->service_id)
            ->first();
 

        return response()->json([
            'id' => $service?->customer?->id,
            'name' => $service?->customer?->name,
        ]);
    }

    
}
 