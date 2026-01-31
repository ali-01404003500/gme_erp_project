<?php

namespace Modules\CRM\Services\Customer\Settings;

use Modules\CRM\Models\Customer\Settings\PercentageType;

class PercentageTypeService
{
    
    public function getAll(int $limit = 20) {
        return PercentageType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return PercentageType::create($data);
    }

    public function update(PercentageType $percentageType, array $data)
    {
        $percentageType->update($data);
        return $percentageType;
    }

    public function delete(PercentageType $percentageType)
    {
        $percentageType->delete();
    }

    public function show($id)
    {
        return PercentageType::findOrFail($id);
    }
}
