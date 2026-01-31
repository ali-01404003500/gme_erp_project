<?php

namespace Modules\Inventory\Services\Product\Settings;
use Modules\Inventory\Models\Product\Settings\Brand;

class BrandService
{
    
    public function getAll(int $limit = 20) {
        return Brand::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        return Brand::create($data);
    }

    public function update(Brand $brand, array $data)
    {
        $brand->update($data);
        return $brand;
    }

    public function delete(Brand $brand)
    {
        $brand->delete();
    }

    public function show($id)
    {
        return Brand::findOrFail($id);
    }


    public function getProductCatalogs($id){
        return $this->show($id)->productCatalogs()->get();
    }
}
