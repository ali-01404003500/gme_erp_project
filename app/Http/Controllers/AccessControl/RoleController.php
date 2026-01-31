<?php

namespace App\Http\Controllers\AccessControl;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\PermissionMaster;
use App\Models\AccessControl\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $data["roles"] = Role::with('users')->paginate();
        return view('access_control.roles.index', $data);
    }

    public function create()
    {
        $data['permissionMasters'] = PermissionMaster::with(['permissions', 'subMasters'])
                ->where("parent_id", null)->orWhere("parent_id", null)->get();
        return view('access_control.roles.create-role', $data);
    }

    public function store(Request $request)
    {
       
        $this->validate($request, [
            'name' => 'required',
            'permitted' => 'required|array|min:1',

        ],[
            'name.required' => 'Role name is required',
            'permitted.required' => 'Atleast one permission is required',
        ]);
        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        $role->permissions()->sync(array_keys($request->permitted));
        foreach ($role->users as $user) {
            InvalidateAuthUserCashe($user->id);
        }

        return redirect()->back()->with('success', 'New Role added successfully');
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return view('access-control.roles.view', $role);
    }

    public function edit($id)
    {
        if($id==1){
            return redirect()->back()->withErrors( 'You can not edit this role');
        }
        $data['role'] = Role::findOrFail($id);
        $loggedData = getAuthUserCashe();
        // dd($loggedData['permissions']);
        $data['permissionMasters'] = PermissionMaster::with(['permissions', 'subMasters'])
        ->where("parent_id", null)->orWhere("parent_id", null)->get();;
        // dd($data['permissionMasters']->first());
        return view('access_control.roles.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $role = Role::findOrFail($id);
        foreach ($role->users as $user) {                   ///pre aupdate permission
            InvalidateAuthUserCashe($user->id);
        }

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        $role->permissions()->sync(array_keys($request->permitted));

        foreach ($role->users as $user) {                   //Post update permission
            InvalidateAuthUserCashe($user->id);
        }

        return redirect()->back()->with('success', 'Role update successfully');
    }
    public function addUserToRoleView($id)
    {
        $data['role'] = Role::find($id);
        $data['users'] = User::select('id', 'name', 'email')->get();
        return view('access_control.roles.add_user', $data);
    }
    public function addUserToRole($id, Request $request)
    {
        if($id==1){
            if(!in_array(1, $request->users ?? [])){
                return redirect()->back()->with( ['error'	=>'You can not remove this user from this role']);
            }
        }
        $role = Role::find($id);
        foreach ($role->users as $user) {                   //post update permission
            InvalidateAuthUserCashe($user->id);
        }
        $role->users()->sync($request->users ?? []);
        $role->refresh();
        foreach ($role->users as $user) {                   //post update permission
            InvalidateAuthUserCashe($user->id);
        }
        return redirect()->back()->with('success', 'User added successfully');
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->users()->detach();
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }
}
