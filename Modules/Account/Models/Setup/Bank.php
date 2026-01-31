<?php

namespace Modules\Account\Models\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    public $deletePrevent = ['branches'];

    protected $guarded = [];

    
    public function branches()
    {
        return $this->hasMany(BankBranch::class, 'bank_id');
    }
}
