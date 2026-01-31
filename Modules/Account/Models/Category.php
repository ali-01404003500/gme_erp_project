<?php


namespace Modules\Account\Models;


use App\Traits\AutoCreateUpdateAndHistory;

class Category extends Model
{
    use AutoCreateUpdateAndHistory;

    protected $table = 'acc_categories';
}
