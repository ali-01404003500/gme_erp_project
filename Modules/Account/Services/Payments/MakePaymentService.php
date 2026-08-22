<?php

namespace Modules\Account\Services\Payments;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;
use Modules\CRM\Models\Customer\Broker;
use Modules\Account\Models\Account;

class MakePaymentService
{

    public function getAll(int $limit = 20)
    { 
        $query = MakePayment::query();

        if (!request('from_to') ) {
            $query->whereDate('created_at', now()->toDateString());
        } else {
            $query->filterByDateRange('created_at');
        }
        return $query->paginate($limit);
 
    }


    public function getPaymentIdForPayment()
    {
        try {
            $today = date('Y-m-d');
            $authUser = auth()->user()->id;

            // Count payments for this user today to determine the serial number
            $paymentToday = MakePayment::whereDate(DB::raw('DATE(created_at)'), $today)
                ->where('created_by', $authUser)
                ->count() ?? 0;

            // Generate payment number with the new format: PR-YYYYMMDD-USR-USERID-SERIAL NUMBER
            $paymentNumber = sprintf(
                'PR-%s-USR-%06d-%05d',
                date('Ymd'),
                $authUser,
                $paymentToday + 1
            );

            return $paymentNumber;
        } catch (\Illuminate\Database\QueryException $e) {
            return 'PR-20260101-USR-000001-00001';
        }
    }


    public function store(array $data, $payments = [])
    {
        DB::beginTransaction();

        $data['payment_id'] = $this->getPaymentIdForPayment();

        $payment_to = match ($data['payment_to_type']) {
            'supplier' => Supplier::find($data['payment_to_id']),
            'vendor' => Vendor::find($data['payment_to_id']),
            'broker' => Broker::find($data['payment_to_id']),
            'petty_cash_expense' => Account::find($data['payment_to_id']),
        };
        // dd($data, $payments);
        $makePayment = MakePayment::create([
            'payment_id' => $data['payment_id'],
            'amount' => $data['payments_total_amount'],
            'advance_amount' => $data['payments_advance_amount'],
            'date' => $payments['payments_date'][0] ?? now()->format('Y-m-d'),
            'payment_to_type' => get_class($payment_to),
            'payment_to_id' => $payment_to->id,
            'status' => $data['status'],
        ]);
        $result['payment'] = $makePayment;



        foreach ($payments['payments_pay_mode'] as $key => $payment) {
            // dd($payments);
            $paymentDetail = $makePayment->paymentDetails()->create([
                'pay_mode' => $payments['payments_pay_mode'][$key] ?? null,
                'amount' => $payments['payments_amount'][$key] ?? 0,
                'date' => $payments['payments_date'][$key] ?? null,
                'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                // 'branch_id' => $payments['branch_id'][$key] ?? null,
                'attachments' => $payments['payments_attachments'][$key] ?? null,
                'verified' => $payments['payments_verified'][$key] ?? false,
                'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                'remark' => $payments['payments_remark'][$key] ?? null,
                'paymentable_type' => get_class($makePayment),
                'paymentable_id' => $makePayment->id
            ]);
            $result['make_payment_details'][] = $paymentDetail;
        }
        $makePayment->refresh();

        /*if ($makePayment->status === 'approved') {
            $this->makeDummyTransaction($makePayment);
        }*/

        // dd($result);

        DB::commit();
        return $result;
    }

    public function storeForPurchases(array $data, EloquentCollection $paymentDetails, $requisition)
    {
        DB::beginTransaction();
        // dd($data, $payments);
        $data['payment_id'] = $this->getPaymentIdForPayment();

        $payment = MakePayment::updateOrCreate(
            [
                'source_id' => $requisition->id,
                'source_type' => get_class($requisition),
            ],
            [
                'payment_id' => $data['payment_id'],
                'amount' => $data['payments_total_amount'],
                'advance_amount' => $data['payments_advance_amount'],
                'status' => "pending",
                'date' => now()->format('Y-m-d'),
                'payment_to_type' => get_class($requisition->supplier),
                'payment_to_id' => $requisition->supplier->id,
            ],
        );

        $from = match ($data['payment_type']) {
            'supplier' => Supplier::find($data['payment_from']),
            default => null,
        };
        //   dd($from, $data['payment_type'], $data['payment_from']);

        $result[] = $payment;
        $payment->paymentDetails()->update([
            'make_payment_id' => null,
        ]);
        // dd($payments);
        foreach ($paymentDetails as $details) {
            $details->update([
                'make_payment_id' => $payment->id,
            ]);
        }
        // dd($payment->payments);
        // $payment->refresh();
        if ($payment->status == 'approved') {
            # code...
            $this->makeDummyTransaction($payment);
        }

        // dd( $result);
        DB::commit();
        return $payment;
    }



