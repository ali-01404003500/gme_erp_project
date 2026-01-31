<?php

namespace Modules\CRM\Services\Customer\Settings;

use Modules\CRM\Models\Customer\Settings\CustomerRating;

class CustomerRatingService
{
    
    public function getAll(int $limit = 20) {
        return CustomerRating::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        return CustomerRating::create($data);
    }

    public function update(CustomerRating $customerRating, array $data)
    {
        $customerRating->update($data);
        return $customerRating;
    }

    public function delete(CustomerRating $customerRating)
    {
        $customerRating->delete();
    }

    public function show($id)
    {
        return CustomerRating::findOrFail($id);
    }
}
