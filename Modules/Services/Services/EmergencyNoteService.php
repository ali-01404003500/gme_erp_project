<?php

namespace Modules\Services\Services;

use Modules\Services\Models\EmergencyNote;

class EmergencyNoteService
{
    
    public function getAll(int $limit = 20) {
        return EmergencyNote::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return EmergencyNote::create($data);
    }

    public function update(EmergencyNote $emergencyNote, array $data)
    {
        $emergencyNote->update($data);
        return $emergencyNote;
    }

    public function delete(EmergencyNote $emergencyNote)
    {
        $emergencyNote->delete();
    }

    public function show($id)
    {
        return EmergencyNote::findOrFail($id);
    }
    
}
