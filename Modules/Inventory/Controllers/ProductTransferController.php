<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\AccessControl\Branch;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\ProductTransfer;
use Modules\Inventory\Models\ProductTransferRequest;
use Modules\Inventory\Models\Settings\Unit;
use Modules\Inventory\Services\ProductTransferService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProductTransferController extends Controller
{

    /**
     * Service variable
     *
     * @var ProductTransferService
     */
    private $service;
    function __construct(ProductTransferService $service)
    {
        $this->service = $service;
        $this->middleware('permited')->except('getPTNumber');

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['productTransfers'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::product-transfers.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('product_transfer_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Inventory::product-transfers.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['productTransferRequest'] = ProductTransferRequest::with("productTransferRequestDetails")->findOrFail($request->product_transfer_request_id);
        if (isset($data['productTransferRequest']) == null) {
            return redirect()->back()->with('error', 'Product Transfer Request Not Found');
        }

        return view('Inventory::product-transfers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $invoice_no = $this->getPTNumber();

        $validate = $request->validate([
            //validate rules
            'transfer_date' => 'required|date',
            'source_warehouse_id' => 'required|integer|exists:branches,id',
            'destination_warehouse_id' => 'required|integer|exists:branches,id',
            'transfer_description' => 'required|string|max:255',
            'product_transfer_request_id' => 'nullable|integer|exists:product_transfer_requests,id',
        ]);

        $products_information = $request->validate([
            'product_id.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
        ]);
        $productStockDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
        ]);

        // Validate Serial/Lot Quantities
        foreach ($products_information['product_id'] as $key => $productId) {
            $quantity = $products_information['quantity'][$key];

            // Check Serials
            if (isset($productStockDetails['serial_no'][$productId])) {
                $serialCount = count($productStockDetails['serial_no'][$productId]);
                if ($serialCount != $quantity) {
                    return redirect()->back()->withErrors(['quantity' => "Product ID {$productId}: Selected serials count ({$serialCount}) does not match transfer quantity ({$quantity})."])->withInput();
                }
            }
            // Check Lots
            elseif (isset($productStockDetails['lot_no'][$productId])) {
                $lotQtySum = array_sum($productStockDetails['lots_quantity'][$productId] ?? []);
                if ($lotQtySum != $quantity) {
                    return redirect()->back()->withErrors(['quantity' => "Product ID {$productId}: Sum of lot quantities ({$lotQtySum}) does not match transfer quantity ({$quantity})."])->withInput();
                }
            }
            // If neither (and assumed required), maybe add error? But avoiding if strictly not required by previous logic.
        }

        $validate['invoice_no'] = $invoice_no;
        $result = $this->service->store($validate, $products_information, $productStockDetails);
        return redirect()->route('inv.product-transfers.edit', $result['productTransfers']->id)->with('success', 'ProductTransfer created successfully.');
    }
    public function getPTNumber()
    {
        $count_purchase_number = ProductTransfer::count();
        if ($count_purchase_number == 0) {

            return 'PT-'
                . date('y')
                . '-'
                . str_pad($count_purchase_number + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $last_job_id = ProductTransfer::orderBy('id', 'desc')->pluck('id')->first();

            return 'PT-'
                . date('y')
                . '-'
                . str_pad($last_job_id + 1, 4, "0", STR_PAD_LEFT);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['productTransfer'] = $this->service->show($id);

        return view("Inventory::product-transfers.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTransfer $productTransfer)
    {
        $data['productTransfer'] = $productTransfer;
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        return view("Inventory::product-transfers.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            //validate rules
            'transfer_date' => 'required|date',
            'source_warehouse_id' => 'required|integer|exists:branches,id',
            'destination_warehouse_id' => 'required|integer|exists:branches,id',
            'transfer_description' => 'required|string|max:255',
            'product_transfer_request_id' => 'nullable|integer|exists:product_transfer_requests,id',
        ]);

        $products_information = $request->validate([
            'product_type_id' => 'array',
            'product_type_id.*' => 'required|integer|exists:product_types,id',
            'product_ids' => 'array',
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'sku' => 'nullable|array',
            'sku.*' => 'nullable|string',
            'unit_type_id' => 'array',
            'unit_type_id.*' => 'required|integer|exists:units,id',
            'quantity' => 'array',
            'quantity.*' => 'required|numeric',
            'transfer_notes' => 'array',
            'transfer_notes.*' => 'nullable|string',
        ]);

        // Validate Serial/Lot Quantities (Simpler check for update as structure differs slightly or same?)
        // The update method uses product_details['product_ids'] and quantity.
        // It does NOT seem to accept stock details (serial/lot) in the update method validation?
        // Analyzing update method: it calls $this->service->update.
        // Service update deletes details and recreates them.
        // BUT the Controller update DOES NOT validate or pass `productStockDetails`!
        // This suggests Update might NOT support changing serials/lots? 
        // Or the previous code failed to include it.
        // Given I am fixing "Stock Reduction Error" which likely happens at Creation (Transfer), I will focus on Store.
        // Reviewing Update:
        // $products_information = $request->validate([...])
        // $this->service->update(..., $products_information);
        // Service update creates ProductTransferDetail. 
        // Service update DOES NOT handle ProductTransferStockDetails!
        // This means Editing a Transfer wipes out the Stock Details relations?
        // This seems like a HUGE bug, but user didn't report "Editing destroys serials".
        // Keep to requested scope.

        $productTransfer = ProductTransfer::query()->findOrFail($id);
        $productTransfer = $this->service->update($productTransfer, $validate, $products_information);

        return redirect()->route('inv.product-transfers.edit', $id)->with('success', 'ProductTransfer created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTransfer $productTransfer)
    {
        $this->service->delete($productTransfer);
        return redirect()->route('inv.product-transfers.index')->with('success', 'ProductTransfer deleted successfully.');
    }
}
