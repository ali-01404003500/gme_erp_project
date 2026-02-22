<?php

namespace Modules\Account\Services;

use Modules\Account\Models\FundTransfer;

class FundTransferService
{
    
    public function getAll(int $limit = 20) {
        return FundTransfer::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return FundTransfer::create($data);
    }

    public function update(FundTransfer $fundTransfer, array $data)
    {
        $fundTransfer->update($data);
        return $fundTransfer;
    }

    public function delete(FundTransfer $fundTransfer)
    {
        $fundTransfer->delete();
    }

    public function show($id)
    {
        return FundTransfer::findOrFail($id);
    }
}
