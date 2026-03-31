<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Database\Factories\AttendanceFactory;
use Modules\HRMS\Models\Settings\Shift;

class Attendance extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    { 
        return AttendanceFactory::new();
    }


    protected $guarded = [];
 
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    } 

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {

            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
             });
        }

    }

    public function entryBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

}
