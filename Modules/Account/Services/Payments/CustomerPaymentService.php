<?php

namespace Modules\Account\Services\Payments;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\Payments\CustomerPayment;
use Modules\Account\Models\Payments\CustomerPaymentDetail;
use Modules\Account\Models\Payments\CustomerPaymentInvoice;
use Modules\Account\Services\AccountTransactionService;
use Modules\Sales\Models\SaleInvoice;

class CustomerPaymentService
{
    
    private $transactionService;
    
    public function __construct(AccountTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    
    public function getAll(int $limit = 20) {
        return CustomerPayment::query()->paginate($limit);
    }
    
    public function store(array $data, array $invoiceDetails, array $invoiceDetailProduct)
    {
        DB::beginTransaction();
        $customerPayment = CustomerPayment::create($data);
        $result['customerPayment'] = $customerPayment;
        foreach ($invoiceDetails['invoice_ids'] as $checked => $invoice_id) {
             $customerPaymentDetail =CustomerPaymentDetail::create( [
                'customer_payment_id'=>$customerPayment->id,
                'invoice_id'=>$invoice_id,
                'invoice_vat'=>$invoiceDetails["invoice_vat"][$invoice_id]??0,         // payable amount
                'vat'=>$invoiceDetails["vat"][$invoice_id]??0,                         // payable amount                
                'pay_amount'=>$invoiceDetails["pay_amount"][$checked]??0
            ]);
            $result['customerPaymentDetails'][] =$customerPaymentDetail;
            // dd($invoiceDetailProduct);
            foreach($invoiceDetailProduct['product_ids'][$invoice_id] as $key => $product_id) {
                $customerPaymentInvoice = CustomerPaymentInvoice::create([
                    'customer_payment_detail_id'=> $customerPaymentDetail->id,
                    'invoice_id'=> $invoice_id,
                    'product_id'=> $product_id,
                    'invoice_quantity'=> $invoiceDetailProduct['invoice_qtys'][$invoice_id][$key],
                    'quantity'=> $invoiceDetailProduct['quantities'][$invoice_id][$key],
                    'unit_price'=> $invoiceDetailProduct['prices'][$invoice_id][$key],
                    'unit_discount'=> $invoiceDetailProduct['unit_discount'][$invoice_id][$key],
                ]);
                $result[$customerPaymentDetail->id]['customerPaymentInvoices'] = $customerPaymentInvoice;
            }
        }

        foreach ($invoiceDetails['invoice_ids'] as $checked => $invoice_id) {
            $invoice = SaleInvoice::find($invoice_id);
            if($invoice->invoice_due == 0){
                $invoice->update(['status'=>'paid']);
            }
        }
        $this->makeTransaction($customerPayment);
        
        // dd($result);
        DB::commit();
        return  $result;
    }

    public function makeTransaction(CustomerPayment $customerPayment){
        // dd($customerPayment);
        $bankAccount = Account::find($customerPayment->account_id);
        $customerReceivableAccount = $customerPayment->customer->getAccount();
        $customerAdvanceAccount = $customerPayment->customer->getAdvanceAccount();

        $description = 'Customer Payment Received';

        if($customerPayment->previous_advance){
            //Advance from Customer Debit
            $this->transactionService->storeTransaction(
                CustomerPayment::class, 
                $customerPayment->id,
                null, 
                $customerAdvanceAccount->id, 
                -$customerPayment->previous_advance, 
                $customerPayment->previous_advance, 
                0, 
                'debit', 
                $description
            );
        }
        //bank  Debit
        $this->transactionService->storeTransaction(
            CustomerPayment::class, 
            $customerPayment->id,
            null, 
            $bankAccount->id, 
            -$customerPayment->total_amount, 
            $customerPayment->total_amount, 
            0, 
            'debit', 
            $description
        );

        $total_receivable = $customerPayment->total_amount + $customerPayment->previous_advance - $customerPayment->advance_amount;
        //customer receivable Credit
        $this->transactionService->storeTransaction(
            CustomerPayment::class, 
            $customerPayment->id,
            null, 
            $customerReceivableAccount->id, 
            $total_receivable,
            0, 
            $total_receivable,
            'credit', 
            $description
        );

        if($customerPayment->advance_amount){
            //Advance from Customer Credit
            $this->transactionService->storeTransaction(
                CustomerPayment::class, 
                $customerPayment->id,
                null, 
                $customerAdvanceAccount->id, 
                $customerPayment->advance_amount, 
                0,
                $customerPayment->advance_amount,
                'credit', 
                $description
            );
        }

        // dd($customerPayment->accountTransactions);
        $customerPayment->verifyTransactions();        
    }

/*  public function makeTransaction(SaleInvoice $saleInvoice)
    {
        $transactionable_type = SaleInvoice::class;
        $transactionable_id = $saleInvoice->id;
        $customerAccount = $saleInvoice->customer->getAccount();         
        $invoice_no = $saleInvoice->invoice_id;

        // Generate a clickable link for the invoice
        $invoice_link = '<a href="' . route('sales.sales-invoices.show', $saleInvoice->id) . '"> #' . $invoice_no . '</a>';
        $description = $invoice_link . '</br>' . ' Invoice Payment';

        $bankAccount = Account::where('account_number', '1100')->first(); // Assuming 1100 is the bank account number

        $this->transactionService->storeTransaction(
            $transactionable_type, 
            $transactionable_id, 
            $invoice_no, 
            $customerAccount->id, 
            -$saleInvoice->net_amount, 
            $saleInvoice->net_amount, 
            0, 
            'debit', 
            $description
        );

        $this->transactionService->storeTransaction(
            $transactionable_type, 
            $transactionable_id, 
            $invoice_no, 
            $bankAccount->id, 
            $saleInvoice->net_amount, 
            0, 
            $saleInvoice->net_amount, 
            'credit', 
            $description
        );

        if ($saleInvoice->vat) { 
            $this->transactionService->storeTransaction(
                $transactionable_type, 
                $transactionable_id, 
                $invoice_no, 
                $customerAccount->id, 
                -$saleInvoice->vat, 
                $saleInvoice->vat, 
                0, 
                'debit', 
                $description . ' VAT'
            );

            $this->transactionService->storeTransaction(
                $transactionable_type, 
                $transactionable_id, 
                $invoice_no, 
                $bankAccount->id, 
                $saleInvoice->vat, 
                0, 
                $saleInvoice->vat, 
                'credit', 
                $description . ' VAT'
            );
        }
    } */

    public function update(CustomerPayment $customerPayment, array $data)
    {
        $customerPayment->update($data);
        return $customerPayment;
    }

    public function delete(CustomerPayment $customerPayment)
    {
        $customerPayment->delete();
    }

    public function show($id)
    {
        return CustomerPayment::findOrFail($id);
    }
}
