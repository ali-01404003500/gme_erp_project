<?php

namespace Modules\Inventory\Models;

use App\Models\AccessControl\Branch;
use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class ProductTransferRequest extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class);
    }
    
    public function productTransferRequestDetails(){
        return $this->hasMany(ProductTransferRequestDetail::class);
    }

    public function sourceBranch(){
        return $this->belongsTo(Branch::class, 'source_branch_id');
    }

    public function destinationBranch(){
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class, "customer_id");
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
