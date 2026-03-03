<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\Courier;
use Modules\Sales\Models\SalesOrder;
use App\Services\Notifications\GeneralNotificationService;
use Modules\Sales\Services\SalesOrderService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\LocationManager\Models\Area;
use Modules\Sales\Models\FreeSalesInvoice;
use Illuminate\Support\Facades\Session;
use Modules\Services\Models\Service;

class SalesOrderController extends Controller
{

    /**
     * Service variable
     *
     * @var SalesOrderService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(SalesOrderService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;

        $this->middleware('permited')->except(['getCustomerSetting', 'getSalesDiscount', 'countSalesOrder', 'countTotalSales', 'calculateDiscountForProducts']);
        $this->middleware('permitedSlug:dashboard')->only(['countSalesOrder', 'countTotalSales']);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['salesOrders'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();
        $data['customers'] = Customer::activeCustomers()->get();


        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::sales-order.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('sales_order_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        // dd($data);
        return view("Sales::sales-order.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $data['products'] = cache()->remember('sales_order_products', 3600, function () {
            return ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        });
        $data['customers'] = cache()->remember('sales_order_customers', 3600, function () {
            return Customer::activeCustomers()->select('id', 'company_name', 'company_place_id', 'status')->with('area')->get();
        });
        // dd($data['customers']);
        $data['couriers'] = Courier::get();
        $data['references'] = SalesOrder::select('id', 'customer_id', 'sales_order_id', 'invoice_date')->get();
        $data['areas'] = Area::select('id', 'area')->get();
        $data['banks'] = Bank::get();
        $data['branches'] = BankBranch::get();
        $data['selected_service_id'] = $request->service_id;
        $data['services'] = $request->service_id ? Service::with(['serviceTokens.customer.area'])->get() : [];

        return view('Sales::sales-order.create', $data);
    }

    public function getBranchByBank(Request $request)
    {

        $branches = BankBranch::where('bank_id', $request->id)->get();
        return response()->json($branches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validate = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'service_id' => 'nullable|integer|exists:services,id',
            'additional_phone' => 'nullable|string',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'commission' => 'nullable|numeric',
            'is_offer' => 'nullable|boolean',
            'total' => 'required|numeric',
            'vat' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'required|string',
            'status' => 'nullable|string',
            'paid_status' => 'nullable|string|in:paid,due,unpaid,condition',
            'is_shipment' => 'nullable|boolean',
            'is_courier' => 'nullable|boolean',
            'delivery_date' => 'nullable|date',
            'sales_type' => 'nullable',
            'reference_id' => $request->input('sales_type') == 'free_sales' ? 'required|integer|exists:sales_orders,id' : 'nullable',
        ]);
        $validate['commission'] = $validate['commission'] ?? 0;


        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
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
        // dd($payments);


        $salesOrderDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'nullable|numeric',
            'total_discount.*' => 'nullable|numeric',
            'amount.*' => 'required|numeric',
            'discount_type.*' => 'nullable|string',
        ]);
        $salesOrderShipments = [];
        if ($validate['is_shipment'] ?? false) {
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
            // dd($salesOrderShipments);

        } else {
            $validate['is_shipment'] = 0;
        }


        $result = $this->service->store($validate, $salesOrderDetails, $salesOrderShipments, $payments);
        $this->generalNotificationService->store([
            'title' => 'New Sales Order Added',
            'description' => 'New Sales Order Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(SalesOrderController::class, 'approve', [$result['salesOrder']->id]),
        ], $this->generalNotificationService->getPermittedUsers('sales.sales-orders.approve'));
        return redirect()->route('sales.sales-orders.edit', $result['salesOrder']->id)->with('success', 'SalesOrder created successfully.');
    }




    public function getCustomerSetting(Request $request)
    {

        $data['customers'] = CustomerSetting::where('customer_id', $request->id)
            ->with('customer', 'customer.area', 'customer.customerShippingAddress', 'customerSettingBrokers', 'customerSettingDiscounts', 'customerSettingFixedDiscounts', 'customerSettingSelfCommissions', 'customerSettingSelfCommissions', 'customer.customerType')->first();

        $data['latestShipmentAddress'] = SalesOrder::query()->where('customer_id', $request->id)->with([
            'shipment' => function ($query) {
                $query->latest()->limit(3);
            }
        ])->get()->pluck('shipment');

        return response()->json($data);
    }

    public function getSalesDiscount(Request $request)
    {
        $customerSetting = CustomerSetting::with(["customerSettingBrokers", "customerSettingDiscounts", "customerSettingFixedDiscounts", "customerSettingSelfCommissions"])->where('customer_id', $request->customer_id)->first();
        $productSetting = Product::where('product_catalog_id', $request->product_id)->first();
        $percentage = null;
        $productPrice = null;
        $discountRange = null;
        if ($productSetting && $customerSetting) {
            $productPrice = $customerSetting?->customerSettingFixedDiscounts?->where('product_id', $request->product_id)?->first();
            if ($productPrice) {
                //pass
            } else if ($productSetting->discount_type == "Percentage") {
                if ($customerSetting->discount_type == 1 || $customerSetting->discount_type == 3) {// percentage

                    // dd($customerSetting);
                    $percentage = $customerSetting->customerSettingDiscounts->where("percentage_type", $productSetting->product_tag_id)->first();

                }
            } else if ($productSetting->discount_type == "Fixed") {
                if ($customerSetting->discount_type == 2 || $customerSetting->discount_type == 3) {// fixed
                    $discountRange = ['min' => $productSetting->min_discount, 'max' => $productSetting->max_discount];
                }

            }
        }
        return response()->json(['customerSetting' => $customerSetting, 'productSetting' => $productSetting, 'discount' => ['percentage' => $percentage, 'productPrice' => $productPrice, 'discountRange' => $discountRange]]);
    }

    public function calculateDiscountForProducts(Request $request)
    {
        // dd($request->all());
        $productsWithQty = $request->input('products', []);
        $invoiceDate = $request->input('invoice_date');

        $discountResults = $this->service->getDiscountDetailsForProducts($productsWithQty, $invoiceDate);
        // dd($discountResults);
        // Return the first discount result if available, otherwise return empty response
        if (!empty($discountResults)) {
            return response()->json($discountResults); // Return first matching discount
        } else {
            return response()->json([
                'offer_id' => null,
                'discount_type' => null,
                'amount' => 0
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['salesOrder'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::sales-order.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);


            $dompdf = new Dompdf($options);

            $fontDir = 'assets/fonts/';
            $fontName = 'SolaimanLipi';
            $fontFile = $fontDir . 'SolaimanLipi.ttf';

            // Include font in DOMPDF's font cache
            $dompdf->getOptions()->set('isFontSubsettingEnabled', true);
            $fontMetrics = $dompdf->getFontMetrics();
            $fontMetrics->get_font($fontFile, 'SolaimanLipi');
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfName = 'sales_order_' . $data['salesOrder']->company_name . '.pdf';
            return $dompdf->stream($pdfName, ['Attachment' => false]);
        }

        return view("Sales::sales-order.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrder $salesOrder)
    {

        // dd($salesOrder);
        $data['salesOrder'] = $salesOrder;
        //
        $data['products'] = ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::where('id', $salesOrder->customer->company_place_id)->get();
        $data['services'] = Service::all();
        $data['banks'] = Bank::get();
        $data['references'] = SalesOrder::get();

        return view("Sales::sales-order.edit", $data);
    }

    public function approve($sales_order_id)
    {

        return redirect()->route('sales.sales-orders.edit', [
            'sales_order' => $sales_order_id,
            'approved' => 1,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        // dd($request->all());
        $validate = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'service_id' => 'nullable|integer|exists:services,id',
            'additional_phone' => 'nullable|string',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'commission' => 'nullable|numeric',
            'is_offer' => 'nullable|boolean',
            'total' => 'required|numeric',
            'vat' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'required|string',
            'status' => 'nullable|string',
            'paid_status' => 'nullable|string|in:paid,due,unpaid,condition',
            'is_shipment' => 'nullable|boolean',
            'is_courier' => 'nullable|boolean',
            'delivery_date' => 'nullable|date',
            'sales_type' => 'nullable',
            'reference_id' => 'nullable',
        ]);

        $validate['commission'] = $validate['commission'] ?? 0;



        $salesOrderDetails = $request->validate([
            'sales_order_detail_id.*' => 'nullable',
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'nullable|numeric',
            'total_discount.*' => 'nullable|numeric',
            'amount.*' => 'required|numeric',
            'discount_type.*' => 'nullable|string',
        ]);


        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
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


        $salesOrderShipments = [];
        if ($validate['is_shipment'] ?? false) {
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
        } else {
            $validate['is_shipment'] = 0;
        }


        $salesOrder = SalesOrder::findOrFail($id);
        $this->service->update($salesOrder, $validate, $salesOrderDetails, $salesOrderShipments, $payments);

        return redirect()->route('sales.sales-orders.edit', $id)->with('success', 'SalesOrder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        $this->service->delete($salesOrder);
        return redirect()->route('sales.sales-orders.index')->with('success', 'SalesOrder deleted successfully.');
    }

    public function countSalesOrder()
    {
        return response()->json(["count" => $this->service->countSalesOrder(), "current_month" => $this->service->countSalesOrderCurrentMonth(), "previous_month" => $this->service->countSalesOrderPreviousMonth()]);
    }

    public function countTotalSales()
    {
        return response()->json(["count" => $this->service->countTotalSales(), "current_month" => $this->service->countTotalSalesCurrentMonth(), "previous_month" => $this->service->countTotalSalesPreviousMonth()]);
    }



    /**
     * Show the invoice for free sales invoice
     */
    public function productFreeSalesInvoice($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $data['salesOrder'] = $salesOrder;
        $data['matchedClearances'] = $this->service->getClearageDetailsForProducts($salesOrder);
        $offers = [];
        $products = [];
        foreach ($data['matchedClearances'] as $item) {
            $offers[] = $item->offerDetails->offer;
            foreach ($item->offerDetails->giftOfferProducts->pluck('product') as $key => $productItem) {
                # code...
                $products[] = $productItem;
            }
        }
        // dd($data['matchedClearances'][0]->offerDetails->offer);
        // dd($offers,/ $products);

        $matchedClearances = array_map(function ($item) {
            return $item->toArray();
        }, $data['matchedClearances']);

        // Check if a FreeSalesInvoice already exists for this sales order
        $freeSalesInvoice = $this->service->getFreeSalesInvoiceBySalesOrder($salesOrder->id);

        if ($freeSalesInvoice) {
            $data['freeSalesInvoice'] = $freeSalesInvoice;
            // Populate old data for the form if editing
            $oldProductIds = [];
            $oldQuantities = [];
            $oldPrices = [];
            $oldAmounts = [];
            $oldDetailIds = [];

            foreach ($freeSalesInvoice->details as $detail) {
                $oldProductIds[] = $detail->product_id;
                $oldQuantities[] = round($detail->quantity);
                $oldPrices[] = round($detail->product->mrp);
                $oldAmounts[] = round($detail->product->mrp * $detail->quantity); // Free sales, so amount is 0
                $oldDetailIds[] = $detail->id;
            }

            // Flash input to session to pre-populate the form using old() helper
            Session::flashInput(compact('oldProductIds', 'oldQuantities', 'oldPrices', 'oldAmounts', 'oldDetailIds') + [
                'invoice_date' => $freeSalesInvoice->invoice_date,
                'remarks' => $freeSalesInvoice->remarks,
                'status' => $freeSalesInvoice->status,
                'customer_id' => $freeSalesInvoice->customer_id,
                'product_ids' => $oldProductIds,
                'quantity' => $oldQuantities,
                'price' => $oldPrices,
                'amount' => $oldAmounts,
                'free_sales_invoice_detail_id' => $oldDetailIds,
            ]);
        }

        $data['products'] = $products;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::where('id', $salesOrder->customer->company_place_id)->get();
        $data['services'] = Service::all();
        $data['banks'] = Bank::get();
        $data['references'] = SalesOrder::get();
        return view('Sales::sales-order.product-free-sales-invoice', $data);
    }

