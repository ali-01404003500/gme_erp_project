<?php

namespace Modules\Account\Services\VendorBill;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\VendorBill\GeneratedVendorBill;
use Modules\Purchase\Models\Vendor;

class GeneratedVendorBillService
{

    public function getAll(int $limit = 20)
    {
        return GeneratedVendorBill::query()
            ->whereIn('status', ['pending', 'verified', 'denied'])
            ->paginate($limit);
    }

    public function store(array $data)
    {
        return GeneratedVendorBill::create($data);
    }

    public function update(GeneratedVendorBill $generatedVendorBill, array $data)
    {
        DB::beginTransaction();
        $generatedVendorBill->update($data);
        $generatedVendorBill->refresh();
        if ($data['status'] == 'approved') {
            $this->makeDummyTransaction($generatedVendorBill);
        }

        DB::commit();
        return $generatedVendorBill;
    }


    /**
     * Here is a dummy transaction example
     * | Date       | Account                           | Debit (৳) | Credit (৳) |
     * | ---------- | --------------------------------- | --------- | ---------- |
     * | 2025-08-06 | Internet Bill - Vendor Name       | 2,000     |            |
     * |            | Expenses Payable - Vendor Name    |           | 2,000      |
     */
    public function makeDummyTransaction(GeneratedVendorBill $generatedVendorBill)
    {
        if (get_class($generatedVendorBill->billFor) == Vendor::class) {
            $generatedVendorBill->transactions()->delete();

            // accounts
            $expensesAccount = $generatedVendorBill->billFor->getExpenseAccount();
            $expensesPayableAccount = $generatedVendorBill->billFor->getExpensePayableAccount();
            //debit
            $generatedVendorBill->transactions()->create([
                'account_id' => $expensesAccount->id,
                'balance_type' => "debit",
                'invoice_no' => $generatedVendorBill->bill_id,
                'debit_amount' => $generatedVendorBill->amount,
                'credit_amount' => 0,
                'description' => "Bill Created. #" . $generatedVendorBill->bill_id
            ]);
            //credit
            $generatedVendorBill->transactions()->create([
                'account_id' => $expensesPayableAccount->id,
                'balance_type' => "credit",
                'invoice_no' => $generatedVendorBill->bill_id,
                'debit_amount' => 0,
                'credit_amount' => $generatedVendorBill->amount,
                'description' => "Bill Created. #" . $generatedVendorBill->bill_id
            ]);
        }
    }




    public function delete(GeneratedVendorBill $generatedVendorBill)
    {
        $generatedVendorBill->delete();
    }

    public function show($id)
    {
        return GeneratedVendorBill::findOrFail($id);
    }
}
