<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\InvoiceWisePayment;
use Modules\Account\Models\InvoiceWisePaymentInvoice;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Account\Models\Transaction;

class OfficePurchase extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }

     public function paymentDetails(){
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }
    public function payment(){
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

    
}
