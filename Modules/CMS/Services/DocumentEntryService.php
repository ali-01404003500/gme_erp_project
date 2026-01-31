<?php

namespace Modules\CMS\Services;

use App\Traits\S3FileHandler;
use Illuminate\Support\Str;
use Modules\CMS\Models\DocumentEntry;
use Modules\CMS\Models\DocumentHead;
use Modules\CMS\Models\DocumentType;

class DocumentEntryService
{
    use S3FileHandler;

    public function getAll(int $limit = 20) {
        return DocumentEntry::query()
        ->searchByFields(['document_type_id'])
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        // if (isset($data['attachment'])) {
        //     $data['attachment'] = $this->uploadFile($data['attachment']);
        // }
        return DocumentEntry::create($data);
    }

    public function update(DocumentEntry $documentEntry, array $data)
    {
        // if (isset($data['attachment'])) {
        //     $data['attachment'] = $this->uploadFile($data['attachment']);
        // }
        $documentEntry->update($data);
        return $documentEntry;
    }

    public function delete(DocumentEntry $documentEntry)
    {
        $documentEntry->delete();
    }

    public function show($id)
    {
        return DocumentEntry::findOrFail($id);
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        // Map document type name to ID
        $documentType = DocumentType::where('name', $jsonData['document_type_name'])->first();
        if (!$documentType) {
            throw new \Exception("Document Type not found: {$jsonData['document_type_name']}");
        }

        // Map document head name to ID
        $documentHead = DocumentHead::where('name', $jsonData['document_head_name'])
            ->where('document_type_id', $documentType->id)
            ->first();
        if (!$documentHead) {
            throw new \Exception("Document Head not found: {$jsonData['document_head_name']} for Document Type: {$jsonData['document_type_name']}");
        }

        return [
            'document_type_id' => $documentType->id,
            'document_head_id' => $documentHead->id,
            'date' => $jsonData['date'] ?? now()->toDateString(),
            'remarks' => $jsonData['remarks'] ?? null,
            'attachment' => $jsonData['attachment'] ?? null,
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

        $message = "Document Entries import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

}
