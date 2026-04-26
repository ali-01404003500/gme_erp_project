<?php

namespace Modules\Licenses\Services;

use Modules\Licenses\Models\DongleOrSerialEntry;
use App\Traits\S3FileHandler;
use Illuminate\Support\Str;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;

class DongleOrSerialEntryService
{
    use S3FileHandler;

    public function getAll(int $limit = 20)
    { 
        return DongleOrSerialEntry::query()
            ->searchByFields(['customer_id', 'dongle_id', 'product_type', 'product_id'])
            ->paginate($limit);
    }

    public function store(array $data)
    {
        // if (isset($data['file_upload'])) {
        //     $data['file_upload'] = $this->uploadFile($data['file_upload'], 'file_upload');
        // }
        return DongleOrSerialEntry::create($data);
    }

    public function update(DongleOrSerialEntry $dongleOrSerialEntry, array $data)
    {
        // if (isset($data['file_upload'])) {
        //     $data['file_upload'] = $this->uploadFile($data['file_upload'], 'file_upload');
        // }
        $dongleOrSerialEntry->update($data);
        return $dongleOrSerialEntry;
    }

    public function delete(DongleOrSerialEntry $dongleOrSerialEntry)
    {
        $dongleOrSerialEntry->delete();
    }

    public function show($id)
    {
        return DongleOrSerialEntry::findOrFail($id);
    }

    function mapJson(array $jsonData): array
{
    // Map customer
    $customer = Customer::where('company_name', $jsonData['customer_name'])->first();
    if (!$customer) {
        throw new \Exception("Customer not found: {$jsonData['customer_name']}");
    }

    // Map product by name & model
    $product = ProductCatalog::where('name', $jsonData['product_name'])
        ->where('model', $jsonData['product_model'])
        ->first();

    if (!$product) {
        $searchCriteria = "name: {$jsonData['product_name']}";
        if (!empty($jsonData['product_model'])) {
            $searchCriteria .= ", model: {$jsonData['product_model']}";
        }
        throw new \Exception("Product not found with {$searchCriteria}");
    }

    // Check duplicate dongle
    if (DongleOrSerialEntry::where('dongle_id', $jsonData['dongle_id'])
        ->whereNull('deleted_at')
        ->exists()) {

        throw new \Exception("Dongle ID already exists: {$jsonData['dongle_id']}");
    }

    return [
        'customer_id'       => $customer->id,
        'address'           => $customer->address,
        'product_id'        => $product->id,
        'product_type'      => $jsonData['product_type'] ?? $product->type,
        'dongle_id'         => $jsonData['dongle_id'],
        'software_version'  => $jsonData['software_version'] ?? $product->software_version,
        'status'            => $jsonData['status'] ?? 'active',
        'file_upload'       => $jsonData['file_upload'] ?? null,
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

        $savedCount = 0;
        $errors = [];
        $skipped = [];

        foreach ($jsonData as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $this->store($mappedData);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Dongle entries import completed. Successfully saved: {$savedCount}";
        if (!empty($skipped)) {
            $message .= '. Skipped duplicates: ' . count($skipped);
        }
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

}
