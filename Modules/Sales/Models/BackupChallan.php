<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BackupChallan extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    protected $guarded = [];

    public $deletePrevent = ['customers','backupChallanDetails','backupChallanShipments'];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function backupChallanDetails()
    {
        return $this->hasMany(BackupChallanDetail::class);
    }

    /**
     * Get the BackupChallanDetails associated with the BackupChallan
     *
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(BackupChallanDetail::class, 'backup_challan_id');
    }
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function backupChallanShipments()
    {
        return $this->hasMany(BackupChallanShipment::class);
    }
    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {
            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
             });
        }

    }

    public function shipment(){
        return $this->morphOne(ShipmentConditionInfo::class, 'for', 'for_type', 'for_id');
    }

}
