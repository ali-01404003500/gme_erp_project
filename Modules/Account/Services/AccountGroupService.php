<?php

namespace Modules\Account\Services;

use Modules\Account\Models\AccountGroup;

class AccountGroupService
{
    
    public function getAll(int $limit = 20) {
        return AccountGroup::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return AccountGroup::create($data);
    }

    public function update(AccountGroup $accountGroup, array $data)
    {
        $accountGroup->update($data);
        return $accountGroup;
    }

    public function delete(AccountGroup $accountGroup)
    {
        $accountGroup->delete();
    }

    public function show($id)
    {
        return AccountGroup::findOrFail($id);
    }
}
