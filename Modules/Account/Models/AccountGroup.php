<?php


namespace Modules\Account\Models;

use App\Models\AccessControl\Branch;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroup extends Model
{
    use HasFactory, SoftDeletes, AutoCreateUpdateAndHistory;
    protected $guarded = [];
    public function accountControls()
    {
        return $this->hasMany(AccountControl::class);
    }


    public function accountControl()
    {
        return $this->hasOne(AccountControl::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'account_group_id', 'id');
    }
}
