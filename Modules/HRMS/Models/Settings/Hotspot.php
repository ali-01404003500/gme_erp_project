<?php

namespace Modules\HRMS\Models\Settings;

use App\Models\BaseModel;
use App\Models\AccessControl\Branch;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotspot extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public $deletePrevent = [];

    /**
     * Get the branch that owns this hotspot
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Check if a coordinate is within the hotspot radius
     * Using Haversine formula to calculate distance
     *
     * @param float $latitude
     * @param float $longitude
     * @return bool
     */
    public function isWithinRadius($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return false;
        }

        $distance = $this->calculateDistance($latitude, $longitude);
        
        return $distance <= $this->radius;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in meters
     *
     * @param float $latitude
     * @param float $longitude
     * @return float Distance in meters
     */
    public function calculateDistance($latitude, $longitude)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Scope to get only active hotspots
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
