<?php

namespace Modules\Account\Models\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankBranch extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    protected $guarded = [];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
