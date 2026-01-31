<?php

namespace Modules\HRMS\Models\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\TransportExpense;

class TransportType extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    public $deletePrevent = ['transportExpenses'];


    protected $guarded = [];

    
    public function transportExpenses()
    {
        return $this->hasMany(TransportExpense::class, 'transport_by');
    }
}
