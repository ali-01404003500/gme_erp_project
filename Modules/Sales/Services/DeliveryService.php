<?php

namespace Modules\Sales\Services;


use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Sales\Models\Delivery;
use Modules\Sales\Models\DeliveryDetail;
use Modules\Sales\Models\DeliveryStock;
use Modules\Inventory\Services\StockService;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\CRM\Models\Customer\Customer;
use Modules\Sales\Models\BackupChallan;
use Modules\Purchase\Models\RequisitionReceiveBatch;
use Modules\Purchase\Models\RequisitionReceiveSerial;

class DeliveryService
{

    private $stockService;

    private $shipmentVerifyService;

    public function __construct(StockService $stockService, ShipmentVerifyService $shipmentVerifyService)
    {
        $this->stockService = $stockService;
        $this->shipmentVerifyService = $shipmentVerifyService;
    }

    public function getAll(int $limit = 100)
    {
        if (!request()->has('status')) {
            request()->merge(['status' => 'pending']);
        }
        return Delivery::query()
            ->whereHas('source')
            ->searchByFields(['status'])
            ->filterByDateRange('delivery_date')
            ->paginate($limit);
    }

    public function store(array $data)
    {
        throw new \Exception('Not Implemented', 501);
    }

    /**
     * Create a new Delivery from a BackupChallan.
     *
     * @param BackupChallan $backupChallan
     * @return Delivery
     */
    public function createFromBackupChallan(BackupChallan $backupChallan): Delivery
    {
        return DB::transaction(function () use ($backupChallan) {
            $delivery = Delivery::create([
                'source_id' => $backupChallan->id,
                'source_type' => get_class($backupChallan),
                'delivery_date' => now()->format('Y-m-d'),
            ]);

            foreach ($backupChallan->backupChallanDetails as $challanDetail) {
                DeliveryDetail::create([
                    'delivery_id' => $delivery->id,
                    'product_id' => $challanDetail->product_id,
                    'quantity' => $challanDetail->quantity,
                ]);
            }

            return $delivery;
        });
    }

    public function stockOut(DeliveryStock $salesOrderDeliveryStock)
    {
        if ($salesOrderDeliveryStock->lot_no) {
            $stock = $this->stockService->store([
                'product_id' => $salesOrderDeliveryStock->product_catalog_id,
                'source_type' => DeliveryStock::class,
                'source_id' => $salesOrderDeliveryStock->id,
                'stock_type' => 'out',
                'out_qty' => $salesOrderDeliveryStock->quantity,
                'lot_no' => $salesOrderDeliveryStock->lot_no,
                'date' =>  DeliveryStock::find($salesOrderDeliveryStock->id)?->deliveryDetail?->delivery?->delivery_date
            ]);
            return $stock;
        }
        if ($salesOrderDeliveryStock->serial_no) {
            $stock = $this->stockService->store([
                'product_id' => $salesOrderDeliveryStock->product_catalog_id,
                'source_type' => DeliveryStock::class,
                'source_id' => $salesOrderDeliveryStock->id,
                'stock_type' => 'out',
                'out_qty' => 1,
                'serial_no' => $salesOrderDeliveryStock->serial_no,
                'date' =>  DeliveryStock::find($salesOrderDeliveryStock->id)?->deliveryDetail?->delivery?->delivery_date
            ]);
            return $stock;
        }
    }

    function storeShipping(Delivery $delivery)
    {
        // dd($delivery->source);
        /***
         * "address" => "Ishwardi/Bazar"
         *  "contact_person_name" => "Lyons Mcguire Inc"
         *  "contact_person_number" => "01545978555"
         */
        // impliments store shiping 
        return $this->shipmentVerifyService->initStore([
            'customer_id' => $delivery->source->customer_id,
            'customer_address' => $delivery->source->shipment->address,
            'challan_no' => $delivery->source->challan_no,
            'courier_id' => $delivery->source->shipment->courier_id,
            'courier_date' => $delivery->delivery_date,
            'cartoon_no' => $delivery->carton_no,
            'source_id' => $delivery->id,
            'source_type' => Delivery::class
        ]);
    }

