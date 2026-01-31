<?php

namespace Modules\Sales\Controllers\Api;

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
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Modules\LocationManager\Models\Area;

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
    function __construct(QuotationService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $quotations = $this->service->getAll();
        $data = [
            'status' => 'success',
            'message' => 'Quotations retrieved successfully.',
            'data' => [
                'quotations' => $quotations,
            ],
        ];

        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] = ProductCatalog::all();
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

        // Validate main quotation data
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'customer_type' => 'required|integer',
            'date' => 'required|date',
            'remarks' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'products_details' => 'required|array|min:1',
            'products_details.*.product_id' => 'required|integer|exists:product_catalogs,id',
            'products_details.*.quantity' => 'required|numeric|min:0',
            'products_details.*.price' => 'required|numeric|min:0',
            'products_details.*.unit_discount' => 'nullable|numeric|min:0',
            'products_details.*.total_discount' => 'nullable|numeric|min:0',
            'products_details.*.amount' => 'required|numeric|min:0',
            'quotationTerms' => 'nullable|array',
        ]);

        // Prepare product arrays for the service
        $salesOrderDetails = collect($validatedData['products_details'])->reduce(
            function ($carry, $product) {
                $carry['product_ids'][] = $product['product_id'];
                $carry['quantity'][] = $product['quantity'];
                $carry['price'][] = $product['price'];
                $carry['unit_discount'][] = $product['unit_discount'] ?? 0;
                $carry['total_discount'][] = $product['total_discount'] ?? 0;
                $carry['amount'][] = $product['amount'];
                return $carry;
            },
            [
                'product_ids' => [],
                'quantity' => [],
                'price' => [],
                'unit_discount' => [],
                'total_discount' => [],
                'amount' => [],
            ],
        );

        // Main quotation data
        $quotationData = [
            'customer_name' => $validatedData['customer_name'],
            'area' => $validatedData['area'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'phone' => $validatedData['phone'] ?? null,
            'customer_type' => $validatedData['customer_type'],
            'date' => $validatedData['date'],
            'remarks' => $validatedData['remarks'] ?? null,
            'total_amount' => $validatedData['total_amount'],
            'discount' => $validatedData['discount'] ?? 0,
            'percentage' => $validatedData['percentage'] ?? 0,
            'total' => $validatedData['total'],
            'net_amount' => $validatedData['net_amount'],
        ];

        // Quotation terms with defaults
        $defaultTerms = [
            'quotation_to' => 'Director',
            'email' => '',
            'attn' => '',
            'attn_cell' => '',
            'payment' => '100% Advance',
            'payment_method' => 'To be paid by Cheque, Cash or Mobile Banking (bKash).',
            'tax_vat' => 'All Prices Excluding TAX & VAT.',
            'installation' => 'Installation by our engineer on prior appointment at your site (OUR COST).',
            'training' => 'Necessary training will be provided FREE OF CHARGE.',
            'warranty' => '01 Year standard warranty. Consumables are not covered.',
            'buyers_responsibility' => 'Use AC dust free room & stabilized power supply.',
            'validity' => '20 Days after submitted quotation.',
            'delivery_info' => 'Products will be delivered from stock or within 60-90 days with advance.',
        ];

        $quotationTerms = [];
        foreach ($defaultTerms as $key => $value) {
            $quotationTerms[$key] = $validatedData['quotationTerms'][$key] ?? $value;
        }
        $quotationData['quotation_no'] = $quotation_no;

        try {
            $result = $this->service->store($quotationData, $salesOrderDetails, $quotationTerms);

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Quotation created successfully.',
                    'data' => $result,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Failed to create quotation: ' . $e->getMessage(),
                    'data' => null,
                ],
                500,
            );
        }
    }

    public function getQuotationNumber()
    {
        $count_purchase_number = Quotation::count();
        if ($count_purchase_number == 0) {
            return 'RN-' . date('y') . '-' . str_pad($count_purchase_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $last_job_id = Quotation::orderBy('id', 'desc')->pluck('id')->first();

            return 'RN-' . date('y') . '-' . str_pad($last_job_id + 1, 4, '0', STR_PAD_LEFT);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $quotation = $this->service->show($id);
        $company_info = CompanyInfo::first();

        return response()->json([
            'status' => 'success',
            'message' => 'Quotation retrieved successfully.',
            'data' => [
                'quotation' => $quotation,
                'company_info' => $company_info,
            ],
        ]);
    }

    /**
     * Show the form for printing the specified resource.
     */
    public function print($id)
    {
        $data['quotation'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return view('Sales::quotation.print', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation)
    {
        $data['quotation'] = $quotation;
        $data['products'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::get();
        $data['customerTypes'] = CustomerType::all();

        return view('Sales::quotation.edit', $data);
    }

    public function approval($id)
    {
        $quotation = $this->service->show($id);
        $data['quotation'] = $quotation;
        $data['products'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::get();
        $data['customerTypes'] = CustomerType::all();

        return view('Sales::quotation.approval', $data);
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
        try {
            $result = $this->service->update($quotation, $validate, $quotationDetails, $quotationTerms);

            return response()->json([
                'status' => 'success',
                'message' => 'Quotation updated successfully.',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
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
                'status' => 1,
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

        $salesOrder = SalesOrder::create([
            'customer_id' => $customer->id,
            'invoice_date' => today()->format('Y-m-d'),
            'total_amount' => $quotation->total_amount,
            'discount' => $quotation->discount,
            'commission' => 0,
            'total' => $quotation->total,
            'vat' => 0,
            'net_amount' => $quotation->total,
            'remarks' => $quotation->remarks,
            'status' => 'approved',
            'source_type' => Quotation::class,
            'source_id' => $quotation->id,
        ]);

        foreach ($quotation->quotationDetails as $item) {
            SalesOrderDetails::create([
                'sales_order_id' => $salesOrder->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'unit_discount' => $item->unit_discount,
                'total_discount' => $item->total_discount,
                'amount' => $item->amount,
                'is_offers_product' => false, // Default value for non-offer products
            ]);
        }

        $quotation->update([
            'status' => 2,
        ]);

        return redirect()->route('sales.sales-orders.index')->with('success', 'SalesOrder created successfully for Quotation.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        try {
            $this->service->delete($quotation);

            return response()->json(['success' => true, 'message' => 'Quotation deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

    public function PDF(Request $request)
    {
        $html = $request->html;

        set_time_limit(1000);
        // $html = view('crm.customer.view', $data)->render();

        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);

        // Initialize Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('quotation' . date('Y-m-d H:i:s') . '.pdf', ['Attachment' => false]);
    }


    /**
     * Get all customer types
     */
    public function getCustomerTypes()
    {
        $customerTypes = CustomerType::all();

        $data = [
            'status' => 'success',
            'message' => 'Customer types retrieved successfully.',
            'data' => [
                'customer_types' => $customerTypes,
            ],
        ];

        return response()->json($data, 200);
    }
}
