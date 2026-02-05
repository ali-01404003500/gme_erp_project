<?php

namespace Modules\SalesTarget\Services;

use Modules\SalesTarget\Models\AchievementBasedSalaryPolicy;

class AchievementBasedSalaryPolicyService
{
    
    public function getAll(int $limit = 20) {
        return AchievementBasedSalaryPolicy::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return AchievementBasedSalaryPolicy::create($data);
    }

    public function update(AchievementBasedSalaryPolicy $achievementBasedSalaryPolicy, array $data)
    {
        $achievementBasedSalaryPolicy->update($data);
        return $achievementBasedSalaryPolicy;
    }

    public function delete(AchievementBasedSalaryPolicy $achievementBasedSalaryPolicy)
    {
        $achievementBasedSalaryPolicy->delete();
    }

    public function show($id)
    {
        return AchievementBasedSalaryPolicy::findOrFail($id);
    }
}
