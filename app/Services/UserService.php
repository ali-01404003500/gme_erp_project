<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    
    public function getAll(int $limit = 20) {
        return User::query()->paginate($limit);
    }
    
    public function create(array $data)
    {
        // dd($data);
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user)
    {
        $user->delete();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }
}
