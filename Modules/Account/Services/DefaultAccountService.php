<?php

namespace Modules\Account\Services;

use Modules\Account\Models\DefaultAccount;

class DefaultAccountService
{
    
    public function getAll(int $limit = 20) {
        return DefaultAccount::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return DefaultAccount::create($data);
    }

    public function update(DefaultAccount $defaultAccount, array $data)
    {
        $defaultAccount->update($data);
        return $defaultAccount;
    }

    public function delete(DefaultAccount $defaultAccount)
    {
        $defaultAccount->delete();
    }

    public function show($id)
    {
        return DefaultAccount::findOrFail($id);
    }
}
