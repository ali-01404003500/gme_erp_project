<?php

namespace Modules\Services\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceToken;

class ServiceService
{

    public function getAll(int $limit = 20)
    {
        return Service::query()
            ->likeSearch('service_unique_id')
            ->when(request()->filled('service_type'), function ($qr) {
                $qr->whereHas('serviceTokens', function ($query) {
                    $query->where('service_type', request('service_type'));
                });
            })
            ->whereIn('action', ['Pending', 'Failed'])
            ->likeSearch('action')
            ->when(request()->filled('from_to'), function ($qr) {
                $qr->whereHas('serviceTokens', function ($query) {
                    $query->filterByDateRange('token_date');
                });             
            })
            // ->filterByDateRange('created_at')
            ->when(auth()->user()->id != 1, function ($qr) {
                $qr->whereIn('created_by', [auth()->user()->id]);
            })
            ->paginate($limit);
    }

     public function getServiceId()
    {
        // Get the current date in the format yymmdd
        $datePart = now()->format('ymd');

        // Count the number of services created on the current day
        $count = Service::whereDate('created_at', now()->toDateString())
                    ->count() + 1;

        // Format the count to be a 3-digit number
        $countPart = str_pad($count, 3, '0', STR_PAD_LEFT);

        // Combine the parts to form the service ID
        $serviceId = "ST-{$datePart}-{$countPart}";

        return $serviceId;
    }

    public function store(array $data, array $serviceTokens = [])
    {
        $data['service_unique_id'] = $this->getServiceId();
        // dd( request()->all(),$data, $serviceTokens);
        $result['service'] = Service::create($data);

        foreach ($serviceTokens['customer_id'] as $key => $customer_id) {
            $serviceToken = ServiceToken::create([
                'service_id' => $result['service']->id,
                'customer_id' => $customer_id,
                'contact_person_phone' => $serviceTokens['contact_person_phone'][$key],
                'token_date' => $serviceTokens['token_date'][$key],
                'invoice_date' => $serviceTokens['invoice_date'][$key],
                'expire_date' => $serviceTokens['expire_date'][$key],
                'product_id' => $serviceTokens['product_id'][$key],
                'serial_number' => $serviceTokens['serial_number'][$key] ?? null,
                'service_type' => $serviceTokens['service_type'][$key],
                'problem_details' => $serviceTokens['problem_details'][$key],
                'problem_type' => $serviceTokens['problem_type'][$key],
                'work_type' => $serviceTokens['work_type'][$key],
                'quantity' => $serviceTokens['quantity'][$key],
                'internal_video_link' => $serviceTokens['internal_video_link'][$key] ?? null,
                'external_video_link' => $serviceTokens['external_video_link'][$key] ?? null,
                'documents' => $serviceTokens['documents'][$key] ?? null,
                'action' => 'Pending',

            ]);
            $result['serviceTokens'][] = $serviceToken;
        };
        return $result;
    }

    public function update(Service $service, array $data, array $serviceTokens = [])
    {
        $service->update($data);
        $service->serviceTokens()->delete();
        $result['service'] = $service;
        $result['serviceTokens'] = [];
        foreach($serviceTokens['customer_id'] as $key => $customer_id) {
            $serviceToken= ServiceToken::create([
                'service_id' =>$service->id,
                'customer_id' => $customer_id,
                'contact_person_phone' => $serviceTokens['contact_person_phone'][$key],
                'token_date' => $serviceTokens['token_date'][$key],
                'invoice_date' => $serviceTokens['invoice_date'][$key],
                'expire_date' => $serviceTokens['expire_date'][$key],
                'product_id' => $serviceTokens['product_id'][$key],
                'serial_number' => $serviceTokens['serial_number'][$key] ?? null,
                'service_type' => $serviceTokens['service_type'][$key],
                'problem_details' => $serviceTokens['problem_details'][$key],
                'problem_type' => $serviceTokens['problem_type'][$key],
                'work_type' => $serviceTokens['work_type'][$key],
                'quantity' => $serviceTokens['quantity'][$key],
                'internal_video_link' => $serviceTokens['internal_video_link'][$key] ?? null,
                'external_video_link' => $serviceTokens['external_video_link'][$key] ?? null,
                'documents' => $serviceTokens['documents'][$key] ?? null,
            ]);
            $result['serviceTokens'][] = $serviceToken;
        };
        return $result;
    }

    public function delete(Service $service)
    {
        $service->delete();
    }

    public function show($id)
    {
        return Service::with('serviceTokens.customer', 'serviceTokens.product')->findOrFail($id);
    }

