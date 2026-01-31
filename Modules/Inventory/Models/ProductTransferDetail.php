<?php

namespace Modules\Inventory\Models;


use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\Settings\Unit;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransferDetail extends Model
{
    use HasFactory;
   

    protected $guarded = [];

    public function productCatalog(){
        return $this->belongsTo(ProductCatalog::class,"product_id");
    }

    public function productType(){
        return $this->belongsTo(ProductType::class,"product_type_id");
    }

    public function unitType(){
        return $this->belongsTo(Unit::class,"unit_type_id");
    }
    public function productTransferStockDetails(){
        return $this->hasMany(ProductTransferStockDetails::class, 'details_id');
    }

    public function productTransfer(){
        return $this->belongsTo(ProductTransfer::class);
    }
}
