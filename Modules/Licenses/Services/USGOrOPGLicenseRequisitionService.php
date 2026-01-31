<?php

namespace Modules\Licenses\Services;

use Modules\Licenses\Models\USGOrOPGLicenseRequisition;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\CRM\Models\Customer\Customer;
use Modules\Licenses\Models\DongleOrSerialEntry;

class USGOrOPGLicenseRequisitionService
{
    private $smsService;

    public function __construct(USGOrOPGLicenseSmsService $smsService = null)
    {
        $this->smsService = $smsService ?? app(USGOrOPGLicenseSmsService::class);
    }

    public function getAll(int $limit = 20)
    {
        return USGOrOPGLicenseRequisition::query()
            ->searchByFields(['customer_id'])
            ->when(request()->filled('from'), function ($qr) {
                $from = Carbon::parse(request('from'))->format('Y-m-d');
                $qr->whereRaw('DATE(created_at) >= ?', [$from]);
            })
            ->when(request()->filled('to'), function ($qr) {
                $to = Carbon::parse(request('to'))->format('Y-m-d');
                $qr->whereRaw('DATE(created_at) <= ?', [$to]);
            })
            ->orderByRaw("FIELD(status, 'Pending') DESC")
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    public function getLicenseNumber($customer_id)
    {
        $today = date('Y-m-d');
        $customer_count = USGOrOPGLicenseRequisition::whereDate(DB::raw('DATE(created_at)'), $today)->count();
        $licensesToday = USGOrOPGLicenseRequisition::whereDate(DB::raw('DATE(created_at)'), $today)->where('customer_id', $customer_id)->count();

        $licenseNumber = sprintf('LIN-%s-%03d-%06d', date('Ymd'), $customer_count + 1, $licensesToday + 1);

        return $licenseNumber;
    }

    public function store(array $data, array $phones = [])
    {
        if (!isset($data['license_id'])) {
            $data['license_id'] = $this->getLicenseNumber($data['customer_id']);
        }
        $requisition = USGOrOPGLicenseRequisition::create($data);

        if (count($phones) > 0) {
            $phoneNumbers = array_merge($phones, [$data['phone']]);
        } else {
            $phoneNumbers = [$data['phone']];
        }

        $phones = collect($phoneNumbers)->map(function ($phone) use ($requisition) {
            return $requisition->phones()->create(['multiple_phone_no' => $phone]);
        });

        $result = $requisition->toArray();
        $result['phones'] = $phones->toArray();

        return $requisition;
    }

    public function update(USGOrOPGLicenseRequisition $requisition, array $data, array $phones)
    {
        $requisition->update($data);

        $requisition->phones()->delete();

        $phones = collect($phones['multiple_phone_nos'])->map(function ($phone) use ($requisition) {
            return $requisition->phones()->create(['multiple_phone_no' => $phone]);
        });

        return $requisition;
    }

    public function delete(USGOrOPGLicenseRequisition $uSGOrOPGLicenseRequisition)
    {
        $uSGOrOPGLicenseRequisition->delete();
    }

    public function show($id)
    {
        return USGOrOPGLicenseRequisition::findOrFail($id);
    }

    function mapJson(array $jsonData): array
    {
        // Map customer name to ID and get address/phone
        $customer = Customer::where('company_name', $jsonData['customer_name'])->first();
        if (!$customer) {
            throw new \Exception("Customer not found: {$jsonData['customer_name']}");
        }

        // Map dongle serial to dongle_id and get product_model/software_version
        $dongle = DongleOrSerialEntry::where('dongle_id', $jsonData['dongle_serial'])->first();
        if (!$dongle) {
            throw new \Exception("Dongle not found: {$jsonData['dongle_serial']}");
        }

        return [
            'license_id' => $jsonData['license_id'] ?? null,
            'customer_id' => $customer->id,
            'address' => $customer->address,
            'phone' => $customer->phone,
            'dongle_id' => $dongle->id,
            'product_model' => $dongle->product_model,
            'software_version' => $dongle->software_version,
            'start_date' => $jsonData['start_date'] ?? null,
            'valid_period' => $jsonData['valid_period'] ?? null,
            'valid_period_type' => $jsonData['valid_period_type'] ?? null,
            'expired_date' => $jsonData['expired_date'] ?? null,
            'remarks' => $jsonData['remarks'] ?? null,
            'multiple_phone_nos' => $jsonData['multiple_phone_nos'] ?? [],
            // SMS related fields (license_key is only for SMS table, not requisition)
            'sms_data' => [
                'sms' => $jsonData['sms'] ?? null,
                'status' => $jsonData['status'] ?? 'Send',
                'license_key' => $jsonData['license_key'] ?? null,
            ],
        ];
    }

    /**
     * Process import data (common logic for both file and direct import)
     */
    private function processImportData(array $jsonData)
    {
        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);

                // Extract multiple_phone_nos and SMS data
                $phones = $mappedData['multiple_phone_nos'] ?? [];
                $smsData = $mappedData['sms_data'] ?? null;
                unset($mappedData['multiple_phone_nos'], $mappedData['sms_data']);

                // Store license requisition via service
                $requisition = $this->store($mappedData, $phones);

                // If SMS data is provided, store SMS record without sending
                if ($smsData && !empty($smsData['sms'])) {
                    $smsRecord = [
                        'customer_id' => $mappedData['customer_id'],
                        'address' => $mappedData['address'],
                        'phone' => $mappedData['phone'],
                        'dongle_id' => $mappedData['dongle_id'],
                        'product_model' => $mappedData['product_model'],
                        'start_date' => $mappedData['start_date'],
                        'valid_period' => $mappedData['valid_period'],
                        'valid_period_type' => $mappedData['valid_period_type'],
                        'expired_date' => $mappedData['expired_date'],
                        'remarks' => $mappedData['remarks'],
                        'license_key' => $smsData['license_key'] ?? null,
                        'license_id' => $requisition->license_id,
                        'u_s_g_or_o_p_g_license_requisition_id' => $requisition->id,
                        'status' => $smsData['status'] ?? 'Send',
                        'software_version' => $mappedData['software_version'],
                        'sms' => $smsData['sms'],
                    ];

                    $this->smsService->storeWithoutSending($smsRecord, ['multiple_phone_nos' => $phones]);
                }

                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        return [
            'saved_count' => $savedCount,
            'errors' => $errors,
        ];
    }

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

        $result = $this->processImportData($jsonData);

        $message = "USG/OPG License Requisitions import completed. Successfully saved: {$result['saved_count']}";
        if (!empty($result['errors'])) {
            $message .= '. Errors: ' . implode('; ', $result['errors']);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Handle direct import from API request data
     */
    public function handleDirectImport($data)
    {
        if (empty($data)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No data provided.',
                ],
                422,
            );
        }

        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        $result = $this->processImportData($items);

        $message = "USG/OPG License Requisitions import completed. Successfully saved: {$result['saved_count']}";
        if (!empty($result['errors'])) {
            $message .= '. Errors: ' . implode('; ', $result['errors']);
        }

        return response()->json(
            [
                'success' => empty($result['errors']) || $result['saved_count'] > 0,
                'message' => $message,
                'saved_count' => $result['saved_count'],
                'error_count' => count($result['errors']),
                'errors' => $result['errors'],
            ],
            empty($result['errors']) ? 200 : 207,
        );
    }
}
