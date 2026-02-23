<?php

namespace Modules\Account\Services;

use DB;
use Modules\Account\Models\CashTransfer;

class CashTransferService
{

    public function getAll(int $limit = 20)
    {
        $query = CashTransfer::with(['fromEmployee.bankAccount', 'toEmployee.bankAccount'])->orderBy('id', 'desc');

        if (!hasPermission('supper_admin')) {
            $currentEmployee = \Modules\HRMS\Models\Employee::where('user_id', auth()->id())->first();
            if ($currentEmployee) {
                $query->where(function ($q) use ($currentEmployee) {
                    $q->where('from_employee_id', $currentEmployee->id)
                        ->orWhere('to_employee_id', $currentEmployee->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        return $query->paginate($limit);
    }

    public function store(array $data)
    {
        if ($data['from_employee_id'] == $data['to_employee_id']) {
            throw \Illuminate\Validation\ValidationException::withMessages(['to_employee_id' => 'Sender and receiver cannot be the same.']);
        }

        $fromEmployee = \Modules\HRMS\Models\Employee::findOrFail($data['from_employee_id']);
        $balance = $fromEmployee->getAccount()->balance;

        if ($data['amount'] > $balance) {
            throw \Illuminate\Validation\ValidationException::withMessages(['amount' => 'Insufficient balance. Available: ' . $balance]);
        }

        $data['status'] = 'pending';
        return CashTransfer::create($data);
    }

    public function update(CashTransfer $cashTransfer, array $data)
    {
        DB::beginTransaction();
        try {
            if ($cashTransfer->status != 'pending') {
                throw \Illuminate\Validation\ValidationException::withMessages(['status' => 'Cannot update non-pending transfer.']);
            }

            if (isset($data['amount']) && $data['amount'] != $cashTransfer->amount) {
                $fromEmployee = $cashTransfer->fromEmployee;
                $balance = $fromEmployee->getAccount()->balance; // Check current balance again? Or balance + old_amount?
                // Ideally: available = current_balance + old_transfer_amount (since it's not deducted yet, wait, it's pending so not deducted).
                // Pending transfer doesn't deduct balance in journal yet.
                // But if there are multiple pending transfers, we might overshoot?
                // For now, simple check against current balance.
                if ($data['amount'] > $balance) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['amount' => 'Insufficient balance. Available: ' . $balance]);
                }
            }

            if (isset($data['status']) && $data['status'] == 'confirmed') {
                $this->confirm($cashTransfer, $data);
            }

            $cashTransfer->update($data);
            DB::commit();
            return $cashTransfer;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirm(CashTransfer $cashTransfer, array $data)
    {


        if ($data['status'] == 'confirmed') {
            $cashTransfer->update([
                'status' => 'confirmed',
            ]);
            $this->postJournalEntry($cashTransfer);
        } else {
            $cashTransfer->update([
                'status' => 'pending',
            ]);
        }

        return $cashTransfer;
    }

    private function postJournalEntry(CashTransfer $cashTransfer)
    {
        $fromAccount = $cashTransfer->fromEmployee->getAccount();
        $toAccount = $cashTransfer->toEmployee->getAccount();

        $cashTransfer->transactions()->delete();

        // Credit Sender (Asset decreases)
        $cashTransfer->transactions()->create([
            'account_id' => $fromAccount->id,
            'debit_amount' => $cashTransfer->amount,
            'credit_amount' => 0,
            'balance_type' => 'debit',
            'transaction_date' => $cashTransfer->transfer_date,
            'description' => 'Cash Transfer from ' . $cashTransfer->fromEmployee->full_name . " to " . $cashTransfer->toEmployee->full_name,
        ]);


        // Debit Receiver (Asset increases)
        $cashTransfer->transactions()->create([
            'account_id' => $toAccount->id,
            'debit_amount' => 0,
            'credit_amount' => $cashTransfer->amount,
            'balance_type' => 'credit',
            'transaction_date' => $cashTransfer->transfer_date,
            'description' => 'Cash Transfer from ' . $cashTransfer->fromEmployee->full_name . " to " . $cashTransfer->toEmployee->full_name,
        ]);
    }

    public function delete(CashTransfer $cashTransfer)
    {
        if ($cashTransfer->status == 'confirmed') {
            throw \Illuminate\Validation\ValidationException::withMessages(['status' => 'Cannot delete confirmed transfer.']);
        }
        $cashTransfer->delete();
    }

    public function show($id)
    {
        $cashTransfer = CashTransfer::with(['fromEmployee', 'toEmployee'])->findOrFail($id);

        if (!hasPermission('supper_admin')) {
            $currentEmployee = \Modules\HRMS\Models\Employee::where('user_id', auth()->id())->first();
            if (!$currentEmployee || ($cashTransfer->from_employee_id != $currentEmployee->id && $cashTransfer->to_employee_id != $currentEmployee->id)) {
                abort(403, 'Unauthorized access.');
            }
        }

        return $cashTransfer;
    }
}
