<?php

namespace Modules\Purchase\Services;

use Carbon\Carbon;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseOrderDetail;

class PurchaseOrderService
{
    
    public function getAll(int $limit = 20) {
        return PurchaseOrder::query()
        ->searchByFields(['po_number'])
        ->filterByDateRange('po_date')
        ->paginate($limit);
    }
    
    public function store(array $data, array  $purchaseOrderDetails)
    {

        $result['purchaseOrder'] =  PurchaseOrder::create($data);

        $result['purchaseOrderDetails'] = [];

        if (count($purchaseOrderDetails['product_ids']) > 0) {
            foreach ($purchaseOrderDetails['product_ids'] as $key => $value) {
                $result['purchaseOrderDetails'][] = PurchaseOrderDetail::create([

                    'purchase_order_id' => $result['purchaseOrder']->id,
                    'product_id' => $purchaseOrderDetails['product_ids'][$key],
                    'product_model' => $purchaseOrderDetails['product_model'][$key],
                    'product_description'=> $purchaseOrderDetails['product_description'][$key],
                    'hs_code'=> $purchaseOrderDetails['hs_code'][$key],
                    'price'=> $purchaseOrderDetails['price'][$key],
                    'quantity'=> $purchaseOrderDetails['quantity'][$key],
                    'amount'=> $purchaseOrderDetails['amount'][$key],
                ]);
            }
        }
        return $result;
    }

    public function update($purchaseOrder, array $data, array $detailes)
    {
        $purchaseOrder->update($data);

        PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)->delete();

        foreach ($detailes['product_ids'] as $key => $value) {
            $purchaseOrderDetail = PurchaseOrderDetail::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $detailes['product_ids'][$key],
                'product_model' => $detailes['product_model'][$key],
                'product_description'=> $detailes['product_description'][$key],
                'hs_code'=> $detailes['hs_code'][$key],
                'price'=> $detailes['price'][$key],
                'quantity'=> $detailes['quantity'][$key],
                'amount'=> $detailes['amount'][$key],
            ]);
        }
        return $purchaseOrder;
    }

    public function delete(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->detailes()->delete();
        $purchaseOrder->delete();
    }

    public function show($id)
    {
        return PurchaseOrder::findOrFail($id);
    }
}
