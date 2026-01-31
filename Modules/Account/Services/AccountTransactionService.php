<?php


namespace Modules\Account\Services;

use Modules\Account\Models\Account;
use Modules\Account\Models\Transaction;

class AccountTransactionService
{

    public function storeTransaction($transactionable_type, $transactionable_id, $invoice_no, $account_id, $amount, $debit_amount, $credit_amount, $balance_type, $description)
    {
        // dd($transactionable_type, $transactionable_id, $invoice_no, $account_id, $amount, $debit_amount, $credit_amount, $balance_type, $description);
        
            $transection = Transaction::create([
                'transactionable_type'  => $transactionable_type,
                'transactionable_id'    => $transactionable_id,
                'account_id'            => $account_id,
                'balance_type'          => $balance_type,
                'invoice_no'            => $invoice_no,
                // 'amount'                => $amount,
                'debit_amount'          => $debit_amount,
                'credit_amount'         => $credit_amount,
                'description'           => $description
            ]);

           

        return $transection;
    }

    public function deleteTransaction($invoice_no)
    {
        Transaction::where('invoice_no', $invoice_no)->delete();
    }

    public function getAccount($accountID)
    {
        return Account::where('id', $accountID)->first();
    }

    public function getAccountById($id)
    {
        return Account::find($id);
    }















    /*
     |--------------------------------------------------------------------------
     | CASH ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getCashAccount()
    {
        return Account::find(request('account_id') ?? config('account.cash'));
    }












    /*
     |--------------------------------------------------------------------------
     | SALE ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getSaleAccount()
    {
        return Account::find(config('account.sale'));
    }










    /*
     |--------------------------------------------------------------------------
     | SALE RETURN ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getSaleReturnAccount()
    {
        return Account::find(config('account.sale_return'));
    }






    /*
     |--------------------------------------------------------------------------
     | PURCHASE RETURN ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getPurchaseReturnAccount()
    {
        return Account::find(config('account.purchase_return'));
    }






    /*
     |--------------------------------------------------------------------------
     | DAMAGE ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getDamageAccount()
    {
        return Account::find(config('account.damage'));
    }












    /*
     |--------------------------------------------------------------------------
     | PURCHASE ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getPurchaseAccount()
    {
        return Account::find(config('account.purchase'));
    }












    /*
     |--------------------------------------------------------------------------
     | PARTY OPENING ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getPartyOpeningAccount()
    {
        return Account::find(config('account.party_opening'));
    }
}
