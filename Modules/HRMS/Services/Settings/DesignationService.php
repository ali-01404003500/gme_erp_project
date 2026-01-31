<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\Designation;

class DesignationService
{
    
    public function getAll(int $limit = 20) {
        return Designation::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Designation::create($data);
    }

    public function update(Designation $designation, array $data)
    {
        $designation->update($data);
        return $designation;
    }

    public function delete(Designation $designation)
    {
        $designation->delete();
    }

    public function show($id)
    {
        return Designation::findOrFail($id);
    }
}
