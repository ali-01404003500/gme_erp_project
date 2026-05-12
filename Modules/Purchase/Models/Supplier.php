<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\Product\Settings\Brand;
use App\Traits\AutoCreateUpdateAndHistory;
use App\Traits\AutoHistory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;
use Modules\Account\Models\InvoiceWisePayment;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Account\Models\Purchase;
use Modules\CRM\Models\Customer\Customer;

class Supplier extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    public function brand(){
        return $this->hasMany(Brand::class);
    }
    public function brands(){
        return $this->hasMany(Brand::class);
    }

    public function purchase(){
        return $this->hasMany(Purchase::class, 'supplier_id', 'id');
    }

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
        if ($this->accounts->where('account_subsidiary_id', 2001)->first() == null) {
            $this->createAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 2001)->first();
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

    
    public function purchaseOrders(){
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    public function purchaseRequisitions(){
        return $this->hasMany(Requisition::class, 'supplier_id');
    }

    public function receives()
    {
        // return $this->hasMany(PurchaseOrderReceive::class, 'supplier_id');
    }

    public function supplierPayments(){
        // return $this->hasMany(SupplierPayment::class, 'supplier_id');
    }

    public function totalInvoiceAmount(): Attribute{
        return Attribute::make(
            get: fn () => $this->receives->sum('net_landed_cost'),
        );
    }

    public function advanceBalance(): Attribute{
        return Attribute::make(
            get: fn () => $this->getAdvanceAccount()->transaction_items->sum('amount'),
        );
    }

    public function requisitions(){
        return $this->hasMany(Requisition::class, 'supplier_id');
    }

    public function invoiceWisePayments()
{
    return $this->morphMany(InvoiceWisePayment::class, 'payment_to');
}

/**
 * Get payment details for this supplier
 */
public function paymentDetails()
{
    return $this->morphMany(MakePaymentDetail::class, 'paymentable');
}



}
