<?php

namespace Modules\Account\Services;

use Modules\Account\Models\AccountControl;

class AccountControlService
{
    
    public function getAll(int $limit = 20) {
        return AccountControl::query()
        ->searchByFields(['account_group_id'])
        ->likeSearch('name')
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return AccountControl::create($data);
    }

    public function update(AccountControl $accountControl, array $data)
    {
        $accountControl->update($data);
        return $accountControl;
    }

    public function delete(AccountControl $accountControl)
    {
        $accountControl->delete();
    }

    public function show($id)
    {
        return AccountControl::findOrFail($id);
    }
}
