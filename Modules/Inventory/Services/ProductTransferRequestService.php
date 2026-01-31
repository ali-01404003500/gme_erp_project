<?php

namespace Modules\Inventory\Services;


use Modules\Inventory\Models\ProductTransferRequest;
use Modules\Inventory\Models\ProductTransferRequestDetail;

class ProductTransferRequestService
{

    public function getAll(int $limit = 20)
    {
        return ProductTransferRequest::query()
            ->searchByFields(['source_branch_id', 'destination_branch_id'])
            ->where(function ($query) {
                // Allow creator (destination branch) to see
                $query->branchOnly()
                    // Allow source branch (provider) to see
                    ->orWhere('source_branch_id', auth()->user()->branch_id)
                    ->orWhere('destination_branch_id', auth()->user()->branch_id);
            })
            ->paginate($limit);
    }

    public function store(array $data, array $productRequestDetails = [])
    {
        $productTransferRequest = ProductTransferRequest::create($data);
        foreach ($productRequestDetails['product_catalog_id'] as $key => $product_catalog_id) {
            # code...
            $productTransferRequest->productTransferRequestDetails()->create([
                // 'product_type_id' => $product_type_id,
                'product_catalog_id' => $product_catalog_id,
                // 'sku' => $productRequestDetails['sku'][$key],
                // 'unit_type_id' => $productRequestDetails['unit_type_id'][$key],
                'quantity' => $productRequestDetails['quantity'][$key],
                // 'transfer_notes' => $productRequestDetails['transfer_notes'][$key],
            ]);
        }
        return $productTransferRequest;
    }

    public function update(ProductTransferRequest $productTransferRequest, array $data, array $productRequestDetails = [])
    {
        // dd($productRequestDetails['product_transfer_request_detail_id']);
        $productTransferRequest->update($data);
        $productTransferRequest->productTransferRequestDetails()->whereNotIn('id', $productRequestDetails['product_transfer_request_detail_id'])->delete();

        foreach ($productRequestDetails['product_catalog_id'] as $key => $product_catalog_id) {
            if ($productRequestDetails['product_transfer_request_detail_id'][$key]) {
                $productTransferRequest->productTransferRequestDetails()->where('id', $productRequestDetails['product_transfer_request_detail_id'][$key])->update([
                    // 'product_type_id' => $product_type_id,
                    'product_catalog_id' => $product_catalog_id,
                    // 'sku' => $productRequestDetails['sku'][$key],
                    // 'unit_type_id' => $productRequestDetails['unit_type_id'][$key],
                    'quantity' => $productRequestDetails['quantity'][$key],
                    // 'transfer_notes' => $productRequestDetails['transfer_notes'][$key],
                ]);
            } else {
                $productTransferRequest->productTransferRequestDetails()->create([
                    // 'product_type_id' => $product_type_id,
                    'product_catalog_id' => $product_catalog_id,
                    // 'sku' => $productRequestDetails['sku'][$key],
                    // 'unit_type_id' => $productRequestDetails['unit_type_id'][$key],
                    'quantity' => $productRequestDetails['quantity'][$key],
                    // 'transfer_notes' => $productRequestDetails['transfer_notes'][$key],
                ]);
            }

        }
        return $productTransferRequest;
    }

    public function delete(ProductTransferRequest $productTransferRequest)
    {
        $productTransferRequest->delete();
    }

    public function show($id)
    {
        return ProductTransferRequest::findOrFail($id);
    }
}