    /**
     * Display the specified free sales invoice.
     */
    public function viewProductFreeSalesInvoice($id, Request $request)
    {
        $data['freeSalesInvoice'] = FreeSalesInvoice::with([
            'details.product',
            'customer',
            'createdBy'
        ])->findOrFail($id);

        $data['company_info'] = CompanyInfo::first();

        return view("Sales::sales-order.view-product-free-sales-invoice", $data);
    }

    /**
     * Store or update products for a free sales invoice.
     */
    public function storeProductFreeSalesInvoice(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'remarks' => 'required|string',
            'status' => 'required|string|in:pending,approved',
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'customer_id' => 'required|integer|exists:customers,id',
            'free_sales_invoice_id' => 'nullable|integer|exists:free_sales_invoices,id', // For update
            'free_sales_invoice_detail_id' => 'nullable|array', // For update
            'free_sales_invoice_detail_id.*' => 'nullable|integer|exists:free_sales_invoice_details,id', // For update
        ]);

        try {
            // The $id from the route is the original sales_order_id
            $freeSalesInvoiceId = $request->input('free_sales_invoice_id');
            $freeSalesInvoice = $this->service->saveFreeSalesInvoice($id, $validated, $freeSalesInvoiceId);
            // Redirect to the original sales order's edit page
            return redirect()->route('sales.sales-orders.product-free-sales-invoice', $id)->with('success', 'Free sales invoice saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save free sales invoice: ' . $e->getMessage())->withInput();
        }
    }
}
