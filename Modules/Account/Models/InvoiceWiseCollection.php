<?php

namespace Modules\Account\Models;

use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Modules\Sales\Models\SalesOrder;

class InvoiceWiseCollection extends BaseModel
{
    use HasFactory, SoftDeletes, AutoCreateUpdateAndHistory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get all of the payments for the invoice-wise collection.
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    /**
     * Get all of the transactions for the invoice-wise collection.
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Get all of the sales orders for the invoice-wise collection.
     */
    public function salesOrders()
    {
        return $this->belongsToMany(SalesOrder::class, 'invoice_wise_collection_sales_order', 'invoice_wise_collection_id', 'sales_order_id')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function signature()
    {
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}