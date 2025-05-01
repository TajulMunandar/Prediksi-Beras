<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAktualController;
use App\Http\Controllers\DataBerasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('/dashboard')->group(function () {
    Route::resource('/', DashboardController::class);
    Route::resource('/user', UserController::class);
    Route::post('/users/{id}/update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::resource('/prediksi', PrediksiController::class);
    Route::resource('/laporan', LaporanController::class);
    Route::resource('/data-aktual', DataAktualController::class);
    Route::post('/data-aktual/import', [DataAktualController::class, 'import'])->name('data-aktual.import');
    Route::resource('/data-beras', DataBerasController::class);
})->middleware(['auth']);

Route::get('/', function () {
    return redirect()->route('login');
});
