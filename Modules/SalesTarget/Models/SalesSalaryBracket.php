<?php
namespace Modules\SalesTarget\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesSalaryBracket extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
 

    protected $fillable = ['min_percent', 'max_percent', 'payout_type', 'payout_percent', 'is_active', 'created_by', 'updated_by', 'deleted_by' ];

    // এই bracket অনুযায়ী actual payout % বের করার logic এখানেই রাখলাম
    public function resolvePayoutPercent(float $achievementPercent): float
    {
        if ($this->payout_type === 'equal_to_achievement') {
            return $achievementPercent; // achievement যা, payout তাই
        }

        return $this->payout_percent ?? 0; // fixed value
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
