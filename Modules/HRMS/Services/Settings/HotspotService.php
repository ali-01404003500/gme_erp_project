<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\Hotspot;

class HotspotService
{
    /**
     * Get all hotspots with pagination
     *
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(int $limit = 20)
    {
        return Hotspot::with('branch')
            ->paginate($limit);
    }

    /**
     * Get hotspot by ID
     *
     * @param int $id
     * @return Hotspot
     */
    public function show($id)
    {
        return Hotspot::with('branch')->findOrFail($id);
    }

    /**
     * Store a new hotspot
     *
     * @param array $data
     * @return Hotspot
     */
    public function store(array $data)
    {
        return Hotspot::create($data);
    }

    /**
     * Update an existing hotspot
     *
     * @param Hotspot $hotspot
     * @param array $data
     * @return Hotspot
     */
    public function update(Hotspot $hotspot, array $data)
    {
        $hotspot->update($data);
        return $hotspot->fresh();
    }

    /**
     * Delete a hotspot
     *
     * @param Hotspot $hotspot
     * @return bool
     * @throws \Exception
     */
    public function delete(Hotspot $hotspot)
    {
        if (!empty($hotspot->deletePrevent)) {
            foreach ($hotspot->deletePrevent as $relation) {
                if ($hotspot->$relation()->exists()) {
                    throw new \Exception("Cannot delete hotspot as it has related {$relation}");
                }
            }
        }
        
        return $hotspot->delete();
    }

    /**
     * Get all active hotspots
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveHotspots()
    {
        return Hotspot::active()->with('branch')->get();
    }

    /**
     * Get hotspot by branch ID
     *
     * @param int $branchId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByBranch(int $branchId)
    {
        return Hotspot::where('branch_id', $branchId)
            ->with('branch')
            ->paginate(20);
    }

    /**
     * Check if coordinates are within any active hotspot
     *
     * @param float $latitude
     * @param float $longitude
     * @return Hotspot|null
     */
    public function findValidHotspot($latitude, $longitude)
    {
        $hotspots = $this->getActiveHotspots();
        
        foreach ($hotspots as $hotspot) {
            if ($hotspot->isWithinRadius($latitude, $longitude)) {
                return $hotspot;
            }
        }
        
        return null;
    }
}
