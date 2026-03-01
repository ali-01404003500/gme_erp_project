<?php

use App\Http\Controllers\AccessControl\BranchController;
use App\Http\Controllers\AccessControl\BranchTypeController;
use App\Http\Controllers\AccessControl\GlobalSettingController;
use App\Http\Controllers\AccessControl\RoleController;
use App\Http\Controllers\AccessControl\ServiceNameController;
use App\Http\Controllers\AccessControl\SmsTemplateController;
use App\Http\Controllers\AccessControl\TriggerNameController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoLocationController;
use App\Http\Controllers\Notifications\GeneralNotificationController;
use App\Http\Controllers\UserLogHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\KeepSignController;
use App\Http\Controllers\OtpVerifyController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['middleware'=>'guest'],function(){
//     Route::get('/',[AuthController::class,'login'])->name('login');
//     Route::get('/register',[AuthController::class,'register'])->name('register');
//     Route::get('/forget-password',[AuthController::class,'forgetPassword'])->name('forget_password');
//     Route::post('/authenticate',[AuthController::class,'authenticate'])->name('authenticate');
//     Route::post('/signup',[AuthController::class,'signup'])->name('signup');
// });

// Route::post('/logout',[AuthController::class,'logout'])->name('logout')->middleware('auth');
// Route::get('/lang/{lang}',[ LanguageController::class,'switchLang'])->name('switch_lang');
// Route::get('/pagination-per-page/{per_page}',[ PaginationController::class,'set_pagination_per_page'])->name('pagination_per_page');

Route::get('/dark-mode-switcher', function (Request $request) {
    $request->session()->put('dark_mode', $request->dark_mode);
    return response()->json(['success' => true]);
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    // Route::get("/locations", function (Request $request) {
    //     if($request->type == "country"){
    //         return cuntriesNames();
    //     }
    //     if($request->type == "division"){
    //         return getDivision();
    //     }
    //     if($request->type =="district"){
    //         return getDistrict($request->division);
    //     }
    // })->name('locations');
 
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/summary', [App\Http\Controllers\DashboardController::class, 'summary'])->name('dashboard.summary');
    Route::get('/dashboard/summary-user', [App\Http\Controllers\DashboardController::class, 'summaryForUser'])->name('dashboard.usersummary');


    Route::get('my-profile', [App\Http\Controllers\HomeController::class, 'myProfile'])->name('my_profile');
    Route::post('change-password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('change-password');
    Route::post('profile-photograph-upload', [App\Http\Controllers\HomeController::class, 'profilePhotographUpload'])->name('profile-photograph-upload');

    Route::get('locations', [GeoLocationController::class, 'getLocations'])->name('locations');
    Route::post('add-geo-location', [GeoLocationController::class, 'addGeoLocation'])->name('add-geo-location');

    //get-notification-count
    Route::get('get-notification-count', [GeneralNotificationController::class, 'getNotificationCount'])->name('get-notification-count');
    Route::get('get-notifications', [GeneralNotificationController::class, 'getNotifications'])->name('get-notifications');
    Route::get('notification-action/{id}', [GeneralNotificationController::class, 'notificationAction'])->name('notification.action');

    // Users
    // Route::resource('users', UserController::class);

    // access controll
    Route::group(['prefix' => 'access-control', 'as' => 'access_control.'], function () {
        /* Role */
        Route::resource('roles', RoleController::class);
        //add user to role
        Route::get('roles/{id}/add-user', [RoleController::class, 'addUserToRoleView'])->name('roles.add_user_view');
        Route::post('roles/{id}/add-user', [RoleController::class, 'addUserToRole'])->name('roles.add_user');

        Route::resource('global-settings', GlobalSettingController::class);

        Route::resource('branchs', BranchController::class);
        Route::resource('branch-types', BranchTypeController::class);

    });

    //hrm and payroll

    // notifications.general-notifications.index

    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::resource('general-notifications', GeneralNotificationController::class)->except(['create', 'edit', 'update']);


        Route::post('opt-verification-request', [GeneralNotificationController::class, 'optVerificationRequest'])->name('opt-verification-request');
        Route::get('otp-verification-status', [GeneralNotificationController::class, 'getOtpVerificationStatus'])->name('otp-verification-status');

    });

    Route::group(['prefix' => 'verification', 'as' => 'verification.'], function () {
        Route::get('verification-requests', [OtpVerifyController::class, 'showVerificationForm'])->name('verification-requests');
        Route::post('verify-otp', [OtpVerifyController::class, 'verifyOtp'])->name('verify-otp');

        Route::post('create-otp', [OtpVerifyController::class, 'createOtp'])->name('create-otp');
        Route::post('update-otp', [OtpVerifyController::class, 'updateOtp'])->name('update-otp');
        Route::delete('delete-otp', [OtpVerifyController::class, 'deleteOtp'])->name('delete-otp');
    });


    Route::group(['prefix' => 'history', 'as' => 'history.'], function () {
        Route::resource('user-log-histories', UserLogHistoryController::class);
    });

    Route::group(['prefix' => 'sms', 'as' => 'sms.'], function () {
        Route::resource('templates', SmsTemplateController::class);
        Route::get('service-name-wise-trigger-names', [SmsTemplateController::class, 'serviceNameWiseTrigerName'])->name('service-name-wise-trigger-names');
        Route::get('entity-list', [SmsTemplateController::class, 'loadEntities'])->name('entity-list');
        Route::resource('service-names', ServiceNameController::class);
        Route::resource('trigger-names', TriggerNameController::class);
    });

    Route::resource('branchs',BranchController::class);

    //export 
    Route::any('/pdf-report', [ReportController::class, 'generatePdfReport'])
        ->name('pdf_report')
        ->withoutMiddleware([VerifyCsrfToken::class]);
    
    Route::post('keep-signature', [KeepSignController::class, 'saveSignature'])->name('keep_signature');


    // file controller
    Route::post('/upload-file', [App\Http\Controllers\FileController::class, 'upload'])->name('upload_file');
    Route::post('/delete-file', [App\Http\Controllers\FileController::class, 'destroyFile'])->name('delete_file');
    // Route::delete('/delete-file', [App\Http\Controllers\FileController::class, 'destroyFile'])->name('delete_file');
    Route::delete('/files/{path}', [App\Http\Controllers\FileController::class, 'destroyFile'])
    ->where('path', '.*')
    ->name('download_file');
});
Route::group(['middleware' => 'guest', 'prefix' => 'password', 'as' => 'password.'], function () {
    Route::get('reset', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('request');
    Route::post('send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('send-otp');
    Route::post('verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('reset', [ForgotPasswordController::class, 'resetPassword'])->name('reset');
});


Route::get('/import-json', [App\Http\Controllers\HomeController::class, 'importJson'])->name('import_json');

Route::get('/files/{path}', [App\Http\Controllers\FileController::class, 'getFile'])
    ->where('path', '.*')
    ->name('download_file');

// Route::get('/files', [FileManagerController::class, 'index'])->name('files.index');
// Route::post('/files', [FileManagerController::class, 'store'])->name('files.store');
// Route::get('/files/{filename}', [FileManagerController::class, 'show'])->name('files.show');
// Route::delete('/files/{filename}', [FileManagerController::class, 'destroy'])->name('files.destroy');