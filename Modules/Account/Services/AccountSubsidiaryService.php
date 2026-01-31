<?php

namespace Modules\Account\Services;

use Modules\Account\Models\AccountSubsidiary;

class AccountSubsidiaryService
{
    
    public function getAll(int $limit = 20) {
        return AccountSubsidiary::query()
        ->searchByFields(['account_group_id', 'account_control_id'])
        ->likeSearch('name')
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return AccountSubsidiary::create($data);
    }

    public function update(AccountSubsidiary $accountSubsidiary, array $data)
    {
        $accountSubsidiary->update($data);
        return $accountSubsidiary;
    }

    public function delete(AccountSubsidiary $accountSubsidiary)
    {
        $accountSubsidiary->delete();
    }

    public function show($id)
    {
        return AccountSubsidiary::findOrFail($id);
    }
}
