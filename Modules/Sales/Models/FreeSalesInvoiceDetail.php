<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Models\ProductCatalog;

class FreeSalesInvoiceDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function freeSalesInvoice()
    {
        return $this->belongsTo(FreeSalesInvoice::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }
}
