<?php


namespace Modules\Account\Models;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
   use AutoCreateUpdateAndHistory, SoftDeletes;
}
