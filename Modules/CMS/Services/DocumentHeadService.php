<?php
namespace Modules\CMS\Services;

use Modules\CMS\Models\DocumentHead;

class DocumentHeadService
{

    public function getAll(int $limit = 20)
    {
        return DocumentHead::query()
            ->with(['documentType']) // ← Eager Loading যোগ করুন
            ->searchByFields(['name'])
            ->paginate($limit);
    }

    public function store(array $data)
    {
        return DocumentHead::create($data);
    }

    public function update(DocumentHead $documentHead, array $data)
    {
        $documentHead->update($data);
        return $documentHead;
    }

    public function delete(DocumentHead $documentHead)
    {
        $documentHead->delete();
    }

    public function show($id)
    {
        return DocumentHead::with(['documentType'])->findOrFail($id); // ← যোগ করুন
    }
}
