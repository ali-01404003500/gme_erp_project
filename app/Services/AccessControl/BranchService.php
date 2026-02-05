<?php

namespace App\Services\AccessControl;

use App\Models\AccessControl\Branch;
use App\Traits\AutoCreatedUpdated;
use App\Traits\S3FileHandler;

class BranchService
{
    use S3FileHandler;
    
    public function getAll(int $limit = 20) {
        return Branch::query()
        ->likeSearch('name')
        ->paginate($limit);
    }
    
    public function store(array $data, array  $branches = [])
    {
        if(isset($data['image'])) {
            $data['image'] = $this->uploadFile($data['image'], 'branches');
        }

        $result['branch'] = Branch::create($data);
        foreach ($branches['names'] as $key => $name) {
            if($name == null) continue;

            $result['branch']->contactPersionDetails()->create([
                'name' => $name,
                'mobile' => $branches['mobiles'][$key]??null,
                'designation' => $branches['designations'][$key]??null,
            ]);
        }

        return $result;
    }

    public function update(Branch $branch, array $data, array $branches = [])
    {

        if(isset($data['image'])) {
            $data['image'] = $this->uploadFile($data['image'], 'branches');
        }

        $branch->update($data);
        $branch->contactPersionDetails()->delete();

        if (isset($branches['names'])) {
            // Delete all existing contact person details first
            foreach ($branches['names'] as $key => $name) {
                if ($name === null) {
                    continue;
                }
    
                // Create new contact person details
                $branch->contactPersionDetails()->create([
                    'name' => $name,
                    'mobile' => $branches['mobiles'][$key] ?? null,
                    'designation' => $branches['designations'][$key] ?? null,
                ]);
            }
        }
    
        return $branch;
    }
    

    public function delete(Branch $branch)
    {
        $branch->contactPersionDetails()->delete();
        $branch->delete();
    }

    public function show($id)
    {
        return Branch::findOrFail($id);
    }
}
