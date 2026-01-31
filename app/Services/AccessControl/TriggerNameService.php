<?php

namespace App\Services\AccessControl;

use App\Models\AccessControl\TriggerName;

class TriggerNameService
{
    
    public function getAll(int $limit = 20) {
        return TriggerName::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return TriggerName::create($data);
    }

    public function update(TriggerName $triggerName, array $data)
    {
        $triggerName->update($data);
        return $triggerName;
    }

    public function delete(TriggerName $triggerName)
    {
        $triggerName->delete();
    }

    public function show($id)
    {
        return TriggerName::findOrFail($id);
    }
}
