<?php

namespace Modules\CRM\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSettingBroker extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function broker(){
        return $this->belongsTo(Broker::class);
    }

}
