<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\AccessControl\Branch;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\ProductTransfer;
use Modules\Inventory\Models\ProductTransferReceive;
use Modules\Inventory\Services\ProductTransferReceiveService;
use Modules\Inventory\Services\StockService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProductTransferReceiveController extends Controller
{
    /**
     * Service variable
     *
     * @var ProductTransferReceiveService
     */
    private $service;

    /**
     * StockService variable
     *
     * @var StockService
     */
    private $stockService;

    function __construct(ProductTransferReceiveService $service, StockService $stockService)
    {
        $this->service = $service;
        $this->stockService = $stockService;
        $this->middleware('permited');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['productTransferReceives'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::product-transfer-receives.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('product_transfer_receive_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Inventory::product-transfer-receives.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['warehouses'] = Branch::query()->get();
        $data['products'] = ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        
        // Get the product transfer if provided
        if ($request->product_transfer_id) {
            $data['productTransfer'] = ProductTransfer::with("productTransferDetails.productTransferStockDetails")->findOrFail($request->product_transfer_id);
            
            if (!isset($data['productTransfer'])) {
                return redirect()->back()->with('error', 'Product Transfer Not Found');
            }
        } else {
            $data['productTransfer'] = null;
        }

        return view('Inventory::product-transfer-receives.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $invoice_no = $this->getPRNumber();

        $validate = $request->validate([
            'receive_date' => 'required|date',
            'product_transfer_id' => 'required|integer|exists:product_transfers,id',
            'source_warehouse_id' => 'required|integer|exists:branches,id',
            'destination_warehouse_id' => 'required|integer|exists:branches,id',
            'receive_description' => 'required|string|max:255',
        ]);

        $products_information = $request->validate([
            'product_id.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'received_quantity.*' => 'required|numeric',
        ]);

        $productStockDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
        ]);

        // Validate Serial/Lot Quantities
        foreach ($products_information['product_id'] as $key => $productId) {
            $receivedQty = $products_information['received_quantity'][$key];

            // Check Serials
            if (isset($productStockDetails['serial_no'][$productId])) {
                $serialCount = count($productStockDetails['serial_no'][$productId]);
                if ($serialCount != $receivedQty) {
                    return redirect()->back()->withErrors(['quantity' => "Product ID {$productId}: Selected serials count ({$serialCount}) does not match received quantity ({$receivedQty})."])->withInput();
                }
            }
            // Check Lots
            elseif (isset($productStockDetails['lot_no'][$productId])) {
                $lotQtySum = array_sum($productStockDetails['lots_quantity'][$productId] ?? []);
                if ($lotQtySum != $receivedQty) {
                    return redirect()->back()->withErrors(['quantity' => "Product ID {$productId}: Sum of lot quantities ({$lotQtySum}) does not match received quantity ({$receivedQty})."])->withInput();
                }
            }
        }

        $validate['invoice_no'] = $invoice_no;
        $result = $this->service->store($validate, $products_information, $productStockDetails);
        
        return redirect()->route('inv.product-transfer-receives.edit', $result['productTransferReceives']->id)->with('success', 'Product Transfer Receive created successfully.');
    }

    public function getPRNumber()
    {
        $count_receive_number = ProductTransferReceive::count();
        if ($count_receive_number == 0) {
            return 'PR-'
                . date('y')
                . '-'
                . str_pad($count_receive_number + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $last_job_id = ProductTransferReceive::orderBy('id', 'desc')->pluck('id')->first();

            return 'PR-'
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
        $data['productTransferReceive'] = $this->service->show($id);

        return view("Inventory::product-transfer-receives.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTransferReceive $productTransferReceive)
    {
        $data['productTransferReceive'] = $productTransferReceive;
        $data['warehouses'] = Branch::query()->get();
        $data['products'] = ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        
        return view("Inventory::product-transfer-receives.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'receive_date' => 'required|date',
            'product_transfer_id' => 'required|integer|exists:product_transfers,id',
            'source_warehouse_id' => 'required|integer|exists:branches,id',
            'destination_warehouse_id' => 'required|integer|exists:branches,id',
            'receive_description' => 'required|string|max:255',
            'status' => 'nullable|string',
        ]);

        $products_information = $request->validate([
            'product_id.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'received_quantity.*' => 'required|numeric',
        ]);

        $productStockDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
        ]);

        $productTransferReceive = ProductTransferReceive::query()->findOrFail($id);
        
        // If status is being set to approved, process stock in
        if (isset($validate['status']) && $validate['status'] == 'approved' && $productTransferReceive->status != 'approved') {
            // Delete existing stock entries and recreate them
            $this->service->delete($productTransferReceive);
            // Recreate with stock in
            $this->service->store($validate, $products_information, $productStockDetails);
            return redirect()->route('inv.product-transfer-receives.index')->with('success', 'Product Transfer Receive approved and stock updated successfully.');
        }

        $productTransferReceive = $this->service->update($productTransferReceive, $validate, $products_information, $productStockDetails);

        return redirect()->route('inv.product-transfer-receives.edit', $id)->with('success', 'Product Transfer Receive updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTransferReceive $productTransferReceive)
    {
        $this->service->delete($productTransferReceive);
        return redirect()->route('inv.product-transfer-receives.index')->with('success', 'Product Transfer Receive deleted successfully.');
    }

    /**
     * Approve the transfer receive
     */
    public function approve($id)
    {
        $productTransferReceive = $this->service->show($id);
        
        // Get the details with stock information
        $products_information = [
            'product_id' => [],
            'quantity' => [],
            'received_quantity' => [],
        ];
        
        $productStockDetails = [
            'lot_no' => [],
            'lots_quantity' => [],
            'serial_no' => [],
        ];

        foreach ($productTransferReceive->productTransferReceiveDetails as $detail) {
            $products_information['product_id'][] = $detail->product_id;
            $products_information['quantity'][] = $detail->quantity;
            $products_information['received_quantity'][] = $detail->received_quantity;

            foreach ($detail->productTransferReceiveStockDetails as $stockDetail) {
                if ($stockDetail->lot_no) {
                    $productStockDetails['lot_no'][$detail->product_id][] = $stockDetail->lot_no;
                    $productStockDetails['lots_quantity'][$detail->product_id][] = $stockDetail->quantity;
                }
                if ($stockDetail->serial_no) {
                    $productStockDetails['serial_no'][$detail->product_id][] = $stockDetail->serial_no;
                }
            }
        }

        // Delete existing and recreate with stock in
        $this->service->delete($productTransferReceive);
        $result = $this->service->store($productTransferReceive->toArray(), $products_information, $productStockDetails);

        return redirect()->route('inv.product-transfer-receives.index')->with('success', 'Product Transfer Receive approved successfully.');
    }
}
