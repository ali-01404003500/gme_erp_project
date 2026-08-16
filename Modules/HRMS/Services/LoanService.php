<?php

namespace Modules\HRMS\Services;

use Modules\Account\Models\Account;
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

    public function getOnlyApproved(int $limit = 20) {
        return Loan::query()
        ->searchByFields([
                'employee_id' => 'employee_id',
            ])
        ->whereIn('status',['approved','paid','processing','verify deny'])
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
 
    public function makeDummyTransaction(Loan $loan)
    {
        
        $loan->transactions()->delete();
 
        $cashAccount = $loan->paymentDetails->first()->bank;
 
        $account = Account::where('accountable_id', $cashAccount->id)->first();
        
        $loan->transactions()->create([
            'account_id' => $account->id,
            'balance_type' => 'credit',
            'invoice_no' => $loan->id,
            'amount' => $loan->amount,
            'debit_amount' => 0,
            'credit_amount' => $loan->amount,
            'description' => 'Employee Salary Advance',
        ]);
 
        $loan->transactions()->create([
            'account_id' => $loan->employee->getStaffLoanAccount()->id,
            'balance_type' => 'debit',
            'invoice_no' => $loan->id,
            'amount' => -$loan->amount,
            'debit_amount' => $loan->amount,
            'credit_amount' => 0,
            'description' => 'Employee Salary Advance',
        ]);
        
    }

    
}
