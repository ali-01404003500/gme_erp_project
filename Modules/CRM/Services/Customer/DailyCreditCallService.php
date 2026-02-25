<?php

namespace Modules\CRM\Services\Customer;

use Modules\CRM\Models\Customer\DailyCreditCall;
 
 


class DailyCreditCallService
{
    
    public function getAll(int $limit = 20) {
        return DailyCreditCall::query();
        //->paginate($limit);
    }
    
    public function store(array $data)
    {
        return DailyCreditCall::create($data);
    }

    public function update(DailyCreditCall $dailyCreditCall, array $data)
    {
        $dailyCreditCall->update($data);
        return $dailyCreditCall;
    }

    public function delete(DailyCreditCall $dailyCreditCall)
    {
        $dailyCreditCall->delete();
    }

    public function show($id)
    {
        $data = DailyCreditCall::where('customer_id', $id)
            ->with('createBy')
            ->orderBy('call_date', 'desc')
            ->get()
            ->toArray();
  
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
