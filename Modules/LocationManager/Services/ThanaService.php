<?php

namespace Modules\LocationManager\Services;

use App\Models\GeoLocation;


class ThanaService
{
    
    public function getAll(int $limit = 20) {
        return GeoLocation::query()
        ->likeSearch('name')
        ->when(request('division_id'), function ($query) {
            $query->where('parent_id', request('division_id'));
        })
        
        ->where('type', 'Thana')->with('parent.parent')
        ->when(request('district_id'), function ($query) {
            $query->where('parent_id', request('district_id'));
        })
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return GeoLocation::create($data);
    }

    public function update($thana, array $data)
    {
        $thana->update($data);
        return $thana;
    }

    public function delete($thana)
    {
        $thana->delete();
    }

   
}
