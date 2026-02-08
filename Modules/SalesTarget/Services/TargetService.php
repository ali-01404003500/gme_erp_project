<?php

namespace Modules\SalesTarget\Services;

use Modules\SalesTarget\Models\Target;
use App\Models\User;

class TargetService
{
    /**
     * Fetch all employees for the dropdown selection.
     */
    public function getAllEmployees()
    {
        return User::all();
    }
    

    /**
     * Fetch all saved targets with employee details for the registry table.
     */
    public function getAllTargets()
    {
        return Target::with('employee')->orderBy('year', 'desc')->get();
    }

    /**
     * Process and store multiple target entries from the dynamic table.
     * Fixed to handle the array input from your new Create Blade.
     */
    public function storeMultipleTargets(array $targetsData)
    {
        foreach ($targetsData as $data) {
            // Ensure we don't process empty rows
            if (empty($data['employee_id'])) continue;

            Target::updateOrCreate(
                [
                    'employee_id' => $data['employee_id'],
                    'year'        => $data['year'] ?? date('Y'),
                ],
                [
                    'jan_target'   => $data['jan_target'] ?? 0,
                    'feb_target'   => $data['feb_target'] ?? 0,
                    'mar_target'   => $data['mar_target'] ?? 0,
                    'apr_target'   => $data['apr_target'] ?? 0,
                    'may_target'   => $data['may_target'] ?? 0,
                    'jun_target'   => $data['jun_target'] ?? 0,
                    'jul_target'   => $data['jul_target'] ?? 0,
                    'aug_target'   => $data['aug_target'] ?? 0,
                    'sep_target'   => $data['sep_target'] ?? 0,
                    'oct_target'   => $data['oct_target'] ?? 0,
                    'nov_target'   => $data['nov_target'] ?? 0,
                    'dec_target'   => $data['dec_target'] ?? 0,
                    'total_target' => $data['total_target'] ?? 0,
                ]
            );
        }
    }
    public function deleteTarget($id)
    {
        $target = Target::findOrFail($id);
        return $target->delete();
    }
}