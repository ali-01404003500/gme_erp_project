<?php

namespace Modules\Services\Models;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Models\ProductCatalog;

class ServiceDocumentEntry extends BaseModel
{
   use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes; 
    
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }
}
