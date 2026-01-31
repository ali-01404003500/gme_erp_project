<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use App\Traits\AutoHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;
use Modules\Account\Models\InvoiceWisePayment;
use Modules\Account\Models\Payments\MakePaymentDetail;

class Vendor extends BaseModel
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
        if($this->accounts->where('account_subsidiary_id', 2001)->first() != null){
            return;
        }
        $this->accounts()->create([
            "name"=> "Accounts Payable - ".  $this->company_name,
            "account_number"=> '2001'.$this->id,
            "account_group_id"=> 2,
            "account_control_id"=> 2000,
            "account_subsidiary_id"=> 2001,
            "opening_balance"=> "0.00",
            "remarks"=> "A supplier account is created for ".$this->company_name,
            "is_deletable"=> 0,
        ]);
    }

    /**
     * Return the account related to the supplier
     * 
     * This method will create the account if it does not exist
     * 
     * @return Account
     */
    public function getAccount(){
        // if ($this->accounts->where('account_subsidiary_id', 2001)->first() == null) {
        //     $this->createAccount();
        //     $this->load('accounts'); // Reload relationship to reflect new creation
        // }
        // return $this->accounts->where('account_subsidiary_id', 2001)->first();

         if ($this->accounts->where('account_subsidiary_id', 2007)->first() == null) {
            $this->createExpensePayableAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 2007)->first();
    }


    public function createAdvanceAccount(){
        if($this->accounts->where('account_subsidiary_id', 1006)->first() != null){
            return;
        }
        $this->accounts()->create([
            "name"=> "Advance - ". $this->company_name,
            "account_number"=> '1006'.$this->id,
            "account_group_id"=> 1,
            "account_control_id"=> 1000,
            "account_subsidiary_id"=> 1006,
            "opening_balance"=> "0.00",
            "remarks"=> "A supplier account is created for ".$this->company_name,
            "is_deletable"=> 0,
        ]);
    }

    public function getAdvanceAccount(){
        if ($this->accounts->where('account_subsidiary_id', 1006)->first() == null) {
            $this->createAdvanceAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 1006)->first();
    }


    
    public function createExpenseAccount(){
        if($this->accounts->where('account_subsidiary_id', 5002)->first() != null){
            return;
        }
        $this->accounts()->create([
            "name"=> "Expenses - ". $this->company_name,
            "account_number"=> '5002'.$this->id,
            "account_group_id"=> 5,
            "account_control_id"=> 5000,
            "account_subsidiary_id"=> 5002,
            "opening_balance"=> "0.00",
            "remarks"=> "A supplier account is created for ".$this->company_name,
            "is_deletable"=> 0,
        ]);
    }

    public function getExpenseAccount(){
        if ($this->accounts->where('account_subsidiary_id', 5002)->first() == null) {
            $this->createExpenseAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 5002)->first();
    }
    
    /**
     * @return void
     */
    public function createExpensePayableAccount(){
        if($this->accounts->where('account_subsidiary_id', 2007)->first() != null){
            return;
        }
        $this->accounts()->create([
            "name"=> "Expenses Payable - ". $this->company_name,
            "account_number"=> '2007'.$this->id,
            "account_group_id"=> 2,
            "account_control_id"=> 2000,
            "account_subsidiary_id"=> 2007,
            "opening_balance"=> "0.00",
            "remarks"=> "A supplier account is created for ".$this->company_name,
            "is_deletable"=> 0,
        ]);
    }

    public function getExpensePayableAccount(){
        if ($this->accounts->where('account_subsidiary_id', 2007)->first() == null) {
            $this->createExpensePayableAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 2007)->first();
    }

    public function officePurchases(){
        return $this->hasMany(OfficePurchase::class);
    }

        public function invoiceWisePayments()
    {
        return $this->morphMany(InvoiceWisePayment::class, 'payment_to');
    }

    /**
     * Get payment details for this vendor
     */
    public function paymentDetails()
    {
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }



}

