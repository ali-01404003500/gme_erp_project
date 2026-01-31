<?php

namespace Modules\Account\Services\Payments;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\Payments\SupplierPayment;
use Modules\Account\Models\Payments\SupplierPaymentDetail;
use Modules\Account\Services\AccountTransactionService;

class SupplierPaymentService
{
    private $transactionService;
    
    public function __construct(AccountTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function getAll(int $limit = 20) {
        return SupplierPayment::query()->paginate($limit);
    }
    
    public function store(array $data, array $receiveDetails)
    {
        DB::beginTransaction();
        $supplierPayment = SupplierPayment::create($data);
        $result['supplierPayment'] = $supplierPayment;
        foreach ($receiveDetails['receive_ids'] as $checked => $receive_id) {
             $supplierPaymentDetail =SupplierPaymentDetail::create( [
                'supplier_payment_id'=>$supplierPayment->id,
                'receive_id'=>$receive_id,
                'invoice_vat'=>$receiveDetails["invoice_vat"][$receive_id]??0,         // payable amount
                'vat'=>$receiveDetails["vat"][$receive_id]??0,                         // payable amount                
                'pay_amount'=>$receiveDetails["pay_amount"][$checked]??0
            ]);
           
        }

        foreach ($receiveDetails['receive_ids'] as $checked => $receive_id) {
            $receive = PurchaseOrderReceive::find($receive_id);
            if($receive->receive_due == 0){
                $receive->update(['status'=>'3']);
            }
        }
        $this->makeTransaction($supplierPayment);
        
        // dd($result);
        DB::commit();
        return  $result;
    }
    public function makeTransaction(SupplierPayment $supplierPayment){
        $account = Account::find($supplierPayment->account_id);
        $supplierPayableAccount = $supplierPayment->supplier->getAccount();
        $supplierAdvanceAccount = $supplierPayment->supplier->getAdvanceAccount();
// dd($supplierAdvanceAccount, $supplierPayableAccount);
        $description = 'Supplier Payment';

            //bank  Debit
            $this->transactionService->storeTransaction(
                SupplierPayment::class, 
                $supplierPayment->id,
                null, 
                $account->id, 
                -$supplierPayment->total_amount, 
                0, 
                $supplierPayment->total_amount, 
                'credit', 
                $description
            );
       
            if($supplierPayment->previous_advance){
                //Advance from Customer Debit
                $this->transactionService->storeTransaction(
                    SupplierPayment::class, 
                    $supplierPayment->id,
                    null, 
                    $supplierAdvanceAccount->id, 
                    -$supplierPayment->previous_advance, 
                    0, 
                    $supplierPayment->previous_advance, 
                    'credit', 
                    $description
                );
            }

        $total_payable = $supplierPayment->total_amount + $supplierPayment->previous_advance - $supplierPayment->advance_amount;
        //customer receivable Credit
        $this->transactionService->storeTransaction(
            SupplierPayment::class, 
            $supplierPayment->id,
            null, 
            $supplierPayableAccount->id, 
            $total_payable,
            $total_payable,
            0, 
            'debit', 
            $description
        );


        if($supplierPayment->advance_amount){
            //Advance from Customer Credit
            $this->transactionService->storeTransaction(
                SupplierPayment::class, 
                $supplierPayment->id,
                null, 
                $supplierAdvanceAccount->id, 
                $supplierPayment->advance_amount, 
                $supplierPayment->advance_amount,
                0,
                'debit', 
                $description
            );
        }

        // dd($supplierPayment->accountTransactions);
        $supplierPayment->verifyTransactions();        
    }
    public function update(SupplierPayment $supplierPayment, array $data)
    {
        $supplierPayment->update($data);
        return $supplierPayment;
    }

    public function delete(SupplierPayment $supplierPayment)
    {
        $supplierPayment->delete();
    }

    public function show($id)
    {
        return SupplierPayment::findOrFail($id);
    }
}
