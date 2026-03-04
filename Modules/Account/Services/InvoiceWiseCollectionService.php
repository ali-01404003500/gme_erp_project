<?php

namespace Modules\Account\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\InvoiceWiseCollection;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Customer;
use Modules\Sales\Models\SalesOrder;

class InvoiceWiseCollectionService
{
    public function getAll(int $limit = 20)
    {
        return InvoiceWiseCollection::
        with(['customer', 'verifiedBy', 'approvedBy', 'payments'])
        ->filterByDateRange('created_at')
        ->latest()
        ->paginate($limit);
    }

    public function getInvoiceWiseCollectionId()
    {
        $today = date('Y-m-d');
        $authUser = auth()->user()->id;
        $countToday = InvoiceWiseCollection::withTrashed()
            ->whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        return sprintf(
            'PR-%s-USR-%06d-%06d',
            date('Ymd'),
            $authUser,
            $countToday + 1
        );
    }

    public function store(array $data, array $paymentsData)
    {
        DB::beginTransaction();
        // dd($data, $paymentsData);
        try {
            $totalPaidAmount = 0;collect($data['pay_amount'])->sum();

            foreach ($data['pay_amount'] as $key => $amount) {
                if(
                    in_array($key   , $data['checked_invoices']) &&
                    $amount > 0
                ){
                    $totalPaidAmount += $amount;

                }
            }
            $status = $data['status'] ?? 'pending';
            $invoiceWiseCollection = InvoiceWiseCollection::create([
                'invoice_wise_collection_id' => $this->getInvoiceWiseCollectionId(),
                'customer_id' => $data['collection_from'],
                'total_amount' => $totalPaidAmount,
                'status' => $status,
                'verified_by' => $status === 'verified' ? auth()->id() : null,
                'approved_by' => $status === 'approved' ? auth()->id() : null,
            ]);

            // Create payments for the invoice-wise collection
            foreach ($paymentsData['payments_pay_mode'] ?? [] as $key => $payMode) {
                if ($payMode && !empty($paymentsData['payments_amount'][$key])) {
                    $invoiceWiseCollection->payments()->create([
                        'pay_mode' => $payMode,
                        'bank_id' => $paymentsData['payments_bank_id'][$key] ?? null,
                        'branch_id' => $paymentsData['payments_branch_id'][$key] ?? null,
                        'transaction_id' => $paymentsData['payments_transaction_id'][$key] ?? null,
                        'e_m_i_entries_id' => $paymentsData['payments_emi_id'][$key] ?? null,
                        'amount' => $paymentsData['payments_amount'][$key] ?? 0,
                        'date' => $paymentsData['payments_date'][$key] ?? null,
                        'attachments' => $paymentsData['payments_attachments'][$key] ?? null,
                        'verified' => $paymentsData['payments_verified'][$key] ?? false,
                        'remarks' => $paymentsData['payments_remark'][$key] ?? null,
                    ]);
                }
            }

            // Associate sales orders with the invoice-wise collection
            foreach ($data['sales_order_ids'] as $index => $salesOrderId) {
                $payAmountForOrder = $data['pay_amount'][$index] ?? ($data['pay_amount'][$salesOrderId] ?? 0);
                if ($payAmountForOrder > 0 && in_array($salesOrderId, $data['checked_invoices'])) {
                    $salesOrder = SalesOrder::find($salesOrderId);
                    if ($salesOrder) {
                        // Associate the sales order with the invoice-wise collection
                        $invoiceWiseCollection->salesOrders()->attach($salesOrderId, ['amount' => $payAmountForOrder]);

                        // Only create a payment record linked to the sales order if the status is approved
                        if ($invoiceWiseCollection->status === 'approved') {
                            $salesOrder->payments()->create([
                                'pay_mode' => 'Collection', // Indicates it's part of a larger collection
                                'amount' => $payAmountForOrder,
                                'date' => now()->toDateString(),
                                'remarks' => 'Payment from Invoice Wise Collection ID: ' . $invoiceWiseCollection->invoice_wise_collection_id,
                            ]);
                        }
                    }
                }
            }

            // Create dummy transaction if status is approved
            if ($invoiceWiseCollection->status === 'approved') {
                $this->makeDummyTransaction($invoiceWiseCollection);
                $invoiceWiseCollection->salesOrders()->update(['paid_status' => 'paid']);
            }

            DB::commit();
            return $invoiceWiseCollection;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        return InvoiceWiseCollection::with(['customer', 'payments', 'salesOrders', 'transactions'])->findOrFail($id);
    }

    public function update(InvoiceWiseCollection $invoiceWiseCollection, array $data, array $payments = [])
    {
        DB::beginTransaction();
        try {
            // Get the old status before update
            $oldStatus = $invoiceWiseCollection->status;

            $callAmt = 0;
            foreach ($data['pay_amount'] as $key => $amount) {
                if(
                    in_array($key   , $data['checked_invoices']) &&
                    $amount > 0
                ){
                    $callAmt  += $amount;

                }
            }
            // Calculate total amount from pay_amount array if total_amount is not provided
            $totalAmount = $data['total_amount'] ?? $callAmt;

            $status = $data['status'] ?? $invoiceWiseCollection->status;
            $updateData = [
                'total_amount' => $totalAmount,
                'status' => $status,
            ];

            if ($status === 'verified' && $invoiceWiseCollection->status !== 'verified') {
                $updateData['verified_by'] = auth()->id();
            }
            if ($status === 'approved' && $invoiceWiseCollection->status !== 'approved') {
                $updateData['approved_by'] = auth()->id();
            }

            $invoiceWiseCollection->update($updateData);

            // Get existing payment IDs to remove the ones not submitted
            $existingPaymentIds = $invoiceWiseCollection->payments->pluck('id')->all();
            $submittedPaymentIds = collect($payments['payments_id'] ?? [])->filter()->all();
            $paymentsToDelete = array_diff($existingPaymentIds, $submittedPaymentIds);

            if (!empty($paymentsToDelete)) {
                $invoiceWiseCollection->payments()->whereIn('id', $paymentsToDelete)->delete();
            }

            // Update or create payments
            foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
                if ($payMode && !empty($payments['payments_amount'][$key])) {
                    $paymentData = [
                        'pay_mode' => $payMode,
                        'amount' => $payments['payments_amount'][$key] ?? 0,
                        'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                        'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                        'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                        'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                        'date' => $payments['payments_date'][$key] ?? null,
                        'attachments' => $payments['payments_attachments'][$key] ?? null,
                        'verified' => $payments['payments_verified'][$key] ?? false,
                        'remarks' => $payments['payments_remark'][$key] ?? null,
                    ];

                    $invoiceWiseCollection->payments()->updateOrCreate(
                        ['id' => $payments['payments_id'][$key] ?? null],
                        $paymentData
                    );
                }
            }

            // Update sales order associations
            $invoiceWiseCollection->salesOrders()->detach(); // Remove all existing associations

            // Re-associate sales orders with the invoice-wise collection
            foreach ($data['sales_order_ids'] as $index => $salesOrderId) {
                $payAmountForOrder = $data['pay_amount'][$index] ?? ($data['pay_amount'][$salesOrderId] ?? 0);
                if ($payAmountForOrder > 0 && in_array($salesOrderId, $data['checked_invoices'])) {
                    $salesOrder = SalesOrder::find($salesOrderId);
                    if ($salesOrder) {
                        // Associate the sales order with the invoice-wise collection
                        $invoiceWiseCollection->salesOrders()->attach($salesOrderId, ['amount' => $payAmountForOrder]);

                        // Recalculate paid amount and update paid_status
                        $totalPaid = $salesOrder->payments()->sum('amount');
                        $newStatus = 'unpaid';
                        if ($totalPaid >= $salesOrder->net_amount) {
                            $newStatus = 'paid';
                        } elseif ($totalPaid > 0) {
                            $newStatus = 'due';
                        }

                        $salesOrder->paid_status = $newStatus;
                        $salesOrder->save();
                    }
                }
            }


            if ($invoiceWiseCollection->status === 'approved') {

                $this->addSalesOrderPayments($invoiceWiseCollection);
                // Status changed to approved, create transactions
                $this->makeDummyTransaction($invoiceWiseCollection);
            }

            // // Handle status changes: if status changed from approved to something else, remove payments from sales orders
            // if ($oldStatus === 'approved' && $invoiceWiseCollection->status !== 'approved') {
            //     $this->removeSalesOrderPayments($invoiceWiseCollection);
            // }
            // // If status changed to approved from something else, add payments to sales orders
            // elseif ($oldStatus !== 'approved' && $invoiceWiseCollection->status === 'approved') {
            //     $this->addSalesOrderPayments($invoiceWiseCollection);
            // }

            // // Handle transaction creation/removal based on status
            // if ($oldStatus !== 'approved' &&  elseif ($oldStatus === 'approved' && $invoiceWiseCollection->status !== 'approved') {
            //     // Status changed from approved, delete transactions
            //     $invoiceWiseCollection->transactions()->delete();
            // }

            DB::commit();
            return $invoiceWiseCollection;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Add payments to sales orders when collection is approved
     */
    private function addSalesOrderPayments(InvoiceWiseCollection $invoiceWiseCollection)
    {
        foreach ($invoiceWiseCollection->salesOrders as $salesOrder) {
            // Get the pivot amount for this specific sales order
            $pivot = DB::table('invoice_wise_collection_sales_order')
                ->where('invoice_wise_collection_id', $invoiceWiseCollection->id)
                ->where('sales_order_id', $salesOrder->id)
                ->first();

            $pivotAmount = $pivot ? $pivot->amount : 0;

            if ($pivotAmount > 0) {
                $salesOrder->payments()->create([
                    'pay_mode' => 'Collection', // Indicates it's part of a larger collection
                    'amount' => $pivotAmount,
                    'date' => now()->toDateString(),
                    'remarks' => 'Payment from Invoice Wise Collection ID: ' . $invoiceWiseCollection->invoice_wise_collection_id,
                ]);
            }
        }
    }

    /**
     * Remove payments from sales orders when collection is no longer approved
     */
    private function removeSalesOrderPayments(InvoiceWiseCollection $invoiceWiseCollection)
    {
        foreach ($invoiceWiseCollection->salesOrders as $salesOrder) {
            // Find and delete payments that were created from this collection
            $collectionPayments = $salesOrder->payments()
                ->where('pay_mode', 'Collection')
                ->where('remarks', 'like', '%Payment from Invoice Wise Collection ID: ' . $invoiceWiseCollection->invoice_wise_collection_id . '%')
                ->get();

            foreach ($collectionPayments as $payment) {
                $payment->delete();
            }
        }
    }

    public function approve(InvoiceWiseCollection $invoiceWiseCollection)
    {
        DB::beginTransaction();
        try {
            // Only approve if not already approved
            if ($invoiceWiseCollection->status !== 'approved') {
                $oldStatus = $invoiceWiseCollection->status;
                $invoiceWiseCollection->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id()
                ]);

                // Add payments to sales orders since the collection is now approved
                $this->addSalesOrderPayments($invoiceWiseCollection);

                // Create dummy transaction for approved collection
                $this->makeDummyTransaction($invoiceWiseCollection);
            }

            DB::commit();
            return $invoiceWiseCollection;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Here is a dummy transaction example
     * | Date       | Account                           | Debit (৳) | Credit (৳) |
     * | ---------- | --------------------------------- | --------- | ---------- |
     * | 2025-08-06 | Accounts Receivable - Customer -1 | 10,000    |            |
     * |            | Sales Revenue                     |           | 10,000     |
     *
     * When customer pay 10,000 with cash, then this will be the transaction
     *
     * | Date       | Account                           | Debit (৳) | Credit (৳) |
     * | ---------- | --------------------------------- | --------- | ---------- |
     * | 2025-08-06 | Accounts Receivable - Customer -1 |           | 10,000     |
     * |            | Cash                              | 10,000    |            |
     *
     */
    public function makeDummyTransaction(InvoiceWiseCollection $invoiceWiseCollection)
    {
        // Delete existing transactions for this collection
        $invoiceWiseCollection->transactions()->delete();

        // Get customer account receivable
        $customer = $invoiceWiseCollection->customer;
        $customerReceivableAccount = $customer->getAccount();

        $chequeAndEmiAmount = $invoiceWiseCollection->payments()->whereIn('pay_mode', ['Cheque', 'EMI','Online Deposit','bKash'])->sum('amount');

        $receivableCreditAmount = $invoiceWiseCollection->total_amount - $chequeAndEmiAmount;

        if ($chequeAndEmiAmount > 0) {

            foreach ($invoiceWiseCollection->payments as $payment) {
                if ($payment->pay_mode == 'Cheque') {
                    // cheque entry
                    if ($payment->chequeVerification) {
                        // update
                        $payment->chequeVerification()->update([
                            'customer_id' => $customer->id,
                            'bank_id' => $payment->bank->id,
                            'branch_id' => $payment->branch->id,
                            'cheque_no' => $payment->transaction_no,
                            'cheque_date' => $payment->date,
                            'amount' => $payment->amount,
                        ]);
                    } else {
                        $payment->chequeVerification()->create([
                            'customer_id' => $customer->id,
                            'bank_id' => $payment->bank_id,
                            'branch_id' => $payment->branch_id,
                            'cheque_no' => $payment->transaction_id,
                            'cheque_date' => $payment->date,
                            'amount' => $payment->amount,
                            "document" => $payment->attachments,
                            "remarks" => $payment->remarks,
                        ]);
                        $payment->load('chequeVerification');
                        // dd($payment->chequeVerification,  $cheqye, get_class($payment), $payment);
                    }
                    //  dd($payment->chequeVerification, $payment, get_class($payment));
                }
                else if ($payment->pay_mode == 'Online Deposit') {
                    // Online Deposit entry
                    if ($payment->onlineDepositVerification) {
                        // update
                        $payment->onlineDepositVerification()->update([
                            'customer_id' => $customer->id,
                            'head_id' => $payment->bank->id, 
                            'deposit_date' => $payment->date,
                            'amount' => $payment->amount,
                        ]);
                    } else {
                        $payment->onlineDepositVerification()->create([
                            'customer_id' => $customer->id,
                            'head_id' => $payment->bank_id,  
                            'deposit_date' => $payment->date,
                            'amount' => $payment->amount,
                            "document" => $payment->attachments,
                            "remarks" => $payment->remarks,
                        ]);
                        $payment->load('onlineDepositVerification');
                          
                    }
                   
                } 
                else if ($payment->pay_mode == 'bKash') {
                    // bKash
                    if ($payment->mfsVerification) {
                        // update
                        $payment->mfsVerification()->update([
                            'customer_id' => $customer->id,
                            'head_id' => $payment->bank->id, 
                            'transaction_no' => $payment->transaction_no,
                            'transaction_date' => $payment->date,
                            'amount' => $payment->amount,
                        ]);
                    } else {
                        $payment->mfsVerification()->create([
                            'customer_id' => $customer->id,
                            'head_id' => $payment->bank_id, 
                            'transaction_no' => $payment->transaction_id,
                            'transaction_date' => $payment->date,
                            'amount' => $payment->amount,
                            "document" => $payment->attachments,
                            "remarks" => $payment->remarks,
                        ]);
                        $payment->load('mfsVerification');
                          
                    }
                   
                }
                else if ($payment->pay_mode == 'EMI') {
                    // emi update
                    if ($payment->bank) {
                        // dd($collection->source,$payment->bank);
                        $payment->bank->restore();
                        $payment->bank->update([
                            'deleted_by' => null,
                        ]);

                        // dd($payment->bank);
                    }
                    // $payment->emiEntry
                    // dd($payment,  $payment->bank, $payment->bank->emiDetails);
                }
            }
        }
        if ($receivableCreditAmount <= 0) {
            $receivableCreditAmount = 0;
            // return ;
        }

        // accounts
        // $customerReceivableAccount = $invoiceWiseCollection->collectionFrom->getAccount();
        // $collection->transactions()->create([
        //     'account_id' => $customerReceivableAccount->id,
        //     'balance_type' => 'credit',
        //     'invoice_no' => $collection->collection_id,
        //     'amount' => $receivableCreditAmount,
        //     'debit_amount' => 0,
        //     'credit_amount' => $receivableCreditAmount,
        //     'description' => 'Collection Created',
        // ]);
        // dd  ($invoiceWiseCollection->payments()->whereNotIn('pay_mode', ['Cheque', 'EMI'])->get());
        // Debit transactions for each payment method
        foreach ($invoiceWiseCollection->payments()->whereNotIn('pay_mode', ['Cheque', 'EMI','Online Deposit','bKash'])->get() as $payment) {
            if ($payment->bank) {
                $invoiceWiseCollection->transactions()->create([
                    'account_id' => $payment->bank->getAccount()->id,
                    'balance_type' => 'debit',
                    'invoice_no' => $invoiceWiseCollection->invoice_wise_collection_id,
                    'amount' => $payment->amount,
                    'debit_amount' => $payment->amount,
                    'credit_amount' => 0,
                    'description' => 'Invoice Wise Collection Payment',
                ]);
            }
        }

        // Credit transaction for customer receivable account
        $invoiceWiseCollection->transactions()->create([
            'account_id' => $customerReceivableAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $invoiceWiseCollection->invoice_wise_collection_id,
            'amount' => $receivableCreditAmount,
            'debit_amount' => 0,
            'credit_amount' => $receivableCreditAmount,
            'description' => 'Collection Created',
        ]);


    }

    public function delete(InvoiceWiseCollection $invoiceWiseCollection)
    {
        DB::transaction(function () use ($invoiceWiseCollection) {
            // Note: This does not automatically reverse payments on Sales Orders.
            // A more robust implementation would be needed for that.
            $invoiceWiseCollection->transactions()->delete();
            $invoiceWiseCollection->payments()->delete();
            $invoiceWiseCollection->delete();
        });
    }
}