<?php

namespace Modules\SalesTarget\Services;

use Illuminate\Support\Facades\DB;

class IncentiveSettingsService
{
    /**
     * Store logic for new records with updated table names.
     */
    public static function storeIncentiveSettings(array $data)
    {
        return DB::transaction(function () use ($data) {
            $id = DB::table('sales_incentives_settings')->insertGetId([
                'title'      => $data['title'],
                'year'       => $data['year'],
                'status'     => $data['status'] ?? 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($data['slabs'])) {
                $slabs = self::formatSlabs($id, $data['slabs']);
                DB::table('sales_incentive_slabs_settings')->insert($slabs);
            }
            return $id;
        });
    }

    /**
     * Update logic with updated table names.
     */
    public static function updateIncentiveSettings($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            DB::table('sales_incentives_settings')->where('id', $id)->update([
                'title'      => $data['title'],
                'year'       => $data['year'],
                'status'     => $data['status'],
                'updated_at' => now(),
            ]);

            // Sync Slabs: Remove old and add new
            DB::table('sales_incentive_slabs_settings')
                ->where('sales_incentive_id', $id)
                ->delete();

            if (!empty($data['slabs'])) {
                $slabs = self::formatSlabs($id, $data['slabs']);
                DB::table('sales_incentive_slabs_settings')->insert($slabs);
            }
        });
    }

    /**
     * Formats slabs for bulk insertion into the settings table.
     */
    private static function formatSlabs($id, $slabsData)
    {
        return array_map(function ($slab) use ($id) {
            return [
                'sales_incentive_id' => $id,
                'min_range'          => $slab['min'],
                'max_range'          => $slab['max'],
                'incentive_type'     => $slab['type'],
                'incentive_rate'     => $slab['rate'] ?? 0,
                'notes'              => $slab['notes'] ?? null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }, $slabsData);
    }
}
