<?php

use App\Models\TitipKunci;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\EmailController;
use App\Http\Controllers\RawatInapController;
use App\Http\Controllers\RekapDataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TitipKunciController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;


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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

// // Change the name of this route to avoid conflicts
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserProfileController::class, 'getAllUsers']);
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::post('/user/profile', [UserProfileController::class, 'update']);
    Route::put('/users/{userId}/change-role', [UserProfileController::class, 'changeUserRole'])->middleware('auth:sanctum');  
    Route::delete('/users/{userId}', [UserProfileController::class, 'deleteUser'])->middleware('auth:sanctum');  
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('/users', [UserProfileController::class, 'getAllUsers']);
        Route::get('/user/profile', [UserProfileController::class, 'show']);
        Route::post('/user/profile', [UserProfileController::class, 'update']);
        Route::put('/users/{userId}/change-role', [UserProfileController::class, 'changeUserRole']);
        Route::delete('/users/{userId}', [UserProfileController::class, 'deleteUser']);
    });
    Route::apiResource('rawat-inap', RawatInapController::class);
    Route::apiResource('rekap-data', RekapDataController::class);
    Route::apiResource('titip-kunci', TitipKunciController::class);
    Route::get('/refresh-token', [AuthController::class, 'refreshToken']);
});