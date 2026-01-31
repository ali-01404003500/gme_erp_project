<?php

namespace App\Services\AccessControl;

use App\Models\AccessControl\BranchType;

class BranchTypeService
{
    
    public function getAll(int $limit = 20) {
        return BranchType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return BranchType::create($data);
    }

    public function update(BranchType $branchType, array $data)
    {
        $branchType->update($data);
        return $branchType;
    }

    public function delete(BranchType $branchType)
    {
        $branchType->delete();
    }

    public function show($id)
    {
        return BranchType::findOrFail($id);
    }
}
