<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\Loan;

class LoanService
{
    
    public function getAll(int $limit = 20) {
        return Loan::query()
        ->searchByFields([
                'employee_id' => 'employee_id',
            ])
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Loan::create($data);
    }

    public function update(Loan $loan, array $data)
    {
        $loan->update($data);
        return $loan;
    }

    public function delete(Loan $loan)
    {
        $loan->delete();
    }

    public function show($id)
    {
        return Loan::findOrFail($id);
    }
}
