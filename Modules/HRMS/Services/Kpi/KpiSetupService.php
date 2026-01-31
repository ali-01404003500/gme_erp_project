<?php

namespace Modules\HRMS\Services\Kpi;

use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Kpi\KpiSetup;

class KpiSetupService
{
    public function getAll(int $limit = 20)
    {
        return KpiSetup::query()->paginate($limit);
    }

    public function store(array $data)
    {
        // Calculate total weight
        $totalWeight = collect($data['kpis'])->sum('weight');

        // Create KPI Setup
        $kpiSetup = KpiSetup::create([
            'designation_id' => $data['designation_id'],
            'total_weight' => $totalWeight,
        ]);

        // Prepare KPI item details
        $details = collect($data['kpis'])->map(function ($item) {
            return [
                'description' => $item['description'] ?? null,
                'weight' => $item['weight'],
            ];
        });

        // Save all details
        $kpiSetup->details()->createMany($details->toArray());

        return $kpiSetup;
    }

    public function update(KpiSetup $kpiSetup, array $data)
    {
        DB::transaction(function () use ($kpiSetup, $data) {
            $kpiSetup->update([
                'designation_id' => $data['designation_id'],
                'total_weight' => collect($data['kpis_kpi-setups'])->sum('weight'),
            ]);

            // Delete existing details
            $kpiSetup->details()->delete();

            $details = collect($data['kpis_kpi-setups'])->map(function ($item) {
                return [
                    'description' => $item['description'] ?? null,
                    'weight' => $item['weight'],
                ];
            });

            // Save all details
            $kpiSetup->details()->createMany($details->toArray());

            return $kpiSetup;
        });
    }

    public function delete(KpiSetup $kpiSetup)
    {
        $kpiSetup->delete();
    }

    public function show($id)
    {
        return KpiSetup::findOrFail($id);
    }
}
