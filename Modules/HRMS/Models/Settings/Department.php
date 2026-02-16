<?php

namespace Modules\HRMS\Models\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Settings\Holiday;
class Department extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public $deletePrevent = ['departments'];

   

public function holiday() {
    return $this->belongsTo(Holiday::class, 'holiday_id'); 
}

}

      
