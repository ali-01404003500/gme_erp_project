<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class ServicePendingToken extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function serviceMyTask()
    {
        return $this->belongsTo(ServiceMyTask::class, 'service_my_task_id');
    }
    public function serviceToken()
    {
        return $this->belongsTo(ServiceToken::class, 'service_token_id');
    }
}
