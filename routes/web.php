<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\AsnController;
use App\Http\Controllers\JenisCutiController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\LogAktivitasController;

// --- ROUTE GUEST (HANYA UNTUK YANG BELUM LOGIN) ---
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ROUTE ASN (WAJIB LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/pengajuan', function () {
        $jenisCuti = \App\Models\JenisCuti::all();
        return view('form_pengajuan', compact('jenisCuti'));
    });

    Route::get('/pengajuan', [\App\Http\Controllers\CutiController::class, 'create']);
    Route::post('/pengajuan', [\App\Http\Controllers\CutiController::class, 'store']);

    Route::get('/pengajuan/{id}/batal', [\App\Http\Controllers\CutiController::class, 'batalkan']);

    Route::get('/profil', function () {
        return view('profil_asn');
    });

    // --- RUTE PROFIL ASN ---
    Route::get('/profil', [\App\Http\Controllers\DashboardController::class, 'profil']);
    Route::post('/profil/update', [\App\Http\Controllers\DashboardController::class, 'updateProfil']);
});

// --- ROUTE KHUSUS ADMIN (WAJIB LOGIN) ---
Route::middleware('auth')->prefix('admin')->group(function () {
    
    Route::get('/', [AdminController::class, 'index']);

    Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index']);
    Route::post('/admin/cuti/{id}/status', [\App\Http\Controllers\AdminController::class, 'updateStatus']);

    Route::get('/laporan/export', [AdminController::class, 'exportLaporan']);

    Route::post('/cuti/{id}/status', [AdminController::class, 'updateStatus']);

    Route::get('/asn', [AsnController::class, 'index']);
    Route::post('/asn', [AsnController::class, 'store']);
    Route::post('/asn/{id}/update', [AsnController::class, 'update']);
    Route::get('/asn/{id}/hapus', [AsnController::class, 'destroy']);

    Route::get('/atasan', [AtasanController::class, 'index']);
    Route::post('/atasan', [AtasanController::class, 'store']);
    Route::get('/atasan/{id}/hapus', [AtasanController::class, 'destroy']);

    Route::get('/cuti', [JenisCutiController::class, 'index']);

    Route::get('/libur', [HariLiburController::class, 'index']);
    Route::post('/libur', [HariLiburController::class, 'store']);
    Route::get('/libur/{id}/hapus', [HariLiburController::class, 'destroy']);

    Route::get('/log', [LogAktivitasController::class, 'index']);
    Route::get('/log/clear', [LogAktivitasController::class, 'clear']);
});