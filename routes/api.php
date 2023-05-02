<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\ForgotPasswordController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::controller(ForgotPasswordController::class)->group(function () {
    // Route::get('forget-password', 'ResetPassword')->name('forget.password');
    Route::post('forget/password/request', 'ResetPasswordRequest')->name('forget.password.request');
    // Route::get('reset-password/{token}', 'ResetLoginPassword')->name('ResetPassword');
    Route::post('update-password', 'changePassword')->name('change.password');
  });

Route::post('sending-queue-emails', [UserController::class,'sendTestEmails']);

//USER ACCESS ALL ROUTES AFTER LOGGED IN
Route::middleware(['auth:api'])->group(function () {
    Route::resource('users', UserController::class);
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('roles', RoleController::class);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);   
});
