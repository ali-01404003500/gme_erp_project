<?php

namespace App\Services;

use App\Models\UserLogHistory;
use Illuminate\Support\Facades\DB;

class UserLogHistoryService
{
    
    public function getAll(int $limit = 20) {
        return UserLogHistory::select(DB::raw('count(*) as count, title, action'))
            ->groupBy('title', 'action')
            ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return UserLogHistory::create($data);
    }

    public function update(UserLogHistory $userLogHistory, array $data)
    {
        $userLogHistory->update($data);
        return $userLogHistory;
    }

    public function delete(UserLogHistory $userLogHistory)
    {
        $userLogHistory->delete();
    }

    public function show($id)
    {
        return UserLogHistory::findOrFail($id);
    }
}