    /**
     * Here is a dummy transaction example
     * | Date       | Account                           | Debit (৳) | Credit (৳) |
     * | ---------- | --------------------------------- | --------- | ---------- |
     * | 2025-08-06 | Accounts Payable - Supplier -1    | 10,000    |            |
     * |            | Cash                              |           | 10,000     |
     * 
     * When supplier receive 10,000 with cash, then this will be the transaction
     * 
     * | Date       | Account                           | Debit (৳) | Credit (৳) |
     * | ---------- | --------------------------------- | --------- | ---------- |
     * | 2025-08-06 | Accounts Payable - Supplier -1    |           | 10,000     |
     * |            | Cash                              | 10,000    |            |
     * 
     * When supplier receive payment for advance, then this will be the transaction
     * 
     * | Date       | Account                           | Debit (৳) | Credit (৳) |
     * | ---------- | --------------------------------- | --------- | ---------- |
     * | 2025-08-06 | Advance - Supplier -1             |           | 15,000     |
     * |            | Cash                              | 15,000    |            |
     */
    public function makeDummyTransaction(MakePayment $makePayment)
    {
        //dd($makePayment);
        $makePayment->transactions()->delete();

        $paymentTo = $makePayment->paymentTo;

        if ($paymentTo instanceof Account) {
            $payableAccount = $paymentTo;
            $advanceAccount = null; // No advance for direct accounts
        } else {
            $payableAccount = method_exists($paymentTo, 'getAccount') ? $paymentTo->getAccount() : null;
            $advanceAccount = method_exists($paymentTo, 'getAdvanceAccount') ? $paymentTo->getAdvanceAccount() : null;
        }

        //debit 
        if ($makePayment->advance_amount > 0 && $advanceAccount) {
            $payableAccountId = $advanceAccount->id;
            $payableAmount = $makePayment->advance_amount;
        }
        else
        {
            $payableAccountId = $payableAccount->id;
            $payableAmount = $makePayment->amount - $makePayment->advance_amount;
        }

        $payableAccountId = $payableAccount->id;
        $payableAmount = $makePayment->amount;

        $makePayment->transactions()->create([
            'account_id' => $payableAccountId,
            'balance_type' => "debit",
            'invoice_no' => $makePayment->payment_id,
            'debit_amount' => $payableAmount,
            'credit_amount' => 0,
            'description' => "Payment Created. #" . $makePayment->payment_id,
            'transaction_date' => $makePayment->date

        ]);

        
        //credit
        foreach ($makePayment->paymentDetails as $payment) {
            if (in_array($payment->pay_mode, ['AIT', 'Waiver', 'Waiver Bad Debt'])) {
                if ($payment->pay_mode == 'AIT') {
                    $aitPyableAccount = Account::where('account_number', '201201')->first();
                    $makePayment->transactions()->create([
                        'account_id' => $aitPyableAccount->id,
                        'balance_type' => "credit",
                        'invoice_no' => $makePayment->payment_id,
                        'debit_amount' => 0,
                        'credit_amount' => $payment->amount,
                        'description' => "Payment Created. #" . $makePayment->payment_id,
                        'transaction_date' => $makePayment->date
                    ]);
                }
                continue;
            }
            if ($payment->bank) {
                $makePayment->transactions()->create([
                    'account_id' => $payment->bank->getAccount()->id,
                    'balance_type' => "credit",
                    'invoice_no' => $makePayment->payment_id,
                    'debit_amount' => 0,
                    'credit_amount' => $payment->amount,
                    'description' => "Payment Created. #" . $makePayment->payment_id,
                    'transaction_date' => $makePayment->date
                ]);
            }
            // dd($payment->bank->getAccount());
        }

        // dd( $makePayment->transactions);

    }