    public function getInvoices($customer_id)
    {
        return SalesOrder::where('customer_id', $customer_id)->where('status', 'delivered')
            ->whereHas('salesOrderDetails', function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->where('is_serial', 'yes');
                });
            })
            ->get();
    }

    public function getProducts($invoice_id)
    {
        return SalesOrderDetails::where('sales_order_id', $invoice_id)
            ->whereHas('product', function ($query) {
                $query->where('is_serial', 'yes');
            })
            ->with('product')
            ->get();
    }

    public function getSerialIds($product_id, $customer_id)
    {
        return DongleOrSerialEntry::where('product_id', $product_id)
            ->where('customer_id', $customer_id)
            ->select('dongle_id')
            ->get(); // If returning objects
    }

    public function getExpireDates($warranty_period, $warranty_period_input)
    {
        return ProductCatalog::where('warranty_period', $warranty_period)
            ->where('$warranty_period_input', $warranty_period_input)
            ->get();
    }


    public function getQuantity($sales_order_id, $product_id)
    {

        return SalesOrderDetails::where('sales_order_id', $sales_order_id)
            ->where('product_id', $product_id)
            ->first();
    }


    public function mapJson(array $jsonData): array
    {
        // Map engineer name to ID (nullable)
        $engineerId = null;
        if (!empty($jsonData['engineer_name'])) {
            $engineerId = Employee::where('full_name', $jsonData['engineer_name'])
                ->value('id') ?? throw new \Exception("Engineer not found: {$jsonData['engineer_name']}");
        }

        // Prepare main service data
        $data = [
            'is_assigned' => $jsonData['is_assigned'] ?? null,
            'status' => $jsonData['status'] ?? null,
            'assigned_engineer_id' => $engineerId,
            'service_date' => $jsonData['service_date'] ?? null,
            'service_priority' => $jsonData['service_priority'] ?? null,
            'remarks' => $jsonData['remarks'] ?? null,
            // service_unique_id will be added by controller or service
        ];

        // Initialize serviceTokens structure (flat arrays)
        $serviceTokens = [
            'customer_id' => [],
            'contact_person_phone' => [],
            'token_date' => [],
            'invoice_id' => [],
            'invoice_date' => [],
            'expire_date' => [],
            'product_id' => [],
            'serial_number' => [],
            'service_type' => [],
            'problem_details' => [],
            'problem_type' => [],
            'work_type' => [],
            'quantity' => [],
            'internal_video_link' => [],
            'external_video_link' => [],
            'documents' => [],
        ];

        // Preload customer names → IDs
        $customerNames = array_column($jsonData['service_tokens'] ?? [], 'customer_id');
        $existingCustomers = Customer::whereIn('customer_id', $customerNames)
            ->pluck('id', 'customer_id')
            ->toArray();

        $products = ProductCatalog::whereIn('product_code', array_column($jsonData['service_tokens'] ?? [], 'product_code'))->pluck('id', 'product_code')->toArray();

        foreach ($jsonData['service_tokens'] as $token) {
            $customerId = $token['customer_id']
                ? ($existingCustomers[$token['customer_id']]
                    ?? throw new \Exception("Customer not found: {$token['customer_id']}"))
                : null;

            $serviceTokens['customer_id'][] = $customerId;
            $serviceTokens['contact_person_phone'][] = $token['contact_person_phone'] ?? null;
            $serviceTokens['token_date'][] = $token['token_date'] ?? null;
            $serviceTokens['invoice_id'][] = $token['invoice_id'] ?? null;
            $serviceTokens['invoice_date'][] = $token['invoice_date'] ?? null;
            $serviceTokens['expire_date'][] = $token['expire_date'] ?? null;
            $serviceTokens['product_id'][] =  $products[$token['product_code']] ?? null;
            $serviceTokens['serial_number'][] = $token['serial_number'] ?? null;
            $serviceTokens['service_type'][] = $token['service_type'] ?? null;
            $serviceTokens['problem_details'][] = $token['problem_details'] ?? null;
            $serviceTokens['problem_type'][] = $token['problem_type'] ?? null;
            $serviceTokens['work_type'][] = $token['work_type'] ?? null;
            $serviceTokens['quantity'][] = $token['quantity'] ?? null;
            $serviceTokens['internal_video_link'][] = $token['internal_video_link'] ?? null;
            $serviceTokens['external_video_link'][] = $token['external_video_link'] ?? null;
            $serviceTokens['documents'][] = $token['documents'] ?? null;
        }

        return [
            'data' => $data,
            'serviceTokens' => $serviceTokens
        ];
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
                $this->store($mappedData['data'], $mappedData['serviceTokens']);
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
}

