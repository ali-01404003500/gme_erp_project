<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\LeaveAdjustment;

class LeaveAdjustmentService
{
    
    public function getAll(int $limit = 20) {
        // return LeaveAdjustment::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return LeaveAdjustment::create($data);
    }

    public function update(LeaveAdjustment $leaveAdjustment, array $data)
    {
        $leaveAdjustment->update($data);
        return $leaveAdjustment;
    }

    public function delete(LeaveAdjustment $leaveAdjustment)
    {
        $leaveAdjustment->delete();
    }

    public function show($id)
    {
        return LeaveAdjustment::findOrFail($id);
    }
}
