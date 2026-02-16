<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDocuments extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory; 
    
    protected $fillable = [
        'employee_id',
        'title',
        'remarks',
        'document_upload'
    ]; 
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

   
}

 