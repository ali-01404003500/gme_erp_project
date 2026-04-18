<?php

namespace Modules\Legal\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\CRM\Models\Customer\Customer;
use Modules\Legal\Models\LegalEntry;

class LegalEntryService
{
    public function getAll(int $limit = 20)
    {
        return LegalEntry::query()
        ->when(request()->filled('from'), function ($qr) {
            $from = Carbon::parse(request('from'))->format('Y-m-d');
            $qr->whereDate("date", '>=', $from);
        })
        ->when(request()->filled('to'), function ($qr) {
            $to = Carbon::parse(request('to'))->format('Y-m-d');
            $qr->whereDate("date", '<=', $to);
        })
        ->paginate($limit);
    }

    // public function store(array $mainData, array $convictData, array $complainantData, array $witnessData, array $hajira)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $mainData['legal_id'] = $this->getLegalId();

    //         // Create the main LegalEntry
    //         $legalEntry = LegalEntry::create($mainData);

    //         // Create complainant (One-to-One)
    //         $legalEntry->complainant()->create([
    //             'company_name' => $complainantData['company_name'] ?? null,
    //             'complainant_name' => $complainantData['complainant_name'],
    //             'complainant_designation' => $complainantData['complainant_designation'] ?? null,
    //             'complainant_phone' => $complainantData['complainant_phone'] ?? null,
    //             'complainant_father' => $complainantData['complainant_father'] ?? null,
    //             'complainant_nid' => $complainantData['complainant_nid'] ?? null,
    //             'complainant_address' => $complainantData['complainant_address'] ?? null,
    //         ]);

    //         // Create convicts (One-to-Many)
    //         foreach ($convictData['customer_id'] as $index => $customerId) {
    //             $legalEntry->convicts()->create([
    //                 'customer_id' => $customerId,
    //                 'convict_name' => $convictData['convict_name'][$index] ?? null,
    //                 'convict_designation' => $convictData['convict_designation'][$index] ?? null,
    //                 'convict_phone' => $convictData['convict_phone'][$index] ?? null,
    //                 'father_or_husband' => $convictData['father_or_husband'][$index] ?? null,
    //                 'convict_father_name' => $convictData['convict_father_name'][$index] ?? null,
    //                 'convict_mother_name' => $convictData['convict_mother_name'][$index] ?? null,
    //                 'convict_nid' => $convictData['convict_nid'][$index] ?? null,
    //                 'convict_address' => $convictData['convict_address'][$index] ?? null,
    //             ]);
    //         }

    //         // Conditional Hajira and Witness creation
    //         if (strtolower($mainData['legal_type']) === 'case') {
    //             // Create Hajira entry
    //             $legalEntry->hajiras()->create([
    //                 'hajira_date' => $hajira['first_hajira_date'],
    //                 'hajira_description' => $mainData['legal_description'] ?? null,
    //             ]);

    //             // Create witnesses (One-to-Many)
    //             if (!empty($witnessData['witness_name'])) {
    //                 foreach ($witnessData['witness_name'] as $index => $name) {
    //                     if ($name) {
    //                         $legalEntry->witnesses()->create([
    //                             'witness_name' => $name,
    //                             'witness_father_name' => $witnessData['witness_father_name'][$index] ?? null,
    //                             'witness_mother_name' => $witnessData['witness_mother_name'][$index] ?? null,
    //                             'witness_address' => $witnessData['witness_address'][$index] ?? null,
    //                             'witness_phone' => $witnessData['witness_phone'][$index] ?? null,
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         return $legalEntry;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }


