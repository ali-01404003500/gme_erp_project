<?php

namespace Modules\Account\Services;

use Modules\Account\Models\Account;
use Modules\Account\Models\FundTransfer;

class FundTransferService
{
     
    public function getAll(int $limit = 20) {
        return FundTransfer::query()
            ->searchByFields(['transfer_type', 'status'])
            ->filterByDateRange('transfer_date')
            ->paginate($limit);
    }


    public function store(array $data)
    {
        return FundTransfer::create($data);
    }

    public function update(FundTransfer $fundTransfer, array $data)
    {
        $fundTransfer->update($data);
        if($data['status']=='approved'){
            $fundTransfer->update(['status'=> 'approved','approve_by'=>auth()->user()->id,'approve_date'=>now()]);
            $this->postJournalEntry($fundTransfer);
        } 
        // if($data['status']=='verified'){
        //     $fundTransfer->update(['verify_by'=>auth()->user()->id,'verify_date'=>now()]);
        // }  
        return $fundTransfer;
    }

    public function delete(FundTransfer $fundTransfer)
    {
        $fundTransfer->delete();
    }

    public function show($id)
    {
        return FundTransfer::findOrFail($id);
    }

    private function postJournalEntry(FundTransfer $fundTransfer)
    {
        $fromAccount = $fundTransfer->transferFromBankAccount->getAccount();
        $toAccount = $fundTransfer->transferToBankAccount->getAccount();
        $chargeAccount = Account::where('account_number', '506401')->first()->id; // Bank Charge Expense account 

        $fundTransfer->transactions()->delete();

        $amount = $fundTransfer->amount;
        $charge = $fundTransfer->charge;

 

        // Debit sender account
        $fundTransfer->transactions()->create([
            'account_id' => $fromAccount->id,
            'debit_amount' => $amount,
            'credit_amount' => 0,
            'balance_type' => 'debit',
            'transaction_date' => $fundTransfer->transfer_date,
            'description' => 'Fund Transfer from ' . $fundTransfer->transferFromBankAccount->account_name . " to " . $fundTransfer->transferFromBankAccount->account_name,
        ]);

        // Bank Charge Expense
        if ($charge > 0 && $chargeAccount) {
            $fundTransfer->transactions()->create([
                'account_id' => $chargeAccount,
                'debit_amount' => $charge,
                'credit_amount' => 0,
                'balance_type' => 'debit',
                'transaction_date' => $fundTransfer->transfer_date,
                 'description' => 'Fund Transfer from ' . $fundTransfer->transferFromBankAccount->account_name . " to " . $fundTransfer->transferFromBankAccount->account_name,
            ]);
        }

        // Credit receiver Bank (amount + charge)
        $fundTransfer->transactions()->create([
            'account_id' => $toAccount->id,
            'debit_amount' => 0,
            'credit_amount' => $amount + $charge,
            'balance_type' => 'credit',
            'transaction_date' => $fundTransfer->transfer_date,
            'description' => 'Fund Transfer from ' . $fundTransfer->transferFromBankAccount->account_name . " to " . $fundTransfer->transferFromBankAccount->account_name,
        ]);
   
    }
}
