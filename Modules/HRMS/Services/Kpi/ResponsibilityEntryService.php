<?php

namespace Modules\HRMS\Services\Kpi;

use Modules\HRMS\Models\Kpi\ResponsibilityEntry;

class ResponsibilityEntryService
{
    
    public function getAll(int $limit = 20) {
        return ResponsibilityEntry::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ResponsibilityEntry::create($data);
    }

    public function update(ResponsibilityEntry $responsibilityEntry, array $data)
    {
        $responsibilityEntry->update($data);
        return $responsibilityEntry;
    }

    public function delete(ResponsibilityEntry $responsibilityEntry)
    {
        $responsibilityEntry->delete();
    }

    public function show($id)
    {
        return ResponsibilityEntry::findOrFail($id);
    }
}
