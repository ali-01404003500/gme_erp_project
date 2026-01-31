<?php

namespace App\Models;

use Modules\Inventory\Models\Stock;
use Illuminate\Database\Eloquent\Model;

abstract class StockModel extends Model{
    
    public function stock() {
        return $this->morphMany(Stock::class, 'source', 'source_type', 'source_id');
    }
    
    abstract public function getParentIdAttribute();
}