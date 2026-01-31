<?php

namespace Modules\Account\Services\AccountSetup;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\AccountSetup\BankAccount;

class BankAccountService
{
    
    public function getAll(int $limit = 20) {
        return BankAccount::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        DB::beginTransaction();
        $bankAccount = BankAccount::create($data);
        $result['bankAccount'] = $bankAccount;

        if (in_array($data['payment_mode'], ['Cheque', 'Online Deposit', 'Card Payment', 'Bank'])) {
            $bankAccount->bank_id = $data['bank_id'];
            $bankAccount->bank_branch_id = $data['bank_branch_id'];
            $bankAccount->bank_account_no = $data['bank_account_no'];
            $bankAccount->save();
        } else {
            $bankAccount->bank_id = null;
            $bankAccount->bank_branch_id = null;
            $bankAccount->bank_account_no = null;
            $bankAccount->save();
        }

        $account = $bankAccount->getAccount();
        $result['account'] = $account;
        // dd($account);
        DB::commit();   
        return $bankAccount;
    }

    public function update(BankAccount $bankAccount, array $data)
    {
        $bankAccount->update($data);

        if (in_array($data['payment_mode'], ['Cheque', 'Online Deposit', 'Card Payment', 'Bank'])) {
            $bankAccount->bank_id = $data['bank_id'];
            $bankAccount->bank_branch_id = $data['bank_branch_id'];
            $bankAccount->bank_account_no = $data['bank_account_no'];
            $bankAccount->save();
        } else {
            $bankAccount->bank_id = null;
            $bankAccount->bank_branch_id = null;
            $bankAccount->bank_account_no = null;
            $bankAccount->save();
        }
        return $bankAccount;
    }

    public function delete(BankAccount $bankAccount)
    {
        $bankAccount->delete();
    }

    public function show($id)
    {
        return BankAccount::findOrFail($id);
    }
}
