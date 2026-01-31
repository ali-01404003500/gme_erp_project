<?php

namespace Modules\HRMS\Services\Kpi;

use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Kpi\KpiTemplateAssignEmployee;
use Modules\HRMS\Models\Kpi\MonthlyKpiAppraisal;
use Modules\HRMS\Models\Kpi\MonthlyKpiAppraisalDetail;
use Modules\HRMS\Models\Kpi\ScoreWiseSuggestion;
use Carbon\Carbon;

class MonthlyKpiAppraisalService
{
    
    public function getAll(int $limit = 20)
    {
        return MonthlyKpiAppraisal::with(['employee'])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Get employee details and check for active KPI assignment
     */
    public function getEmployeeDetails($employeeId, $assessmentMonth)
    {
        $employee = Employee::with([
            'employementDetail.department',
            'employementDetail.designation',
            'employementDetail.branch'
        ])->findOrFail($employeeId);

        $monthDate = Carbon::parse($assessmentMonth)->startOfMonth();

        // Find active KPI assignment for the assessment month
        $kpiAssignment = KpiTemplateAssignEmployee::where('employee_id', $employeeId)
            ->with('details.responsibility')
            ->first();

        if (!$kpiAssignment) {
            return response()->json([
                'error' => 'No active KPI assignment found for this employee in the selected month.'
            ], 404);
        }
        // Check if appraisal already exists for this month (optional - commented out as per your code)
        $existingAppraisal = MonthlyKpiAppraisal::where('employee_id', $employeeId)
            ->where('assessment_month', $assessmentMonth)
            ->first();

        if ($existingAppraisal) {
            return response()->json([
                'error' => 'Appraisal already exists for this employee in the selected month.',
                'existing_appraisal_id' => $existingAppraisal->id
            ], 409);
        }


        return response()->json([
            'employee' => [
                'branch_name' => $employee->employementDetail->branch->name ?? 'N/A',
                'department_id' => $employee->employementDetail->department_id,
                'department_name' => $employee->employementDetail->department->name ?? 'N/A',
                'designation_id' => $employee->employementDetail->designation_id,
                'designation_name' => $employee->employementDetail->designation->name ?? 'N/A',
                'branch_id' => $employee->employementDetail->branch_id,
            ],
            'kpi_assignment' => [
                'id' => $kpiAssignment->id,
                'responsibilities' => $kpiAssignment->details->map(function ($detail) use ($assessmentMonth) {
                    $targetDays = $this->calculateTargetDays(
                        $detail->time,
                        $detail->frequency,
                        $assessmentMonth
                    );

                    return [
                        'responsibility_entry_id' => $detail->responsibility_entry_id,
                        'key_responsibility' => $detail->responsibility->description ?? '',
                        'target_days' => $targetDays,
                        'weight' => $detail->weight,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get remarks based on aggregate score from ScoreWiseSuggestion
     */
    public function getRemarksByScore($score)
    {
        $suggestion = ScoreWiseSuggestion::getSuggestionByScore($score);
        
        if ($suggestion) {
            return response()->json([
                'rating_grade' => $suggestion->rating_grade,
                'remarks' => $suggestion->remarks,
                'training_need' => $suggestion->training_need,
                'formatted_remarks' => $suggestion->getFormattedRemarks(),
            ]);
        }

        return response()->json([
            'rating_grade' => 'N/A',
            'remarks' => 'No suggestion available for this score.',
            'training_need' => null,
            'formatted_remarks' => 'No suggestion available for this score.',
        ]);
    }

    /**
     * Calculate target days based on frequency and assessment month
     */
    protected function calculateTargetDays($time, $frequency, $assessmentMonth)
    {
        $monthDate = Carbon::parse($assessmentMonth);
        $daysInMonth = $monthDate->daysInMonth;

        switch ($frequency) {
            case 'Day':
                return $time;
            case 'Month':
                return $time * 30;
            case 'Year':
                return $time * 365;
            default:
                return $time;
        }
    }

    /**
     * Store new monthly KPI appraisal
     */
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            // Create the main appraisal record
            $appraisal = MonthlyKpiAppraisal::create([
                'employee_id' => $data['employee_id'],
                'kpi_template_assign_employee_id' => $data['kpi_template_assign_employee_id'],
                'assessment_month' => $data['assessment_month'],
                'achieved_performance_score' => $data['achieved_performance_score'] ?? 0,
                'performance_score_note' => $data['performance_score_note'] ?? null,
                'succession_management_score' => $data['succession_management_score'] ?? 0,
                'succession_management_note' => $data['succession_management_note'] ?? null,
                'behavioral_performance_score' => $data['behavioral_performance_score'] ?? 0,
                'behavioral_performance_note' => $data['behavioral_performance_note'] ?? null,
                'status' => $data['status'] ?? 'Draft',
                'notes' => $data['notes'] ?? null,
            ]);

            // Store appraisal details
            foreach ($data['responsibilities'] as $resp) {
                MonthlyKpiAppraisalDetail::create([
                    'monthly_kpi_appraisal_id' => $appraisal->id,
                    'responsibility_entry_id' => $resp['responsibility_entry_id'],
                    'target_days' => $resp['target_days'],
                    'actual_days' => $resp['actual_days'] ?? 0,
                    'weight' => $resp['weight'],
                    'kpi_score' => $resp['kpi_score'] ?? 0,
                    'performance_score' => $resp['performance_score'] ?? 0,
                ]);
            }

            return $appraisal;
        });
    }

    /**
     * Update existing appraisal
     */
    public function update(MonthlyKpiAppraisal $appraisal, array $data)
    {
        return DB::transaction(function () use ($appraisal, $data) {
            // Update main appraisal record
            $updateData = [
                'achieved_performance_score' => $data['achieved_performance_score'] ?? 0,
                'performance_score_note' => $data['performance_score_note'] ?? null,
                'succession_management_score' => $data['succession_management_score'] ?? 0,
                'succession_management_note' => $data['succession_management_note'] ?? null,
                'behavioral_performance_score' => $data['behavioral_performance_score'] ?? 0,
                'behavioral_performance_note' => $data['behavioral_performance_note'] ?? null,
                'status' => $data['status'] ?? $appraisal->status,
                'notes' => $data['notes'] ?? null,
            ];

         
            $appraisal->update($updateData);

            // Update appraisal details
            if (isset($data['responsibilities'])) {
                foreach ($data['responsibilities'] as $resp) {
                    $detail = MonthlyKpiAppraisalDetail::find($resp['id']);
                    
                    if ($detail && $detail->monthly_kpi_appraisal_id == $appraisal->id) {
                        $detail->update([
                            'actual_days' => $resp['actual_days'] ?? 0,
                            'kpi_score' => $resp['kpi_score'] ?? 0,
                            'performance_score' => $resp['performance_score'] ?? 0,
                        ]);
                    }
                }
            }

            return $appraisal->fresh();
        });
    }

    /**
     * Delete appraisal
     */
    public function delete(MonthlyKpiAppraisal $appraisal)
    {
        return DB::transaction(function () use ($appraisal) {
            // Delete all related details first
            $appraisal->details()->delete();
            
            // Delete the appraisal
            $appraisal->delete();
            
            return true;
        });
    }

    /**
     * Show appraisal details
     */
    public function show($id)
    {
        return MonthlyKpiAppraisal::with([
            'employee.employementDetail.department',
            'employee.employementDetail.designation',
            'employee.employementDetail.branch',
            'details.responsibility',
            'kpiAssignment',
        ])->findOrFail($id);
    }

    /**
     * Approve appraisal
     */
    public function approve(MonthlyKpiAppraisal $appraisal)
    {
        return DB::transaction(function () use ($appraisal) {
            $appraisal->update([
                'status' => 'Approved',
                'approved_by' => auth()->user()->id,
            ]);

            return $appraisal->fresh();
        });
    }

    /**
     * Reject appraisal
     */
    public function reject(MonthlyKpiAppraisal $appraisal)
    {
        return DB::transaction(function () use ($appraisal) {
            $appraisal->update([
                'status' => 'Rejected',
            ]);

            return $appraisal->fresh();
        });
    }

    /**
     * Get appraisals by employee
     */
    public function getByEmployee($employeeId, $limit = 20)
    {
        return MonthlyKpiAppraisal::where('employee_id', $employeeId)
            ->with(['employee', 'details'])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Get appraisals by status
     */
    public function getByStatus($status, $limit = 20)
    {
        return MonthlyKpiAppraisal::where('status', $status)
            ->with(['employee'])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Get appraisals by date range
     */
    public function getByDateRange($startDate, $endDate, $limit = 20)
    {
        return MonthlyKpiAppraisal::whereBetween('assessment_month', [$startDate, $endDate])
            ->with(['employee'])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Check if appraisal exists for employee and month
     */
    public function checkDuplicate($employeeId, $assessmentMonth)
    {
        $monthDate = Carbon::parse($assessmentMonth)->startOfMonth();
        
        return MonthlyKpiAppraisal::where('employee_id', $employeeId)
            ->whereYear('assessment_month', $monthDate->year)
            ->whereMonth('assessment_month', $monthDate->month)
            ->exists();
    }

    /**
     * Calculate aggregate score
     */
    public function calculateAggregateScore(MonthlyKpiAppraisal $appraisal)
    {
        return ($appraisal->achieved_performance_score ?? 0) + 
               ($appraisal->succession_management_score ?? 0) + 
               ($appraisal->behavioral_performance_score ?? 0);
    }

    /**
     * Get performance statistics for an employee
     */
    public function getEmployeeStatistics($employeeId)
    {
        $appraisals = MonthlyKpiAppraisal::where('employee_id', $employeeId)
            ->where('status', 'Approved')
            ->get();

        if ($appraisals->isEmpty()) {
            return null;
        }

        $totalScore = 0;
        foreach ($appraisals as $appraisal) {
            $totalScore += $this->calculateAggregateScore($appraisal);
        }

        return [
            'total_appraisals' => $appraisals->count(),
            'average_score' => $totalScore / $appraisals->count(),
            'highest_score' => $appraisals->max(function($appraisal) {
                return $this->calculateAggregateScore($appraisal);
            }),
            'lowest_score' => $appraisals->min(function($appraisal) {
                return $this->calculateAggregateScore($appraisal);
            }),
        ];
    }
}