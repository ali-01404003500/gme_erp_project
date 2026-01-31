<?php

namespace Modules\Inventory\Models;


use App\Traits\AutoCreateUpdateAndHistory;
use App\Traits\AutoHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IssueProduct extends Model
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function issueProductDetails()
    {
        return $this->hasMany(IssueProductDetails::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(warehouse::class);
    }
}
