<?php

namespace Modules\Account\Services\Payments;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Payments\BrokerPayment;
use Modules\Sales\Models\SalesCommission;

class BrokerPaymentService
{
    public function getAll(int $limit = 20)
    {
        return BrokerPayment::query()
            ->searchByFields(['broker_id', 'type'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('commission_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('commission_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            })
            ->paginate($limit);
    }

    public function store(array $data)
    {
       
        $storedPayments = [];

        if (!empty($data['ids'])) {
            foreach ($data['ids'] as $key => $id) {
                // Only store if remaining_amount is > 0
                $paymentAmount = floatval(str_replace(',', '', $data['remaining_amount'][$key] ?? 0));

                if ($paymentAmount > 0) {
                    $brokerPayment = BrokerPayment::create([
                        'sales_commission_id' => $id,
                        'broker_payment_bank_id' => $data['broker_payment_bank_id'][$key] ?? null, 
                        'attachment_name' => request()->input('attachment_name_'.$id) ?? null,
                        'payment_amount' => $paymentAmount,
                        'remarks' => $data['remarks'] ?? null,
                    ]);

                    $storedPayments[] = $brokerPayment;
                }
            }
        }

        return $storedPayments;
    }

    public function approve($id)
    {
        return DB::transaction(function () use ($id) {
            $payment = BrokerPayment::findOrFail($id);

            $payment->approved_by = auth()->id();
            $payment->status = 'Approved';
            $payment->save();

            // Call dummy transaction
            if ($payment->status === 'Approved') {
                $this->makeDummyTransaction($payment);
            }

            return $payment;
        });
    }

    public function makeDummyTransaction(BrokerPayment $payment)
    {
        $payment->transactions()->delete();

        // accounts
        $payableAccount = $payment->salesCommission->broker->getAccount();
        $cashAccount = BankAccount::where('payment_mode', 'Cash')->first()->getAccount();
        //debit
        $payment->transactions()->create([
            'account_id' => $payableAccount->id,
            'balance_type' => 'debit',
            'invoice_no' => $payment->id,
            'amount' => $payment->payment_amount,
            'debit_amount' => $payment->payment_amount,
            'credit_amount' => 0,
            'description' => 'Commission Payment Created. #' . $payment->id,
        ]);

        //credit

        $payment->transactions()->create([
            'account_id' => $cashAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $payment->id,
            'amount' => -$payment->payment_amount,
            'debit_amount' => 0,
            'credit_amount' => $payment->payment_amount,
            'description' => 'Commission Payment Created. #' . $payment->id,
        ]);

        // dd($payment->bank->getAccount());
    }

    public function update(BrokerPayment $brokerPayment, array $data)
    {
        $brokerPayment->update($data);
        return $brokerPayment;
    }

    public function delete(BrokerPayment $brokerPayment)
    {
        $brokerPayment->delete();
    }

    public function show($id)
    {
        return BrokerPayment::findOrFail($id);
    }
}
