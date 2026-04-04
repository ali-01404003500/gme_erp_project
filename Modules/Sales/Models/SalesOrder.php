<?php

namespace Modules\Sales\Models;


use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\OtpVerification;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payment;
use Modules\Account\Models\Sale;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Customer;
use Modules\Services\Models\Service;

class SalesOrder extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;

    protected $guarded = [];

    protected $casts = [
        'is_offer' => 'boolean',
    ];

    private $source_types =[
        Quotation::class=>'Quotation',
        BackupChallan::class=>'Challan'

    ];

    public function salesOrderDetails(){
        return $this->hasMany(SalesOrderDetails::class, 'sales_order_id');
    }

    public function details(){
        return $this->salesOrderDetails();
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function salesOrderDeliveries(){
        return $this->hasMany(SalesOrderDelivery::class, 'sales_order_id');
    }

    public function source(){
        return $this->morphTo('source', 'source_type', 'source_id');
    }
    public function getSourceNameAttribute(){
        return $this->source_types[$this->source_type]??$this->source_type;
    }

    public function reference(){
        return $this->belongsTo(SalesOrder::class, 'reference_id');  
    }

    public function delivery(){
        return $this->morphOne(Delivery::class, 'source', 'source_type', 'source_id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    // public function shipment(){
    //     return $this->hasOne(SalesOrderShipment::class, 'sales_order_id');
    // }

    
    public function shipment(){
        return $this->morphOne(ShipmentConditionInfo::class, 'for', 'for_type', 'for_id');
    }

    public function service(){
        return $this->belongsTo(Service::class, 'service_id');
    }


    public function otpVerifications(){
        return $this->morphMany(OtpVerification::class, 'sourceable');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    /**
     * The account transactions that belong to the SalesOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
     public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }
    public function signature(){
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }

    /**
     * Calculate the total amount of payments for this sales order
     *
     * @return float
     */
    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    /**
     * Calculate the due amount for this sales order (net amount minus paid amount)
     *
     * @return float
     */
    public function getDueAmountAttribute(): float
    {
        return max(0, (float) ($this->net_amount - $this->paid_amount));
    }

    /**
     * Polymorphic many-to-many relationship with offers
     */
    public function offers()
    {
        return $this->morphToMany(
            related: \Modules\Inventory\Models\Offer::class,
            name: 'offerable',
            table: 'offerables',
            foreignPivotKey: 'offerable_id',
            relatedPivotKey: 'offer_id'
        );
    }


    public function getAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1005)->first() == null) {
            $this->createAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 1005)->first();
    }

}
