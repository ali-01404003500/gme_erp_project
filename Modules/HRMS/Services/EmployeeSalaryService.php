<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\EmployeeSalary;

class EmployeeSalaryService
{
    
    public function getAll(int $limit = 20) {
        return EmployeeSalary::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        EmployeeSalary::where('employee_id', $data['employee_id'])
            ->update(['status' => 0]);
        return EmployeeSalary::create($data);
    }

    public function update(EmployeeSalary $employeeSalary, array $data)
    {
        $employeeSalary->update($data);
        return $employeeSalary;
    }

    public function delete(EmployeeSalary $employeeSalary)
    {
        $employeeSalary->delete();
    }

    public function show($id)
    {
        return EmployeeSalary::findOrFail($id);
    }
}
