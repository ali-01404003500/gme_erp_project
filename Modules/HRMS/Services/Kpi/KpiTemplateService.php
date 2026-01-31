<?php

namespace Modules\HRMS\Services\Kpi;

use Modules\HRMS\Models\Kpi\KpiTemplate;
use Modules\HRMS\Models\Kpi\ResponsibilityEntry;

class KpiTemplateService
{
    public function getAll(int $limit = 20)
    {
        return KpiTemplate::query()->paginate($limit);
    }

    public function store(array $data)
    {
        // Create the main KPI Template
        $kpiTemplate = KpiTemplate::create([
            'department_id' => $data['department_id'],
            'designation_id' => $data['designation_id'],
            'status' => $data['status'],
        ]);

        // Insert related responsibilities
        foreach ($data['responsibilities'] as $responsibility) {
            $kpiTemplate->responsibilities()->create([
                'responsibility_entriy_id' => $responsibility['id'],
                'weight' => $responsibility['weight'],
                'time' => $responsibility['time'],
                'frequency' => $responsibility['frequency'],
            ]);
        }

        return $kpiTemplate;
    }

    public function update(KpiTemplate $kpiTemplate, array $data)
    {
        // Update main KPI Template info
        $kpiTemplate->update([
            'department_id' => $data['department_id'],
            'designation_id' => $data['designation_id'],
            'status' => $data['status'],
        ]);

        // Get existing responsibilities
        $existingResponsibilityIds = $kpiTemplate->responsibilities()->pluck('responsibility_entriy_id')->toArray();

        $newResponsibilityIds = array_keys($data['responsibilities']);

        // Delete removed responsibilities
        $toDelete = array_diff($existingResponsibilityIds, $newResponsibilityIds);
        if (!empty($toDelete)) {
            $kpiTemplate->responsibilities()->whereIn('responsibility_entriy_id', $toDelete)->delete();
        }

        // Insert or update current responsibilities
        foreach ($data['responsibilities'] as $res) {
            $kpiTemplate->responsibilities()->updateOrCreate(
                ['responsibility_entriy_id' => $res['id']],
                [
                    'weight' => $res['weight'],
                    'time' => $res['time'],
                    'frequency' => $res['frequency'],
                ],
            );
        }

        return $kpiTemplate;
    }

    public function delete(KpiTemplate $kpiTemplate)
    {
        $kpiTemplate->delete();
    }

    public function show($id)
    {
        return KpiTemplate::findOrFail($id);
    }
}
