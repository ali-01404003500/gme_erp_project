<?php

namespace Modules\Services\Models\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceToken;

class ServiceType extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public $deletePrevent = ['serviceTokens'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceTokens()
    {
        return $this->hasMany(ServiceToken::class, 'service_type', 'name');
    }
}
