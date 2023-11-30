<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KultumController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Controllers\ProfilController;
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

Route::middleware('jadwal')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kultum/{kultum:id}', [KultumController::class, 'show'])->name('show.kultum');
    Route::middleware('signin')->group(function () {
        Route::get('/auth/logout', [SessionController::class, 'logout'])->name('logout');
    
        Route::middleware('admin')->group(function() {
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
            Route::get('penjadwalan/{id}/add-kultum', [KultumController::class, 'index'])->name('add.kultum');
            Route::post('penjadwalan/{id}/add-kultum', [KultumController::class, 'create'])->name('save.kultum');

            Route::post('/try-send-message', [PenjadwalanController::class, 'sendMessage'])->name('try.send.message');
            Route::get('/send-notification-alert-manually', [PenjadwalanController::class, 'sendNotificationManually'])->name('send.notification.alert.manually');
    
            Route::get('profil', [ProfilController::class, 'index'])->name('profil');
            Route::post('profil/update-username', [ProfilController::class, 'updateUsername'])->name('profil.username');
            Route::post('profil/update-password', [ProfilController::class, 'updatePassword'])->name('profil.password');
    
            Route::get('/laporan/download/', [LaporanController::class, 'download'])->name('laporan.download');
            Route::get('/laporan/show-pdf-format/', [LaporanController::class, 'showPdfView'])->name('laporan.pdf-format');
            Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        });
    });
    
    Route::get('/auth', [SessionController::class, 'index'])->name('index.login');
    Route::post('/auth/login', [SessionController::class, 'login'])->name('login');
    Route::post('/auth/forget', [SessionController::class, 'forget'])->name('forget');
});