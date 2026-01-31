<?php

namespace Modules\Account\Services\Setup;

use Modules\Account\Models\Setup\BankBranch;

class BankBranchService
{
    
    public function getAll(int $limit = 20) {
        return BankBranch::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return BankBranch::create($data);
    }

    public function update(BankBranch $bankBranch, array $data)
    {
        $bankBranch->update($data);
        return $bankBranch;
    }

    public function delete(BankBranch $bankBranch)
    {
        $bankBranch->delete();
    }

    public function show($id)
    {
        return BankBranch::findOrFail($id);
    }
}
