<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationTermsAndCondition extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
