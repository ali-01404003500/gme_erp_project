<?php

namespace Modules\Services\Services;

use Illuminate\Support\Str;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Services\Models\ServiceDocumentEntry;

class ServiceDocumentEntryService
{
    
    public function getAll(int $limit = 20) {
        return ServiceDocumentEntry::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ServiceDocumentEntry::create($data);
    }

    public function update(ServiceDocumentEntry $serviceDocumentEntry, array $data)
    {
        $serviceDocumentEntry->update($data);
        return $serviceDocumentEntry;
    }

    public function delete(ServiceDocumentEntry $serviceDocumentEntry)
    {
        $serviceDocumentEntry->delete();
    }

    public function show($id)
    {
        return ServiceDocumentEntry::findOrFail($id);
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        // Map product name/code to ID
        $product = ProductCatalog::where('name', $jsonData['product_name'])
            ->first();
            
        if (!$product) {
            throw new \Exception("Product not found: {$jsonData['product_name']}");
        }

        return [
            'product_id' => $product->id,
            'document_date' => $jsonData['document_date'] ?? now()->toDateString(),
            'documents' => $jsonData['documents'] ?? null,
            'remarks' => $jsonData['remarks'] ?? null,
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

        $message = "Service Document Entries import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

}
