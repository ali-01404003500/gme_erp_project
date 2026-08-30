<?php

namespace Modules\Account\Services;

use App\Models\AccessControl\SmsTemplate;
use App\Models\AccessControl\TriggerName;
use App\Models\SmsInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\AdvanceChequeEntry;
use Modules\Account\Models\AdvanceChequeEntryDetail;
use Modules\Account\Models\ChequeDishonorSummary;
use Modules\Account\Models\ChequeVerification;
use Modules\CRM\Models\Customer\Customer;

class ChequeVerificationService
{
    public function getAll(int $limit = 20)
    {
        $query = ChequeVerification::query();

        // 📌 Date filters
        $query->searchByFields(['customer_id', 'bank_id','head_id'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('cheque_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('cheque_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            });

        // 📌 Collect allowed statuses based on permissions
        $allowedStatuses = [];

        if (hasPermission('account.cheque-verifications.deposit')) {
            $allowedStatuses[] = 'pending';
        }
        if (hasPermission('account.cheque-verifications.check')) {
            $allowedStatuses[] = 'deposited';
        }
        if (hasPermission('account.cheque-verifications.check-verification')) {
            $allowedStatuses[] = 'honored';
        }

        // 📌 Apply status filter
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
        // dd($data);
        $chequeVerification = ChequeVerification::create($data);
        // dd($data['source_type'],AdvanceChequeEntryDetail::class, $data['source_type'] == AdvanceChequeEntryDetail::class);

        if ($data['source_type'] == AdvanceChequeEntryDetail::class) {
            $advanceChequeEntry = AdvanceChequeEntryDetail::find($data['source_id']);
            // dd($advanceChequeEntry);
            if ($advanceChequeEntry) {
                $advanceChequeEntry->update([
                    'status' => 'Deposited',
                    'approved_by' => auth()->id(),
                ]);
            }
        }
        return $chequeVerification;
    }

    public function update(ChequeVerification $chequeVerification, array $data)
    {
        $chequeVerification->update($data);
        return $chequeVerification;
    }
    public function updateStatus(ChequeVerification $entry, array $data)
    {
        try {
            DB::beginTransaction(); // dd($data);
            // $entry->status = $data['status'];
            // $entry->remarks = $data['remarks'] ?? null;
            // $entry->charge = $data['charge'] ?? 0;
            // dd($data['status'],$entry->status);

            if ($data['status'] === 'dishonored') {
                if ($entry->status === 'honored') {
                    $entry->remarks = $data['remarks'] ?? null;
                    $entry->charge = $data['charge'] ?? 0;
                    $entry->status = 'deposited';
                } 
                elseif ($entry->status === 'deposited') {
                    $entry->remarks = $data['remarks'] ?? null;
                    $entry->charge = $data['charge'] ?? 0;
                    $entry->status = 'pending';
                }

                ChequeDishonorSummary::create([
                    'cheque_verification_id' => $entry->id,
                    'dishonor_date' => now(),
                ]);

                $entry->dishonored_by = auth()->id();

                /*Create:: sms send for cheque dishonor*/ 
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
                    $bankName = $entry->account->accountable->account_name;
                    $customerBalance =  $customerPreBalance + $collectionAmount - $entry->charge;
                   
                    $smsData = [
                        'customer_name' => $customerName,
                        'customer_pre_balance ' => $customerPreBalance,
                        'collection_amount' => $collectionAmount,
                        'received_date' => $receivedDate,
                        'bank_name' => $bankName,
                        'charge_amount' => $entry->charge, 
                        'cheque_no' => $entry->cheque_no,  
                        'customer_bank' => $entry->bank->name,  
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

            if ($data['status'] === 'honored') {
                $entry->status = $data['status'];
                $entry->remarks = $data['remarks'] ?? null;
                $entry->charge = $data['charge'] ?? 0;
                $entry->encashed_by = auth()->id();
            }
            if ($data['status'] === 'honor-verified') { 
                $entry->status = $data['status'];
                $entry->remarks = $data['remarks'] ?? null;
                $entry->charge = $data['charge'] ?? 0;
                $entry->encash_verified_by = auth()->id();
            
                $this->makeBankTransaction($entry);
                if ($entry->source_type == AdvanceChequeEntryDetail::class) {
                    $advanceChequeEntry = AdvanceChequeEntryDetail::find($entry->source_id);
                    // dd($advanceChequeEntry->emiEntryDetail(), $advanceChequeEntry);
                    if ($advanceChequeEntry) {
                        $advanceChequeEntry->emiEntryDetail()->update([
                            'status' => 'paid',
                            'receipt_no' => json_encode($advanceChequeEntry->advanceChequeEntry->receipt_no),
                        ]);
                    }
                }



                /*Create:: sms send for cheque honnor*/ 
                if ($entry->amount > 0) {
 
                    $triggerName = TriggerName::where('code', 'T05')->where('status', 1)->first();
                    $sms = SmsTemplate::where('code_name', "TEM005")->first(); 
                    $smsTemplate = $sms->template_body;

                    $customerInfo = Customer::where('id', $entry->customer_id)->first(); 

                    $phone =   $customerInfo->contact_for_sms; 
                    $customerName = $customerInfo->company_name; 
                    $customerPreBalance = Customer::find($entry->customer_id)->getAccount()->balance;
                    $collectionAmount = $entry->amount; 
                    $receivedDate = $entry->date ? Carbon::parse($entry->date)->format('d-m-Y') : now()->format('d-m-Y'); 
                    $bankName = $entry->account->accountable->account_name;
                    $customerBalance =  $customerPreBalance + $collectionAmount - $entry->charge;
                   
                    $smsData = [
                        'customer_name' => $customerName,
                        'customer_pre_balance ' => $customerPreBalance,
                        'collection_amount' => $collectionAmount,
                        'received_date' => $receivedDate,
                        'bank_name' => $bankName,
                        'charge_amount' => $entry->charge, 
                        'cheque_no' => $entry->cheque_no,  
                        'customer_bank' => $entry->bank->name,  
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
                                'trigger_name' => 'T05', 
                                
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
           
            $entry->save();
            
            DB::commit();
            return $entry;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deposit(ChequeVerification $chequeVerification, array $data)
    {
         
        $data['status'] = 'deposited';
        $data['deposited_by'] = auth()->id();
        $chequeVerification->update($data);

        return $chequeVerification;
    }

    public function cash(ChequeVerification $chequeVerification)
    {
        try {
            DB::beginTransaction();

            $data['status'] = 'cash';
            $data['deposited_by'] = auth()->id();
            $chequeVerification->update($data);
            if ($chequeVerification->source_type == AdvanceChequeEntryDetail::class) {
                $advanceChequeEntry = AdvanceChequeEntryDetail::find($chequeVerification->source_id);
                // dd($advanceChequeEntry->emiEntryDetail(), $advanceChequeEntry);
                if ($advanceChequeEntry) {
                    $advanceChequeEntry->emiEntryDetail()->update([
                        'status' => 'paid',
                        'receipt_no' => $advanceChequeEntry->advanceChequeEntry->receipt_no
                    ]);
                }
            }

            $this->makeCashTransaction($chequeVerification);

            // dd($chequeVerification);
            DB::commit();
            return $chequeVerification;
        } catch (\Throwable $th) {
            DB::rollBack();
            $th->getMessage();
        }
    }

    public function chequeReturn(ChequeVerification $chequeVerification)
    {
        DB::beginTransaction();

        $data['status'] = 'return';
        $chequeVerification->update($data);
        $chequeVerification->source->update(['verified'=>'-1']);
         
        DB::commit();

        return $chequeVerification;
    }


    public function makeBankTransaction(ChequeVerification $chequeVerification)
    {
        // Delete any existing transactions to prevent duplicates
        $chequeVerification->transactions()->delete();

        // 1. Accounts
        $customerAccount = $chequeVerification->customer->getAccount(); // A/R
        $bankAccount = Account::find($chequeVerification->head_id); // Cash/Bank

        if (!$bankAccount) {
            throw new \Exception('Bank account not found. Please configure it.');
        }

        $amount = $chequeVerification->amount;
 

        // 2. Debit Cash (Asset increase)
        $chequeVerification->transactions()->create([
            'account_id' => $bankAccount->id,
            'balance_type' => 'debit',
            'invoice_no' => 'CHQ-' . $chequeVerification->id,
            'amount' => $amount,
            'debit_amount' => $amount,
            'credit_amount' => 0,
            'description' => "Bank collection for Cheque #{$chequeVerification->cheque_no}",
            'transaction_date' => $chequeVerification->cheque_date?? date('Y-m-d')
        ]);

        // 3. Credit Accounts Receivable (Asset decrease)
        $chequeVerification->transactions()->create([
            'account_id' => $customerAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => 'CHQ-' . $chequeVerification->id,
            'amount' => -$amount,
            'debit_amount' => 0,
            'credit_amount' => $amount,
            'description' => "Bank collection for Cheque #{$chequeVerification->cheque_no}",
            'transaction_date' => $chequeVerification->cheque_date?? date('Y-m-d')
        ]);

        if($chequeVerification->charge > 0){

            $chequeVerification->transactions()->create([
                'account_id' => $customerAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => 'CHQ-' . $chequeVerification->id,
                'amount' => -$chequeVerification->charge,
                'debit_amount' => $chequeVerification->charge,
                'credit_amount' => 0,
                'description' => "Bank collection for Cheque #{$chequeVerification->cheque_no}",
                'transaction_date' => $chequeVerification->cheque_date?? date('Y-m-d')
            ]);

            
            // Bank charge entry
            $bankChargeAccount =  Account::where('account_number', '506401')->first()->id; // Bank Charge Expense account
            $chequeVerification->transactions()->create([
                'account_id' => $bankChargeAccount,
                'balance_type' => 'credit',
                'invoice_no' => 'CHG-' . $chequeVerification->id,
                'amount' => $chequeVerification->charge,
                'debit_amount' => 0,
                'credit_amount' => $chequeVerification->charge,
                'description' => "Bank collection for Cheque #{$chequeVerification->cheque_no}",
                'transaction_date' => $chequeVerification->cheque_date?? date('Y-m-d')
            ]);
 
        }



       

        // 4. Check balance
        $totalDebits = $chequeVerification->transactions()->sum('debit_amount');
        $totalCredits = $chequeVerification->transactions()->sum('credit_amount');
        // dd($totalDebits, $totalCredits);
        if ($totalDebits != $totalCredits) {
            logger()->error("Unbalanced journal entries for Cheque #{$chequeVerification->id}", [
                'debits' => $totalDebits,
                'credits' => $totalCredits,
            ]);
            throw new \Exception("Unbalanced journal entries for cheque #{$chequeVerification->id}");
        }
    }

    public function makeCashTransaction(ChequeVerification $chequeVerification)
    {
        // Delete any existing transactions to prevent duplicates
        $chequeVerification->transactions()->delete();
   
        // 1. Accounts
        $customerAccount = $chequeVerification->customer->getAccount(); // A/R
       // $cashAccount = BankAccount::where('payment_mode', 'Cash')->first()->getAccount(); // Cash/Bank

        $cashAccount = $chequeVerification->depositedEmpBy->getAccount();
 
 
        if (!$cashAccount) {
            throw new \Exception('Cash account not found. Please configure it.');
        }

        $amount = $chequeVerification->amount;

        // 2. Debit Cash (Asset increase)
        $chequeVerification->transactions()->create([
            'account_id' => $cashAccount->id,
            'balance_type' => 'debit',
            'invoice_no' => 'CHQ-' . $chequeVerification->id,
            'amount' => $amount,
            'debit_amount' => $amount,
            'credit_amount' => 0,
            'description' => "Cash collection for Cheque #{$chequeVerification->cheque_no}",
        ]);

        // 3. Credit Accounts Receivable (Asset decrease)
        $chequeVerification->transactions()->create([
            'account_id' => $customerAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => 'CHQ-' . $chequeVerification->id,
            'amount' => -$amount,
            'debit_amount' => 0,
            'credit_amount' => $amount,
            'description' => "Settlement of customer receivable via Cheque #{$chequeVerification->cheque_no}",
        ]);

        // 4. Check balance
        $totalDebits = $chequeVerification->transactions()->sum('debit_amount');
        $totalCredits = $chequeVerification->transactions()->sum('credit_amount');
        // dd($totalDebits, $totalCredits);
        if ($totalDebits != $totalCredits) {
            logger()->error("Unbalanced journal entries for Cheque #{$chequeVerification->id}", [
                'debits' => $totalDebits,
                'credits' => $totalCredits,
            ]);
            throw new \Exception("Unbalanced journal entries for cheque #{$chequeVerification->id}");
        }
    }

    public function delete(ChequeVerification $chequeVerification)
    {
        $chequeVerification->delete();
    }

    public function show($id)
    {
        return ChequeVerification::findOrFail($id);
    }
}
