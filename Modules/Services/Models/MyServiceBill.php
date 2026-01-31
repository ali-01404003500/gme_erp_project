<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;

class MyServiceBill extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class);
    }
}
