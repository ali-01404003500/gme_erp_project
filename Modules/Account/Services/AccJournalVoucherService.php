<?php


namespace Modules\Account\Services;

use App\Traits\S3FileHandler;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\Voucher;
use Modules\Account\Models\VoucherDetail;

class AccJournalVoucherService
{
    public $invoiceNumberService;

    private $transactionService;



    public $journal;




    use S3FileHandler;





    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->invoiceNumberService = new InvoiceNumberService();

        $this->transactionService   = new AccountTransactionService();
    }












    /*
     |--------------------------------------------------------------------------
     | VALIDATE DATA
     |--------------------------------------------------------------------------
    */
    public function validateData($request)
    {

        $request->validate([

            'date'              => 'required',
            'description'       => 'required',
            'balance_type'      => 'in:Debit,Credit',
            'debits.*'          => 'required',
            'credits.*'         => 'required'

        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE JOURNAL VOUCHER
     |--------------------------------------------------------------------------
    */

    public function storeJournalVoucher($request)
    {
        if (isset($request->attachment)) {
            $attachment = $this->uploadFile($request->attachment, 'journal-vouchers');
        }
        $this->journal = Voucher::create([

            'invoice_no'    => $this->getInvoiceNumber(),
            'date'          => $request->date,
            'description'   => $request->description,
            'reference'     => $request->reference,
            'amount'        => array_sum($request->debit),
            'voucher_type'  => $request->voucher_type,
            'is_approved'   => 0,
            'attachment'    => isset($attachment) ? $attachment : null

        ]);
       
    }



    public function getInvoiceNumber()
    {
        $count_purchase_number =  Voucher::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d'))->count();

            return 'JVC-'
                . date('ymd')
                . '-'
                . str_pad($count_purchase_number + 1, 4, "0", STR_PAD_LEFT);
        
    }



    


    

    /*
     |--------------------------------------------------------------------------
     | STORE RECEIVE VOUCHER DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeJournalVoucherDetails($request)
    {

        foreach ($request->account_ids  as $key => $account_id) {

            $debit  = $request->debit[$key];
            $credit = $request->credit[$key];

            $amount = $debit + $credit; // here add two data, because one always 0
            $type   = $debit > $credit ? 'Debit' : 'Credit';



            $this->journal->details()->create([

                'account_id'    => $account_id,
                'amount'        => $amount,
                'balance_type'  => $type,
            ]);
        } 
    }




    public function updateJournalVoucher($request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $voucher = Voucher::findOrFail($id);
    
            if (isset($request->attachment)) {
                $attachment = $this->uploadFile($request->attachment, 'journal-vouchers');
            }
    
            // Update the journal voucher
            $voucher->update([
                'date'          => $request->date,
                'description'   => $request->description,
                'reference'     => $request->reference,
                'amount'        => array_sum($request->debit),
                'voucher_type'  => $request->voucher_type,
                'attachment'    => isset($attachment) ? $attachment : null
            ]);
    
            // Clear and recreate voucher details
            $voucher->details()->delete();
            foreach ($request->account_ids as $key => $account_id) {
                $debit  = $request->debit[$key];
                $credit = $request->credit[$key];
                $amount = $debit + $credit; // One is always 0
                $type   = $debit > $credit ? 'Debit' : 'Credit';
    
                $voucher->details()->create([
                    'account_id'    => $account_id,
                    'amount'        => $amount,
                    'balance_type'  => $type,
                ]);
            }
    
            if ($request->draft == 0) {
                $voucher->update([ 'is_approved' => 1 ]);
                $this->makeTransactionForUpdate($voucher);
            }
        });
    }
    


    


    

    /*
     |--------------------------------------------------------------------------
     | APPROVE VOUCHER
     |--------------------------------------------------------------------------
    */
    public function approveVoucher()
    {
        $this->journal->update([ 'is_approved' => 1 ]);
    }





    public function makeTransactionForUpdate($journal)
    {
        foreach ($journal->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $journal->invoice_no)
            ]);     

            $account = Account::find($detail->account_id);

            $debit_amount = 0;
            $credit_amount = 0;

            if ($detail->balance_type == 'Debit') {
                $debit_amount = $detail->amount;
                $amount = -$debit_amount;
            } else {
                $credit_amount = $detail->amount;
                $amount = $credit_amount;
            }

            $description = $journal->description;

            $this->transactionService->storeTransaction(
                VoucherDetail::class,
                $detail->id,
                $journal->invoice_no,
                $account->id,
                $amount,
                $debit_amount,
                $credit_amount,
                $detail->balance_type,
                $description
            );


        }
    }

    


    

    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        foreach ($this->journal->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $this->journal->invoice_no)
            ]);

            
            $account = Account::find($detail->account_id);

            $debit_amount = 0;
            $credit_amount = 0;

            if ($detail->balance_type == 'Debit') {
                $debit_amount = $detail->amount;
                $amount = -$debit_amount;
            } else {
                $credit_amount = $detail->amount;
                $amount = $credit_amount;
            }

            $description = $this->journal->description;

            $this->transactionService->storeTransaction(
                VoucherDetail::class,
                $detail->id,
                $this->journal->invoice_no,
                $account->id,
                $amount,
                $debit_amount,
                $credit_amount,
                $detail->balance_type,
                $description
            );
        }
    }
}
