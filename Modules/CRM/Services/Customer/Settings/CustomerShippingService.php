<?php

namespace Modules\CRM\Services\Customer\Settings;

use Modules\CRM\Models\Customer\Settings\CustomerShipping;

class CustomerShippingService
{
    
    public function getAll(int $limit = 20) {
        return CustomerShipping::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        return CustomerShipping::create($data);
    }

    public function update(CustomerShipping $customerShipping, array $data)
    {
        $customerShipping->update($data);
        return $customerShipping;
    }

    public function delete(CustomerShipping $customerShipping)
    {
        $customerShipping->delete();
    }

    public function show($id)
    {
        return CustomerShipping::findOrFail($id);
    }
}
