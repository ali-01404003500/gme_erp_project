<?php

namespace Modules\Inventory\Services;


use Modules\Inventory\Models\warehouse;
use App\Traits\S3FileHandler;

class warehouseService
{
    use S3FileHandler;
    
    public function getAll(int $limit = 20) {
        return warehouse::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        if(isset($data['picture'])) {
            $data['picture'] = $this->uploadFile($data['picture'], 'warehouses');
        }
        return warehouse::create($data);
    }

    public function update(warehouse $warehouse, array $data)
    {
        if(isset($data['picture'])) {
            $data['picture']= $this->uploadFile($data['picture'], 'warehouses');
        }
        $warehouse->update($data);
        return $warehouse;
    }

    public function delete(warehouse $warehouse)
    {
        $warehouse->delete();
    }

    public function show($id)
    {
        return warehouse::findOrFail($id);
    }
}
