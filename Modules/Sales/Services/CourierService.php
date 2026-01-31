<?php

namespace Modules\Sales\Services;


use Modules\Sales\Models\Courier;

class CourierService
{
    
    public function getAll(int $limit = 20) {
        return Courier::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        $courier = Courier::create($data);
        return $courier;
    }

    public function update(Courier $courier, array $data)
    {
        $courier->update($data);
        return $courier;
    }

    public function delete(Courier $courier)
    {
        $courier->delete();
    }

    public function show($id)
    {
        return Courier::findOrFail($id);
    }
}
