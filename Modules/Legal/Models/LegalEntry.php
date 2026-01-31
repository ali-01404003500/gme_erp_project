<?php

namespace Modules\Legal\Models;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalEntry extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;  

    protected $guarded = [];
    protected $casts = [
            'attachment' => 'array'
        ];
    public function complainant()
    {
        return $this->hasOne(LegalEntryComplainant::class);
    }

    public function convicts()
    {
        return $this->hasMany(LegalEntryConvict::class);
    }

    public function hajiras()
    {
        return $this->hasMany(LegalEntryHajira::class);
    }

    public function witnesses()
    {
        return $this->hasMany(LegalEntryWitness::class);
    }
}
