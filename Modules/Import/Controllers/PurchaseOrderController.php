<?php

namespace Modules\Import\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Unit;
use Modules\Inventory\Models\warehouse; 
use Modules\Purchase\Services\PurchaseOrderService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\Import\Models\PurchaseOrder;
use Modules\Purchase\Models\Supplier;

class PurchaseOrderController extends Controller
{

    /**
     * Service variable
     *
     * @var PurchaseOrderService
     */
    private $service;
    function __construct(PurchaseOrderService $service)
    {
        $this->service = $service;
        $this->middleware('permited')->only('create', 'store', 'edit', 'update', 'destroy');

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['purchaseOrders'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::order.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('stock_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Import::purchase-orders.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = ProductCatalog::where('status', 'active')->get();
        $data['brands'] = Brand::all();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        $data['customers'] = Customer::activeCustomers()->get();
        return view('Purchase::order.create', $data);
    }

    public function getSupplierData(Request $request)
    {

        $data = Supplier::query()->where('id', $request->id)->with('brands.productCatalog.product')->get();
        return response()->json($data);
    }

    public function getProductData(Request $request)
    {
        $data = ProductCatalog::query()->where('id', $request->id)->with('product')->get();
        return response()->json($data);
    }

    public function getBrandData(Request $request)
    {
        $data = ProductCatalog::query()->where('product_brand_id', $request->id)->with('product')->get();
        return response()->json($data);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $po_number = $this->getPONumber($request->supplier_id);

        $validate = $request->validate([
            'supplier_id' => 'nullable',
            'po_date' => 'nullable|date',
            'search_by_brand_id' => 'nullable',
            'total_amount' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'transport_title' => 'nullable|string',
            'shipping_method' => 'nullable|string',
            'shipping_terms' => 'nullable|string',
            'delivery_date' => 'nullable|date',
        ]);
        $productValidate = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'hs_code' => 'nullable|array',
            'hs_code.*' => 'nullable|string',
            'product_description' => 'nullable|array',
            'product_description.*' => 'nullable|string',
            'product_model' => 'nullable|array',
            'product_model.*' => 'nullable|string',
            'price' => 'nullable|array',
            'price.*' => 'nullable',
            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable',
            'amount' => 'nullable|array',
            'amount.*' => 'nullable|numeric|min:0',
        ]);
        $validate['po_number'] = $po_number;

        if (isset($request->product_ids[0])) {
            $request->validate([
                'product_ids.*' => 'required',
                'hs_code.*' => 'required',
            ]);
        }

        $result = $this->service->store($validate, $productValidate);
        return redirect()->route('purchase.orders.index', $result['purchaseOrder']->id)->with('success', 'PurchaseOrder created successfully.');

    }

   public function getPONumber($supplier_id)
    {
        $today = date('Y-m-d');
        $authUser = auth()->user()->id;

        // Count today's purchase orders created by this user
        $todayOrders = PurchaseOrder::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        // Generate PO number in required format
        $poNumber = sprintf(
            'PO-SUP-%06d-%s-%04d',
            $supplier_id,              
            date('Y'),            
            $todayOrders + 1       
        );

        return $poNumber;
    }



    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['purchaseOrder'] = $this->service->show($id);
        // $data['company_info'] = CompanyInfo::first();
        $purchaseOrder = $this->service->show($id);


        $data = [
            'purchaseOrder' => $purchaseOrder,
        ];
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::order.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('purchaseOrder_' . $data['purchaseOrder']->company_name . '.pdf', ['Attachment' => false]);
        }

        return view("Purchase::order.show", $data);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['purchaseOrder'] = PurchaseOrder::find($id);
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = ProductCatalog::where('status', 'active')->get();
        $data['brands'] = Brand::all();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        $data['customers'] = Customer::activeCustomers()->get();
        return view("Purchase::order.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);

        $validate = $request->validate([
            'supplier_id' => 'nullable',
            'po_date' => 'nullable|date',
            'search_by_brand_id' => 'nullable',
            'total_amount' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'transport_title' => 'nullable|string',
            'shipping_method' => 'nullable|string',
            'shipping_terms' => 'nullable|string',
            'delivery_date' => 'nullable|date',
        ]);
        $productValidate = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'hs_code' => 'nullable|array',
            'hs_code.*' => 'nullable|string',
            'product_description' => 'nullable|array',
            'product_description.*' => 'nullable|string',
            'product_model' => 'nullable|array',
            'product_model.*' => 'nullable|string',
            'price' => 'nullable|array',
            'price.*' => 'nullable',
            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable',
            'amount' => 'nullable|array',
            'amount.*' => 'nullable|numeric|min:0',
        ]);

        if (isset($request->product_ids[0])) {
            $request->validate([
                'product_ids.*' => 'required',
                'hs_code.*' => 'required',
            ]);
        }
        $this->service->update($purchaseOrder, $validate, $productValidate);

        return redirect()->route('purchase.orders.edit', $id)->with('success', 'PurchaseOrder updated successfully.')->with('success', 'PurchaseOrder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        $this->service->delete($purchaseOrder);
        return redirect()->route('purchase.orders.index')->with('success', 'PurchaseOrder deleted successfully.');
    }
}
