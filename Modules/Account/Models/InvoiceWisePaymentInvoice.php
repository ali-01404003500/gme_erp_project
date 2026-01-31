<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Purchase\Models\OfficePurchase;
use Modules\Purchase\Models\Requisition;

class InvoiceWisePaymentInvoice extends Model
{
    protected $guarded = [];

    /**
     * Get the invoice wise payment that owns this invoice
     */
    public function invoiceWisePayment()
    {
        return $this->belongsTo(InvoiceWisePayment::class);
    }

    /**
     * Get the invoice (Requisition or OfficePurchase)
     */
    public function invoice()
    {
        return $this->morphTo();
    }

    /**
     * Get requisition if invoice is a requisition
     */
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'invoice_id')
            ->where('invoice_type', Requisition::class);
    }

    /**
     * Get office purchase if invoice is an office purchase
     */
    public function officePurchase()
    {
        return $this->belongsTo(OfficePurchase::class, 'invoice_id')
            ->where('invoice_type', OfficePurchase::class);
    }
}