<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserLogHistory;
use Illuminate\Support\Facades\App;

trait AutoHistory
{

    public static function boot()
    {
        parent::boot();

        if (!App::runningInConsole()) {
            /**
             * Auto create created_by field when create anything through model
             */
            static::created(function ($model) {
                
                $model_name =  get_class($model);

                $create = json_encode($model->getAttributes());

                $date = date('Y-m-d H:i:s');

                $user_id =  auth()->id();
                $status = "created";
                $ip =     request()->ip();
                $remark = $model->remark ?? null;
                $ref =    $model->id;

                UserLogHistory::create([
                    'user_id' => $user_id,
                    'title' => $model_name,
                    'action' => $status,
                    'from' => '',
                    'ip' => $ip,
                    'ref' => $ref,
                    'remarks' => $remark,
                    'data' =>  $create,
                ]);
            });

            /**
             * Auto update updated_by field when update anything of the model data
             */
            // static::updating(function ($model) {
                
            // });


            static::updated(function ($model) {

                if($model->deleted_at != null || $model->deleted_by != null) {
                    return;
                }
                $model_name =  get_class($model);

                $create = json_encode($model->getAttributes());

                $date = date('Y-m-d H:i:s');

                $user_id =  auth()->id();
                $status = "updated";
                $ip =     request()->ip();
                $remark = $model->remark ?? null;
                $ref =    $model->id;

                UserLogHistory::create([
                    'user_id' => $user_id,
                    'title' => $model_name,
                    'action' => $status,
                    'from' => '',
                    'ip' => $ip,
                    'ref' => $ref,
                    'remarks' => $remark,
                    'data' =>  $create,
                ]);
            });


            static::deleting(function ($model) {


                if (!$model->forceDeleting) {
                    
                    $model_name =  get_class($model);

                    $create = json_encode($model->getAttributes());

                    $date = date('Y-m-d H:i:s');

                    $user_id =  auth()->id();
                    $status = "deleted";
                    $ip =     request()->ip();
                    $remark = $model->remark ?? null;
                    $ref =    $model->id;

                    UserLogHistory::create([
                        'user_id' => $user_id,
                        'title' => $model_name,
                        'action' => $status,
                        'from' => '',
                        'ip' => $ip,
                        'ref' => $ref,
                        'remarks' => $remark,
                        'data' =>  $create,
                    ]);
                }
                
            });

            
        }
    }
}
