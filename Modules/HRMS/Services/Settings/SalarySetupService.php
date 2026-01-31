<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\SalarySetup;

class SalarySetupService
{
    
    public function getAll(int $limit = 20) {
        return SalarySetup::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return SalarySetup::create($data);
    }

    public function update(SalarySetup $salarySetup, array $data)
    {
        $salarySetup->update($data);
        return $salarySetup;
    }

    public function delete(SalarySetup $salarySetup)
    {
        $salarySetup->delete();
    }

    public function show($id)
    {
        return SalarySetup::findOrFail($id);
    }
}
