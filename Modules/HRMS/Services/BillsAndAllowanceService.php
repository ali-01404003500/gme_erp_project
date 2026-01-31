<?php

namespace Modules\HRMS\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\GeneralExpense;
use Modules\HRMS\Models\TransportExpense;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\ExpenseType;
use Modules\HRMS\Models\Settings\TransportType;
use Modules\Account\Models\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BillsAndAllowanceService
{
    // $transportTotal = $this->transportExpenses->sum('final_approved_amount');
        // $generalTotal = $this->generalExpenses->sum('final_approved_amount');
    
    public function getAll(int $limit = 20) {
        return BillsAndAllowance::query()
        ->with(['transportExpenses', 'generalExpenses', 'employee'])
        ->searchByFields(['employee_id', 'date_of_bill_claim'])
        
        ->when(request()->filled('from') && request()->filled('to'), function ($qr) {
            $qr->whereBetween('date_of_bill_claim', [request('from'), request('to')]);
        })
        ->paginate($limit);
    }

    public function create($request)
    {
        DB::beginTransaction();
        $validatedMain = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_of_bill_claim' => 'required|date',
        ]);
    
        $result['bills'] = BillsAndAllowance::create($validatedMain);
        $result['transportExpense'] = [];
        $result['generalExpense'] = [];
    
        // Transport Expenses
        $transportCount = count($request->input('date_of_expense', []));
        for ($i = 0; $i < $transportCount; $i++) {
            $date = $request->input("date_of_expense.$i");
            $from = $request->input("from_location.$i");
            $to = $request->input("to_location.$i");
            $amount = $request->input("transport_amount.$i");
    
            if ($date || $from || $to || $amount) {
                $data = Validator::make($request->all(), [
                    "date_of_expense.$i" => 'required|date',
                    "from_location.$i" => 'required|string',
                    "to_location.$i" => 'required|string',
                    "transport_by.$i" => 'required|string',
                    "distance.$i" => 'required|integer',
                    "expense_description.$i" => 'required|string',
                    "transport_amount.$i" => 'required|numeric|min:1',
                    "transport_settlement_amount.$i" => 'required|numeric|min:1',
                    "receipts_invoices_$i" => 'nullable|string',
                    "supporting_documents_$i" => 'nullable|string',
                ])->validate();
    
                $result['transportExpense'][] = TransportExpense::create([
                    'bills_and_allowance_id' => $result['bills']->id,
                    'date_of_expense' => $request->input("date_of_expense.$i"),
                    'from_location' => $request->input("from_location.$i"),
                    'to_location' => $request->input("to_location.$i"),
                    'transport_by' => $request->input("transport_by.$i"),
                    'distance' => $request->input("distance.$i"),
                    'expense_description' => $request->input("expense_description.$i"),
                    'amount' => $request->input("transport_amount.$i"),
                    'settlement_amount' => $request->input("transport_settlement_amount.$i"),
                    'receipts_invoices' => $request->input("receipts_invoices_$i"),
                    'supporting_documents' => $request->input("supporting_documents_$i"),
                ]);
            }
        }
    
        // General Expenses
        $generalCount = count($request->input('expense_date', []));
        for ($i = 0; $i < $generalCount; $i++) {
            $date = $request->input("expense_date.$i");
            $type = $request->input("expense_type.$i");
            $amount = $request->input("general_amount.$i");
    
            if ($date || $type || $amount) {
                $data = Validator::make($request->all(), [
                    "expense_date.$i" => 'required|date',
                    "expense_type.$i" => 'required|string',
                    "general_expense_description.$i" => 'required|string',
                    "general_amount.$i" => 'required|numeric|min:0.01',
                    "general_settlement_amount.$i" => 'required|numeric|min:0.01',
                    "receipts_invoices.$i" => 'nullable|string',
                    "supporting_documents.$i" => 'nullable|string',
                ])->validate();
    
                $result['generalExpense'][] = GeneralExpense::create([
                    'bills_and_allowance_id' => $result['bills']->id,
                    'expense_date' => $request->input("expense_date.$i"),
                    'expense_type' => $request->input("expense_type.$i"),
                    'expense_description' => $request->input("general_expense_description.$i"),
                    'amount' => $request->input("general_amount.$i"),
                    'settlement_amount' => $request->input("general_settlement_amount.$i"),
                    'receipts_invoices' => $request->input("general_receipts_invoices_$i"),
                    'supporting_documents' => $request->input("general_supporting_documents_$i"),
                ]);
            }
        }

        DB::commit();
    
        return $result;
    }

    public function createApi($data, $transportExpense=[], $generalExpense=[])
    {
        DB::beginTransaction();

        $bill = BillsAndAllowance::create($data);

        if(!empty($transportExpense)){
            $bill->transportExpenses()->createMany($transportExpense);
        }
        if(!empty($generalExpense)){
            $bill->generalExpenses()->createMany($generalExpense);
        }
        $bill->refresh();

        DB::commit();

        return [
            'bill' => $bill,
            'transportExpenses' => $bill->transportExpenses,
            'generalExpenses' => $bill->generalExpenses,
        ];
    }
        
    public function update($billsAndAllowance, array $data,  Request $request)
    {
        DB::beginTransaction();
        $result['bills'] = $billsAndAllowance;
        $result['bills']->update($data);
        $result['bills']->transportExpenses()->delete();
        $result['bills']->generalExpenses()->delete();

        $result['transportExpense'] = [];
        $result['generalExpense'] = [];
    
        // Transport Expenses
        $transportCount = count($request->input('date_of_expense', []));
        for ($i = 0; $i < $transportCount; $i++) {
            $date = $request->input("date_of_expense.$i");
            $from = $request->input("from_location.$i");
            $to = $request->input("to_location.$i");
            $amount = $request->input("transport_amount.$i");
    
            if ($date || $from || $to || $amount) {
                $data = Validator::make($request->all(), [
                    "date_of_expense.$i" => 'required|date',
                    "from_location.$i" => 'required|string',
                    "to_location.$i" => 'required|string',
                    "transport_by.$i" => 'required|string',
                    "distance.$i" => 'required|integer',
                    "expense_description.$i" => 'required|string',
                    "transport_amount.$i" => 'required|numeric|min:1',
                    "transport_settlement_amount.$i" => 'required|numeric|min:1',
                    "receipts_invoices_$i" => 'nullable|string',
                    "supporting_documents_$i" => 'nullable|string',
                ])->validate();
    
                $result['transportExpense'][] = TransportExpense::create([
                    'bills_and_allowance_id' => $result['bills']->id,
                    'date_of_expense' => $request->input("date_of_expense.$i"),
                    'from_location' => $request->input("from_location.$i"),
                    'to_location' => $request->input("to_location.$i"),
                    'transport_by' => $request->input("transport_by.$i"),
                    'distance' => $request->input("distance.$i"),
                    'expense_description' => $request->input("expense_description.$i"),
                    'amount' => $request->input("transport_amount.$i"),
                    'settlement_amount' => $request->input("transport_settlement_amount.$i"),
                    'receipts_invoices' => $request->input("receipts_invoices_$i"),
                    'supporting_documents' => $request->input("supporting_documents_$i"),
                ]);
            }
        }
    
        // General Expenses
        $generalCount = count($request->input('expense_date', []));
        for ($i = 0; $i < $generalCount; $i++) {
            $date = $request->input("expense_date.$i");
            $type = $request->input("expense_type.$i");
            $amount = $request->input("general_amount.$i");
    
            if ($date || $type || $amount) {
                $data = Validator::make($request->all(), [
                    "expense_date.$i" => 'required|date',
                    "expense_type.$i" => 'required|string',
                    "general_expense_description.$i" => 'required|string',
                    "general_amount.$i" => 'required|numeric|min:0.01',
                    "general_settlement_amount.$i" => 'required|numeric|min:0.01',
                    "receipts_invoices.$i" => 'nullable|string',
                    "supporting_documents.$i" => 'nullable|string',
                ])->validate();
    
                $result['generalExpense'][] = GeneralExpense::create([
                    'bills_and_allowance_id' => $result['bills']->id,
                    'expense_date' => $request->input("expense_date.$i"),
                    'expense_type' => $request->input("expense_type.$i"),
                    'expense_description' => $request->input("general_expense_description.$i"),
                    'amount' => $request->input("general_amount.$i"),
                    'settlement_amount' => $request->input("general_settlement_amount.$i"),
                    'receipts_invoices' => $request->input("general_receipts_invoices_$i"),
                    'supporting_documents' => $request->input("general_supporting_documents_$i"),
                ]);
            }
        }
        
        DB::commit();

        return $result;
    }

    public function updateApi($id, $data, $transportExpense=[], $generalExpense=[])
    {
        DB::beginTransaction();
    
        $bill = BillsAndAllowance::findOrFail($id);
    
        $bill->update($data);
    
        if (!empty($transportExpense)) {
            $bill->transportExpenses()->delete();
            $bill->transportExpenses()->createMany($transportExpense);
        }
    
        if (!empty($generalExpense)) {
            $bill->generalExpenses()->delete();
            $bill->generalExpenses()->createMany($generalExpense);
        }
    
        $bill->refresh();
    
        DB::commit();
    
        return [
            'bill' => $bill,
            'transportExpenses' => $bill->transportExpenses,
            'generalExpenses' => $bill->generalExpenses,
        ];
    }

    public function delete(BillsAndAllowance $billsAndAllowance)
    {
        $billsAndAllowance->transportExpenses()->delete();
        $billsAndAllowance->generalExpenses()->delete();
        $billsAndAllowance->delete();
    }

    public function show($id)
    {
        return BillsAndAllowance::with('transportExpenses', 'generalExpenses', 'employee')->findOrFail($id);
    }

    /**
     * Map JSON data to Bills and Allowance format
     */
    public function mapJson(array $jsonData): array
    {
        Log::info('Starting mapJson', ['employee_name' => $jsonData['employee_name'] ?? null]);

        // Find employee
        $employee = null;
        if (!empty($jsonData['employee_name'])) {
            $employee = Employee::where('full_name', $jsonData['employee_name'])
                ->first();
        } 

        if (!$employee) {
            throw new \Exception("Employee not found: " . ($jsonData['employee_name'] ?? $jsonData['employee_id'] ?? 'Unknown'));
        }

        Log::info('Employee found', ['id' => $employee->id]);

        // Transport expenses
        $transportExpenses = [];
        if (!empty($jsonData['transport_expenses'])) {
            foreach ($jsonData['transport_expenses'] as $expense) {
                $transportType = TransportType::where('name', $expense['transport_by'])->first();
                if (!$transportType) {
                    throw new \Exception("Transport type not found: {$expense['transport_by']}");
                }

                $transportExpenses[] = [
                    'date_of_expense' => $expense['date_of_expense'],
                    'from_location' => $expense['from_location'],
                    'to_location' => $expense['to_location'],
                    'transport_by' => $transportType->id,
                    'distance' => $expense['distance'] ?? 0,
                    'expense_description' => $expense['expense_description'] ?? '',
                    'amount' => $expense['amount'],
                    'settlement_amount' => $expense['settlement_amount'] ?? $expense['amount'],
                    'receipts_invoices' => $expense['receipts_invoices'] ?? null,
                    'supporting_documents' => $expense['supporting_documents'] ?? null,
                    // 'account_head_name' => $expense['account_head'] ?? null,
                ];
            }
        }

        // General expenses
        $generalExpenses = [];
        if (!empty($jsonData['general_expenses'])) {
            foreach ($jsonData['general_expenses'] as $expense) {
                $expenseType = ExpenseType::where('name', $expense['expense_type'])->first();
                if (!$expenseType) {
                    throw new \Exception("Expense type not found: {$expense['expense_type']}");
                }

                $generalExpenses[] = [
                    'expense_date' => $expense['expense_date'],
                    'expense_type' => $expenseType->id,
                    'expense_description' => $expense['expense_description'] ?? '',
                    'amount' => $expense['amount'],
                    'settlement_amount' => $expense['settlement_amount'] ?? $expense['amount'],
                    'receipts_invoices' => $expense['receipts_invoices'] ?? null,
                    'supporting_documents' => $expense['supporting_documents'] ?? null,
                    // 'account_head_name' => $expense['account_head'] ?? null,
                ];
            }
        }

        return [
            'employee_id' => $employee->id,
            'date_of_bill_claim' => $jsonData['date_of_bill_claim'],
            'status' => $jsonData['status'] ?? 'pending',
            'transport_expenses' => $transportExpenses,
            'general_expenses' => $generalExpenses,
        ];
    }

    private function processImportData(array $jsonData)
    {
        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $index => $item) {
            DB::beginTransaction();
            try {
                $mappedData = $this->mapJson($item);
                $status = $mappedData['status'];
                
                $billData = [
                    'employee_id' => $mappedData['employee_id'],
                    'date_of_bill_claim' => $mappedData['date_of_bill_claim'],
                ];

                $result = $this->createApi(
                    $billData, 
                    $mappedData['transport_expenses'], 
                    $mappedData['general_expenses']
                );
                
                $bill = $result['bill'];
                $bill->refresh();
                $bill->load(['transportExpenses', 'generalExpenses']);

                // Process approvals
                if (in_array($status, ['team_leader_check', 'accounts_check', 'approved', 'paid'])) {
                    $this->processApprovalStatus($bill, $status, $item);
                }

                // Process payment if status is 'paid'
                if ($status === 'paid') {
                    $this->processPaymentFromJson($bill, $item);
                }

                DB::commit();
                $savedCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                Log::error("Import error", ['row' => $index + 1, 'error' => $e->getMessage()]);
            }
        }

        return ['saved_count' => $savedCount, 'errors' => $errors];
    }

    private function processApprovalStatus(BillsAndAllowance $bill, string $status, array $jsonData)
    {
        try {
            // Team Leader
            if (in_array($status, ['team_leader_check', 'accounts_check', 'approved', 'paid'])) {
                $bill->update([
                    'checked_by_team_leader' => auth()->id(),
                    'checked_by_team_leader_date' => now(),
                    'checked_by_team_leader_comments' => $jsonData['checked_by_team_leader_comments'] ?? 'Imported',
                    'status' => 'team_leader_check',
                ]);

                if (!empty($jsonData['transport_expenses'])) {
                    foreach ($jsonData['transport_expenses'] as $idx => $exp) {
                        $bill->transportExpenses[$idx]?->update([
                            'team_leader_approved_amount' => $exp['team_leader_approved_amount'] ?? $exp['amount']
                        ]);
                    }
                }

                if (!empty($jsonData['general_expenses'])) {
                    foreach ($jsonData['general_expenses'] as $idx => $exp) {
                        $bill->generalExpenses[$idx]?->update([
                            'team_leader_approved_amount' => $exp['team_leader_approved_amount'] ?? $exp['amount']
                        ]);
                    }
                }
            }

            // Accounts
            if (in_array($status, ['accounts_check', 'approved', 'paid'])) {
                $bill->update([
                    'checked_by_accounts' => auth()->id(),
                    'checked_by_accounts_date' => now(),
                    'checked_by_accounts_comments' => $jsonData['checked_by_accounts_comments'] ?? 'Imported',
                    'status' => 'accounts_check',
                ]);

                if (!empty($jsonData['transport_expenses'])) {
                    foreach ($jsonData['transport_expenses'] as $idx => $exp) {
                        $bill->transportExpenses[$idx]?->update([
                            'accounts_approved_amount' => $exp['accounts_approved_amount'] ?? $exp['amount']
                        ]);
                    }
                }

                if (!empty($jsonData['general_expenses'])) {
                    foreach ($jsonData['general_expenses'] as $idx => $exp) {
                        $bill->generalExpenses[$idx]?->update([
                            'accounts_approved_amount' => $exp['accounts_approved_amount'] ?? $exp['amount']
                        ]);
                    }
                }
            }

            // Final Approval
            if (in_array($status, ['approved', 'paid'])) {
                $bill->update([
                    'final_approved_by' => auth()->id(),
                    'final_approved_date' => now(),
                    'final_approved_comments' => $jsonData['final_approved_comments'] ?? 'Imported',
                    'status' => 'approved',
                ]);

                if (!empty($jsonData['transport_expenses'])) {
                    foreach ($jsonData['transport_expenses'] as $idx => $exp) {
                        $bill->transportExpenses[$idx]?->update([
                            'final_approved_amount' => $exp['final_approved_amount'] ?? $exp['amount']
                        ]);
                    }
                }

                if (!empty($jsonData['general_expenses'])) {
                    foreach ($jsonData['general_expenses'] as $idx => $exp) {
                        $bill->generalExpenses[$idx]?->update([
                            'final_approved_amount' => $exp['final_approved_amount'] ?? $exp['amount']
                        ]);
                    }
                }

                // Create Step 1 journal entry
                app(\Modules\Account\Services\Payments\PettyCashPaymentService::class)
                    ->createStep1JournalEntry($bill->id);
            }
        } catch (\Exception $e) {
            throw new \Exception("Approval failed: " . $e->getMessage());
        }
    }

    /**
     * Process payment from JSON data
     */
    private function processPaymentFromJson(BillsAndAllowance $bill, array $jsonData)
    {
        try {
            // Map account heads from JSON
            $accountHeads = [];

            // Map transport expense account heads
            if (!empty($jsonData['transport_expenses'])) {
                foreach ($jsonData['transport_expenses'] as $idx => $exp) {
                    $accountHeadName = $exp['account_head'] ?? null;
                    if ($accountHeadName) {
                        $account = Account::where('name', $accountHeadName)
                            ->orWhere('account_number', $accountHeadName)
                            ->first();
                        if (!$account) {
                            throw new \Exception("Account head not found: {$accountHeadName}");
                        }

                        $transportExpense = $bill->transportExpenses[$idx] ?? null;
                        if ($transportExpense) {
                            $accountHeads['transport_' . $transportExpense->id] = $account->id;
                        }
                    }
                }
            }

            // Map general expense account heads
            if (!empty($jsonData['general_expenses'])) {
                foreach ($jsonData['general_expenses'] as $idx => $exp) {
                    $accountHeadName = $exp['account_head'] ?? null;
                    if ($accountHeadName) {
                        $account = Account::where('name', $accountHeadName)
                            ->orWhere('account_number', $accountHeadName)
                            ->first();
                        
                        if (!$account) {
                            throw new \Exception("Account head not found: {$accountHeadName}");
                        }

                        $generalExpense = $bill->generalExpenses[$idx] ?? null;
                        if ($generalExpense) {
                            $accountHeads['general_' . $generalExpense->id] = $account->id;
                        }
                    }
                }
            }

            // Get payment account from JSON (instead of login user account)
            $paymentAccountName = $jsonData['payment_account'] ?? null;
            if (!$paymentAccountName) {
                throw new \Exception("Payment account not specified in JSON data");
            }

            $paymentAccount = Account::where('name', $paymentAccountName)
                ->orWhere('account_number', $paymentAccountName)
                ->first();

            if (!$paymentAccount) {
                throw new \Exception("Payment account not found: {$paymentAccountName}");
            }

            // Prepare payment data with payment account
            $paymentData = [
                'account_heads' => $accountHeads,
                'payment_account_id' => $paymentAccount->id, // NEW: Payment account from JSON
                'remarks' => $jsonData['payment_remarks'] ?? 'Payment processed via JSON import'
            ];

            // Process payment using PettyCashPaymentService
            $paymentService = app(\Modules\Account\Services\Payments\PettyCashPaymentService::class);
            $result = $paymentService->processPaymentForImport($bill->id, $paymentData);

            Log::info("Payment processed successfully for bill {$bill->id}");

            return $result;

        } catch (\Exception $e) {
            Log::error("Payment processing failed", [
                'bill_id' => $bill->id,
                'error' => $e->getMessage()
            ]);
            throw new \Exception("Payment processing failed: " . $e->getMessage());
        }
    }


    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'JSON file is empty.');
        }

        $result = $this->processImportData($jsonData);

        $message = "Bills and Allowance import completed. Successfully saved: {$result['saved_count']}";
        if (!empty($result['errors'])) {
            $message .= '. Errors: ' . implode('; ', $result['errors']);
        }

        return redirect()->back()->with('success', $message);
    }

    public function handleDirectImport($data)
    {
        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'No data provided.'], 422);
        }

        $items = isset($data[0]) ? $data : [$data];
        $result = $this->processImportData($items);

        $message = "Bills and Allowance import completed. Successfully saved: {$result['saved_count']}";
        if (!empty($result['errors'])) {
            $message .= '. Errors: ' . implode('; ', $result['errors']);
        }

        return response()->json([
            'success' => empty($result['errors']) || $result['saved_count'] > 0,
            'message' => $message,
            'saved_count' => $result['saved_count'],
            'error_count' => count($result['errors']),
            'errors' => $result['errors'],
        ], empty($result['errors']) ? 200 : 207);
    }
}