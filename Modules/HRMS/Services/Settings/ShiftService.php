<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\Shift;

class ShiftService
{
    
    public function getAll(int $limit = 20) {
        return Shift::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Shift::create($data);
    }

    public function update(Shift $shift, array $data)
    {
        $shift->update($data);
        return $shift;
    }

    public function delete(Shift $shift)
    {
        $shift->delete();
    }

    public function show($id)
    {
        return Shift::findOrFail($id);
    }
}
