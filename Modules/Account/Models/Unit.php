<?php


namespace Modules\Account\Models;
use App\Model;
use App\Traits\AutoCreateUpdateAndHistory;
use Modules\Account\Models\Model as ModelsModel;

class Unit extends ModelsModel
{
    use AutoCreateUpdateAndHistory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
