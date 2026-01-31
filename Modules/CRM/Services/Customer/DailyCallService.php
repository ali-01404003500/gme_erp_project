<?php

namespace Modules\CRM\Services\Customer;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\DailyCall;

class DailyCallService
{
    
    public function getAll(int $limit = 20) {
        return DailyCall::query()
        ->searchByFields(['customer_id'])
        ->when(request()->filled('from') && request()->filled('to'), function ($qr) {
            $from = request('from');
            $to = request('to');

            $qr->where(function($query) use ($from, $to) {
                $query->whereDate(DB::raw('DATE_FORMAT(call_date, "%Y-%m-%d")'), '>=', $from)
                    ->whereDate(DB::raw('DATE_FORMAT(call_date, "%Y-%m-%d")'), '<=', $to);
            });
        })
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return DailyCall::create($data);
    }

    public function update(DailyCall $dailyCall, array $data)
    {
        $dailyCall->update($data);
        return $dailyCall;
    }

    public function delete(DailyCall $dailyCall)
    {
        $dailyCall->delete();
    }

    public function show($id)
    {
        return DailyCall::findOrFail($id);
    }

    function mapJson(array $jsonData): array
{
    // Map customer name to ID
    $customer = Customer::where('company_name', $jsonData['customer_name'])->first();
    if (!$customer) {
        throw new \Exception("Customer not found: {$jsonData['customer_name']}");
    }

    return [
        'customer_id' => $customer->id,
        'call_type_id' => $jsonData['call_type_id'] ?? null,
        'call_date' => $jsonData['call_date'] ?? now()->toDateString(),
        'is_account_complain' => $jsonData['is_account_complain'] ?? false,
        'complains_details' => $jsonData['complains_details'] ?? null,
        'is_service_complain' => $jsonData['is_service_complain'] ?? false,
        'service_complain_details' => $jsonData['service_complain_details'] ?? null,
        'is_product_required' => $jsonData['is_product_required'] ?? false,
        'product_required_details' => $jsonData['product_required_details'] ?? null,
        'remarks' => $jsonData['remarks'] ?? null,
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

    $savedCount = 0;
    $errors = [];

    foreach ($jsonData as $index => $item) {
        try {
            $mappedData = $this->mapJson($item);

            // Store via service
            $this->store($mappedData);

            $savedCount++;
        } catch (\Exception $e) {
            $errors[] = "Row {$index}: " . $e->getMessage();
        }
    }

    $message = "Daily Calls import completed. Successfully saved: {$savedCount}";
    if (!empty($errors)) {
        $message .= '. Errors: ' . implode('; ', $errors);
    }

    return redirect()->back()->with('success', $message);
}



}
