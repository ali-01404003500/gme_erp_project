<?php

// check authenticate use is system admin or not

use App\Models\AccessControl\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * check authenticate use is system admin or not
 * @param string $slug
 * @return bool
 * 
 */

function CacheLoaggedUserData()
{
    if (auth()->check()) {
        $id = auth()->id();
        if (!Cache::has('auth-user-data-' . $id)) {
            $roleSlugs = auth()->user()->roles->pluck('slug');
            $permissions = auth()->user()->roles->pluck('permissions')->flatten()->pluck('slug');
            Cache::set('auth-user-data-' . $id, collect([
                'roles' => $roleSlugs,
                'permissions' => $permissions
            ]), now()->addHours(12));
        }
    }
}

function getAuthUserCashe()
{
    if (auth()->check()) {
        $id = auth()->id();
        CacheLoaggedUserData();
        return Cache::get('auth-user-data-' . $id);
    }
    return null;
}

function InvalidateAuthUserCashe($id)
{
    Cache::forget('auth-user-data-' . $id);
}
function hasPermission($slug)
{
    // dd(request()->route()->getName());
    //is authenticated user 
    if (auth()->check()) {
        $loggedData = getAuthUserCashe();
        if (collect($loggedData['permissions'])->contains($slug) || collect($loggedData['roles'])->contains('supper_admin') || (str_ends_with($slug, '.*') && collect($loggedData['permissions'])->filter(function ($permission) use ($slug) {return str_starts_with($permission, substr($slug, 0, -2));})->isNotEmpty())) {
            return true;

        }
        // debug mode enable 
        elseif (config('app.debug')) {
            // check slug in permission table
            if (!Permission::where('slug', $slug)->exists()) {
                //log a warn
                Log::warning("slug:{$slug} not found in permission table or wrong slug");
                return false;
            }
            return false;
        }
        return false;
    }
    return false;
}

function hasAnyPermission(array $slugs)
{
    foreach ($slugs as $slug) {
        if (hasPermission($slug)) {
            return true;
        }
    }
    return false;
}

// check footer panel show or not
// function isShowFooter()
// {
//     return      !request()->is('hrm/payroll/master-salary/*')
//             &&  !request()->is('hrm/payroll/bank-salary/*')
//             &&  !request()->is('hrm/payroll/cash-salary/*')
//             &&  !request()->is('hrm/payroll/master-salary-without-payslip/*')
//             &&  !request()->is('hrm/payroll/master-salary-with-payslip/*');
// }




// // // get group info
// // function getGroup()
// // {
// //     return Cache::remember('group', $oneDay = 86400, function () {
// //         return Group::first();
// //     });
// // }




// // get fav icon
// function getFavIcon()
// {
//     if (file_exists(optional(getGroup())->fav_icon)) {
//         return $fav_icon = asset(optional(getGroup())->fav_icon);
//     }

//     return '/icon.png';
// }



// // check user login in local
// function isApplicationInLocal()
// {
//     try {

//         return env('APP_PRODUCTION_TYPE') == 'local';

//     } catch(\Exception $ex) {

//         return false;
//     }
// }



// function employee_full_ids()
// {
//     return Cache::remember('employees', $oneDay = 86400, function () {
//         return Employee::query()->active()->permissionEmployment()->get(['id', 'name', 'employee_full_id']);
//     });
// }



// function employeesCardNumbers()
// {
//     return Cache::remember('employeesCardNumbers', $oneDay = 86400, function () {
//         return Employee::query()->active()->permissionEmployment()->pluck('card_no');
//     });
// }


// function getGarmentsSettings(string $key, $company_id = null) {
//     return garmentsSettings()->where('key', $key)->when($company_id != null, fn($q) => $q->where('company_id', $company_id))->first()->value;
// }

// function garmentsSettings()
// {
//     // return Cache::remember('garments_settings', $oneDay = 86400, function () {
//         return GarmentsSetting::get();
//     // });
// }



// // SUPPLIER TYPE CHECK FOR GARMENTS MODULE AND GENERAL STORE MODULE
// function checkSupplierType($supplier_type_id)
// {

//     return request()->supplier_pi_type_id == $supplier_type_id;
// }

// function checkSupplerTypeRequisition($requisition, $supplier_type_id){
//     return $requisition->work_order_type_id ==  $supplier_type_id;
// }


// // auto update employment position that employee where not have position
// function updateEmploymentPosition($request, $model)
// {
//     $oldModel = clone($model);

//     $data = $model->with('employee.active_employment')->latest()->take(300)->whereDoesntHave('position')->get();

//     $count = 0;

//     foreach ($data as $key => $item) {
//         $item->update([
//             'position_id' => optional(optional($item->employee)->active_employment)->id
//         ]);
//         $count++;
//     }

//     if ($count > 0) {

//         $nextCount  = $oldModel->whereDoesntHave('position')->count();
//         $message    = $count . ' Employment position updated. ';

//         if ($nextCount > 300) {
//             return $message . ' Reload for next 300 from ' . $nextCount;
//         } else if($nextCount > 0) {
//             return $message . ' Reload for last ' . $nextCount;
//         }
//     }

//     return '';
// }
