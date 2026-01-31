<?php

namespace Modules\Sales\Services;


use Modules\Sales\Models\BackupChallanDeliveryStock;
use Modules\Inventory\Services\StockService;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Models\BackupChallanDelivery;
use Modules\Sales\Models\BackupChallanDeliveryDetail;

class BackupChallanDeliveryService
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    
    public function getAll(int $limit = 20) {
        return BackupChallanDelivery::query()->paginate($limit);
    }
    

    public function store(array $data, array $sODProductDetails,array $productDetails)
    {
        // dd($data, $sODProductDetails, $productDetails);
        DB::beginTransaction();
    //    $salesOrderDelivery =  $data;
       $backupChallanDelivery =  BackupChallanDelivery::create($data);
       $backupChallan = $backupChallanDelivery->backupChallan;

       
       $result = [];
       $result ['backupChallanDelivery'] = $backupChallanDelivery;
       $remainingQuantities = [];
       foreach ($sODProductDetails['product_id'] as $key => $productCatalogId) {
           $backupChallanDeliveryDetails = BackupChallanDeliveryDetail::create([
               'backup_challan_delivery_id' => $backupChallanDelivery->id,
               'product_id' => $productCatalogId, 
               'quantity' => $sODProductDetails['quantity'][$key],
           ]);
           $backupChallanDetail = $backupChallan->backupChallanDetails()->where('product_id', $productCatalogId)->first();
           $remainingQuantities[] = $backupChallanDetail->quantity - $sODProductDetails['quantity'][$key];
        //    backupChallanDeliveryProductDetails::create();
           $result['backupChallanDeliveryProductDetails'][] = $backupChallanDeliveryDetails;
           if($productDetails['lot_no']??null) {
                foreach($productDetails['lot_no'][$productCatalogId]??[] as $key2 => $lotNo) {
                    $backupChallanDeliveryStock =  BackupChallanDeliveryStock::create([
                        'b_c_d_p_details_id' => $backupChallanDeliveryDetails->id,
                        'product_catalog_id' => $productCatalogId,
                        'quantity' => $productDetails['lots_quantity'][$productCatalogId][$key2]??null,
                        'lot_no' => $lotNo,
                        'backup_challan_type' => $data['backup_challan_type'],
                    ]);
                    $this->stockOut($backupChallanDeliveryStock);
                    $result['backupChallanDeliveryStock'][$backupChallanDeliveryDetails->id][] = $backupChallanDeliveryStock;
                }
           } 
            if($productDetails['serial_no']??null) {
                foreach($productDetails['serial_no'][$productCatalogId]??[] as $key2 => $serialNo) {
                    $backupChallanDeliveryStock = BackupChallanDeliveryStock::create([
                        'b_c_d_p_details_id' => $backupChallanDeliveryDetails->id,
                        'product_catalog_id' => $productCatalogId,
                        'serial_no' => $serialNo,
                        'backup_challan_type' => $data['backup_challan_type'],

                    ]);
                    $this->stockOut($backupChallanDeliveryStock);
                    $result['backupChallanDeliveryStock'][$backupChallanDeliveryDetails->id][] =   $backupChallanDeliveryStock;
                }
           }
       }
       if(array_sum($remainingQuantities) <= 0) {
           $backupChallan->update(['status' => 'delivered']);
       }
        // dd($result);
        DB::commit();

        return $result;
    }

    public function stockOut(BackupChallanDeliveryStock $backupChallanDeliveryStock){
        if($backupChallanDeliveryStock->lot_no){
            $stock = $this->stockService->store([
                'product_id' => $backupChallanDeliveryStock->product_catalog_id,
                'source_type' => BackupChallanDeliveryStock::class,
                'source_id' => $backupChallanDeliveryStock->id,
                'stock_type' => 'out',
                'out_qty' => $backupChallanDeliveryStock->quantity,
                'lot_no' => $backupChallanDeliveryStock->lot_no
            ]);
            return   $stock;
        }
         if($backupChallanDeliveryStock->serial_no){
            $stock = $this->stockService->store([
                'product_id' => $backupChallanDeliveryStock->product_catalog_id,
                        'source_type' => BackupChallanDeliveryStock::class,
                        'source_id' => $backupChallanDeliveryStock->id,
                        'stock_type' => 'out',
                        'out_qty' => 1,
                        'serial_no' => $backupChallanDeliveryStock->serial_no
            ]);
            return   $stock;
        }
    }
    public function update(BackupChallanDelivery $backupChallanDelivery, array $data)
    {
        $backupChallanDelivery->update($data);
        return $backupChallanDelivery;
    }

    public function delete(BackupChallanDelivery $backupChallanDelivery)
    {
        $backupChallanDelivery->delete();
    }

    public function show($id)
    {
        return BackupChallanDelivery::findOrFail($id);
    }
}
