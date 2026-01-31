<?php

namespace Modules\CRM\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\Settings\Tag;

class CustomerSettingDiscount extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

public function PercentageType(){
        return $this->belongsTo(Tag::class,"percentage_type");
    }

}
