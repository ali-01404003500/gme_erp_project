<?php
namespace Modules\HRMS\Services;

use Modules\HRMS\Models\LeaveYear;

class LeaveYearService
{
    public function getRunningYear()
    {
        $year = LeaveYear::where('is_closed', 0)->first();

        if (! $year) {
            return null;
        }

        return [
            'open_year'      => $year->year,
            'start_date'     => $year->start_date->format('d-m-Y'),
            'end_date'       => $year->end_date->format('d-m-Y'),
            'closing_status' => $year->is_closed ? 'Yes' : 'No',
        ];
    }

    public function getClosedYears()
    {
        return LeaveYear::where('is_closed', 1)
            ->with('closedByUser')
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($year, $index) {
                return [
                    'sl'        => $index + 1,
                    'year'      => $year->year,
                    'start'     => $year->start_date->format('d-m-Y'),
                    'end'       => $year->end_date->format('d-m-Y'),
                    'closed_by' => $year->closedByUser->email ?? 'System',
                ];
            });
    }

    public function storeYear(array $data)
    {
        if (LeaveYear::where('is_closed', 0)->exists()) {
            throw new \Exception("A leave year is already running.");
        }

        return LeaveYear::create([
            'year'       => $data['year'],
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'is_closed'  => 0,
        ]);
    }
}
