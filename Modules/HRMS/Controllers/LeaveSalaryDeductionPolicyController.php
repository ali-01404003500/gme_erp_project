<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\AbsentPolicy;
use Modules\HRMS\Models\DelayPolicy;
use Modules\HRMS\Models\EarlyOutDeductionPolicy;
use Modules\HRMS\Models\ExtremeDelayPolicy;
use Modules\HRMS\Models\MissedOutTimeDeductionPolicy;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Models\UnderworkDeductionPolicy;
use Modules\HRMS\Models\UnpaidLeaveDeductionPolicy;

class LeaveSalaryDeductionPolicyController extends Controller
{
    public function index()
    {
        $policies = [
            'absent'        => AbsentPolicy::latest()->first() ?? (object) [
                'consider_absent'    => 0, 
                'deduct_from_gross'  => 0, 
            ],
            'delay'         => DelayPolicy::latest()->first() ?? (object) [
                'consider_delay'             => 0,
                'deduct_from_gross_salary'         => 0, 
                'delay_limit'                => 0,
                'adjust_days'                => 0,
            ],
            'extreme_delay' => ExtremeDelayPolicy::latest()->first() ?? (object) [
                'consider_extreme_delay'             => 0,
                'deduct_from_gross_salary'                 => 0, 
                'extreme_delay_limit'                => 0,
                'adjust_days'                        => 0,
            ],
            'early_out'     => EarlyOutDeductionPolicy::latest()->first() ?? (object) [
                'consider_early_out'             => 0,
                'deduct_from_gross'              => 0,
                'consider_consecutive_early_out' => 0,
                'early_out_limit'                => 0,
                'adjust_days'                    => 0,
            ],
            'unpaid_leave'  => UnpaidLeaveDeductionPolicy::latest()->first() ?? (object) [
                'consider_unpaid_leave' => 0,
                'deduct_from_gross'     => 0,
            ],
            'missed_out'    => MissedOutTimeDeductionPolicy::latest()->first() ?? (object) [
                'consider_missed_out'  => 0,
                'deduct_from_gross'    => 0,
                'consider_consecutive' => 0,
                'missed_out_limit'     => 0,
                'adjust_days'          => 0,
            ],
        ];

        return view('HRMS::salary-deduction-policy.index', compact('policies'));
    }

    public function store(Request $request)
    {
        $policyType = $request->input('policy_type');

        //============ Absent Policy
        if ($policyType === 'absent') {
            AbsentPolicy::updateOrCreate(
                ['id' => 1],
                [
                    'consider_absent'    => $request->has('absent_consider'), 
                    'deduct_from_gross'  => $request->has('absent_deduct_gross'), 
                ]
            );
        }

        //============ Delay Policy
        if ($policyType === 'delay') {
            DelayPolicy::updateOrCreate(
                ['id' => 1],
                [
                    'consider_delay'             => $request->has('delay_consider'),
                    'deduct_from_gross_salary'         => $request->has('delay_deduct_gross_salary'), 
                    'delay_limit'                => $request->delay_limit ?? 4,
                    'adjust_days'                => $request->delay_adjust ?? 1,
                ]
            );
        }

        //============ Extreme Delay Policy
        if ($policyType === 'extreme_delay') {
            ExtremeDelayPolicy::updateOrCreate(
                ['id' => 1],
                [
                    'consider_extreme_delay'             => $request->has('ext_consider'),
                    'deduct_from_gross_salary'                 => $request->has('ext_deduct_gross_salary'), 
                    'extreme_delay_limit'                => $request->input('ext_limit', 4),
                    'adjust_days'                        => $request->input('ext_adjust', 1),
                ]
            );
        }

        //============ Early out
        if ($policyType === 'early_out') {
            EarlyOutDeductionPolicy::updateOrCreate(
                ['id' => 1],
                [
                    'consider_early_out'             => $request->has('early_out_consider'),
                    'deduct_from_gross'              => $request->has('early_out_deduct_gross'), 
                    'early_out_limit'                => $request->early_out_limit ?? 0,
                    'adjust_days'                    => $request->early_out_adjust ?? 0,
                ]
            );
        }

       
        //============ unpaid leave
        if ($policyType === 'unpaid_leave') {
            UnpaidLeaveDeductionPolicy::updateOrCreate(
                ['id' => 1],
                [
                    'unpaid_consider'     => $request->has('unpaid_consider'),
                    'unpaid_deduct_gross' => $request->has('unpaid_deduct_gross'),
                ]
            );
        }
        //============ missed_out_time_deduction_policies
        if ($policyType === 'missed_out') {
            MissedOutTimeDeductionPolicy::updateOrCreate(
                ['id' => 1],
                [
                    'consider_missed_out'  => $request->has('missed_out_consider'),
                    'deduct_from_gross'    => $request->has('missed_out_deduct_gross'), 
                    'missed_out_limit'     => $request->missed_out_limit ?? 0,
                    'adjust_days'          => $request->missed_out_adjust ?? 0,
                ]
            );
        }
        return redirect()->back()->with('activeTab', $policyType)->with('success', ucfirst(str_replace('_', ' ', $policyType)) . ' Policy updated successfully.');
    }
}
