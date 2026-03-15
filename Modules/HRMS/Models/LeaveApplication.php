<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\ApprovalRequest;
use Modules\HRMS\Models\Settings\LeaveType;

class LeaveApplication extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    // protected $casts = [
    //     'start_date' => 'date',
    //     'end_date' => 'date',
    //     'file_uploads' => 'array'
    // ];

    protected $casts = [
        'file_uploads' => 'array'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function approvals()
    {
        return $this->morphMany(ApprovalRequest::class, 'reference');
    }
    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {

            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
             });
        }

    }
}
 