<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveEligibleEmployee extends Model
{
    protected $fillable = ['condition_type', 'eligibility'];
}
