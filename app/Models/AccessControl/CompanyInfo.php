<?php

namespace App\Models\AccessControl;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyInfo extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public function commercialInfo(){
        return $this->hasOne(CommercialInfo::class, 'company_id', 'id');
    }

    public function index()
    {
        $companyInfo = cache()->remember('company_info', now()->addHours(24), function () {
            return CompanyInfo::first();
        });

        return view('your-view', compact('companyInfo'));
    }
}
