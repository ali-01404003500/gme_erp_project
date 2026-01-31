<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyVisitPlan extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    
    protected $guarded = [];

    protected $casts = [
        'attachment' => 'array',
    ];
}
