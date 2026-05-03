<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\Courier;
use Modules\Sales\Models\Quotation;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use App\Services\Notifications\GeneralNotificationService;
use Modules\Sales\Services\QuotationService;
use Illuminate\Http\Request; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Modules\LocationManager\Models\Area;
use Modules\Sales\Models\SalesRequisition;
use Modules\Sales\Services\SalesOrderService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class QuotationController extends Controller
{

    /**
     * Service variable
     *
     * @var QuotationService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;


    /**
     * SalesOrderService variable
     *
     * @var SalesOrderService
     */
    private $salesOrderService;
    function __construct(QuotationService $service, GeneralNotificationService $generalNotificationService, SalesOrderService $salesOrderService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        $this->salesOrderService = $salesOrderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['quotations'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::quotation.indexView', $data)->render();

            // Set Dompdf options  
            $dompdf = Pdf::loadView('your-view-name', $data); 
            return $dompdf->stream('quotation_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Sales::quotation.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::get();
        $data['customerTypes'] = CustomerType::all();
        return view('Sales::quotation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $quotation_no = $this->getQuotationNumber();

        $validate = $request->validate([
            'customer_name' => 'required|string',
            'area' => 'required|string',
            'address' => 'nullable|string',
            'phone' => ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/'],
            'customer_type' => 'required|integer|exists:customer_types,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'percentage' => 'required|numeric',
            'total' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);


        $quotationDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'nullable|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);

        $quotationTerms = $request->validate([
            'quotation_to' => 'nullable|string',
            'email' => 'nullable|string',
            'attn' => 'nullable|string',
            'attn_cell' => 'nullable|string',
            'payment' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'tax_vat' => 'nullable|string',
            'installation' => 'nullable|string',
            'training' => 'nullable|string',
            'warranty' => 'nullable|string',
            'buyers_responsibility' => 'nullable|string',
            'validity' => 'nullable|string',
            'delivery_info' => 'nullable|string',
        ]);
        $validate['quotation_no'] = $quotation_no;

        $result = $this->service->store($validate, $quotationDetails, $quotationTerms);
        $this->generalNotificationService->store([
            'title' => 'New Quotation Added',
            'description' => 'New Quotation Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(QuotationController::class, 'approve', [$result['quotation']->id]),
        ], $this->generalNotificationService->getPermittedUsers('sales.quotations.approve'));
        return redirect()->route('sales.quotations.index')->with('success', 'Quotation created successfully.');
    }

    public function getQuotationNumber()
    {
        $today = date('Y-m-d');
        $authUser = auth()->user()->id;
        $quotationToday = Quotation::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();
        // Generate Sales Order number with the appropriate format
        $quotationNumber = sprintf(
            'QUO-%s-USR-%06d-%03d',
            date('Ymd'),
            $authUser,
            $quotationToday + 1
        );
        return $quotationNumber;
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['quotation'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return view("Sales::quotation.show", $data);
    }

    /**
     * Show the form for printing the specified resource.
     */
    public function print($id)
    {
        $data['quotation'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return view("Sales::quotation.print", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation)
    {
        $data['quotation'] = $quotation;
        $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::get();
        $data['customerTypes'] = CustomerType::all();

        return view("Sales::quotation.edit", $data);
    }

    public function approval($id)
    {
        $quotation = $this->service->show($id);
        $data['quotation'] = $quotation;
        $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::get();
        $data['customerTypes'] = CustomerType::all();

        return view("Sales::quotation.approval", $data);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
        $validate = $request->validate([
            'customer_name' => 'required|string',
            'area' => 'required|string',
            'address' => 'nullable|string',
            'phone' => ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/'],
            'customer_type' => 'required|integer|exists:customer_types,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'percentage' => 'required|numeric',
            'total' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);


        $quotationDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'required|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);
        $quotationTerms = $request->validate([
            'quotation_to' => 'nullable|string',
            'email' => 'nullable|string',
            'attn' => 'nullable|string',
            'attn_cell' => 'nullable|string',
            'payment' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'tax_vat' => 'nullable|string',
            'installation' => 'nullable|string',
            'training' => 'nullable|string',
            'warranty' => 'nullable|string',
            'buyers_responsibility' => 'nullable|string',
            'validity' => 'nullable|string',
            'delivery_info' => 'nullable|string',
        ]);
        $this->service->update($quotation, $validate, $quotationDetails, $quotationTerms);

        return redirect()->route('sales.quotations.index')->with('success', 'Quotation updated successfully.');
    }
    public function approveStore(Request $request, $id)
    {

        $quotation = Quotation::find($id);

        $validate = $request->validate([
            'customer_name' => 'required|string',
            'area' => 'required|string',
            'address' => 'nullable|string',
            'phone' => ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/'],
            'customer_type' => 'required|integer|exists:customer_types,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'percentage' => 'required|numeric',
            'total' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        $validate['approved_by'] = auth()->user()->id;

        $quotationDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'required|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);
        $quotationTerms = $request->validate([
            'quotation_to' => 'nullable|string',
            'email' => 'nullable|string',
            'attn' => 'nullable|string',
            'attn_cell' => 'nullable|string',
            'payment' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'tax_vat' => 'nullable|string',
            'installation' => 'nullable|string',
            'training' => 'nullable|string',
            'warranty' => 'nullable|string',
            'buyers_responsibility' => 'nullable|string',
            'validity' => 'nullable|string',
            'delivery_info' => 'nullable|string',
        ]);
        $this->service->update($quotation, $validate, $quotationDetails, $quotationTerms);

        return redirect()->route('sales.quotations.index')->with('success', 'Quotation Approved successfully.');
    }

    public function salesOrder(Request $request)
    {
        $quotation = Quotation::find($request->id);

        $customer = Customer::where('company_name', $quotation->customer_name)->where('phone', $quotation->phone)->first();

        if ($customer == null) {
            $customer = Customer::create([
                'company_name' => $quotation->customer_name,
                'phone' => $quotation->phone,
                'customer_type' => $quotation->customer_type,
                'status' => 1
            ]);
            CustomerSetting::create([
                'customer_id' => $customer->id,
                'customer_rating' => 1,
                'customer_status' => 1,
                'credit_limit' => 0,
                'additional_credit_limit' => 0,
                'opening_balance' => 0,
                'is_condition_bill' => 0,
                'minimum_condition_bill' => 1,
                'vat_status' => 0,
                'is_document_return' => 0,
                'service_applicable' => 0,
                'discount_type' => 0,
            ]);
        }
        

        /// create sales orders 
        $data = [
            'customer_id' => $customer->id,
            'invoice_date' => today()->format('Y-m-d'),
            'total_amount' => $quotation->total_amount,
            'discount' => $quotation->discount,
            'commission' => 0,
            'total' => $quotation->total,
            'vat' => 0,
            'net_amount' => $quotation->total,
            'remarks' => $quotation->remarks,
            'status' => 'pending',
            'source_type' => Quotation::class,
            'source_id' => $quotation->id,
        ];

        $salesOrderDetails = [
            'product_ids' => [],
            'quantity' => [],
            'price' => [],
            'unit_discount' => [],
            'total_discount' => [],
            'amount' => [],
        ];

        foreach ($quotation->quotationDetails as $item) {
            $salesOrderDetails['product_ids'][] = $item->product_id;
            $salesOrderDetails['quantity'][] = $item->quantity;
            $salesOrderDetails['price'][] = $item->price;
            $salesOrderDetails['unit_discount'][] = $item->unit_discount;
            $salesOrderDetails['total_discount'][] = $item->total_discount;
            $salesOrderDetails['amount'][] = $item->amount;
        }

        $salesOrderShipments = [];
        $payments = [];

        $result =   $this->salesOrderService->store($data, $salesOrderDetails, $salesOrderShipments, $payments);
        $salesOrder = $result['salesOrder'];

        $quotation->update([
            'status' => 2
        ]);

        return redirect()->route('sales.sales-orders.index')->with('success', 'SalesOrder created successfully for Quotation.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        $this->service->delete($quotation);
        return redirect()->route('sales.quotations.index')->with('success', 'Quotation deleted successfully.');
    }



    // app/Http/Controllers/YourController.php
    public function PDF(Request $request)
    {
        $quotation = $this->service->show($request->quotation_id);
        $company_info = CompanyInfo::first();

        $withoutImage = $request->input('without_image') == '1';

        // Customer info handling
        $content = $request->input('edited_content');

        $pattern = '/(E-mail|Cell|ATTN):\s*(<br\s*\/?>|&nbsp;|\s)*(?=<br\s*\/?>|<\/p>|\r\n|\r|\n|$)/i';
        $cleanContent = preg_replace($pattern, '', $content);
        $cleanContent = preg_replace('/(<br\s*\/?>\s*){2,}/i', '<br>', $cleanContent);

        $editedCustomerInfo = preg_replace('/^<br\s*\/?>/', '', trim($cleanContent));

        if (empty($editedCustomerInfo)) {
            $editedCustomerInfo = $quotation->quotationTerms->quotation_to . "<br>" .
                $quotation->customer_name . "<br>" .
                $quotation->area . ', ' . $quotation->address . "<br>" .
                "E-mail:<br>Cell:<br>ATTN:<br>Cell:";

            if (!empty($quotation->email)) {
                $editedCustomerInfo .= "E-mail: " . $quotation->email . "<br>";
            }

            if (!empty($quotation->attn_person)) {
                $editedCustomerInfo .= "ATTN: " . $quotation->attn_person . "<br>";
            }

            if (!empty($quotation->cell_no)) {
                $editedCustomerInfo .= "Cell: " . $quotation->cell_no . "<br>";
            }
        }

        //  FIXED QR GENERATION (IMPORTANT)
        $quotation->quotationDetails = $quotation->quotationDetails->map(function ($item) {

            $productUrl = ProductCatalog::where('id', $item->product_id)->value('product_catalog_web_link');

            if (!$productUrl) {
                $item->qr = null;
                return $item;
            }
            
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($productUrl)
                ->size(120)
                ->margin(2)
                ->build();

            $item->qr = base64_encode($result->getString());

            return $item;
        });

        //dd($quotation->quotationDetails);

        set_time_limit(300);

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ])->loadView('Sales::quotation.show', [
            'quotation' => $quotation,
            'company_info' => $company_info,
            'withoutImage' => $withoutImage,
            'editedCustomerInfo' => $editedCustomerInfo,
        ]);

        $filename = 'Quotation_' . $quotation->reference_no . '_' .
            ($withoutImage ? 'without_image' : 'with_image') . '_' .
            now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->stream($filename);
    }
}
