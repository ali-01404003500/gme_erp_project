<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class OpeningBalanceDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function accountOpeningBalance()
    {
        return $this->belongsTo(AccountOpeningBalance::class, 'aop_id', 'id');
    }
}
