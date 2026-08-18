<?php
namespace Modules\SalesTarget\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTargetRateTier extends Model
{
    protected $fillable = ['min_percent', 'max_percent', 'rate_percent', 'is_active'];
}
