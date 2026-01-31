<?php

namespace Modules\Sales\Services;



use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Sales\Models\SalesOrderDelivery;
use Modules\Sales\Models\SalesOrderDeliveryDetail;
use Modules\Sales\Models\SalesOrderDeliveryStock;
use Modules\Inventory\Services\StockService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;

class SalesOrderDeliveryService
{

    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    
    public function getAll(int $limit = 20) {
        return SalesOrderDelivery::query()
        ->when(request()->filled('from'), function ($qr) {
            $qr->whereHas('salesOrder', function ($q) {
                $q->where('invoice_date', '>=', Carbon::parse( request('from'))->format('Y-m-d'));
            });
        })
        ->when(request()->filled('to'), function ($qr) {
            $qr->whereHas('salesOrder', function ($q) {
                $q->where('invoice_date', '<=', Carbon::parse( request('to'))->format('Y-m-d'));
            });
        })
        ->with(['salesOrder'])
        ->paginate($limit);
    }

    public function stockOut(SalesOrderDeliveryStock $salesOrderDeliveryStock){
        if($salesOrderDeliveryStock->lot_no){
            $stock = $this->stockService->store([
                'product_id' => $salesOrderDeliveryStock->product_catalog_id,
                'source_type' => SalesOrderDeliveryStock::class,
                'source_id' => $salesOrderDeliveryStock->id,
                'stock_type' => 'out',
                'out_qty' => $salesOrderDeliveryStock->quantity,
                'lot_no' => $salesOrderDeliveryStock->lot_no
            ]);
            return   $stock;
        }
         if($salesOrderDeliveryStock->serial_no){
            $stock = $this->stockService->store([
                'product_id' => $salesOrderDeliveryStock->product_catalog_id,
                'source_type' => SalesOrderDeliveryStock::class,
                'source_id' => $salesOrderDeliveryStock->id,
                'stock_type' => 'out',
                'out_qty' => 1,
                'serial_no' => $salesOrderDeliveryStock->serial_no
            ]);
            return   $stock;
        }
    }

    function assignDongleToCustomer(SalesOrderDeliveryStock $serialStockSalesOrderDeliveryStock, $customerId){
        $inSource =  $this->stockService->getInSource($serialStockSalesOrderDeliveryStock->product_catalog_id, $serialStockSalesOrderDeliveryStock->serial_no);
        $customer = Customer::find($customerId);
        return DongleOrSerialEntry::create([
            'customer_id'=>$customerId,
            'address'=>$customer->address,
            'product_id'=>$serialStockSalesOrderDeliveryStock->product_catalog_id,
            'product_type'=>$serialStockSalesOrderDeliveryStock->productCatalog->productType->name,
            'dongle_id'=>$inSource->dongle_no,
            'status'=>"active",
        ]);
    }
    
    public function store(array $data, array $sODProductDetails,array $productDetails)
    {
        // dd($data, $sODProductDetails, $productDetails);
        DB::beginTransaction();

    //    $salesOrderDelivery =  $data;
       $salesOrderDelivery =  SalesOrderDelivery::create($data);
       $salesOrder = $salesOrderDelivery->salesOrder;

       
       $result = [];
       $result ['salesOrderDelivery'] = $salesOrderDelivery;
       $remainingQuantities = [];
       foreach ($sODProductDetails['product_id'] as $key => $productCatalogId) {
           $salesOrderDeliveryDetails = SalesOrderDeliveryDetail::create([
               'sales_order_delivery_id' => $salesOrderDelivery->id,
               'product_id' => $productCatalogId, 
               'quantity' => $sODProductDetails['quantity'][$key],
           ]);
           $salesOrderDetail = $salesOrder->salesOrderDetails()->where('product_id', $productCatalogId)->first();
           $remainingQuantities[] = $salesOrderDetail->quantity - $sODProductDetails['quantity'][$key];
        //    SalesOrderDeliveryProductDetails::create();
           $result['salesOrderDeliveryProductDetails'][] = $salesOrderDeliveryDetails;
           if($productDetails['lot_no']??null) {
                foreach($productDetails['lot_no'][$productCatalogId]??[] as $key2 => $lotNo) {
                    $salesOrderDeliveryStock =  SalesOrderDeliveryStock::create([
                        's_o_d_p_details_id' => $salesOrderDeliveryDetails->id,
                        'product_catalog_id' => $productCatalogId,
                        'quantity' => $productDetails['lots_quantity'][$productCatalogId][$key2]??null,
                        'lot_no' => $lotNo,
                    ]);
                    $this->stockOut($salesOrderDeliveryStock);
                    $result['salesOrderDeliveryStock'][$salesOrderDeliveryDetails->id][] = $salesOrderDeliveryStock;
                }
           } 
            if($productDetails['serial_no']??null) {
                foreach($productDetails['serial_no'][$productCatalogId]??[] as $key2 => $serialNo) {
                    $salesOrderDeliveryStock = SalesOrderDeliveryStock::create([
                        's_o_d_p_details_id' => $salesOrderDeliveryDetails->id,
                        'product_catalog_id' => $productCatalogId,
                        'serial_no' => $serialNo,
                    ]);
                    $this->stockOut($salesOrderDeliveryStock);
                    $this->assignDongleToCustomer($salesOrderDeliveryStock,  $salesOrder->customer_id);
                    $result['salesOrderDeliveryStock'][$salesOrderDeliveryDetails->id][] =   $salesOrderDeliveryStock;
                }
           }
       }
       if(array_sum($remainingQuantities) <= 0) {
           $salesOrder->update(['status' => 'delivered']);
       }
        // dd($result);
        DB::commit();

        return $result;
    }

    public function update(SalesOrderDelivery $salesOrderDelivery, array $data)
    {
        $salesOrderDelivery->update($data);
        return $salesOrderDelivery;
    }

    public function delete(SalesOrderDelivery $salesOrderDelivery)
    {
        $salesOrderDelivery->delete();
    }

    public function show($id)
    {
        return SalesOrderDelivery::with(['salesOrder', 'salesOrderDeliveryDetails.salesOrderDeliveryStock'])->findOrFail($id);
    }
}
