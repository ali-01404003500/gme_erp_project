<?php

namespace Modules\Account\Services\AccountSettings;

use Modules\Account\Models\AccountSettings\DefaultPayableReceivable;

class DefaultPayableReceivableService
{
    
    public function getAll(int $limit = 20) {
        return DefaultPayableReceivable::query()->paginate($limit);
    }
    
    public function store(array $data)
{
    DefaultPayableReceivable::truncate();
    foreach ($data as $item) {
        if (is_null($item['account_id'])) {
            continue; // Skip if `account_id` is null
        }

        DefaultPayableReceivable::create([
            'type' => $item['type'],
            'account_id' => $item['account_id'],
        ]);
    }
}


    public function update(DefaultPayableReceivable $defaultPayableReceivable, array $data)
    {
        $defaultPayableReceivable->update($data);
        return $defaultPayableReceivable;
    }

    public function delete(DefaultPayableReceivable $defaultPayableReceivable)
    {
        $defaultPayableReceivable->delete();
    }

    public function show($id)
    {
        return DefaultPayableReceivable::findOrFail($id);
    }
}
