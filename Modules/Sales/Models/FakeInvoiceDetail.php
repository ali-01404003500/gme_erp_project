<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;

class FakeInvoiceDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function fakeInvoice()
    {
        return $this->belongsTo(FakeInvoice::class, 'fake_invoice_id');
    }
    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
