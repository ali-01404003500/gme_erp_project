<?php
namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\LeaveGroup;

class LeaveGroupService
{
    public function getAllGroups()
    {
        return LeaveGroup::with('leaveTypes')->latest()->get();
    }

    public function storeGroup(array $data)
    {
        return DB::transaction(function () use ($data) {
            $group = LeaveGroup::create(['group_name' => $data['group_name']]);

            if (isset($data['configs']) && is_array($data['configs'])) {
                $group->leaveTypes()->sync($this->prepareSyncData($data['configs']));
            }
            return $group;
        });
    }

    public function updateGroup($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $group = LeaveGroup::findOrFail($id);
            $group->update(['group_name' => $data['group_name']]);

            if (isset($data['configs']) && is_array($data['configs'])) {
                $group->leaveTypes()->sync($this->prepareSyncData($data['configs']));
            }
            return $group;
        });
    }

    public function deleteGroup($id)
    {
        return LeaveGroup::findOrFail($id)->delete();
    }

    private function prepareSyncData(array $configs): array
    {
        $syncData = [];
        foreach ($configs as $leaveTypeId => $config) {
            $syncData[$leaveTypeId] = [
                'allowed_balance'                    => $config['allowed_balance'] ?? 0,
                'max_leave_balance_in_year'          => $config['max_leave_balance_in_year'] ?? 0,
                'continuous_sanction'                => $config['continuous_sanction'] ?? 0,
                'max_forward_from_previous_year'     => $config['max_forward_from_previous_year'] ?? 0,
                'max_sanction_in_service_life'       => $config['max_sanction_in_service_life'] ?? 0,
                'interval_days_in_same_leave'        => $config['interval_days_in_same_leave'] ?? 0,
                'min_day_count_for_attachment'       => $config['min_day_count_for_attachment'] ?? 0,
                'max_limit_for_past_leave'           => $config['max_limit_for_past_leave'] ?? 0,
                'apply_future_leave_after_days'      => $config['apply_future_leave_after_days'] ?? 0,
                'max_balance_for_encashment'         => $config['max_balance_for_encashment'] ?? null,
                'is_balance_forward'                 => isset($config['is_balance_forward']) ? 1 : 0,
                'allow_leave_encashment'             => isset($config['allow_leave_encashment']) ? 1 : 0,
                'balance_forwarding_on_group_change' => isset($config['balance_forwarding_on_group_change']) ? 1 : 0,
                'leave_allow_between_multiple_years' => isset($config['leave_allow_between_multiple_years']) ? 1 : 0,
                'negative_balance'                   => isset($config['negative_balance']) ? 1 : 0,
                'is_half_day'                        => isset($config['is_half_day']) ? 1 : 0,
                'continuous_days_allow'              => isset($config['continuous_days_allow']) ? 1 : 0,
                'is_prefix_allowed'                  => isset($config['is_prefix_allowed']) ? 1 : 0,
                'is_suffix_allowed'                  => isset($config['is_suffix_allowed']) ? 1 : 0,
                'requires_leave_attachment'          => isset($config['requires_leave_attachment']) ? 1 : 0,
                'allow_earn_leave'                   => isset($config['allow_earn_leave']) ? 1 : 0,
            ];
        }
        return $syncData;
    }
}
