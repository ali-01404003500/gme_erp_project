<?php

namespace Modules\LocationManager\Services;

use App\Models\GeoLocation;


class DistrictService
{
    
    public function getAll(int $limit = 20) {
        return GeoLocation::query()
        ->likeSearch('name')
        ->when(request('division_id'), function($query) {
            $query->where('parent_id', request('division_id'));
        })
        ->where('type', 'District')->paginate($limit);
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
