<?php

namespace Modules\SalesTarget\Services;

use Modules\SalesTarget\Models\Target;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TargetService
{

    // Get all employees
    public function getAllEmployees()
    {
        return User::all();
    }


    // Get all targets
    public function getAllTargets()
    {
        return Target::with('employee')->orderBy('year', 'desc')->get();
    }

    
    // Store multiple targets
    public function storeMultipleTargets(array $targetsData)
    {
        return DB::transaction(function () use ($targetsData) {
            foreach ($targetsData as $data) {
                if (empty($data['employee_id'])) continue;

                // Search by target_id column instead of id
                $targetId = $data['target_id'] ?? ($data['id'] ?? null);

                Target::updateOrCreate(
                    [
                        'target_id' => $targetId,
                    ],
                    [
                        'employee_id'  => $data['employee_id'],
                        'year'         => $data['year'] ?? date('Y'),
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
        });
    }


    // Delete target

    public function deleteTarget($id)
    {
        $target = Target::findOrFail($id);
        return $target->delete();
    }
}
