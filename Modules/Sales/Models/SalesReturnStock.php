<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class SalesReturnStock extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function salesReturnDetail()
    {
        return $this->belongsTo(SalesReturnDetail::class, 'sales_return_detail_id', 'id');
    }
}
