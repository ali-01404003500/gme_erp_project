<?php

namespace Modules\Purchase\Services;

use App\Traits\S3FileHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\RequisitionDetail;
use Modules\Purchase\Models\Supplier;

class RequisitionService
{
    use S3FileHandler;

    public function getAll(int $limit = 20) {
        // dd(Carbon::parse( request('from')));
        return Requisition::query()
        ->searchByFields(['requisition_no','customer_id','supplier_id','branch_id'])
        ->when(request()->filled('status'), function ($qr) {
            if(request('status') == 0){
                $qr->where('status', null);
            }else{
                $qr->where('status', request('status'));
            }
            
        })
        ->when(request()->filled('from'), function ($qr) {
            $qr->where('invoice_date', '>=', Carbon::parse( request('from'))->format('Y-m-d'));
        })
        ->when(request()->filled('to'), function ($qr) {
            $qr->where('invoice_date', '<=', Carbon::parse( request('to'))->format('Y-m-d'));
        })
        ->paginate($limit);
    }

    
    public function store(array $data,array $productDetails, $payments=[])
    {

        $result['requisition'] = Requisition::create($data);

        $result['productDetails'] = [];

        if (count($productDetails['product_ids']) > 0) {
            foreach ($productDetails['product_ids'] as $key => $value) {
                $result['productDetails'][] = RequisitionDetail::create([

                    'requisition_id' => $result['requisition']->id,
                    'product_id' => $productDetails['product_ids'][$key],
                    'price'=> $productDetails['price'][$key],
                    'sales_price'=> $productDetails['sales_price'][$key],
                    'quantity'=> $productDetails['quantity'][$key],
                    'amount'=> $productDetails['amount'][$key],
                ]);
            }
        }
    if (!empty($payments['payments_pay_mode'])) {
    foreach ($payments['payments_pay_mode'] as $key => $payment) {
        $paymentDetail = $result['requisition']->paymentDetails()->create([
            'pay_mode'       => $payments['payments_pay_mode'][$key] ?? null,
            'amount'         => $payments['payments_amount'][$key] ?? 0,
            'date'           => $payments['payments_date'][$key] ?? null,
            'bank_id'        => $payments['payments_bank_id'][$key] ?? null,
            'attachments'    => $payments['payments_attachments'][$key] ?? null,
            'verified'       => $payments['payments_verified'][$key] ?? false,
            'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
            'remark'         => $payments['payments_remark'][$key] ?? null,
        ]);
        $result['make_payment_details'][] = $paymentDetail;
    }
}



        return $result;
    }

    public function update(Requisition $requisition, array $data, array $productDetails, $payments=[])
    {


        $requisition->update($data);

        RequisitionDetail::where('requisition_id', $requisition->id)->delete();
    
        foreach ($productDetails['product_ids'] as $key => $value) {
            $productDetail = [
                'requisition_id' => $requisition->id,
                'product_id' => $value,
                'price' => $productDetails['price'][$key],
                'sales_price' => $productDetails['sales_price'][$key],	
                'quantity' => $productDetails['quantity'][$key],
                'amount' => $productDetails['amount'][$key],
            ];
            if (isset($requisition->productDetails[$key])) {
                $requisition->productDetails[$key]->update($productDetail);
            } else {
                RequisitionDetail::create($productDetail);
            }
        }
        $requisition->paymentDetails()->delete();
    if (!empty($payments['payments_pay_mode']) ) {

        foreach($payments['payments_pay_mode'] as $key => $payment){
            $paymentDetail = $requisition->paymentDetails()->create([
                'pay_mode' => $payments['payments_pay_mode'][$key] ?? null,
                'amount' => $payments['payments_amount'][$key] ?? 0,
                'date' => $payments['payments_date'][$key] ?? null,
                'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                'attachments' => $payments['payments_attachments'][$key] ?? null,
                'verified' => $payments['payments_verified'][$key] ?? false,
                'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                'remark' => $payments['payments_remark'][$key] ?? null,
            ]);
            $result['make_payment_details'][] = $paymentDetail;
        }
    }
    
        return $requisition;
    }
    

    public function delete(Requisition $requisition)
    {
        $requisition->delete();
    }

    public function show($id)
    {
        return Requisition::findOrFail($id);
    }

