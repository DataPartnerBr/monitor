<?php

use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\MaintenanceController;
use App\Http\Controllers\API\V1\MonitorController;
use App\Http\Controllers\API\V1\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});

Route::get('password/reset', function(){
    return response()->json(['MSG' => 'Não Autorizado']);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});

//Route::prefix('v1')->group(function () {
Route::prefix('v1')->group(function () {
    // PROFILE
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::put('profile', [ProfileController::class, 'updateProfile']);
    Route::post('profile/avatar', [ProfileController::class, 'update_avatar']);
    Route::get('logs', [ProfileController::class, 'getLogs']);
    Route::post('change-password', [ProfileController::class, 'changePassword']);

    // DASHBOARD
    Route::get('dashboard', [DashboardController::class, 'getData']);

    // lOGS
    Route::get('log-activity', [MaintenanceController::class,'getActivityLog']);

    // TRAKING MONITOR
    Route::get('tracking-monitor', [MonitorController::class, 'trackingMonitor']);

    Route::apiResources([
        'monitor' => MonitorController::class,
    ]);
});
