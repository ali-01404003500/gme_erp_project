<?php


namespace Modules\Account\Models;


use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountSubsidiary extends Model
{
    use AutoCreateUpdateAndHistory,SoftDeletes;

    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function accountControl(): BelongsTo
    {
        return $this->belongsTo(AccountControl::class, 'account_control_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
