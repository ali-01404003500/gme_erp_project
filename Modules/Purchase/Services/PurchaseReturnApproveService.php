<?php

namespace Modules\Purchase\Services;


use Modules\Sales\Models\Delivery;
use Modules\Inventory\Services\StockService;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Models\PurchaseReturnApprove;
use Modules\Purchase\Models\PurchaseReturnApproveDetail;
use Modules\Purchase\Models\PurchaseReturnApproveStock;

class PurchaseReturnApproveService
{
    
    private $stockService;

    public function __construct(StockService $stockService){
        $this->stockService = $stockService;
    }

    public function getAll(int $limit = 20) {
        if(!request()->has('status')){
            request()->merge(['status' => 'pending']);
        }
        return Delivery::query()
        ->searchByFields(['status'])
        ->filterByDateRange('delivery_date')
        ->paginate($limit);
    }
    
  

    public function stockOut(PurchaseReturnApproveStock $purchaseReturnApproveStock){
        if($purchaseReturnApproveStock->lot_no){
            $stock = $this->stockService->store([
                'product_id' => $purchaseReturnApproveStock->product_id,
                'source_type' => PurchaseReturnApproveStock::class,
                'source_id' => $purchaseReturnApproveStock->id,
                'stock_type' => 'out',
                'out_qty' => $purchaseReturnApproveStock->quantity,
                'lot_no' => $purchaseReturnApproveStock->lot_no,
            ]);
            return   $stock;
        }
         if($purchaseReturnApproveStock->serial_no){
            $stock = $this->stockService->store([
                'product_id' => $purchaseReturnApproveStock->product_id,
                'source_type' => PurchaseReturnApproveStock::class,
                'source_id' => $purchaseReturnApproveStock->id,
                'stock_type' => 'out',
                'out_qty' => 1,
                'serial_no' => $purchaseReturnApproveStock->serial_no
            ]);
            return   $stock;
        }
    }
    
    public function store(array $data, array $returnDetails, array $returnStockDetails)
    {
        $return = PurchaseReturnApprove::create($data);
        $result['return'] = $return;
        DB::beginTransaction();
        foreach ($returnDetails['product_ids'] as $key => $product_id) {
            $returnDetail = PurchaseReturnApproveDetail::create([
                'p_r_approve_id' => $return->id,
                'product_id' => $product_id, 
                'quantity' => $returnDetails['quantity'][$key],
                'price' => $returnDetails['price'][$key],
                'amount' => $returnDetails['amount'][$key],	
            ]);
        
            $result['returnDetails'][] = $returnDetail;
            if($returnStockDetails['lot_no']??null) {
                 foreach($returnStockDetails['lot_no'][$product_id]??[] as $key2 => $lotNo) {
                     $returnStock =  PurchaseReturnApproveStock::create([
                         'p_r_approve_detail_id' => $returnDetail->id,
                         'product_id' => $product_id,
                         'quantity' => $returnStockDetails['lots_quantity'][$product_id][$key2]??null,
                         'lot_no' => $lotNo,
                     ]);
                     $this->stockOut($returnStock);
                     $result['returnStock'][$returnDetail->id][] = $returnStock;
                 }
            } 
             if($returnStockDetails['serial_no']??null) {
                 foreach($returnStockDetails['serial_no'][$product_id]??[] as $key2 => $serialNo) {
                     $returnStock = PurchaseReturnApproveStock::create([
                         'p_r_approve_detail_id' => $returnDetail->id,
                         'product_id' => $product_id,
                         'serial_no' => $serialNo,
                     ]);
                     $this->stockOut($returnStock);
                   
                     $result['returnStock'][$returnDetail->id][] =   $returnStock;
                }
            }
        }
       
        $return->paurchaseReturn()->update([
            'status' => "Returned",
        ]);
        // $this->makeDummyTransaction($return);

        // $this->storeShipping();
        // dd($result);
        DB::commit();

        return $result;
    }

    public function delete(Delivery $delivery)
    {
        $delivery->delete();
    }

    public function show($id)
    {
        return  PurchaseReturnApprove::where('purchase_return_id', $id)->first();
    }
}
