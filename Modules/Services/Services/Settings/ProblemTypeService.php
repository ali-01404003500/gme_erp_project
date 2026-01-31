<?php

namespace Modules\Services\Services\Settings;

use Modules\Services\Models\Settings\ProblemType;

class ProblemTypeService
{
    
    public function getAll(int $limit = 20) {
        return ProblemType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ProblemType::create($data);
    }

    public function update(ProblemType $problemType, array $data)
    {
        $problemType->update($data);
        return $problemType;
    }

    public function delete(ProblemType $problemType)
    {
        $problemType->delete();
    }

    public function show($id)
    {
        return ProblemType::findOrFail($id);
    }
}
