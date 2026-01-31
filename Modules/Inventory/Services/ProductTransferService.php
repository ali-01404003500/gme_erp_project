<?php

namespace Modules\Inventory\Services;


use Modules\Inventory\Models\ProductTransfer;
use Modules\Inventory\Models\ProductTransferDetail;
use Modules\Inventory\Models\ProductTransferStockDetails;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\ProductTransferRequest;

class ProductTransferService
{

    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function getAll(int $limit = 20)
    {
        return ProductTransfer::query()
            ->searchByFields(['source_warehouse_id', 'destination_warehouse_id'])
            ->paginate($limit);
    }


    public function stockOut(ProductTransferStockDetails $productTransferStockDetails, $branch_id)
    {
        if ($productTransferStockDetails->lot_no) {
            $stock = $this->stockService->store([
                'product_id' => $productTransferStockDetails->product_id,
                'source_type' => ProductTransferStockDetails::class,
                'source_id' => $productTransferStockDetails->id,
                'stock_type' => 'out',
                'out_qty' => $productTransferStockDetails->quantity,
                'lot_no' => $productTransferStockDetails->lot_no,
                'branch_id' => $branch_id,
            ]);
            return $stock;
        }
        if ($productTransferStockDetails->serial_no) {
            $stock = $this->stockService->store([
                'product_id' => $productTransferStockDetails->product_id,
                'source_type' => ProductTransferStockDetails::class,
                'source_id' => $productTransferStockDetails->id,
                'stock_type' => 'out',
                'out_qty' => 1,
                'serial_no' => $productTransferStockDetails->serial_no,
                'branch_id' => $branch_id,
            ]);
            return $stock;
        }
    }

    public function stockIn(ProductTransferStockDetails $productTransferStockDetails, $branch_id)
    {
        if ($productTransferStockDetails->lot_no) {
            $stock = $this->stockService->store([
                'product_id' => $productTransferStockDetails->product_id,
                'source_type' => ProductTransferStockDetails::class,
                'source_id' => $productTransferStockDetails->id,
                'stock_type' => 'in',
                'in_qty' => $productTransferStockDetails->quantity,
                'lot_no' => $productTransferStockDetails->lot_no,
                'branch_id' => $branch_id,
            ]);
            return $stock;
        }
        if ($productTransferStockDetails->serial_no) {
            $stock = $this->stockService->store([
                'product_id' => $productTransferStockDetails->product_id,
                'source_type' => ProductTransferStockDetails::class,
                'source_id' => $productTransferStockDetails->id,
                'stock_type' => 'in',
                'in_qty' => 1,
                'serial_no' => $productTransferStockDetails->serial_no,
                'branch_id' => $branch_id,
            ]);
            return $stock;
        }
    }

    public function store(array $data, array $product_details = [], array $productStockDetails = [])
    {
        DB::beginTransaction();
        $result['productTransfers'] = ProductTransfer::create($data);
        $result['product_transfer_details'] = [];
        // dd($productStockDetails);
        foreach ($product_details['product_id'] as $key => $product_id) {
            $productTransferDetail = ProductTransferDetail::create([
                'product_transfer_id' => $result['productTransfers']->id,
                'product_id' => $product_details['product_id'][$key],
                'quantity' => $product_details['quantity'][$key],
            ]);
            $result['product_transfer_details']['productTransferStockDetails'] = [];
            if ($productStockDetails['lot_no'] ?? null) {
                foreach ($productStockDetails['lot_no'][$product_id] as $key2 => $value) {
                    # code...
                    $productTransferStockDetail = ProductTransferStockDetails::create([
                        'details_id' => $productTransferDetail->id,
                        'product_id' => $product_id,
                        'lot_no' => $value,
                        'quantity' => $productStockDetails['lots_quantity'][$product_id][$key2],

                    ]);
                    $this->stockOut($productTransferStockDetail, $result['productTransfers']->source_warehouse_id);
                    $this->stockIn($productTransferStockDetail, $result['productTransfers']->destination_warehouse_id);
                    $result['product_transfer_details']['productTransferStockDetails'][] = $productTransferStockDetail;
                }
            }
            if ($productStockDetails['serial_no'] ?? null) {
                foreach ($productStockDetails['serial_no'][$product_id] as $key => $value) {
                    # code...
                    $productTransferStockDetail = ProductTransferStockDetails::create([
                        'details_id' => $productTransferDetail->id,
                        'product_id' => $product_id,
                        'serial_no' => $value,
                        'quantity' => 1,
                    ]);
                    $this->stockOut($productTransferStockDetail, $result['productTransfers']->source_warehouse_id);
                    $this->stockIn($productTransferStockDetail, $result['productTransfers']->destination_warehouse_id);
                    $result['product_transfer_details']['productTransferStockDetails'][] = $productTransferStockDetail;
                }
            }

            $result['product_transfer_details'][] = $productTransferDetail;
            // dd($result['productTransfers']);

            $productTransfer = $result['productTransfers'];

            $productTransfer->productTransferRequest()->update([
                'status' => 'transferred'
            ]);

        }

        // dd($result['product_transfer_details']);
        DB::commit();

        return $result;
    }

    public function update(ProductTransfer $productTransfer, array $data, array $product_details = [])
    {
        $productTransfer->update($data);

        ProductTransferDetail::where('product_transfer_id', $productTransfer->id)->delete();

        $result['product_details'] = [];
        foreach ($product_details['product_type_id'] as $key => $detail) {
            $result['product_details'][] = ProductTransferDetail::create([
                'product_transfer_id' => $productTransfer->id,
                'product_id' => $product_details['product_ids'][$key],
                // 'sku' => $product_details['sku'][$key],
                'quantity' => $product_details['quantity'][$key],
                // 'transfer_notes' =>  $product_details['transfer_notes'][$key],
            ]);
        }
        return $productTransfer;
    }

    public function delete(ProductTransfer $productTransfer)
    {

        $productTransferDetails = ProductTransferDetail::where('product_transfer_id', $productTransfer->id)->delete();
        $productTransfer->delete();
    }

    public function show($id)
    {
        return ProductTransfer::findOrFail($id);
    }
}
