<?php

namespace Modules\Account\Models;

use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountOpeningBalance extends Model
{
    use AutoCreateUpdateAndHistory, SoftDeletes;

    
}
