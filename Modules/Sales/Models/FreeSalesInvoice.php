<?php

namespace Modules\Sales\Models;

use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;

class FreeSalesInvoice extends Model
{
    use HasFactory,SoftDeletes, AutoCreateUpdateAndHistory;

    protected $guarded = [];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(FreeSalesInvoiceDetail::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signature()
    {
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }
}
