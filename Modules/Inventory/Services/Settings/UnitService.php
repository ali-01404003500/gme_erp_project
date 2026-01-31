<?php

namespace Modules\Inventory\Services\Settings;
use Modules\Inventory\Models\Settings\Unit;

class UnitService
{
    
    public function getAll(int $limit = 20) {
        return Unit::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Unit::create($data);
    }

    public function update(Unit $unit, array $data)
    {
        $unit->update($data);
        return $unit;
    }

    public function delete(Unit $unit)
    {
        $unit->delete();
    }

    public function show($id)
    {
        return Unit::findOrFail($id);
    }
}
