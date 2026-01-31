<?php

namespace Modules\Sales\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Sales\Models\ConditionAmountCollect;

class ConditionAmountCollectService
{

    public function getAll(int $limit = 20)
    {
        // Return pending collections
        return ConditionAmountCollect::with(['customer', 'courier', 'salesOrder.shipment', 'salesOrder.source'])
            ->where('status', 'pending')
            ->latest()
            ->paginate($limit);
    }

    public function store(array $data)
    {
        return ConditionAmountCollect::create($data);
    }

    public function update(ConditionAmountCollect $conditionAmountCollect, array $data)
    {
        $conditionAmountCollect->update($data);
        return $conditionAmountCollect;
    }

    public function markAsReceived(ConditionAmountCollect $conditionAmountCollect)
    {
        $conditionAmountCollect->update([
            'status' => 'received',
            'received_amount' => $conditionAmountCollect->condition_amount, // Assuming full amount received
            'received_date' => now(),
            'updated_by' => auth()->id(),
        ]);
        return $conditionAmountCollect;
    }

    public function receivedBack($id)
    {
        $conditionAmountCollect = ConditionAmountCollect::findOrFail($id);
        $conditionAmountCollect->update([
            'status' => 'pending',
            'received_amount' => 0,
            'received_date' => null,
            'updated_by' => auth()->id(),
        ]);
        return $conditionAmountCollect;
    }

    public function delete(ConditionAmountCollect $conditionAmountCollect)
    {
        $conditionAmountCollect->delete();
    }

    public function show($id)
    {
        return ConditionAmountCollect::with(['customer', 'courier', 'salesOrder'])->findOrFail($id);
    }

    public function getMetrics()
    {
        $received = ConditionAmountCollect::where('status', 'received')->get();
        return [
            'received_count' => $received->count(),
            'received_amount' => $received->sum('received_amount'),
        ];
    }

    /**
     * Get list of received items awaiting approval
     */
    public function getReceivedList(int $limit = 20)
    {
        return ConditionAmountCollect::with(['customer', 'courier', 'salesOrder.shipment', 'salesOrder.source', 'shipmentVerify'])
            ->where('status', 'received')
            ->latest()
            ->paginate($limit);
    }

    /**
     * Approve multiple collections and create accounting transactions
     */
    public function approveCollections(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            $collections = ConditionAmountCollect::with(['customer', 'salesOrder'])
                ->whereIn('id', $ids)
                ->where('status', 'received')
                ->get();

            foreach ($collections as $collection) {
                // Update status to approved/completed
                $collection->update([
                    'status' => 'approved',
                    'updated_by' => auth()->id(),
                ]);

                // Create accounting transactions
                $this->makeApprovalTransaction($collection);
            }

            return $collections;
        });
    }

    /**
     * Create accounting transactions for approved conditional amount
     * Following the pattern from ShipmentVerifyService::makeDummyTransaction
     */
    private function makeApprovalTransaction(ConditionAmountCollect $collection)
    {
        // Delete any existing transactions
        $collection->transactions()->delete();

        $conditionalAmount = $collection->condition_amount;

        if ($conditionalAmount <= 0) {
            return;
        }

        // Customer Ledger (Receivable) - Credit
        $customer = $collection->customer;
        $customerAccount = $customer->getAccount();

        // Employee Cash Account (User who approved) - Debit
        $employee = auth()->user()->employee;
        $employeeAccount = $employee?->getAccount();

        if (!$employeeAccount) {
            if (hasPermission('supper_admin')) {
                $employeeAccount = Account::where('name', 'Cash-in-Hand')->first();
            }
        }

        if (!$employeeAccount || !$customerAccount) {
            throw new \Exception('Account configuration missing for employee or customer');
        }

        $invoice_no = $collection->shipmentVerify->shipment_id ?? 'COND-' . $collection->id;

        // Debit Transaction - Cash in Hand (Employee receives cash)
        $collection->transactions()->create([
            'account_id' => $employeeAccount->id,
            'balance_type' => 'debit',
            'invoice_no' => $invoice_no,
            'debit_amount' => $conditionalAmount,
            'credit_amount' => 0,
            'description' => 'Conditional Amount Collection - ' . $customer->company_name
        ]);

        // Credit Transaction - Customer Ledger (Reduce receivable)
        $collection->transactions()->create([
            'account_id' => $customerAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $invoice_no,
            'debit_amount' => 0,
            'credit_amount' => $conditionalAmount,
            'description' => 'Conditional Amount Collection - ' . $customer->company_name
        ]);
    }

    /**
     * Get multiple condition amount collects by their IDs
     */
    public function getByIds(array $ids)
    {
        return ConditionAmountCollect::with(['customer', 'courier', 'salesOrder'])
            ->whereIn('id', $ids)
            ->get();
    }
}
