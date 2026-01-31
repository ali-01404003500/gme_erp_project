<?php

namespace App\Models\AccessControl;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchType extends BaseModel
{
    use HasFactory;  
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    public $deletePrevent = ['branch'];


    protected $guarded = [];

    public function branch(){
        return $this->hasMany(Branch::class, 'branch_type_id');
    }
}
