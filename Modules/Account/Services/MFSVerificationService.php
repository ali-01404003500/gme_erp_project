<?php

namespace Modules\Account\Services;

use App\Models\AccessControl\SmsTemplate;
use App\Models\AccessControl\TriggerName;
use App\Models\SmsInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Bank;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\MFSVerification;
use Modules\Account\Models\Setup\BankBranch;
use Modules\CRM\Models\Customer\Customer;
use Modules\Purchase\Models\Vendor;

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

            //dd($entry);
            if($entry->status === 'approved') { 
                $this->makeDummyTransaction($entry);
  
                /*Create:: sms send for mfs collection*/ 
                if ($entry->amount > 0) {
 
                    $triggerName = TriggerName::where('code', 'T02')->where('status', 1)->first();
                    $sms = SmsTemplate::where('code_name', "TEM002")->first(); 
                    $smsTemplate = $sms->template_body;

                    $customerInfo = Customer::where('id', $entry->customer_id)->first(); 

                    $phone =   $customerInfo->contact_for_sms; 
                    $customerName = $customerInfo->company_name; 
                    $customerPreBalance = Customer::find($entry->customer_id)->getAccount()->balance;
                    $collectionAmount = $entry->amount; 
                    $receivedDate = $entry->date ? Carbon::parse($entry->date)->format('d-m-Y') : now()->format('d-m-Y'); 
                    $customerBalance =  $customerPreBalance + $collectionAmount - $entry->charge;
                    
                    $smsData = [
                        'customer_name' => $customerName,
                        'customer_pre_balance ' => $customerPreBalance,
                        'collection_amount' => $collectionAmount,
                        'received_date' => $receivedDate,
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
                                'trigger_name' => 'T02', 
                                
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
    public function makeDummyTransaction(MFSVerification $mFSVerification)
    {
        /**
         */
        // dd($mFSVerification);
        $mFSVerification->transactions()->delete();

          
        // accounts
        $customerReceivableAccount = $mFSVerification->customer->getAccount();
 
        //debit     
        $mFSVerification->transactions()->create([
            'account_id' => $mFSVerification->bankAccount->getAccount()->id,
            'balance_type' => 'debit',
            'invoice_no' => $mFSVerification->source?->collection_id, 
            'debit_amount' => $mFSVerification->amount,
            'credit_amount' => 0, 
            'description' => "Collection through bKash #{$mFSVerification->transaction_id}",
            'transaction_date' => $mFSVerification->transaction_date,
        ]);
    
 
        //credit
        $mFSVerification->transactions()->create([
            'account_id' => $customerReceivableAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $mFSVerification->source?->collection_id, 
            'debit_amount' => 0,
            'credit_amount' => $mFSVerification->amount,
            'description' => "Collection through bKash #{$mFSVerification->transaction_id}",
            'transaction_date' => $mFSVerification->transaction_date,
        ]);


        if($mFSVerification->charge > 0) 
        { 
            //debit 
            $mFSVerification->transactions()->create([
                'account_id' => $customerReceivableAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $mFSVerification->source?->collection_id, 
                'debit_amount' => $mFSVerification->charge,
                'credit_amount' => 0,
                'description' => "Collection Charge #{$mFSVerification->transaction_id}",
                'transaction_date' => $mFSVerification->transaction_date,
            ]);
 
            //credit  
            $bankChargeAccount = Account::where('account_number', '506402')->first()->id; // Bank Charge Expense account
            $mFSVerification->transactions()->create([
                'account_id' => $bankChargeAccount,
                'balance_type' => 'credit',
                'invoice_no' => $mFSVerification->source?->collection_id, 
                'debit_amount' => 0,
                'credit_amount' => $mFSVerification->charge,
                'description' => "Collection Charge bKash #{$mFSVerification->transaction_id}",
                'transaction_date' => $mFSVerification->transaction_date,
            ]); 
        }


        // dd(  $mFSVerification->transactions, $mFSVerification);

 
    }




    function mapJson(array $jsonData): array
    {
        // Map customer name to ID
        if ($jsonData['collection_type'] == 'vendor') {
            $customerId = Vendor::where('name', $jsonData['customer_name'])
                ->value('id') ?? throw new \Exception("Vendor not found: {$jsonData['customer_name']}");
        } else {

            // if customer id then find with customer id or find with customer name
            if (!empty($jsonData['customer_id'])) {
                $customerId = Customer::where('customer_id', $jsonData['customer_id'])
                    ->value('id') ?? throw new \Exception("Customer not found: {$jsonData['customer_id']}");
            } else {
                $customerId = Customer::where('company_name', $jsonData['customer_name'])
                    ->value('id') ?? throw new \Exception("Customer not found: {$jsonData['customer_name']}");
            }
            // $customerId = Customer::where('company_name', $jsonData['customer_name'])->orWhere('customer_id', $jsonData['customer_id'])
            //     ->value('id') ?? throw new \Exception("Customer not found: {$jsonData['customer_name']}");
        }
        // Calculate total amounts

        // dd($jsonData['payments']);

        $totalPaid = 0;
        foreach ($jsonData['payments'] as $payment) {
            $totalPaid += $payment['amount'] ?? 0;
        }

        // calculate total amounts here
        $jsonData['total_amount'] = $totalPaid;
        $jsonData['payable_amount'] = $totalPaid;
        $jsonData['due_amount'] = 0;
        $jsonData['advance_amount'] = 0;

        // Prepare main validation data
        $validate = [
            'voucher_type' => $jsonData['voucher_type'],
            'collection_id' => $jsonData['collection_id'],
            'collection_type' => $jsonData['collection_type'],
            'collection_from' => $customerId,
            'payments_total_amount' => $jsonData['total_amount'],
            'payments_payable_amount' => $jsonData['payable_amount'],
            'payments_due_amount' => 0,
            'payments_advance_amount' => 0,
            'status' => $jsonData['status'],
            'collection_date' => $jsonData['date'],
        ];

        // Initialize payments array with required totals
        $payments = [
            'payments_total_amount' => $jsonData['total_amount'],
            'payments_payable_amount' => $jsonData['payable_amount'],
            'payments_due_amount' => 0,
            'payments_advance_amount' => 0,
            'payments_pay_mode' => [],
            'payments_bank_id' => [],
            'payments_branch_id' => [],
            'payments_emi_id' => [],
            'payments_transaction_id' => [],
            'payments_date' => [],
            'payments_amount' => [],
            'payments_attachments' => [],
            'payments_verified' => [],
            'payments_remark' => [],
        ];

        // Preload reference data for performance
        $banks = Bank::pluck('id', 'name')->toArray();
        $accounts = BankAccount::pluck('id', 'account_name')->toArray();
        $branches = BankBranch::pluck('id', 'name')->toArray();
        $emis = EMIEntry::pluck('id', 'emi_number')->toArray();

        // Process each payment entry
        foreach ($jsonData['payments'] as $payment) {
            // Payment mode validation
            $validModes = ['Cash', 'Cheque', 'Online Deposit', 'bKash', 'Nagad', 'Rocket', 'Card', 'EMI', 'Card Payment', 'AIT', 'Waiver', 'Waiver Bad Debt'];
            if (!in_array($payment['pay_mode'], $validModes)) {
                throw new \Exception("Invalid payment mode: {$payment['pay_mode']}");
            }
            // dd( $payment);

            // Map bank references
            $bankId = $payment['bank_name'] ? ($payment['pay_mode'] === 'Cheque'
                ? ($banks[$payment['bank_name']] ?? null)
                : ($accounts[$payment['bank_name']] ?? null)) : null;

            $branchId = $payment['branch_name']
                ? ($branches[$payment['branch_name']] ?? throw new \Exception("Branch not found: {$payment['branch_name']}"))
                : null;

            $emiId = $payment['pay_mode'] === 'EMI'
                ? ($emis[$payment['bank_name']] ?? throw new \Exception("EMI reference not found: {$payment['bank_name']}"))
                : null;

            // Add to payments array
            $payments['payments_pay_mode'][] = $payment['pay_mode'];
            $payments['payments_bank_id'][] = $bankId;
            $payments['payments_branch_id'][] = $branchId;
            $payments['payments_emi_id'][] = $emiId;
            $payments['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
            $payments['payments_date'][] = $payment['date'] ?? now()->format('Y-m-d');
            $payments['payments_amount'][] = $payment['amount'] ?? 0;
            $payments['payments_attachments'][] = $payment['attachment'] ?? null;
            $payments['payments_verified'][] = false;
            $payments['payments_remark'][] = $payment['remark'] ?? null;
            $payments['diposit'][] = $payment['diposit'] ?? null;

        }

        return [
            'validate' => $validate,
            'payments' => $payments
        ];
    }
    /**
     * Store a new MFS verification from a json file
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Modules\Account\Models\MFSVerification
     */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

        // Create directory if it doesn't exist
        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        // Create file if it doesn't exist
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        return $this->handleDirectImport($jsonData);
    }


    /**
     * Handle direct data import from API request for MFS Verification
     */
    public function handleDirectImport($data)
    {
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided.'
            ], 422);
        }

        DB::beginTransaction();
        $savedCount = 0;
        $errors = [];

        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        foreach ($items as $index => $item) {
            try {
                $mappedData = $this->mapMFSVerificationJson($item);
                $mfsverification =  $this->store($mappedData);
                if($mfsverification->status == "approved"){
                    $this->makeDummyTransaction($mfsverification);
                }
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        if (empty($errors)) {
            DB::commit();
            $message = "Import completed. Successfully saved: {$savedCount}";
        } else {
            DB::rollBack();
            $message = "Import failed. Errors: " . implode('; ', $errors);
        }

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'saved_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], empty($errors) ? 200 : 207); // 207 Multi-Status if partial success
    }

    /**
     * Map JSON data to MFSVerification format
     *
     * @param array $jsonData
     * @return array
     * @throws \Exception
     */
    public function mapMFSVerificationJson(array $jsonData): array
    {
        // Map customer name or ID to customer ID
        if (!empty($jsonData['customer_id'])) {
            $customerId = Customer::where('customer_id', $jsonData['customer_id'])
                ->value('id') ?? throw new \Exception("Customer not found: {$jsonData['customer_id']}");
        } elseif (!empty($jsonData['customer_name'])) {
            $customerId = Customer::where('company_name', $jsonData['customer_name'])
                ->value('id') ?? throw new \Exception("Customer not found: {$jsonData['customer_name']}");
        } else {
            throw new \Exception("Either customer_id or customer_name is required");
        }

        // Map bank account (head_id) - optional, can be null for charge-only entries
        $bankAccountId = null;
        if (!empty($jsonData['bank_account_name'])) {
            $bankAccountId = BankAccount::where('account_name', $jsonData['bank_account_name'])
                ->value('id') ?? throw new \Exception("Bank account not found: {$jsonData['bank_account_name']}");
        } elseif (!empty($jsonData['head_id'])) {
            $bankAccountId = $jsonData['head_id'];
        }

        // Validate that at least amount or charge is provided
        $amount = $jsonData['amount'] ?? 0;
        $charge = $jsonData['charge'] ?? 0;
        if ($amount <= 0 && $charge <= 0) {
            throw new \Exception("Either amount or charge must be greater than zero");
        }

        // Map source if provided
        $sourceType = is_array($jsonData['source_type'] ?? null) ? null : ($jsonData['source_type'] ?? null);
        $sourceId = is_array($jsonData['source_id'] ?? null) ? null : ($jsonData['source_id'] ?? null);

        // Prepare MFS verification data
        $validate = [
            'voucher_type' => is_array($jsonData['voucher_type'] ?? null) ? 'mfs_collection' : ($jsonData['voucher_type'] ?? 'mfs_collection'),
            'collection_id' => is_array($jsonData['collection_id'] ?? null) ? null : ($jsonData['collection_id'] ?? null),
            'collection_type' => is_array($jsonData['collection_type'] ?? null) ? 'customer' : ($jsonData['collection_type'] ?? 'customer'),
            'customer_id' => $customerId,
            'head_id' => $bankAccountId,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'amount' => $amount,
            'charge' => $charge,
            'transaction_date' => is_array($jsonData['transaction_date'] ?? null) ? now()->format('Y-m-d') : ($jsonData['transaction_date'] ?? now()->format('Y-m-d')),
            'status' => is_array($jsonData['status'] ?? null) ? 'pending' : ($jsonData['status'] ?? 'pending'),
            'remarks' => is_array($jsonData['remarks'] ?? null) ? null : ($jsonData['remarks'] ?? null),
            'deposited_by' => is_array($jsonData['deposited_by'] ?? null) ? auth()->id() : ($jsonData['deposited_by'] ?? auth()->id()),
            'document' => is_array($jsonData['document'] ?? []) ? json_encode($jsonData['document']?? []) : null,
        ];

        return $validate;
    }
}
