<?php

namespace Modules\Account\Services\Collections;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Collections\Collection;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\EMIEntryDetail;
use Modules\Account\Models\Payment;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\Account\Services\ChequeVerificationService;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;
use Modules\Purchase\Models\Vendor;
use Modules\Sales\Models\SalesOrder;

class CollectionService
{
    public function getAll(int $limit = 20)
    {
        return Collection::query()->with(["collectionFrom"])->filterByDateRange('created_at')
            ->when(request()->filled('customer_id'), function ($query) {
                $query->where('collection_from_type', Customer::class)->where('collection_from_id', request('customer_id'));
            })
            ->likeSearch('collection_id')
            ->paginate($limit);
    }

    public function getCollectionId()
    {
        $today = date('Y-m-d');
        $authUser = auth()->user()->id;

        $collectionToday = Collection::whereDate(DB::raw('DATE(collection_date)'), $today)
            ->count();

        // Format: PR-YYYYMMDD-USR-USERID-SERIAL NUMBER
        // Example: PR-20250820-USR-000002-00001
        $collectionNumber = sprintf(
            'PR-%s-USR-%06d-%05d',
            date('Ymd'),
            $authUser,
            $collectionToday + 1
        );

        return $collectionNumber;
    }

    public function store(array $data, array $payments = [])
    {
        DB::beginTransaction();
        // dd($data, $payments);

        // Build attributes without null collection_date
        $attributes = [
            'collection_id' => $data['collection_id'] ?? $this->getCollectionId(),
            'total_amount' => $data['payments_total_amount'],
            'advance_amount' => $data['payments_advance_amount'],
            'status' => $data['status'],
        ];

        // Only add collection_date if it's not null
        if (!empty($data['collection_date'])) {
            $attributes['collection_date'] = $data['collection_date'];
        }

        $collection = Collection::create($attributes);
        $from = null;
        switch ($data['collection_type']) {
            case 'customer':
                $from = Customer::find($data['collection_from']);
                break;
            case 'vendor':
                $from = Vendor::find($data['collection_from']);
                break;
            default:
                # code...
                break;
        }
        //   dd($from, $data['collection_type'], $data['collection_from']);

        $collection->collectionFrom()->associate($from);

        $collection->save();

        $result["collection"] = $collection;
        // Save payments
        foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
            if ($payMode) {
                $result['payments'][] = $collection->payments()->create([
                    'pay_mode' => $payMode,
                    'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                    'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                    'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                    'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                    'amount' => $payments['payments_amount'][$key] ?? 0,
                    'date' => $payments['payments_date'][$key] ?? null,
                    'attachments' => $payments['payments_attachments'][$key] ?? null,
                    'verified' => $payments['payments_verified'][$key] ?? false,
                    'remarks' => $payments['payments_remark'][$key] ?? null,
                ]);
            }
        }

        if ($collection->status == 'approved') {
            # code...
            $this->makeDummyTransaction($collection);
        }

