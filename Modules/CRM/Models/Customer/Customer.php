<?php

namespace Modules\CRM\Models\Customer;

use App\Models\AccessControl\Role;
use App\Models\BaseModel;
use App\Models\User;
use Modules\Account\Models\Transaction;
use Modules\HRMS\Models\Employee;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\VoucherDetail;
use Modules\CMS\Models\ApplicationEntry;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Modules\CRM\Models\Customer\BrokerCustomerAttached;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\DailyCall;
// use Modules\CRM\Models\Customer\Settings\CustomerShipping;
use Modules\Inventory\Models\IssueProduct;
// use Modules\Inventory\Models\ProductTransferRequest;
use Modules\Legal\Models\LegalEntryConvict;
use Modules\Licenses\Models\CBCLicenseRequisition;
use Modules\Licenses\Models\CbcSms;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Licenses\Models\USGOrOPGLicenseRequisition;
use Modules\Licenses\Models\UsgOrOpgSms;
// use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\Supplier;
use Modules\Sales\Models\BackupChallan;
// use Modules\Sales\Models\Quotation;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesRequisition;
use Modules\Sales\Models\ShipmentVerify;
use Modules\Services\Models\ServiceToken;
use Modules\LocationManager\Models\Area;
use Modules\Sales\Models\FakeInvoice;
use Modules\Sales\Models\SalesReturn;
// use Modules\Sales\Models\SaleReturn;
// use Modules\Services\Models\ServiceQuotation;