public function store(array $mainData, array $convictData, array $complainantData, array $witnessData, array $hajiraData)
{
    DB::beginTransaction();

    try {
        $mainData['legal_id'] = $this->getLegalId();

        // Create the main LegalEntry
        $legalEntry = LegalEntry::create($mainData);

        // Create complainant (One-to-One)
        $legalEntry->complainant()->create([
            'company_name' => $complainantData['company_name'] ?? null,
            'complainant_name' => $complainantData['complainant_name'],
            'complainant_designation' => $complainantData['complainant_designation'] ?? null,
            'complainant_phone' => $complainantData['complainant_phone'] ?? null,
            'complainant_father' => $complainantData['complainant_father'] ?? null,
            'complainant_nid' => $complainantData['complainant_nid'] ?? null,
            'complainant_address' => $complainantData['complainant_address'] ?? null,
        ]);

        // Create convicts (One-to-Many)
        foreach ($convictData['customer_id'] as $index => $customerId) {
            $legalEntry->convicts()->create([
                'customer_id' => $customerId,
                'convict_name' => $convictData['convict_name'][$index] ?? null,
                'convict_designation' => $convictData['convict_designation'][$index] ?? null,
                'convict_phone' => $convictData['convict_phone'][$index] ?? null,
                'father_or_husband' => $convictData['father_or_husband'][$index] ?? null,
                'convict_father_name' => $convictData['convict_father_name'][$index] ?? null,
                'convict_mother_name' => $convictData['convict_mother_name'][$index] ?? null,
                'convict_nid' => $convictData['convict_nid'][$index] ?? null,
                'convict_address' => $convictData['convict_address'][$index] ?? null,
            ]);
        }

        // Conditional Hajira and Witness creation
        if (strtolower($mainData['legal_type']) === 'case') {
            
            // MULTIPLE HAJIRA CREATION (One-to-Many)
            if (isset($hajiraData['hajira_date']) && is_array($hajiraData['hajira_date'])) {
                foreach ($hajiraData['hajira_date'] as $index => $hajiraDate) {
                    if ($hajiraDate) {
                        $legalEntry->hajiras()->create([
                            'hajira_date' => $hajiraDate,
                            'hajira_description' => $hajiraData['hajira_description'][$index] ?? null,
                        ]);
                    }
                }
            }

            // Create witnesses (One-to-Many)
            if (!empty($witnessData['witness_name'])) {
                foreach ($witnessData['witness_name'] as $index => $name) {
                    if ($name) {
                        $legalEntry->witnesses()->create([
                            'witness_name' => $name,
                            'witness_father_name' => $witnessData['witness_father_name'][$index] ?? null,
                            'witness_mother_name' => $witnessData['witness_mother_name'][$index] ?? null,
                            'witness_address' => $witnessData['witness_address'][$index] ?? null,
                            'witness_phone' => $witnessData['witness_phone'][$index] ?? null,
                        ]);
                    }
                }
            }
        }

        DB::commit();
        return $legalEntry;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}

    
    public function getLegalId()
    {
        $count_purchase_number = LegalEntry::count();
        if ($count_purchase_number == 0) {
            return 'L-' . date('Y') . '-' . str_pad($count_purchase_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $last_job_id = LegalEntry::orderBy('id', 'desc')->pluck('id')->first();

            return 'L-' . date('Y') . '-' . str_pad($last_job_id + 1, 4, '0', STR_PAD_LEFT);
        }
    }

    public function update(array $mainData, array $convictData, array $complainantData, array $witnessData, array $hajira, $id)
    {
        DB::beginTransaction();

        try {
            // Find the LegalEntry
            $legalEntry = LegalEntry::findOrFail($id);

            // Update the main LegalEntry
            $legalEntry->update($mainData);

            // Update complainant (One-to-One)
            $legalEntry->complainant()->update([
                'company_name' => $complainantData['company_name'] ?? null,
                'complainant_name' => $complainantData['complainant_name'],
                'complainant_designation' => $complainantData['complainant_designation'] ?? null,
                'complainant_phone' => $complainantData['complainant_phone'] ?? null,
                'complainant_father' => $complainantData['complainant_father'] ?? null,
                'complainant_nid' => $complainantData['complainant_nid'] ?? null,
                'complainant_address' => $complainantData['complainant_address'] ?? null,
            ]);

            // Update convicts (One-to-Many)
            // First, collect existing convict IDs
            $existingConvictIds = $legalEntry->convicts->pluck('id')->toArray();
            $submittedConvictIds = [];

            // Process submitted convicts
            if (!empty($convictData['customer_id'])) {
                foreach ($convictData['customer_id'] as $index => $customerId) {
                    $convictDataArray = [
                        'customer_id' => $customerId,
                        'convict_name' => $convictData['convict_name'][$index] ?? null,
                        'convict_designation' => $convictData['convict_designation'][$index] ?? null,
                        'convict_phone' => $convictData['convict_phone'][$index] ?? null,
                        'father_or_husband' => $convictData['father_or_husband'][$index] ?? null,
                        'convict_father_name' => $convictData['convict_father_name'][$index] ?? null,
                        'convict_mother_name' => $convictData['convict_mother_name'][$index] ?? null,
                        'convict_nid' => $convictData['convict_nid'][$index] ?? null,
                        'convict_address' => $convictData['convict_address'][$index] ?? null,
                    ];

                    // If an ID is provided (existing convict), update it; otherwise, create a new one
                    if (isset($convictData['convict_id'][$index]) && in_array($convictData['convict_id'][$index], $existingConvictIds)) {
                        $legalEntry->convicts()->find($convictData['convict_id'][$index])->update($convictDataArray);
                        $submittedConvictIds[] = $convictData['convict_id'][$index];
                    } else {
                        $legalEntry->convicts()->create($convictDataArray);
                    }
                }
            }

            // Delete convicts that were removed
            $convictsToDelete = array_diff($existingConvictIds, $submittedConvictIds);
            if (!empty($convictsToDelete)) {
                $legalEntry->convicts()->whereIn('id', $convictsToDelete)->delete();
            }

            // Conditional Hajira and Witness handling
            if (strtolower($mainData['legal_type']) === 'case') {
                // Update or create Hajira entry
                $legalEntry->hajiras()->updateOrCreate(
                    ['legal_entry_id' => $legalEntry->id],
                    [
                        'hajira_date' => $hajira['first_hajira_date'],
                        'hajira_description' => $mainData['legal_description'] ?? null,
                    ],
                );

                // Update witnesses (One-to-Many)
                $existingWitnessIds = $legalEntry->witnesses->pluck('id')->toArray();
                $submittedWitnessIds = [];

                if (!empty($witnessData['witness_name'])) {
                    foreach ($witnessData['witness_name'] as $index => $name) {
                        if ($name) {
                            $witnessDataArray = [
                                'witness_name' => $name,
                                'witness_father_name' => $witnessData['witness_father_name'][$index] ?? null,
                                'witness_mother_name' => $witnessData['witness_mother_name'][$index] ?? null,
                                'witness_address' => $witnessData['witness_address'][$index] ?? null,
                                'witness_phone' => $witnessData['witness_phone'][$index] ?? null,
                            ];

                            // If an ID is provided (existing witness), update it; otherwise, create a new one
                            if (isset($witnessData['witness_id'][$index]) && in_array($witnessData['witness_id'][$index], $existingWitnessIds)) {
                                $legalEntry->witnesses()->find($witnessData['witness_id'][$index])->update($witnessDataArray);
                                $submittedWitnessIds[] = $witnessData['witness_id'][$index];
                            } else {
                                $legalEntry->witnesses()->create($witnessDataArray);
                            }
                        }
                    }
                }

                // Delete witnesses that were removed
                $witnessesToDelete = array_diff($existingWitnessIds, $submittedWitnessIds);
                if (!empty($witnessesToDelete)) {
                    $legalEntry->witnesses()->whereIn('id', $witnessesToDelete)->delete();
                }
            } else {
                // If legal_type is not 'case', delete any existing hajiras and witnesses
                $legalEntry->hajiras()->delete();
                $legalEntry->witnesses()->delete();
            }

            DB::commit();
            return $legalEntry;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(LegalEntry $legalEntry)
    {
        $legalEntry->delete();
    }

    public function show($id)
    {
        return LegalEntry::findOrFail($id);
    }


    public function mapJson(array $jsonData): array
    {
        // === Main Legal Entry Data ===
        $mainData = [
            'date' => $jsonData['date'],
            'amount' => $jsonData['amount'],
            'legal_type' => $jsonData['legal_type'],
            'case_no' => $jsonData['case_no'] ?? null,
            'occurrence_info' => $jsonData['occurrence_info'] ?? null,
            'occurrence_date' => $jsonData['occurrence_date'] ?? null,
            'legal_description' => $jsonData['legal_description'] ?? null,
            'advocate_name' => $jsonData['advocate_name'] ?? null,
            'advocate_designation' => $jsonData['advocate_designation'] ?? null,
            'advocate_phone' => $jsonData['advocate_phone'] ?? null,
            'advocate_address' => $jsonData['advocate_address'] ?? null,
            'attachment' => $jsonData['attachments'] ?? null,
        ];

        // === Complainant Data (1:1) ===
        $complainant = $jsonData['complainant'] ?? [];
        $complainantData = [
            'company_name' => $complainant['company_name'] ?? null,
            'complainant_name' => $complainant['name'] ?? throw new \Exception("Complainant name is required"),
            'complainant_designation' => $complainant['designation'] ?? null,
            'complainant_phone' => $complainant['phone'] ?? null,
            'complainant_father' => $complainant['father'] ?? null,
            'complainant_nid' => $complainant['nid'] ?? null,
            'complainant_address' => $complainant['address'] ?? null,
        ];

        // === Convict Data (1:N) ===
        $convictData = [
            'customer_id' => [],
            'convict_name' => [],
            'convict_designation' => [],
            'convict_phone' => [],
            'father_or_husband' => [],
            'convict_father_name' => [],
            'convict_mother_name' => [],
            'convict_nid' => [],
            'convict_address' => [],
        ];

        $convicts = $jsonData['convicts'] ?? [];
        if (!empty($convicts)) {
            // Preload customer names → IDs
            $customerNames = array_column($convicts, 'customer_name');
            $customersMap = Customer::whereIn('company_name', $customerNames)
                ->pluck('id', 'company_name')
                ->toArray();

            foreach ($convicts as $convict) {
                $customerId = $convict['customer_name']
                    ? ($customersMap[$convict['customer_name']]
                        ?? throw new \Exception("Customer not found: {$convict['customer_name']}"))
                    : null;

                $convictData['customer_id'][] = $customerId;
                $convictData['convict_name'][] = $convict['name'] ?? null;
                $convictData['convict_designation'][] = $convict['designation'] ?? null;
                $convictData['convict_phone'][] = $convict['phone'] ?? null;
                $convictData['father_or_husband'][] = $convict['father_or_husband'] ?? null;
                $convictData['convict_father_name'][] = $convict['father_name'] ?? null;
                $convictData['convict_mother_name'][] = $convict['mother_name'] ?? null;
                $convictData['convict_nid'][] = $convict['nid'] ?? null;
                $convictData['convict_address'][] = $convict['address'] ?? null;
            }
        }

        // === Witness Data (1:N, only if legal_type = 'case') ===
        $witnessData = [
            'witness_name' => [],
            'witness_father_name' => [],
            'witness_mother_name' => [],
            'witness_address' => [],
            'witness_phone' => [],
        ];

        if (strtolower($jsonData['legal_type']) === 'case') {
            $witnesses = $jsonData['witnesses'] ?? [];
            foreach ($witnesses as $witness) {
                if (!empty($witness['name'])) {
                    $witnessData['witness_name'][] = $witness['name'];
                    $witnessData['witness_father_name'][] = $witness['father_name'] ?? null;
                    $witnessData['witness_mother_name'][] = $witness['mother_name'] ?? null;
                    $witnessData['witness_address'][] = $witness['address'] ?? null;
                    $witnessData['witness_phone'][] = $witness['phone'] ?? null;
                }
            }
        }

        // === Hajira Data ===
        $hajira = [
            'first_hajira_date' => ($jsonData['legal_type'] === 'case')
                ? ($jsonData['first_hajira_date'] ?? null)
                : null
        ];

        return compact('mainData', 'convictData', 'complainantData', 'witnessData', 'hajira');
    }

    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . \Illuminate\Support\Str::snake(request()->input('name')) . '.json';

        // Ensure directory exists
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
                $this->store(
                    $mappedData['mainData'],
                    $mappedData['convictData'],
                    $mappedData['complainantData'],
                    $mappedData['witnessData'],
                    $mappedData['hajira']
                );
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        if (empty($errors)) {
            DB::commit();
        } else {
            DB::rollBack();
        }

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

   /**
 * Map JSON data for Legal Schedule Update
 *
 * @param array $jsonData
 * @return array
 * @throws \Exception
 */
public function mapJsonForSchedule(array $jsonData): array
{
    $legalEntry = null;

    // Try to find legal entry by legal_id first
    if (!empty($jsonData['legal_id'])) {
        $legalEntry = LegalEntry::where('legal_id', $jsonData['legal_id'])->first();
    }

    // If not found, try by case_no
    if (!$legalEntry && !empty($jsonData['case_no'])) {
        $legalEntry = LegalEntry::where('case_no', $jsonData['case_no'])->first();
    }

    // Throw exception if legal entry not found
    if (!$legalEntry) {
        $identifier = $jsonData['legal_id'] ?? $jsonData['case_no'] ?? 'unknown';
        throw new \Exception("Legal entry not found with identifier: {$identifier}");
    }

    $status = $jsonData['status'] ?? 'running';

    // Only require next_hajira_date if status is 'running'
    if ($status === 'running') {
        if (empty($jsonData['next_hajira_date'])) {
            throw new \Exception("Next hajira date is required when status is 'running'");
        }
    }

    // For withdraw, approval_status is often required
    $approvalStatus = $jsonData['approval_status']  ?? 'pending';
    // dd($approvalStatus);

    return [
        'legal_entry_id' => $legalEntry->id,
        'modal_status' => $status,
        'next_hajira_date' => $status === 'running' ? $jsonData['next_hajira_date'] : null,
        'hajira_remarks' => $jsonData['hajira_remarks'] ?? null,
        'attachment' => $jsonData['attachment'] ?? null,
        'approval_status' => $approvalStatus,
    ];
}

/**
 * Store legal schedule updates from JSON file or direct data
 *
 * @param array|null $directData Optional direct data array
 * @return array Results of the import
 * @throws \Exception
 */
public function storeFromJsonFileForSchedule($directData = null)
{
    DB::beginTransaction();

    try {
        // Determine data source
        if ($directData !== null) {
            // Use direct data if provided
            $data = is_string($directData) ? json_decode($directData, true) : $directData;

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON data: " . json_last_error_msg());
            }
        } else {
            // Read from JSON file
            $jsonFileDir = storage_path('app/json_formats');

            // Create directory if not exists
            if (!is_dir($jsonFileDir)) {
                mkdir($jsonFileDir, 0755, true);
            }

            $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

            // Create empty file if not exists
            if (!file_exists($jsonFile)) {
                file_put_contents($jsonFile, json_encode([]));
            }

            $fileContent = file_get_contents($jsonFile);
            $data = json_decode($fileContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON file format: " . json_last_error_msg());
            }
        }

        // Validate data is array
        if (!is_array($data)) {
            throw new \Exception("Data must be an array");
        }

        // Handle empty data
        if (empty($data)) {
            DB::commit();
            return [
                'success' => true,
                'message' => 'No data to process',
                'count' => 0,
                'results' => []
            ];
        }

        $results = [];
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // Process each record
        foreach ($data as $index => $item) {
            try {
                // Map the JSON data
                $mappedData = $this->mapJsonForSchedule($item);

                // Update the schedule
                $result = $this->updateSchedule($mappedData);

                $results[] = [
                    'index' => $index,
                    'status' => 'success',
                    'legal_entry_id' => $result->id,
                    'legal_id' => $result->legal_id,
                ];

                $successCount++;

            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'data' => $item,
                    'error' => $e->getMessage()
                ];
                $errorCount++;
            }
        }

        DB::commit();

        // Return detailed results
        return [
            'success' => $successCount > 0,
            'message' => "Processed {$successCount} records successfully" .
                         ($errorCount > 0 ? ", {$errorCount} failed" : ""),
            'total' => count($data),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'results' => $results,
            'errors' => $errors
        ];

    } catch (\Exception $e) {
        DB::rollBack();

        return [
            'success' => false,
            'message' => 'Import failed: ' . $e->getMessage(),
            'total' => 0,
            'success_count' => 0,
            'error_count' => 0,
            'results' => [],
            'errors' => [['error' => $e->getMessage()]]
        ];
    }
}

/**
 * Update legal entry schedule and create new hajira
 *
 * @param array $data
 * @return LegalEntry
 * @throws \Exception
 */
public function updateSchedule(array $data)
{
    DB::beginTransaction();

    try {
        $entry = LegalEntry::findOrFail($data['legal_entry_id']);

        // Update main entry
        $entry->status = $data['modal_status'];
        $entry->approval_status = $data['approval_status'];
        $entry->attachment = $data['attachment'];


        $entry->save();

        // Only create hajira for 'running' cases with a date
        if ($entry->status === 'running' && !empty($data['next_hajira_date'])) {
            $entry->hajiras()->create([
                'hajira_date' => $data['next_hajira_date'],
                'hajira_description' => $data['hajira_remarks'],
            ]);
        }

        DB::commit();

        return $entry->fresh(['hajiras']);

    } catch (\Exception $e) {
        DB::rollBack();
        throw new \Exception("Failed to update schedule: " . $e->getMessage());
    }
}


}