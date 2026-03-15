<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\ProductTransferReceive;
use Modules\Inventory\Models\ProductTransferReceiveDetail;
use Modules\Inventory\Models\ProductTransferReceiveStockDetail;
use Modules\Inventory\Models\ProductTransfer;
use Illuminate\Support\Facades\DB;

class ProductTransferReceiveService
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function getAll(int $limit = 20)
    {
        return ProductTransferReceive::query()
            ->with(['productTransfer', 'sourceBranch', 'destinationBranch'])
            ->searchByFields(['source_warehouse_id', 'destination_warehouse_id'])
            ->paginate($limit);
    }

    public function stockIn(ProductTransferReceiveStockDetail $productTransferReceiveStockDetail, $branch_id)
    {
        if ($productTransferReceiveStockDetail->lot_no) {
            $stock = $this->stockService->store([
                'product_id' => $productTransferReceiveStockDetail->product_id,
                'source_type' => ProductTransferReceiveStockDetail::class,
                'source_id' => $productTransferReceiveStockDetail->id,
                'stock_type' => 'in',
                'in_qty' => $productTransferReceiveStockDetail->quantity,
                'lot_no' => $productTransferReceiveStockDetail->lot_no,
                'branch_id' => $branch_id,
            ]);
            return $stock;
        }
        if ($productTransferReceiveStockDetail->serial_no) {
            $stock = $this->stockService->store([
                'product_id' => $productTransferReceiveStockDetail->product_id,
                'source_type' => ProductTransferReceiveStockDetail::class,
                'source_id' => $productTransferReceiveStockDetail->id,
                'stock_type' => 'in',
                'in_qty' => 1,
                'serial_no' => $productTransferReceiveStockDetail->serial_no,
                'branch_id' => $branch_id,
            ]);
            return $stock;
        }
    }

    public function store(array $data, array $product_details = [], array $productStockDetails = [])
    {
        DB::beginTransaction();
        try {
            $result['productTransferReceives'] = ProductTransferReceive::create($data);
            $result['product_transfer_receive_details'] = [];

            foreach ($product_details['product_id'] as $key => $product_id) {
                $productTransferReceiveDetail = ProductTransferReceiveDetail::create([
                    'product_transfer_receive_id' => $result['productTransferReceives']->id,
                    'product_id' => $product_details['product_id'][$key],
                    'quantity' => $product_details['quantity'][$key],
                    'received_quantity' => $product_details['received_quantity'][$key] ?? $product_details['quantity'][$key],
                ]);

                $result['product_transfer_receive_details']['productTransferReceiveStockDetails'] = [];

                // Handle Lot Numbers
                if (isset($productStockDetails['lot_no'][$product_id])) {
                    foreach ($productStockDetails['lot_no'][$product_id] as $key2 => $value) {
                        $productTransferReceiveStockDetail = ProductTransferReceiveStockDetail::create([
                            'details_id' => $productTransferReceiveDetail->id,
                            'product_id' => $product_id,
                            'lot_no' => $value,
                            'quantity' => $productStockDetails['lots_quantity'][$product_id][$key2],
                        ]);
                        
                        // Stock In to destination warehouse
                        $this->stockIn($productTransferReceiveStockDetail, $result['productTransferReceives']->destination_warehouse_id);
                        
                        $result['product_transfer_receive_details']['productTransferReceiveStockDetails'][] = $productTransferReceiveStockDetail;
                    }
                }

                // Handle Serial Numbers
                if (isset($productStockDetails['serial_no'][$product_id])) {
                    foreach ($productStockDetails['serial_no'][$product_id] as $key3 => $value) {
                        $productTransferReceiveStockDetail = ProductTransferReceiveStockDetail::create([
                            'details_id' => $productTransferReceiveDetail->id,
                            'product_id' => $product_id,
                            'serial_no' => $value,
                            'quantity' => 1,
                        ]);
                        
                        // Stock In to destination warehouse
                        $this->stockIn($productTransferReceiveStockDetail, $result['productTransferReceives']->destination_warehouse_id);
                        
                        $result['product_transfer_receive_details']['productTransferReceiveStockDetails'][] = $productTransferReceiveStockDetail;
                    }
                }

                $result['product_transfer_receive_details'][] = $productTransferReceiveDetail;
            }

            // Update ProductTransfer status to 'received'
            $productTransfer = $result['productTransferReceives']->productTransfer;
            if ($productTransfer) {
                $productTransfer->update(['status' => 'received']);
            }

            // Update ProductTransferReceive status to 'approved'
            $result['productTransferReceives']->update(['status' => 'approved']);

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(ProductTransferReceive $productTransferReceive, array $data, array $product_details = [], array $productStockDetails = [])
    {
        DB::beginTransaction();
        try {
            $productTransferReceive->update($data);

            // Delete existing details and recreate them
            ProductTransferReceiveDetail::where('product_transfer_receive_id', $productTransferReceive->id)->delete();

            $result['product_details'] = [];
            foreach ($product_details['product_id'] as $key => $product_id) {
                $productTransferReceiveDetail = ProductTransferReceiveDetail::create([
                    'product_transfer_receive_id' => $productTransferReceive->id,
                    'product_id' => $product_id,
                    'quantity' => $product_details['quantity'][$key],
                    'received_quantity' => $product_details['received_quantity'][$key] ?? $product_details['quantity'][$key],
                ]);

                // Handle Stock Details if provided
                if (isset($productStockDetails['lot_no'][$product_id])) {
                    foreach ($productStockDetails['lot_no'][$product_id] as $key2 => $value) {
                        ProductTransferReceiveStockDetail::create([
                            'details_id' => $productTransferReceiveDetail->id,
                            'product_id' => $product_id,
                            'lot_no' => $value,
                            'quantity' => $productStockDetails['lots_quantity'][$product_id][$key2],
                        ]);
                    }
                }

                if (isset($productStockDetails['serial_no'][$product_id])) {
                    foreach ($productStockDetails['serial_no'][$product_id] as $key3 => $value) {
                        ProductTransferReceiveStockDetail::create([
                            'details_id' => $productTransferReceiveDetail->id,
                            'product_id' => $product_id,
                            'serial_no' => $value,
                            'quantity' => 1,
                        ]);
                    }
                }

                $result['product_details'][] = $productTransferReceiveDetail;
            }

            DB::commit();
            return $productTransferReceive;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(ProductTransferReceive $productTransferReceive)
    {
        DB::beginTransaction();
        try {
            // Delete stock entries related to this receive
            ProductTransferReceiveStockDetail::whereHas('productTransferReceiveDetail', function ($query) use ($productTransferReceive) {
                $query->where('product_transfer_receive_id', $productTransferReceive->id);
            })->delete();

            ProductTransferReceiveDetail::where('product_transfer_receive_id', $productTransferReceive->id)->delete();
            
            $productTransferReceive->delete();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        return ProductTransferReceive::with([
            'productTransfer',
            'productTransferReceiveDetails.productCatalog',
            'productTransferReceiveDetails.productTransferReceiveStockDetails',
            'sourceBranch',
            'destinationBranch'
        ])->findOrFail($id);
    }

    public function getPendingReceivesByBranch($branchId)
    {
        return ProductTransferReceive::where('destination_warehouse_id', $branchId)
            ->where('status', 'pending')
            ->with(['productTransfer', 'sourceBranch', 'destinationBranch'])
            ->get();
    }
}