    public function approve(Requisition $requisition, array $data, array $productDetails){
        $requisition->update($data);
        // if($request->approve == '1') {
        //     $requisition->update([
        //         'status'=> 1,
        //         ]);
        //     } else if($request->reject == '2') {
        //         $requisition->update([
        //             'status'=> 2,
        //             ]);
        //     }
        RequisitionDetail::where('requisition_id', $requisition->id)->delete();
    
        foreach ($productDetails['product_ids'] as $key => $value) {
            $productDetail = [
                'requisition_id' => $requisition->id,
                'product_id' => $value,
                'price' => $productDetails['price'][$key],
                'sales_price' => $productDetails['sales_price'][$key],	
                'quantity' => $productDetails['quantity'][$key],
                'amount' => $productDetails['amount'][$key],
            ];
            if (isset($requisition->productDetails[$key])) {
                $requisition->productDetails[$key]->update($productDetail);
            } else {
                RequisitionDetail::create($productDetail);
            }
        }
    
        return $requisition;
    }




     /**
      * Here is a dummy transaction example for Purchase Order creation
      * | Date       | Account                           | Debit (৳) | Credit (৳) |
      * | ---------- | --------------------------------- | --------- | ---------- |
      * | 2025-08-06 | Accounts Payable - Supplier -1    | 25,000    |            |
      * |            | Purchase/Inventory Account        |           | 25,000     |
      *
      * When purchase order is created, this creates a liability to the supplier
      * and recognizes the future inventory/expense
      *
      * | Date       | Account                           | Debit (৳) | Credit (৳) |
      * | ---------- | --------------------------------- | --------- | ---------- |
      * | 2025-08-06 | Accounts Payable - Supplier -1    | 25,000    |            |
      * |            | Purchase/Inventory Account        |           | 25,000     |
      */
    public function makeDummyTransaction(Requisition $requisition)
    {



        // dd(  $requisition,$requisition->requisitionDetails);
        if(!$requisition->supplier){
            return;
        }

        //debit 
        foreach ($requisition->requisitionDetails as $requisitionDetail) {
            //Inventory Account
            $InventoryAccount = $requisitionDetail->product->getInventoryAccount();

            $requisition->transactions()->create([
                'account_id'            => $InventoryAccount->id,
                'balance_type'          => "debit",
                'invoice_no'            => $requisition->requisition_no,
                'debit_amount'          => $requisitionDetail->amount,
                'credit_amount'         => 0,
                'description'           => "Purchase Order Created. #" . $requisition->requisition_no,
                'transaction_date'      => $requisition->invoice_date
            ]);
        }

        //cre
// Accounts Payable
        $AccountsPayable = $requisition->supplier->getAccount();

        $requisition->transactions()->create([
            'account_id'            => $AccountsPayable->id,
            'balance_type'          => "credit",
            'invoice_no'            => $requisition->requisition_no,
            'debit_amount'          => 0,
            'credit_amount'         => $requisition->net_amount,
            'description'           => "Purchase Order Created. #" . $requisition->requisition_no,
                            'transaction_date'      => $requisition->invoice_date

        ]);
        // // Delete existing transactions
        // $requisition->transactions()->delete();

        // // Get supplier payable account
        // $supplierPayableAccount = $requisition->supplier->getAccount();

        // // Calculate total amount from requisition details
        // $totalAmount = $requisition->requisitionDetails->sum('amount');

        // // Debit: Accounts Payable (liability to supplier)
        // $requisition->transactions()->create([
        //     'account_id'            => $supplierPayableAccount->id,
        //     'balance_type'          => "debit",
        //     'invoice_no'            => $requisition->requisition_no,
        //     'debit_amount'          => $totalAmount,
        //     'credit_amount'         => 0,
        //     'description'           => "Purchase Order Created. #" . $requisition->requisition_no
        // ]);

        // // Credit: Purchase/Inventory account (asset or expense)
        // // Note: This would typically be an inventory account or purchase account
        // // For now, using a generic approach - this should be configured based on your chart of accounts
        // $purchaseAccount = $requisition->supplier->getPurchaseAccount(); // Assuming this method exists

        // if ($purchaseAccount) {
        //     $requisition->transactions()->create([
        //         'account_id'            => $purchaseAccount->id,
        //         'balance_type'          => "credit",
        //         'invoice_no'            => $requisition->requisition_no,
        //         'debit_amount'          => 0,
        //         'credit_amount'         => $totalAmount,
        //         'description'           => "Purchase Order Created. #" . $requisition->requisition_no
        //     ]);
        // }
    }




}
