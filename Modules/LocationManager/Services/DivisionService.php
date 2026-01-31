<?php

namespace Modules\LocationManager\Services;

use App\Models\GeoLocation;


class DivisionService
{
    
    public function getAll(int $limit = 20) {
        return GeoLocation::query()
        ->likeSearch('name')
        ->where('type', 'Division')
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return GeoLocation::create($data);
    }

    public function update($division, array $data)
    {
        $division->update($data);
        return $division;
    }

    public function delete($division)
    {
        $division->delete();
    }

   
}
