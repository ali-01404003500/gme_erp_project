<?php

namespace Modules\CMS\Services;

use Modules\CMS\Models\DocumentType;

class DocumentTypeService
{
    
    public function getAll(int $limit = 20) {
        return DocumentType::query()
        ->searchByFields(['name'])
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return DocumentType::create($data);
    }

    public function update(DocumentType $documentType, array $data)
    {
        $documentType->update($data);
        return $documentType;
    }

    public function delete(DocumentType $documentType)
    {
        $documentType->delete();
    }

    public function show($id)
    {
        return DocumentType::findOrFail($id);
    }
}
