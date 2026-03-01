<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\Holiday;

class HolidayService
{
    
    public function getAll(int $limit = 20) {
        return Holiday::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        Holiday::create([
            'name' => $data['name'],
            'day_type' => $data['holiday_day_type'],
            'start_date' => $data['holiday_day_type'] == 2 ? null : $data['start_date'],
            'end_date' => $data['holiday_day_type'] == 2 ? null : $data['end_date'],
            'every_year' => $data['every_year'],
            'day_name' => $data['holiday_day_type'] == 2 ? implode(',', $data['day']) : '',

            // new fields
           
            'department' => $data['department'] ?? null,
        ]);
    }

    public function update(Holiday $holiday, array $data)
    {
        $holiday->update([
            'name' => $data['name'],
            'day_type' => $data['holiday_day_type'],
            'start_date' => $data['holiday_day_type'] == 2 ? null : $data['start_date'],
            'end_date' => $data['holiday_day_type'] == 2 ? null : $data['end_date'],
            'every_year' => $data['every_year'],
            'day_name' => $data['holiday_day_type'] == 2 ? implode(',', $data['day']) : '',

            //  new fields
                
                'department' => $data['department'] ?? null,
        ]);
        return $holiday;
    }

    public function delete(Holiday $holiday)
    {
        $holiday->delete();
    }

    public function show($id)
    {
        return Holiday::findOrFail($id);
    }
}
