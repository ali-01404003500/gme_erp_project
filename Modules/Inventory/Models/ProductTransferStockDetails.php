<?php

namespace Modules\Inventory\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransferStockDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productTransferDetail(){
        return $this->belongsTo(ProductTransferDetail::class, 'details_id');
    }
}
