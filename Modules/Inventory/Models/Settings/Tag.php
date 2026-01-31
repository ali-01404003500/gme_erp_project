<?php

namespace Modules\Inventory\Models\Settings;


use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Models\ProductCatalog;

class Tag extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    public $deletePrevent = ['products', 'productCatalogs'];

    protected $guarded = [];

    
    public function products()
    {
        return $this->hasMany(ProductCatalog::class, 'product_tag_id');
    }
    public function productCatalogs()
    {
        return $this->hasMany(ProductCatalog::class, 'product_tag_id');
    }
}
