<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
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

Route::middleware('jadwal')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware(["signin"])->group(function () {
        Route::get('/auth/logout', [SessionController::class, 'logout'])->name('logout');
    
        Route::get('/account', [UserController::class, 'index'])->name('akun.index');
        Route::post('/account/create', [UserController::class, 'create'])->name('akun.create');
        Route::delete('/account/delete/{id}', [UserController::class, 'delete'])->name('akun.delete');
        Route::put('/account/{id}/update', [UserController::class, 'update'])->name('akun.update');
    
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::delete('/jadwal/delete/{id}', [JadwalController::class, 'delete'])->name('jadwal.delete');
        Route::put('/jadwal/{id}/update', [JadwalController::class, 'update'])->name('jadwal.update');
    
        Route::get('/penjadwalan', [PenjadwalanController::class, 'index'])->name('penjadwalan.index');
        Route::get('/penjadwalan/{id}', [PenjadwalanController::class, 'index'])->name('penjadwalan.jadwal');
        Route::post('/penjadwalan/create/{jadwal}', [PenjadwalanController::class, 'create'])->name('penjadwalan.create');
        Route::delete('/penjadwalan/delete/{id}/{jadwal}', [PenjadwalanController::class, 'delete'])->name('penjadwalan.delete');
        Route::put('/penjadwalan/{id}/update/{jadwal}', [PenjadwalanController::class, 'update'])->name('penjadwalan.update');
    });

    Route::get('/download-laporan', [LaporanController::class, 'download']);
    
    Route::get('/auth', [SessionController::class, 'index'])->name('index.login');
    Route::post('/auth/login', [SessionController::class, 'login'])->name('login');
    Route::post('/auth/forget', [SessionController::class, 'forget'])->name('forget');
});