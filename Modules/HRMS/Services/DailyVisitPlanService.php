<?php

namespace Modules\HRMS\Services;

use Carbon\Carbon;
use Modules\HRMS\Models\DailyVisitPlan;

class DailyVisitPlanService
{
    
    public function getAll(int $limit = 20) {
        return DailyVisitPlan::query()
        ->when(auth()->id() != 1, function ($qr) {
            $qr->where('created_by', auth()->id());
        })
        ->when(request()->filled('from'), function ($qr) {
            $qr->where('date', '>=', Carbon::parse( request('from'))->format('Y-m-d'));
        })
        ->when(request()->filled('to'), function ($qr) {
            $qr->where('date', '<=', Carbon::parse( request('to'))->format('Y-m-d'));
        })
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return DailyVisitPlan::create($data);
    }

    public function update(DailyVisitPlan $dailyVisitPlan, array $data)
    {
        $dailyVisitPlan->update($data);
        return $dailyVisitPlan;
    }

    public function delete(DailyVisitPlan $dailyVisitPlan)
    {
        $dailyVisitPlan->delete();
    }

    public function show($id)
    {
        return DailyVisitPlan::findOrFail($id);
    }
}
