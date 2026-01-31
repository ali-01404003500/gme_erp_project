<?php

namespace Modules\Licenses\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class USGOrOPGLicenseRequisitionPhone extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function uSGOrOPGLicenseRequisition()
    {
        return $this->belongsTo(USGOrOPGLicenseRequisition::class);
    }
}
