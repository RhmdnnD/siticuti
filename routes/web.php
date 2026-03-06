<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\AsnController;
use App\Http\Controllers\JenisCutiController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\LogAktivitasController;

// Memanggil Middleware yang baru kita buat
use App\Http\Middleware\AdminMiddleware; 

// =========================================================
// 1. ROUTE GUEST (HANYA UNTUK YANG BELUM LOGIN)
// =========================================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// ROUTE LOGOUT
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// =========================================================
// 2. ROUTE GLOBAL (WAJIB LOGIN)
// =========================================================
Route::middleware('auth')->group(function () {

    // -----------------------------------------------------
    // A. AREA KHUSUS ASN (PEGAWAI)
    // -----------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profil
    Route::get('/profil', [DashboardController::class, 'profil']);
    Route::post('/profil/update', [DashboardController::class, 'updateProfil']);

    // Pengajuan Cuti
    Route::get('/pengajuan', [CutiController::class, 'create']);
    Route::post('/pengajuan', [CutiController::class, 'store']);
    Route::get('/pengajuan/{id}/batal', [CutiController::class, 'batalkan']);


    // -----------------------------------------------------
    // B. AREA KHUSUS ADMIN (HRD)
    // -----------------------------------------------------
    // Memasang gembok khusus AdminMiddleware
    Route::middleware(AdminMiddleware::class)->prefix('admin')->group(function () {

        // Dashboard Admin & Aksi
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/cuti/{id}/status', [AdminController::class, 'updateStatus']);
        Route::get('/laporan/export', [AdminController::class, 'exportLaporan']);

        Route::post('/cuti/{id}/batal-setuju', [AdminController::class, 'batalkanPersetujuan']);

        // Manajemen ASN
        Route::get('/asn', [AsnController::class, 'index']);
        Route::post('/asn', [AsnController::class, 'store']);
        Route::post('/asn/{id}/update', [AsnController::class, 'update']);
        Route::get('/asn/{id}/hapus', [AsnController::class, 'destroy']);

        // Manajemen Atasan
        Route::get('/atasan', [AtasanController::class, 'index']);
        Route::post('/atasan', [AtasanController::class, 'store']);
        Route::get('/atasan/{id}/hapus', [AtasanController::class, 'destroy']);

        // Manajemen Jenis Cuti
        Route::get('/cuti', [JenisCutiController::class, 'index']);

        // Manajemen Hari Libur
        Route::get('/libur', [HariLiburController::class, 'index']);
        Route::post('/libur', [HariLiburController::class, 'store']);
        Route::get('/libur/{id}/hapus', [HariLiburController::class, 'destroy']);

        // Log Aktivitas
        Route::get('/log', [LogAktivitasController::class, 'index']);
        Route::post('/log/clear', [LogAktivitasController::class, 'clear']);
        Route::post('/log/autoclean', [LogAktivitasController::class, 'autoClean']);
        
    });

});