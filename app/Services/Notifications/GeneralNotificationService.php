<?php

namespace App\Services\Notifications;

use App\Models\AccessControl\Role;
use App\Models\Notifications\GeneralNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GeneralNotificationService
{
    public function getAll(int $limit = 20) {

        if (!request()->has('status')) {
            request()->merge(['status' => '1']);
        }
            
        return GeneralNotification::query()
            ->searchByFields(['status'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
    
    public function store(array $data, array $user_ids=[], $source = null)
    {
        // dd($data + ['source_type' => get_class($source), 'source_id' => $source->id]);
        if ($source) {
            $generalNotification = GeneralNotification::create($data + ['source_type' => get_class($source), 'source_id' => $source->id]);
        } else {
            $generalNotification = GeneralNotification::create($data);
        }
        $generalNotification->users()->attach($user_ids);

        foreach ($user_ids as $user_id) {
            $count = User::where('id', $user_id)
            ->withCount(['generalNotifications' => function($query) {
                $query->where('status', 1);
            }])
            ->first()
            ->general_notifications_count;
            Cache::put('notification_count_'.$user_id, $count);
        } 
        // dd($generalNotification);
        return $generalNotification;
    }

    public function updateBySource($source, $status = 2)
    {
        $generalNotifications = GeneralNotification::where('source_type', get_class($source))
            ->where('source_id', $source->id)
            ->get();
        foreach($generalNotifications as $generalNotification){
            $generalNotification->update(['status' => $status]);
        }
    }

    public function update(GeneralNotification $generalNotification, array $data)
    {
        $generalNotification->update($data);
        return $generalNotification;
    }

    public function delete(GeneralNotification $generalNotification)
    {
        $generalNotification->delete();
    }

    public function show($id)
    {
        return GeneralNotification::findOrFail($id);
    }

    public function getNotificationCount(){
        return Cache::get('notification_count_'.(Auth::user()->id), 0);
    }

    public function getNotifications(){
        $limit = request()->input('limit', 10);
        //get latest notifications for current user
        return User::with(['generalNotifications' => function($q) use ($limit) {
            $q->where('status', 1)->latest()->limit($limit);
        }])->where('id', Auth::user()->id)->first()->generalNotifications;

    }

    public function updateNotificationCount($user_id = null){
        if($user_id == null){
            $user_id = Auth::user()->id;
        }
        $count = User::where('id', $user_id)
            ->withCount(['generalNotifications' => function($query) {
                $query->where('status', 1);
            }])
            ->first()
            ->general_notifications_count;
            Cache::put('notification_count_'.$user_id, $count);
    }

    public function actionBuilder($controller, $method, $params=[]){
        return json_encode([
            'controller' => $controller,
            'method' => $method,
            'params' => $params
        ]);
    }

    /**
     * Returns a json encoded string for redirecting to a given url
     * The string is in the format of an action that can be used by the GeneralNotificationController
     * @param string $url The url to redirect to
     * @return string The json encoded string
     */
      public function redirectBuilder($url){
        return json_encode([
            'type' => 'redirect',
            'url' => $url
        ]);
    }

    public function getPermittedUsers(string $permission){
        $user_ids = Role::with(['users', 'permissions'])->whereHas('permissions', function ($query) use ($permission) {
            $query->where('slug', $permission);
        })->get()->pluck('users')->flatten()->pluck('id')->toArray();

        $admin_user_id = User::whereHas('roles', function ($query) {
            $query->where('slug', 'supper_admin');
        })->get()->pluck('id')->toArray();
        $user_ids = array_merge($user_ids, $admin_user_id);
        return  array_unique($user_ids);
    }

    
}