class Customer extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;


    protected $guarded = [];

    public $deletePrevent = [
        'salesOrders',
        // 'customerShippingAddress',
        // 'saleReturn',
        'voucherDetail',
        'brokerCustomerAttached',
        'customerSetting',
        'dailyCall',
        // 'customerShipping',
        // 'productTransferRequest',
        'cbcLicenseRequisition',
        'cbcSms',
        'dongleOrSerialEntry',
        'usgOrOpgLicenseRequisition',
        'usgOrOpgSms',
        // 'purchaseOrder',
        'requisition',
        'supplier',
        'backupChallan',
        // 'quotation',
        'salesOrder', 
        'salesRequisition',
        'shipmentVerify',
        'serviceToken',
        'applicationEntries',
        'backupChallans',
        'brokerCustomerAttacheds',
        'cbcLicenseRequisitions',
        'customerBillings',
        'customerOwners',
        // 'customerPayments',
        'customerSettings',
        // 'customerShippingNewss',
        'customers',
        'dailyCalls',
        'dongleOrSerialEntries',
        'emiEntries',
        'fakeInvoices',
        'issueProducts',
        'legalEntryConvicts',
        'requisitions',
        'salesRequisitions',
        'salesReturns',
        // 'serviceQuotations',
        'serviceTokens',
        'shipmentVerifies',
        'suppliers',
        'usgOrOpgLicenseRequisitions',
        // 'voucherDetails'
    ];
    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type');
    }

    public function customerOwner()
    {
        return $this->hasMany(CustomerOwner::class, 'customer_id');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    public function scopeActived($query)
    {
        return $query->where('status', 2);
    }

    /**
     * Get the shipping addresses associated with the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function customerShippingAddress()
    {
        return $this->hasMany(CustomerShippingNew::class, 'customer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_ref_id');
    }

    public function customerReference()
    {
        return $this->belongsTo(Customer::class, 'customer_ref_id');
    }


    public function userRef()
    {
        return $this->belongsTo(Employee::class, 'user_ref_id');
    }


    public function area()
    {
        return $this->belongsTo(Area::class, 'company_place_id');
    }

    public function account()
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    // public function saleReturn(){
    // return $this->hasMany(SaleReturn::class, 'customer_id');
    // }

    // public function voucherDetail(){
    //     return $this->hasMany(VoucherDetail::class, 'customer_id');
    // }

    public function brokerCustomerAttached()
    {
        return $this->hasMany(BrokerCustomerAttached::class, 'customer_id');
    }

    public function customerSetting()
    {
        return $this->hasMany(CustomerSetting::class, 'customer_id');
    }
    public function setting()
    {
        return $this->hasOne(CustomerSetting::class, 'customer_id');
    }

    public function dailyCall()
    {
        return $this->hasMany(DailyCall::class, 'customer_id');
    }

    // public function customerShipping(){
    // return $this->hasMany(CustomerShipping::class, 'customer_id');
    // }

    // public function productTransferRequest(){
    // return $this->hasMany(ProductTransferRequest::class, 'customer_id');
    // }

    public function cbcLicenseRequisition()
    {
        return $this->hasMany(CBCLicenseRequisition::class, 'customer_id');
    }

    public function cbcSms()
    {
        return $this->hasMany(CbcSms::class, 'customer_id');
    }

    public function dongleOrSerialEntry()
    {
        return $this->hasMany(DongleOrSerialEntry::class, 'customer_id');
    }

    public function usgOrOpgLicenseRequisition()
    {
        return $this->hasMany(USGOrOPGLicenseRequisition::class, 'customer_id');
    }

    public function usgOrOpgSms()
    {
        return $this->hasMany(UsgOrOpgSms::class, 'customer_id');
    }

    // public function purchaseOrder(){
    // return $this->hasMany(PurchaseOrder::class, 'customer_id');
    // }

    public function requisition()
    {
        return $this->hasMany(Requisition::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->hasMany(Supplier::class, 'customer_id');
    }

    public function backupChallan()
    {
        return $this->hasMany(BackupChallan::class, 'customer_id');
    }

    // public function quotation(){
    // return $this->hasMany(Quotation::class, 'customer_id');
    // }

    public function salesOrder()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    public function salesRequisition()
    {
        return $this->hasMany(SalesRequisition::class, 'customer_id');
    }

    public function shipmentVerify()
    {
        return $this->hasMany(ShipmentVerify::class, 'customer_id');
    }

    public function serviceToken()
    {
        return $this->hasMany(ServiceToken::class, 'customer_id');
    }

    public function accounts()
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function createAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1005)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name" => "Account Recivable - " . $this->company_name,
            "account_number" => '1005' . $this->id,
            "account_group_id" => 1,
            "account_control_id" => 1000,
            "account_subsidiary_id" => 1005,
            "opening_balance" => "0.00",
            "remarks" => "A customer account is created for " . $this->company_name,
            "is_deletable" => 0,
        ]);
    }

    public function getAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1005)->first() == null) {
            $this->createAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 1005)->first();
    }

    public function createAdvanceAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2003)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name" => "Advance Account - " . $this->company_name,
            "account_number" => '2003' . $this->id,
            "account_group_id" => 2,
            "account_control_id" => 2000,
            "account_subsidiary_id" => 2003,
            "opening_balance" => "0.00",
            "remarks" => "A customer advance account is created for " . $this->company_name,
            "is_deletable" => 0,
        ]);
    }




    public function getAdvanceAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2003)->first() == null) {
            $this->createAdvanceAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }

        return $this->accounts->where('account_subsidiary_id', 2003)->first();
    }



    public function createSalesDiscountAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 4006)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name" => "Sales Discounts - " . $this->company_name,
            "account_number" => '4006' . $this->id,
            'account_group_id' => 4,
            'account_control_id' => 4000,
            'account_subsidiary_id' => 4002,
            "opening_balance" => "0.00",
            "remarks" => "A Sales Discounts account is created for " . $this->company_name,
            "is_deletable" => 0,
        ]);
    }

    public function getSalesDiscountAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 4002)->first() == null) {
            $this->createSalesDiscountAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }

        return $this->accounts->where('account_subsidiary_id', 4002)->first();
    }



    public function applicationEntries()
    {
        return $this->hasMany(ApplicationEntry::class);
    }

    public function backupChallans()
    {
        return $this->hasMany(BackupChallan::class);
    }

    public function brokerCustomerAttacheds()
    {
        return $this->hasMany(BrokerCustomerAttached::class);
    }

    public function cbcLicenseRequisitions()
    {
        return $this->hasMany(CbcLicenseRequisition::class);
    }

    public function customerBillings()
    {
        return $this->hasMany(CustomerBilling::class);
    }

    public function customerOwners()
    {
        return $this->hasMany(CustomerOwner::class);
    }

    // public function customerPayments(){
    //     return $this->hasMany(CustomerPayment::class);
    // }

    public function customerSettings()
    {
        return $this->hasMany(CustomerSetting::class);
    }

    // public function customerShippingNewss(){
    // return $this->hasMany(CustomerShippingNew::class);
    // }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function dailyCalls()
    {
        return $this->hasMany(DailyCall::class);
    }

    public function dongleOrSerialEntries()
    {
        return $this->hasMany(DongleOrSerialEntry::class);
    }

    public function emiEntries()
    {
        return $this->hasMany(EMIEntry::class);
    }

    public function fakeInvoices()
    {
        return $this->hasMany(FakeInvoice::class);
    }

    public function issueProducts()
    {
        return $this->hasMany(IssueProduct::class);
    }

    public function legalEntryConvicts()
    {
        return $this->hasMany(LegalEntryConvict::class);
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }


    public function salesRequisitions()
    {
        return $this->hasMany(SalesRequisition::class);
    }

    public function salesReturns()
    {
        return $this->hasMany(SalesReturn::class);
    }

    // public function serviceQuotations(){
    // return $this->hasMany(ServiceQuotation::class);
    // }

    public function serviceTokens()
    {
        return $this->hasMany(ServiceToken::class);
    }

    public function shipmentVerifies()
    {
        return $this->hasMany(ShipmentVerify::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function usgOrOpgLicenseRequisitions()
    {
        return $this->hasMany(UsgOrOpgLicenseRequisition::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }


    // public function voucherDetails(){
    //     return $this->hasMany(VoucherDetail::class);
    // }

    /**
     * Get the user associated with the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'phone');
    }


    public function createUser()
    {
        $user = $this->user == null ? $this->user()->create([
            'name' => $this->company_name,
            'password' => "12345678",
        ]) : $this->user;
        $user->roles()->detach();
        $user->roles()->syncWithoutDetaching([Role::where('slug', 'customer')->first()->id]);
        InvalidateAuthUserCashe($user->id);
        return $user;
    }

    public function getNameAttribute()
    { 
        $area = optional($this->area)->area ?? null; 
        return "{$this->company_name} - {$area}";
    }
}
