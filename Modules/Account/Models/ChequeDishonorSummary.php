<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class ChequeDishonorSummary extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function chequeVerification(){
        $this->belongsTo(ChequeVerification::class,'cheque_verification_id');
    }
}
