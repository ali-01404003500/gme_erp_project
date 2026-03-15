<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;

class AttendancePolicy extends Model
{

    protected $table = 'attendance_policies';

    protected $guarded = [];

    protected $casts = [
        'day_wise_settings' => 'array',
        'effective_from'    => 'date',
    ];
}
