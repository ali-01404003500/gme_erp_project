<?php
namespace Modules\SalesTarget\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTargetRateTier extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $fillable = ['min_percent', 'max_percent', 'rate_percent', 'is_active',  'created_by', 'updated_by', 'deleted_by' ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
