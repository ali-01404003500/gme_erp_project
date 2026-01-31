<?php

namespace Modules\CRM\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GeoLocation;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;

class Broker extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public function customerAttached()
    {
        return $this->hasMany(BrokerCustomerAttached::class);
    }

    public function brokerBank()
    {
        return $this->hasMany(BrokerBank::class);
    }

    public function brokerCommission()
    {
        return $this->hasMany(BrokerCommission::class);
    }

    public function division()
    {
        return $this->belongsTo(GeoLocation::class, 'division_id');
    }

    public function district()
    {
        return $this->belongsTo(GeoLocation::class, 'district_id');
    }

    public function thana()
    {
        return $this->belongsTo(GeoLocation::class, 'thana_id');
    }

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class, 'customer_id');
    // }

    /**
     * Morph one relationship with Account model
     *
     * The account related to the customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function accounts()
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function createAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2001)->first() != null) {
            return;
        }
        $this->accounts()->create([
            'name' => 'Commission Payable - ' . $this->broker_name,
            'account_number' => '2001' . $this->id,
            'account_group_id' => 2,
            'account_control_id' => 2000,
            'account_subsidiary_id' => 2001,
            'opening_balance' => '0.00',
            'remarks' => 'A Broker account is created for ' . $this->broker_name,
            'is_deletable' => 0,
        ]);
    }

    /**
     * Return the account related to the supplier
     *
     * This method will create the account if it does not exist
     *
     * @return Account
     */
    public function getAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2001)->first() == null) {
            $this->createAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 2001)->first();
    }

    public function createExpenseAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 5023)->first() != null) {
            return;
        }
        $this->accounts()->create([
            'name' => 'Commission Expenses - ' . $this->broker_name,
            'account_number' => '5023' . $this->id,
            'account_group_id' => 5,
            'account_control_id' => 5000,
            'account_subsidiary_id' => 5023,
            'opening_balance' => '0.00',
            'remarks' => 'A Broker account is created for ' . $this->broker_name,
            'is_deletable' => 0,
        ]);
    }

    public function getExpenseAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 5023)->first() == null) {
            $this->createExpenseAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 5023)->first();
    }

    public function createAdvanceAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1006)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name" => "Advance - " . $this->broker_name,
            "account_number" => '1006' . $this->id,
            "account_group_id" => 1,
            "account_control_id" => 1000,
            "account_subsidiary_id" => 1006,
            "opening_balance" => "0.00",
            "remarks" => "A broker account is created for " . $this->broker_name,
            "is_deletable" => 0,
        ]);
    }

    public function getAdvanceAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1006)->first() == null) {
            $this->createAdvanceAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 1006)->first();
    }
}
