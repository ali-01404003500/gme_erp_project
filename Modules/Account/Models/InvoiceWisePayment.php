<?php

namespace Modules\Account\Models;

use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Purchase\Models\OfficePurchase;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;

class InvoiceWisePayment extends BaseModel
{
    use AutoCreateUpdateAndHistory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'advance_amount' => 'decimal:2',
    ];

    /**
     * Polymorphic relation to payment_to (Supplier or Vendor)
     */
    public function paymentTo()
    {
        return $this->morphTo();
    }

    /**
     * Get payment details (payment methods used)
     */
    public function payments()
    {
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }

    /**
     * Get all accounting transactions
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Get invoice records (pivot table records)
     */
    public function invoices()
    {
        return $this->hasMany(InvoiceWisePaymentInvoice::class);
    }

    /**
     * Get requisitions through polymorphic relationship
     */
    public function requisitions()
    {
        return $this->morphedByMany(Requisition::class, 'invoice', 'invoice_wise_payment_invoices')
            ->withPivot('amount')
            ->withTimestamps();
    }

    /**
     * Get office purchases through polymorphic relationship
     */
    public function officePurchases()
    {
        return $this->morphedByMany(OfficePurchase::class, 'invoice', 'invoice_wise_payment_invoices')
            ->withPivot('amount')
            ->withTimestamps();
    }

    /**
     * Get the user who created this payment
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified this payment
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the user who approved this payment
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if payment is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is verified
     */
    public function isVerified()
    {
        return $this->status === 'verified';
    }

    /**
     * Scope to filter approved payments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to filter pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

        public function signature(){
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }
}

