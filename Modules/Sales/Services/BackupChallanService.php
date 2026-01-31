<?php

namespace Modules\Sales\Services;


use Modules\Sales\Models\BackupChallan;
use Modules\Sales\Services\DeliveryService;
use Carbon\Carbon;

class BackupChallanService
{
    private $salesOrderService;
    private $deliveryService;

    public function __construct(SalesOrderService $salesOrderService, DeliveryService $deliveryService)
    {
        $this->salesOrderService = $salesOrderService;
        $this->deliveryService = $deliveryService;
    }
    
    public function getAll(int $limit = 20) {
        return BackupChallan::query()
        ->searchByFields(['customer_id','status'])
        ->when(request()->filled('from'), function ($qr) {
            $qr->where('invoice_date', '>=', Carbon::parse( request('from'))->format('Y-m-d'));
        })
        ->when(request()->filled('to'), function ($qr) {
            $qr->where('invoice_date', '<=', Carbon::parse( request('to'))->format('Y-m-d'));
        })
        ->orderByRaw("FIELD(status, 'pending') DESC")        
        ->paginate($limit);
    }
    
    public function store(array $data, array $backupChallanDetails, array $backupChallanShipments)
    {
        $result['backupChallan'] = BackupChallan::create($data);

        $result['backupChallanDetails'] = [];
        foreach($backupChallanDetails['product_ids'] as $key => $productId) {
            $result['backupChallanDetails'][] = $result['backupChallan']->backupChallanDetails()->create([
                'product_id' => $productId,
                'quantity'=> $backupChallanDetails['quantity'][$key],
                'price'=> $backupChallanDetails['price'][$key],
                'amount'=> $backupChallanDetails['amount'][$key],
            ]);
        }
        if(isset($data['is_shipment']) && $data['is_shipment'] == 1) {
            $result['backupChallanShipments'] = $result['backupChallan']->backupChallanShipments()->create($backupChallanShipments);
        }

        return $result;
    }

    public function update(BackupChallan $backupChallan, array $data, array $backupChallanDetails, array $backupChallanShipments)
    {
        $backupChallan->update($data);

        $backupChallan->backupChallanDetails()->delete();
        foreach($backupChallanDetails['product_ids'] as $key => $productId) {
            $backupChallan->backupChallanDetails()->create([
                'product_id' => $productId,
                'quantity'=> $backupChallanDetails['quantity'][$key],
                'price'=> $backupChallanDetails['price'][$key],
                'amount'=> $backupChallanDetails['amount'][$key],
            ]);
        }

        if (isset($data['is_shipment']) && $data['is_shipment'] == 1) {
            $backupChallan->backupChallanShipments()->delete();
            $backupChallan->backupChallanShipments()->create($backupChallanShipments);
        } else {
            $backupChallan->backupChallanShipments()->delete();
        }

        return $backupChallan;
    }

    public function delete(BackupChallan $backupChallan)
    {
        $backupChallan->backupChallanDetails()->delete();
        $backupChallan->backupChallanShipments()->delete();
        $backupChallan->delete();
    }

    public function show($id)
    {
        return BackupChallan::findOrFail($id);
    }

    public function saveToSalesOrder(BackupChallan $backupChallan)
    {
        $salesOrder = $this->salesOrderService->saveFromChallan($backupChallan);
        // Use the injected SalesOrderService to convert the challan to order
        $backupChallan->update(['status' => 'Sales']);
        return $salesOrder;
    }

    public function sendToDelivery(BackupChallan $backupChallan)
    {
        $delivery = $this->deliveryService->createFromBackupChallan($backupChallan);
        $backupChallan->update(['status' => 'processing_delivery']);
        return $delivery;
    }
}
