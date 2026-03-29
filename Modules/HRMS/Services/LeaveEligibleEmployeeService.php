<?php
namespace Modules\HRMS\Services;

use Modules\HRMS\Models\LeaveEligibleEmployee;

class LeaveEligibleEmployeeService
{
    public function getAll(int $limit = 20)
    {
        return LeaveEligibleEmployee::query()->latest()->paginate($limit);
    }

    public function store(array $data)
    {
        return LeaveEligibleEmployee::create($data);
    }

    public function update(LeaveEligibleEmployee $leaveEligible, array $data)
    {
        $leaveEligible->update($data);
        return $leaveEligible;
    }

    public function delete(LeaveEligibleEmployee $leaveEligible)
    {
        return $leaveEligible->delete();
    }
    public function show($id)
    {
        return LeaveEligibleEmployee::findOrFail($id);
    }
}
