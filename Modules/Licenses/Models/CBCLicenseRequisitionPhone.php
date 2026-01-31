<?php

namespace Modules\Licenses\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CBCLicenseRequisitionPhone extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function cBCLicenseRequisition()
    {
        return $this->belongsTo(CBCLicenseRequisition::class);
    }
}
