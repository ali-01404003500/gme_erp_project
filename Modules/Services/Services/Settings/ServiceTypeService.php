<?php

namespace Modules\Services\Services\Settings;

use Modules\Services\Models\Settings\ServiceType;

class ServiceTypeService
{
    
    public function getAll(int $limit = 20) {
        return ServiceType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ServiceType::create($data);
    }

    public function update(ServiceType $serviceType, array $data)
    {
        $serviceType->update($data);
        return $serviceType;
    }

    public function delete(ServiceType $serviceType)
    {
        $serviceType->delete();
    }

    public function show($id)
    {
        return ServiceType::findOrFail($id);
    }
}
