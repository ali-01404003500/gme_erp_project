<?php

namespace Modules\Account\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Account\Models\AdvanceChequeEntry;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\CRM\Models\Customer\Customer;

class AdvanceChequeEntryService
{
    public function getAll(int $limit = 20)
    {
        return AdvanceChequeEntry::query()
        ->searchByFields(['receipt_no', 'customer_id'])
        ->filterByDateRange('collection_date')
        ->orderBy('created_at', 'desc')
        ->paginate($limit);
    }

    public function store(array $data, array $details)
    {
        // dd($data, $details);
        try{
        DB::beginTransaction();
        $data['total_amount'] = array_sum($details['amount']);
        $data['receipt_no'] = $this->getReceiptNo();
        $advanceChequeEntry = AdvanceChequeEntry::create($data);
        foreach ($details['bank_ids'] as $key => $bank_id) {
            $advanceChequeEntry->details()->create([
                'bank_id' => $bank_id,
                'emi_entry_details_id' => $details['emi_detail_id'][$key] ?? null,
                'branch_id' => $details['branch_ids'][$key],
                'cheque_no' => $details['cheque_no'][$key],
                'cheque_date' => $details['cheque_date'][$key],
                'amount' => $details['amount'][$key],
                'document' => $details['documents'][$key],
                'is_security_cheque' => $details['is_security_cheque'][$key],
            ]);
        }
        DB::commit();

        return $advanceChequeEntry;
        }catch(\Throwable $th){
            DB::rollBack();
            throw $th;
        }
    }

    public function getReceiptNo()
    {
        $authUser = auth()->user()->id;
        $today = date('Ymd');
        $prefix = 'ACR-';
        $sequence = $this->getSequence($prefix, $today);
        return sprintf('%s%s-USR-%05d-%06d', $prefix, $today, $sequence, $authUser, $sequence);
    }

    protected function getSequence(string $prefix, string $today)
    {
        $count = AdvanceChequeEntry::whereDate('created_at', $today)->count();
        return $count + 1;
    }

    public function update(AdvanceChequeEntry $advanceChequeEntry, array $data, array $details)
    {
        $data['total_amount'] = array_sum($details['amount']);
        $advanceChequeEntry->update($data);

        $advanceChequeEntry->details()->delete();
        foreach ($details['bank_ids'] as $key => $bank_id) {
            $advanceChequeEntry->details()->create([
                'bank_id' => $bank_id,
                'branch_id' => $details['branch_ids'][$key],
                'cheque_no' => $details['cheque_no'][$key],
                'cheque_date' => $details['cheque_date'][$key],
                'amount' => $details['amount'][$key],
                'document' => $details['documents'][$key],
                'is_security_cheque' => $details['is_security_cheque'][$key],
            ]);
        }

        return $advanceChequeEntry;
    }

    public function delete(AdvanceChequeEntry $advanceChequeEntry)
    {
        $advanceChequeEntry->delete();
    }

    public function show($id)
    {
        return AdvanceChequeEntry::findOrFail($id);
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
        $jsonFile = storage_path('app/json_formats/'.Str::snake(request()->input('name')).'.json');
        if(!file_exists($jsonFile)){
            file_put_contents($jsonFile, json_encode([]));
        }
        $data = json_decode(file_get_contents($jsonFile), true);
        // dd($data);
        $this->handleDirectImport($data);
        /**
         * todo: run loop for eatch implement json proccess here 
         */
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
                $this->store($mappedData['data'], $mappedData['details']);
                // dd($mappedData);
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
     * Map JSON data to the format expected by the store method.
     */
    public function mapJson(array $jsonData): array
    {
        // dd($jsonData);
        $customer = Customer::where('customer_id', $jsonData['customer_name'])->firstOrFail();

        if (!empty($jsonData['emi_number'])) {
            $emiEntry = EMIEntry::where('emi_number', $jsonData['emi_number'])->first();
            // dd($emiEntry);
            if ($emiEntry) {
                $dueEmiDetails = $emiEntry->emiDetails()->where('status', 'due')->get();
            }
        }
        $data = [
            'cheque_type' => $jsonData['cheque_type'],
            'customer_id' => $customer->id,
            'collection_date' => $jsonData['collection_date'],
            'no_of_cheque' => $jsonData['no_of_cheque'] ?? null,
            'reference' => $emiEntry->id?? null,
            'remarks' => $jsonData['remarks'] ?? null,
            'document' => $jsonData['document'] ?? null,
        ];


        $details = [
            'emi_detail_id' => [],
            'bank_ids' => [],
            'branch_ids' => [],
            'cheque_no' => [],
            'cheque_date' => [],
            'amount' => [],
            'documents' => [],
            'is_security_cheque' => [],
        ];

        $dueEmiDetails = collect();
        

        foreach ($jsonData['cheques'] as $key => $cheque) {
            $bank = Bank::where('name', $cheque['bank_name'])->firstOrFail();
            $branch = BankBranch::where('name', $cheque['branch_name'])->where('bank_id', $bank->id)->firstOrFail();

            $details['bank_ids'][] = $bank->id;
            $details['branch_ids'][] = $branch->id;
            $details['cheque_no'][] = $cheque['cheque_no'];
            $details['cheque_date'][] = $cheque['cheque_date'];
            $details['amount'][] = $cheque['amount'];
            $details['documents'][] = $cheque['document'] ?? null;
            $details['is_security_cheque'][] = $cheque['is_security_cheque'] ? 1 : 0;
            // Assign EMI detail ID if available and matches the order
            $details['emi_detail_id'][] = $dueEmiDetails->get($key)->id ?? null;
        }

        return [
            'data' => $data,
            'details' => $details,
        ];
    }

}
