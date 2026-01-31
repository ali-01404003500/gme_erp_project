<?php

namespace Modules\Services\Services;

use Modules\Services\Models\ServiceAssign;

class ServiceAssignService
{
    
    public function getAll(int $limit = 20) {
        return ServiceAssign::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ServiceAssign::create($data);
    }

    public function update(ServiceAssign $serviceAssign, array $data)
    {
        $serviceAssign->update($data);
        return $serviceAssign;
    }

    public function delete(ServiceAssign $serviceAssign)
    {
        $serviceAssign->delete();
    }

    public function show($id)
    {
        return ServiceAssign::findOrFail($id);
    }
}
