<?php
namespace Modules\SalesTarget\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTarget extends Model
{
    protected $guarded = []; // fields onek beshi, ei jonno guarded fully open rakha holo

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function slab()
    {
        return $this->belongsTo(SalesTargetSlab::class, 'sales_target_slab_id');
    }
}
