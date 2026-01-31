<?php

namespace Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Purchase\Models\PurchaseReturn;
use Modules\Purchase\Models\PurchaseReturnApproveDetail;
use Modules\Purchase\Models\RequisitionDetail;
use Modules\Inventory\Services\StockService;
use Modules\Purchase\Services\PurchaseReturnApproveService;
use Modules\Purchase\Services\PurchaseReturnService;
use Illuminate\Http\Request;
use Modules\Purchase\Models\PurchaseReturnDetail;

class PurchaseReturnApproveController extends Controller
{
    private $service; 
    private $stockService;
    private $purchaseReturnService;

    function __construct(
        PurchaseReturnApproveService $service, 
        StockService $stockService,
        PurchaseReturnService $purchaseReturnService
    ) {
        $this->service = $service;
        $this->stockService = $stockService;
        $this->purchaseReturnService = $purchaseReturnService;
    }

    public function create($id)
    {
        $data['purchaseReturn'] = PurchaseReturn::with('paymentDetails')->find($id);
        return view('Purchase::returns.approve', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([ 
            'purchase_return_id' => 'required|exists:purchase_returns,id',
        ]);

        $validateDetails = $request->validate([
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
            'return_qty.*' => 'nullable|numeric',
        ]);

        foreach($validateDetails['return_qty'] as $key => $salesQuantity) {
            if($validateDetails['return_qty'][$key] != $validateDetails['quantity'][$key]) {
                return redirect()->back()->withErrors([
                    'quantity.'.$key => 'The sales quantity and quantity of product '.$key.' should be same.'
                ]);
            }
        }
        
        $deliveryStockDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
        ]);

        // Store the approval details
        $result = $this->service->store($validate, $validateDetails, $deliveryStockDetails);

        // Create transactions for the approved return
        $purchaseReturn = PurchaseReturn::find($validate['purchase_return_id']);
        $this->purchaseReturnService->makeDummyTransaction($purchaseReturn);

        return redirect()->route('purchase.returns.index')
            ->with('success', 'Purchase Return approved and transactions created successfully.');
    }

    public function selectStock($product_id, $requisition_id, Request $request)
    {
        $data["product"] = ProductCatalog::find($product_id);
        $data['requisition'] = RequisitionDetail::where('requisition_id', $requisition_id)
            ->with(['requisition.supplier','requisition.receiveSerials','requisition.receiveBatches'])
            ->get();

        if($data["product"]->is_serial_product) {
            $serials = $data['requisition']->pluck("requisition.receiveSerials")
                ->flatten()->unique('id')->pluck('serial_no');
        } else {
            $lots = $data['requisition']->pluck("requisition.receiveBatches")
                ->flatten()->unique('id')->pluck('lot_no');
        }

        $purchaseReturn = PurchaseReturn::where('requisition_id', $requisition_id)->first();
        $data['total_stock'] = PurchaseReturnDetail::where('purchase_return_id', $purchaseReturn->id)
            ->where('product_id', $product_id)->sum('quantity');

        if($data['product']) {
            $data['stocks'] = $data["product"]->is_serial_product 
                ? $this->stockService->availableSerialsProductStocksWithSerials($product_id, $serials)
                : $this->stockService->availableLotsProductStocksWithLots($product_id, $lots);
        }

        return view('Purchase::returns.select-stock', $data);
    }

    public function show($id)
    {
        $data['purchaseReturn'] = $this->service->show($id);
        return view('Purchase::returns.approve-show', $data);
    }

    public function details(Request $request)
    {
        $data['returnApproveDetail'] = PurchaseReturnApproveDetail::with('purchaseReturnApproveStocks')
            ->find($request->p_r_approve_detail_id);
        return view("Purchase::returns.details", $data);
    }
}