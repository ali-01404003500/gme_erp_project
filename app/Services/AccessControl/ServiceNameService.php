<?php

namespace App\Services\AccessControl;

use App\Models\AccessControl\ServiceName;

class ServiceNameService
{
    
    public function getAll(int $limit = 20) {
        return ServiceName::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ServiceName::create($data);
    }

    public function update(ServiceName $serviceName, array $data)
    {
        $serviceName->update($data);
        return $serviceName;
    }

    public function delete(ServiceName $serviceName)
    {
        $serviceName->delete();
    }

    public function show($id)
    {
        return ServiceName::findOrFail($id);
    }
}
