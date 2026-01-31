<?php

namespace Modules\Account\Services\IOURequisition;

use Modules\Account\Models\IOURequisition\IOURequisitionEntry;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\IOURequisition\IOUReturn;
use Modules\HRMS\Models\Employee;
use Illuminate\Support\Str;

class IOURequisitionEntryService
{
    
    public function getAll(int $limit = 20) {
        return IOURequisitionEntry::query()
                ->searchByFields(['type', 'status'])
            ->filterByDateRange('date')
                ->paginate($limit);
    }

    
    public function generateIOURequisitionEntryNumber()
    {
        $today = date('Ymd');
        $authUser = auth()->user()->id;
        $count = IOURequisitionEntry::whereDate('created_at', $today)
            ->where('created_by', $authUser)
            ->count();
        return sprintf('IOU%s%06d', $today, $count + 1);
    }
    
    public function store(array $data)
    {
        if (!isset($data['i_o_u_requition_entry_id'])) {
            $data['i_o_u_requition_entry_id'] = $this->generateIOURequisitionEntryNumber();
        }

        return IOURequisitionEntry::create($data);
    }

    public function update(IOURequisitionEntry $iOURequisitionEntry, array $data)
    {
        $iOURequisitionEntry->update($data);
        return $iOURequisitionEntry;
    }

    public function delete(IOURequisitionEntry $iOURequisitionEntry)
    {
        $iOURequisitionEntry->delete();
    }

    public function show($id)
    {
        return IOURequisitionEntry::with('employee')->findOrFail($id);
    }

    public function markAsPaid(IOURequisitionEntry $entry): bool
    {
        DB::beginTransaction();
        $entry->update([
            'status' => 'paid',
        ]);
        $this->makeDummyTransaction($entry);

        DB::commit();
        return true;
    }

    public function processReturn(IOURequisitionEntry $entry, $bankAccountId, $remarks = null)
    {
        DB::beginTransaction();

        $iouReturn = IOUReturn::create([
            'entry_id' => $entry->id,
            'bank_account_id' => $bankAccountId,
            'remarks' => $remarks,
            'amount' => $entry->request_amount,
        ]);

        $entry->update([
            'status' => 'returned',
        ]);

        $this->makeDummyTransactionForReturn($iouReturn);

        DB::commit();
        return $iouReturn;
    }

    public function makeDummyTransaction(IOURequisitionEntry $entry)
    {
        $entry->transactions()->delete();

        $cashAccount = auth()->user()->employee->getAccount();

        $entry->transactions()->create([
            'account_id' => $cashAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $entry->id,
            'amount' => $entry->request_amount,
            'debit_amount' => 0,
            'credit_amount' => $entry->request_amount,
            'description' => 'Staff Advance Given',
        ]);

        $entry->transactions()->create([
            'account_id' => $entry->employee->getStaffAdvanceAccount()->id,
            'balance_type' => 'debit',
            'invoice_no' => $entry->id,
            'amount' => -$entry->request_amount,
            'debit_amount' => $entry->request_amount,
            'credit_amount' => 0,
            'description' => 'Staff Advance Given',
        ]);
    }
    
    public function makeDummyTransactionForReturn(IOUReturn $iouReturn)
    {
        $iouReturn->transactions()->delete();

        $bankAccount = BankAccount::findOrFail($iouReturn->bank_account_id)->getAccount();
        
        $iouReturn->transactions()->create([
            'account_id' => $bankAccount->id,
            'balance_type' => 'debit',
            'invoice_no' => $iouReturn->id,
            'debit_amount' => $iouReturn->amount,
            'credit_amount' => 0,
            'description' => 'Staff Advance Return',
        ]);

        $iouReturn->transactions()->create([
            'account_id' => $iouReturn->entry->employee->getStaffAdvanceAccount()->id,
            'balance_type' => 'credit',
            'invoice_no' => $iouReturn->id,
            'debit_amount' => 0,
            'credit_amount' => $iouReturn->amount,
            'description' => 'Staff Advance Return',
        ]);
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        // Map employee name to ID
        $employee = Employee::where('full_name', $jsonData['employee_name'])
            ->first();
            
        if (!$employee) {
            throw new \Exception("Employee not found: {$jsonData['employee_name']}");
        }

        // Validate type
        if (!in_array($jsonData['type'], ['Expense', 'Advance'])) {
            throw new \Exception("Invalid type: {$jsonData['type']}. Must be 'Expense' or 'Advance'");
        }

        return [
            'date' => $jsonData['date'] ?? now()->toDateString(),
            'type' => $jsonData['type'],
            'employee_id' => $employee->id,
            'request_amount' => $jsonData['request_amount'],
            'remarks' => $jsonData['remarks'] ?? null,
            'status' => $jsonData['status'] ?? 'pending',
        ];
    }

    /**
     * Store data from JSON file
     */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

        // Ensure directory exists
        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        // Create file if it doesn't exist
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'JSON file is empty.');
        }

        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $this->store($mappedData);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "IOU Requisition Entries import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }
}