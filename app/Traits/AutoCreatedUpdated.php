<?php

namespace App\Traits;

use App\Exceptions\IntegrityAvailableException;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\LinesOfCode\IllogicalValuesException;

trait AutoCreatedUpdated
{
    // protected $deletePrevent = [];
    public static function boot()
    {
        parent::boot();

        if (!App::runningInConsole()) {
            /**
             * Auto create created_by field when create anything through model
             */
            static::creating(function ($model) {
                $model->fill([
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            });

            /**
             * Auto update updated_by field when update anything of the model data
             */
            static::updating(function ($model) {
                $model->fill([
                    'updated_by' => auth()->id()
                ]);
            });

            static::deleting(function ($model) {
                foreach ($model->deletePrevent??[] as $key => $value) {
                    if (method_exists($model, $value)) {
                        if($model->{$value}()->count() > 0) {
                            throw new IntegrityAvailableException('You can not delete it because it already used in '.$value);
                        }
                    }
                }
                if (!$model->isForceDeleting()) {
                    $model->deleted_by = Auth::id();
                    $model->save();
                }
            });
            
        }
        

        // if (method_exists(parent::class, 'bootHistory')) {
            // parent::bootHistory();
        // }
        
    }


    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }



    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }



    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function deleted_user()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
}
