<?php

namespace App\Models\AccessControl;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsTemplate extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public function serviceNames(){
        return $this->belongsTo(ServiceName::class,'service_name_id');
    }

    public function triggerNames(){
        return $this->belongsTo(TriggerName::class,'trigger_name_id');
    }
}
