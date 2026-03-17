<?php
namespace Modules\HRMS\Services;

use Modules\HRMS\Models\AttendancePolicy;

class AttendancePolicyService
{
    public function getAllPolicies($search = null)
    {
        $query = AttendancePolicy::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
            // ->orWhere('effective_from', 'LIKE', "%{$search}%");
        }

        return $query->latest()->get();
    }

    public function storePolicy(array $data)
    {
        AttendancePolicy::updated(['status' => 0]);  
        return AttendancePolicy::create($this->formatData($data));
    }

    public function updatePolicy($id, array $data)
    {
        $policy = AttendancePolicy::findOrFail($id);
        $policy->update($this->formatData($data));
        return $policy;
    }

    /**
     * data format for AttendancePolicy model
     */
    private function formatData(array $data)
    {
        return [
            'name'                 => $data['name'],
            'effective_from'       => $data['effective_from'],
            'working_hours'        => $data['working_hours'] ?? null,
            'in_time'              => $data['in_time'] ?? null,
            'delay_buffer'         => $data['delay_buffer'] ?? '00:00',
            'ex_delay_buffer'      => $data['ex_delay_buffer'] ?? '00:00',
            'early_out_time'       => $data['early_out_time'] ?? null,
            'break_time'           => $data['break_time'] ?? 0,
            // checkbox settings
            'ignore_ot_deduction'  => filter_var($data['ignore_ot_deduction'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'exclude_from_reports' => filter_var($data['exclude_from_reports'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'discard_weekend'      => filter_var($data['discard_weekend'] ?? false, FILTER_VALIDATE_BOOLEAN),

            'day_wise_settings'    => $data['days'] ?? [],
        ];
    }
}
