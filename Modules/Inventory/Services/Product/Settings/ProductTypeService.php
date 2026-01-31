<?php

namespace Modules\Inventory\Services\Product\Settings;
use Modules\Inventory\Models\Product\Settings\ProductType;

class ProductTypeService
{
    
    public function getAll(int $limit = 20) {
        return ProductType::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        return ProductType::create($data);
    }

    public function update(ProductType $productType, array $data)
    {
        $productType->update($data);
        return $productType;
    }

    public function delete(ProductType $productType)
    {
        $productType->delete();
    }

    public function show($id)
    {
        return ProductType::with('productCatalogs')->findOrFail($id);
    }

    public function productCatalogs($productTypeId){
        $productType = $this->show($productTypeId);
        return $productType->productCatalogs;
    }
}
