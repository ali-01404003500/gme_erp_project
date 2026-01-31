<?php


namespace Modules\Account\Services;

use App\Traits\S3FileHandler;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\Voucher;
use Modules\Account\Models\VoucherDetail;

class AccReceiveVoucherService
{
    public $invoiceNumberService;

    private $transactionService;



    public $receive;




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
     | STORE RECEIVE VOUCHER
     |--------------------------------------------------------------------------
    */
    public function storeReceiveVoucher($request)
    {
        if (isset($request->attachment)) {
            $attachment = $this->uploadFile($request->attachment, 'receive-vouchers');
        }

        $this->receive = Voucher::create([

            'invoice_no'    => $this->getInvoiceNumber(),
            // 'company_id'    => $request->company_id,
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

            return 'RVC-'
                . date('ymd')
                . '-'
                . str_pad($count_purchase_number + 1, 4, "0", STR_PAD_LEFT);
        
    }






    


    

    /*
     |--------------------------------------------------------------------------
     | STORE RECEIVE VOUCHER DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeReceiveVoucherDetails($request)
    {

        foreach ($request->account_ids  as $key => $account_id) {

            $debit  = $request->debit[$key];
            $credit = $request->credit[$key];

            $amount = $debit + $credit; // here add two data, because one always 0
            $type   = $debit > $credit ? 'Debit' : 'Credit';



            $detail = $this->receive->details()->create([

                'account_id'    => $account_id,
                'amount'        => $amount,
                'balance_type'  => $type,
            ]);
        } 
    }




    public function updatePaymentVoucher($request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $voucher = Voucher::findOrFail($id);
    
            if (isset($request->attachment)) {
                $attachment = $this->uploadFile($request->attachment, 'receive-vouchers');
            }
    
            // Update the payment voucher
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
        $this->receive->update([ 'is_approved' => 1 ]);
    }





    public function makeTransactionForUpdate($payment)
    {
        foreach ($payment->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $payment->invoice_no)
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

            $description = $payment->description;

            $this->transactionService->storeTransaction(
                VoucherDetail::class,
                $detail->id,
                $payment->invoice_no,
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
        foreach ($this->receive->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $this->receive->invoice_no)
            ]);
            $account = Account::find($detail->account_id);

            $description = $this->receive->description;

            $debit_amount = 0;
            $credit_amount = 0;

            if ($detail->balance_type == 'Debit') {
                $debit_amount = $detail->amount;
                $amount = -$debit_amount;
            } else {
                $credit_amount = $detail->amount;
                $amount = $credit_amount;
            }

            $description = $this->receive->description;

            $this->transactionService->storeTransaction(
                VoucherDetail::class,
                $detail->id,
                $this->receive->invoice_no,
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
