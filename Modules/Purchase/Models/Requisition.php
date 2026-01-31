<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use App\Models\AccessControl\Branch;
use Modules\Inventory\Models\warehouse;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\InvoiceWisePayment;
use Modules\Account\Models\InvoiceWisePaymentInvoice;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Customer;

class Requisition extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'file_uploads' => 'array'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function requisitionDetails()
    {
        return $this->hasMany(RequisitionDetail::class);
    }

    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {
            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
            });
        }
    }

    public function receive()
    {
        return $this->hasOne(RequisitionReceive::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiveSerials()
    {
        return $this->hasMany(RequisitionReceiveSerial::class, 'requisition_id');
    }

    public function receiveBatches()
    {
        return $this->hasMany(RequisitionReceiveBatch::class, 'requisition_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }

    public function paymentDetails()
    {
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }

    public function payment()
    {
        return $this->morphMany(MakePayment::class, 'source');
    }

    public function invoiceWisePaymentInvoices()
    {
        return $this->morphMany(InvoiceWisePaymentInvoice::class, 'invoice');
    }

    public function invoiceWisePayments()
    {
        return $this->morphToMany(
            InvoiceWisePayment::class,
            'invoice',
            'invoice_wise_payment_invoices'
        )->withPivot('amount')->withTimestamps();
    }

    // ================================================================
    // NEW: Accessors for Paid & Due Amount (supports both payment types)
    // ================================================================

    /**
     * Total paid amount from BOTH MakePaymentDetail AND InvoiceWisePayment
     */
    public function getPaidAmountAttribute(): float
    {
        $oldPaid = $this->payment->where('status', 'approved')
        ->flatMap(fn($payment) => $payment->paymentDetails)
        ->sum('amount');


        

        $newPaid = $this->invoiceWisePaymentInvoices()
            ->whereHas('invoiceWisePayment', fn($q) => $q->where('status', 'approved'))
            ->sum('amount');

        return (float) ($oldPaid + $newPaid);
    }

    /**
     * Remaining due amount
     */
    public function getDueAmountAttribute(): float
    {
        return max(0, $this->net_amount - $this->paid_amount);
    }
}