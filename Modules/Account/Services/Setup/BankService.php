<?php

namespace Modules\Account\Services\Setup;

use Modules\Account\Models\Setup\Bank;

class BankService
{
    
    public function getAll(int $limit = 20) {
        return Bank::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Bank::create($data);
    }

    public function update(Bank $bank, array $data)
    {
        $bank->update($data);
        return $bank;
    }

    public function delete(Bank $bank)
    {
        $bank->delete();
    }

    public function show($id)
    {
        return Bank::findOrFail($id);
    }
}
