<?php

namespace Modules\Account\Models\Collections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payment;
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
     * Get all of the sales orders for the invoice-wise collection.
     */
    public function salesOrders()
    {
        return $this->belongsToMany(SalesOrder::class, 'invoice_wise_collection_sales_order', 'invoice_wise_collection_id', 'sales_order_id')
                    ->withPivot('amount')
                    ->withTimestamps();
    }
}
