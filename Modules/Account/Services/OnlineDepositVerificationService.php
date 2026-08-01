<?php

namespace Modules\Account\Services;

use App\Models\AccessControl\SmsTemplate;
use App\Models\AccessControl\TriggerName;
use App\Models\SmsInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\OnlineDepositVerification;
use Modules\CRM\Models\Customer\Customer;

class OnlineDepositVerificationService
{
    
    public function getAll(int $limit = 20) {
       
        $query = OnlineDepositVerification::query();

        // Date filters
        $query->searchByFields(['customer_id','head_id'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('deposit_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('deposit_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            });

        // Collect allowed statuses based on permissions
        $allowedStatuses = [];
 
        $allowedStatuses[] = 'pending';
        if (hasPermission('account.online-deposit-verifications.check-verification')) {
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
        return OnlineDepositVerification::create($data);
    }

    public function update(OnlineDepositVerification $onlineDepositVerification, array $data)
    {
        $onlineDepositVerification->update($data);
        return $onlineDepositVerification;
    }

    public function delete(OnlineDepositVerification $onlineDepositVerification)
    {
        $onlineDepositVerification->delete();
    }

    public function show($id)
    {
        return OnlineDepositVerification::findOrFail($id);
    }

    public function updateStatus(OnlineDepositVerification $entry, array $data)
    {
        
        try {
            DB::beginTransaction(); // dd($data);
            $entry->update($data);

            if($entry->status === 'approved') { 
                $this->makeDummyTransaction($entry);


                /*Create:: sms send for mfs collection*/ 
                if ($entry->amount > 0) {
 
                    $triggerName = TriggerName::where('code', 'T04')->where('status', 1)->first();
                    $sms = SmsTemplate::where('code_name', "TEM004")->first(); 
                    $smsTemplate = $sms->template_body;

                    $customerInfo = Customer::where('id', $entry->customer_id)->first(); 

                    $phone =   $customerInfo->contact_for_sms; 
                    $customerName = $customerInfo->company_name; 
                    $customerPreBalance = Customer::find($entry->customer_id)->getAccount()->balance;
                    $collectionAmount = $entry->amount; 
                    $receivedDate = $entry->date ? Carbon::parse($entry->date)->format('d-m-Y') : now()->format('d-m-Y'); 
                    $customerBalance =  $customerPreBalance + $collectionAmount - $entry->charge;
                    $bankName = $entry->bankAccount->bank->name;

                    $smsData = [
                        'customer_name' => $customerName,
                        'customer_pre_balance ' => $customerPreBalance,
                        'collection_amount' => $collectionAmount,
                        'received_date' => $receivedDate,
                        'bank_name' => $bankName,
                        'charge_amount' => $entry->charge, 
                        'customer_current_balance ' => $customerBalance
                    ];   

                
                    foreach ($smsData as $key => $value) {
                        $smsTemplate = str_replace('$' . $key, $value, $smsTemplate);
                    } 

                    $time = Carbon::parse(now()); 
                    $newTime = $time->addMinutes($triggerName->after_send_time);

                    if (!empty($phone)) {
                        SmsInfo::updateOrCreate(
                            [
                                'sms_reference' => $entry->source_id,
                                'sms_mem_id' => $entry->customer_id,
                                'sms_status' => 'pending', // condition
                                'trigger_name' => 'T04', 
                                
                            ],
                            [
                                'sms_send_time' => $newTime,
                                'sms_to' => $phone,
                                'sms_text' => $smsTemplate, 
                            ]
                        );
                    }

                    
                    // dd($smsTemplate);  
                }

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
    public function makeDummyTransaction(OnlineDepositVerification $onlineDepositVerification)
    {
        /**
         */
        // dd($onlineDepositVerification->payments);
        $onlineDepositVerification->transactions()->delete();

         
 
        // accounts
        $customerReceivableAccount = $onlineDepositVerification->customer->getAccount();

        //debit     
        $onlineDepositVerification->transactions()->create([
            'account_id' => $onlineDepositVerification->bankAccount->getAccount()->id,
            'balance_type' => 'debit',
            'invoice_no' => $onlineDepositVerification->source->collection_id, 
            'debit_amount' => $onlineDepositVerification->amount,
            'credit_amount' => 0,
            'description' => "Collection through Online Deposit",
            'transaction_date' => $onlineDepositVerification->deposit_date,
        ]);
 
        //credit
        $onlineDepositVerification->transactions()->create([
            'account_id' => $customerReceivableAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $onlineDepositVerification->source->collection_id, 
            'debit_amount' => 0,
            'credit_amount' => $onlineDepositVerification->amount,
            'description' => "Collection through Online Deposit",
            'transaction_date' => $onlineDepositVerification->deposit_date,
        ]);

        if($onlineDepositVerification->charge > 0) 
        { 
            //debit 
            $onlineDepositVerification->transactions()->create([
                'account_id' => $customerReceivableAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $onlineDepositVerification->source?->collection_id, 
                'debit_amount' => $onlineDepositVerification->charge,
                'credit_amount' => 0,
                'description' => "Collection through Online Deposit",
                'transaction_date' => $onlineDepositVerification->transaction_date,
            ]);
 
            //credit
            $bankChargeAccount = Account::where('account_number', '506401')->first()->id; // Bank Charge Expense account
            $onlineDepositVerification->transactions()->create([
                'account_id' => $bankChargeAccount,
                'balance_type' => 'credit',
                'invoice_no' => $onlineDepositVerification->source?->collection_id, 
                'debit_amount' => 0,
                'credit_amount' => $onlineDepositVerification->charge,
                'description' => "Collection through Online Deposit",
                'transaction_date' => $onlineDepositVerification->transaction_date,
            ]); 
        }
 
    }


}
