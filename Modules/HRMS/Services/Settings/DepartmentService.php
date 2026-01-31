<?php

namespace Modules\HRMS\Services\Settings;
use Modules\HRMS\Models\Settings\Department;

class DepartmentService
{
    
    public function getAll(int $limit = 20) {
        return Department::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data)
    {
        $department->update($data);
        return $department;
    }

    public function delete(Department $department)
    {
        $department->delete();
    }

    public function show($id)
    {
        return Department::findOrFail($id);
    }
}
