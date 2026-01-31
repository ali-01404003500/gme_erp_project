<?php


namespace Modules\Account\Models;


use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Collection extends Model
{
    use AutoCreateUpdateAndHistory;
    protected $casts = [
        'collection_date' => 'date'
    ];


    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
