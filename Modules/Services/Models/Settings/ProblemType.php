<?php

namespace Modules\Services\Models\Settings;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProblemType extends BaseModel
{
     use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    
    protected $guarded = [];
}
