<?php

namespace Modules\Inventory\Services\Settings;
use Modules\Inventory\Models\Settings\Approver;

class ApproverService
{
    
    public function getAll(int $limit = 20) {
        return Approver::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        
        return Approver::create($data);
    }

    public function update(Approver $approver, array $data)
    {
        $approver->update($data);
        return $approver;
    }

    public function delete(Approver $approver)
    {
        $approver->delete();
    }

    public function show($id)
    {
        return Approver::findOrFail($id);
    }
}
