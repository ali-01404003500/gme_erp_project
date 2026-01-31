<?php

namespace Modules\CRM\Services\Customer\Settings;

use Modules\CRM\Models\Customer\Settings\CustomerType;

class CustomerTypeService
{
    
    public function getAll(int $limit = 20) {
        return CustomerType::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        return CustomerType::create($data);
    }

    public function update(CustomerType $customerType, array $data)
    {
        $customerType->update($data);
        return $customerType;
    }

    public function delete(CustomerType $customerType)
    {
        $customerType->delete();
    }

    public function show($id)
    {
        return CustomerType::findOrFail($id);
    }
}
