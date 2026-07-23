<?php

namespace Modules\Purchase\Services;

use App\Traits\S3FileHandler;
use Illuminate\Support\Str;
use Modules\Account\Models\Account;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Models\OfficePurchase;
use Modules\Purchase\Models\Vendor;

class OfficePurchaseService
{
    use S3FileHandler;

        private function getOPNumber()
    {
        $today = date('Y-m-d');

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        // Count today's purchase orders created by this user
        $todayOrders = OfficePurchase::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        // Generate PO number in required format
        $poNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-FP-%05d',
            $authUserBranch,        // Branch ID (2 digits, padded)
            $authUserBranchType,    // Branch Type (2 digits, padded)
            date('Ymd'),            // YYYYMMDD
            $authUser,              // User ID (6 digits, padded)
            $todayOrders + 1        // Count of today’s entries (5 digits, padded)
        );

        return $poNumber;
    }

    public function getAll(int $limit = 20) {
        return OfficePurchase::query()
        ->searchByFields(['invoice_no', 'date'])
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        if(!isset($data['invoice_no'])){
            $data['invoice_no'] = $this->getOPNumber();
        }
        // if (isset($data['file_upload'])) {
        //     $data['file_upload'] = $this->uploadFile($data['file_upload'], 'file_upload');
        // }
        return OfficePurchase::create($data);
    }

    public function update(OfficePurchase $officePurchase, array $data)
    {
        // if (isset($data['file_upload'])) {
        //     $data['file_upload'] = $this->uploadFile($data['file_upload'], 'file_upload');
        // }
        $officePurchase->update($data);
        return $officePurchase;
    }
    public function makeDummyTransaction(OfficePurchase $officePurchase)
    {

        if(!$officePurchase->vendor){
            return;
        }

        //debit 
            //Inventory Account
            $expenseAccount = Account::query()->where('account_number', '5000')->first();

            $officePurchase->transactions()->create([
                'account_id'            => $expenseAccount->id,
                'balance_type'          => "debit",
                'invoice_no'            => $officePurchase->invoice_no,
                'debit_amount'          => $officePurchase->bill_amount,
                'credit_amount'         => 0,
                'description'           => "Office Purchase Created. #" . $officePurchase->invoice_no,
                'transaction_date'      => $officePurchase->date
            ]);
        

        //cre
        // Accounts Payable
        $AccountsPayable = $officePurchase->vendor->getAccount();

        $officePurchase->transactions()->create([
            'account_id'            => $AccountsPayable->id,
            'balance_type'          => "credit",
            'invoice_no'            => $officePurchase->invoice_no,
            'debit_amount'          => 0,
            'credit_amount'         => $officePurchase->bill_amount,
            'description'           => "Office Purchase Created. #" . $officePurchase->invoice_no,
            'transaction_date'      => $officePurchase->date
        ]);
        // // Delete existing transactions
       
    }
    public function delete($officePurchase)
    {
        $officePurchase->delete();
    }

    public function show($id)
    {
        return OfficePurchase::findOrFail($id);
    }


    function mapJson(array $jsonData): array
    {
        // Map vendor name to ID
        $vendorId = Vendor::where('company_name', $jsonData['vendor_name'])
            ->value('id') ?? throw new \Exception("Vendor not found: {$jsonData['vendor_name']}");

        // Build data array exactly as expected by your service
        $data = [
            'vendor_id' => $vendorId,
            'invoice_no' => $jsonData['invoice_no'],
            'date' => $jsonData['date'],
            'reference_bill' => $jsonData['reference_bill'],
            'particular' => $jsonData['particular'],
            'bill_amount' => $jsonData['bill_amount'],
            'remarks' => $jsonData['remarks'] ?? null,
            'file_upload' => $jsonData['file_upload'] ?? null,
            // 'invoice_no' will be added by the controller (as in your code)
        ];

        return $data;
    }
    
    public function storeFromJsonFile()
    {
        $jsonFile = storage_path('app/json_formats/'.Str::snake(request()->input('name')).'.json');
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }
        $data = json_decode(file_get_contents($jsonFile), true);
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

        $savedCount = 0;
        $errors = [];

        DB::beginTransaction();
        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        foreach ($items as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $this->store($mappedData);
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
}
