<?php

namespace Modules\LocationManager\Services;

use Modules\LocationManager\Models\Location;

class LocationService
{
    
    public function getAll(int $limit = 20) {
        return Location::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        return Location::create($data);
    }

    public function update(Location $location, array $data)
    {
        $location->update($data);
        return $location;
    }

    public function delete(Location $location)
    {
        $location->delete();
    }

    public function show($id)
    {
        return Location::findOrFail($id);
    }
}
