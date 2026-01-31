<?php

namespace Modules\Inventory\Models\Settings;

use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approver extends Model
{
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    use HasFactory;
}
