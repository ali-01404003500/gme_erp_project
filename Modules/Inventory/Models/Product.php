<?php

namespace Modules\Inventory\Models;


use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\Settings\Tag;
use Modules\Inventory\Models\Settings\Unit;
use App\Traits\AutoCreateUpdateAndHistory;
use App\Traits\AutoHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    public $deletePrevent = [];

    protected $guarded = [];

    public function productType(){
        return $this->belongsTo(ProductType::class);
    }

    public function productCatalog(){
        return $this->belongsTo(ProductCatalog::class);
    }

    public function tag(){
        return $this->belongsTo(Tag::class, 'product_tag_id');
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'product_brand_id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_type_id');
    }


    
    
}
