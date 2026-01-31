<?php

namespace Modules\Account\Services;

use Modules\Account\Models\Account;

class AccountService
{
    
    public function getAll(int $limit = 20) {
        return Account::query()
        ->searchByFields(['account_group_id', 'account_control_id', 'account_subsidiary_id'])
        ->likeSearch('name')
        ->orderBy('account_group_id', 'asc')
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Account::create($data);
    }

    public function update(Account $account, array $data)
    {
        $account->update($data);
        return $account;
    }

    public function delete(Account $account)
    {
        $account->delete();
    }

    public function show($id)
    {
        return Account::findOrFail($id);
    }
}
