<?php

namespace App\Traits;

use App\Models\User;


trait AutoCreateUpdateAndHistory
{
    use AutoCreatedUpdated{
        AutoCreatedUpdated::boot as bootCreateUpdate;
    }

    use AutoHistory{
        AutoHistory::boot as bootHistory;
    }

    public static function boot(){
        parent::boot();

        self::bootCreateUpdate();
        self::bootHistory();
    }
}