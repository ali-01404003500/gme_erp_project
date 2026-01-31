<?php


namespace Modules\Account\Models;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use AutoCreateUpdateAndHistory,  SoftDeletes;

    protected $appends = ['balance'];


    public function accountWithGroup(): Attribute{
        return Attribute::make(
            get: fn () => $this->name.' ( '.@$this->accountGroup->name .' )',
        );
    }
    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function accountControl(): BelongsTo
    {
        return $this->belongsTo(AccountControl::class, 'account_control_id');
    }

    public function accountSubsidiary(): BelongsTo
    {
        return $this->belongsTo(AccountSubsidiary::class, 'account_subsidiary_id');
    }

    public function opening_balances()
    {
        return $this->hasMany(AccountOpeningBalance::class, 'account_id', 'id');
    }

    public function transaction_items()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    

    public function transactions()
    {
        // return $this->morphMany(Transaction::class, 'transactionable');
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    public function getBalanceAttribute()
    {
        // dd($this->transactions());
        return $this->transactions()->sum('amount');
    }


    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->company_id);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public function scopeCurrentBalance($query)
    {
        $query->where('account_group_id', 1);
    }


    public function scopeAsset($query)
    {
        $query->where('account_group_id', 1);
    }


    public function scopeLiabilities($query)
    {
        $query->where('account_group_id', 2);
    }


    public function scopeCurrentAsset($query)
    {
        $query->where('account_control_id', 1000);
    }


    public function scopeFixedAsset($query)
    {
        $query->where('account_control_id', 2);
    }

    public function accountable() {
        return $this->morphTo();
    }
}
