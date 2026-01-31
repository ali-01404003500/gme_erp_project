<?php

namespace Modules\LocationManager\Services;

use Modules\LocationManager\Models\Area;
use App\Models\GeoLocation;
use Illuminate\Support\Str;

class AreaService
{
    
    public function getAll(int $limit = 20) {
        return Area::query()->withoutGlobalScope('latest')
        ->likeSearch('area')
        ->when(request('thana_id'), function ($query) {
            $query->where('thana_id', request('thana_id'));
        })
        ->paginate($limit);
    }
    
    public function store($data)
    {
        return Area::create([
            "division_id" => $data["division"] ?? $data["division_id"],
            "district_id"=> $data["district"] ?? $data["district_id"],
            "thana_id"=> $data["thana"] ?? $data["thana_id"],
            "area"=> $data["area"],
            "union_id"=> $data["union"] ?? $data["union_id"] ?? null,
            "village_id"=> $data["village"] ?? $data["village_id"] ?? null,
            "post_code"=> $data["post_code"] ?? null,
            "street"=> $data["street"] ?? null,
            "lat"=> $data["lat"] ?? null,
            "lon"=> $data["lon"] ?? null,
        ]);
    }

    public function update(Area $area, $data)
    {
        $area->update([
            "division_id" => $data["division"],
            "district_id"=> $data["district"],
            "thana_id"=> $data["thana"],
            "area"=> $data["area"],
            "union_id"=> $data["union"],
            "village_id"=> $data["village"],
            "post_code"=> $data["post_code"],
            "street"=> $data["street"],
            "lat"=> $data["lat"],
            "lon"=> $data["lon"],
        ]);
        return $area;
    }

    public function delete(Area $area)
    {
        $area->delete();
    }

    public function show($id)
    {
        return Area::findOrFail($id);
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        // Map Division name to ID
        $division = GeoLocation::where('name', $jsonData['division_name'])
            ->where('type', 'division')
            ->first();
        if (!$division) {
            throw new \Exception("Division not found: {$jsonData['division_name']}");
        }

        // Map District name to ID
        $district = GeoLocation::where('name', $jsonData['district_name'])
            ->where('type', 'district')
            ->where('parent_id', $division->id)
            ->first();
        if (!$district) {
            throw new \Exception("District not found: {$jsonData['district_name']} under Division: {$jsonData['division_name']}");
        }

        // Map Thana name to ID
        $thana = GeoLocation::where('name', $jsonData['thana_name'])
            ->where('type', 'thana')
            ->where('parent_id', $district->id)
            ->first();
        if (!$thana) {
            throw new \Exception("Thana not found: {$jsonData['thana_name']} under District: {$jsonData['district_name']}");
        }

        // Optional: Map Union name to ID
        $unionId = null;
        if (!empty($jsonData['union_name'])) {
            $union = GeoLocation::where('name', $jsonData['union_name'])
                ->where('type', 'union')
                ->where('parent_id', $thana->id)
                ->first();
            if ($union) {
                $unionId = $union->id;
            }
        }

        // Optional: Map Village name to ID
        $villageId = null;
        if (!empty($jsonData['village_name'])) {
            $village = GeoLocation::where('name', $jsonData['village_name'])
                ->where('type', 'village')
                ->first();
            if ($village) {
                $villageId = $village->id;
            }
        }

        return [
            'division_id' => $division->id,
            'district_id' => $district->id,
            'thana_id' => $thana->id,
            'area' => $jsonData['area'],
            'union_id' => $unionId,
            'village_id' => $villageId,
            'post_code' => $jsonData['post_code'] ?? null,
            'street' => $jsonData['street'] ?? null,
            'lat' => $jsonData['lat'] ?? null,
            'lon' => $jsonData['lon'] ?? null,
        ];
    }

    /**
     * Store data from JSON file
     */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

        // Ensure directory exists
        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        // Create file if it doesn't exist
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'JSON file is empty.');
        }

        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $this->store($mappedData);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Areas import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }
}