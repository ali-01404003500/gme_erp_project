<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\LeaveType;

class LeaveTypeService
{
    
    public function getAll(int $limit = 20) {
        return LeaveType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return LeaveType::create($data);
    }

    public function update(LeaveType $leaveType, array $data)
    {
        $leaveType->update($data);
        return $leaveType;
    }

    public function delete(LeaveType $leaveType)
    {
        $leaveType->delete();
    }

    public function show($id)
    {
        return LeaveType::findOrFail($id);
    }
}
