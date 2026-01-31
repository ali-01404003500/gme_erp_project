<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileManagerController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [LoginController::class, 'loginApi']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [LoginController::class, 'logoutApi']);

    //test
    Route::get('test', function () {
        return response()->json(['success' => true, "user"=>auth()->user()]);
    });

    //file upload
    Route::post('/files', [FileController::class, 'upload']);
    //file delete
    Route::delete('/files/{path}', [FileController::class, 'destroyFile'])->where('path', '.*');

    Route::get('/import-json', [App\Http\Controllers\HomeController::class, 'importJson'])->name('import_json');

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = User::with(['employee', 'branch', 'roles', 'customer'])->find(auth()->user()->id);
    $user->employee;
    $user->branch;
    $user->roles;
    $user->customer;
    return response()->json($user);
});

Route::middleware('auth:api')->get('my-permissions', function (Request $request) {
    if (auth()->check()){
        $permissions = getAuthUserCashe();
        // dd($permissions);
        return response()->json($permissions);
    }
    return response()->json();
});


Route::get('/files/{path}', [App\Http\Controllers\FileController::class, 'getFile'])
    ->where('path', '.*')
    ->name('download_file');