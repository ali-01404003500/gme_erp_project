<?php

namespace App\Models\AccessControl;


use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    public $deletePrevent = ['users', 'contactPersionDetails'];
    protected $guarded = [];

    public function branchType()
    {
        return $this->belongsTo(BranchType::class, 'branch_type_id');
    }

    public function contactPersionDetails()  {
        return $this->hasMany(ContactPersionDetail::class, 'branch_id');
    }

    public function users()  {
        return $this->hasMany(User::class, 'branch_id');
    }
    
}
