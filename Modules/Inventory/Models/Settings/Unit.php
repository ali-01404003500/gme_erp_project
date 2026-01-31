<?php

namespace Modules\Inventory\Models\Settings;


use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\ProductTransferDetail;
use Modules\Inventory\Models\ProductTransferRequestDetail;
use App\Traits\AutoCreateUpdateAndHistory;
use App\Traits\AutoHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Models\IssueProductDetails;

class Unit extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    public $deletePrevent = ['issueProductDetails', 'productCatalogs'];
    protected $guarded = [];

    
    public function issueProductDetails()
    {
        return $this->hasMany(IssueProductDetails::class, 'unit_type_id');
    }
    
    public function productCatalogs()
    {
        return $this->hasMany(ProductCatalog::class, 'unit_type_id');
    }
    
}
