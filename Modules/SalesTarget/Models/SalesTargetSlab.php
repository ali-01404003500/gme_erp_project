<?php
namespace Modules\SalesTarget\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTargetSlab extends Model
{
    protected $fillable = ['name', 'min_salary', 'max_salary', 'target_multiplier', 'is_active'];

    public function calculateTargetFor(float $salary): float
    {
        return round($salary * $this->target_multiplier, 2);
    }
}