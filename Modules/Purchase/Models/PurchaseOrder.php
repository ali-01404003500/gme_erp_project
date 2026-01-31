<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\Product\Settings\Brand;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class PurchaseOrder extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function detailes(){
        return $this->hasMany(PurchaseOrderDetail::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
