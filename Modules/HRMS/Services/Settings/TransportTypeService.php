<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\TransportType;

class TransportTypeService
{
    
    public function getAll(int $limit = 20) {
        return TransportType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return TransportType::create($data);
    }

    public function update(TransportType $transportType, array $data)
    {
        $transportType->update($data);
        return $transportType;
    }

    public function delete(TransportType $transportType)
    {
        $transportType->delete();
    }

    public function show($id)
    {
        return TransportType::findOrFail($id);
    }
}
