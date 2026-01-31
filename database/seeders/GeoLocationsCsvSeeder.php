<?php

namespace Database\Seeders;

use App\Models\GeoLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeoLocationsCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = database_path('seeders/geo-locations.csv');

        $csvData = array_map('str_getcsv', file($csvFile));
        $headers = array_shift($csvData);
        // dd($headers);
        foreach ($csvData as $row) {
            $data = array_combine($headers, $row);

            // Find or create Division
            $division = $this->findOrCreateLocation('Division', $data['Division'], null);

            // Find or create District
            $district = $this->findOrCreateLocation('District', $data['District'], $division->id);

            // Find or create Thana
            $thana = $this->findOrCreateLocation('Thana', $data['Thana'], $district->id);

            // Find or create Union
            $union = $this->findOrCreateLocation('Union', $data['Union'], $thana->id);

            // Find or create Village
            $village = $this->findOrCreateLocation('Village', $data['Village'], $union->id);
        }
    }

    /**
     * Find or create a location.
     *
     * @param string $type
     * @param string $name
     * @param int|null $parentId
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function findOrCreateLocation($type, $name, $parentId)
    {
        return GeoLocation::updateOrCreate(
            ['type' => $type, 'name' => trim($name)],
            ['parent_id' => $parentId]
        );
    }
}
