<?php

namespace Modules\SalesTarget\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AchievementBasedSalaryPolicy extends BaseModel
{
    use HasFactory,SoftDeletes,AutoCreateUpdateAndHistory;
    
    protected $guarded = [];
}
