<?php

namespace Modules\LocationManager\Services;

use Modules\LocationManager\Models\LocationType;

class LocationTypeService
{
    
    public function getAll(int $limit = 20) {
        return LocationType::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        return LocationType::create($data);
    }

    public function update(LocationType $locationType, array $data)
    {
        $locationType->update($data);
        return $locationType;
    }

    public function delete(LocationType $locationType)
    {
        $locationType->delete();
    }

    public function show($id)
    {
        return LocationType::findOrFail($id);
    }
}
