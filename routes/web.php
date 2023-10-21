<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(["signin"])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/auth/logout', [SessionController::class, 'logout'])->name('logout');

    Route::get('/account', [UserController::class, 'index'])->name('akun.index');
    Route::post('/account/create', [UserController::class, 'create'])->name('akun.create');
    Route::delete('/account/delete/{id}', [UserController::class, 'delete'])->name('akun.delete');
    Route::put('/account/{id}/update', [UserController::class, 'update'])->name('akun.update');
});

Route::get('/auth', [SessionController::class, 'index']);
Route::post('/auth/login', [SessionController::class, 'login'])->name('login');
Route::post('/auth/forget', [SessionController::class, 'forget'])->name('forget');