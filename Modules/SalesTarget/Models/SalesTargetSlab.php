<?php
namespace Modules\SalesTarget\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTargetSlab extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $fillable = ['name', 'min_salary', 'max_salary', 'target_multiplier', 'is_active', 'created_by', 'updated_by', 'deleted_by' ];

    public function calculateTargetFor(float $salary): float
    {
        return round($salary * $this->target_multiplier, 2);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}