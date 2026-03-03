<?php

namespace Modules\Account\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\MFSVerification;

class MFSVerificationService
{
    
    public function getAll(int $limit = 20) {
       
        $query = MFSVerification::query();

        // Date filters
        $query->searchByFields(['customer_id','head_id'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('transaction_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('transaction_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            });

        // Collect allowed statuses based on permissions
        $allowedStatuses = [];
 
        $allowedStatuses[] = 'pending';
        if (hasPermission('account.mfs-verifications.check-verification')) {
            $allowedStatuses[] = 'verified';
        }
         
        // Apply status filter
        if (!empty($allowedStatuses)) {
            $query->whereIn('status', $allowedStatuses);
        } else {
            // no permission -> empty result
            $query->whereRaw('1=0');
        }
 
        return $query->paginate($limit);
    }
    
    
    public function store(array $data)
    {
        return MFSVerification::create($data);
    }

    public function update(MFSVerification $mFSVerification, array $data)
    {
        $mFSVerification->update($data);
        return $mFSVerification;
    }

    public function delete(MFSVerification $mFSVerification)
    {
        $mFSVerification->delete();
    }

    public function show($id)
    {
        return MFSVerification::findOrFail($id);
    }

    public function updateStatus(MFSVerification $entry, array $data)
    {
        
        try {
            DB::beginTransaction(); // dd($data);
            $entry->update($data);

            if($entry->status === 'approved') { 
                $this->makeDummyTransaction($entry);
            }
               
            DB::commit();
            return $entry;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
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
    public function makeDummyTransaction(MFSVerification $mFSVerification)
    {
        /**
         */
        // dd($mFSVerification->payments);
        $mFSVerification->transactions()->delete();

          
        // accounts
        $customerReceivableAccount = $mFSVerification->customer->getAccount();

        //debit     
        $mFSVerification->transactions()->create([
            'account_id' => $mFSVerification->bankAccount->getAccount()->id,
            'balance_type' => 'debit',
            'invoice_no' => $mFSVerification->source->collection_id, 
            'debit_amount' => $mFSVerification->amount-$mFSVerification->charge,
            'credit_amount' => 0,
            'description' => 'Collection Payment',
            'transaction_date' => $mFSVerification->transaction_date,
        ]);

        if($mFSVerification->charge > 0) {
            $bankChargeAccount = Account::where('name', 'MFS Charge Expense')->first()->id; // Bank Charge Expense account
            $mFSVerification->transactions()->create([
                'account_id' => $bankChargeAccount,
                'balance_type' => 'debit',
                'invoice_no' => $mFSVerification->source->collection_id, 
                'debit_amount' => $mFSVerification->charge,
                'credit_amount' => 0,
                'description' => 'Collection Charge',
                'transaction_date' => $mFSVerification->transaction_date,
            ]);

        }
        
    
        //credit
        $mFSVerification->transactions()->create([
            'account_id' => $customerReceivableAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $mFSVerification->source->collection_id, 
            'debit_amount' => 0,
            'credit_amount' => $mFSVerification->amount,
            'description' => 'Collection Created',
            'transaction_date' => $mFSVerification->transaction_date,
        ]);
 
    } 
}
