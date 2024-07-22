<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RawatInapController;
use App\Http\Controllers\RekapDataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TitipKunciController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Remove the email verification routes from Auth::routes
Auth::routes(['verify' => false]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('rawat-inap', RawatInapController::class);
    Route::resource('titip-kunci', TitipKunciController::class);
    
    Route::get('/rawat-inap', [RawatInapController::class, 'index'])->name('rawat-inap.index');
    Route::get('/titip-kunci', [TitipKunciController::class, 'index'])->name('titip-kunci.index');
    Route::get('/rekap-data', [RekapDataController::class, 'index'])->name('rekap-data.index');
    
    Route::delete('/rawatinap/{id}', [RawatInapController::class, 'destroy'])->name('rawatinap.hapus');
    
    Route::delete('/titipkunci/{id}', [TitipKunciController::class, 'destroy'])->name('titipkunci.hapus');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
});

// Add the email verification routes here
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');