        // dd( $result);
        DB::commit();
        return $result;
    }

    /**
     * Store a collection for sales.
     *
     * @param array $data The form data.
     * @param Collection $payments The payments.
     *
     * @return Collection The stored collection.
     */
    public function storeForSales(array $data, EloquentCollection $payments, $salesOrder)
    {
        DB::beginTransaction();
        // dd($data, $payments);

        $collection = Collection::where('source_id', $salesOrder->id)
            ->where('source_type', get_class($salesOrder))
            ->first();
        if (!$collection) {
            $attributes = [
                'source_id' => $salesOrder->id,
                'source_type' => get_class($salesOrder),
                'collection_id' => $this->getCollectionId(),
                'total_amount' => $data['payments_total_amount'],
                'advance_amount' => $data['payments_advance_amount'],
                'status' => "pending",
            ];

            // Only add collection_date if it's not null
            if (!empty($data['collection_date'])) {
                $attributes['collection_date'] = $data['collection_date'];
            }

            $collection = Collection::create($attributes);
        } else {
            $updateData = [
                'total_amount' => $data['payments_total_amount'],
                'advance_amount' => $data['payments_advance_amount'],
                'status' => "pending",
            ];

            // Only add collection_date if it's not null
            if (!empty($data['collection_date'])) {
                $updateData['collection_date'] = $data['collection_date'];
            }

            $collection = Collection::updateOrCreate(
                [
                    'source_id' => $salesOrder->id,
                    'source_type' => get_class($salesOrder),
                ],
                $updateData
            );
        }

        $from = match ($data['collection_type']) {
            'customer' => Customer::find($data['collection_from']),
            'vendor' => Vendor::find($data['collection_from']),
            default => null,
        };
        //   dd($from, $data['collection_type'], $data['collection_from']);

        $collection->collectionFrom()->associate($from);

        $collection->save();
        $result[] = $collection;
        $collection->payments()->update([
            'collection_id' => null,
        ]);
        // dd($payments);
        foreach ($payments as $payment) {
            $payment->update([
                'collection_id' => $collection->id,
            ]);
        }
        // dd($collection->payments);
        // $collection->refresh();
        if ($collection->status == 'approved') {
            # code...
            $this->makeDummyTransaction($collection);
        }

        // dd( $result);
        DB::commit();
        return $collection;
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
    public function makeDummyTransaction(Collection $collection)
    {
        /**
         */
        // dd($collection->payments);
        $collection->transactions()->delete();

        $chequeAndEmiAmount = $collection->payments()->whereIn('pay_mode', ['Cheque', 'EMI'])->sum('amount');

        $receivableCreditAmount = $collection->total_amount - $chequeAndEmiAmount;

        if ($chequeAndEmiAmount > 0) {

            foreach ($collection->payments as $payment) {
                if ($payment->pay_mode == 'Cheque') {
                    // cheque entry
                    if ($payment->chequeVerification) {
                        // update
                        $payment->chequeVerification()->update([
                            'customer_id' => $collection->collectionFrom->id,
                            'bank_id' => $payment->bank->id,
                            'branch_id' => $payment->branch->id,
                            'cheque_no' => $payment->transaction_no,
                            'cheque_date' => $payment->date,
                            'amount' => $payment->amount,
                        ]);
                    } else {
                        $payment->chequeVerification()->create([
                            'customer_id' => $collection->collectionFrom->id,
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
                } else if ($payment->pay_mode == 'EMI') {
                    // emi update
                    if ($payment->bank) {
                        // dd($collection->source,$payment->bank);
                        $payment->bank->restore();
                        $payment->bank->update([
                            'deleted_by' => null,
                            'sales_order_id' => $collection->source_type == SalesOrder::class ? $collection->source->id : null
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
            return;
        }

        // accounts
        $customerReceivableAccount = $collection->collectionFrom->getAccount();
        //debit

        // dd($collection->payments);
        foreach ($collection->payments as $payment) {
            if (in_array($payment->pay_mode, ['Cheque', 'EMI'])) {
                continue;
            }
            if (in_array($payment->pay_mode, ['AIT', 'Waiver', 'Waiver Bad Debt'])) {
                if ($payment->pay_mode == 'AIT') {
                    // Dr AIT Receivable A/C.
                    $aitReceivableAccount = Account::where('account_number', '102301')->first();
                    $collection->transactions()->create([
                        'account_id' => $aitReceivableAccount->id,
                        'balance_type' => 'debit',
                        'invoice_no' => $collection->collection_id,
                        'amount' => $payment->amount,
                        'debit_amount' => $payment->amount,
                        'credit_amount' => 0,
                        'description' => 'Collection Payment',
                        'transaction_date' => $collection->collection_date,
                    ]);

                } else if ($payment->pay_mode == 'Waiver') {
                    // Dr Waiver A/C.
                    $waiverAccount = Account::where('account_number', '505301')->first();
                    $collection->transactions()->create([
                        'account_id' => $waiverAccount->id,
                        'balance_type' => 'debit',
                        'invoice_no' => $collection->collection_id,
                        'amount' => $payment->amount,
                        'debit_amount' => $payment->amount,
                        'credit_amount' => 0,
                        'description' => 'Collection Payment',
                        'transaction_date' => $collection->collection_date,
                    ]);

                } else if ($payment->pay_mode == 'Waiver Bad Debt') {
                    // Dr Waiver Bad Debt A/C.
                    $waiverBadDebtAccount = Account::where('account_number', '505401')->first();
                    $collection->transactions()->create([
                        'account_id' => $waiverBadDebtAccount->id,
                        'balance_type' => 'debit',
                        'invoice_no' => $collection->collection_id,
                        'amount' => $payment->amount,
                        'debit_amount' => $payment->amount,
                        'credit_amount' => 0,
                        'description' => 'Collection Payment',
                        'transaction_date' => $collection->collection_date,
                    ]);

                }
                continue;
            }
            if ($payment->bank) {
                $collection->transactions()->create([
                    'account_id' => $payment->bank->getAccount()->id,
                    'balance_type' => 'debit',
                    'invoice_no' => $collection->collection_id,
                    'amount' => -$payment->amount,
                    'debit_amount' => $payment->amount,
                    'credit_amount' => 0,
                    'description' => 'Collection Payment',
                    'transaction_date' => $collection->collection_date,
                ]);
            }
        }
        $collection->transactions()->create([
            'account_id' => $customerReceivableAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $collection->collection_id,
            'amount' => $receivableCreditAmount,
            'debit_amount' => 0,
            'credit_amount' => $receivableCreditAmount,
            'description' => 'Collection Created',
            'transaction_date' => $collection->collection_date,
        ]);

        // $collection->transactions()->create([
        //         'account_id'            => Account::where('account_code', '1000')->first()->id,
        //         'balance_type'          => "credit",
        //         'invoice_no'            => $collection->collection_id,
        //         'amount'                => $collection->amount,
        //         'debit_amount'          => 0,
        //         'credit_amount'         => $collection->amount,
        //         'description'           => "Collection Created"
        // ]);
        // dd($collection->transactions);
    }

    public function update(Collection $collection, array $data, array $payments = [])
    {
        DB::beginTransaction();

        $updateData = [
            'total_amount' => $data['payments_total_amount'],
            'advance_amount' => $data['payments_advance_amount'],
            'status' => $data['status'],
        ];

        if ($data['status'] === 'verified' && $collection->status !== 'verified') {
            $updateData['verified_by'] = auth()->id();
            $updateData['verified_at'] = now();
        }

        if ($data['status'] === 'approved' && $collection->status !== 'approved') {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
        }

        // Only add collection_date if it's not null
        if (!empty($data['collection_date'])) {
            $updateData['collection_date'] = $data['collection_date'];
        }

        $collection->update($updateData);
        $result['collection'] = $collection;

        $from = null;
        switch ($data['collection_type']) {
            case 'customer':
                $from = Customer::find($data['collection_from']);
                break;
            case 'vendor':
                $from = Vendor::find($data['collection_from']);
                break;
            default:
                break;
        }

        $collection->collectionFrom()->associate($from);
        $collection->save();

        // Delete existing payments
        // $collection->payments()->delete();
        $collection
            ->payments()
            ->whereNotIn('id', $payments['payments_id'] ?? [])
            ->delete();

        // Save new payments
        foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
            if ($payMode) {
                $payment = $collection->payments()->updateOrCreate(
                    [
                        'id' => $payments['payments_id'][$key] ?? null,
                    ],
                    [
                        'pay_mode' => $payMode,
                        'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                        'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                        'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                        'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                        'amount' => $payments['payments_amount'][$key] ?? 0,
                        'date' => $payments['payments_date'][$key] ?? null,
                        'attachments' => $payments['payments_attachments'][$key] ?? null,
                        'verified' => $payments['payments_verified'][$key] ?? false,
                        'remarks' => $payments['payments_remark'][$key] ?? null,
                    ],
                );
            }
        }
        // dd($payments['payments_pay_mode']);
        if ($collection->status == 'denied') {
            if ($collection->source_type == EMIEntryDetail::class) {
                $emiEntryDetail = EMIEntryDetail::find($collection->source_id);
                if ($emiEntryDetail) {
                    $emiEntryDetail->update([
                        'status' => 'due',
                    ]);
                }
            }
            if ($collection->source_type == EMIEntry::class) {
                $emiEntry = EMIEntry::find($collection->source_id);
                if ($emiEntry) {
                    $emiEntry->update([
                        'status' => 'due',
                    ]);
                    $emiEntry
                        ->emiDetails()
                        ->where('status', '=', 'settlement_processing')
                        ->update([
                            'status' => 'due',
                        ]);
                }
            }
        }

        if ($collection->status == 'approved') {
            if ($collection->source_type == EMIEntryDetail::class) {
                $emiEntryDetail = EMIEntryDetail::find($collection->source_id);
                if ($emiEntryDetail) {
                    $emiEntryDetail->update([
                        'status' => 'paid',
                    ]);
                }
            }
            if ($collection->source_type == EMIEntry::class) {
                $emiEntry = EMIEntry::find($collection->source_id);
                if ($emiEntry) {
                    $emiEntry->update([
                        'status' => 'paid',
                    ]);
                    $emiEntry
                        ->emiDetails()
                        ->where('status', '=', 'settlement_processing')
                        ->update([
                            'status' => 'early_settlement_paid',
                        ]);
                }
            }

            $this->makeDummyTransaction($collection);

            // dd($collection->transactions);
        }

        DB::commit();


    }

    public function delete(Collection $collection)
    {
        if ($collection->source_type == EMIEntryDetail::class) {
            $emiEntryDetail = EMIEntryDetail::find($collection->source_id);
            if ($emiEntryDetail) {
                $emiEntryDetail->update([
                    'status' => 'due',
                ]);
            }
        }
        if ($collection->source_type == EMIEntry::class) {
            $emiEntry = EMIEntry::find($collection->source_id);
            if ($emiEntry) {
                $emiEntry->update([
                    'status' => 'due',
                ]);
                $emiEntry
                    ->emiDetails()
                    ->where('status', '=', 'settlement_processing')
                    ->update([
                        'status' => 'due',
                    ]);
            }
        }
        $collection->transactions()->delete();
        $collection->payments()->delete();
        $collection->collectionFrom()->dissociate();
        $collection->delete();
    }

    public function show($id)
    {
        return Collection::with([
            'payments',
            'collectionFrom'
        ])->findOrFail($id);
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
        $emis = EmiEntry::pluck('id', 'emi_number')->toArray();

        // Process each payment entry
        foreach ($jsonData['payments'] as $payment) {
            // Payment mode validation
            $validModes = ['Cash', 'Cheque', 'Online Deposit', 'bKash', 'Nagad', 'Rocket', 'Card', 'EMI', 'Card Payment', 'AIT', 'Waiver'];
            if (!in_array($payment['pay_mode'], $validModes)) {
                throw new \Exception("Invalid payment mode: {$payment['pay_mode']}");
            }
            // dd( $payment);

            // Map bank references
            $bankId = $payment['pay_mode'] === 'Cheque'
                ? ($banks[$payment['bank_name']] ?? null)
                : ($accounts[$payment['bank_name']] ?? null);

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
     * Store a new payment from a json file
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Modules\Account\Models\Payments\MakePayment
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

        return $this->handleDirectImport($this, $jsonData);
    }

    /**
     * Function to map deposit data from JSON
     * Finds bank ID and employee/user ID based on deposit information
     */
    public function mapDepositData($depositInfo)
    {
        // $depositInfo =$depositData;

        // Find bank ID by bank name
        $bankId = null;
        if (isset($depositInfo['bank'])) {
            $bank = BankAccount::where('account_name', $depositInfo['bank'])->first();
            $bankId = $bank ? $bank->getAccount()->id : null;
        }

        // Find employee/user ID by deposite_by name
        $userId = null;
        if (isset($depositInfo['deposite_by'])) {
            // First try to find in users table
            $user = Employee::where('full_name', $depositInfo['deposite_by'])->first();
            // dd($user);

            $userId = $user ? $user->user_id : 1;
        }
        return [
            'head_id' => $bankId,
            'remarks' => $depositInfo['remark'] ?? "Json Deposits",
            'status' => $depositInfo['type'] == 'cash' ? 'cashed' : 'deposited' ?? null,
            'charge' => $depositInfo['charge'] ?? null,
            'deposit_date' => $depositInfo['date'] ?? null,
            'deposited_by' => $userId ?? null
        ];
    }

    /**
     * Handle direct data import from API request
     */
    public function handleDirectImport($service, $data)
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
                $mappedData = $this->mapJson($item);
                $collection = $this->store($mappedData['validate'], $mappedData['payments'])["collection"] ?? null;

                //diposit check
                foreach ($collection->payments as $payment) {
                    if ($payment->pay_mode == 'Cheque') {
                        // cheque entry
                        $payment->load('chequeVerification');
                        // dd($collection, $payment->chequeVerification, $mappedData['payments']['diposit'] );
                        if ($payment->chequeVerification && $mappedData['payments']['diposit'][0]) {
                            // update
                            // dd($mappedData['payments']['diposit'][0], );
                            $payment->chequeVerification()->update($this->mapDepositData($mappedData['payments']['diposit'][0]));
                            //  dd($payment->chequeVerification);
                            $payment->chequeVerification->refresh();
                            if ($mappedData['payments']['diposit'][0]['type'] == 'bank') {

                                app(ChequeVerificationService::class)->makeBankTransaction($payment->chequeVerification);
                            } else {

                                app(ChequeVerificationService::class)->makeCashTransaction($payment->chequeVerification);
                            }
                        }


                    }
                }

                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        DB::commit();

        $message = "Import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'saved_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], empty($errors) ? 200 : 207); // 207 Multi-Status if partial success
    }
}
