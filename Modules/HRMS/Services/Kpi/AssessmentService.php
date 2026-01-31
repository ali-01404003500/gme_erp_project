<?php

namespace Modules\HRMS\Services\Kpi;

use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Kpi\Assessment;

class AssessmentService
{
    public function getAll(int $limit = 20)
    {
        return Assessment::query()
        ->searchByFields([
            'employee_id' => 'employee_id',
        ])
        ->paginate($limit);
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            // Save parent assessment
            $assessment = Assessment::create([
                'employee_id' => $data['employee_id'],
                'from_date' => explode(' to ', $data['from_to_date'])[0],
                'to_date' => explode(' to ', $data['from_to_date'])[1],
                'total_mark' => $data['total_mark'],
                'total_weight' => $data['total_weight'],
                'overall_score' => $data['overall_score'],
                'status' => $data['status'],
            ]);

            // Save KPI details
            foreach ($data['kpis'] as $kpi) {
                $assessment->details()->create([
                    'kpi_setup_detail_id' => $kpi['id'],
                    'description' => $kpi['description'] ?? null,
                    'weight' => $kpi['weight'],
                    'mark' => $kpi['mark'],
                    'remarks' => $kpi['remarks'] ?? null,
                ]);
            }

            DB::commit();
            return $assessment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Assessment $assessment, array $data)
    {
        DB::beginTransaction();

        try {
            $from_date = explode(' to ', $data['from_to_date'])[0] ?? null;
            $to_date = explode(' to ', $data['from_to_date'])[1] ?? null;
            // Update assessment base info
            $assessment->update([
                'employee_id' => $data['employee_id'],
                'from_date' => $from_date ,
                'to_date' => $to_date ,
                'total_mark' => $data['total_mark'],
                'total_weight' => $data['total_weight'],
                'overall_score' => $data['overall_score'],
                'status' => $data['status'],
            ]);

            // Delete old KPI entries (if you're using one-to-many relationship)
            $assessment->details()->delete();

            // Re-create KPI entries
            foreach ($data['kpis'] as $kpi) {
                // dd($kpi);
                $assessment->details()->create([
                    'kpi_setup_detail_id' => $kpi['id'],
                    'description' => $kpi['description'] ?? null,
                    'weight' => $kpi['weight'],
                    'mark' => $kpi['mark'],
                    'remarks' => $kpi['remarks'] ?? null,
                ]);
            }
            DB::commit();
            return $assessment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function delete(Assessment $assessment)
    {
        $assessment->delete();
    }

    public function show($id)
    {
        return Assessment::findOrFail($id);
    }
}