    public function update(MakePayment $makePayment, array $data, $payments = [])
    {
       
        DB::beginTransaction();

        $payment_to = match ($data['payment_to_type']) {
            'supplier' => Supplier::find($data['payment_to_id']),
            'vendor' => Vendor::find($data['payment_to_id']),
            'petty_cash_expense' => Account::find($data['payment_to_id']),
            default => null

        };
        // dd($payment_to);

        $updateData = [
            'amount' => $data['payments_total_amount'],
            'advance_amount' => $data['payments_advance_amount'],
            'date' => $payments['payments_date'][0] ?? null ?? now()->format('Y-m-d'),
            'payment_to_type' => get_class($payment_to),
            'payment_to_id' => $payment_to->id,
            'status' => $data['status'],
        ];

        // Handle verification and approval tracking
        if ($data['status'] === 'verified' && $makePayment->status !== 'verified') {
            $updateData['verified_by'] = auth()->id();
            $updateData['verified_at'] = now();
        }

        if ($data['status'] === 'approved' && $makePayment->status !== 'approved') {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
        }

        $makePayment->update($updateData);

        $makePayment->paymentDetails()->delete();

        foreach ($payments['payments_pay_mode'] as $key => $payment) {
            $makePayment->paymentDetails()->create([
                'pay_mode' => $payments['payments_pay_mode'][$key] ?? null,
                'amount' => $payments['payments_amount'][$key] ?? 0,
                'date' => $payments['payments_date'][$key] ?? null,
                'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                'attachments' => $payments['payments_attachments'][$key] ?? null,
                'verified' => $payments['payments_verified'][$key] ?? false,
                'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                'remark' => $payments['payments_remark'][$key] ?? null,
                'paymentable_type' => get_class($makePayment),
                'paymentable_id' => $makePayment->id
            ]);
        }

        $makePayment->refresh();

        if ($makePayment->status === 'approved') {
            $this->makeDummyTransaction($makePayment);
        }

        DB::commit();
        return $makePayment;
    }

    public function delete(MakePayment $makePayment)
    {
        $makePayment->delete();
    }

    public function show($id)
    {
        return MakePayment::findOrFail($id);
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
        $jsonFile = storage_path('app/json_formats/' . Str::snake(request()->input('name')) . '.json');
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }
        $data = json_decode(file_get_contents($jsonFile), true);
        // dd($data);
        return $this->handleDirectImport($data);
    }

    /**
     * Handle direct data import from API request
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

        $result = [];
        foreach ($items as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $result[] = $this->store($mappedData['data'], $mappedData['payments']);
                // dd($mappedData);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        // dd($items);

        // dd($result);
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
     * Map JSON data to the format expected by the store method.
     */
    public function mapJson(array $jsonData): array
    {
        $paymentToType = $jsonData['payment_to_type'];
        $paymentToNameKey = 'payment_to_' . $paymentToType;
        $paymentToName = $jsonData[$paymentToNameKey] ?? null;

        if (!$paymentToName) {
            throw new \Exception("The key '{$paymentToNameKey}' is missing from the JSON data.");
        }

        if ($paymentToType === 'supplier') {
            $paymentTo = Supplier::where('company_name', $paymentToName)->firstOrFail();
        } elseif ($paymentToType === 'vendor') {
            $paymentTo = Vendor::where('company_name', $paymentToName)->firstOrFail();
        } else {
            throw new \Exception("Invalid payment_to_type: {$paymentToType}");
        }

        $totalAmount = 0;
        foreach ($jsonData['payments'] ?? [] as $payment) {
            $totalAmount += $payment['amount'] ?? 0;
        }

        $data = [
            'payment_to_type' => $paymentToType,
            'payment_to_id' => $paymentTo->id,
            'payments_total_amount' => $totalAmount,
            'payments_advance_amount' => $jsonData['payments_advance_amount'] ?? 0,
            'status' => $jsonData['status'] ?? 'pending',
        ];

        $payments = [
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

        foreach ($jsonData['payments'] ?? [] as $payment) {
            $bankId = null;
            if (!empty($payment['bank_name'])) {
                $bank = BankAccount::where('account_name', $payment['bank_name'])->first();
                $bankId = $bank ? $bank->id : null;
            }

            $payments['payments_pay_mode'][] = $payment['payment_mode'];
            $payments['payments_bank_id'][] = $bankId;
            $payments['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
            $payments['payments_date'][] = $payment['date'] ?? now()->toDateString();
            $payments['payments_amount'][] = $payment['amount'] ?? 0;
            $payments['payments_attachments'][] = $payment['attachment'] ?? null;
            $payments['payments_remark'][] = $payment['remark'] ?? null;
            $payments['payments_verified'][] = false; // Default to not verified
        }

        return [
            'data' => $data,
            'payments' => $payments,
        ];
    }



}
