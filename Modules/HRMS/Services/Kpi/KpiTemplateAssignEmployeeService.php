<?php

namespace Modules\HRMS\Services\Kpi;

use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Kpi\KpiTemplate;
use Modules\HRMS\Models\Kpi\KpiTemplateAssignEmployee;
use Modules\HRMS\Models\Kpi\KpiTemplateAssignEmployeeDetail;

class KpiTemplateAssignEmployeeService
{
    
    public function getAll(int $limit = 20) {
        return KpiTemplateAssignEmployee::query()->paginate($limit);
    }

    public function getEmployeeAndTemplate($employeeId)
    {
        $employee = Employee::with([
            'employementDetail.department',
            'employementDetail.designation',
            'employementDetail.supervisorName'
        ])->findOrFail($employeeId);

        $kpiTemplate = KpiTemplate::where('department_id', $employee->employementDetail->department_id)
            ->where('designation_id', $employee->employementDetail->designation_id)
            ->where('status', 'Active')
            ->with('responsibilities.responsibilityEntry')
            ->first();

        return response()->json([
            'employee' => [
                'department_name' => $employee->employementDetail->department->name ?? 'N/A',
                'designation_name' => $employee->employementDetail->designation->name ?? 'N/A',
                'supervisor_name' => $employee->employementDetail->supervisorName->full_name ?? 'N/A',
                'joining_date' => $employee->employementDetail->date_of_joining,
            ],
            'kpi_template' => $kpiTemplate ? [
                'id' => $kpiTemplate->id,
                'responsibilities' => $kpiTemplate->responsibilities->map(function ($r) {
                    return [
                        'responsibility_entry_id' => $r->responsibility_entriy_id,
                        'description' => $r->responsibilityEntry->description ?? '',
                        'weight' => $r->weight,
                        'time' => $r->time,
                        'frequency' => $r->frequency,
                    ];
                }),
            ] : null,
        ]);
    }

    
    public function checkDuplicate($data)
    {
        return KpiTemplateAssignEmployee::where('employee_id', $data['employee_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                      ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
            })
            ->exists();
    }

    /**
     * Store KPI Assignment with responsibilities
     */
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $assignment = KpiTemplateAssignEmployee::create([
                'employee_id' => $data['employee_id'],
                'kpi_template_id' => $data['kpi_template_id'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'preparation_date' => $data['preparation_date'],
            ]);

            $responsibilityIds = [];
            foreach ($data['responsibilities'] as $resp) {
                // Skip duplicate responsibilities in same submission
                if (in_array($resp['responsibility_entry_id'], $responsibilityIds)) {
                    continue;
                }

                KpiTemplateAssignEmployeeDetail::create([
                    'kpi_template_assign_employee_id' => $assignment->id,
                    'responsibility_entry_id' => $resp['responsibility_entry_id'],
                    'weight' => $resp['weight'],
                    'time' => $resp['time'],
                    'frequency' => $resp['frequency'],
                ]);

                $responsibilityIds[] = $resp['responsibility_entry_id'];
            }

            return $assignment;
        });
    }

    public function update(KpiTemplateAssignEmployee $kpiTemplateAssignEmployee, array $data)
    {
        DB::transaction(function () use ($kpiTemplateAssignEmployee, $data) {
            $kpiTemplateAssignEmployee->update([
                'employee_id' => $data['employee_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'preparation_date' => $data['preparation_date'],
            ]);

            $kpiTemplateAssignEmployee->details()->delete();

            foreach ($data['responsibilities'] as $r) {
                $kpiTemplateAssignEmployee->details()->create([
                    'responsibility_entry_id' => $r['responsibility_entry_id'],
                    'weight' => $r['weight'],
                    'time' => $r['time'],
                    'frequency' => $r['frequency'],
                ]);
            }
        });
        return $kpiTemplateAssignEmployee;
    }

    public function delete(KpiTemplateAssignEmployee $kpiTemplateAssignEmployee)
    {
        $kpiTemplateAssignEmployee->delete();
    }

    public function show($id)
    {
        return KpiTemplateAssignEmployee::findOrFail($id);
    }
}
