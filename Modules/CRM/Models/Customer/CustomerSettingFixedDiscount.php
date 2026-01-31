<?php

namespace Modules\CRM\Models\Customer;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSettingFixedDiscount extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(ProductCatalog::class);
    }

}
