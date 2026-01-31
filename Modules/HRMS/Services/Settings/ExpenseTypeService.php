<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\ExpenseType;

class ExpenseTypeService
{
    
    public function getAll(int $limit = 20) {
        return ExpenseType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ExpenseType::create($data);
    }

    public function update(ExpenseType $expenseType, array $data)
    {
        $expenseType->update($data);
        return $expenseType;
    }

    public function delete(ExpenseType $expenseType)
    {
        $expenseType->delete();
    }

    public function show($id)
    {
        return ExpenseType::findOrFail($id);
    }
}