    /**
     * Assign a dongle to customer.
     *
     * @param DeliveryStock $serialStockSalesOrderDeliveryStock
     * @param int $customerId
     * @return DongleOrSerialEntry
     */

    function assignDongleToCustomer(DeliveryStock $serialStockSalesOrderDeliveryStock, $customerId)
    {
        $inSource =  $this->stockService->getInSource($serialStockSalesOrderDeliveryStock->product_catalog_id, $serialStockSalesOrderDeliveryStock->serial_no);
        $customer = Customer::find($customerId);
        if (!($inSource->serial_no ?? null))
            return;
        return DongleOrSerialEntry::create([
            'customer_id' => $customerId,
            'address' => $customer->address ?? 'N/A',
            'product_id' => $serialStockSalesOrderDeliveryStock->product_catalog_id,
            'product_type' => $serialStockSalesOrderDeliveryStock->productCatalog->productType->name,
            'dongle_id' => $inSource->serial_no,
            'status' => "active",
        ]);
    }

    public function initShipping(Delivery $delivery)
    {
        // implements store shipping

    }

    public function update(Delivery $delivery, $data, array $deliveryDetails, $deliveryStockDetails)
    {

        DB::beginTransaction();
        $delivery->update($data);
        $result['delivery'] = $delivery;

        // Calculate total expected quantity from source details
        $expectedQty = $delivery->source->details->sum('quantity');

        // Delete existing delivery details
        $delivery->deliveryDetails()->delete();

        $deliveredQty = 0; // Track actual delivered quantity

        foreach ($deliveryDetails['product_id'] as $key => $product_id) {
            $quantity = $deliveryDetails['quantity'][$key] ?? 0;

            // Skip if quantity is not set or is zero/empty
            if (empty($quantity) || $quantity <= 0) {
                continue;
            }

            $deliveryDetail = DeliveryDetail::create([
                'delivery_id' => $delivery->id,
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);

            $result['deliveryDetails'][] = $deliveryDetail;

            // Add to actual delivered quantity
            $deliveredQty += $quantity;

            // Handle Lot No
            if (!empty($deliveryStockDetails['lot_no'][$product_id])) {
                foreach ($deliveryStockDetails['lot_no'][$product_id] as $key2 => $lotNo) {
                    $lotQuantity = $deliveryStockDetails['lots_quantity'][$product_id][$key2] ?? 0;
                    if (empty($lotQuantity) || $lotQuantity <= 0) {
                        continue;
                    }
                    $deliveryStock = DeliveryStock::create([
                        'delivery_detail_id' => $deliveryDetail->id,
                        'product_catalog_id' => $product_id,
                        'quantity' => $lotQuantity,
                        'lot_no' => $lotNo,
                    ]);
                    $this->stockOut($deliveryStock);
                    $result['deliveryStock'][$deliveryDetail->id][] = $deliveryStock;
                }
            }

            // Handle Serial No
            if (!empty($deliveryStockDetails['serial_no'][$product_id])) {
                foreach ($deliveryStockDetails['serial_no'][$product_id] as $key2 => $serialNo) {
                    if (empty($serialNo)) {
                        continue;
                    }
                    $deliveryStock = DeliveryStock::create([
                        'delivery_detail_id' => $deliveryDetail->id,
                        'product_catalog_id' => $product_id,
                        'serial_no' => $serialNo,
                        'quantity' => 1,
                    ]);
                    $this->stockOut($deliveryStock);
                    if ($delivery->source->customer_id) {
                        $this->assignDongleToCustomer($deliveryStock, $delivery->source->customer_id);
                    }
                    $result['deliveryStock'][$deliveryDetail->id][] = $deliveryStock;
                }
            }
        }

        // Determine delivery status based on calculated quantities
        $status = ($deliveredQty >= $expectedQty) ? 'delivered' : 'partial';

        // Update delivery and source status
        $delivery->update(['status' => $status]);
        $delivery->source->update(['status' => $status]);

        // Optional: Shipment creation
        if ($delivery->source->is_shipment == 1 && $delivery->source->shipment) {
            // dd($shipment);
            $shipment = $this->storeShipping($delivery);
            $result['shipment'] = $shipment;
        }

        // dd($result);
        $this->makeDummyTransaction($delivery);
        DB::commit();
        return $result;
    }

    public function makeDummyTransaction(Delivery $delivery)
    {
        $delivery->transactions()->delete();

        //Cost of Goods Sold account
        $cogs = Account::where('account_number', 5300)->first();

        // Calculate total cost of goods sold first
        $totalCost = 0;
        $inventoryTransactions = [];
        // dd($delivery->deliveryDetails);
        foreach ($delivery->deliveryDetails as $deliveryDetail) {
            $inventoryAccount = $deliveryDetail->product->getInventoryAccount();

            if ($deliveryDetail->product->is_serial_product) {
                // $price = RequisitionReceiveSerial::whereIn('serial_no', $deliveryDetail->deliveryStocks->pluck('serial_no'))->get() ->pluck('requisition.requisitionDetails')->flatten()->where('product_id', $deliveryDetail->product_id)->first()->price;
                $price = $deliveryDetail->product->getLandedPrice($deliveryDetail->deliveryStocks->pluck('serial_no')->toArray());
            } else {
                //                 dd($deliveryDetail,$deliveryDetail->deliveryStocks, 
                // RequisitionReceiveBatch::whereIn('lot_no', $deliveryDetail->deliveryStocks->pluck('lot_no'))->get()->pluck('requisition.requisitionDetails')->flatten()->where('product_id', $deliveryDetail->product_id)->first()->price            );
                // $price = RequisitionReceiveBatch::with('requisition.requisitionDetails')->whereIn('lot_no', $deliveryDetail->deliveryStocks->pluck('lot_no'))->get()->pluck('requisition.requisitionDetails')->flatten()->where('product_id', $deliveryDetail->product_id)->first()->price;
                $price = $deliveryDetail->product->getLandedPrice($deliveryDetail->deliveryStocks->pluck('lot_no')->toArray());
            }


            $itemCost = $deliveryDetail->quantity * $price;
            $totalCost += $itemCost;

            $inventoryTransactions[] = [
                'account_id' => $inventoryAccount->id,
                'invoice_no' => $delivery->source->sales_order_id ?? $delivery->source->invoice_id,
                'balance_type' => 'credit',
                'debit_amount' => 0,
                'credit_amount' => $itemCost,
                'description' => "Invoice for Deliverys #" . $delivery->source->sales_order_id ?? $delivery->source->invoice_id,
                'transaction_date' => $delivery->delivery_date,
            ];
        }

        // Create COGS transaction with total cost as debit amount
        $delivery->transactions()->create([
            'account_id' => $cogs->id,
            'invoice_no' => $delivery->source->sales_order_id ?? $delivery->source->invoice_id,
            'balance_type' => 'debit',
            'debit_amount' => $totalCost,
            'credit_amount' => 0,
            'description' => "Invoice for Deliverys #" . $delivery->source->sales_order_id ?? $delivery->source->invoice_id,
            'transaction_date' => $delivery->delivery_date,
        ]);

        // Create inventory transactions
        foreach ($inventoryTransactions as $transaction) {
            $delivery->transactions()->create($transaction);
        }


        // dd($delivery->transactions);
        $totalDebits = $delivery->transactions()->sum('debit_amount');
        $totalCredits = $delivery->transactions()->sum('credit_amount');

        if ($totalDebits != $totalCredits) {
            logger()->error("Journal entries for Deliverys #" . $delivery->sales_order_id . " are unbalanced!", ['debits' => $totalDebits, 'credits' => $totalCredits]);
            throw new \Exception("Unbalanced journal entries for sales order. Debits: $totalDebits, Credits: $totalCredits");
        }
    }

    public function delete(Delivery $delivery)
    {
        $delivery->delete();
    }

    public function show($id)
    {
        return Delivery::with([])->findOrFail($id);
    }
}
