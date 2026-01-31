<?php

namespace Modules\Services\Services;

use Modules\Services\Models\ServiceBill;

class ServiceBillService
{
    
    public function getAll(int $limit = 20) {
        return ServiceBill::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ServiceBill::create($data);
    }

    public function update(ServiceBill $serviceBill, array $data)
    {
        $serviceBill->update($data);
        return $serviceBill;
    }

    public function delete(ServiceBill $serviceBill)
    {
        $serviceBill->delete();
    }

    public function show($id)
    {
        return ServiceBill::findOrFail($id);
    }
}
