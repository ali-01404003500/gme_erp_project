<?php


namespace Modules\Account\Models;


use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountControl extends Model
{
    use AutoCreateUpdateAndHistory, SoftDeletes;

    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function accountSubsidiaries()
    {
        return $this->hasMany(AccountSubsidiary::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
