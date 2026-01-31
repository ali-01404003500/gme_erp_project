<?php

namespace Modules\CRM\Models\Customer\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PercentageType extends BaseModel
{
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    use HasFactory;
    protected $guarded = [];


}
