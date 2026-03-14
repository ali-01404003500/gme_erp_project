<?php

namespace Modules\CRM\Services\Customer;

use Modules\CRM\Models\Customer\DailyCreditCall;
use Modules\CRM\Models\Customer\DailyLegalTask;

class DailyCreditCallService
{
    
    public function getAll(int $limit = 20) {
        return DailyCreditCall::query();
        
    }


    public function store(array $data)
    {
        return DailyCreditCall::create($data);
    }
      public function legalStore(array $data)
    {
        return DailyLegalTask::create($data);
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
            ->with('createdBy')
            ->orderBy('call_date', 'desc')
            ->get();
  
        return $data;
    }


    
    public function legalShow($id)
    {
        $data = DailyLegalTask::where('customer_id', $id)
            ->with('createdBy')
            ->orderBy('created_at', 'desc')
            ->get();
  
        return $data;
    }
}
