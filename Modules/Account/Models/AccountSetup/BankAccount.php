<?php

namespace Modules\Account\Models\AccountSetup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;

class BankAccount extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    protected $guarded = [];

     /**
     * Morph one relationship with Account model
     * 
     * The account related to the customer
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function accounts() {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function createAccount(){
        // Try to get the account first
        if($this->accounts()->where('account_subsidiary_id', $this->getSubsidiaryIdByPaymentMode())->first() != null){
            return;
        }
        $this->accounts()->create([
            "name"=> $this->account_name,
            "account_number"=> '1002'.$this->id,
            "account_group_id"=> 1,
            "account_control_id"=> 1000,
            "account_subsidiary_id"=> $this->getSubsidiaryIdByPaymentMode(),
            "opening_balance"=> "0.00",
            "remarks"=> "A Bank account is created for ".$this->account_name,
            "is_deletable"=> 0,
        ]);
        
    }

    public function getAccount(){
        $account = $this->accounts()->where('account_subsidiary_id', $this->getSubsidiaryIdByPaymentMode())->first();
        if ($account) {
            return $account;
        }
        $this->createAccount(); 
        return $this->accounts()->where('account_subsidiary_id', $this->getSubsidiaryIdByPaymentMode())->first();
    }

    private function getSubsidiaryIdByPaymentMode() {
        switch ($this->payment_mode) {
            case 'Cash':
                return 1001;
                
            case 'Bank':
            case 'Online Deposit':
                return 1002;

            case 'bKash':
            case 'Nagad':
            case 'Rocket':
                return 1019;
            
            case 'Card Payment':
                return 1020;
            
            default:
                return 1002;
        }
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function bankBranch()
    {
        return $this->belongsTo(BankBranch::class);
    }
}
