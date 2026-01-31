<?php

namespace Modules\Inventory\Services;


use Modules\Inventory\Models\IssueProduct;
use Modules\Inventory\Models\IssueProductDetails;

class IssueProductService
{
    
    public function getAll(int $limit = 20) {
        return IssueProduct::query()->paginate($limit);
    }
    
    public function store(array $data, array  $issueProductDetails)
    {
        $result['products'] = IssueProduct::create($data);
        $result['issueProductDetails'] = [];
        foreach ($issueProductDetails['product_catalog_id'] as $key => $value) {
            $result['issueProductDetails'][] = IssueProductDetails::create([
                'issue_product_id' => $result['products']->id,
                'product_catalog_id' => $issueProductDetails['product_catalog_id'][$key],
                'product_name' => $issueProductDetails['product_name'][$key],
                'sku' => $issueProductDetails['sku'][$key],
                'unit_type_id' => $issueProductDetails['unit_type_id'][$key],
                'quantity' => $issueProductDetails['quantity'][$key],
            ]);
        }
        return $result;
    }

    public function update(IssueProduct $issueProduct, array $data, array $issueProductDetails)
    {
        $result['products'] = $issueProduct->update($data);
        $result['issueProductDetails'] = [];
        $issueProductDetailsId = $issueProductDetails['issue_product_detail_id'];
        $issueProduct->issueProductDetails()->whereNotIn('id', $issueProductDetailsId)->delete();

        foreach ($issueProductDetails['product_catalog_id'] as $key => $value) {
            $result['issueProductDetails'][] = IssueProductDetails::updateOrCreate(
                [
                    'id' => $issueProductDetails['issue_product_details_id'][$key]??null,
                ],[
                'issue_product_id' => $issueProduct->id,
                'product_catalog_id' => $issueProductDetails['product_catalog_id'][$key],
                'product_name' => $issueProductDetails['product_name'][$key],
                'sku' => $issueProductDetails['sku'][$key],
                'unit_type_id' => $issueProductDetails['unit_type_id'][$key],
                'quantity' => $issueProductDetails['quantity'][$key],
            ]);
        }
        return $issueProduct;
    }

    public function delete(IssueProduct $issueProduct)
    {
        $issueProduct->issueProductDetails()->delete();
        $issueProduct->delete();
    }

    public function show($id)
    {
        return IssueProduct::findOrFail($id);
    }
}
