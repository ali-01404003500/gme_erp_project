<?php

namespace Modules\Account\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\InvoiceWisePayment;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;

class InvoiceWisePaymentService
{
    /**
     * Get all invoice wise payments with pagination
     */
    public function getAll(int $limit = 20)
    {
        return InvoiceWisePayment::with('paymentTo')->latest()->paginate($limit);
    }

    /**
     * Generate unique payment ID
     */
    public function getInvoiceWisePaymentId()
    {
        $today = date('Y-m-d');
        $authUser = auth()->user()->id;
        $countToday = InvoiceWisePayment::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        return sprintf(
            'IWP-%s-%06d-%06d',
            date('Ymd'),
            $authUser,
            $countToday + 1
        );
    }

    /**
     * Store new invoice wise payment
     */
    public function store(array $data, array $paymentsData)
    {
        DB::beginTransaction();
        try {
            $totalPaidAmount = collect($data['pay_amount'])->sum();
            // Create invoice wise payment
            $invoiceWisePayment = InvoiceWisePayment::create([
                'invoice_wise_payment_id' => $this->getInvoiceWisePaymentId(),
                'payment_to_type' =>  $data['payment_to_type'],
                'payment_to_id' => $data['payment_to_id'],
                'total_amount' => $totalPaidAmount,
                'status' => $data['status'] ?? 'pending',
            ]);

            // Create payment details (payment methods)
            foreach ($paymentsData['payments_pay_mode'] ?? [] as $key => $payMode) {
                if ($payMode && !empty($paymentsData['payments_amount'][$key])) {
                    $invoiceWisePayment->payments()->create([
                        'pay_mode' => $payMode,
                        'bank_id' => $paymentsData['payments_bank_id'][$key] ?? null,
                        'transaction_id' => $paymentsData['payments_transaction_id'][$key] ?? null,
                        'amount' => $paymentsData['payments_amount'][$key] ?? 0,
                        'date' => $paymentsData['payments_date'][$key] ?? null,
                        'attachments' => $paymentsData['payments_attachments'][$key] ?? null,
                        'verified' => $paymentsData['payments_verified'][$key] ?? false,
                        'remark' => $paymentsData['payments_remark'][$key] ?? null,
                    ]);
                }
            }

            // Associate invoices with payment
            foreach ($data['invoice_ids'] as $index => $invoiceId) {
                $payAmountForInvoice = $data['pay_amount'][$index] ?? 0;
                if ($payAmountForInvoice > 0) {
                    $invoiceType = $data['invoice_types'][$index];
                    $invoiceWisePayment->invoices()->create([
                        'invoice_type' => $invoiceType,
                        'invoice_id' => $invoiceId,
                        'amount' => $payAmountForInvoice,
                    ]);
                }
            }

            if ($data['status'] === 'verified') {
                $invoiceWisePayment->update([
                    'verified_by' => auth()->user()->id,
                ]);
            }
            
            if ($data['status'] === 'approved') {
                $invoiceWisePayment->update([
                    'verified_by' => auth()->user()->id,
                    'approved_by' => auth()->user()->id,
                ]);
            }

            // Create accounting transactions if approved
            if ($invoiceWisePayment->status === 'approved') {
                
                $this->makeDummyTransaction($invoiceWisePayment);
            }

            DB::commit();
            return $invoiceWisePayment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Show single invoice wise payment
     */
    public function show($id)
    {
        return InvoiceWisePayment::with([
            'paymentTo', 
            'payments', 
            'invoices.invoice', 
            'transactions'
        ])->findOrFail($id);
    }

    /**
     * Update invoice wise payment
     */
    public function update(InvoiceWisePayment $invoiceWisePayment, array $data, array $payments = [])
    {
        DB::beginTransaction();
        try {
            $oldStatus = $invoiceWisePayment->status;
            $totalAmount = $data['total_amount'] ?? collect($data['pay_amount'] ?? [])->sum();
            $data['payment_to_type'] = $data['payment_to_type'] == 'supplier' ? Supplier::class : Vendor::class;
            // Update main payment record
            $invoiceWisePayment->update([
                'payment_to_type' => $data['payment_to_type'],
                'payment_to_id' => $data['payment_to_id'],
                'total_amount' => $totalAmount,
                'status' => $data['status'] ?? $invoiceWisePayment->status,
            ]);

            // Update payment details - remove old ones not in submission
            $existingPaymentIds = $invoiceWisePayment->payments->pluck('id')->all();
            $submittedPaymentIds = collect($payments['payments_id'] ?? [])->filter()->all();
            $paymentsToDelete = array_diff($existingPaymentIds, $submittedPaymentIds);

            if (!empty($paymentsToDelete)) {
                $invoiceWisePayment->payments()->whereIn('id', $paymentsToDelete)->delete();
            }

            // Update or create payment details
            foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
                if ($payMode && !empty($payments['payments_amount'][$key])) {
                    $paymentData = [
                        'pay_mode' => $payMode,
                        'amount' => $payments['payments_amount'][$key] ?? 0,
                        'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                        'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                        'date' => $payments['payments_date'][$key] ?? null,
                        'attachments' => $payments['payments_attachments'][$key] ?? null,
                        'verified' => $payments['payments_verified'][$key] ?? false,
                        'remark' => $payments['payments_remark'][$key] ?? null,
                    ];

                    $invoiceWisePayment->payments()->updateOrCreate(
                        ['id' => $payments['payments_id'][$key] ?? null],
                        $paymentData
                    );
                }
            }

            // Update invoice associations
            $invoiceWisePayment->invoices()->delete();
            
            foreach ($data['invoice_ids'] as $index => $invoiceId) {
                $payAmountForInvoice = $data['pay_amount'][$index] ?? 0;
                if ($payAmountForInvoice > 0) {
                    $invoiceType = $data['invoice_types'][$index];
                    $invoiceWisePayment->invoices()->create([
                        'invoice_type' => $invoiceType,
                        'invoice_id' => $invoiceId,
                        'amount' => $payAmountForInvoice,
                    ]);
                }
            }

            // Handle status changes and transactions
            if ($oldStatus !== 'approved' && $invoiceWisePayment->status === 'approved') {
                // Status changed to approved - create transactions
                $this->makeDummyTransaction($invoiceWisePayment);
            } elseif ($oldStatus === 'approved' && $invoiceWisePayment->status !== 'approved') {
                // Status changed from approved - delete transactions
                $invoiceWisePayment->transactions()->delete();
            } elseif ($invoiceWisePayment->status === 'approved') {
                // Already approved, update transactions
                $this->makeDummyTransaction($invoiceWisePayment);
            }

            DB::commit();
            return $invoiceWisePayment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create accounting transactions for approved payment
     */
    public function makeDummyTransaction(InvoiceWisePayment $invoiceWisePayment)
    {
        //dd($invoiceWisePayment);
        // Delete existing transactions
        $invoiceWisePayment->transactions()->delete();
        
        // Get payment to (supplier/vendor) accounts
        $paymentTo = $invoiceWisePayment->paymentTo;
        $payableAccount = $paymentTo->getAccount();
        $advanceAccount = $paymentTo->getAdvanceAccount();

      
        
        $totalAmount = $invoiceWisePayment->total_amount;
        $payableAmount = $totalAmount ;
        
        // Calculate advance payment
        $totalInvoicesDue = $invoiceWisePayment->invoices->sum('amount');
        $advanceAmount = $totalAmount > $totalInvoicesDue ? ($totalAmount - $totalInvoicesDue) : 0;
        $actualPayableAmount = $totalAmount - $advanceAmount;

        if ($payableAmount <= 0) {
            return;
        }

        // DEBIT: Accounts Payable (reduces liability)
        if ($actualPayableAmount > 0) {
            $invoiceWisePayment->transactions()->create([
                'account_id' => $payableAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $invoiceWisePayment->invoice_wise_payment_id,
                'amount' => $actualPayableAmount,
                'debit_amount' => $actualPayableAmount,
                'credit_amount' => 0,
                'description' => 'Payment Created. #' . $invoiceWisePayment->invoice_wise_payment_id,
            ]);
        }

        // DEBIT: Advance Account (if overpayment)
        if ($advanceAmount > 0) {
            $invoiceWisePayment->transactions()->create([
                'account_id' => $advanceAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $invoiceWisePayment->invoice_wise_payment_id,
                'amount' => $advanceAmount,
                'debit_amount' => $advanceAmount,
                'credit_amount' => 0,
                'description' => 'Advance Payment Created. #' . $invoiceWisePayment->invoice_wise_payment_id,
            ]);
        }

        // CREDIT: Bank/Cash Accounts (reduces assets)
        foreach ($invoiceWisePayment->payments as $payment) {
            if ($payment->bank) {
                $invoiceWisePayment->transactions()->create([
                    'account_id' => $payment->bank->getAccount()->id,
                    'balance_type' => 'credit',
                    'invoice_no' => $invoiceWisePayment->invoice_wise_payment_id,
                    'amount' => -$payment->amount,
                    'debit_amount' => 0,
                    'credit_amount' => $payment->amount,
                    'description' => 'Payment Created. #' . $invoiceWisePayment->invoice_wise_payment_id,
                ]);
            }
        }


    }

  
    
    /**
     * Approve invoice wise payment
     */
    public function approve(InvoiceWisePayment $invoiceWisePayment)
    {
        DB::beginTransaction();
        try {
            if ($invoiceWisePayment->status !== 'approved') {
                $invoiceWisePayment->update([
                    'status' => 'approved',
                    'approved_by' => auth()->user()->id ?? null,
                ]);
                
                $this->makeDummyTransaction($invoiceWisePayment);
            }
            
            DB::commit();
            return $invoiceWisePayment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete invoice wise payment
     */
    public function delete(InvoiceWisePayment $invoiceWisePayment)
    {
        DB::transaction(function () use ($invoiceWisePayment) {
            $invoiceWisePayment->transactions()->delete();
            $invoiceWisePayment->payments()->delete();
            $invoiceWisePayment->invoices()->delete();
            $invoiceWisePayment->delete();
        });
    }
}